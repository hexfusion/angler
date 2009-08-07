<?php
# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtusersessioncookietimeout.php 2239 2008-05-06 20:08:00Z bchoate $

function smarty_function_mtusersessioncookietimeout($args, &$ctx) {
    return $ctx->mt->config('UserSessionCookieTimeout');
}
