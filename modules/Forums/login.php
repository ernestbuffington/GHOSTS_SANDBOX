<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                                login.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   Id: login.php,v 1.47.2.18 2005/05/06 20:50:10 acydburn Exp
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
      Evolution Functions                      v1.5.0       12/20/2005
 ************************************************************************/

if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));
require("modules/".$module_name."/nukebb.php");

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB2', true);
include($phpbb2_root_path . 'extension.inc');
include($phpbb2_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$nuke_userdata = session_pagestart($nuke_user_ip, NUKE_PAGE_LOGIN);
init_userprefs($nuke_userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
    $sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
    $sid = '';
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) )
{
    if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && (!$nuke_userdata['session_logged_in'] || isset($HTTP_POST_VARS['admin'])) )
    {
        $nuke_username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
        $password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

        $sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try
            FROM " . NUKE_USERS_TABLE . "
            WHERE username = '" . str_replace("\\'", "''", $nuke_username) . "'";
        if ( !($result = $nuke_db->sql_query($sql)) )
        {
            message_die(NUKE_GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
        }

        if( $row = $nuke_db->sql_fetchrow($result) )
        {
            if( $row['user_level'] != NUKE_ADMIN && $board_config['board_disable'] )
            {
                                nuke_redirect(append_sid("index.$phpEx", true));
                                exit;
            }
            else
            {
                 // If the last login is more than x minutes ago, then reset the login tries/time
                 if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $row['user_last_login_try'] < (time() - ($board_config['login_reset_time'] * 60)))
                 {
                    $nuke_db->sql_query('UPDATE ' . NUKE_USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
                    $row['user_last_login_try'] = $row['user_login_tries'] = 0;
                 }

                 // Check to see if user is allowed to login again... if his tries are exceeded
                 if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $board_config['max_login_attempts'] &&
                    $row['user_last_login_try'] >= (time() - ($board_config['login_reset_time'] * 60)) && $row['user_login_tries'] >= $board_config['max_login_attempts'] && $nuke_userdata['user_level'] != NUKE_ADMIN)
                 {
                    message_die(NUKE_GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $board_config['max_login_attempts'], $board_config['login_reset_time']));
                }
/*****[BEGIN]******************************************
 [ Base:     Evolution Functions               v1.5.0 ]
 ******************************************************/
                if( md5($password) == $row['user_password'] && $row['user_active'] )
                {
/*****[END]********************************************
 [ Base:     Evolution Functions               v1.5.0 ] 
 ******************************************************/
                    $autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

                    $admin = (isset($HTTP_POST_VARS['admin'])) ? 1 : 0;
                    $session_id = nuke_session_begin($row['user_id'], $nuke_user_ip, NUKE_PAGE_INDEX, FALSE, $autologin, $admin);
                    // Reset login tries
                    $nuke_db->sql_query('UPDATE ' . NUKE_USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);

                    if( $session_id )
                    {
                        $url = ( !empty($HTTP_POST_VARS['nuke_redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['nuke_redirect'])) : "index.$phpEx";
                        nuke_redirect(append_sid($url, true));
                    }
                    else
                    {
                        message_die(NUKE_CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
                    }
                }
                // Only store a failed login attempt for an active user - inactive users can't login even with a correct password
 				elseif( $row['user_active'] )
                {
                       // Save login tries and last login
                       if ($row['user_id'] != NUKE_ANONYMOUS)
                       {
                          $sql = 'UPDATE ' . NUKE_USERS_TABLE . '
                             SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
                             WHERE user_id = ' . $row['user_id'];
                          $nuke_db->sql_query($sql);
                       }
                    $nuke_redirect = ( !empty($HTTP_POST_VARS['nuke_redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['nuke_redirect'])) : '';
                    $nuke_redirect = str_replace('?', '&', $nuke_redirect);

                    if (strstr(urldecode($nuke_redirect), "\n") || strstr(urldecode($nuke_redirect), "\r") || strstr(urldecode($nuke_redirect), ';url'))
                    {
                        message_die(NUKE_GENERAL_ERROR, 'Tried to nuke_redirect to potentially insecure url.');
                    }

                    $template_nuke->assign_vars(array(
                        'META' => '<meta http-equiv=\"refresh\" content=\"3;url=' . append_sid("login.$phpEx?nuke_redirect=$nuke_redirect") . '\">')
                    );

                    $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href=\"' . append_sid("login.$phpEx?nuke_redirect=$nuke_redirect") . '\">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

                    message_die(NUKE_GENERAL_MESSAGE, $message);
                }
            }
        }
        else
        {

				$nuke_redirect = ( !empty($HTTP_POST_VARS['nuke_redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['nuke_redirect'])) : '';
				$nuke_redirect = str_replace('?', '&', $nuke_redirect);

				if (strstr(urldecode($nuke_redirect), "\n") || strstr(urldecode($nuke_redirect), "\r") || strstr(urldecode($nuke_redirect), ';url'))
				{
					message_die(NUKE_GENERAL_ERROR, 'Tried to nuke_redirect to potentially insecure url.');
				}

				$template_nuke->assign_vars(array(
					'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?nuke_redirect=$nuke_redirect\">")
				);

				$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?nuke_redirect=$nuke_redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

				message_die(NUKE_GENERAL_MESSAGE, $message);
        }
    }
    else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $nuke_userdata['session_logged_in'] )
    {
        // session id check
        if ($sid == '' || $sid != $nuke_userdata['session_id'])
        {
            message_die(NUKE_GENERAL_ERROR, 'Invalid_session');
        }
        if( $nuke_userdata['session_logged_in'] )
        {
            session_end($nuke_userdata['session_id'], $nuke_userdata['user_id']);
        }

        if (!empty($HTTP_POST_VARS['nuke_redirect']) || !empty($HTTP_GET_VARS['nuke_redirect']))
        {
            $url = (!empty($HTTP_POST_VARS['nuke_redirect'])) ? htmlspecialchars($HTTP_POST_VARS['nuke_redirect']) : htmlspecialchars($HTTP_GET_VARS['nuke_redirect']);
            $url = str_replace('&amp;', '&', $url);
            nuke_redirect(append_sid($url, true));
        }
        else
        {
            nuke_redirect(append_sid("index.$phpEx", true));
        }
    }
    else
    {
        $url = ( !empty($HTTP_POST_VARS['nuke_redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['nuke_redirect'])) : "index.$phpEx";
        nuke_redirect(append_sid($url, true));
    }
}
else
{
    //
    // Do a full login page dohickey if
    // user not already logged in
    //
    if( !$nuke_userdata['session_logged_in'] || (isset($HTTP_GET_VARS['admin']) && $nuke_userdata['session_logged_in'] && $nuke_userdata['user_level'] == NUKE_ADMIN))
    {
        $page_title = $lang['Login'];
                include("includes/nuke_page_header.php");

        $template_nuke->set_filenames(array(
            'body' => 'login_body.tpl')
        );

        $forward_page = '';

        if( isset($HTTP_POST_VARS['nuke_redirect']) || isset($HTTP_GET_VARS['nuke_redirect']) )
        {
            $forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

            if( preg_match("/^nuke_redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
            {
                $forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
                $forward_match = explode('&', $forward_to);

                if(count($forward_match) > 1)
                {
                    for($i = 1; $i < count($forward_match); $i++)
                    {
                        if( !preg_match("/sid=/", $forward_match[$i]) )
                        {
                            if( $forward_page != '' )
                            {
                                $forward_page .= '&';
                            }
                            $forward_page .= $forward_match[$i];
                        }
                    }
                    $forward_page = $forward_match[0] . '?' . $forward_page;
                }
                else
                {
                    $forward_page = $forward_match[0];
                }
            }
        }

        nuke_redirect("modules.php?name=Your_Account&nuke_redirect=$forward_page");
        $nuke_username = ( $nuke_userdata['user_id'] != NUKE_ANONYMOUS ) ? $nuke_userdata['username'] : '';

        $s_hidden_fields = '<input type="hidden" name="nuke_redirect" value="' . $forward_page . '" />';

        $s_hidden_fields .= (isset($HTTP_GET_VARS['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

        make_jumpbox('viewforum.'.$phpEx);
        $template_nuke->assign_vars(array(
            'USERNAME' => $nuke_username,

            'L_ENTER_PASSWORD' => (isset($HTTP_GET_VARS['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
            'L_SEND_PASSWORD' => $lang['Forgotten_password'],

            'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

            'S_HIDDEN_FIELDS' => $s_hidden_fields)
        );

        $template_nuke->pparse('body');

                include("includes/page_tail.php");
    }
    else
    {
                nuke_redirect(append_sid("index.$phpEx", true));
                exit;
    }

}

?>