<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                            admin_disallow.php
 *                            -------------------
 *   begin                : Tuesday, Oct 05, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   Id: admin_disallow.php,v 1.9.2.2 2002/11/26 11:42:11 psotfx Exp
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

define('IN_PHPBB2', 1);

if( !empty($setmodules) )
{
        $filename = basename(__FILE__);
        $module['Users']['Disallow'] = $filename;

        return;
}

//
// Include required files, get $phpEx and check permissions
//
$phpbb2_root_path = "./../";
require($phpbb2_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if( isset($HTTP_POST_VARS['add_name']) )
{
        include("../../../includes/functions_validate.php");

        $disallowed_user = ( isset($HTTP_POST_VARS['disallowed_user']) ) ? trim($HTTP_POST_VARS['disallowed_user']) : trim($HTTP_GET_VARS['disallowed_user']);

        if ($disallowed_user == '')
        {
                message_die(NUKE_GENERAL_MESSAGE, $lang['Fields_empty']);
        }
        if( !validate_username($disallowed_user) )
        {
                $message = $lang['Disallowed_already'];
        }
        else
        {
                $sql = "INSERT INTO " . NUKE_DISALLOW_TABLE . " (disallow_username)
                        VALUES('" . str_replace("\'", "''", $disallowed_user) . "')";
                $result = $nuke_db->sql_query( $sql );
                if ( !$result )
                {
                        message_die(NUKE_GENERAL_ERROR, "Could not add disallowed user.", "",__LINE__, __FILE__, $sql);
                }
                $message = $lang['Disallow_successful'];
        }

        $message .= "<br /><br />" . sprintf($lang['Click_return_disallowadmin'], "<a href=\"" . append_sid("admin_disallow.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(NUKE_GENERAL_MESSAGE, $message);
}
else if( isset($HTTP_POST_VARS['delete_name']) )
{
        $disallowed_id = ( isset($HTTP_POST_VARS['disallowed_id']) ) ? intval( $HTTP_POST_VARS['disallowed_id'] ) : intval( $HTTP_GET_VARS['disallowed_id'] );

        $sql = "DELETE FROM " . NUKE_DISALLOW_TABLE . "
                WHERE disallow_id = $disallowed_id";
        $result = $nuke_db->sql_query($sql);
        if( !$result )
        {
                message_die(NUKE_GENERAL_ERROR, "Couldn't removed disallowed user.", "",__LINE__, __FILE__, $sql);
        }

        $message .= $lang['Disallowed_deleted'] . "<br /><br />" . sprintf($lang['Click_return_disallowadmin'], "<a href=\"" . append_sid("admin_disallow.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(NUKE_GENERAL_MESSAGE, $message);

}

//
// Grab the current list of disallowed usernames...
//
$sql = "SELECT *
        FROM " . NUKE_DISALLOW_TABLE;
$result = $nuke_db->sql_query($sql);
if( !$result )
{
        message_die(NUKE_GENERAL_ERROR, "Couldn't get disallowed users.", "", __LINE__, __FILE__, $sql );
}

$disallowed = $nuke_db->sql_fetchrowset($result);

//
// Ok now generate the info for the template, which will be put out no matter
// what mode we are in.
//
$disallow_select = '<select name="disallowed_id">';

if( empty($disallowed) )
{
        $disallow_select .= '<option value="">' . $lang['no_disallowed'] . '</option>';
}
else
{
        $nuke_user = array();
        for( $i = 0; $i < count($disallowed); $i++ )
        {
                $disallow_select .= '<option value="' . $disallowed[$i]['disallow_id'] . '">' . $disallowed[$i]['disallow_username'] . '</option>';
        }
}

$disallow_select .= '</select>';

$template_nuke->set_filenames(array(
        "body" => "admin/disallow_body.tpl")
);

$template_nuke->assign_vars(array(
        "S_DISALLOW_SELECT" => $disallow_select,
        "S_FORM_ACTION" => append_sid("admin_disallow.$phpEx"),

        "L_INFO" => $output_info,
        "L_DISALLOW_TITLE" => $lang['Disallow_control'],
        "L_DISALLOW_EXPLAIN" => $lang['Disallow_explain'],
        "L_DELETE" => $lang['Delete_disallow'],
        "L_DELETE_DISALLOW" => $lang['Delete_disallow_title'],
        "L_DELETE_EXPLAIN" => $lang['Delete_disallow_explain'],
        "L_ADD" => $lang['Add_disallow'],
        "L_ADD_DISALLOW" => $lang['Add_disallow_title'],
        "L_ADD_EXPLAIN" => $lang['Add_disallow_explain'],
        "L_USERNAME" => $lang['Username'])
);

$template_nuke->pparse("body");

include('./nuke_page_footer_admin.'.$phpEx);

?>