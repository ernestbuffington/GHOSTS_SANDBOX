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
$status_id = intval($status_id);
if($status_id < 1) { header("Location: ".$admin_file.".php?op=TaskStatusList"); }
$status_name = htmlentities($status_name, ENT_QUOTES);
$network_db->sql_query("UPDATE `".$network_prefix."_tasks_status` SET `status_name`='$status_name' WHERE `status_id`='$status_id'");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_tasks_status`");
header("Location: ".$admin_file.".php?op=TaskStatusList");

?>