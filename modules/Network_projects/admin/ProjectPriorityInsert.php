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
get_lang('Network_Projects');
if(!defined('NETWORK_SUPPORT_ADMIN')) { die("Illegal Access Detected!!!"); }
$priority_name = htmlentities($priority_name, ENT_QUOTES);
$result = $network_db->sql_query("SELECT `priority_weight` FROM `".$network_prefix."_projects_priorities` ORDER BY `priority_weight` DESC");
list($lweight) = $network_db->sql_fetchrow($result);
$weight = $lweight + 1;
if($weight < 1) { $weight = 1; }
$network_db->sql_query("INSERT INTO `".$network_prefix."_projects_priorities` VALUES (NULL, '$priority_name', '$weight')");
header("Location: ".$admin_file.".php?op=ProjectPriorityList");

?>