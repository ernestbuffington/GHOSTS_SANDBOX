<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/**
*
* @package attachment_mod
* @version $Id: functions_includes.php,v 1.3 2005/11/06 16:32:19 acydburn Exp $
* @copyright (c) 2002 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* These are functions called directly from phpBB2 Files
*/

/**
* Include the FAQ-File (faq.php)
*/
function attach_faq_include($lang_file)
{
    global $phpbb2_root_path, $board_config, $phpEx, $faq, $attach_config;

    if (intval($attach_config['disable_mod']))
    {
        return;
    }

    if ($lang_file == 'lang_faq')
    {
        $language = attach_mod_get_lang('lang_faq_attach');
        include($phpbb2_root_path . 'language/lang_' . $language . '/lang_faq_attach.'.$phpEx);
    }
}

/**
* Setup Basic Authentication (includes/auth.php)
*/
function attach_setup_basic_auth($type, &$nuke_auth_fields, &$a_sql)
{
    switch ($type)
    {
        case NUKE_AUTH_ALL:
            $a_sql .= ', a.auth_attachments, a.auth_download';
            $nuke_auth_fields[] = 'auth_attachments';
            $nuke_auth_fields[] = 'auth_download';
        break;

        case NUKE_AUTH_ATTACH:
            $a_sql = 'a.auth_attachments';
            $nuke_auth_fields = array('auth_attachments');
        break;

        case AUTH_DOWNLOAD:
            $a_sql = 'a.auth_download';
            $nuke_auth_fields = array('auth_download');
        break;

        default:
            break;
    }
}

/**
* Setup Forum Authentication (admin/admin_forumauth.php)
*/
function attach_setup_forum_auth(&$simple_auth_ary, &$forum_auth_fields, &$field_names)
{
    global $lang;

    // Add Attachment Auth
    //                    Post Attachments
    $simple_auth_ary[0][] = NUKE_AUTH_MOD;
    $simple_auth_ary[1][] = NUKE_AUTH_MOD;
    $simple_auth_ary[2][] = NUKE_AUTH_MOD;
    $simple_auth_ary[3][] = NUKE_AUTH_MOD;
    $simple_auth_ary[4][] = NUKE_AUTH_MOD;
    $simple_auth_ary[5][] = NUKE_AUTH_MOD;
    $simple_auth_ary[6][] = NUKE_AUTH_MOD;

    //                    Download Attachments
    $simple_auth_ary[0][] = NUKE_AUTH_ALL;
    $simple_auth_ary[1][] = NUKE_AUTH_ALL;
    $simple_auth_ary[2][] = NUKE_AUTH_REG;
    $simple_auth_ary[3][] = NUKE_AUTH_ACL;
    $simple_auth_ary[4][] = NUKE_AUTH_ACL;
    $simple_auth_ary[5][] = NUKE_AUTH_MOD;
    $simple_auth_ary[6][] = NUKE_AUTH_MOD;

    $forum_auth_fields[] = 'auth_attachments';
    $field_names['auth_attachments'] = $lang['Auth_attach'];

    $forum_auth_fields[] = 'auth_download';
    $field_names['auth_download'] = $lang['Auth_download'];
}

/**
* Setup Usergroup Authentication (admin/admin_ug_auth.php)
*/
function attach_setup_usergroup_auth(&$forum_auth_fields, &$nuke_auth_field_match, &$field_names)
{
    global $lang;

    // Post Attachments
    $forum_auth_fields[] = 'auth_attachments';
    $nuke_auth_field_match['auth_attachments'] = NUKE_AUTH_ATTACH;
    $field_names['auth_attachments'] = $lang['Auth_attach'];

    // Download Attachments
    $forum_auth_fields[] = 'auth_download';
    $nuke_auth_field_match['auth_download'] = AUTH_DOWNLOAD;
    $field_names['auth_download'] = $lang['Auth_download'];
}

/**
* Setup Viewtopic Authentication for f_access (viewtopic.php:includes/topic_review.php)
*/
function attach_setup_viewtopic_auth(&$order_sql, &$sql)
{
    $order_sql = str_replace('f.auth_attachments', 'f.auth_attachments, f.auth_download, t.topic_attachment', $order_sql);
    $sql = str_replace('f.auth_attachments', 'f.auth_attachments, f.auth_download, t.topic_attachment', $sql);
}

/**
* Setup s_auth_can in viewforum and viewtopic (viewtopic.php/viewforum.php)
*/
function attach_build_auth_levels($is_auth, &$s_auth_can)
{
    global $lang, $attach_config, $phpEx, $forum_id;

    if (intval($attach_config['disable_mod']))
    {
        return;
    }

    // If you want to have the rules window link within the forum view too, comment out the two lines, and comment the third line
//    $rules_link = '(<a href="' . $phpbb2_root_path . 'attach_rules.' . $phpEx . '?f=' . $forum_id . '" target="_blank">Rules</a>)';
//    $s_auth_can .= ( ( $is_auth['auth_attachments'] ) ? $rules_link . ' ' . $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';
    $s_auth_can .= (($is_auth['auth_attachments']) ? $lang['Rules_attach_can'] : $lang['Rules_attach_cannot'] ) . '<br />';

    $s_auth_can .= (($is_auth['auth_download']) ? $lang['Rules_download_can'] : $lang['Rules_download_cannot'] ) . '<br />';
}

/**
* Called from admin_users.php and admin_groups.php in order to process Quota Settings (admin/admin_users.php:admin/admin_groups.php)
*/
function attachment_quota_settings($admin_mode, $submit = false, $mode)
{
    global $template_nuke, $nuke_db, $HTTP_POST_VARS, $HTTP_GET_VARS, $lang, $lang, $phpbb2_root_path, $phpEx, $attach_config;

    // Make sure constants got included
    include_once($phpbb2_root_path . 'attach_mod/includes/constants.'.$phpEx);

    if (!intval($attach_config['allow_ftp_upload']))
    {
        if ($attach_config['upload_dir'][0] == '/' || ($attach_config['upload_dir'][0] != '/' && $attach_config['upload_dir'][1] == ':'))
        {
            $upload_dir = $attach_config['upload_dir'];
        }
        else
        {
			$upload_dir = $phpbb2_root_path . $attach_config['upload_dir'];
        }
    }
    else
    {
        $upload_dir = $attach_config['download_path'];
    }

    include_once($phpbb2_root_path . 'attach_mod/includes/functions_selects.' . $phpEx);
    include_once($phpbb2_root_path . 'attach_mod/includes/functions_admin.' . $phpEx);

    $nuke_user_id = 0;

    if ($admin_mode == 'user')
    {
        // We overwrite submit here... to be sure
        $submit = (isset($HTTP_POST_VARS['submit'])) ? true : false;

        if (!$submit && $mode != 'save')
        {
            $nuke_user_id = get_var(NUKE_POST_USERS_URL, 0);
            $u_name = get_var('username', '');

            if (!$nuke_user_id && !$u_name)
            {
                message_die(NUKE_GENERAL_MESSAGE, $lang['No_user_id_specified'] );
            }

            if ($nuke_user_id)
            {
                $this_userdata['user_id'] = $nuke_user_id;
            }
            else
            {
                // Get userdata is handling the sanitizing of username
                $this_userdata = get_userdata($HTTP_POST_VARS['username'], true);
            }

            $nuke_user_id = (int) $this_userdata['user_id'];
        }
        else
        {
            $nuke_user_id = get_var('id', 0);

            if (!$nuke_user_id)
            {
                message_die(NUKE_GENERAL_MESSAGE, $lang['No_user_id_specified'] );
            }
        }
    }

    if ($admin_mode == 'user' && !$submit && $mode != 'save')
    {
        // Show the contents
        $sql = 'SELECT quota_limit_id, quota_type FROM ' . QUOTA_TABLE . '
            WHERE user_id = ' . (int) $nuke_user_id;

        if( !($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to get Quota Settings', '', __LINE__, __FILE__, $sql);
        }

        $pm_quota = $upload_quota = 0;

        if ($row = $nuke_db->sql_fetchrow($result))
        {
            do
            {
                if ($row['quota_type'] == QUOTA_UPLOAD_LIMIT)
                {
                    $upload_quota = $row['quota_limit_id'];
                }
                else if ($row['quota_type'] == QUOTA_PM_LIMIT)
                {
                    $pm_quota = $row['quota_limit_id'];
                }
            }
            while ($row = $nuke_db->sql_fetchrow($result));
        }
        else
        {
            // Set Default Quota Limit
            $upload_quota = $attach_config['default_upload_quota'];
            $pm_quota = $attach_config['default_pm_quota'];
        }
        $nuke_db->sql_freeresult($result);

        $template_nuke->assign_vars(array(
            'S_SELECT_UPLOAD_QUOTA'        => quota_limit_select('user_upload_quota', $upload_quota),
            'S_SELECT_PM_QUOTA'            => quota_limit_select('user_pm_quota', $pm_quota),
            'L_UPLOAD_QUOTA'            => $lang['Upload_quota'],
            'L_PM_QUOTA'                => $lang['Pm_quota'])
        );
    }

    if ($admin_mode == 'user' && $submit && $HTTP_POST_VARS['deleteuser'])
    {
        process_quota_settings($admin_mode, $nuke_user_id, QUOTA_UPLOAD_LIMIT, 0);
        process_quota_settings($admin_mode, $nuke_user_id, QUOTA_PM_LIMIT, 0);
    }
    else if ($admin_mode == 'user' && $submit && $mode == 'save')
    {
        // Get the contents
        $upload_quota = get_var('user_upload_quota', 0);
        $pm_quota = get_var('user_pm_quota', 0);

        process_quota_settings($admin_mode, $nuke_user_id, QUOTA_UPLOAD_LIMIT, $upload_quota);
        process_quota_settings($admin_mode, $nuke_user_id, QUOTA_PM_LIMIT, $pm_quota);
    }

    if ($admin_mode == 'group' && $mode == 'newgroup')
    {
        return;
    }

    if ($admin_mode == 'group' && !$submit && isset($HTTP_POST_VARS['edit']))
    {
        // Get group id again, we do not trust phpBB here, Mods may be installed ;)
        $group_id = get_var(NUKE_POST_GROUPS_URL, 0);

        // Show the contents
        $sql = 'SELECT quota_limit_id, quota_type FROM ' . QUOTA_TABLE . '
            WHERE group_id = ' . (int) $group_id;

        if( !($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to get Quota Settings', '', __LINE__, __FILE__, $sql);
        }

        $pm_quota = $upload_quota = 0;

        if ($row = $nuke_db->sql_fetchrow($result))
        {
            do
            {
                if ($row['quota_type'] == QUOTA_UPLOAD_LIMIT)
                {
                    $upload_quota = $row['quota_limit_id'];
                }
                else if ($row['quota_type'] == QUOTA_PM_LIMIT)
                {
                    $pm_quota = $row['quota_limit_id'];
                }
            }
            while ($row = $nuke_db->sql_fetchrow($result));
        }
        else
        {
            // Set Default Quota Limit
            $upload_quota = $attach_config['default_upload_quota'];
            $pm_quota = $attach_config['default_pm_quota'];
        }
        $nuke_db->sql_freeresult($result);

        $template_nuke->assign_vars(array(
            'S_SELECT_UPLOAD_QUOTA'    => quota_limit_select('group_upload_quota', $upload_quota),
            'S_SELECT_PM_QUOTA'        => quota_limit_select('group_pm_quota', $pm_quota),
            'L_UPLOAD_QUOTA'        => $lang['Upload_quota'],
            'L_PM_QUOTA'            => $lang['Pm_quota'])
        );
    }

    if ($admin_mode == 'group' && $submit && isset($HTTP_POST_VARS['group_delete']))
    {
        $group_id = get_var(NUKE_POST_GROUPS_URL, 0);

        process_quota_settings($admin_mode, $group_id, QUOTA_UPLOAD_LIMIT, 0);
        process_quota_settings($admin_mode, $group_id, QUOTA_PM_LIMIT, 0);
    }
    else if ($admin_mode == 'group' && $submit)
    {
        $group_id = get_var(NUKE_POST_GROUPS_URL, 0);

        // Get the contents
        $upload_quota = get_var('group_upload_quota', 0);
        $pm_quota = get_var('group_pm_quota', 0);

        process_quota_settings($admin_mode, $group_id, QUOTA_UPLOAD_LIMIT, $upload_quota);
        process_quota_settings($admin_mode, $group_id, QUOTA_PM_LIMIT, $pm_quota);
    }

}

/**
* Called from usercp_viewprofile, displays the User Upload Quota Box, Upload Stats and a Link to the User Attachment Control Panel
* Groups are able to be grabbed, but it's not used within the Attachment Mod. ;)
* (includes/usercp_viewprofile.php)
*/
function display_upload_attach_box_limits($nuke_user_id, $group_id = 0)
{
    global $attach_config, $board_config, $phpbb2_root_path, $lang, $nuke_db, $template_nuke, $phpEx, $nuke_userdata, $profiledata;

    if (intval($attach_config['disable_mod']))
    {
        return;
    }

    if ($nuke_userdata['user_level'] != NUKE_ADMIN && $nuke_userdata['user_id'] != $nuke_user_id)
    {
        return;
    }

    if (!$nuke_user_id)
    {
        return;
    }

    // Return if the user is not within the to be listed Group
    if ($group_id)
    {
        if (!user_in_group($nuke_user_id, $group_id))
        {
            return;
        }
    }

    $nuke_user_id = (int) $nuke_user_id;
    $group_id = (int) $group_id;

    $attachments = new attach_posting();
    $attachments->page = NUKE_PAGE_INDEX;

    // Get the assigned Quota Limit. For Groups, we are directly getting the value, because this Quota can change from user to user.
    if ($group_id)
    {
        $sql = 'SELECT l.quota_limit
            FROM ' . QUOTA_TABLE . ' q, ' . QUOTA_LIMITS_TABLE . ' l
            WHERE q.group_id = ' . (int) $group_id . '
                AND q.quota_type = ' . QUOTA_UPLOAD_LIMIT . '
                AND q.quota_limit_id = l.quota_limit_id
            LIMIT 1';

        if ( !($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Could not get Group Quota', '', __LINE__, __FILE__, $sql);
        }

        if ($nuke_db->sql_numrows($result) > 0)
        {
            $row = $nuke_db->sql_fetchrow($result);
            $attach_config['upload_filesize_limit'] = intval($row['quota_limit']);
            $nuke_db->sql_freeresult($result);
        }
        else
        {
            $nuke_db->sql_freeresult($result);

            // Set Default Quota Limit
            $quota_id = intval($attach_config['default_upload_quota']);

            if ($quota_id == 0)
            {
                $attach_config['upload_filesize_limit'] = $attach_config['attachment_quota'];
            }
            else
            {
                $sql = 'SELECT quota_limit
                    FROM ' . QUOTA_LIMITS_TABLE . '
                    WHERE quota_limit_id = ' . (int) $quota_id . '
                    LIMIT 1';

                if ( !($result = $nuke_db->sql_query($sql)) )
                {
                    message_die(NUKE_GENERAL_ERROR, 'Could not get Quota Limit', '', __LINE__, __FILE__, $sql);
                }

                if ($nuke_db->sql_numrows($result) > 0)
                {
                    $row = $nuke_db->sql_fetchrow($result);
                    $attach_config['upload_filesize_limit'] = $row['quota_limit'];
                }
                else
                {
                    $attach_config['upload_filesize_limit'] = $attach_config['attachment_quota'];
                }
                $nuke_db->sql_freeresult($result);
            }
        }
    }
    else
    {
        if (is_array($profiledata))
        {
            $attachments->get_quota_limits($profiledata, $nuke_user_id);
        }
        else
        {
            $attachments->get_quota_limits($nuke_userdata, $nuke_user_id);
        }
    }

    if (!$attach_config['upload_filesize_limit'])
    {
        $upload_filesize_limit = $attach_config['attachment_quota'];
    }
    else
    {
        $upload_filesize_limit = $attach_config['upload_filesize_limit'];
    }

    if ($upload_filesize_limit == 0)
    {
        $nuke_user_quota = $lang['Unlimited'];
    }
    else
    {
        $size_lang = ($upload_filesize_limit >= 1048576) ? $lang['MB'] : ( ($upload_filesize_limit >= 1024) ? $lang['KB'] : $lang['Bytes'] );

        if ($upload_filesize_limit >= 1048576)
        {
            $nuke_user_quota = (round($upload_filesize_limit / 1048576 * 100) / 100) . ' ' . $size_lang;
        }
        else if ($upload_filesize_limit >= 1024)
        {
            $nuke_user_quota = (round($upload_filesize_limit / 1024 * 100) / 100) . ' ' . $size_lang;
        }
        else
        {
            $nuke_user_quota = ($upload_filesize_limit) . ' ' . $size_lang;
        }
    }

    // Get all attach_id's the specific user posted, but only uploads to the board and not Private Messages
    $sql = 'SELECT attach_id
        FROM ' . ATTACHMENTS_TABLE . '
        WHERE user_id_1 = ' . (int) $nuke_user_id . '
            AND privmsgs_id = 0
        GROUP BY attach_id';

    if ( !($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Couldn\'t query attachments', '', __LINE__, __FILE__, $sql);
    }

    $attach_ids = $nuke_db->sql_fetchrowset($result);
    $num_attach_ids = $nuke_db->sql_numrows($result);
    $nuke_db->sql_freeresult($result);
    $attach_id = array();

    for ($j = 0; $j < $num_attach_ids; $j++)
    {
        $attach_id[] = intval($attach_ids[$j]['attach_id']);
    }

	$upload_filesize = (sizeof($attach_id) > 0) ? get_total_attach_filesize($attach_id) : 0;

    $size_lang = ($upload_filesize >= 1048576) ? $lang['MB'] : ( ($upload_filesize >= 1024) ? $lang['KB'] : $lang['Bytes'] );

    if ($upload_filesize >= 1048576)
    {
        $nuke_user_uploaded = (round($upload_filesize / 1048576 * 100) / 100) . ' ' . $size_lang;
    }
    else if ($upload_filesize >= 1024)
    {
        $nuke_user_uploaded = (round($upload_filesize / 1024 * 100) / 100) . ' ' . $size_lang;
    }
    else
    {
        $nuke_user_uploaded = ($upload_filesize) . ' ' . $size_lang;
    }

    $upload_limit_pct = ( $upload_filesize_limit > 0 ) ? round(( $upload_filesize / $upload_filesize_limit ) * 100) : 0;
    $upload_limit_img_length = ( $upload_filesize_limit > 0 ) ? round(( $upload_filesize / $upload_filesize_limit ) * $board_config['privmsg_graphic_length']) : 0;
    if ($upload_limit_pct > 100)
    {
        $upload_limit_img_length = $board_config['privmsg_graphic_length'];
    }
    $upload_limit_remain = ( $upload_filesize_limit > 0 ) ? $upload_filesize_limit - $upload_filesize : 100;

    $l_box_size_status = sprintf($lang['Upload_percent_profile'], $upload_limit_pct);

    $template_nuke->assign_block_vars('switch_upload_limits', array());

    $template_nuke->assign_vars(array(
        'L_UACP'            => $lang['UACP'],
        'L_UPLOAD_QUOTA'    => $lang['Upload_quota'],
        //'U_UACP'            => $phpbb2_root_path . 'uacp.' . $phpEx . '?u=' . $nuke_user_id . '&amp;sid=' . $nuke_userdata['session_id'],
        'U_UACP' => append_sid('uacp.' . $phpEx . '?u=' . $nuke_user_id . '&amp;sid=' . $nuke_userdata['session_id']),
        'UPLOADED'            => sprintf($lang['User_uploaded_profile'], $nuke_user_uploaded),
        'QUOTA'                => sprintf($lang['User_quota_profile'], $nuke_user_quota),
        'UPLOAD_LIMIT_IMG_WIDTH'    => $upload_limit_img_length,
        'UPLOAD_LIMIT_PERCENT'        => $upload_limit_pct,
        'PERCENT_FULL'                => $l_box_size_status)
    );
}

/**
* Prune Attachments (includes/prune.php)
*/
function prune_attachments($sql_post)
{
    // prune it.
    delete_attachment($sql_post);
}

/**
* Function responsible for viewonline (within viewonline.php and the admin index page)
* not included in vanilla attachment mod
*
* added directly after the switch statement
* viewonline.php:
*        perform_attach_pageregister($row['session_page']);
* admin/index.php:
*        perform_attach_pageregister($onlinerow_reg[$i]['user_session_page'], TRUE);
*        perform_attach_pageregister($onlinerow_guest[$i]['session_page'], TRUE);
*/
function perform_attach_pageregister($session_page, $in_admin = false)
{
    global $location, $location_url, $lang;

    switch ($session_page)
    {
        case (PAGE_UACP):
            $location = $lang['User_acp_title'];
            $location_url = ($in_admin) ? "index.$phpEx?pane=right" : "index.$phpEx";
        break;

        case (PAGE_RULES):
            $location = $lang['Rules_page'];
            $location_url = ($in_admin) ? "index.$phpEx?pane=right" : "index.$phpEx";
        break;
    }
}

?>