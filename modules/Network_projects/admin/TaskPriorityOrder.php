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
$pidrep = intval($pidrep);
$pid = intval($pid);
$result = $network_db->sql_query("UPDATE `".$network_prefix."_tasks_priorities` SET `priority_weight`='$weight' WHERE `priority_id`='$pidrep'");
$result2 = $network_db->sql_query("UPDATE `".$network_prefix."_tasks_priorities` SET `priority_weight`='$weightrep' WHERE `priority_id`='$pid'");
header("Location: ".$admin_file.".php?op=TaskPriorityList");

?>