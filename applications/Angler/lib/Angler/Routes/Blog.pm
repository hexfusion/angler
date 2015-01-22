package Angler::Routes::Blog;

use Dancer ':syntax';
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

get '/blog/:article' => sub {
    my %tokens;
    my $form = form('search');
    my $article = params->{article};

    my $blog = $schema->resultset('Message')->find(
        {
          uri => $article,
        }
    );

    unless ($blog) {
        return template 'blog/content';
    }

    $tokens{blog} = $blog;
    $tokens{form} = $form;

    template 'blog/view', \%tokens;
};
