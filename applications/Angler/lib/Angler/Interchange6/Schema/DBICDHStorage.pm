package Angler::Interchange6::Schema::DBICDHStorage;

# the following is necessary for some setups
use Angler::Interchange6::Schema::Result::DBICDHStorageResult;
 
use Moose;
extends 'DBIx::Class::DeploymentHandler::VersionStorage::Standard';

sub _build_version_rs {
  $_[0]->schema->register_class(
    __VERSION =>
      'Angler::Interchange6::Schema::Result::DBICDHStorageResult'
  );
  $_[0]->schema->resultset('__VERSION')
}

no Moose;
__PACKAGE__->meta->make_immutable;

1;
