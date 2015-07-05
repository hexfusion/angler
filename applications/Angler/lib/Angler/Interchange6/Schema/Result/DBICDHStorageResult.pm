package Angler::Interchange6::Schema::Result::DBICDHStorageResult;

use parent 'DBIx::Class::DeploymentHandler::VersionStorage::Standard::VersionResult';

 __PACKAGE__->table('fl_bench_journal_versions');

1;
