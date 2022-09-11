<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                               proarcade.php
 *                            -------------------
 *
 *   PHPNuke Ported Arcade - http://www.nukearcade.com
 *   Original Arcade Mod phpBB by giefca - http://www.gf-phpbb.com
 *
 ***************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       09/20/2005
 ************************************************************************/

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

if ($popup != "1"){
    $nuke_module_name = basename(dirname(__FILE__));
    require("modules/".$nuke_module_name."/nukebb.php");
}
else
{
    $phpbb2_root_path = NUKE_PHPBB2_DIR;
}

define('IN_PHPBB2', true);
include($phpbb2_root_path . 'extension.inc');
include($phpbb2_root_path . 'common.' . $phpEx);
require($phpbb2_root_path . 'gf_funcs/gen_funcs.' . $phpEx);

$gid = get_var_gf(array('name'=>'gid', 'intval'=>true, 'default'=>0));

$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";

//
// Start session management
//

$nuke_userdata = session_nuke_pagestart($nuke_user_ip, NUKE_PAGE_GAME, $nukeuser);
init_userprefs($nuke_userdata);

//
// End session management
//
include('includes/functions_arcade.' . $phpEx);
//
// Start auth check
//
$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";

$sql = "SELECT * FROM " . NUKE_GAMES_TABLE . " WHERE game_id = '$gid'";

if (!($result = $nuke_db->sql_query($sql))) {
        message_die(NUKE_GENERAL_ERROR, "Could not read from the games table", '', __LINE__, __FILE__, $sql);
}

if (!($row = $nuke_db->sql_fetchrow($result))) {
        message_die(NUKE_GENERAL_ERROR, "Game does not exist.");
}

$hashoffset = get_var_gf(array('name'=>'hashoffset', 'default'=>''));
$gamehash = get_var_gf(array('name'=>'gamehash', 'default'=>''));
$vpaver = get_var_gf(array('name'=>'vpaver', 'default'=>''));
$newhash = get_var_gf(array('name'=>'newhash', 'default'=>''));
$gpaver = get_var_gf(array('name'=>'gpaver', 'default'=>''));
$settime = get_var_gf(array('name'=>'settime', 'intval'=>true, 'default'=>''));
$sid = get_var_gf(array('name'=>'sid', 'default'=>''));
$valid = get_var_gf(array('name'=>'valid', 'default'=>''));

if ($row['game_type'] == 0) {
        message_die(NUKE_GENERAL_ERROR, "Game Type no longer supported, please contact the admin and have him/her delete it.");
}

if ($row['game_type'] == 1) {
        message_die(NUKE_GENERAL_ERROR, "Game Type no longer supported, please contact the admin and have him/her delete it.");
}

if ($row['game_type'] == 2) {
        message_die(NUKE_GENERAL_ERROR, "Game Type no longer supported, please contact the admin and have him/her delete it.");
}

if ($row['game_type'] == 3) {
        $gamehash_id = substr($newhash , $hashoffset , 32) . substr($newhash , 0 , $hashoffset);
        $vpaver = ($gpaver == "GFARV2") ? '100B2' : '';
        $vscore = $row['game_scorevar'];
        $score = get_var_gf(array('name'=>$vscore, 'intval'=>true, 'default'=>''));
}

if ($row['game_type'] == 4 or $row['game_type'] == 5) {
        $gamehash_id = md5($nuke_user_ip);
        $vpaver = ($gpaver == "GFARV2") ? '100B2' : '';
        $score = $HTTP_POST_VARS['vscore'];
        $settime = $_COOKIE['timestarted'];
}

$vscore = $score;

if (!$nuke_userdata['session_logged_in']  && ($valid=='')) {
        header($header_location . "modules.php?name=Forums&file=proarcade&$vscore=$score&gid=$gid&valid=X&newhash=$newhash&gamehash_id=$gamehash_id&gamehash=$gamehash&hashoffset=$hashoffset&settime=$settime&sid=$sid&vpaver=$vpaver");
        exit;
}

if (!$nuke_userdata['session_logged_in']) {
        header($header_location . "modules.php?name=Your_Account");
        exit;
}

if ($row['game_type'] != 4 or $row['game_type'] != 5) {
        $sql = "SELECT * FROM " . NUKE_GAMEHASH_TABLE . " WHERE gamehash_id = '$gamehash_id' and game_id = '$gid' and user_id = '" . $nuke_userdata['user_id'] . "'";

        if (!($result = $nuke_db->sql_query($sql))) {
                message_die(NUKE_GENERAL_ERROR, "Could not read the hashtable", '', __LINE__, __FILE__, $sql);
        }

        if (!($row = $nuke_db->sql_fetchrow($result)) or ($vpaver != "100B2") or (!isset($vscore))) {
                $sql = "INSERT INTO " . NUKE_HACKGAME_TABLE . " (user_id , game_id , date_hack) VALUES ('" . $nuke_userdata['user_id'] . "' , '$gid' , '" . time() . "')" ;

                if (!$nuke_db->sql_query($sql)) {
                        message_die(NUKE_GENERAL_ERROR, 'Could not insert hack game data', '', __LINE__, __FILE__, $sql);
                }

                 header($header_location . "modules.php?name=Forums&file=arcade");
                exit;
        }
}

$sql = "DELETE FROM " . NUKE_GAMEHASH_TABLE . " WHERE gamehash_id = '$gamehash_id' and game_id = $gid and user_id = " . $nuke_userdata['user_id'] ;

if (!$nuke_db->sql_query($sql)) {
        message_die(NUKE_GENERAL_ERROR, 'Could not delete hash data from the games table', '', __LINE__, __FILE__, $sql);
}

if ($row['game_type'] == 4 or $row['game_type'] ==5) {
        if ($_COOKIE['gidstarted'] != $gid || !isset($_COOKIE['gidstarted'])) {
                $sql = "INSERT INTO " . NUKE_HACKGAME_TABLE . " (user_id , game_id , date_hack) VALUES ('" . $nuke_userdata['user_id'] . "' , '$gid' , '" . time() . "')";

                if (!$nuke_db->sql_query($sql)) {
                        message_die(NUKE_GENERAL_ERROR, 'Could not insert hack data from the games table', '', __LINE__, __FILE__, $sql);
                }

                header($header_location . "modules.php?name=Forums&file=arcade");
                exit;
         }
}
//
// End of auth check
//

$sql = "SELECT * FROM " . NUKE_SCORES_TABLE . " WHERE game_id = $gid and user_id = " . $nuke_userdata['user_id'] ;

if (!($result = $nuke_db->sql_query($sql))) {
        message_die(NUKE_GENERAL_ERROR, "Unable to insert data into scores table", '', __LINE__, __FILE__, $sql);
}

$datenow = time();
$ecart = $datenow - $settime ;

if (!($row = $nuke_db->sql_fetchrow($result))) {
        $sql = "INSERT INTO " . NUKE_SCORES_TABLE . " (game_id , user_id , score_game , score_date , score_time , score_set) VALUES ($gid , " . $nuke_userdata['user_id'] . " , $score , $datenow , $ecart , 1) ";

        if (!($result = $nuke_db->sql_query($sql))) {
                message_die(NUKE_GENERAL_ERROR, "Unable to insert data into scores table", '', __LINE__, __FILE__, $sql);
        }
} else {
        if ($row['score_game'] < $score) {
                $sql = "UPDATE " . NUKE_SCORES_TABLE . " set score_game = $score , score_set = score_set + 1 , score_date = $datenow , score_time = score_time + $ecart WHERE game_id = $gid and user_id = " . $nuke_userdata['user_id'] ;

                if (!($result = $nuke_db->sql_query($sql))) {
                        message_die(NUKE_GENERAL_ERROR, "Unable to insert data into scores table", '', __LINE__, __FILE__, $sql);
                }
        } else {
                $sql = "UPDATE " . NUKE_SCORES_TABLE . " set score_set = score_set + 1  , score_time = score_time + $ecart WHERE game_id = $gid and user_id = " . $nuke_userdata['user_id'] ;

                if (!($result = $nuke_db->sql_query($sql))) {
                        message_die(NUKE_GENERAL_ERROR, "Unable to insert data into scores table", '', __LINE__, __FILE__, $sql);
                }
        }
}

$sql = "SELECT * FROM " . NUKE_GAMES_TABLE . " WHERE game_id = " . $gid;

if (!($result = $nuke_db->sql_query($sql))) {
        message_die(NUKE_GENERAL_ERROR, "Could not read the games table", '', __LINE__, __FILE__, $sql);
}

if (($row = $nuke_db->sql_fetchrow($result)) && ($row['game_highscore']< $score)) {
        $sql = "UPDATE " . NUKE_GAMES_TABLE . " SET game_highscore = $score, game_highuser = " . $nuke_userdata['user_id'] . ", game_highdate = " . time() . ", game_set = game_set+1 WHERE game_id = $gid" ;

        if (!($result = $nuke_db->sql_query($sql))) {
                message_die(NUKE_GENERAL_ERROR, "Error accessing games table", '', __LINE__, __FILE__, $sql);
        }

        if ($row['game_highuser'] != $nuke_userdata['user_id']) {
                $sql = "UPDATE " . NUKE_COMMENTS_TABLE . " SET comments_value = '' WHERE game_id = $gid";

                if (!($result = $nuke_db->sql_query($sql))) {
                        message_die(NUKE_GENERAL_ERROR, "Error accessing comments table", '', __LINE__, __FILE__, $sql);
                }

                $flag = 1;

                $sql = "SELECT * FROM " . NUKE_SCORES_TABLE . " WHERE game_id = $gid ORDER BY score_game DESC LIMIT 1,1";

                if (!($result = $nuke_db->sql_query($sql))) {
                        message_die(NUKE_GENERAL_ERROR, "Error accessing scores table", '', __LINE__, __FILE__, $sql);
                }

                if ($row = $nuke_db->sql_fetchrow($result)) {
                        $sql= "SELECT s.score_game, s.game_id, g.game_name, u.user_id, u.username FROM " . NUKE_SCORES_TABLE . " s LEFT JOIN " . NUKE_USERS_TABLE . " u ON s.user_id = u.user_id LEFT JOIN " . NUKE_GAMES_TABLE . " g ON s.game_id = g.game_id WHERE s.game_id = " . $gid . " ORDER BY score_game DESC LIMIT 0,1";

                        if (!($result = $nuke_db->sql_query($sql))) {
                                message_die(NUKE_GENERAL_ERROR, "Error accessing scores and users table", '', __LINE__, __FILE__, $sql);
                        }

                        $row[0] = $nuke_db->sql_fetchrow($result);

                        $sql= "SELECT s.score_game, s.game_id, g.game_name, u.user_id, u.username FROM " . NUKE_SCORES_TABLE . " s LEFT JOIN " . NUKE_USERS_TABLE . " u ON s.user_id = u.user_id LEFT JOIN " . NUKE_GAMES_TABLE . " g ON s.game_id = g.game_id WHERE s.game_id = " . $gid . " ORDER BY score_game DESC LIMIT 1,1";

                        if (!($result = $nuke_db->sql_query($sql))) {
                                message_die(NUKE_GENERAL_ERROR, "Error accessing scores and users table", '', __LINE__, __FILE__, $sql);
                        }

                        $row[1] = $nuke_db->sql_fetchrow($result);

                        $nuke_user_id = $row[1]['user_id'];

                        $sql = "SELECT user_allow_arcadepm FROM " . NUKE_USERS_TABLE . " WHERE user_id = $nuke_user_id";

                        if (!($result = $nuke_db->sql_query($sql))) {
                                message_die(NUKE_GENERAL_ERROR, "Error retrieving user arcade pm preference", '', __LINE__, __FILE__, $sql);
                        }

                        $row_check = $nuke_db->sql_fetchrow($result);

                        if ($row_check['user_allow_arcadepm'] == 1) {
                                $sql = "UPDATE " . NUKE_USERS_TABLE . " SET user_new_privmsg = '1', user_last_privmsg = '9999999999' WHERE user_id = $nuke_user_id";

                                if (!($result = $nuke_db->sql_query($sql))) {
                                        message_die(NUKE_GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
                                }

                                $link = "<a href=modules.php?name=Forums&amp;file=games&amp;gid=" . $row[0]['game_id'] . ">here</a>";

                                                                $privmsgs_date = date("U");

                                $sql = "INSERT INTO " . NUKE_PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig) VALUES (" . NUKE_PRIVMSGS_NEW_MAIL . ", '" . str_replace("\'", "''", addslashes(sprintf($lang['register_pm_subject'],$row[0]['game_name']))) . "', '2', " . $nuke_user_id . ", " . $privmsgs_date . ", '0', '1', '1', '0')";

                                if (!$nuke_db->sql_query($sql)) {
                                        message_die(NUKE_GENERAL_ERROR, 'Could not insert private message sent info', '', __LINE__, __FILE__, $sql);
                                }

                                $privmsg_sent_id = $nuke_db->sql_nextid();

                                $sql = "INSERT INTO " . NUKE_PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_text) VALUES ($privmsg_sent_id, '" . str_replace("\'", "''", addslashes(sprintf($lang['register_pm'],$row[1]['score_game'],$row[0]['game_name'],$row[0]['username'],$row[0]['score_game'],$link))) . "')";

                                if (!$nuke_db->sql_query($sql)) {
                                        message_die(NUKE_GENERAL_ERROR, 'Could not insert private message sent text', '', __LINE__, __FILE__, $sql);
                                }
                        }
                }
        }
}

                else
                {
        $sql = "UPDATE " . NUKE_GAMES_TABLE . " SET game_set = game_set+1 WHERE game_id = $gid";
        if (!$nuke_db->sql_query($sql))
                   {
                message_die(NUKE_GENERAL_ERROR, 'Could not update games table', '', __LINE__, __FILE__, $sql);
           }
                }

                if ($flag == 1)
                {
                     if ($_COOKIE['arcadepopup']=='1')
                         {
                     header($header_location . "modules.php?name=Forums&file=commentspopup_new&gid=$gid");
                     exit;
                 }
                 else
                 {
                         header($header_location . "modules.php?name=Forums&file=comments_new&gid=$gid");
                    exit;
                 }
                }
                else
                {
                     if ($_COOKIE['arcadepopup']=='1')
                         {
                     header($header_location . "modules.php?name=Forums&file=gamespopup&gid=$gid&mode=done");
                     exit;
                 }
                 else
                         {
                     header($header_location . "modules.php?name=Forums&file=games&gid=$gid");
                     exit;
                         }
                }

?>