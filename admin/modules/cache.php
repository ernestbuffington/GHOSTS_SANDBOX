<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Cache Admin Panel
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : cache.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.2
   Date          : 11/11/2005 (mm-dd-yyyy)

   Notes         : Allows admin to easily manage the built-in cache.
************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/
if (!defined('ADMIN_FILE')) 
die ("Illegal File Access");

define('CACHE_ADMIN', true);
global $prefix, $nuke_db, $evoconfig;

function nuke_cache_header() 
{
    global $admin_file, $evoconfig, $nuke_usrclearcache, $nuke_cache;

    $enabled = ($nuke_cache->valid) ? "<font color=\"green\">" . _CACHE_ENABLED . "</font>" : "<font color=\"red\">" . _CACHE_DISABLED . "</font> (<a href=\"$admin_file.php?op=nuke_howto_enable_cache\">" . _CACHE_HOWTOENABLE . "</a>)";
    $enabled_img = ($nuke_cache->valid) ? get_evo_icon('evo-sprite good') : get_evo_icon('evo-sprite bad');
    $nuke_cache_num_files = $nuke_cache->count_rows();
    $last_cleared_img = ((time() - $evoconfig['cache_last_cleared']) >= 604800) ? get_evo_icon('evo-sprite bad') : get_evo_icon('evo-sprite good');
    $clear_needed = ((time() - $evoconfig['cache_last_cleared']) >= 604800) ? "(<a href=\"$admin_file.php?op=cache_clear\"><font color=\"red\">" . _CACHE_CLEARNOW . "</font></a>)" : "";
    $last_cleared = date('F j, Y, g:i a', $evoconfig['cache_last_cleared']);
    $nuke_user_can_clear = ($nuke_usrclearcache) ? "[ <strong>" . _CACHE_YES . "</strong> | <a href=\"$admin_file.php?op=nuke_usrclearcache&amp;opt=0\">" . _CACHE_NO . "</a> ]" : "[ <a href=\"$admin_file.php?op=nuke_usrclearcache&amp;opt=1\">" . _CACHE_YES . "</a> | <strong>" . _CACHE_NO . "</strong> ]";
    $nuke_cache_good = (is_writable(NUKE_CACHE_DIR) && !ini_get('safe_mode')) ? "<font color=\"green\">" . _CACHE_GOOD . "</font>" : "<font color=\"red\">" . _CACHE_BAD . "</font>";
    $nuke_cache_good_img = (is_writable(NUKE_CACHE_DIR) && !ini_get('safe_mode')) ? get_evo_icon('evo-sprite good') : get_evo_icon('evo-sprite bad');
    $nuke_cache_good = (ini_get('safe_mode')) ? "<font color=red>" . _CACHESAFEMODE . "</font>" : $nuke_cache_good;
    switch ($nuke_cache->type) {
        case FILE_CACHE:
            $nuke_cache_type = _CACHE_FILEMODE;
        break;
        case SQL_CACHE:
            $nuke_cache_type = _CACHE_SQLMODE;
        break;
        case XCACHE:
            $nuke_cache_type = 'XCache';
        break;
        case APC_CACHE:
            $nuke_cache_type = 'APC';
        break;
        case MEMCACHED:
            $nuke_cache_type = 'Memcached';
        break;
        default:
            $nuke_cache_type =  _CACHE_DISABLED;
        break;
    }
    OpenTable();
    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php?op=cache\">" . _CACHE_HEADER . "</a> ]</div>\n";
    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _CACHE_RETURN . "</a> ]</div>\n";
    CloseTable();

    OpenTable();
        echo "<center>"
        ."<table border='0' width='70%'><tr><td>"
        ."$enabled_img</td><td>"
        ."<i>" . _CACHE_STATUS . "</i></td><td>" . $enabled . "</td>"
        ."</tr><tr><td>"
        ."$enabled_img</td><td>"
        ."<i>" . _CACHE_MODE . "</i></td><td>" . $nuke_cache_type . "</td>"
        ."</tr><tr><td>"
        ."$nuke_cache_good_img</td><td>"
        ."<i>" . _CACHE_DIR_STATUS . "</i></td><td>" . $nuke_cache_good . "</td>"
        ."</tr>"
        // ."<tr><td>"
        // ."<img src='images/thumb_up.png' alt='' width='10' height='10' /></td><td>"
        // ."<i>" . _CACHE_NUM_FILES . "</i></td><td>" . $nuke_cache_num_files . "</td>"
        // ."</tr>"
        ."<tr><td>"
        ."$last_cleared_img</td><td>"
        ."<i>" . _CACHE_LAST_CLEARED . "</i></td><td>" . $last_cleared . "  $clear_needed</td>"
        ."</tr>"
        ."<tr><td>"
        .(($nuke_usrclearcache == 1) ? get_evo_icon('evo-sprite good') : get_evo_icon('evo-sprite bad'))."</td><td>"
        ."<i>" . _CACHE_USER_CAN_CLEAR . "</i></td><td>" . $nuke_user_can_clear . "</td>"
        ."</tr>"
        ."<tr><td>"
        .get_evo_icon('evo-sprite good')."</td><td>"
        ."<i>" . _CACHE_TYPES . "</i></td><td>" . nuke_get_cache_types() . "</td>"
        ."</tr>"
        ."</table>"
        .'<br />'
        ."[ <a href=\"$admin_file.php?op=cache_clear\">" . _CACHE_CLEAR . "</a> ]"
        ."</center>";
    CloseTable();
    echo "<br />";
}

function nuke_get_cache_types() {
    $out = '';

    if (is_writable(NUKE_CACHE_DIR)) {
        $out .= 'File <br />';
    }
    if (extension_loaded('apc')) {
        $out .= 'APC <br />';
    }
    if (extension_loaded('memcache')) {
        $out .= 'Memcached <br />';
    }
    if (extension_loaded('XCache')) {
        $out .= 'XCache <br />';
    }

    return $out;
}

function nuke_display_main() {
   global $admin_file, $nuke_cache;

   $open = get_evo_icon('evo-sprite folder-live');
   $closed = get_evo_icon('evo-sprite folder');


}

function nuke_delete_cache($file, $name) {
    global $admin_file, $nuke_cache;
    OpenTable();
    if (!empty($file) && !empty($name)) {
            if ($nuke_cache->delete($file, $name)) {
                echo "<center>\n";
                echo "<strong>" . _CACHE_FILE_DELETE_SUCC . "</strong><br /><br />\n";
                nuke_redirect("$admin_file.php?op=cache");
                echo "</center>\n";
            } else {
                echo "<center>\n";
                echo "<strong>" . _CACHE_FILE_DELETE_FAIL . "</strong><br /><br />\n";
                nuke_redirect("$admin_file.php?op=cache");
                echo "</center>\n";
            }
    } elseif (empty($file) && (!empty($name))) {
            if ($nuke_cache->delete('', $name)) {
                echo "<center>\n";
                echo "<strong>" . _CACHE_CAT_DELETE_SUCC . "</strong><br /><br />\n";
                nuke_redirect("$admin_file.php?op=cache");
                echo "</center>\n";
            } else {
                echo "<center>\n";
                echo "<strong>" . _CACHE_CAT_DELETE_FAIL . "</strong><br /><br />\n";
                nuke_redirect("$admin_file.php?op=cache");
                echo "</center>\n";
            }
    } else {
            echo "<center>\n";
            echo "<strong>" . _CACHE_INVALID . "</strong><br /><br />\n";
            nuke_redirect("$admin_file.php?op=cache");
            echo "</center>\n";
    }
    CloseTable();
}

function nuke_cache_view($file, $name) {
    global $admin_file, $nuke_cache;
    OpenTable();
        echo  "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" align=\"center\" class=\"forumline\">\n";
        echo  "<tr>\n"
             ."<td class=\"row1\" width='33%' align='center'><span class=\"content\"><a href=\"$admin_file.php?op=cache_delete&amp;file=$file&amp;name=$name\">" . _CACHE_DELETE . "</a></span></td>\n"
             ."<td class=\"row1\" width='33%' align='center'><span class=\"content\"><a href=\"$admin_file.php?op=cache\">" . _CACHE_RETURNCACHE . "</a></span></td>\n"
             ."</tr>\n"
             ."</table>\n";
        echo "<br />\n";
        echo  "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" align=\"left\" class=\"forumline\">\n";
        echo  "<tr>\n"
             ."<td class=\"row1\" width='100%' align='left'>\n";
        if(is_array($nuke_cache->saved[$name][$file])) {
            $file = "<?php\n\n\$$file = array(\n".$nuke_cache->array_parse($nuke_cache->saved[$name][$file]).");\n\n?>";
        } else {
            $file = "<?php\n\n\$$file = \"" . $nuke_cache->saved[$name][$file] . "\";\n\n?>";
        }
        @highlight_string($file);
        echo  "</td>\n";
        echo  "</tr>\n";
        echo "</table>\n";
    CloseTable();
}

function nuke_clear_cache() { 
    global $nuke_db, $prefix, $admin_file, $nuke_cache;
    
    OpenTable();
    
    if ($nuke_cache->clear()) {
        // Update the last cleared time stamp
        $nuke_db->sql_query("UPDATE `" . $prefix . "_evolution` SET evo_value='" . time() . "' WHERE evo_field='cache_last_cleared'");
        
        echo "<center>\n";
        echo "<strong>" . _CACHE_CLEARED_SUCC . "</strong><br /><br />\n";
        nuke_redirect("$admin_file.php?op=cache");
        echo "</center>\n";
    } else {
        echo "<center>\n";
        echo "<strong>" . _CACHE_CLEARED_FAIL . "</strong><br /><br />\n";
        nuke_redirect("$admin_file.php?op=cache");
        echo "</center>\n";
    }
    
    CloseTable();
}

function nuke_usrclearcache($opt) {
    global $prefix, $nuke_db, $admin_file, $nuke_cache;
    $opt = intval($opt);
    if($opt == 1 || $opt == 0) {
        $nuke_db->sql_query("UPDATE ".$prefix."_evolution SET evo_value='" . $opt . "' WHERE evo_field='nuke_usrclearcache'");
        $nuke_cache->delete('evoconfig');
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _CACHE_PREF_UPDATED_SUCC . "</strong><br /><br />\n";
            nuke_redirect("$admin_file.php?op=cache");
            echo "</center>\n";
        CloseTable();
    } else {
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _CACHE_INVALID . "</strong><br /><br />\n";
            nuke_redirect("$admin_file.php?op=cache");
            echo "</center>\n";
        CloseTable();
    }
}

function nuke_howto_enable_cache() {
    global $admin_file;
    OpenTable();
        echo "<center>\n";
        echo "<strong>" . _CACHE_ENABLE_HOW . "</strong><br />";
        echo "<br />\n";
        nuke_redirect("$admin_file.php?op=cache");
        echo "</center>\n";
    CloseTable();
}

global $nuke_userinfo;
if (is_admin()) {
    include_once(NUKE_BASE_DIR.'header.php');
    nuke_cache_header();
    switch ($op) {
        case 'cache_delete':
            nuke_delete_cache($_GET['file'], $_GET['name']);
        break;
        case 'nuke_cache_view':
            nuke_cache_view($_GET['file'], $_GET['name']);
        break;
        case 'cache_clear':
            nuke_clear_cache();
        break;
        case 'nuke_usrclearcache':
            nuke_usrclearcache($_GET['opt']);
        break;
        case 'nuke_howto_enable_cache':
            nuke_howto_enable_cache();
        break;
        default:
            nuke_display_main();
        break;
    }
    include_once(NUKE_BASE_DIR.'footer.php');
} else {
    echo "Access Denied";
}

?>