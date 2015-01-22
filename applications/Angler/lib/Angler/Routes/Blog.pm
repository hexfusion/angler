package Angler::Routes::Blog;

use Dancer ':syntax';
#use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;

my $schema = shop_schema;

get '/blog' => sub {
    my %tokens;
    my $form = form('search');

    my $blog = $schema->resultset('MessageType')->search_related('messages',
        {
          name => 'blog_post',
          approved => '1',
          public => '1',
        }
    );

    $tokens{blog} = $blog;
    $tokens{form} = $form;

    $tokens{"extra-js-file"} = 'blog-page.js';
    template 'blog/content', \%tokens;
};
