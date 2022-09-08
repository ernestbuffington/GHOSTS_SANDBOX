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
$network_db->sql_query("UPDATE `".$network_prefix."_members` SET `member_name`='$member_name', `member_email`='$member_email' WHERE `member_id`='$member_id'");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_members`");
header("Location: ".$admin_file.".php?op=MemberList");

?>