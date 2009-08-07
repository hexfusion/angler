<?php
# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcommentername.php 2685 2008-07-03 00:38:40Z bchoate $

function smarty_function_mtcommentername($args, &$ctx) {
    $a =& $ctx->stash('commenter');
    $name = isset($a) ? $a['author_nickname'] : '';
    if ($name == '') {
        $name = $ctx->tag('CommentName');
    }
    return $name;
}
