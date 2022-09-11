<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
*                           admin_statistics.php
*                            -------------------
*   begin                : Sat, Jan 04, 2003
*   copyright            : (C) 2003 Meik Sievertsen
*   email                : acyd.burn@gmx.de
*
*   $Id: admin_statistics.php,v 1.14 2003/03/16 19:38:27 acydburn Exp $
*
***************************************************************************/

/***************************************************************************
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
*
***************************************************************************/

global $directory_mode, $file_mode;

define('IN_PHPBB2', true);

//
// Let's set the root dir for phpBB
//
$phpbb2_root_path = './../';
require($phpbb2_root_path . 'extension.inc');
if (!empty($board_config))
{
    @include_once($phpbb2_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
}

if( !empty($setmodules) )
{
    $filename = basename(__FILE__);
    $nuke_module['Statistics']['Install_module'] = $filename . '?mode=mod_install';
    $nuke_module['Statistics']['Manage_modules'] = $filename . '?mode=mod_manage';
    return;
}
$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : FALSE;

if ($cancel)
{
    $no_nuke_page_header = TRUE;
}

require('pagestart.' . $phpEx);

$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : FALSE;

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
    $mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
    $mode = '';
}
@include_once($phpbb2_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
include($phpbb2_root_path . 'stats_mod/includes/constants.'.$phpEx);

$sql = "SELECT * FROM " . STATS_CONFIG_TABLE;
     
if ( !($result = $nuke_db->sql_query($sql)) )
{
    message_die(NUKE_GENERAL_ERROR, 'Could not query statistics config table', '', __LINE__, __FILE__, $sql);
}

$stats_config = array();

while ($row = $nuke_db->sql_fetchrow($result))
{
    $stats_config[$row['config_name']] = trim($row['config_value']);
}

include($phpbb2_root_path . 'stats_mod/includes/lang_functions.'.$phpEx);
include($phpbb2_root_path . 'stats_mod/includes/stat_functions.'.$phpEx);
include($phpbb2_root_path . 'stats_mod/includes/admin_functions.'.$phpEx);

if ($cancel)
{
    $url = 'admin/' . append_nuke_sid("admin_statistics.$phpEx?mode=mod_manage", true);
    
    $server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
    $server_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['server_name']));
    $server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';
    $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
    $url = preg_replace('/^\/?(.*?)\/?$/', '/\1', trim($url));

    // Redirect via an HTML form for PITA webservers
    if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
    {
        header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta nuke_redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be nuke_redirected</div></body></html>';
        exit;
    }

    // Behave as per HTTP/1.1 spec for others
    nuke_redirect($server_protocol . $server_name . $server_port . $script_name . $url);
    exit;
}

// BEGIN Install Module
if (($mode == 'mod_install') && ($submit))
{
    $template_nuke->set_filenames(array(
        'body' => 'admin/stat_install_module.tpl')
    );

    $update_id = (isset($HTTP_POST_VARS['update_id'])) ? intval($HTTP_POST_VARS['update_id']) : -1;

    if (isset($HTTP_POST_VARS['install_module']))
    {
        $filename = trim(stripslashes(htmlspecialchars($HTTP_POST_VARS['filename'])));
        
        if (!($fp = fopen($filename, 'r')) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to open ' . $filename);
        }

        read_pak_header($fp);
        fclose($fp);

        if (strstr($filename, 'test.pak'))
        {
            unlink($filename);
        }

        $stream = implode('', @file($filename));
        $info_file = read_pak_file($stream, 'INFO');
        $lang_file = read_pak_file($stream, 'LANG');
        $php_file = read_pak_file($stream, 'NUKE_MOD');
        
        $info_array = parse_info_file($info_file);
        
        $install_languages = ( isset($HTTP_POST_VARS['checked_languages']) ) ?  $HTTP_POST_VARS['checked_languages'] : array();
        for ($i = 0; $i < count($install_languages); $i++)
        {
            $install_languages[$i] = 'lang_' . trim($install_languages[$i]);
        }
        
        $lang_array = parse_lang_file($lang_file, $install_languages);
        
        build_module($info_array, $lang_array, $php_file, $update_id);

        if ($update_id == -1)
        {
            message_die(NUKE_GENERAL_MESSAGE, $lang['Module_installed']);
        }
        else
        {
            message_die(NUKE_GENERAL_MESSAGE, $lang['Module_updated']);
        }
    }

    if ( isset($HTTP_POST_VARS['fileselect']) )
    {
        $filename = $phpbb2_root_path . 'modules/pakfiles/' . trim($HTTP_POST_VARS['selected_pak_file']);
    }
    else if (isset($HTTP_POST_VARS['fileupload']))
    {
        $filename = $HTTP_POST_FILES['package']['tmp_name'];

        // check php upload-size
        if ( ($filename == 'none') || ($filename == '') )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to upload file, please use the pak file selector');
        }

        $contents = @implode('', @file($filename));

        if ($contents == '')
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to upload file, please use the pak file selector');
        }

        if (!file_exists($phpbb2_root_path . 'modules/cache'))
        {
            @umask(0);
            mkdir($phpbb2_root_path . 'modules/cache', $directory_mode);
        }
        
        if (!($fp = fopen($phpbb2_root_path . 'modules/cache/temp.pak', 'wt')))
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to write temp file');
        }

        fwrite($fp, $contents, strlen($contents));
        fclose($fp);

        $filename = $phpbb2_root_path . 'modules/cache/temp.pak';
    }
    else
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Module Package');
    }

    if (!($fp = fopen($filename, 'r')) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to open ' . $filename);
    }
    
    read_pak_header($fp);
    fclose($fp);

    $stream = implode('', @file($filename));
    $info_file = read_pak_file($stream, 'INFO');
    $lang_file = read_pak_file($stream, 'LANG');

    $s_hidden_fields = '<input type="hidden" name="filename" value="' . $filename . '">';

    // Prepare the Data
    $info_array = parse_info_file($info_file);
    $lang_array = parse_lang_file($lang_file);

    if (trim($info_array['short_name']) == '')
    {
        message_die(NUKE_GENERAL_ERROR, 'Short name not specified.', '', __LINE__, __FILE__, $sql);
    }
    
    if ($update_id == -1)
    {
        $sql = "SELECT short_name FROM " . MODULES_TABLE . " WHERE short_name = '" . trim($info_array['short_name']) . "'";

        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
        }
    
        if ($nuke_db->sql_numrows($result) > 0)
        {
            message_die(NUKE_GENERAL_ERROR, sprintf($lang['Inst_module_already_exist'], $info_array['short_name']));
        }
    }
    else
    {
        $sql = "SELECT * FROM " . MODULES_TABLE . " WHERE module_id = " . $update_id;

        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
        }
    
        if ($nuke_db->sql_numrows($result) == 0)
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to get Module ' . $update_id);
        }
        
        $row = $nuke_db->sql_fetchrow($result);

        if (trim($row['short_name']) != trim($info_array['short_name']))
        {
            message_die(NUKE_GENERAL_ERROR, $lang['Incorrect_update_module']);
        }
    }

    // Prepare Template
    $template_nuke->assign_block_vars('switch_install_module', array());

    // Info Array
    $template_nuke->assign_vars(array(
        'L_INSTALL_MODULE' => $lang['Install_module'],
        'L_INSTALL_MODULE_EXPLAIN' => $lang['Install_module_explain'],
        'L_MODULE_NAME' => $lang['Module_name'],
        'L_MODULE_DESCRIPTION' => $lang['Module_description'],
        'L_MODULE_VERSION' => $lang['Module_version'],
        'L_REQUIRED_STATS_VERSION' => $lang['Required_stats_version'],
        'L_INSTALLED_STATS_VERSION' => $lang['Installed_stats_version'],
        'L_MODULE_AUTHOR' => $lang['Module_author'],
        'L_AUTHOR_EMAIL' => $lang['Author_email'],
        'L_MODULE_URL' => $lang['Module_url'],
        'L_UPDATE_URL' => $lang['Update_url'],
        'L_PROVIDED_LANGUAGE' => $lang['Provided_language'],
        'L_INSTALL_LANGUAGE' => $lang['Install_language'],
        'L_INSTALL' => $lang['Install'],
        
        'MODULE_NAME' => nl2br($info_array['name']),
        'MODULE_DESCRIPTION' => nl2br($info_array['extra_info']),
        'MODULE_VERSION' => $info_array['version'],
        'STATS_VERSION' => $info_array['stats_mod_version'],
        'INSTALLED_STATS_VERSION' => $stats_config['version'],
        'MODULE_AUTHOR' => nl2br($info_array['author']),
        'AUTHOR_EMAIL' => nl2br($info_array['email']),
        'MODULE_URL' => nl2br($info_array['url']),
        'UPDATE_URL' => nl2br($info_array['check_update_site']))
    );

    @reset($lang_array);
    while (list($key, $data) = @each($lang_array))
    {
        $language_nuke = str_replace('lang_', '', $key);

        $template_nuke->assign_block_vars('languages', array(
            'MODULE_LANGUAGE' => $language_nuke)
        );

    }

    $s_hidden_fields .= '<input type="hidden" name="install_module" value="1">';
    if ($update_id != -1)
    {
        $s_hidden_fields .= '<input type="hidden" name="update_id" value="' . $update_id . '">';
    }

    $template_nuke->assign_vars(array(
        'S_HIDDEN_FIELDS' => $s_hidden_fields)
    );
}

if (($mode == 'mod_install') && (!$submit))
{
    $template_nuke->set_filenames(array(
        'body' => 'admin/stat_install_module.tpl')
    );

    // erst mal package auswhlen... oder hochladen
    if ( (!isset($HTTP_POST_VARS['fileupload'])) && (!isset($HTTP_POST_VARS['fileselect'])) )
    {
        $nuke_module_paks = array();
    
        $dir = @opendir($phpbb2_root_path . 'modules/pakfiles');

        while($file = @readdir($dir))
        {
            if( !@is_dir($phpbb2_root_path . 'modules/pakfiles' . '/' . $file) )
            {
                if ( preg_match('/\.pak$/i', $file) )
                {
                    $nuke_module_paks[] = $file;
                }
            }
        }

        @closedir($dir);

        if (count($nuke_module_paks) > 0)
        {
            $template_nuke->assign_block_vars('switch_select_module', array());

            $nuke_module_select_field = '<select name="selected_pak_file">';

            for ($i = 0; $i < count($nuke_module_paks); $i++)
            {
                $selected = ($i == 0) ? ' selected="selected"' : '';

                $nuke_module_select_field .= '<option value="' . $nuke_module_paks[$i] . '"' . $selected . '>' . $nuke_module_paks[$i] . '</option>';
            }
    
            $nuke_module_select_field .= '</select>';
            
            $s_hidden_fields = '<input type="hidden" name="fileselect" value="1">';

            $template_nuke->assign_vars(array(
                'L_SELECT_MODULE' => $lang['Select_module_pak'],
                'S_SELECT_MODULE' => $nuke_module_select_field,
                'S_SELECT_HIDDEN_FIELDS' => $s_hidden_fields)
            );
        
        }

        $template_nuke->assign_block_vars('switch_upload_module', array());

        $s_hidden_fields = '<input type="hidden" name="fileupload" value="1">';

        $template_nuke->assign_vars(array(
            'L_INSTALL_MODULE' => $lang['Install_module'],
            'L_INSTALL_MODULE_EXPLAIN' => $lang['Install_module_explain'],
            'L_UPLOAD_MODULE' => $lang['Upload_module_pak'],
            'L_SUBMIT' => $lang['Submit'],
            'S_ACTION' => append_nuke_sid($phpbb2_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode),
            'S_UPLOAD_HIDDEN_FIELDS' => $s_hidden_fields)
        );

    }
}
// END Install Module

// BEGIN Manage Modules
if ($mode == 'mod_manage')
{
    if (isset($HTTP_GET_VARS['move_up']))
    {
        $nuke_module_id = intval($HTTP_GET_VARS['move_up']);
        move_up($nuke_module_id);
    }
    else if (isset($HTTP_GET_VARS['move_down']))
    {
        $nuke_module_id = intval($HTTP_GET_VARS['move_down']);
        move_down($nuke_module_id);
    }
    else if (isset($HTTP_GET_VARS['activate']))
    {
        $nuke_module_id = intval($HTTP_GET_VARS['activate']);
        activate($nuke_module_id);
    }
    else if (isset($HTTP_GET_VARS['deactivate']))
    {
        $nuke_module_id = intval($HTTP_GET_VARS['deactivate']);
        deactivate($nuke_module_id);
    }
    
    $template_nuke->set_filenames(array(
        'body' => 'admin/stat_manage_body.tpl')
    );

    $sql = "SELECT m.*, i.* FROM " . MODULES_TABLE . " m, " . MODULE_INFO_TABLE . " i WHERE i.module_id = m.module_id ORDER BY module_order ASC";

    if (!($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get Module Informations', '', __LINE__, __FILE__, $sql);
    }

    if ($nuke_db->sql_numrows($result) == 0)
    {
        message_die(NUKE_GENERAL_MESSAGE, 'No installed Modules found.');
    }

    $template_nuke->assign_vars(array(
        'L_EDIT' => $lang['Edit'],
        'L_DELETE' => $lang['Delete'],
        'L_MOVE_UP' => $lang['Move_up'],
        'L_MOVE_DOWN' => $lang['Move_down'],
        'L_MANAGE_MODULES' => $lang['Manage_modules'],
        'L_MANAGE_MODULES_EXPLAIN' => $lang['Manage_modules_explain'])
    );

    while ($row = $nuke_db->sql_fetchrow($result))
    {
        $nuke_module_id = intval($row['module_id']);
        $nuke_module_active = (intval($row['active'])) ? TRUE : FALSE;

        $template_nuke->assign_block_vars('modulerow', array(
            'MODULE_NAME' => trim($row['long_name']),
            'MODULE_DESC' => trim(nl2br($row['extra_info'])),

            'U_VIEW_MODULE' => '../../../modules.php?name=Forums&amp;file=statistics&amp;preview='.$nuke_module_id,
            'U_MODULE_EDIT' => append_nuke_sid($phpbb2_root_path . 'admin/admin_edit_module.'.$phpEx.'?mode=mod_edit&amp;module='.$nuke_module_id),
            'U_MODULE_DELETE' => append_nuke_sid($phpbb2_root_path . 'admin/admin_statistics.'.$phpEx.'?mode=mod_delete&amp;module='.$nuke_module_id),
            'U_MODULE_MOVE_UP' => append_nuke_sid($phpbb2_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;move_up='.$nuke_module_id),
            'U_MODULE_MOVE_DOWN' => append_nuke_sid($phpbb2_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;move_down='.$nuke_module_id),
            'U_MODULE_ACTIVATE' => ($nuke_module_active) ? append_nuke_sid($phpbb2_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;deactivate='.$nuke_module_id) : append_nuke_sid($phpbb2_root_path . 'admin/admin_statistics.'.$phpEx.'?mode='.$mode.'&amp;activate='.$nuke_module_id),
            'ACTIVATE' => ($nuke_module_active) ? $lang['Deactivate'] : $lang['Activate'])
        );
    }
}
// END Manage Modules

// BEGIN Delete Module
if ($mode == 'mod_delete')
{
    $confirm = ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : FALSE;

    if (!$confirm)
    {
        if (isset($HTTP_GET_VARS['module']))
        {
            $nuke_module_id = intval($HTTP_GET_VARS['module']);
        }
        else
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to delete Module.');
        }

        $hidden_fields = '<input type="hidden" name="mode" value="'.$mode.'" /><input type="hidden" name="module_id" value="'.$nuke_module_id.'" />';
            
        $template_nuke->set_filenames(array(
            'body' => 'confirm_body.tpl')
        );

        $template_nuke->assign_vars(array(
            'MESSAGE_TITLE' => $lang['Confirm'],
            'MESSAGE_TEXT' => $lang['Confirm_delete_module'],

            'L_YES' => $lang['Yes'],
            'L_NO' => $lang['No'],

            'S_CONFIRM_ACTION' => append_nuke_sid($phpbb2_root_path . "admin/admin_statistics.$phpEx"),
            'S_HIDDEN_FIELDS' => $hidden_fields)
        );
    }
    else
    {
        if (isset($HTTP_POST_VARS['module_id']))
        {
            $nuke_module_id = intval($HTTP_POST_VARS['module_id']);
        }
        else
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to delete Module.');
        }
    
        // Firstly, we need the Module Informations ;)
        $sql = "SELECT * FROM " . MODULES_TABLE . " WHERE module_id = " . $nuke_module_id;
        
        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to get Module Informations', '', __LINE__, __FILE__, $sql);
        }

        if ($nuke_db->sql_numrows($result) == 0)
        {
            message_die(NUKE_GENERAL_MESSAGE, 'No Module Data found... unable to delete Module.');
        }

        $row = $nuke_db->sql_fetchrow($result);
        $short_name = trim($row['short_name']);
        
        // Ok, collect the Informations for deleting the Language Variables
        $language_nuke_directory = $phpbb2_root_path . 'modules/language';
        $language_nukes = array();

        if (!file_exists($language_nuke_directory))
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
        }

        if( $dir = @opendir($language_nuke_directory) )
        {
            while( $sub_dir = @readdir($dir) )
            {
                if( !is_file($language_nuke_directory . '/' . $sub_dir) && !is_link($language_nuke_directory . '/' . $sub_dir) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
                {
                    if (strstr($sub_dir, 'lang_'))
                    {
                        $language_nukes[] = trim($sub_dir);
                    }
                }
            }
        
            closedir($dir);
        }

        $new_language_data = array();

        // Ok, go through all Languages and generate new Language Files
        for ($i = 0; $i < count($language_nukes); $i++)
        {
            $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nukes[$i] . '/lang_modules.php';
            $file_content = implode('', file($language_nuke_file));
            if (trim($file_content) != '')
            {
                $file_content = delete_language_block($file_content, $short_name);
            }
/*            else 
            {
                message_die(NUKE_GENERAL_ERROR, 'ERROR: Empty Language File ? -> ' . $language_nuke_file);
            }*/

            $new_language_data[$language_nukes[$i]] = trim($file_content);
        }

        // Now begin the Transaction
        $sql = "DELETE FROM " . MODULES_TABLE . " WHERE module_id = " . $nuke_module_id;

        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to delete Module', '', __LINE__, __FILE__, $sql);
        }

        $sql = "DELETE FROM " . MODULE_INFO_TABLE . " WHERE module_id = " . $nuke_module_id;

        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to delete Module', '', __LINE__, __FILE__, $sql);
        }
        
        $sql = "DELETE FROM " . CACHE_TABLE . " WHERE module_id = " . $nuke_module_id;

        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to delete Module', '', __LINE__, __FILE__, $sql);
        }

        // was this the last module ?
        $sql = "SELECT * FROM " . MODULES_TABLE;

        if (!($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to select Modules', '', __LINE__, __FILE__, $sql);
        }
        
        if ($nuke_db->sql_numrows($result) == 0)
        {
            $delete_language_folder = TRUE;
        }
        else
        {
            $delete_language_folder = FALSE;
        }

        // We are through successfully ? hmm... this was not intended. anyway, delete the Language Variables
        if ($delete_language_folder)
        {
            for ($i = 0; $i < count($language_nukes); $i++)
            {
                $language_nuke = trim($language_nukes[$i]);
                $language_nuke_dir = $phpbb2_root_path . 'modules/language';
                $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';

                if (file_exists($language_nuke_file))
                {
                    chmod($language_nuke_file, $file_mode);
                    unlink($language_nuke_file);
                }

                if (file_exists($language_nuke_dir . '/' . $language_nuke))
                {
                    chmod($language_nuke_dir . '/' . $language_nuke, $directory_mode);
                    rmdir($language_nuke_dir . '/' . $language_nuke);
                }
            }

            chmod($language_nuke_dir, $directory_mode);
            rmdir($language_nuke_dir);
        }
        else
        {
            for ($i = 0; $i < count($language_nukes); $i++)
            {
                $language_nuke = trim($language_nukes[$i]);
                $language_nuke_dir = $phpbb2_root_path . 'modules/language';
                $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';

                if (!file_exists($language_nuke_dir))
                {
                    @umask(0);
                    mkdir($language_nuke_dir, $directory_mode);
                }
                else
                {
                    chmod($language_nuke_dir, $directory_mode);
                }
        
                if (!file_exists($language_nuke_dir . '/' . $language_nuke))
                {
                    @umask(0);
                    mkdir($language_nuke_dir . '/' . $language_nuke, $directory_mode);
                }
                else
                {
                    chmod($language_nuke_dir . '/' . $language_nuke, $directory_mode);
                }
        
                if (file_exists($language_nuke_file))
                {
                    chmod($language_nuke_file, $directory_mode);
                }
        
                if (!($fp = fopen($language_nuke_file, 'wt')))
                {
                    message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
                }

                fwrite($fp, $new_language_data[$language_nuke], strlen($new_language_data[$language_nuke]));
                fclose($fp);

                chmod($language_nuke_file, $file_mode);
                chmod($language_nuke_dir . '/' . $language_nuke, $directory_mode);
                chmod($language_nuke_dir, $directory_mode);
            }
        }
    
        // Delete the Module Files
        $directory = $phpbb2_root_path . 'modules/' . $short_name;
        $nuke_module_file = $phpbb2_root_path . 'modules/' . $short_name . '/module.php';

        if (file_exists($nuke_module_file))
        {
            chmod($nuke_module_file, $file_mode);
            unlink($nuke_module_file);
        }

        if (file_exists($directory))
        {
            chmod($directory, $directory_mode);
            rmdir($directory);
        }

        // Resync Order
        resync_module_order();
        
        message_die(NUKE_GENERAL_MESSAGE, 'Module successfully deleted');
    
    }
}
// END Delete Module
$template_nuke->pparse('body');

//
// Page Footer
//
include('./nuke_page_footer_admin.'.$phpEx);

?>