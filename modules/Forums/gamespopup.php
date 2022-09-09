<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                              games_popup.php
 *                            -------------------
 *
 *   PHPNuke Ported Arcade - http://www.nukearcade.com
 *   Original Arcade Mod phpBB by giefca - http://www.gf-phpbb.com
 *
 ***************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       09/20/2005
-=[Mod]=-
      Advanced Username Color                  v1.0.5       09/20/2005
 ************************************************************************/

if (!defined('MODULE_FILE')) {
    die('You can\'t access this file directly...');
}

$popup = 1;
if ($popup != "1"){
    $module_name = basename(dirname(__FILE__));
    require("modules/".$module_name."/nukebb.php");
}
else
{
    $phpbb2_root_path = NUKE_PHPBB2_DIR;
}

define('IN_PHPBB2', true);
include($phpbb2_root_path . 'extension.inc');
include($phpbb2_root_path . 'common.'.$phpEx);

//
// Start session management
//
$nuke_userdata = session_pagestart($nuke_user_ip, NUKE_PAGE_GAME, $nukeuser);
init_userprefs($nuke_userdata);
//
// End session management
//
include('includes/functions_arcade.' . $phpEx);
//
// Start auth check
//
if (!$nuke_userdata['session_logged_in']) {
        $header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";
        header($header_location . "modules.php?name=Your_Account");
        exit;
}
//
// End of auth check
//

$arcade_config = array();
$arcade_config = read_arcade_config();

if($arcade_config['limit_by_posts'] && $nuke_userdata['user_level'] != NUKE_ADMIN){
$secs = 86400;
$uid = $nuke_userdata['user_id'];

$days = $arcade_config['days_limit'];
$posts = $arcade_config['posts_needed'];

$current_time = time();
$old_time = $current_time - ($secs * $days);

//Begin Limit Play mod
if($arcade_config['limit_type']=='posts')
{
$sql = "SELECT * FROM " . NUKE_POSTS_TABLE . " WHERE poster_id = $uid";
}
else
{
$sql = "SELECT * FROM " . NUKE_POSTS_TABLE . " WHERE poster_id = $uid and post_time BETWEEN $old_time AND $current_time";
}
if ( !($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
    }

    $Amount_Of_Posts = $nuke_db->sql_numrows( $result );


    if($Amount_Of_Posts < $posts)
    {
    $diff_posts = $posts - $Amount_Of_Posts;

    if($arcade_config['limit_type']=='posts')
        {
            $message = "You need $posts posts to play the arcade.<br />You need $diff_posts more posts.";
        }
        else 
        {
            $message = "You need $posts posts in the last $days days to play the arcade.<br />You need $diff_posts more posts.";
        }
        message_die(NUKE_GENERAL_MESSAGE, $message);
    }
}
//End Limit Play mod
if (!empty($HTTP_POST_VARS['gid']) || !empty($HTTP_GET_VARS['gid'])) {
        $gid = (!empty($HTTP_POST_VARS['gid'])) ? intval($HTTP_POST_VARS['gid']) : intval($HTTP_GET_VARS['gid']);
} else {
        message_die(NUKE_GENERAL_ERROR, "No game is specified");
}

$sql = "SELECT g.* , u.username, MAX(s.score_game) AS highscore FROM " . NUKE_GAMES_TABLE . " g LEFT JOIN " . NUKE_SCORES_TABLE . " s ON g.game_id = s.game_id LEFT JOIN " . NUKE_USERS_TABLE . " u ON g.game_highuser = u.user_id WHERE g.game_id = $gid GROUP BY g.game_id,g.game_highscore";

if (!($result = $nuke_db->sql_query($sql))) {
        message_die(NUKE_GENERAL_ERROR, "Could not read games table", '', __LINE__, __FILE__, $sql);
}

if (!($row = $nuke_db->sql_fetchrow($result)) ) {
        message_die(NUKE_GENERAL_ERROR, "This game does not exist", '', __LINE__, __FILE__, $sql);
}

$mode = $HTTP_GET_VARS['mode'];
if($mode == "done")
    {
        $gamename = $row['game_name'];
        // set page title
        $page_title = "Current Highscore's for " .$gamename;

        $gen_simple_header = TRUE;
        include("includes/nuke_page_header_review.php");


        $template_nuke->set_filenames(array(
                        'body' => 'gamespopup_finish.tpl'));

                $template_nuke->assign_vars(array(
                        'GAMENAME' => $gamename,
                        'PLAYAGAIN' => append_sid("gamespopup.$phpEx?gid=$gid", true),
                        'RETURN' => append_sid("arcade.$phpEx", true),
                        ));

                $sql = "SELECT s.*, u.username FROM " . NUKE_SCORES_TABLE . " s LEFT JOIN " . NUKE_USERS_TABLE . " u ON s.user_id = u.user_id WHERE game_id = $gid ORDER BY s.score_game DESC, s.score_date ASC LIMIT 0,15";

                if (!($result = $nuke_db->sql_query($sql)))
                {
        message_die(NUKE_GENERAL_ERROR, "Could not read from scores table", '', __LINE__, __FILE__, $sql);
                }

                $pos = 0;
                $posreelle = 0;
                $lastscore = 0;
                while ($row = $nuke_db->sql_fetchrow($result))
                {
                    $posreelle++;
                        if ($lastscore!=$row['score_game'])
                        {
                    $pos = $posreelle;
                }

                $lastscore = $row['score_game'];
                $class = ($class == 'row1') ? 'row2' : 'row1';
                $template_nuke->assign_block_vars('scorerow', array(
                            'CLASS' => $class,
                            'POS' => $pos,
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                'USERNAME' => UsernameColor($row['username']),
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                'URL_STATS' => '<nobr><a class="cattitle" href="' . append_sid("statarcade.$phpEx?uid=" . $row['user_id']) . '">' . "<img src='modules/Forums/templates/" . $theme['template_name'] . "/images/loupe.gif' align='absmiddle' border='0' alt='" . $lang['statuser'] . " " . $row['username'] . "'>" . '</a></nobr>',
                'SCORE' => number_format($row['score_game']),
                'DATEHIGH' => create_date($board_config['default_dateformat'] , $row['score_date'] , $board_config['board_timezone']))
                        );

                }

                //
                // Generate the page end
                //
                $template_nuke->pparse('body');
                include("includes/page_tail_review.php");
                exit;

    }

$liste_cat_auth_play = get_arcade_categories($nuke_userdata['user_id'], $nuke_userdata['user_level'],'play');
$tbauth_play = array();
$tbauth_play = explode(',',$liste_cat_auth_play);

if (!in_array($row['arcade_catid'],$tbauth_play)) {
        message_die(NUKE_GENERAL_MESSAGE, $lang['game_forbidden']);
}


//chargement du template
$template_nuke->set_filenames(array(
        'body' => 'gamespopup_body.tpl')
);

$sql = "DELETE FROM " . NUKE_GAMEHASH_TABLE . " WHERE hash_date < " . (time() - 72000);

if (!$nuke_db->sql_query($sql)) {
        message_die(NUKE_GENERAL_ERROR, "Could not delete from the hash table", '', __LINE__, __FILE__, $sql);
}

// Type V2 Game Else Type V1
if ($row['game_type'] == 3) {
        $type_v2 = true;
        $template_nuke->assign_block_vars('game_type_V2',array());
        $gamehash_id = md5(uniqid($nuke_user_ip));
        $sql = "INSERT INTO " . NUKE_GAMEHASH_TABLE . " (gamehash_id , game_id , user_id , hash_date) VALUES ('$gamehash_id' , '$gid' , '" . $nuke_userdata['user_id'] . "' , '" . time() . "')";

        if (!($result = $nuke_db->sql_query($sql))) {
                message_die(NUKE_GENERAL_ERROR, "Could not delete from the hash table", '', __LINE__, __FILE__, $sql);
        }
}
elseif ($row['game_type'] == 4 or $row['game_type'] == 5)
{
        if ($row['game_type'] == 5)
                {
               $template_nuke->assign_block_vars('game_type_V5',array());
            }
            else
            {
           $template_nuke->assign_block_vars('game_type_V2',array());
            }
        setcookie('gidstarted', '', time() - 3600);
        setcookie('gidstarted',$gid);
        setcookie('timestarted', '', time() - 3600);
        setcookie('timestarted', time());

        $gamehash_id = md5($nuke_user_ip);
        $sql = "INSERT INTO " . NUKE_GAMEHASH_TABLE . " (gamehash_id , game_id , user_id , hash_date) VALUES ('$gamehash_id' , '$gid' , '" . $nuke_userdata['user_id'] . "' , '" . time() . "')";

        if (!($result = $nuke_db->sql_query($sql)))
                {
        message_die(NUKE_GENERAL_ERROR, "Couldn't update hashtable", '', __LINE__, __FILE__, $sql);
        }

}
else
{
        message_die(NUKE_GENERAL_ERROR, "Game Type no longer supported, please contact the admin and have him/her delete it.");
}

setcookie('arcadepopup', '', time() - 3600);
setcookie('arcadepopup', '1');

$scriptpath = substr($board_config['script_path'] , strlen($board_config['script_path']) - 1 , 1) == '/' ? substr($board_config['script_path'] , 0 , strlen($board_config['script_path']) - 1) : $board_config['script_path'];
$scriptpath = "http://" . $board_config['server_name'] .$scriptpath;
global $prefix;
$sql = "SELECT arcade_cattitle FROM `".$prefix."_bbarcade_categories` WHERE arcade_catid = " . $row['arcade_catid'];
$result = $nuke_db->sql_query($sql);
$ourrow = $nuke_db->sql_fetchrow($result);
$cat_title = $ourrow['arcade_cattitle'];

$template_nuke->assign_vars(array(
        'SWF_GAME' => $row['game_swf'] ,
        'GAMEHASH' => $gamehash_id,
        'L_GAME' => $row['game_name'],
                'HIGHUSER' => (!empty($row['username'])) ? "'s Highscore: ".$row['username']." - ": " : No Highscore",
                'HIGHSCORE' => $row['highscore'])
);

//
// Output page header
$page_title = $lang['arcade_game'];
$template_nuke->pparse('body');

?>