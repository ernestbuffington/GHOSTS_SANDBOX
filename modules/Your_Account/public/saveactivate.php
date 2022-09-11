<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/*********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke             */
/* ============================================                                 */
/*                                                                              */
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                             */
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                        */
/*                                                                              */
/* Contact author: escudero@phpnuke.org.br                                      */
/* International Support Forum: http://ravenphpscripts.com/forum76.html         */
/*                                                                              */
/* This program is free software. You can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by         */
/* the Free Software Foundation; either version 2 of the License.               */
/*                                                                              */
/*********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion    */
/*********************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
-=[Mod]=-
      Finished Redirection                     v1.0.0       06/28/2005
      Initial Usergroup                        v1.0.1       09/06/2005
 ************************************************************************/

if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

if (!defined('CNBYA')) {
    die('CNBYA protection');
}

    $ya_username = trim(check_html($ya_username, 'nohtml'));
    $check_num = trim(check_html($check_num, 'nohtml'));
    $ya_time = intval($ya_time);
    $result = $nuke_db->sql_query("SELECT * FROM ".$nuke_user_prefix."_users_temp WHERE username='$ya_username' AND check_num='$check_num' AND time='$ya_time'");
    if ($nuke_db->sql_numrows($result) == 1) {
        $row = $nuke_db->sql_fetchrow($result);
        $nuke_username = $row['username'];
        $nuke_user_email = $row['user_email'];
        $nuke_user_regdate = $row['user_regdate'];
        $nuke_user_password = $row['user_password'];
        $realname = ya_fixtext($realname);
        if(empty($realname)) { $realname = $row['username']; }
        $nuke_user_sig = str_replace("<br />", "\r\n", $nuke_user_sig);
        $nuke_user_sig = ya_fixtext($nuke_user_sig);
        $nuke_user_email = ya_fixtext($nuke_user_email);
        $femail = ya_fixtext($femail);
        $nuke_user_website = ya_fixtext($nuke_user_website);
        if (!preg_match("#http://#i", $nuke_user_website) AND !empty($nuke_user_website)) { $nuke_user_website = "http://$nuke_user_website"; }
        $bio = str_replace("<br />", "\r\n", $bio);
        $bio = ya_fixtext($bio);
        $nuke_user_occ = ya_fixtext($nuke_user_occ);
        $nuke_user_from = ya_fixtext($nuke_user_from);
        $nuke_user_interests = ya_fixtext($nuke_user_interests);
        $nuke_user_dateformat = ya_fixtext($nuke_user_dateformat);
        $newsletter = intval($newsletter);
        $nuke_user_viewemail = intval($nuke_user_viewemail);
        $nuke_user_allow_viewonline = intval($nuke_user_allow_viewonline);
        $nuke_user_timezone = intval($nuke_user_timezone);
        list($latest_uid) = $nuke_db->sql_fetchrow($nuke_db->sql_query("SELECT max(user_id) AS latest_uid FROM ".$nuke_user_prefix."_users"));
        if ($latest_uid == "-1") { $new_uid = 1; } else { $new_uid = $latest_uid+1; }
        $lv = time();
        $nuke_db->sql_query("LOCK TABLES ".$nuke_user_prefix."_users WRITE");
        $nuke_db->sql_query("INSERT INTO ".$nuke_user_prefix."_users (user_id, user_avatar, user_avatar_type, user_lang, user_lastvisit, umode) VALUES ($new_uid, 'gallery/blank.gif', '3', '$language_nuke', '$lv', 'nested')");
        $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET username='$nuke_username', name='$realname', user_email='$nuke_user_email', femail='$femail', user_website='$nuke_user_website', user_from='$nuke_user_from', user_occ='$nuke_user_occ', user_interests='$nuke_user_interests', newsletter='$newsletter', user_viewemail='$nuke_user_viewemail', user_allow_viewonline='$nuke_user_allow_viewonline', user_timezone='$nuke_user_timezone', user_dateformat='$nuke_user_dateformat', user_sig='$nuke_user_sig', bio='$bio', user_password='$nuke_user_password', user_regdate='$nuke_user_regdate' WHERE user_id='$new_uid'");
        $nuke_db->sql_query("UNLOCK TABLES");
        $nuke_db->sql_query("DELETE FROM ".$nuke_user_prefix."_users_temp WHERE username='$nuke_username'");

        $res = $nuke_db->sql_query("SELECT * FROM ".$nuke_user_prefix."_cnbya_value_temp WHERE uid = '$row[user_id]'");
        while ($sqlvalue = $nuke_db->sql_fetchrow($res)) {
         $nuke_db->sql_query("INSERT INTO ".$nuke_user_prefix."_cnbya_value (uid, fid, value) VALUES ('$new_uid', '$sqlvalue[fid]','$sqlvalue[value]')");
        }
        $nuke_db->sql_query("DELETE FROM ".$nuke_user_prefix."_cnbya_value_temp WHERE uid='$row[user_id]'");
        $nuke_db->sql_query("OPTIMIZE TABLE ".$nuke_user_prefix."_cnbya_value_temp");

        $nuke_db->sql_query("OPTIMIZE TABLE ".$nuke_user_prefix."_users_temp");
        include_once(NUKE_BASE_DIR.'header.php');
/*****[BEGIN]******************************************
 [ Mod:     Welcome PM                         v2.0.0 ]
 ******************************************************/
        include('modules/Your_Account/public/functions_welcome_pm.php');
/*****[END]********************************************
 [ Mod:     Welcome PM                         v2.0.0 ]
 ******************************************************/
        title(""._ACTIVATIONYES."");
        OpenTable();
        $result = $nuke_db->sql_query("SELECT * FROM ".$nuke_user_prefix."_users WHERE username='$nuke_username' AND user_password='$nuke_user_password'");
        if ($nuke_db->sql_numrows($result) == 1) {
/*****[BEGIN]******************************************
 [ Mod:     Welcome PM                         v2.0.0 ]
 ******************************************************/
            send_pm($new_uid,$ya_username);
/*****[END]********************************************
 [ Mod:     Welcome PM                         v2.0.0 ]
 ******************************************************/
            $nuke_userinfo = $nuke_db->sql_fetchrow($result);
            yacookie($nuke_userinfo['user_id'],$nuke_userinfo['username'],$nuke_userinfo['user_password'],$nuke_userinfo['storynum'],$nuke_userinfo['umode'],$nuke_userinfo['uorder'],$nuke_userinfo['thold'],$nuke_userinfo['noscore'],$nuke_userinfo['ublockon'],$nuke_userinfo['theme'],$nuke_userinfo['commentmax']);
/*****[BEGIN]******************************************
 [ Mod:     Initial Usergroup                  v1.0.1 ]
 ******************************************************/
            include('modules/Your_Account/public/custom_functions.php');
            init_group($new_uid);
/*****[END]********************************************
 [ Mod:     Initial Usergroup                  v1.0.1 ]
 ******************************************************/
            // CurtisH (clh@curtishancock.com <clh@curtishancock.com>)
            //echo "<META HTTP-EQUIV=\"refresh\" content=\"2;URL=$nukeurl\">";
            //echo "<center><strong>$row[username]:</strong> "._ACTMSG2."</center>";
            echo "<meta http-equiv=\"refresh\" content=\"modules.php?name=Your_Account\">";
            echo "<center><strong>$row[username]:</strong> "._ACTMSG."</center>";
/*****[BEGIN]******************************************
 [ Mod:     Finished Redirection               v1.0.0 ]
 ******************************************************/
            $complete = 1;
/*****[END]********************************************
 [ Mod:     Finished Redirection               v1.0.0 ]
 ******************************************************/
        } else {
            echo "<center>"._SOMETHINGWRONG."</center><br />";
        }
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
/*****[BEGIN]******************************************
 [ Mod:     Finished Redirection               v1.0.0 ]
 ******************************************************/
        if($complete) {
         header("Refresh: 3; URL=index.php");
         exit();
        }
/*****[END]********************************************
 [ Mod:     Finished Redirection               v1.0.0 ]
 ******************************************************/
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        title(""._ACTIVATIONERROR."");
        OpenTable();
        echo "<center>"._ACTERROR."</center>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
        exit;
    }

?>