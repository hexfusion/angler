<?php
# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mttotalpages.php 2103 2008-04-25 11:36:53Z fumiakiy $

function smarty_function_mttotalpages($args, &$ctx) {
    $limit = $ctx->stash('__pager_limit');
    if (!$limit) return 1;
    $offset = $ctx->stash('__pager_offset');
    ceil( $count / $limit );
}
?>

