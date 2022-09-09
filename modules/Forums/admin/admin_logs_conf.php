<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                              admin_logs_conf.php
 *                              -------------------
 *     begin                : Jan 24 2003
 *     copyright            : Morpheus
 *     email                : morpheus@2037.biz
 *
 *     $Id: admin_logs_config.php,v 1.85.2.9 2003/01/24 18:31:54 Moprheus Exp $
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB2', 1);

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $module['Logs']['Logs Config'] = "$file";
    return;
}

//
// Load default header
//
$module_name = basename(dirname(dirname(__FILE__)));
$phpbb2_root_path = './../';
require_once($phpbb2_root_path . 'extension.inc');
require_once('./pagestart.' . $phpEx);
include_once("../../../includes/functions_log.php");
$template_nuke->set_filenames(array(
    "body" => "admin/logs_config_body.tpl")
);

$sql = "SELECT config_value AS all_admin
FROM " . NUKE_BB_LOGS_CONFIG_TABLE . "
WHERE config_name = 'all_admin' ";

if(!$result = $nuke_db->sql_query($sql))
{
   message_die(NUKE_CRITICAL_ERROR, "Could not query log config informations", "", __LINE__, __FILE__, $sql);
}
$row = $nuke_db->sql_fetchrow($result);
$all_admin_authorized = $row['all_admin'];

if ( $all_admin_authorized == '0' && $nuke_userdata['user_id'] <> '2' && !is_mod_admin($module_name) && $nuke_userdata['user_view_log'] <> '1' )
{
    message_die(NUKE_GENERAL_MESSAGE, $lang['Admin_not_authorized']);
}

$sql = "SELECT *
FROM " . NUKE_BB_LOGS_CONFIG_TABLE ;

if(!$result = $nuke_db->sql_query($sql))
{
   message_die(NUKE_CRITICAL_ERROR, "Could not query log config informations", "", __LINE__, __FILE__, $sql);
}
else
{
    while ( $row = $nuke_db->sql_fetchrow($result) )
    {
        $nuke_config_name = $row['config_name'];
        $nuke_config_value = $row['config_value'];
        $default_config[$nuke_config_name] = $nuke_config_value;

        $new[$nuke_config_name] = ( isset($HTTP_POST_VARS[$nuke_config_name]) ) ? $HTTP_POST_VARS[$nuke_config_name] : $default_config[$nuke_config_name];
        if ( isset($HTTP_POST_VARS['submit']) )
        {
            $sql = "UPDATE " . NUKE_BB_LOGS_CONFIG_TABLE . " SET
                config_value = '" . str_replace("\'", "''", $new[$nuke_config_name]) . "'
                WHERE config_name = '$nuke_config_name'";
            if( !$nuke_db->sql_query($sql) )
            {
                message_die(NUKE_GENERAL_ERROR, "Failed to update configuration for $nuke_config_name", "", __LINE__, __FILE__, $sql);
            }
        }
    }

    if( isset($HTTP_POST_VARS['submit']) )
    {
        $message = $lang['Log_Config_updated'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_conf.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(NUKE_GENERAL_MESSAGE, $message);
    }
}

$add_admin_username = ( isset($HTTP_POST_VARS['add_admin']) ) ? $HTTP_POST_VARS['add_admin'] : '';
$delete_admin_username = ( isset($HTTP_POST_VARS['delete_admin']) ) ? $HTTP_POST_VARS['delete_admin'] : array();

//
// Admins which can be allowed
//

$sql = "SELECT user_id, username
    FROM " . NUKE_USERS_TABLE . "
    WHERE user_level = '2'
    AND user_id <> '2'
    AND user_view_log = '0' ";
$result = $nuke_db->sql_query($sql);
if( !$result )
{
    message_die(NUKE_GENERAL_ERROR, "Couldn't selected informations about user.", "",__LINE__, __FILE__, $sql);
}

$choose = $nuke_db->sql_fetchrowset($result);
$add_admin_select = '<select name="add_admin_select">';

if( empty($choose) )
{
    $add_admin_select .= '<option value="">' . $lang['No_other_admins'] . '</option>';
}
else
{
    $nuke_user = array();
    for( $i = 0; $i < count($choose); $i++ )
    {
        $add_admin_select .= '<option value="' . $choose[$i]['user_id'] . '">' . $choose[$i]['username'] . '</option>';
    }
}
$add_admin_select .= '</select>';
$choose_username_add = ( isset($HTTP_POST_VARS['add_admin_select']) ) ? $HTTP_POST_VARS['add_admin_select'] : '';

if ( $add_admin_username )
{
    if ( $choose_username_add != '' )
    {
        //
        // Allow a admin to see the logs
        //
        $sql = "UPDATE " . NUKE_USERS_TABLE . "
            SET user_view_log = '1'
            WHERE user_id = '$choose_username_add' ";
            $result = $nuke_db->sql_query($sql);
            if( !$result )
            {
                message_die(NUKE_GENERAL_ERROR, "Couldn't allow this admin to see the logs.", "",__LINE__, __FILE__, $sql);
            }
            else
            {
                message_die(NUKE_GENERAL_MESSAGE, $lang['Admins_add_success'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_conf.$phpEx") . "\">", "</a>"));
            }
    }
    else
    {
        message_die(NUKE_GENERAL_MESSAGE, $lang['No_admins_allow'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_conf.$phpEx") . "\">", "</a>"));
    }
}

//
// Admins which can be disallowed
//

$sql = "SELECT user_id, username
    FROM " . NUKE_USERS_TABLE . "
    WHERE user_level = '1'
    AND user_id <> '2'
    AND user_view_log = '1' ";
$result = $nuke_db->sql_query($sql);
if( !$result )
{
    message_die(NUKE_GENERAL_ERROR, "Couldn't selected informations about user.", "",__LINE__, __FILE__, $sql);
}
$choose_delete = trim($nuke_db->sql_fetchrowset($result));
$delete_admin_select = '<select name="delete_admin_select[]" multiple="multiple" size="4">';

if( empty($choose_delete) )
{
    $delete_admin_select .= '<option value=""> ' . $lang['No_admins_authorized'] . '</option>';
}
else
{
    $nuke_user = array();
    for( $i = 0; $i < count($choose_delete); $i++ )
    {
        $delete_admin_select .= '<option value="' . $choose_delete[$i]['user_id'] . '">' . $choose_delete[$i]['username'] . '</option>';
    }
}
$delete_admin_select .= '</select>';

$choose_username_del = ( isset($HTTP_POST_VARS['delete_admin_select']) ) ? $HTTP_POST_VARS['delete_admin_select'] : array();
$choose_username_del_sql = implode(', ', $choose_username_del);

if ( $delete_admin_username )
{
    if ( $choose_username_del_sql != '' )
    {
        //
        // Disllow a admin to see the logs
        //
        $sql = "UPDATE " . NUKE_USERS_TABLE . "
            SET user_view_log = '0'
            WHERE user_id ";

            if ( count($choose_username_del) > 1 )
            {
                $sql .= "IN ($choose_username_del_sql)";
            }
            else
            {
                $sql .= " = $choose_username_del_sql ";
            }
            $result = $nuke_db->sql_query($sql);
            if( !$result )
            {
                message_die(NUKE_GENERAL_ERROR, "Couldn't disallow this admin to see the logs.", "",__LINE__, __FILE__, $sql);
            }
            else
            {
                message_die(NUKE_GENERAL_MESSAGE, $lang['Admins_del_success'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>"));
            }
    }
    else
    {
        message_die(NUKE_GENERAL_MESSAGE, $lang['No_admins_disallow'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>"));
    }
}

$do_prune = ( isset($HTTP_POST_VARS['do_prune']) ) ? TRUE : FALSE;

if ( $do_prune )
{
    $prune_days = ( isset($HTTP_POST_VARS['prune_days']) ) ? intval($HTTP_POST_VARS['prune_days']) : 0;

    $prune = prune_logs($prune_days);

    if ( $prune )
    {
        message_die(NUKE_GENERAL_MESSAGE, $lang['Prune_success'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_conf.$phpEx") . "\">", "</a>"));
    }
}
$all_admin_yes = ( $new['all_admin'] ) ? "checked=\"checked\"" : "";
$all_admin_no = ( !$new['all_admin'] ) ? "checked=\"checked\"" : "";
$template_nuke->assign_vars(array(
    "S_CONFIG_ACTION" => append_sid("admin_logs_conf.$phpEx"),

    "L_YES" => $lang['Yes'],
    "L_NO" => $lang['No'],
    "L_SUBMIT" => $lang['Submit'],
    "L_RESET" => $lang['Reset'],
    "L_ENABLED" => $lang['Enabled'],
    "L_DISABLED" => $lang['Disabled'],
    "L_ADD" => $lang['Add_disallow'],
    "L_DELETE" => $lang['Delete_disallow'],
    "L_LOG_CONFIG_TITLE" => $lang['Log_Config'],
    "L_LOG_CONFIG_TITLE_EXPLAIN" => $lang['Log_Config_explain'],
    "L_GENERAL_LOG_CONFIG" => $lang['General_Config_Log'],
    "L_ALLOW_OTHER_ADMIN" => $lang['Allow_all_admin'],
    "L_ALLOW_OTHER_ADMIN_EXPLAIN" => $lang['Allow_all_admin_explain'],
    "L_ADD_ADMIN_USERNAME" => $lang['Add_Admin_Username'],
    "L_DELETE_ADMIN_USERNAME" => $lang['Delete_Admin_Username'],
    "L_USERNAME_ADD_ADMIN_EXPLAIN" => $lang['Add_username_admin_explain'],
    "L_USERNAME_DELETE_ADMIN_EXPLAIN" => $lang['Delete_username_admin_explain'],
    "L_PRUNE_LOG" => $lang['Prune_of_logs'],
    "L_PRUNE" => $lang['Prune'],
    "L_PRUNE_EXPLAIN" => $lang['Prune_explain'],
    "L_DO_PRUNE" => $lang['Prune_!'],

    "S_ALLOW_ALL_ADMIN" => $all_admin_yes,
    "S_DISALLOW_ALL_ADMIN" => $all_admin_no,
    "S_ADD_ADMIN" => $add_admin_select,
    "S_DELETE_ADMIN" => $delete_admin_select)
);

$template_nuke->pparse("body");

include('./nuke_page_footer_admin.'.$phpEx);

?>