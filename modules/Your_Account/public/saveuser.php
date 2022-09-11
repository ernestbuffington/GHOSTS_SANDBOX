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
      Evolution Functions                      v1.5.0       12/20/2005
 ************************************************************************/

if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

if (!defined('CNBYA')) {
    die('CNBYA protection');
}

    $stop = "";
    global $cookie;
    $check = $cookie[1];
    $check2 = $cookie[2];
    $result = $nuke_db->sql_query("SELECT user_id, user_password, user_email FROM ".$nuke_user_prefix."_users WHERE username='$check'");
    $row = $nuke_db->sql_fetchrow($result);
    $vuid = $row['user_id'];
    $ccpass = $row['user_password'];
    $tuemail = strtolower($row['user_email']);
    $nuke_user_sig = str_replace("<br />", "\r\n", $nuke_user_sig);
    $nuke_user_sig = ya_fixtext($nuke_user_sig);
    $nuke_user_email = strtolower($nuke_user_email);
    $nuke_user_email = ya_fixtext($nuke_user_email);
    $femail = ya_fixtext($femail);
    $nuke_user_website = ya_fixtext($nuke_user_website);
    $bio = ya_fixtext($bio);
    $nuke_user_icq = ya_fixtext($nuke_user_icq);
    $nuke_user_aim = ya_fixtext($nuke_user_aim);
    $nuke_user_yim = ya_fixtext($nuke_user_yim);
    $nuke_user_msnm = ya_fixtext($nuke_user_msnm);
    $nuke_user_occ = ya_fixtext($nuke_user_occ);
    $nuke_user_from = ya_fixtext($nuke_user_from);
    $nuke_user_interests = ya_fixtext($nuke_user_interests);
    $realname = ya_fixtext($realname);
    $nuke_user_dateformat = ya_fixtext($nuke_user_dateformat);
    $newsletter = intval($newsletter);
    $nuke_user_viewemail = intval($nuke_user_viewemail);
    $nuke_user_allow_viewonline = intval($nuke_user_allow_viewonline);
    $nuke_user_timezone = intval($nuke_user_timezone);
    if ($ya_config['allowmailchange'] < 1) {
        if ($tuemail != $nuke_user_email) { ya_mailCheck($nuke_user_email); }
    }
    if ($nuke_user_password > "" OR $vpass > "") { ya_passCheck($nuke_user_password, $vpass); }

        $result = $nuke_db->sql_query("SELECT * FROM ".$nuke_user_prefix."_cnbya_field WHERE need = '3' ORDER BY pos");
        while ($sqlvalue = $nuke_db->sql_fetchrow($result)) {
          $t = $sqlvalue[fid];
          if (empty($nfield[$t])) {
            include_once(NUKE_BASE_DIR.'header.php');
            opentable();
            if (substr($sqlvalue[name],0,1)=='_') eval( "\$name_exit = $sqlvalue[name];"); else $name_exit = $sqlvalue[name];
            echo "<center><span class='title'><strong>"._ERRORREG."</strong></span><br /><br />";
            echo "<span class='content'>"._YA_FILEDNEED1."$name_exit"._YA_FILEDNEED2."<br /><br />"._GOBACK."</span></center>";
            closetable();
            include_once(NUKE_BASE_DIR.'footer.php');
            exit;
          };
        }

    if (empty($stop) AND ($nuke_user_id == $vuid) AND ($check2 == $ccpass)) {
        if (!preg_match("#http://#i", $nuke_user_website) AND !empty($nuke_user_website)) {
            $nuke_user_website = "http://$nuke_user_website";
        }
        if ($bio) { filter_text($bio); $bio = $EditedMessage; $bio = Fix_Quotes($bio); }
        if (!empty($nuke_user_password)) {
            global $cookie;
            $nuke_db->sql_query("LOCK TABLES ".$nuke_user_prefix."_users, ".$nuke_user_prefix."_cnbya_value WRITE");
/*****[BEGIN]******************************************
 [ Base:     Evolution Functions               v1.5.0 ]
 ******************************************************/
            $nuke_user_password = md5($nuke_user_password);
/*****[END]********************************************
 [ Base:     Evolution Functions               v1.5.0 ]
 ******************************************************/

            if ( ($ya_config['emailvalidate'] == '0') OR ($tuemail == $nuke_user_email) ) {
                $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET name='$realname', user_email='$nuke_user_email', femail='$femail', user_website='$nuke_user_website', user_password='$nuke_user_password', bio='$bio', user_icq='$nuke_user_icq', user_occ='$nuke_user_occ', user_from='$nuke_user_from', user_interests='$nuke_user_interests', user_sig='$nuke_user_sig', user_aim='$nuke_user_aim', user_yim='$nuke_user_yim', user_msnm='$nuke_user_msnm', newsletter='$newsletter', user_viewemail='$nuke_user_viewemail', user_allow_viewonline='$nuke_user_allow_viewonline', user_notify='$nuke_user_notify', user_notify_pm='$nuke_user_notify_pm', user_popup_pm='$nuke_user_popup_pm', user_attachsig='$nuke_user_attachsig', user_allowbbcode='$nuke_user_allowbbcode', user_allowhtml='$nuke_user_allowhtml', user_allowsmile='$nuke_user_allowsmile', user_timezone='$nuke_user_timezone', user_dateformat='$nuke_user_dateformat' WHERE user_id='$nuke_user_id'");
            } else {
                $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET name='$realname', femail='$femail', user_website='$nuke_user_website', user_password='$nuke_user_password', bio='$bio', user_icq='$nuke_user_icq', user_occ='$nuke_user_occ', user_from='$nuke_user_from', user_interests='$nuke_user_interests', user_sig='$nuke_user_sig', user_aim='$nuke_user_aim', user_yim='$nuke_user_yim', user_msnm='$nuke_user_msnm', newsletter='$newsletter', user_viewemail='$nuke_user_viewemail', user_allow_viewonline='$nuke_user_allow_viewonline', user_notify='$nuke_user_notify', user_notify_pm='$nuke_user_notify_pm', user_popup_pm='$nuke_user_popup_pm', user_attachsig='$nuke_user_attachsig', user_allowbbcode='$nuke_user_allowbbcode', user_allowhtml='$nuke_user_allowhtml', user_allowsmile='$nuke_user_allowsmile', user_timezone='$nuke_user_timezone', user_dateformat='$nuke_user_dateformat' WHERE user_id='$nuke_user_id'");
                $datekey = date("F Y");
                $check_num = substr(md5(hexdec($datekey) * hexdec($cookie[2]) * hexdec($sitekey) * hexdec($nuke_user_email) * hexdec($tuemail)), 2, 10);
                $finishlink = "$nukeurl/modules.php?name=$nuke_module_name&op=changemail&id=$nuke_user_id&mail=$nuke_user_email&check_num=$check_num";
                $message .= _CHANGEMAIL1." $tuemail "._CHANGEMAIL2." $nuke_user_email"._CHANGEMAIL3." $sitename.<br /><br />";
                $message .= _CHANGEMAILFIN."<br /><br />$finishlink<br /><br />";
                $subject = _CHANGEMAILSUB;
                ya_mail($nuke_user_email, $subject, $message, '');
            }

            if (count($nfield) > 0) {
              foreach ($nfield as $key => $var) {
                  if (($nuke_db->sql_numrows($nuke_db->sql_query("SELECT * FROM ".$nuke_user_prefix."_cnbya_value WHERE fid='$key' AND uid = '$nuke_user_id'"))) == 0) {
                  $sql = "INSERT INTO ".$nuke_user_prefix."_cnbya_value (uid, fid, value) VALUES ('$nuke_user_id', '$key','$nfield[$key]')";
                  $nuke_db->sql_query($sql);
                }
                else {
                $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_cnbya_value SET value='$nfield[$key]' WHERE fid='$key' AND uid = '$nuke_user_id'");
                }
              }
            }

            $sql = "SELECT * FROM ".$nuke_user_prefix."_users WHERE username='$nuke_username' AND user_password='$nuke_user_password'";
            $result = $nuke_db->sql_query($sql);
            if ($nuke_db->sql_numrows($result) == 1) {
                $nuke_userinfo = $nuke_db->sql_fetchrow($result);
                yacookie($nuke_userinfo[user_id],$nuke_userinfo[username],$nuke_userinfo[user_password],$nuke_userinfo[storynum],$nuke_userinfo[umode],$nuke_userinfo[uorder],$nuke_userinfo[thold],$nuke_userinfo[noscore],$nuke_userinfo[ublockon],$nuke_userinfo[theme],$nuke_userinfo[commentmax]);
            } else {
                echo "<center>"._SOMETHINGWRONG."</center><br />";
            }
            $nuke_db->sql_query("UNLOCK TABLES");
        } else {
            $nuke_db->sql_query("LOCK TABLES ".$nuke_user_prefix."_users,".$nuke_user_prefix."_cnbya_value WRITE");

        if ( ($ya_config['emailvalidate'] == '0') OR ($tuemail == $nuke_user_email) ) {
            $q = "UPDATE ".$nuke_user_prefix."_users SET name='$realname', user_email='$nuke_user_email', femail='$femail', user_website='$nuke_user_website', bio='$bio', user_icq='$nuke_user_icq', user_occ='$nuke_user_occ', user_from='$nuke_user_from', user_interests='$nuke_user_interests', user_sig='$nuke_user_sig', user_aim='$nuke_user_aim', user_yim='$nuke_user_yim', user_msnm='$nuke_user_msnm', newsletter='$newsletter', user_viewemail='$nuke_user_viewemail', user_allow_viewonline='$nuke_user_allow_viewonline', user_notify='$nuke_user_notify', user_notify_pm='$nuke_user_notify_pm', user_popup_pm='$nuke_user_popup_pm', user_attachsig='$nuke_user_attachsig', user_allowbbcode='$nuke_user_allowbbcode', user_allowhtml='$nuke_user_allowhtml', user_allowsmile='$nuke_user_allowsmile', user_timezone='$nuke_user_timezone', user_dateformat='$nuke_user_dateformat' WHERE user_id='$nuke_user_id'";
                $nuke_db->sql_query($q);
            } else {

                $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET name='$realname', femail='$femail', user_website='$nuke_user_website', bio='$bio', user_icq='$nuke_user_icq', user_occ='$nuke_user_occ', user_from='$nuke_user_from', user_interests='$nuke_user_interests', user_sig='$nuke_user_sig', user_aim='$nuke_user_aim', user_yim='$nuke_user_yim', user_msnm='$nuke_user_msnm', newsletter='$newsletter', user_viewemail='$nuke_user_viewemail', user_allow_viewonline='$nuke_user_allow_viewonline', user_notify='$nuke_user_notify', user_notify_pm='$nuke_user_notify_pm', user_popup_pm='$nuke_user_popup_pm', user_attachsig='$nuke_user_attachsig', user_allowbbcode='$nuke_user_allowbbcode', user_allowhtml='$nuke_user_allowhtml', user_allowsmile='$nuke_user_allowsmile', user_timezone='$nuke_user_timezone', user_dateformat='$nuke_user_dateformat' WHERE user_id='$nuke_user_id'");
                $datekey = date("F Y");
                $check_num = substr(md5(hexdec($datekey) * hexdec($cookie[2]) * hexdec($sitekey) * hexdec($nuke_user_email) * hexdec($tuemail)), 2, 10);

                $finishlink = "$nukeurl/modules.php?name=$nuke_module_name&op=changemail&id=$nuke_user_id&mail=$nuke_user_email&check_num=$check_num";
                $message .= _CHANGEMAIL1." $tuemail "._CHANGEMAIL2." $nuke_user_email"._CHANGEMAIL3." $sitename.<br /><br />";
                $message .= _CHANGEMAILFIN."<br /><br />$finishlink<br /><br />";
                $subject = _CHANGEMAILSUB;
                ya_mail($nuke_user_email, $subject, $message, '');
        }

        if (count($nfield) > 0) {
                 foreach ($nfield as $key => $var) {
                  if (($nuke_db->sql_numrows($nuke_db->sql_query("SELECT * FROM ".$nuke_user_prefix."_cnbya_value WHERE fid='$key' AND uid = '$nuke_user_id'"))) == 0) {
                      $sql = "INSERT INTO ".$nuke_user_prefix."_cnbya_value (uid, fid, value) VALUES ('$nuke_user_id', '$key','$nfield[$key]')";
                      $nuke_db->sql_query($sql);
                  }
                  else {
                  $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_cnbya_value SET value='$nfield[$key]' WHERE fid='$key' AND uid = '$nuke_user_id'");
                  }
              }
        }

            $nuke_db->sql_query("UNLOCK TABLES");
        }
        nuke_redirect("modules.php?name=$nuke_module_name");
    } else {
        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
        echo "<center><span class='title'><strong>"._ERRORREG."</strong></span><br /><br />";
        echo "<span class='content'>$stop<br /><br />"._GOBACK."</span></center>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

?>