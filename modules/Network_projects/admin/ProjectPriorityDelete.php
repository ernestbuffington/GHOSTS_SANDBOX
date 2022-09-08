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
$priority_id = intval($priority_id);
if($priority_id < 1) { header("Location: ".$admin_file.".php?op=ProjectPriorityList"); }
$priority = pjprojectpriority_info($priority_id);
$network_db->sql_query("DELETE FROM `".$network_prefix."_projects_priorities` WHERE `priority_id`='$priority_id'");
$network_db->sql_query("UPDATE `".$network_prefix."_projects` SET `priority_id`='$swap_priority_id' WHERE `priority_id`='$priority_id'");
$priorityresult = $network_db->sql_query("SELECT `priority_id`, `priority_weight` FROM `".$network_prefix."_projects_priorities` WHERE `priority_weight`>='".$priority['priority_weight']."'");
while(list($p_id, $weight) = $network_db->sql_fetchrow($priorityresult)) {
    $new_weight = $weight - 1;
    $network_db->sql_query("UPDATE `".$network_prefix."_projects_priorities` SET `priority_weight`='$new_weight' WHERE `priority_id`='$p_id'");
}
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects_priorities`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects`");
header("Location: ".$admin_file.".php?op=ProjectPriorityList");

?>