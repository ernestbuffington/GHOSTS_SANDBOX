<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/************************************************************************
   Nuke-Evolution: Submissions Block
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : wait.php
   Author        : Quake
   Version       : 2.0.0
   Date          : 09/02/2006 (dd-mm-yyyy)

   Notes         : Overview about submissions and other useful information
                   about your website.
************************************************************************/

if(!defined('NUKE_EVO')) {
    exit;
}

global $admin_file, $nuke_db, $prefix, $nuke_cache;

$nuke_module_name = basename(dirname(dirname(__FILE__)));

if(is_active($nuke_module_name)) {
    $content .= "<div align=\"left\"><strong><u><span class=\"content\">"._AWL."</span>:</u></strong></div>";
    if(($numbrokenl = $nuke_cache->load('numbrokenl', 'submissions')) === false) {
        $result = $nuke_db->sql_query("SELECT COUNT(*) FROM ".$prefix."_links_modrequest WHERE brokenlink='1'");
        list($numbrokenl) = $nuke_db->sql_fetchrow($result, SQL_NUM);
        $nuke_db->sql_freeresult($result);
        $nuke_cache->save('numbrokenl', 'submissions', $numbrokenl);
    }
    if(($nummodreql = $nuke_cache->load('nummodreql', 'submissions')) === false) {
        $result = $nuke_db->sql_query("SELECT COUNT(*) FROM ".$prefix."_links_modrequest WHERE brokenlink='0'");
        list($nummodreql) = $nuke_db->sql_fetchrow($result, SQL_NUM);
        $nuke_db->sql_freeresult($result);
        $nuke_cache->save('nummodreql', 'submissions', $nummodreql);
    }
    if(($numwaitl = $nuke_cache->load('numwaitl', 'submissions')) === false) {
        $result = $nuke_db->sql_query("SELECT COUNT(*) FROM ".$prefix."_links_newlink");
        list($numwaitl) = $nuke_db->sql_fetchrow($result, SQL_NUM);
        $nuke_db->sql_freeresult($result);
        $nuke_cache->save('numwaitl', 'submissions', $numwaitl);
    }
    $content .= "<img src=\"images/arrow.gif\" alt=\"\" />&nbsp;<a href=\"".$admin_file.".php?op=LinksListBrokenLinks\">"._BROKENLINKS."</a>:&nbsp;<strong>$numbrokenl</strong><br />";
    $content .= "<img src=\"images/arrow.gif\" alt=\"\" />&nbsp;<a href=\"".$admin_file.".php?op=LinksListModRequests\">"._MODREQLINKS."</a>:&nbsp;<strong>$nummodreql</strong><br />";
    $content .= "<img src=\"images/arrow.gif\" alt=\"\" />&nbsp;<a href=\"".$admin_file.".php?op=Links\">"._WLINKS."</a>:&nbsp;<strong>$numwaitl</strong><br />";
}

?>