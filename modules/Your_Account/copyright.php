<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/*********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ============================================                                 */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/*********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion    */
/*********************************************************************************/

define('CP_INCLUDE_DIR', dirname(dirname(dirname(__FILE__))));
require_once(CP_INCLUDE_DIR.'/includes/showcp.php');

$nuke_author_email        = "";
$nuke_author_homepage    = "http://dev.phpnuke.org.br";
$nuke_author_name        = "<a href=\"$nuke_author_homepage\">Comunidade PHP Nuke Brasil</a>";
$license        = "Modifications - Copyright &copy; 2000-2004 Comunidade PHP Nuke Brasil";
$download_location    = "";
$nuke_module_version        = "4.4.2";
$nuke_module_description    = "";

show_copyright($nuke_author_name, $nuke_author_email, $nuke_author_homepage, $license, $download_location, $nuke_module_version, $nuke_module_description);

?>