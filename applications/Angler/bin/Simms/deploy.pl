use utf8;
use Dancer ':syntax';

use Dancer::Plugin::DBIC qw(schema resultset rset);
use Database::Schema;
use YAML qw/LoadFile/;

# Variables
my $schema = Database::Schema->connect('dbi:mysql:dbname=simms;host=localhost;port=3306', 'root', 'toor', {mysql_enable_utf8 => 1});

# Deploy
$schema->deploy({ 
#	add_drop_table => 1
});
print "Deployed";


1;
