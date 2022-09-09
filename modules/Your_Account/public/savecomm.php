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
 ************************************************************************/

if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

if (!defined('CNBYA')) {
    die('CNBYA protection');
}

    global $cookie, $nuke_userinfo;
    $check = $cookie[1];
    $check2 = $cookie[2];
    $result = $nuke_db->sql_query("SELECT user_id, user_password FROM ".$nuke_user_prefix."_users WHERE username='$check'");
    $row = $nuke_db->sql_fetchrow($result);
    $vuid = $row['user_id'];
    $ccpass = $row['user_password'];
    if (($nuke_user_id == $vuid) AND ($check2 == $ccpass)) {
        if(isset($noscore)) $noscore=1; else $noscore=0;
        $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET umode='$umode', uorder='$uorder', thold='$thold', noscore='$noscore', commentmax='$commentmax' WHERE user_id='$nuke_user_id'");
        yacookie($nuke_userinfo[user_id],$nuke_userinfo[username],$nuke_userinfo[user_password],$nuke_userinfo[storynum],$nuke_userinfo[umode],$nuke_userinfo[uorder],$nuke_userinfo[thold],$nuke_userinfo[noscore],$nuke_userinfo[ublockon],$nuke_userinfo[theme],$nuke_userinfo[commentmax]);
        nuke_redirect("modules.php?name=$module_name");
    }

?>