package Angler::Interchange6::Schema::DeploymentHandler;

use Moose;

use Angler::Interchange6::Schema::DBICDHStorage;

extends 'DBIx::Class::DeploymentHandler::Dad';

# a single with would be better, but we can't do that
# see: http://rt.cpan.org/Public/Bug/Display.html?id=46347
with 'DBIx::Class::DeploymentHandler::WithApplicatorDumple' => {
    interface_role       => 'DBIx::Class::DeploymentHandler::HandlesDeploy',
    class_name           => 'DBIx::Class::DeploymentHandler::DeployMethod::SQL::Translator',
    delegate_name        => 'deploy_method',
    attributes_to_assume => ['schema'],
    attributes_to_copy   => [qw( databases script_directory sql_translator_args )],
  },
   'DBIx::Class::DeploymentHandler::WithApplicatorDumple' => {
     interface_role       => 'DBIx::Class::DeploymentHandler::HandlesVersioning',
     class_name           => 'DBIx::Class::DeploymentHandler::VersionHandler::Monotonic',
     delegate_name        => 'version_handler',
     attributes_to_assume => [qw( database_version schema_version to_version )],
  },
   'DBIx::Class::DeploymentHandler::WithApplicatorDumple' => {
     interface_role       => 'DBIx::Class::DeploymentHandler::HandlesVersionStorage',
     class_name           => 'Angler::Interchange6::Schema::DBICDHStorage',
     delegate_name        => 'version_storage',
     attributes_to_assume => ['schema'],
   };

with 'DBIx::Class::DeploymentHandler::WithReasonableDefaults';

sub prepare_version_storage_install {
    my $self = shift;

    $self->prepare_resultsource_install({
       result_source => $self->version_storage->version_rs->result_source
    });
}

sub install_version_storage {
    my $self = shift;

    my $version = (shift || {})->{version} || $self->schema_version;
    $self->install_resultsource({
        result_source => $self->version_storage->version_rs->result_source,
        version       => $version,
    });
}

sub prepare_install {
    $_[0]->prepare_deploy;
    $_[0]->prepare_version_storage_install;
}

no Moose;

__PACKAGE__->meta->make_immutable;

1;
