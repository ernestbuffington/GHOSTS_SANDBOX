<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/

if(!defined('NUKE_EVO')) exit;

include_once(NUKE_MODULES_DIR .'Evo_UserBlock/addons/core.php');
global $lang_evo_userblock;

function evouserinfo_block_getactive () {
    global $prefix, $nuke_db, $lang_evo_userblock, $nuke_cache;
    if(isset($active) && is_array($active)) return $active;
    
    if ((($active = $nuke_cache->load('active', 'evouserinfo')) === false) || !isset($active)) {
        $sql = 'SELECT * FROM '.$prefix.'_evo_userinfo WHERE active=1 ORDER BY position ASC';
        $result = $nuke_db->sql_query($sql);
        while($row = $nuke_db->sql_fetchrow($result)) {
            $active[] = $row;
        }
        $nuke_db->sql_freeresult($result);
        $nuke_cache->save('active', 'evouserinfo', $active);
    }
    return $active;
}

function evouserinfo_block_display () {
    define('EVO_BLOCK', true);
    global $lang_evo_userblock;
    $active = evouserinfo_block_getactive();
    $content = "";
    $blank = 0;
    foreach ($active as $element) {
        if($element['filename'] != 'Break') {
            if(file_exists(NUKE_MODULES_DIR .'Evo_UserBlock/addons/'.$element['filename'].'.php')) {
                include_once(NUKE_MODULES_DIR .'Evo_UserBlock/addons/'.$element['filename'].'.php');
                $output = 'evouserinfo_'.$element['filename'];
                $content .= $$output;
                if(isset($$output) && !empty($$output)) {
                    $blank = 1;
                }
            }
        } else {
            if($blank) {
                $content .= "<hr />";
            }
            $blank = 0;
        }
    }
    return $content;
}

// $content = '
// <style>

// hr:last-of-type {
// 	margin-bottom:5px;
// }

// </style>
// ';
$content = evouserinfo_block_display();
$content .= '<br />';

?>