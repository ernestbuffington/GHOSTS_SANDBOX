<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
-=[Mod]=-
      Advanced Username Color                  v1.0.5       06/12/2005
      Smilies in Topic Titles                  v1.0.0       08/23/2005
      Smilies in Topic Titles Toggle           v1.0.0       09/10/2005
 ************************************************************************/

if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
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
/*****[BEGIN]******************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 ******************************************************/
include('includes/bbcode.'.$phpEx);
/*****[END]********************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 ******************************************************/

// ############         Edit below         ########################################
$topic_length = '40';     // length of topic title
$topic_limit = '10';      // limit of displayed topics per page
$special_forums = '0';    // specify forums ('0' = no; '1' = yes)
$forum_ids = '';          // IDs of forums; separate them with a comma
$set_mode = 'today';      // set default mode ('today', 'yesterday', 'last24', 'lastweek', 'lastXdays')
$set_days = '3';          // set default days (used for lastXdays mode)
// ############         Edit above         ########################################

$nuke_userdata = session_nuke_pagestart($nuke_user_ip, NUKE_PAGE_RECENT);
init_userprefs($nuke_userdata);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
    $mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
}
else
{
    $mode = $set_mode;
}

if( isset($HTTP_GET_VARS['amount_days']) || isset($HTTP_POST_VARS['amount_days']) )
{
    $amount_days = ( isset($HTTP_GET_VARS['amount_days']) ) ? $HTTP_GET_VARS['amount_days'] : $HTTP_POST_VARS['amount_days'];
}
else
{
    $amount_days = $set_days;
}

$page_title = $lang['Recent_topics'];
include("includes/nuke_page_header.php");

$sql_auth = "SELECT * FROM ". NUKE_FORUMS_TABLE;
if( !$result_auth = $nuke_db->sql_query($sql_auth) )
{
    message_die(NUKE_GENERAL_ERROR, 'could not query forums information.', '', __LINE__, __FILE__, $sql_auth);
}
$forums = array();
while( $row_auth = $nuke_db->sql_fetchrow($result_auth) )
{
    $forums[] = $row_auth;
}
$nuke_db->sql_freeresult($result_auth);

$is_auth_ary = array();
$is_auth_ary = auth(NUKE_AUTH_ALL, NUKE_AUTH_LIST_ALL, $nuke_userdata);

$except_forums = '\'start\'';
for( $f = 0; $f < count($forums); $f++ )
{
    if( (!$is_auth_ary[$forums[$f]['forum_id']]['auth_read']) || (!$is_auth_ary[$forums[$f]['forum_id']]['auth_view']) )
    {
        if( $except_forums == '\'start\'' )
        {
            $except_forums = $forums[$f]['forum_id'];
        }
        else
        {
            $except_forums .= ','. $forums[$f]['forum_id'];
        }
    }
}

$where_forums = ( $special_forums == '0' ) ? 't.forum_id NOT IN ('. $except_forums .')' : 't.forum_id NOT IN ('. $except_forums .') AND t.forum_id IN ('. $forum_ids .')';

$sql_start = "SELECT t.*, p.poster_id, p.post_username AS last_poster_name, p.post_id, p.post_time, f.forum_name, f.forum_id, u.username AS last_poster, u.user_id AS last_poster_id, u2.username AS first_poster, u2.user_id AS first_poster_id, p2.post_username AS first_poster_name
           FROM (". NUKE_BB_TOPICS_TABLE ." t, ". NUKE_POSTS_TABLE ." p, ". NUKE_POSTS_TABLE ." p2,  ". NUKE_FORUMS_TABLE ." f, ". NUKE_USERS_TABLE ." u, ". NUKE_USERS_TABLE ." u2)
           WHERE
            (p2.post_id = t.topic_first_post_id
            AND p.forum_id = f.forum_id
            AND p.poster_id = u.user_id
            AND u2.user_id = t.topic_poster)
           AND
           ($where_forums)
           AND p.post_id = t.topic_last_post_id AND "; 

$sql_end = "  ORDER BY t.topic_last_post_id DESC LIMIT $start, $topic_limit";

switch( $mode )
{
    case 'today':
        $sql = $sql_start ."FROM_UNIXTIME(p.post_time,'%Y%m%d') - FROM_UNIXTIME(unix_timestamp(NOW()),'%Y%m%d') = 0". $sql_end;
        $template_nuke->assign_vars(array('STATUS' => $lang['Recent_today']));
        $where_count = "$where_forums AND FROM_UNIXTIME(p.post_time,'%Y%m%d') - FROM_UNIXTIME(unix_timestamp(NOW()),'%Y%m%d') = 0";
        $l_mode = $lang['Recent_title_today'];
        break;

    case 'yesterday':
        $sql = $sql_start ."FROM_UNIXTIME(p.post_time,'%Y%m%d') - FROM_UNIXTIME(unix_timestamp(NOW()),'%Y%m%d') = -1". $sql_end;
        $template_nuke->assign_vars(array('STATUS' => $lang['Recent_yesterday']));
        $where_count = "$where_forums AND FROM_UNIXTIME(p.post_time,'%Y%m%d') - FROM_UNIXTIME(unix_timestamp(NOW()),'%Y%m%d') = -1";
        $l_mode = $lang['Recent_title_yesterday'];
        break;

    case 'last24':
        $sql = $sql_start ."UNIX_TIMESTAMP(NOW()) - p.post_time < 86400". $sql_end;
        $template_nuke->assign_vars(array('STATUS' => $lang['Recent_last24']));
        $where_count = "$where_forums AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400";
        $l_mode = $lang['Recent_title_last24'];
        break;

    case 'lastweek':
        $sql = $sql_start ."UNIX_TIMESTAMP(NOW()) - p.post_time < 691200". $sql_end;
        $template_nuke->assign_vars(array('STATUS' => $lang['Recent_lastweek']));
        $where_count = "$where_forums AND UNIX_TIMESTAMP(NOW()) - p.post_time < 691200";
        $l_mode = $lang['Recent_title_lastweek'];
        break;

    case 'lastXdays':
        if(!$amount_days || !ctype_digit($amount_days)) {
            $message = 'You must enter a valid day<br /><br />'. sprintf($lang['Recent_click_return'], '<a href="'. append_nuke_sid("recent.$phpEx") .'">', '</a>') .'<br />'. sprintf($lang['Click_return_index'], '<a href="'. append_nuke_sid("index.$phpEx") .'">', '</a>');
            message_die(NUKE_GENERAL_MESSAGE, $message);
            break;
        }
        $sql = $sql_start ."UNIX_TIMESTAMP(NOW()) - p.post_time < 86400 * $amount_days". $sql_end;
        $template_nuke->assign_vars(array('STATUS' => sprintf($lang['Recent_lastXdays'], $amount_days)));
        $where_count = "$where_forums AND UNIX_TIMESTAMP(NOW()) - p.post_time < 86400 * $amount_days";
        $l_mode = sprintf($lang['Recent_title_lastXdays'], $amount_days);
        break;

    default:
        $message = $lang['Recent_wrong_mode'] .'<br /><br />'. sprintf($lang['Recent_click_return'], '<a href="'. append_nuke_sid("recent.$phpEx") .'">', '</a>') .'<br />'. sprintf($lang['Click_return_index'], '<a href="'. append_nuke_sid("index.$phpEx") .'">', '</a>');
        message_die(NUKE_GENERAL_MESSAGE, $message);
        break;
}
if( !$result = $nuke_db->sql_query($sql) )
{
    message_die(NUKE_GENERAL_ERROR, 'could not obtain main information.', '', __LINE__, __FILE__, $sql);
}
$line = array();
while( $row = $nuke_db->sql_fetchrow($result) )
{
    $line[] = $row;
}
$nuke_db->sql_freeresult($result);
        
$template_nuke->set_filenames(array('body' => 'recent_body.tpl'));

$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_t']) : array();
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f']) : array();
for( $i = 0; $i < count($line); $i++ )
{
    $forum_id = $line[$i]['forum_id'];
    $forum_url = append_nuke_sid("viewforum.$phpEx?". NUKE_POST_FORUM_URL ."=$forum_id");
    $topic_id = $line[$i]['topic_id'];
    $topic_url = append_nuke_sid("viewtopic.$phpEx?". NUKE_POST_TOPIC_URL ."=$topic_id");

    $word_censor = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $line[$i]['topic_title']) : $line[$i]['topic_title'];
    $topic_title = ( strlen($line[$i]['topic_title']) < $topic_length ) ? $word_censor : substr(stripslashes($word_censor), 0, $topic_length) .'...';
/*****[BEGIN]******************************************
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 [ Mod:     Smilies in Topic Titles Toggle     v1.0.0 ]
 ******************************************************/
    $topic_title = ($board_config['smilies_in_titles']) ? smilies_pass($topic_title) : $topic_title;
/*****[END]********************************************
 [ Mod:     Smilies in Topic Titles Toggle     v1.0.0 ]
 [ Mod:     Smilies in Topic Titles            v1.0.0 ]
 ******************************************************/

    $topic_type =  ( $line[$i]['topic_type'] == NUKE_POST_ANNOUNCE ) ? $lang['Topic_Announcement'] .' ': '';
    $topic_type .= ( $line[$i]['topic_type'] == NUKE_POST_GLOBAL_ANNOUNCE ) ? $lang['Topic_global_announcement'] .' ': '';
    $topic_type .= ( $line[$i]['topic_type'] == NUKE_POST_STICKY ) ? $lang['Topic_Sticky'] .' ': '';
    $topic_type .= ( $line[$i]['topic_vote'] ) ? $lang['Topic_Poll'] .' ': '';

    $views = $line[$i]['topic_views'];
    $replies = $line[$i]['topic_replies'];
    if( ( $replies + 1 ) > $board_config['posts_per_page'] )
    {
        $total_pages = ceil( ( $replies + 1 ) / $board_config['posts_per_page'] );
        $goto_page = ' [ ';
        $times = '1';
        for( $j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'] )
        {
            $goto_page .= '<a href="'. append_nuke_sid("viewtopic.$phpEx?". NUKE_POST_TOPIC_URL ."=". $topic_id ."&amp;start=$j") .'">'. $times .'</a>';
            if( $times == '1' && $total_pages > '4' )
            {
                $goto_page .= ' ... ';
                $times = $total_pages - 3;
                $j += ( $total_pages - 4 ) * $board_config['posts_per_page'];
            }
            else if( $times < $total_pages )
            {
                $goto_page .= ', ';
            }
            $times++;
        }
        $goto_page .= ' ] ';
    }
    else
    {
        $goto_page = '';
    }

    if( $line[$i]['topic_status'] == NUKE_TOPIC_LOCKED )
    {
        $folder = $images['folder_locked'];
        $folder_new = $images['folder_locked_new'];
    }
    else if( $line[$i]['topic_type'] == NUKE_POST_ANNOUNCE )
    {
        $folder = $images['folder_announce'];
        $folder_new = $images['folder_announce_new'];
    }
    else if( $line[$i]['topic_type'] == NUKE_POST_GLOBAL_ANNOUNCE )
    {
        $folder = $images['folder_global_announce'];
        $folder_new = $images['folder_global_announce_new'];
    }
    else if( $line[$i]['topic_type'] == NUKE_POST_STICKY )
    {
        $folder = $images['folder_sticky'];
        $folder_new = $images['folder_sticky_new'];
    }
    else
    {
        if( $replies >= $board_config['hot_threshold'] )
        {
            $folder = $images['folder_hot'];
            $folder_new = $images['folder_hot_new'];
        }
        else
        {
            $folder = $images['folder'];
            $folder_new = $images['folder_new'];
        }
    }

    $newest_img = '';
    if( $nuke_userdata['session_logged_in'] )
    {
        if( $line[$i]['post_time'] > $nuke_userdata['user_lastvisit'] ) 
        {
            if( !empty($tracking_topics) || !empty($tracking_forums) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f_all']) )
            {
                $unread_topics = true;
                if( !empty($tracking_topics[$topic_id]) )
                {
                    if( $tracking_topics[$topic_id] >= $line[$i]['post_time'] )
                    {
                        $unread_topics = false;
                    }
                }
                if( !empty($tracking_forums[$forum_id]) )
                {
                    if( $tracking_forums[$forum_id] >= $line[$i]['post_time'] )
                    {
                        $unread_topics = false;
                    }
                }
                if( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f_all']) )
                {
                    if( $HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f_all'] >= $line[$i]['post_time'] )
                    {
                        $unread_topics = false;
                    }
                }

                if( $unread_topics )
                {
                    $folder_image = $folder_new;
                    $folder_alt = $lang['New_posts'];
                    $newest_img = '<a href="'. append_nuke_sid("viewtopic.$phpEx?". NUKE_POST_TOPIC_URL ."=$topic_id&amp;view=newest") .'"><img src="'. $images['icon_newest_reply'] .'" alt="'. $lang['View_newest_post'] .'" title="'. $lang['View_newest_post'] .'" border="0" /></a> ';
                }
                else
                {
                    $folder_image = $folder;
                    $folder_alt = ( $line[$i]['topic_status'] == NUKE_TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
                    $newest_img = '';
                }
            }
            else
            {
                $folder_image = $folder_new;
                $folder_alt = ( $line[$i]['topic_status'] == NUKE_TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['New_posts'];
                $newest_img = '<a href="'. append_nuke_sid("viewtopic.$phpEx?". NUKE_POST_TOPIC_URL ."=$topic_id&amp;view=newest") .'"><img src="'. $images['icon_newest_reply'] .'" alt="'. $lang['View_newest_post'] .'" title="'. $lang['View_newest_post'] .'" border="0" /></a> ';
            }
        }
        else 
        {
            $folder_image = $folder;
            $folder_alt = ( $line[$i]['topic_status'] == NUKE_TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
            $newest_img = '';
        }
    }
    else
    {
        $folder_image = $folder;
        $folder_alt = ( $line[$i]['topic_status'] == NUKE_TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
        $newest_img = '';
    }
            
    $first_time = create_date($board_config['default_dateformat'], $line[$i]['topic_time'], $board_config['board_timezone']);
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    $first_author = ( $line[$i]['first_poster_id'] != NUKE_ANONYMOUS ) ? '<a href="'. append_nuke_sid("profile.$phpEx?mode=viewprofile&amp;". NUKE_POST_USERS_URL .'='. $line[$i]['first_poster_id']) .'">' . UsernameColor($line[$i]['first_poster']) .'</a>' : ( ($line[$i]['first_poster_name'] != '' ) ? $line[$i]['first_poster_name'] : $lang['Guest'] );
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    $last_time = create_date($board_config['default_dateformat'], $line[$i]['post_time'], $board_config['board_timezone']);
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    $last_author = ( $line[$i]['last_poster_id'] != NUKE_ANONYMOUS ) ? '<a href="'. append_nuke_sid("profile.$phpEx?mode=viewprofile&amp;". NUKE_POST_USERS_URL .'='. $line[$i]['last_poster_id']) .'">' . UsernameColor($line[$i]['last_poster']) .'</a>' : ( ($line[$i]['last_poster_name'] != '' ) ? $line[$i]['last_poster_name'] : $lang['Guest'] );
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    $last_url = '<a href="'. append_nuke_sid("viewtopic.$phpEx?". NUKE_POST_POST_URL .'='. $line[$i]['topic_last_post_id']) .'#'. $line[$i]['topic_last_post_id'] .'"><img src="'. $images['icon_latest_reply'] .'" alt="'. $lang['View_latest_post'] .'" title="'. $lang['View_latest_post'] .'" border="0" /></a>';

    $template_nuke->assign_block_vars('recent', array(
        'ROW_CLASS' => ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'],
        'TOPIC_TITLE' => $topic_title,
        'TOPIC_TYPE' => $topic_type,
        'GOTO_PAGE' => $goto_page,
        'L_VIEWS' => $lang['Views'],
        'VIEWS' => $views,
        'L_REPLIES' => $lang['Replies'],
        'REPLIES' => $replies,
        'NEWEST_IMG' => $newest_img,
        'TOPIC_FOLDER_IMG' => $folder_image,
        'TOPIC_FOLDER_ALT' => $folder_alt,
        'FIRST_TIME' => sprintf($lang['Recent_first'], $first_time),
        'FIRST_AUTHOR' => sprintf($lang['Recent_first_poster'], $first_author),
        'LAST_TIME' => $last_time,
        'LAST_AUTHOR' => $last_author,
        'LAST_URL' => $last_url,
        'FORUM_NAME' => $line[$i]['forum_name'],
        'U_VIEW_FORUM' => $forum_url,
        'U_VIEW_TOPIC' => $topic_url,
    ));
}

$sql = "SELECT count(t.topic_id) AS total_topics FROM ". NUKE_BB_TOPICS_TABLE ." t , ". NUKE_POSTS_TABLE ." p
           WHERE $where_count AND p.post_id = t.topic_last_post_id";
if( !($result = $nuke_db->sql_query($sql)) )
{
    message_die(NUKE_GENERAL_ERROR, 'error getting total topics.', '', __LINE__, __FILE__, $sql);
}
if( $total = $nuke_db->sql_fetchrow($result) )
{
    $total_topics = $total['total_topics'];
    $pagination = generate_pagination("recent.$phpEx?amount_days=$amount_days&amp;mode=$mode", $total_topics, $topic_limit, $start) .'&nbsp;';
}

if( $total_topics == '0' )
{
    $template_nuke->assign_block_vars('switch_no_topics', array());
}

$template_nuke->assign_vars(array(
    'L_RECENT_TITLE' => ( $total_topics == '1' ) ? sprintf($lang['Recent_title_one'], $total_topics, $l_mode) : sprintf($lang['Recent_title_more'], $total_topics, $l_mode),
    'L_TODAY' => $lang['Recent_today'],
    'L_YESTERDAY' => $lang['Recent_yesterday'],
    'L_LAST24' => $lang['Recent_last24'],
    'L_LASTWEEK' => $lang['Recent_lastweek'],
    'L_LAST' => $lang['Recent_last'],
    'L_DAYS' => $lang['Recent_days'],
    'L_SELECT_MODE' => $lang['Recent_select_mode'],
    'L_SHOWING_POSTS' => $lang['Recent_showing_posts'],
    'L_NO_TOPICS' => $lang['Recent_no_topics'],
    'AMOUNT_DAYS' => $amount_days,
    'FORM_ACTION' => append_nuke_sid("recent.$phpEx"),
    'PAGINATION' => ( $total_topics != '0' ) ? $pagination : '',
    'PAGE_NUMBER' => ( $total_topics != '0' ) ? sprintf($lang['Page_of'], ( floor( $start / $topic_limit ) + 1 ), ceil( $total_topics / $topic_limit )) : '',
));

$template_nuke->pparse('body');
include("includes/page_tail.php");

?>