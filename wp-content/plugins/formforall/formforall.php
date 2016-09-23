<?php
/*
Plugin Name: FormForAll
Plugin URI: http://www.formforall.com/
Description: FormForAll plugin allows you to insert the best forms directly coming from the FormForAll API.
Version: 1.2
Author: FormForAll
Author URI: http://www.formforall.com/
*/

/*
    FormForAll plugin allows you to insert the best forms directly coming from the FormForAll API.
    Copyright (C) 2013 FormForAll (http://www.formforall.com/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

require_once('formforall_common.php');



/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'formforall_install'); 

/* Runs on plugin deactivation */
register_deactivation_hook(__FILE__, 'formforall_remove');


if (is_admin()) {
    require_once('formforall_admin.php');
    
} else {
    require_once('formforall_front.php');
}
?>
