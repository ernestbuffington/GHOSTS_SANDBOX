<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                               toparcade.php
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
include($phpbb2_root_path . 'common.'.$phpEx);
require($phpbb2_root_path . 'gf_funcs/gen_funcs.' . $phpEx);

$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";

//
// Start session management
//
$nuke_userdata = session_nuke_pagestart($nuke_user_ip, NUKE_PAGE_TOPARCADES, $nukeuser);
init_userprefs($nuke_userdata);
//
// End session management
//
include('includes/functions_arcade.' . $phpEx);
//
// Start auth check
//
$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";

if (!$nuke_userdata['session_logged_in']) {
        header($header_location . "modules.php?name=Your_Account");
        exit;
}
//
// End of auth check
//

$template_nuke->set_filenames(array(
        'body' => 'toparcade_body.tpl')
);

$template_nuke->assign_vars(array(
        'L_TOPARCADE_FIVE' => $lang['toparcade_five'],
        'L_ARCADE' => $lang['toparcade_players'],
        'NAV_DESC' => '<a class="nav" href="' . append_nuke_sid("arcade.$phpEx") . '">' . $lang['arcade'] . '</a>'
)
);

$nbcol = 3;
$games_par_page = 12;
$liste_cat_auth = get_arcade_categories($nuke_userdata['user_id'], $nuke_userdata['user_level'],'view');

if (empty($liste_cat_auth)) {
        $liste_cat_auth = "''";
}

$sql = "SELECT COUNT(*) AS nbtot FROM " . NUKE_GAMES_TABLE . " WHERE arcade_catid IN ($liste_cat_auth)";

if (!($result = $nuke_db->sql_query($sql))) {
        message_die(NUKE_GENERAL_ERROR, "Could not read the games table", '', __LINE__, __FILE__, $sql);
}

if ($row=$nuke_db->sql_fetchrow($result)) {
        $total_games = $row['nbtot'];
} else {
        $total_games = 0;
}


$start = get_var_gf(array('name'=>'start', 'intval'=>true));
$limit_sql = " LIMIT $start," . $games_par_page;

$sql = "SELECT distinct game_id , game_name FROM " . NUKE_GAMES_TABLE . " WHERE arcade_catid IN ($liste_cat_auth) ORDER BY game_name ASC $limit_sql";

if (!($result = $nuke_db->sql_query($sql))) {
        message_die(NUKE_GENERAL_ERROR, "Could not read the games table", '', __LINE__, __FILE__, $sql);
}

$fini = false;

if (!$row = $nuke_db->sql_fetchrow($result)) {
        $fini=true;
}

while ((!$fini) ) {
        $template_nuke->assign_block_vars('blkligne', array());

        for ($cg = 1; $cg <= $nbcol; $cg++) {
                $template_nuke->assign_block_vars('blkligne.blkcolonne', array());

                if (!$fini) {
                         $template_nuke->assign_block_vars('blkligne.blkcolonne.blkgame', array(
                                'GAMENAME' => '<nobr><a class="cattitle" href="' . append_nuke_sid("games.$phpEx?gid=" . $row['game_id']) . '">' . $row['game_name'] . '</a></nobr>')
                        );

                        $pos = 0;
                        $posreelle = 0;
                        $lastscore = 0;
                        $sql2 = "SELECT s.* , u.username FROM " . NUKE_SCORES_TABLE . " s LEFT JOIN " . NUKE_USERS_TABLE . " u ON u.user_id = s.user_id WHERE s.game_id = " . $row['game_id'] . " ORDER BY s.score_game DESC, s.score_date ASC LIMIT 0,5";

                        if (!($result2 = $nuke_db->sql_query($sql2))) {
                                message_die(NUKE_GENERAL_ERROR, "Could not read from the scores/users tables", '', __LINE__, __FILE__, $sql);
                        }

                        while($row2 = $nuke_db->sql_fetchrow($result2)) {
                                $posreelle++;

                                if ($lastscore != $row2['score_game']) {
                                        $pos = $posreelle;
                                }
                                $lastscore = $row2['score_game'];
                                $template_nuke->assign_block_vars('blkligne.blkcolonne.blkgame.blkscore', array(
                                        'SCORE' => number_format($row2['score_game']),
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                                        'USERNAME' => UsernameColor($row2['username']),
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                                        'POS' => $pos)
                                );
                        }

                        if (!($row = $nuke_db->sql_fetchrow($result))) {
                                $fini = true;
                        }
                }
        }
}

$template_nuke->assign_vars(array(
        'PAGINATION' => generate_pagination(append_nuke_sid("toparcade.$phpEx?uid=$uid"), $total_games, $games_par_page, $start),
        'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $games_par_page) + 1), ceil($total_games / $games_par_page)))
);

include($phpbb2_root_path . 'hall_of_fame.'.$phpEx);

//
// Output page header
$page_title = $lang['toparcade'];
include('includes/nuke_page_header.'.$phpEx);
$template_nuke->pparse('body');
include('includes/page_tail.'.$phpEx);

?>