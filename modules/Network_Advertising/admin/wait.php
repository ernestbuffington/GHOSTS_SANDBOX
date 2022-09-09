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

global $admin_file, $network_db, $network_prefix, $banners, $nuke_cache;

if($banners && is_mod_admin('Advertising')) {
    $content .= "<div align=\"left\"><strong><u><span class=\"content\">"._ABAN."</span>:</u></strong></div>";
    if (!$active = $nuke_cache->load('numbanact', 'submissions')) {
        list($active) = $network_db->sql_ufetchrow("SELECT COUNT(*) FROM " . $network_prefix . "_banner WHERE active='1'", SQL_NUM);
        $nuke_cache->save('numbanact', 'submissions', $active);
    }
    if (!$inactive = $nuke_cache->load('numbandea', 'submissions')) {
        list($inactive) = $network_db->sql_ufetchrow("SELECT COUNT(*) FROM " . $network_prefix . "_banner WHERE active='0'", SQL_NUM);
        $nuke_cache->save('numbandea', 'submissions', $inactive);
    }
    $content .= "<img src=\"images/arrow.gif\" border=\"0\" alt=\"\">&nbsp;<a href=\"".$admin_file.".php?op=NetworkBannersAdmin\">"._ABANNERS."</a>:&nbsp;<strong>$active</strong><br />";
    $content .= "<img src=\"images/arrow.gif\" border=\"0\" alt=\"\">&nbsp;<a href=\"".$admin_file.".php?op=NetworkBannersAdmin\">"._DBANNERS."</a>:&nbsp;<strong>$inactive</strong><br />";
}

?>