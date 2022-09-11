<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                            admin_ug_auth.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   Id: admin_ug_auth.php,v 1.13.2.9 2005/07/19 20:01:05 acydburn Exp
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

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
-=[Mod]=-
      Attachment Mod                           v2.4.1       07/20/2005
      Global Announcements                     v1.2.8       06/13/2005
      Advanced Username Color                  v1.0.5       06/13/2005
      Group Colors                             v1.0.0       10/20/2005
************************************************************************/

define('IN_PHPBB2', 1);

if( !empty($setmodules) )
{
        $filename = basename(__FILE__);
        $nuke_module['Users']['Permissions'] = $filename . "?mode=user";
        $nuke_module['Groups']['Permissions'] = $filename . "?mode=group";

        return;
}

//
// Load default header
//
$no_nuke_page_header = TRUE;

$phpbb2_root_path = "./../";
require($phpbb2_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$params = array('mode' => 'mode', 'user_id' => NUKE_POST_USERS_URL, 'group_id' => NUKE_POST_GROUPS_URL, 'adv' => 'adv');

while( list($var, $param) = @each($params) )
{
        if ( !empty($_POST[$param]) || !empty($_GET[$param]) )
        {
                $$var = ( !empty($_POST[$param]) ) ? $_POST[$param] : $_GET[$param];
        }
        else
        {
                $$var = "";
        }
}

$nuke_user_id = intval($nuke_user_id);
$group_id = intval($group_id);
$adv = intval($adv);
$mode = htmlspecialchars($mode);

//
// Start program - define vars
//
/*****[BEGIN]******************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
$forum_auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_vote', 'auth_pollcreate', 'auth_globalannounce');
/*****[END]********************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/

$nuke_auth_field_match = array(
        'auth_view' => NUKE_AUTH_VIEW,
        'auth_read' => NUKE_AUTH_READ,
        'auth_post' => NUKE_AUTH_POST,
        'auth_reply' => NUKE_AUTH_REPLY,
        'auth_edit' => NUKE_AUTH_EDIT,
        'auth_delete' => NUKE_AUTH_DELETE,
        'auth_sticky' => NUKE_AUTH_STICKY,
        'auth_announce' => NUKE_AUTH_ANNOUNCE,
        'auth_vote' => NUKE_AUTH_VOTE,
        'auth_pollcreate' => NUKE_AUTH_POLLCREATE,
/*****[BEGIN]******************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
        'auth_globalannounce' => NUKE_AUTH_GLOBALANNOUNCE);
/*****[END]********************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/

$field_names = array(
        'auth_view' => $lang['View'],
        'auth_read' => $lang['Read'],
        'auth_post' => $lang['Post'],
        /*--FNA--*/
        'auth_reply' => $lang['Reply'],
        'auth_edit' => $lang['Edit'],
        'auth_delete' => $lang['Delete'],
        'auth_sticky' => $lang['Sticky'],
        'auth_announce' => $lang['Announce'],
        'auth_vote' => $lang['Vote'],
        'auth_pollcreate' => $lang['Pollcreate'],
/*****[BEGIN]******************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
        'auth_globalannounce' => $lang['Globalannounce']);
/*****[END]********************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    Attachment Mod                      v2.4.1 ]
 ******************************************************/
attach_setup_usergroup_auth($forum_auth_fields, $nuke_auth_field_match, $field_names);
/*****[END]********************************************
 [ Mod:    Attachment Mod                      v2.4.1 ]
 ******************************************************/

// ---------------
// Start Functions
//
function check_auth($type, $key, $u_access, $is_admin)
{
        $nuke_auth_user = 0;

        if( count($u_access) )
        {
                for($j = 0; $j < count($u_access); $j++)
                {
                        $result = 0;
                        switch($type)
                        {
                                case NUKE_AUTH_ACL:
                                        $result = $u_access[$j][$key];

                                case NUKE_AUTH_MOD:
                                        $result = $result || $u_access[$j]['auth_mod'];

                                case NUKE_AUTH_ADMIN:
                                        $result = $result || $is_admin;
                                        break;
                        }

                        $nuke_auth_user = $nuke_auth_user || $result;
                }
        }
        else
        {
                $nuke_auth_user = $is_admin;
        }

        return $nuke_auth_user;
}
//
// End Functions
// -------------

if ( isset($_POST['submit']) && ( ( $mode == 'user' && $nuke_user_id ) || ( $mode == 'group' && $group_id ) ) )
{
        $nuke_user_level = '';
        if ( $mode == 'user' )
        {
                //
                // Get group_id for this user_id
                //
                $sql = "SELECT g.group_id, u.user_level
                        FROM " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_USERS_TABLE . " u, " . NUKE_GROUPS_TABLE . " g
                        WHERE u.user_id = '$nuke_user_id'
                                AND ug.user_id = u.user_id
                                AND g.group_id = ug.group_id
                                AND g.group_single_user = " . TRUE;
                if ( !($result = $nuke_db->sql_query($sql)) )
                {
                        message_die(NUKE_GENERAL_ERROR, 'Could not select info from user/user_group table', '', __LINE__, __FILE__, $sql);
                }

                $row = $nuke_db->sql_fetchrow($result);

                $group_id = intval($row['group_id']);
                $nuke_user_level = intval($row['user_level']);

                $nuke_db->sql_freeresult($result);
        }

        //
        // Carry out requests
        //
        if ( $mode == 'user' && $_POST['userlevel'] == 'admin' && $nuke_user_level != NUKE_ADMIN )
        {
                //
                // Make user an admin (if already user)
                //
            if ( $nuke_userdata['user_id'] != $nuke_user_id )

                {
                        $sql = "UPDATE " . NUKE_USERS_TABLE . "
                                SET user_level = " . NUKE_ADMIN . "
                                WHERE user_id = '$nuke_user_id'";
                        if ( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
                        }

                        $sql = "DELETE FROM " . NUKE_AUTH_ACCESS_TABLE . "
                                WHERE group_id = '$group_id'
                                        AND auth_mod = '0'";
                        if ( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, "Couldn't delete auth access info", "", __LINE__, __FILE__, $sql);
                        }

                        //
                        // Delete any entries in auth_access, they are not required if user is becoming an
                        // admin
                        //
/*****[BEGIN]******************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
                        $sql = "UPDATE " . NUKE_AUTH_ACCESS_TABLE . "
                                SET auth_view = '0', auth_read = '0', auth_post = '0', auth_reply = '0', auth_edit = '0', auth_delete = '0', auth_sticky = '0', auth_announce = '0', auth_globalannounce = 0
                                WHERE group_id = '$group_id'";
/*****[END]********************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
                        if ( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, "Couldn't update auth access", "", __LINE__, __FILE__, $sql);
                        }
                }

                $message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_nuke_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_nuke_sid("index.$phpEx?pane=right") . '">', '</a>');
                message_die(NUKE_GENERAL_MESSAGE, $message);
        }
        else
        {
                if ( $mode == 'user' && $_POST['userlevel'] == 'user' && $nuke_user_level == NUKE_ADMIN )
                {
                        //
                        // Make admin a user (if already admin) ... ignore if you're trying
                        // to change yourself from an admin to user!
                        //
                        if ( $nuke_userdata['user_id'] != $nuke_user_id )
                        {
/*****[BEGIN]******************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
                                $sql = "UPDATE " . NUKE_AUTH_ACCESS_TABLE . "
                                        SET auth_view = '0', auth_read = '0', auth_post = '0', auth_reply = '0', auth_edit = '0', auth_delete = '0', auth_sticky = '0', auth_announce = '0', auth_globalannounce = 0
                                        WHERE group_id = '$group_id'";
/*****[END]********************************************
 [ Mod:     Global Announcements               v1.2.8 ]
 ******************************************************/
                                if ( !($result = $nuke_db->sql_query($sql)) )
                                {
                                        message_die(NUKE_GENERAL_ERROR, 'Could not update auth access', '', __LINE__, __FILE__, $sql);
                                }

                                //
                                // Update users level, reset to NUKE_USER
                                //
                                $sql = "UPDATE " . NUKE_USERS_TABLE . "
                                        SET user_level = " . NUKE_USER . "
                                        WHERE user_id = '$nuke_user_id'";
                                if ( !($result = $nuke_db->sql_query($sql)) )
                                {
                                        message_die(NUKE_GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
                                }
                        }

                        $message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_nuke_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_nuke_sid("index.$phpEx?pane=right") . '">', '</a>');
                }
                else
                {

                        $change_mod_list = ( isset($_POST['moderator']) ) ? $_POST['moderator'] : array();

            			if ( empty($adv) )
            			{
            				$sql = "SELECT f.*
            					FROM " . NUKE_FORUMS_TABLE . " f, " . NUKE_CATEGORIES_TABLE . " c
            					WHERE f.cat_id = c.cat_id
            					ORDER BY c.cat_order, f.forum_order ASC";
            				if ( !($result = $nuke_db->sql_query($sql)) )
            				{
            					message_die(NUKE_GENERAL_ERROR, "Couldn't obtain forum information", "", __LINE__, __FILE__, $sql);
            				}

            				$forum_access = $forum_auth_level_fields = array();
            				while( $row = $nuke_db->sql_fetchrow($result) )
            				{
            					$forum_access[] = $row;
            				}
            				$nuke_db->sql_freeresult($result);

            				for($i = 0; $i < count($forum_access); $i++)
            				{
            					$forum_id = $forum_access[$i]['forum_id'];

            					for($j = 0; $j < count($forum_auth_fields); $j++)
            					{
            						$forum_auth_level_fields[$forum_id][$forum_auth_fields[$j]] = $forum_access[$i][$forum_auth_fields[$j]] == NUKE_AUTH_ACL;
            					}
            				}

            				while( list($forum_id, $value) = @each($_POST['private']) )
            				{
            					while( list($nuke_auth_field, $exists) = @each($forum_auth_level_fields[$forum_id]) )
            					{
            						if ($exists)
            						{
            							$change_acl_list[$forum_id][$nuke_auth_field] = $value;
            						}
            					}
            				}
                        }
                        else
                        {
                                $change_acl_list = array();
                                for($j = 0; $j < count($forum_auth_fields); $j++)
                                {
                                        $nuke_auth_field = $forum_auth_fields[$j];

                                        while( list($forum_id, $value) = @each($_POST['private_' . $nuke_auth_field]) )
                                        {
                                                $change_acl_list[$forum_id][$nuke_auth_field] = $value;
                                        }
                                }
                        }

            $sql = 'SELECT f.*
                FROM ' . NUKE_FORUMS_TABLE . ' f, ' . NUKE_CATEGORIES_TABLE . ' c
                WHERE f.cat_id = c.cat_id
                ORDER BY c.cat_order, f.forum_order';

                        if ( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, "Couldn't obtain forum information", "", __LINE__, __FILE__, $sql);
                        }

                        $forum_access = array();
                        while( $row = $nuke_db->sql_fetchrow($result) )
                        {
                                $forum_access[] = $row;
                        }
                        $nuke_db->sql_freeresult($result);

                        $sql = ( $mode == 'user' ) ? "SELECT aa.* FROM " . NUKE_AUTH_ACCESS_TABLE . " aa, " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_GROUPS_TABLE. " g WHERE ug.user_id = $nuke_user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = " . TRUE : "SELECT * FROM " . NUKE_AUTH_ACCESS_TABLE . " WHERE group_id = '$group_id'";
                        if ( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
                        }

                        $nuke_auth_access = array();
                        while( $row = $nuke_db->sql_fetchrow($result) )
                        {
                                $nuke_auth_access[$row['forum_id']] = $row;
                        }
                        $nuke_db->sql_freeresult($result);

                        $forum_auth_action = array();
                        $update_acl_status = array();
                        $update_mod_status = array();

                        for($i = 0; $i < count($forum_access); $i++)
                        {
                                $forum_id = $forum_access[$i]['forum_id'];

                                if (
                                        ( isset($nuke_auth_access[$forum_id]['auth_mod']) && $change_mod_list[$forum_id] != $nuke_auth_access[$forum_id]['auth_mod'] ) ||
                    					( !isset($nuke_auth_access[$forum_id]['auth_mod']) && !empty($change_mod_list[$forum_id]) )
                    				)
                    				{
                    					$update_mod_status[$forum_id] = $change_mod_list[$forum_id];

                                        if ( !$update_mod_status[$forum_id] )
                                        {
                                                $forum_auth_action[$forum_id] = 'delete';
                                        }
                                        else if ( !isset($nuke_auth_access[$forum_id]['auth_mod']) )
                                        {
                                                $forum_auth_action[$forum_id] = 'insert';
                                        }
                                        else
                                        {
                                                $forum_auth_action[$forum_id] = 'update';
                                        }
                                }

                                for($j = 0; $j < count($forum_auth_fields); $j++)
                                {
                                        $nuke_auth_field = $forum_auth_fields[$j];

                                        if( $forum_access[$i][$nuke_auth_field] == NUKE_AUTH_ACL && isset($change_acl_list[$forum_id][$nuke_auth_field]) )
                                        {
                                                if ( ( empty($nuke_auth_access[$forum_id]['auth_mod']) &&
                                                        ( isset($nuke_auth_access[$forum_id][$nuke_auth_field]) && $change_acl_list[$forum_id][$nuke_auth_field] != $nuke_auth_access[$forum_id][$nuke_auth_field] ) ||
                                                        ( !isset($nuke_auth_access[$forum_id][$nuke_auth_field]) && !empty($change_acl_list[$forum_id][$nuke_auth_field]) ) ) ||
                                                        !empty($update_mod_status[$forum_id])
                                                )
                                                {
                                                        $update_acl_status[$forum_id][$nuke_auth_field] = ( !empty($update_mod_status[$forum_id]) ) ? 0 :  $change_acl_list[$forum_id][$nuke_auth_field];

                                                        if ( isset($nuke_auth_access[$forum_id][$nuke_auth_field]) && empty($update_acl_status[$forum_id][$nuke_auth_field]) && $forum_auth_action[$forum_id] != 'insert' && $forum_auth_action[$forum_id] != 'update' )
                                                        {
                                                                $forum_auth_action[$forum_id] = 'delete';
                                                        }
                                                        else if ( !isset($nuke_auth_access[$forum_id][$nuke_auth_field]) && !( $forum_auth_action[$forum_id] == 'delete' && empty($update_acl_status[$forum_id][$nuke_auth_field]) ) )
                                                        {
                                                                $forum_auth_action[$forum_id] = 'insert';
                                                        }
                                                        else if ( isset($nuke_auth_access[$forum_id][$nuke_auth_field]) && !empty($update_acl_status[$forum_id][$nuke_auth_field]) )
                                                        {
                                                                $forum_auth_action[$forum_id] = 'update';
                                                        }
                                                }
                                                else if ( ( empty($nuke_auth_access[$forum_id]['auth_mod']) &&
                                                        ( isset($nuke_auth_access[$forum_id][$nuke_auth_field]) && $change_acl_list[$forum_id][$nuke_auth_field] == $nuke_auth_access[$forum_id][$nuke_auth_field] ) ) && $forum_auth_action[$forum_id] == 'delete' )
                                                {
                                                        $forum_auth_action[$forum_id] = 'update';
                                                }
                                        }
                                }
                        }

                        //
                        // Checks complete, make updates to DB
                        //
                        $delete_sql = '';
                        while( list($forum_id, $action) = @each($forum_auth_action) )
                        {
                                if ( $action == 'delete' )
                                {
                                        $delete_sql .= ( ( $delete_sql != '' ) ? ', ' : '' ) . $forum_id;
                                }
                                else
                                {
                                        if ( $action == 'insert' )
                                        {
                                                $sql_field = '';
                                                $sql_value = '';
                                                while ( list($nuke_auth_type, $value) = @each($update_acl_status[$forum_id]) )
                                                {
                                                        $sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . $nuke_auth_type;
                                                        $sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . $value;
                                                }
                                                $sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . 'auth_mod';
                                                $sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . ( ( !isset($update_mod_status[$forum_id]) ) ? 0 : $update_mod_status[$forum_id]);

                                                $sql = "INSERT INTO " . NUKE_AUTH_ACCESS_TABLE . " (forum_id, group_id, $sql_field)
                                                        VALUES ($forum_id, $group_id, $sql_value)";
                                        }
                                        else
                                        {
                                                $sql_values = '';
                                                while ( list($nuke_auth_type, $value) = @each($update_acl_status[$forum_id]) )
                                                {
                                                        $sql_values .= ( ( $sql_values != '' ) ? ', ' : '' ) . $nuke_auth_type . ' = ' . $value;
                                                }
                                                $sql_values .= ( ( $sql_values != '' ) ? ', ' : '' ) . 'auth_mod = ' . ( ( !isset($update_mod_status[$forum_id]) ) ? 0 : $update_mod_status[$forum_id]);

                                                $sql = "UPDATE " . NUKE_AUTH_ACCESS_TABLE . "
                                                        SET $sql_values
                                                        WHERE group_id = '$group_id'
                                                                AND forum_id = '$forum_id'";
                                        }
                                        if( !($result = $nuke_db->sql_query($sql)) )
                                        {
                                                message_die(NUKE_GENERAL_ERROR, "Couldn't update private forum permissions", "", __LINE__, __FILE__, $sql);
                                        }
                                }
                        }

                        if ( $delete_sql != '' )
                        {
                                $sql = "DELETE FROM " . NUKE_AUTH_ACCESS_TABLE . "
                                        WHERE group_id = '$group_id'
                                                AND forum_id IN ($delete_sql)";
                                if( !($result = $nuke_db->sql_query($sql)) )
                                {
                                        message_die(NUKE_GENERAL_ERROR, "Couldn't delete permission entries", "", __LINE__, __FILE__, $sql);
                                }
                        }

                        $l_auth_return = ( $mode == 'user' ) ? $lang['Click_return_userauth'] : $lang['Click_return_groupauth'];
                        $message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_nuke_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_nuke_sid("index.$phpEx?pane=right") . '">', '</a>');
                }

                //
                // Update user level to mod for appropriate users
                //
                $sql = "SELECT u.user_id
                        FROM " . NUKE_AUTH_ACCESS_TABLE . " aa, " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_USERS_TABLE . " u
                        WHERE ug.group_id = aa.group_id
                                AND u.user_id = ug.user_id
                                AND ug.user_pending = 0
                                AND u.user_level NOT IN (" . NUKE_MOD . ", " . NUKE_ADMIN . ")
                        GROUP BY u.user_id
                        HAVING SUM(aa.auth_mod) > 0";
                if ( !($result = $nuke_db->sql_query($sql)) )
                {
                        message_die(NUKE_GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
                }

                $set_mod = '';
                while( $row = $nuke_db->sql_fetchrow($result) )
                {
                        $set_mod .= ( ( $set_mod != '' ) ? ', ' : '' ) . $row['user_id'];
                }
                $nuke_db->sql_freeresult($result);

                //
                // Update user level to user for appropriate users
                //
                switch ( SQL_LAYER )
                {
                        case 'postgresql':
                                $sql = "SELECT u.user_id
                                        FROM " . NUKE_USERS_TABLE . " u, " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_AUTH_ACCESS_TABLE . " aa
                                        WHERE ug.user_id = u.user_id
                                                AND aa.group_id = ug.group_id
                                                AND u.user_level NOT IN (" . NUKE_USER . ", " . NUKE_ADMIN . ")
                                        GROUP BY u.user_id
                                        HAVING SUM(aa.auth_mod) = 0
                                        UNION (
                                                SELECT u.user_id
                                                FROM " . NUKE_USERS_TABLE . " u
                                                WHERE NOT EXISTS (
                                                        SELECT aa.auth_mod
                                                        FROM " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_AUTH_ACCESS_TABLE . " aa
                                                        WHERE ug.user_id = u.user_id
                                                                AND aa.group_id = ug.group_id
                                                )
                                                AND u.user_level NOT IN (" . NUKE_USER . ", " . NUKE_ADMIN . ")
                                                GROUP BY u.user_id
                                        )";
                                break;
                        case 'oracle':
                                $sql = "SELECT u.user_id
                                        FROM " . NUKE_USERS_TABLE . " u, " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_AUTH_ACCESS_TABLE . " aa
                                        WHERE ug.user_id = u.user_id(+)
                                                AND aa.group_id = ug.group_id(+)
                                                AND u.user_level NOT IN (" . NUKE_USER . ", " . NUKE_ADMIN . ")
                                        GROUP BY u.user_id
                                        HAVING SUM(aa.auth_mod) = 0";
                                break;
                        default:
                                $sql = "SELECT u.user_id
                                        FROM ( ( " . NUKE_USERS_TABLE . " u
                                        LEFT JOIN " . NUKE_USER_GROUP_TABLE . " ug ON ug.user_id = u.user_id )
                                        LEFT JOIN " . NUKE_AUTH_ACCESS_TABLE . " aa ON aa.group_id = ug.group_id )
                                        WHERE u.user_level NOT IN (" . NUKE_USER . ", " . NUKE_ADMIN . ")
                                        GROUP BY u.user_id
                                        HAVING SUM(aa.auth_mod) = 0";
                                break;
                }
                if ( !($result = $nuke_db->sql_query($sql)) )
                {
                        message_die(NUKE_GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
                }

                $unset_mod = "";
                while( $row = $nuke_db->sql_fetchrow($result) )
                {
                        $unset_mod .= ( ( $unset_mod != '' ) ? ', ' : '' ) . $row['user_id'];
                }
                $nuke_db->sql_freeresult($result);

                if ( $set_mod != '' )
                {
                        $sql = "UPDATE " . NUKE_USERS_TABLE . "
                                SET user_level = " . NUKE_MOD . "
                                WHERE user_id IN ($set_mod)";
                        if( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql);
                        }
                }

                if ( $unset_mod != '' )
                {
                        $sql = "UPDATE " . NUKE_USERS_TABLE . "
                                SET user_level = " . NUKE_USER . "
                                WHERE user_id IN ($unset_mod)";
                        if( !($result = $nuke_db->sql_query($sql)) )
                        {
                                message_die(NUKE_GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql);
                        }
                }
                
        $sql = 'SELECT user_id FROM ' . NUKE_USER_GROUP_TABLE . "
            WHERE group_id = $group_id";
        $result = $nuke_db->sql_query($sql);

        $group_user = array();
        while ($row = $nuke_db->sql_fetchrow($result))
        {
            $group_user[$row['user_id']] = $row['user_id'];
        }
        $nuke_db->sql_freeresult($result);

        $sql = "SELECT ug.user_id, COUNT(auth_mod) AS is_auth_mod
            FROM " . NUKE_AUTH_ACCESS_TABLE . " aa, " . NUKE_USER_GROUP_TABLE . " ug
            WHERE ug.user_id IN (" . implode(', ', $group_user) . ")
                AND aa.group_id = ug.group_id
                AND aa.auth_mod = 1
            GROUP BY ug.user_id";
        if ( !($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Please add someone to this group, we could not obtain moderator status', '', __LINE__, __FILE__, $sql);
        }

        while ($row = $nuke_db->sql_fetchrow($result))
        {
            if ($row['is_auth_mod'])
            {
                unset($group_user[$row['user_id']]);
            }
        }
        $nuke_db->sql_freeresult($result);

        if (count($group_user))
        {
/*****[BEGIN]******************************************
 [ Base:    Nuke Patched                       v3.1.0 ]
 ******************************************************/
            $sql = "UPDATE " . NUKE_USERS_TABLE . "
                SET user_level = " . NUKE_USER . "
                WHERE user_id IN (" . implode(', ', $group_user) . ") AND user_level = " . NUKE_MOD;
/*****[END]********************************************
 [ Base:    Nuke Patched                       v3.1.0 ]
 ******************************************************/
            if ( !($result = $nuke_db->sql_query($sql)) )
            {
                message_die(NUKE_GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
            }
        }

                message_die(NUKE_GENERAL_MESSAGE, $message);
                $nuke_cache->delete('forum_moderators', 'config');
        }
}
else if ( ( $mode == 'user' && ( isset($_POST['username']) || $nuke_user_id ) ) || ( $mode == 'group' && $group_id ) )
{
        if ( isset($_POST['username']) )
        {
                $this_userdata = get_userdata($_POST['username'], true);
                if ( !is_array($this_userdata) )
                {
                        message_die(NUKE_GENERAL_MESSAGE, $lang['No_such_user']);
                }
                $nuke_user_id = $this_userdata['user_id'];
        }

        //
        // Front end
        //
        $sql = "SELECT f.*
                FROM " . NUKE_FORUMS_TABLE . " f, " . NUKE_CATEGORIES_TABLE . " c
                WHERE f.cat_id = c.cat_id
                ORDER BY c.cat_order, f.forum_order ASC";
        if ( !($result = $nuke_db->sql_query($sql)) )
        {
                message_die(NUKE_GENERAL_ERROR, "Couldn't obtain forum information", "", __LINE__, __FILE__, $sql);
        }

        $forum_access = array();
        while( $row = $nuke_db->sql_fetchrow($result) )
        {
                $forum_access[] = $row;
        }
        $nuke_db->sql_freeresult($result);

        if( empty($adv) )
        {
                for($i = 0; $i < count($forum_access); $i++)
                {
                        $forum_id = $forum_access[$i]['forum_id'];

                        $forum_auth_level[$forum_id] = NUKE_AUTH_ALL;

                        for($j = 0; $j < count($forum_auth_fields); $j++)
                        {
                                $forum_access[$i][$forum_auth_fields[$j]] . ' :: ';
                                if ( $forum_access[$i][$forum_auth_fields[$j]] == NUKE_AUTH_ACL )
                                {
                                        $forum_auth_level[$forum_id] = NUKE_AUTH_ACL;
                                        $forum_auth_level_fields[$forum_id][] = $forum_auth_fields[$j];
                                }
                        }
                }
        }

//
// Check if a private user group existis for this user and if not, create one.
//
        $sql = "SELECT user_id FROM " . NUKE_USER_GROUP_TABLE . " WHERE user_id = '$nuke_user_id'";
        $result = $nuke_db->sql_query($sql);
        $row = $nuke_db->sql_fetchrow($result);
        $nuke_user_check = $row['user_id'];
        if ( $nuke_user_check != $nuke_user_id )
        {
            $sql = "SELECT MAX(group_id) AS total
                    FROM " . NUKE_GROUPS_TABLE;
            if ( !($result = $nuke_db->sql_query($sql)) )
            {
                message_die(NUKE_GENERAL_ERROR, 'Could not select last group_id information', '', __LINE__, __FILE__, $sql);
            }
            if ( !($row = $nuke_db->sql_fetchrow($result)) )
            {
                message_die(NUKE_GENERAL_ERROR, 'Could not obtain next group_id information', '', __LINE__, __FILE__, $sql);
            }
            $group_id = $row['total'] + 1;
            $sql = "INSERT INTO " . NUKE_GROUPS_TABLE . " (group_id, group_name, group_description, group_single_user, group_moderator)
                    VALUES ('$group_id', '', 'Personal User', '1', '0')";
            if ( !($result = $nuke_db->sql_query($sql)) )
            {
                message_die(NUKE_GENERAL_ERROR, 'Could not create private group', '', __LINE__, __FILE__, $sql);
            }
            $sql = "INSERT INTO " . NUKE_USER_GROUP_TABLE . " (group_id, user_id, user_pending)
                    VALUES ('$group_id', '$nuke_user_id', '0')";
            if ( !($result = $nuke_db->sql_query($sql)) )
            {
                message_die(NUKE_GENERAL_ERROR, 'Could not create private group', '', __LINE__, __FILE__, $sql);
            }
        }
//
//  End Private group check.
//
        $sql = "SELECT u.user_id, u.username, u.user_level, g.group_id, g.group_name, g.group_single_user, ug.user_pending FROM " . NUKE_USERS_TABLE . " u, " . NUKE_GROUPS_TABLE . " g, " . NUKE_USER_GROUP_TABLE . " ug WHERE ";
        $sql .= ( $mode == 'user' ) ? "u.user_id = '$nuke_user_id' AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = '$group_id' AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
        if ( !($result = $nuke_db->sql_query($sql)) )
        {
                message_die(NUKE_GENERAL_ERROR, "Couldn't obtain user/group information", "", __LINE__, __FILE__, $sql);
        }
        $ug_info = array();
        while( $row = $nuke_db->sql_fetchrow($result) )
        {
                $ug_info[] = $row;
        }
        $nuke_db->sql_freeresult($result);

        $sql = ( $mode == 'user' ) ? "SELECT aa.*, g.group_single_user FROM " . NUKE_AUTH_ACCESS_TABLE . " aa, " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_GROUPS_TABLE. " g WHERE ug.user_id = $nuke_user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = 1" : "SELECT * FROM " . NUKE_AUTH_ACCESS_TABLE . " WHERE group_id = '$group_id'";
        if ( !($result = $nuke_db->sql_query($sql)) )
        {
                message_die(NUKE_GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
        }

        $nuke_auth_access = array();
        $nuke_auth_access_count = array();
        while( $row = $nuke_db->sql_fetchrow($result) )
        {
                $nuke_auth_access[$row['forum_id']][] = $row;
                $nuke_auth_access_count[$row['forum_id']]++;
        }
        $nuke_db->sql_freeresult($result);

        $is_admin = ( $mode == 'user' ) ? ( ( $ug_info[0]['user_level'] == NUKE_ADMIN && $ug_info[0]['user_id'] != NUKE_ANONYMOUS ) ? 1 : 0 ) : 0;

        for($i = 0; $i < count($forum_access); $i++)
        {
                $forum_id = $forum_access[$i]['forum_id'];

                unset($prev_acl_setting);
                for($j = 0; $j < count($forum_auth_fields); $j++)
                {
                        $key = $forum_auth_fields[$j];
                        $value = $forum_access[$i][$key];

                        switch( $value )
                        {
                                case NUKE_AUTH_ALL:
                                case NUKE_AUTH_REG:
                                        $nuke_auth_ug[$forum_id][$key] = 1;
                                        break;

                                case NUKE_AUTH_ACL:
                                        $nuke_auth_ug[$forum_id][$key] = ( !empty($nuke_auth_access_count[$forum_id]) ) ? check_auth(NUKE_AUTH_ACL, $key, $nuke_auth_access[$forum_id], $is_admin) : 0;
                                        $nuke_auth_field_acl[$forum_id][$key] = $nuke_auth_ug[$forum_id][$key];

                                        if ( isset($prev_acl_setting) )
                                        {
                                                if ( $prev_acl_setting != $nuke_auth_ug[$forum_id][$key] && empty($adv) )
                                                {
                                                        $adv = 1;
                                                }
                                        }

                                        $prev_acl_setting = $nuke_auth_ug[$forum_id][$key];

                                        break;

                                case NUKE_AUTH_MOD:
                                        $nuke_auth_ug[$forum_id][$key] = ( !empty($nuke_auth_access_count[$forum_id]) ) ? check_auth(NUKE_AUTH_MOD, $key, $nuke_auth_access[$forum_id], $is_admin) : 0;
                                        break;

                                case NUKE_AUTH_ADMIN:
                                        $nuke_auth_ug[$forum_id][$key] = $is_admin;
                                        break;

                                default:
                                        $nuke_auth_ug[$forum_id][$key] = 0;
                                        break;
                        }
                }

                //
                // Is user a moderator?
                //
                $nuke_auth_ug[$forum_id]['auth_mod'] = ( !empty($nuke_auth_access_count[$forum_id]) ) ? check_auth(NUKE_AUTH_MOD, 'auth_mod', $nuke_auth_access[$forum_id], 0) : 0;
        }

        $i = 0;
        @reset($nuke_auth_ug);
        while( list($forum_id, $nuke_user_ary) = @each($nuke_auth_ug) )
        {
                if ( empty($adv) )
                {
                        if ( $forum_auth_level[$forum_id] == NUKE_AUTH_ACL )
                        {
                                $allowed = 1;

                                for($j = 0; $j < count($forum_auth_level_fields[$forum_id]); $j++)
                                {
                                        if ( !$nuke_auth_ug[$forum_id][$forum_auth_level_fields[$forum_id][$j]] )
                                        {
                                                $allowed = 0;
                                        }
                                }

                                $optionlist_acl = '<select name="private[' . $forum_id . ']">';

                                if ( $is_admin || $nuke_user_ary['auth_mod'] )
                                {
                                        $optionlist_acl .= '<option value="1">' . $lang['Allowed_Access'] . '</option>';
                                }
                                else if ( $allowed )
                                {
                                        $optionlist_acl .= '<option value="1" selected="selected">' . $lang['Allowed_Access'] . '</option><option value="0">'. $lang['Disallowed_Access'] . '</option>';
                                }
                                else
                                {
                                        $optionlist_acl .= '<option value="1">' . $lang['Allowed_Access'] . '</option><option value="0" selected="selected">' . $lang['Disallowed_Access'] . '</option>';
                                }

                                $optionlist_acl .= '</select>';
                        }
                        else
                        {
                                $optionlist_acl = '&nbsp;';
                        }
                }
                else
                {
                        for($j = 0; $j < count($forum_access); $j++)
                        {
                                if ( $forum_access[$j]['forum_id'] == $forum_id )
                                {
                                        for($k = 0; $k < count($forum_auth_fields); $k++)
                                        {
                                                $field_name = $forum_auth_fields[$k];

                                                if( $forum_access[$j][$field_name] == NUKE_AUTH_ACL )
                                                {
                                                        $optionlist_acl_adv[$forum_id][$k] = '<select name="private_' . $field_name . '[' . $forum_id . ']">';

                                                        if( isset($nuke_auth_field_acl[$forum_id][$field_name]) && !($is_admin || $nuke_user_ary['auth_mod']) )
                                                        {
                                                                if( !$nuke_auth_field_acl[$forum_id][$field_name] )
                                                                {
                                                                        $optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
                                                                }
                                                                else
                                                                {
                                                                        $optionlist_acl_adv[$forum_id][$k] .= '<option value="1" selected="selected">' . $lang['ON'] . '</option><option value="0">' . $lang['OFF'] . '</option>';
                                                                }
                                                        }
                                                        else
                                                        {
                                                                if( $is_admin || $nuke_user_ary['auth_mod'] )
                                                                {
                                                                        $optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option>';
                                                                }
                                                                else
                                                                {
                                                                        $optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
                                                                }
                                                        }

                                                        $optionlist_acl_adv[$forum_id][$k] .= '</select>';

                                                }
                                        }
                                }
                        }
                }

                $optionlist_mod = '<select name="moderator[' . $forum_id . ']">';
                $optionlist_mod .= ( $nuke_user_ary['auth_mod'] ) ? '<option value="1" selected="selected">' . $lang['Is_Moderator'] . '</option><option value="0">' . $lang['Not_Moderator'] . '</option>' : '<option value="1">' . $lang['Is_Moderator'] . '</option><option value="0" selected="selected">' . $lang['Not_Moderator'] . '</option>';
                $optionlist_mod .= '</select>';

                $row_class = ( !( $i % 2 ) ) ? 'row2' : 'row1';
                $row_color = ( !( $i % 2 ) ) ? $theme['td_color1'] : $theme['td_color2'];

                $template_nuke->assign_block_vars('forums', array(
                        'ROW_COLOR' => '#' . $row_color,
                        'ROW_CLASS' => $row_class,
                        'FORUM_NAME' => $forum_access[$i]['forum_name'],

                        'U_FORUM_AUTH' => append_nuke_sid("admin_forumauth.$phpEx?f=" . $forum_access[$i]['forum_id']),

                        'S_MOD_SELECT' => $optionlist_mod)
                );

                if( !$adv )
                {
                        $template_nuke->assign_block_vars('forums.aclvalues', array(
                                'S_ACL_SELECT' => $optionlist_acl)
                        );
                }
                else
                {
                        for($j = 0; $j < count($forum_auth_fields); $j++)
                        {
                                $template_nuke->assign_block_vars('forums.aclvalues', array(
                                        'S_ACL_SELECT' => $optionlist_acl_adv[$forum_id][$j])
                                );
                        }
                }

                $i++;
        }
        //@reset($nuke_auth_user);

        if ( $mode == 'user' )
        {
                $t_username = $ug_info[0]['username'];
                $s_user_type = ( $is_admin ) ? '<select name="userlevel"><option value="admin" selected="selected">' . $lang['Auth_Admin'] . '</option><option value="user">' . $lang['Auth_User'] . '</option></select>' : '<select name="userlevel"><option value="admin">' . $lang['Auth_Admin'] . '</option><option value="user" selected="selected">' . $lang['Auth_User'] . '</option></select>';
        }
        else
        {
                $t_groupname = $ug_info[0]['group_name'];
        }

        $name = array();
        $id = array();
        for($i = 0; $i < count($ug_info); $i++)
        {
                if( ( $mode == 'user' && !$ug_info[$i]['group_single_user'] ) || $mode == 'group' )
                {
                        $name[] = ( $mode == 'user' ) ? $ug_info[$i]['group_name'] :  $ug_info[$i]['username'];
                        $id[] = ( $mode == 'user' ) ? intval($ug_info[$i]['group_id']) : intval($ug_info[$i]['user_id']);
                }
        }

    $t_usergroup_list = $t_pending_list = '';
    if( count($name) )
    {
        for($i = 0; $i < count($ug_info); $i++)
        {
               $ug = ( $mode == 'user' ) ? 'group&amp;' . NUKE_POST_GROUPS_URL : 'user&amp;' . NUKE_POST_USERS_URL;

               if (!$ug_info[$i]['user_pending'])
               {
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
              $t_usergroup_list .= ( ( $t_usergroup_list != '' ) ? ', ' : '' ) . '<a href="' . append_nuke_sid("admin_ug_auth.$phpEx?mode=$ug=" . $id[$i]) . '">' . UsernameColor($name[$i]) . '</a>';
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
               }
               else
               {
              $t_pending_list .= ( ( $t_pending_list != '' ) ? ', ' : '' ) . '<a href="' . append_nuke_sid("admin_ug_auth.$phpEx?mode=$ug=" . $id[$i]) . '">' . $name[$i] . '</a>';
               }
        }
        }
    $t_usergroup_list = ($t_usergroup_list == '') ? $lang['None'] : $t_usergroup_list;
    $t_pending_list = ($t_pending_list == '') ? $lang['None'] : $t_pending_list;
        $s_column_span = 2; // Two columns always present
        if( !$adv )
        {
                $template_nuke->assign_block_vars('acltype', array(
                        'L_UG_ACL_TYPE' => $lang['Simple_Permission'])
                );
                $s_column_span++;
        }
        else
        {
                for($i = 0; $i < count($forum_auth_fields); $i++)
                {
                        $cell_title = $field_names[$forum_auth_fields[$i]];

                        $template_nuke->assign_block_vars('acltype', array(
                                'L_UG_ACL_TYPE' => $cell_title)
                        );
                        $s_column_span++;
                }
        }

        //
        // Dump in the page header ...
        //
        include('./nuke_page_header_admin.'.$phpEx);

        $template_nuke->set_filenames(array(
                "body" => 'admin/auth_ug_body.tpl')
        );

        $adv_switch = ( empty($adv) ) ? 1 : 0;
        $u_ug_switch = ( $mode == 'user' ) ? NUKE_POST_USERS_URL . "=" . $nuke_user_id : NUKE_POST_GROUPS_URL . "=" . $group_id;
        $switch_mode = append_nuke_sid("admin_ug_auth.$phpEx?mode=$mode&amp;" . $u_ug_switch . "&amp;adv=$adv_switch");
        $switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
        $u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';

        $s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="adv" value="' . $adv . '" />';
        $s_hidden_fields .= ( $mode == 'user' ) ? '<input type="hidden" name="' . NUKE_POST_USERS_URL . '" value="' . $nuke_user_id . '" />' : '<input type="hidden" name="' . NUKE_POST_GROUPS_URL . '" value="' . $group_id . '" />';

        if ( $mode == 'user' )
        {
                $template_nuke->assign_block_vars('switch_user_auth', array());

                $template_nuke->assign_vars(array(
                        'USERNAME' => $t_username,
                        'USER_LEVEL' => $lang['User_Level'] . " : " . $s_user_type,
                        'USER_GROUP_MEMBERSHIPS' => $lang['Group_memberships'] . ' : ' . $t_usergroup_list)
                );
        }
        else
        {
                $template_nuke->assign_block_vars("switch_group_auth", array());

                $template_nuke->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:    Group Colors                        v1.0.0 ]
 ******************************************************/
                        'USERNAME' => GroupColor($t_groupname),
/*****[END]********************************************
 [ Mod:    Group Colors                        v1.0.0 ]
 ******************************************************/
                        'GROUP_MEMBERSHIP' => $lang['Usergroup_members'] . ' : ' . $t_usergroup_list . '<br />' . $lang['Pending_members'] . ' : ' . $t_pending_list)
                );
        }

        $template_nuke->assign_vars(array(
                'L_USER_OR_GROUPNAME' => ( $mode == 'user' ) ? $lang['Username'] : $lang['Group_name'],

                'L_AUTH_TITLE' => ( $mode == 'user' ) ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
                'L_AUTH_EXPLAIN' => ( $mode == 'user' ) ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
                'L_MODERATOR_STATUS' => $lang['Moderator_status'],
                'L_PERMISSIONS' => $lang['Permissions'],
                'L_SUBMIT' => $lang['Submit'],
                'L_RESET' => $lang['Reset'],
                'L_FORUM' => $lang['Forum'],

                'U_USER_OR_GROUP' => append_nuke_sid("admin_ug_auth.$phpEx"),
                'U_SWITCH_MODE' => $u_switch_mode,

                'S_COLUMN_SPAN' => $s_column_span,
                'S_AUTH_ACTION' => append_nuke_sid("admin_ug_auth.$phpEx"),
                'S_HIDDEN_FIELDS' => $s_hidden_fields)
        );
}
else
{
        //
        // Select a user/group
        //
        include('./nuke_page_header_admin.'.$phpEx);

        $template_nuke->set_filenames(array(
                'body' => ( $mode == 'user' ) ? 'admin/user_select_body.tpl' : 'admin/auth_select_body.tpl')
        );

        if ( $mode == 'user' )
        {
                $template_nuke->assign_vars(array(
                        'L_FIND_USERNAME' => $lang['Find_username'],

                        'U_SEARCH_USER' => append_nuke_sid("search.$phpEx?mode=searchuser&popup=1&menu=1"))
                );
        }
        else
        {
                $sql = "SELECT group_id, group_name
                        FROM " . NUKE_GROUPS_TABLE . "
                        WHERE group_single_user <> " . TRUE;
                if ( !($result = $nuke_db->sql_query($sql)) )
                {
                        message_die(NUKE_GENERAL_ERROR, "Couldn't get group list", "", __LINE__, __FILE__, $sql);
                }

                if ( $row = $nuke_db->sql_fetchrow($result) )
                {
                        $select_list = '<select name="' . NUKE_POST_GROUPS_URL . '">';
                        do
                        {
                                $select_list .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
                        }
                        while ( $row = $nuke_db->sql_fetchrow($result) );
                        $select_list .= '</select>';
                }

                $template_nuke->assign_vars(array(
                        'S_AUTH_SELECT' => $select_list)
                );
        }

        $s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';

        $l_type = ( $mode == 'user' ) ? 'NUKE_USER' : 'AUTH';

        $template_nuke->assign_vars(array(
                'L_' . $l_type . '_TITLE' => ( $mode == 'user' ) ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
                'L_' . $l_type . '_EXPLAIN' => ( $mode == 'user' ) ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
                'L_' . $l_type . '_SELECT' => ( $mode == 'user' ) ? $lang['Select_a_User'] : $lang['Select_a_Group'],
                'L_LOOK_UP' => ( $mode == 'user' ) ? $lang['Look_up_User'] : $lang['Look_up_Group'],

                'S_HIDDEN_FIELDS' => $s_hidden_fields,
                'S_' . $l_type . '_ACTION' => append_nuke_sid("admin_ug_auth.$phpEx"))
        );

}

$template_nuke->pparse('body');

include('./nuke_page_footer_admin.'.$phpEx);

?>