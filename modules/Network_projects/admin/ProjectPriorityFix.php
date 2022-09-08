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
$result = $network_db->sql_query("SELECT * FROM `".$network_prefix."_projects_priorities` WHERE `priority_weight`>'0' ORDER BY `priority_id` ASC");
$weight = 0;
while($row = $network_db->sql_fetchrow($result)) {
  $xid = intval($row['priority_id']);
  $weight++;
  $network_db->sql_query("UPDATE `".$network_prefix."_projects_priorities` SET `priority_weight`='$weight' WHERE `priority_id`='$xid'");
}
header("Location: ".$admin_file.".php?op=ProjectPriorityList");

?>