 use Error qw(:try);
 use RT::Client::REST;
 use RT::Client::REST::Ticket;

my $user='sam@westbranchresort.com';
my $pass='mayfly6969';

  my $rt = RT::Client::REST->new(
    server => 'http://rt.westbranchresort.com',
    timeout => 30,
);

  try {
    $rt->login(username => $user, password => $pass);
  } catch Exception::Class::Base with {
    die "problem logging in: ", shift->message;
  };


  # Create a new ticket:
  my $ticket = RT::Client::REST::Ticket->new(
    rt => $rt,
    queue => "General",
    subject => "This is my remote user test",
  )->store(text => "This is the initial text of the ticket");
  print "Created a new ticket, ID ", $ticket->id, "\n";

