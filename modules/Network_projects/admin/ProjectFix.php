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
$result = $network_db->sql_query("SELECT * FROM `".$network_prefix."_projects` ORDER BY `project_id` ASC");
$weight = 0;
while($row = $network_db->sql_fetchrow($result)) {
  $xid = intval($row['project_id']);
  $weight++;
  $network_db->sql_query("UPDATE `".$network_prefix."_projects` SET `weight`='$weight' WHERE `project_id`='$xid'");
}
header("Location: ".$admin_file.".php?op=ProjectList");

?>