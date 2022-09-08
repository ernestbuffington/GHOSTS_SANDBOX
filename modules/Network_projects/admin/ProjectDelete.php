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
$project_id = intval($project_id);
$project = pjproject_info($project_id);
$network_db->sql_query("DELETE FROM `".$network_prefix."_projects` WHERE `project_id`='$project_id'");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects`");
$network_db->sql_query("DELETE FROM `".$network_prefix."_projects_members` WHERE `project_id`='$project_id'");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects_members`");
$taskresult = $network_db->sql_query("SELECT `task_id` FROM `".$network_prefix."_tasks` WHERE `project_id`='$project_id'");
while(list($task_id) = $network_db->sql_fetchrow($taskresult)) {
  $network_db->sql_query("DELETE FROM `".$network_prefix."_tasks` WHERE `task_id`='$task_id'");
  $network_db->sql_query("DELETE FROM `".$network_prefix."_tasks_members` WHERE `task_id`='$task_id'");
}
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_tasks`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_tasks_members`");
$reportresult = $network_db->sql_query("SELECT `report_id` FROM `".$network_prefix."_reports` WHERE `project_id`='$project_id'");
while(list($report_id) = $network_db->sql_fetchrow($reportresult)) {
  $network_db->sql_query("DELETE FROM `".$network_prefix."_reports` WHERE `report_id`='$report_id'");
  $network_db->sql_query("DELETE FROM `".$network_prefix."_reports_members` WHERE `report_id`='$report_id'");
  $network_db->sql_query("DELETE FROM `".$network_prefix."_reports_comments` WHERE `report_id`='$report_id'");
}
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_reports`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_reports_members`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_reports_comments`");
$requestresult = $network_db->sql_query("SELECT `request_id` FROM `".$network_prefix."_requests` WHERE `project_id`='$project_id'");
while(list($request_id) = $network_db->sql_fetchrow($requestresult)) {
  $network_db->sql_query("DELETE FROM `".$network_prefix."_requests` WHERE `request_id`='$request_id'");
  $network_db->sql_query("DELETE FROM `".$network_prefix."_requests_members` WHERE `request_id`='$request_id'");
  $network_db->sql_query("DELETE FROM `".$network_prefix."_requests_comments` WHERE `request_id`='$request_id'");
}
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests_members`");
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests_comments`");
$projectresult = $network_db->sql_query("SELECT `project_id`, `weight` FROM `".$network_prefix."_projects` WHERE `weight`>='".$project['weight']."'");
while(list($p_project_id, $weight) = $network_db->sql_fetchrow($projectresult)) {
  $new_weight = $weight - 1;
  $network_db->sql_query("UPDATE `".$network_prefix."_projects` SET `weight`='$new_weight' WHERE `project_id`='$p_project_id'");
}
$network_db->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects`");
header("Location: ".$admin_file.".php?op=ProjectList");

?>