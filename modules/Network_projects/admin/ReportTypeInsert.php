<?php
/*=======================================================================
 PHP-Nuke Titanium: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/
/********************************************************/
/* NukeProject(tm)                                      */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://nukescripts.86it.us                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/
global $network_db;
if(!defined('NETWORK_SUPPORT_ADMIN')) { die("Illegal Access Detected!!!"); }
$type_name = htmlentities($type_name, ENT_QUOTES);
$result = $network_db->sql_query("SELECT `type_weight` FROM `".$network_prefix."_reports_types` ORDER BY `type_weight` DESC");
list($lweight) = $network_db->sql_fetchrow($result);
$weight = $lweight + 1;
if($weight < 1) { $weight = 1; }
$network_db->sql_query("INSERT INTO `".$network_prefix."_reports_types` VALUES (NULL, '$type_name', '$weight')");
header("Location: ".$admin_file.".php?op=ReportTypeList");

?>