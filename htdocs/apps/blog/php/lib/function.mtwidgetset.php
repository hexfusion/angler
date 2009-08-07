<?php
# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtwidgetset.php 2091 2008-04-25 01:26:16Z fumiakiy $

require_once("function.mtwidgetmanager.php");
function smarty_function_mtwidgetset($args, &$ctx) {
    return smarty_function_mtwidgetmanager($args, $ctx);
}
?>

