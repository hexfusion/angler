<?php
# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcommentscoreavg.php 2786 2008-07-16 02:06:07Z fumiakiy $

require_once('rating_lib.php');

function smarty_function_mtcommentscoreavg($args, &$ctx) {
    return hdlr_score_avg($ctx, 'comment', $args['namespace'], $args);
}
?>
