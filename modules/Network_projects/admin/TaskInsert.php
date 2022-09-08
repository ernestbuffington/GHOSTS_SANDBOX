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
$date = time();
$task_name = htmlentities($task_name, ENT_QUOTES);
$task_description = htmlentities($task_description, ENT_QUOTES);
$priority_id = intval($priority_id);
$status_id = intval($status_id);
$task_percent = intval($task_percent);
$project_id = intval($project_id);
$start_date = "$task_start_year-$task_start_month-$task_start_day";
if($start_date == "0000-00-00") { $start_date = 0; } else { $start_date = strtotime($start_date); }
$finish_date = "$task_finish_year-$task_finish_month-$task_finish_day";
if($finish_date == "0000-00-00") { $finish_date = 0; } else { $finish_date = strtotime($finish_date); }
$network_db->sql_query("INSERT INTO `".$network_prefix."_tasks` VALUES (NULL, '$project_id', '$task_name', '$task_description', '$priority_id', '$status_id', '$task_percent', '$date', '$start_date', '$finish_date')");
$taskresult = $network_db->sql_query("SELECT `task_id` FROM `".$network_prefix."_tasks` WHERE `date_created`='$date'");
list($task_id) = $network_db->sql_fetchrow($taskresult);
if(implode("", $member_ids) > "") {
  while(list($null, $member_id) = each($member_ids)) {
    $numrows = $network_db->sql_numrows($network_db->sql_query("SELECT * FROM `".$network_prefix."_tasks_members` WHERE `task_id`='$task_id' AND `member_id`='$member_id'"));
    if($numrows == 0) {
      $network_db->sql_query("INSERT INTO `".$network_prefix."_tasks_members` VALUES ('$task_id', '$member_id', '".$pj_config['new_task_position']."')");        
    }
  }
}
header("Location: ".$admin_file.".php?op=ProjectTasks&project_id=$project_id");

?>