package Angler::Atandra;

require Exporter;

@ISA = qw(Exporter);
@EXPORT = qw (process_cgi_call);

use strict;
use warnings;
use Angler::Config use_libs => 1;
use DBI;
use MIME::Base64;
use XML::Simple;

my $master_userid = 'thub_angler';
my $master_password = 'enter_password';

my $debug = 1;

# Set to 1 if all customers should be imported into the same record
# Set to 0 if unique customers should be imported
my $opt_one_customer_import = 1; 
#my $opt_one_customer_import = 0; 

my $header_ok = <<EOF;
Status: 200 OK
Content-type: text/xml
EOF

my $xml_header = <<EOF;
<?xml version="1.0" encoding="ISO-8859-1"?>
EOF

my $response_header = <<EOF;
<RESPONSE Version="2.8">
EOF

sub process_input {
	my ($input) = @_;

	my $xml = XML::Simple->new()->XMLin($input, ForceArray => ['Order', 'Item']);

	return $xml;
}

sub get_dbh {
	return Angler::Config->dbh('westbranchangler_com', undef);
}

sub get_element_xml {
	my ($map, $database_value, $element_name_override) = @_;
	my $element_xml;
	my $name = '';
	my $extra = '';

	$database_value = $database_value || '';

	if ($element_name_override) {
		$name = ' Name="' . $element_name_override . '"';
	} elsif ($map->{element_name}) {
		$name = ' Name="' . $map->{element_name} . '"';
	}

	if ($map->{default}) {
		if ($database_value eq '') {
			$database_value = $map->{default};
		}
	}

	if ($map->{base64}) {
		$database_value = encode_base64($database_value, '');
		$extra = ' encoding="yes"';
	}

	return <<EOF;
			<$map->{element}$name$extra>$database_value</$map->{element}>
EOF
}

sub get_coupon_xml {
	my ($map, $database_value) = @_;
	my $element_xml;

	if (!$database_value) {
		return '';
	}

	my @pairs = split(',', $database_value);

	foreach my $pair (@pairs) {
		my ($name, $value) = split('=', $pair);
		$name =~ s/^\s+//;
		$name =~ s/\s+$//;
		$value =~ s/^\s+//;
		$value =~ s/\s+$//;

		$element_xml .= <<EOF;
			<Coupon>
				<CouponCode>$name</CouponCode>
				<CouponID>$name</CouponID>
				<CouponCode>$name</CouponCode>
				<CouponValue>$value</CouponValue>
			</Coupon>
EOF
	}

	if ($element_xml) {
		$element_xml = <<EOF;
		<Coupons>
$element_xml
		</Coupons>
EOF
	}

	return $element_xml;
}


sub get_orders {
	my ($input) = @_;

	my $xml = '';
	my $dbh = get_dbh();

	my $number_of_days = $input->{NumberOfDays} || 1;
	my $last_order_number = $dbh->quote($input->{OrderStartNumber});
	my $limit = $input->{LimitOrderCount} || '25';

	my $order_field_map = {
		order_number => { element => 'OrderID', table => 'transactions', group => 'Order'},
		order_number_2 => { element => 'ProviderOrderRef', table => 'transactions', field => 'order_number', group => 'Order'},
		order_date	=> { element => 'Date', group => 'Order', cast => 'date'},
		username => { element => 'CustomerID', group => 'Order', table => 'transactions', field => 'username' },
		special_instructions => { element => 'Comment', group => 'Order'},
		order_site => { element => 'CustomField1', group => 'Order', table => 'transactions', field => 'site'},

		fname => { element => 'FirstName', group => 'Ship', base64 => 1 },
		lname => { element => 'LastName', group => 'Ship', base64 => 1 },
		address1 => { element => 'Address1', group => 'Ship', base64 => 1 },
		address2 => { element => 'Address2', group => 'Ship', base64 => 1 },
		city => { element => 'City', group => 'Ship', base64 => 1 },
		state => { element => 'State', group => 'Ship', base64 => 1 },
		zip => { element => 'Zip', group => 'Ship'},
		country => { element => 'Country', group => 'Ship'},
		phone => { element => 'Phone', group => 'Ship'},
		company => { element => 'CompanyName', group => 'Ship', base64 => 1, default => '-' },
		shipping_type => { element => 'ShipCarrierName', group => 'Ship'},
		shipping_description => { element => 'ShipMethod', group => 'Ship'},

		email => { element => 'Email', group => 'Bill', base64 => 1},
		b_fname => { element => 'FirstName', group => 'Bill', base64 => 1 },
		b_lname => { element => 'LastName', group => 'Bill', base64 => 1 },
		b_address1 => { element => 'Address1', group => 'Bill', base64 => 1 },
		b_address2 => { element => 'Address2', group => 'Bill', base64 => 1 },
		b_city => { element => 'City', group => 'Bill', base64 => 1 },
		b_state => { element => 'State', group => 'Bill', base64 => 1 },
		b_zip => { element => 'Zip', group => 'Bill'},
		b_country => { element => 'Country', group => 'Bill'},
		b_phone => { element => 'Phone', group => 'Bill'},
		b_company => { element => 'CompanyName', group => 'Bill', base64 => 1, default => '-' },

		card_partial => { element => '', group => 'CreditCard'},
		exp_month => { element => '', group => 'CreditCard' },
		exp_year => { element => '', group => 'CreditCard' },
		type => { element => '', group => 'CreditCard' },

		order_subtotal => { element => 'Subotal', group => '', field => 'subtotal', table => 'transactions'},
		shipping => { element => 'Shipping', group => 'Charges'},
		handling => { element => 'Handling', group => 'Charges'},
		salestax => { element => 'Tax', group => 'Charges', element_name => 'TAXES'},
		total_cost => { element => 'Total', group => 'Charges'},
		discount_total => { element => 'Discount', group => 'Charges'},

		variant_sku => { element => 'ItemCode', group => 'Item'},
		product_sku => { element => 'ItemCode', group => ''},
		title => { element => 'ItemDescription', group => 'Item', base64 => 1},
		quantity => { element => 'Quantity', group => 'Item'},
		price => { element => 'UnitPrice', group => 'Item'},
		orderline_subtotal => { element => 'ItemTotal', table => 'orderline', field => 'subtotal',  group => 'Item'},

		discounts_used => { element => 'CouponCode', group => 'Coupon' },
	};

	my $sql = <<EOF;
SELECT 
EOF

	my $sql_select_fields;
	my @select_fields;
	foreach my $key (keys %$order_field_map) {
		my $field = $key;
		if ($order_field_map->{"$key"}->{table}) {
			if ($order_field_map->{"$key"}->{field}) {
				$field = $order_field_map->{"$key"}->{table} . "." . $order_field_map->{"$key"}->{field} . " AS " . $key;
			} else {
				$field = $order_field_map->{"$key"}->{table} . "." . $key;
			}
		}

		if ($order_field_map->{"$key"}->{cast}) {
			$field .= "::" . $order_field_map->{"$key"}->{cast};
		}

		push @select_fields, $field;
	}

	$sql_select_fields = join(', ', @select_fields);

	$sql .= $sql_select_fields;

	$sql .=<<EOF;	

FROM transactions
LEFT JOIN payments
	ON transactions.order_number = payments.order_number
LEFT JOIN creditcards
	ON payments.creditcards_id = creditcards.creditcards_id
LEFT JOIN orderline
	ON transactions.order_number = orderline.order_number
WHERE transactions.status <> 'canceled'
EOF
	if ($input->{OrderStartNumber}) {
		$sql .= <<EOF;
			AND transactions.order_number > $last_order_number
EOF
	} else {
		$sql .= <<EOF;
			AND (transactions.order_date::date >= (NOW() - INTERVAL '$number_of_days days')::date) 
EOF
	}

	$sql .= <<EOF;
ORDER BY transactions.order_number ASC
LIMIT $limit
EOF

#debug_file($sql);

	my $sth = $dbh->prepare($sql);

	$sth->execute();

	my $valid_record = 0;
	my $current_order_number = 0;
	my $order = '';
	my $bill = '';
	my $ship = '';
	my $cc = '';
	my $charges = '';
	my $coupons = '';
	my $order_lines = '';

	while (my $row = $sth->fetchrow_hashref) {
		$valid_record++;
		if ($current_order_number ne $row->{order_number}) {
			if ($order && $order_lines) {
				$xml .= <<EOF;
	<Order>
$order
		<Bill>
$bill
			<CreditCard>
$cc
			</CreditCard>
		</Bill>
		<Ship>
$ship
		</Ship>
		<Items>
$order_lines
		</Items>
		<Charges>
$charges
		</Charges>
$coupons
	</Order>
EOF
			}

			$current_order_number = $row->{order_number};
			
			$order = '';
			$bill = '';
			$ship = '';
			$cc = '';
			$charges = '';
			$coupons = '';

			my $credit_card = $row->{card_partial};
			if ($credit_card =~ m/\d{4}$/) {
				$credit_card = $&;
			}

			$cc .= <<EOF;
				<CreditCardType>$row->{type}</CreditCardType>
				<CreditCardCharge>$row->{total_cost}</CreditCardCharge>
				<ExpirationDate>$row->{exp_month}/$row->{exp_year}</ExpirationDate>
				<CreditCardName>$row->{b_fname} $row->{b_lname}</CreditCardName>
				<CreditCardNumber>$credit_card</CreditCardNumber>
EOF

			foreach my $key (keys %$order_field_map) {
				if ($order_field_map->{"$key"}->{group} eq 'Order') {
					$order .= get_element_xml($order_field_map->{"$key"}, $row->{"$key"});
				} elsif ($order_field_map->{"$key"}->{group} eq 'Bill') {
					if ($key eq 'email' && $opt_one_customer_import == 1) {
						$bill .= get_element_xml($order_field_map->{"$key"}, 'info@westbranchangler.com');
					} else {
						$bill .= get_element_xml($order_field_map->{"$key"}, $row->{"$key"});
					}
				} elsif ($order_field_map->{"$key"}->{group} eq 'Ship') {
					$ship .= get_element_xml($order_field_map->{"$key"}, $row->{"$key"});
				} elsif ($order_field_map->{"$key"}->{group} eq 'Charges') {
						my $element_name_override = '';
						if ($key eq 'salestax') {
							$element_name_override = compute_tax_location($row->{order_subtotal}, $row->{salestax});
						}
						$charges .= get_element_xml($order_field_map->{"$key"}, $row->{"$key"}, $element_name_override);
				} elsif ($order_field_map->{"$key"}->{group} eq 'Coupon') {
					$coupons .= get_coupon_xml($order_field_map->{"$key"}, $row->{"$key"});
				}
			}

			$order_lines = '';
		}

		$order_lines .= <<EOF;
			<Item>
EOF

		foreach my $key (keys %$order_field_map) {
			if ($order_field_map->{"$key"}->{group} eq 'Item') {
				if ($key eq 'variant_sku' && !$row->{'variant_sku'}) {
					$order_lines .= "\t" . get_element_xml($order_field_map->{"$key"}, $row->{'product_sku'});
				} else {
					$order_lines .= "\t" . get_element_xml($order_field_map->{"$key"}, $row->{"$key"});
				}
			}
		}

		$order_lines .= <<EOF;
			</Item>
EOF
		
	}

	if ($order && $order_lines) {
		$xml .= <<EOF;
	<Order>
$order
		<Bill>
$bill
			<CreditCard>
$cc
			</CreditCard>
		</Bill>
		<Ship>
$ship
		</Ship>
		<Items>
$order_lines
		</Items>
		<Charges>
$charges
		</Charges>
$coupons
	</Order>
EOF
	}


	if ($valid_record) {
		$xml = <<EOF;
<Orders>
$xml
</Orders>
EOF
		$xml = envelope_xml('0', 'All Ok', 'GetOrders') . $xml;
	} else {
		$xml = envelope_xml('1000', 'No Orders returned', 'GetOrders');
	}

debug_file($xml);
	return $xml;
}

sub compute_tax_location {
	my ($subtotal, $salestax) = @_;

	if ($salestax > 0) {
		my $percentage = $salestax / $subtotal;

		if ($percentage == 0.09) {
			return 'TAXES';
		}
		return 'RESALE';
	} else {
		return 'TAXES';
	}
}

sub send_response {
	my ($content) = @_;
	my $length = length($content);

	print <<EOF;
Status: 200 OK
Content-type: text/html
Content-length: $length

$content
EOF

	return;
}

sub send_response_xml {
	my ($content) = @_;
	$content = <<EOF;
$xml_header
$response_header
$content
</RESPONSE>
EOF

	my $length = length($content);

	print <<EOF;
Status: 200 OK
Content-type: text/xml
Content-length: $length

$content
EOF

	return;
}

sub envelope_xml {
	my ($code, $message, $command, $hide_provider) = @_;

	$hide_provider = $hide_provider || 0;

	my $provider;

	if ($hide_provider == 1) {
		$provider = "";
	} else {
		$provider = "\n		<Provider>GENERIC</Provider>";
	}

	my $xml = <<EOF;
	<Envelope>
		<Command>$command</Command>
		<StatusCode>$code</StatusCode>
		<StatusMessage>$message</StatusMessage>$provider
	</Envelope>
EOF

	return $xml;
}

sub insert_tracking_number {
	my ($order_number, $tracking_number) = @_;

	if (!$order_number || !$tracking_number) {
		return 1;
	}

	my $dbh = get_dbh();

	my $sql;

	my $q_order_number = $dbh->quote($order_number);
	my $q_tracking_number = $dbh->quote($tracking_number);

	$sql = <<EOF;
		SELECT count(orderline_number) AS count_line
		FROM orderline
		WHERE (status = 'packing' OR status = 'packed') AND order_number = $q_order_number
EOF

	my $sth = $dbh->prepare($sql);

	if ($sth) {
		$sth->execute();
		my $row = $sth->fetchrow_hashref();
		if ($row->{count_line}) {
			
		} else {
			return 1;
		}
	} else {
		return 1;
	}


	$sql = <<EOF;
		INSERT INTO worldship_tracking VALUES ($q_order_number, $q_tracking_number, '1', 'F');
EOF

	$sth = $dbh->prepare($sql);

	if ($sth) {
		$sth->execute();
		return 0;
	} else {
		return 1;
	}
}

sub lookup_variant {
	my ($sku) = @_;

	my $valid_variant = 0;
	my $parent_sku = '';

	my $dbh = get_dbh();

	my $sql;

	my $q_sku = $dbh->quote($sku);

	$sql = <<EOF;
		SELECT count(code) AS count_sku, sku AS parent_sku
		FROM variants
		WHERE code = $q_sku
		GROUP BY parent_sku
EOF

	my $sth = $dbh->prepare($sql);

	if ($sth) {
		$sth->execute();
		while (my $row = $sth->fetchrow_hashref()) {
			if ($row->{count_sku} > 0) {
				$valid_variant = 1;
				$parent_sku = $row->{sku};
			}
		}
	}

	return { 
			valid => $valid_variant,
			parent_sku => $parent_sku,
		};
}

sub lookup_product {
	my ($sku) = @_;

	my $valid_sku = 0;

	my $dbh = get_dbh();

	my $sql;

	my $q_sku = $dbh->quote($sku);

	$sql = <<EOF;
		SELECT count(sku) AS count_sku
		FROM products
		WHERE sku = $q_sku
EOF

	my $sth = $dbh->prepare($sql);

	if ($sth) {
		$sth->execute();
		while (my $row = $sth->fetchrow_hashref()) {
			if ($row->{count_sku} > 0) {
				$valid_sku = 1;
			}
		}
	}

	return $valid_sku;
}

sub update_product_record {
	my ($type, $sku, $quantity, $update_inventory, $price, $sale_price, $update_pricing) = @_;

	my $result = 0;

	my $dbh = get_dbh();

	my $sql;

	my $q_sku = $dbh->quote($sku);

	$price = $price || 0;
	$sale_price = $sale_price || 0;

	my @update_statements;

	if ($type eq 'variant') {
		$sql = <<EOF;
	UPDATE variants
	SET 
EOF

	} else {
		$sql = <<EOF;
	UPDATE products
	SET 
EOF
	}

	if ($update_inventory) {
		push @update_statements, "inventory = $quantity"
	}

	if ($update_pricing) {
		if ($price > 0) {
			push @update_statements, "price = $price"
		}
		if ($sale_price > 0) {
			push @update_statements, "sale_price = $sale_price"
		}
	}

	$sql .= join(', ', @update_statements);

	if ($type eq 'variant') {
		$sql .= " WHERE code = $q_sku"
	} else {
		$sql .= " WHERE sku = $q_sku"
	}

	my $sth = $dbh->prepare($sql);

	if ($sth) {
		$sth->execute();
	}

	return 0;
}

sub update_product_inventory {
	my ($item, $update_inventory, $update_pricing) = @_;

	my $results = 0;

	if (!$item->{ItemCode} || (!$update_inventory && !$update_pricing)) {
		return 1;
	}

	my $variant_results = lookup_variant($item->{ItemCode});

	if ($variant_results->{valid} == 1) {
		update_product_record('variant', $item->{ItemCode}, $item->{QuantityInStock}, $update_inventory, $item->{Price}, $item->{SalePrice}, $update_pricing);
	} else {
		my $product_results = lookup_product($item->{ItemCode});

		if ($product_results == 1) {
			update_product_record('product', $item->{ItemCode}, $item->{QuantityInStock}, $update_inventory, $item->{Price}, $item->{SalePrice}, $update_pricing);
		} else {
			$results = 1;
		}
	}

	return $results;
}

sub update_shipping_status {
	my ($input_xml) = @_;

	my $xml = '';

	my $valid_record = 0;

	my $orders = $input_xml->{Orders}->{Order};

	foreach my $order (@$orders) {
		my $results = insert_tracking_number($order->{HostOrderID}, $order->{TrackingNumber});
		$xml .= <<EOF;
		<Order>
			<HostOrderID>$order->{HostOrderID}</HostOrderID>
			<LocalOrderID>$order->{LocalOrderID}</LocalOrderID>
EOF

		if ($results == 1) {
			$xml .= <<EOF;
			<HostStatus>Failed</HostStatus>
		</Order>
EOF
		} else {
			$valid_record = 1;
			$xml .= <<EOF;
			<HostStatus>Success</HostStatus>
		</Order>
EOF
		}
	}

	$xml = <<EOF;
	<Orders>
$xml
	</Orders>
EOF

	if ($valid_record == 1) {
		$xml = envelope_xml('0', 'All Ok', 'UpdateShippingStatus', 1) . $xml;
	} else {
		$xml = envelope_xml('1000', 'No Orders returned', 'UpdateShippingStatus', 1) . $xml;
	}

	return $xml;	
}

sub update_inventory {
	my ($input_xml) = @_;

	my $update_pricing = $input_xml->{UpdatePrice} || 0;
	my $update_inventory = $input_xml->{UpdateInventory} || 0;

	my $add_products = $input_xml->{AddProducts} || 0;

	my $xml = '';

	my $items = $input_xml->{Items}->{Item};

	foreach my $item (@$items) {
		my $results;

		if ($add_products == 1) {
#			$results = Add code here that will insert item into your database
#						or will update the existing record if it already exists
		} else {
			$results = update_product_inventory($item, $update_inventory, $update_pricing);
		}
		$xml .= <<EOF;
		<Item>
			<ItemCode>$item->{ItemCode}</ItemCode>
			<QuantityInStock>$item->{QuantityInStock}</QuantityInStock>
EOF

		$xml .= <<EOF;
			<InventoryUpdateStatus>$results</InventoryUpdateStatus>
		</Item>
EOF
	}

	$xml = <<EOF;
	<Items>
$xml
	</Items>
EOF

	$xml = envelope_xml('0', 'All Ok', 'UpdateInventory', 0) . $xml;

	return $xml;	
}

sub error_xml {
	my ($code, $message, $command) = @_;

	my $env_xml = envelope_xml($code, $message, $command);

	my $response_xml = <<EOF;

$xml_header
$response_header
$env_xml
</RESPONSE>
EOF

	return $response_xml;
}

sub http_error {
	my ($code, $message) = @_;
	print <<EOF;
Status: $code $message
Content-type: text/html

<title>$code $message</title>
<h1>$code $message</h1>
<p>Unexpected error processing XML request.</p>
EOF
	exit 0;
}

sub debug_file {
	my ($message) = @_;

	if (!$debug) {
		return;
	}

	my $filename = Angler::Config->variable('westbranchangler_com', 'HOME_DIR') . "/tmp/atanda_log.txt";

	open (TMP, ">>$filename");
	print TMP $message . "\n\n";
	close (TMP);

	return;
}

sub process_cgi_call {
	debug_file("-------------------------------");
	my $post_method = $ENV{'REQUEST_METHOD'};
	my $post_type = $ENV{'CONTENT_TYPE'};
	my $post_length = $ENV{'CONTENT_LENGTH'};

	my $query_string = $ENV{'QUERY_STRING'};

=cut
debug_file('Method: ' . $post_method);
debug_file('Type: ' . $post_type);
debug_file('Length: ' . $post_length);
debug_file('Query: ' . $query_string);
=cut

	http_error(405, "Method Not Allowed") unless $post_method eq "POST";
=cut
	http_error(400, "Bad Request") unless $post_type eq "text/xml";
=cut
	http_error(411, "Length Required") unless $post_length > 0;

	my $body;

	my $bytes_read = read \*STDIN, $body, $post_length;

	$body =~ s/request\=//g;
	$body =~ s/\%([A-Fa-f0-9]{2})/pack('C', hex($1))/seg;
	$body =~ s/\+/ /g;

	debug_file($body);

debug_file("-------------------------------");

	my $input_xml = process_input($body);

=cut
	foreach my $key (keys %$input_xml) {
		print $key;
	}
=cut

	my $command = $input_xml->{Command};
	my $userid = $input_xml->{UserID};
	my $password = $input_xml->{Password};
	my $output_xml;

	if (($master_userid eq $userid ) && ($master_password eq $password)) {

		if ($command eq 'GetOrders') {
			$output_xml = get_orders($input_xml);
		} elsif ($command eq 'UpdateOrdersPaymentStatus') {
			$output_xml = update_payment_status($input_xml);
		} elsif ($command eq 'UpdateOrdersShippingStatus') {
			$output_xml = update_shipping_status($input_xml);
		} elsif ($command eq 'UpdateInventory') {
			$output_xml = update_inventory($input_xml);
		} else {
			$output_xml = envelope_xml('9999', 'Invalid command', $command);
		}
		
	} else {
		$output_xml = envelope_xml('9000', 'Login credentials are invalid', $command);
	}

	send_response_xml($output_xml);

	return;
}

1;
