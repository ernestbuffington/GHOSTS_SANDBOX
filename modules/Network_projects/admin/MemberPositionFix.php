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
$result = $network_db->sql_query("SELECT * FROM `".$network_prefix."_members_positions` WHERE `position_weight`>'0' ORDER BY `position_id` ASC");
$weight = 0;
while($row = $network_db->sql_fetchrow($result)) {
  $xid = intval($row['position_id']);
  $weight++;
  $network_db->sql_query("UPDATE `".$network_prefix."_members_positions` SET `position_weight`='$weight' WHERE `position_id`='$xid'");
}
header("Location: ".$admin_file.".php?op=MemberPositionList");

?>