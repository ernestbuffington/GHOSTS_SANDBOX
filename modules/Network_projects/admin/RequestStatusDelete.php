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
if($status_id < 1) { header("Location: ".$admin_file.".php?op=RequestStatusList"); }
$status = pjrequeststatus_info($status_id);
$network_db->sql_query("DELETE FROM `".$network_prefix."_requests_status` WHERE `status_id`='$status_id'");
$network_db->sql_query("UPDATE `".$network_prefix."_requests` SET `status_id`='$swap_status_id' WHERE `status_id`='$status_id'");
$statusresult = $network_db->sql_query("SELECT `status_id`, `status_weight` FROM `".$network_prefix."_requests_status` WHERE `status_weight`>='".$status['status_weight']."'");
while(list($p_id, $weight) = $network_db->sql_fetchrow($statusresult)) {
    $new_weight = $weight - 1;
    $network_db->sql_query("UPDATE `".$network_prefix."_requests_status` SET `status_weight`='$new_weight' WHERE `status_id`='$p_id'");
}
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests_status`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests`");
header("Location: ".$admin_file.".php?op=RequestStatusList");

?>