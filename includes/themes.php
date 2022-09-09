<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Theme Management
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : themes.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.2
   Date          : 11.27.2005 (mm.dd.yyyy)

   Notes         : Allows admin to easily manage themes.
************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

function theme_exists($theme_name) {
    if (is_file(NUKE_THEMES_DIR . $theme_name . '/theme.php')) {
        return true;
    }
    return false;
}

function ThemeAllowed($theme) {
    global $Default_Theme;
    static $themesA;
    if (isset($themesA[$theme])) {
        return $themesA[$theme];
    }
    if(!$theme || !theme_exists($theme)) {
        $themesA[$theme] = 0;
        return false;
    }
    if((is_admin() && theme_exists($theme)) || ($theme == $Default_Theme)) {
        $themesA[$theme] = 1;
        return true;
    }
    $themes = get_themes();
    foreach($themes as $allowed_themes) {
        $allowed[] = $allowed_themes['theme_name'];
    }
    if(@in_array($theme, $allowed) && AllowThemeChange()) {
        $themesA[$theme] = 1;
        return true;
    }
    $themesA[$theme] = 0;
    return false;
}

function theme_installed($theme_name) {
    global $nuke_db, $prefix;
    $sql = "SELECT theme_name FROM " . $prefix . "_themes WHERE theme_name = '$theme_name'";
    $theme_installed = $nuke_db->sql_numrows($nuke_db->sql_query($sql));
    if ($theme_installed > 0) {
        return true;
    }
    return false;
}

function ThemeSort()
{
   //www.php.net
   $arguments = func_get_args();
   $arrays    = $arguments[0];
   for ($c = (count($arguments)-1); $c > 0; $c--)
   {
       if (in_array($arguments[$c], array(SORT_ASC , SORT_DESC)))
       {
           continue;
       }
       $compare = create_function('$a,$b','return strcasecmp($a["'.$arguments[$c].'"], $b["'.$arguments[$c].'"]);');
       usort($arrays, $compare);
       if ($arguments[$c+1] == SORT_DESC)
       {
           $arrays = array_reverse($arrays);
       }
   }
   return $arrays ;
}

function ThemeGetStatus($theme_name, $active=0) {
    global $prefix, $nuke_db;
    if (is_default($theme_name)) {
        return "<strong>"._THEMES_DEFAULT."</strong>";
    }
    if(!theme_installed($theme_name)) {
        return _THEMES_QUNINSTALLED;
    }
    if(!theme_exists($theme_name)) {
        return "<font color='red'><strong>"._THEMES_THEME_MISSING."</strong></font>";
    }
    return (($active==1) ? "<i>"._THEMES_ACTIVE."</i>" : "<i>"._THEMES_INACTIVE."</i>");
}

function ThemeNumUsers($theme_name) {
    global $nuke_db, $nuke_user_prefix;
    $where = (is_default($theme_name)) ? "theme = '' OR theme = '" . $theme_name . "'" : "theme = '$theme_name'";
    $sql = "SELECT COUNT(*) AS count FROM " . $nuke_user_prefix . "_users WHERE user_id != '1' AND $where";
    $num = $nuke_db->sql_fetchrow($nuke_db->sql_query($sql));
    return $num['count'];
}

function ThemeIsActive($theme, $admin_file=false) {
    global $nuke_db, $prefix;
    static $activeT;
    if(isset($activeT[$theme])) { return $activeT[$theme]; }
    $sql = "SELECT active FROM " . $prefix . "_themes WHERE theme_name = '$theme'";
    $result = $nuke_db->sql_query($sql);
    $row = $nuke_db->sql_fetchrow($result);
    $nuke_db->sql_freeresult($result);
    // return $activeT[$theme] = ((is_admin() && !$admin_file) ? 1 : $row['active']);
    return $row['active'];
}

function ThemeGetGroups($groups) {
    global $prefix, $nuke_db;
    $return_groups = "";
    if(!is_array($groups)) { $groups = explode("-",$groups); }
    for($i=0, $maxi=count($groups); $i<$maxi; $i++) {
        $comma = (empty($groups[$i+1])) ? "" : ", ";
        $sql = "SELECT group_name FROM " . $prefix . "_bbgroups WHERE group_id = '" . $groups[$i] . "'";
        $result = $nuke_db->sql_query($sql);
        $row = $nuke_db->sql_fetchrow($result);
        $nuke_db->sql_freeresult($result);
        $return_groups .= $row['group_name'] . $comma;
    }
    if (empty($return_groups)) { $return_groups = _THEMES_NONE; }
    return $return_groups;
}

function is_default($theme_name) {
    return (get_default() == $theme_name);
}

function get_default() {
    global $nuke_db, $prefix;
    static $default;
    if(isset($default)) return $default;
    $result = $nuke_db->sql_query("SELECT default_Theme FROM " . $prefix . "_config");
    $default = $nuke_db->sql_fetchrow($result);
    $nuke_db->sql_freeresult($result);
    return $default = $default[0];
}

function add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active) {
    $themes[$theme_name] = array();
    $themes[$theme_name]['theme_name'] = $theme_name;
    $themes[$theme_name]['custom_name'] = $custom_name;
    $themes[$theme_name]['groups'] = $groups;
    $themes[$theme_name]['permissions'] = $perms;
    $themes[$theme_name]['active'] = $active;
    return $themes;
}

function ThemeMostPopular() {
    global $nuke_db, $nuke_user_prefix;
    static $theme;
    if(isset($theme)) return $theme;
    $sql = "SELECT COUNT(*) AS theme_count, theme FROM " . $nuke_user_prefix . "_users WHERE user_id > 1 GROUP BY theme ORDER BY theme_count DESC";
    $result = $nuke_db->sql_query($sql);
    $row = $nuke_db->sql_fetchrow($result);
    $nuke_db->sql_freeresult($result);
    $theme = ($row['theme'] && theme_exists($row['theme']) ) ? $row['theme'] : get_default();
    return $theme;
}

function get_themes($mode='user_themes') 
{
    //Returns all themes the user is allowed to use
    global $nuke_db, $prefix, $debugger;

    switch($mode) 
    {
        case 'user_themes':
            $sql = "SELECT * FROM " . $prefix . "_themes WHERE active='1' ORDER BY theme_name ASC";
            if (!$result = $nuke_db->sql_query($sql)) {
                $debugger->handle_error(_THEMES_ERROR_MESSAGE, _THEMES_ERROR);
            }
            $themes = array();
            while ($row=$nuke_db->sql_fetchrow($result)) 
            {
                $active = $row['active'];
                $theme_name = $row['theme_name'];
                $groups = $row['groups'];
                $perms = $row['permissions'];
                $custom_name = $row['custom_name'];
                if ($perms == 1) {
                    if (theme_exists($theme_name) && ThemeIsActive($theme_name)) {
                            $themes = add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active);
                    }
                }elseif ($perms == 2) {
                    if (ThemeGetGroups($groups) && theme_exists($theme_name) && ThemeIsActive($theme_name)) {
                            $themes = add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active);
                    }
                }elseif ($perms == 3) {
                    if (is_admin() && theme_exists($theme_name) && ThemeIsActive($theme_name)) {
                            $themes = add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active);
                    }
                }
            }
            $nuke_db->sql_freeresult($result);
        break;

        case 'all':
            $sql = "SELECT * FROM " . $prefix . "_themes ORDER BY theme_name ASC";
            if (!$result = $nuke_db->sql_query($sql)) {
                $debugger->handle_error(_THEMES_ERROR_MESSAGE, _THEMES_ERROR);
            }
            $themes = array();
            while ($row=$nuke_db->sql_fetchrow($result)) {
                $active = $row['active'];
                $theme_name = $row['theme_name'];
                $groups = $row['groups'];
                $perms = $row['permissions'];
                $custom_name = $row['custom_name'];
                $themes = add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active);
            }
            $nuke_db->sql_freeresult($result);
        break;

        case 'active':
            $sql = "SELECT * FROM " . $prefix . "_themes WHERE active='1' ORDER BY theme_name ASC";
            if (!$result = $nuke_db->sql_query($sql)) {
                $debugger->handle_error(_THEMES_ERROR_MESSAGE, _THEMES_ERROR);
            }
            $themes = array();
            while ($row=$nuke_db->sql_fetchrow($result)) {
                $active = $row['active'];
                $theme_name = $row['theme_name'];
                $groups = $row['groups'];
                $perms = $row['permissions'];
                $custom_name = $row['custom_name'];
                if(theme_exists($theme_name)) {
                    $themes = add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active);
                }
            }
            $nuke_db->sql_freeresult($result);
        break;

        case 'uninstalled':
            $uninstalled_themes = array();
            $themes = opendir(NUKE_THEMES_DIR);
            while(false !== ($theme_name = readdir($themes))) {
                if(is_dir(NUKE_THEMES_DIR . $theme_name) && $theme_name != "." && $theme_name != ".." && $theme_name != ".svn") {
                    $sql = "SELECT theme_name FROM " . $prefix . "_themes WHERE theme_name = '$theme_name'";
                    $theme_installed = $nuke_db->sql_numrows($nuke_db->sql_query($sql));
                    if ($theme_installed == 0) {
                        $uninstalled_themes[] = $theme_name;
                    }
                }
            }
            return $uninstalled_themes;
        break;

        case 'dir':
          $sql = "SELECT * FROM " . $prefix . "_themes ORDER BY theme_name ASC";
            if (!$result = $nuke_db->sql_query($sql)) {
                $debugger->handle_error(_THEMES_ERROR_MESSAGE, _THEMES_ERROR);
            }
            $themes = array();
            while ($row=$nuke_db->sql_fetchrow($result)) {
                $active = $row['active'];
                $theme_name = $row['theme_name'];
                $groups = $row['groups'];
                $perms = $row['permissions'];
                $custom_name = $row['custom_name'];
                $themes = add_theme($themes, $theme_name, $custom_name, $groups, $perms, $active);
            }
            $nuke_db->sql_freeresult($result);
            $dir = opendir(NUKE_THEMES_DIR);
            while(false !== ($theme_name = readdir($dir))) {
                if(is_dir(NUKE_THEMES_DIR . $theme_name) && $theme_name != "." && $theme_name != ".." && $theme_name != ".svn") {
                    $sql = "SELECT * FROM " . $prefix . "_themes WHERE theme_name = '$theme_name'";
                    $theme_installed = $nuke_db->sql_numrows($nuke_db->sql_query($sql));
                    if ($theme_installed == 0) {
                        $themes = add_theme($themes, $theme_name, '', '', '', '');
                    }
                }
            }
        break;
    }
    return $themes;
}

function GetThemeSelect($name, $mode='user_themes', $other_user=false, $extra='', $current='', $show_default=1) {
    global $nuke_userinfo;
    if($other_user) $nuke_userinfo = $other_user;

    $themes = get_themes($mode);
    $select = "<select name=\"" . $name . "\" $extra>";
    if($show_default) {
        $dSelect = (is_default($nuke_userinfo['theme'])) ? "selected" : "";
        $select .= "<option value=\"\" $dSelect>"._THEMES_DEFAULT."</option>";
    }
    foreach($themes as $theme) {
        $name = (!empty($theme['custom_name'])) ? $theme['custom_name'] : $theme['theme_name'];
        $selected = (($nuke_userinfo['theme'] == $theme['theme_name']) || ($current == $theme['theme_name'])) ? "selected" : "";
        $select .= "<option value=\"" . $theme['theme_name'] . "\" $selected>" . $name . "</option>";
    }
    $select .= "</select>";

    return $select;
}

function ThemeBackup($theme) {
    global $nuke_db, $prefix, $Default_Theme, $cache;
        if(!is_default($theme) && theme_exists($Default_Theme)) { return $Default_Theme; }
        $cache->delete('nukeconfig', 'config');
        log_write('error', 'Your default theme is missing! ' . $Default_Theme . ' was NOT found!', 'Criticial Error');
        $themes = opendir(NUKE_THEMES_DIR);
        while(false !== ($theme_name = readdir($themes))) {
            if(is_dir(NUKE_THEMES_DIR . $theme_name) && $theme_name != "." && $theme_name != "..") {
                return $theme_name;
            }
        }
    die(_THEMES_PROBLEM);
}

function ThemeCount($theme) {
    global $nuke_db, $prefix, $nuke_user_prefix;
    list($count) = $nuke_db->sql_ufetchrow("SELECT COUNT(*) AS count FROM " . $nuke_user_prefix . "_users WHERE theme='" . $theme . "' AND user_id <> '1'");
    return $count;
}

function ChangeTheme($theme, $who) {
    global $nuke_db, $nuke_user_prefix, $nuke_userinfo;
	if(!$who) { $who = $nuke_userinfo['user_id']; }
    $nuke_db->sql_query('UPDATE ' . $nuke_user_prefix . '_users SET theme="' . $theme . '" WHERE user_id = "' . $who . '"');
	$nuke_userinfo['theme'] = $theme;
    UpdateCookie();
    nuke_redirect($_SERVER['REQUEST_URI']);
    return true;
}

function AllowThemeChange() {
    global $nuke_db, $prefix;
    static $usrthemeselect;
    list($usrthemeselect) = $nuke_db->sql_ufetchrow("SELECT config_value FROM " . $prefix . "_cnbya_config WHERE config_name = 'allowusertheme'");
    return(($usrthemeselect == 0) ? 1 : 0);
}

function LoadThemeInfo($theme) 
{
    global $nuke_db, $prefix, $params, $default, $cache;
    static $theme_info;
    if(isset($theme_info)) 
        return $theme_info; 

    if(!$theme_info = $cache->load($theme, 'themes')) 
    {
        $result = $nuke_db->sql_query("SELECT theme_info FROM " . $prefix . "_themes WHERE theme_name = '" . $theme . "'");
        $row = $nuke_db->sql_fetchrow($result);
        $nuke_db->sql_freeresult($result);
        $loaded_info = (!empty($row['theme_info'])) ? explode(':::', $row['theme_info']) : $default;
        $theme_info = array_combine($params, $loaded_info);
        $cache->save($theme, 'themes', $theme_info);
    }
    return $theme_info;
}
?>
