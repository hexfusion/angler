<?php
# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: block.mtifmoreresults.php 2103 2008-04-25 11:36:53Z fumiakiy $

function smarty_block_mtifmoreresults($args, $content, &$ctx, &$repeat) {
    if (!isset($content)) {
        $limit = $ctx->stash('__pager_limit');
        $count = $ctx->stash('__pager_total_count');
        $offset = $ctx->stash('__pager_offset');
        if ( $limit && !$offset ) $offset += $limit;
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat, ( $limit + $offset ) < $count);
    } else {
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat);
    }
}
?>
