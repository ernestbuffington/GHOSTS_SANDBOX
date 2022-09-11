<?php
/***************************************************************************
 *                            userdel.php
 *   NUKE_MOD: Admin delete user with all postings
 *                            -------------------
 *   begin                : Saturday, Feb 28, 2006
 *   copyright            : (C) 2006 Sergei Sekirin
 *   email                : sergei-vs@mail.ru
 *
 *   $Id: userdel.php, v 1.0.5  2006/08/18 sergei-vs Exp $
 *   NOTE: v.1.0.5 tested on phpBB 2.0.19
 ***************************************************************************/
//
// Inc. to phpBB
//
if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

if ((!(isset($popup)) OR ($popup != "1")) && !isset($HTTP_GET_VARS['printertopic']))
{
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
include("includes/functions_userdel.php");

//
// Start session management
//
$nuke_userdata = session_nuke_pagestart($nuke_user_ip, NUKE_PAGE_PROFILE);
init_userprefs($nuke_userdata);
//
// End session management
//


//
// Authorized ?
//

$if_admin = ($nuke_userdata['user_level'] == NUKE_ADMIN );
if ( !$if_admin )
{
	message_die(NUKE_GENERAL_MESSAGE, $lang['Not_Authorised']);
}
//
include($phpbb2_root_path.'language/lang_' . $nuke_userdata['user_lang'] . '/lang_user_delete.'.$phpEx);

//
// Set ID of deleted user
//
if( isset( $HTTP_POST_VARS['user_deleted_id'] ) || isset( $HTTP_GET_VARS['user_deleted_id'] ) )
{
	$nuke_user_deleted_id = ( isset( $HTTP_POST_VARS['user_deleted_id']) ) ? $HTTP_POST_VARS['user_deleted_id'] : $HTTP_GET_VARS['user_deleted_id'];
	$nuke_user_deleted_id = intval($nuke_user_deleted_id);
}
else
{
	message_die(NUKE_GENERAL_MESSAGE, 'Poster is not specifyed');
}


$mode = (( isset($HTTP_POST_VARS['delete_mode'])) ? intval($HTTP_POST_VARS['delete_mode']) : '1');

switch( $mode )
{
	case 1:
		$mode_quest = '';
        $final_anno = $lang['user_is_deleted'];
		break;
	case 2:
		$mode_quest = $lang['with_all_topics'];
        $final_anno = $lang['user_with_topics_deleted'];
		break;
	case 3:
		$mode_quest = $lang['with_all_postings'];
        $final_anno = $lang['user_deleted_with_all_postings'];
		break;

        //<!---1
	case 4:
		$mode_quest = $lang['with_all_postings'];
        $final_anno = $lang['only_user_postings_deleted'];
		break;
        //--->

	default:
		message_die(NUKE_GENERAL_MESSAGE, $lang['No_post_mode']);
		break;
}

//
// Set confirm
//
$confirm = isset($HTTP_POST_VARS['confirm']) ? true : false;


//
// Cancel? - Back to profile
//
if ( isset($HTTP_POST_VARS['cancel']) )
{
	if ( $nuke_user_deleted_id )
	{
		$nuke_redirect = "profile.$phpEx?mode=viewprofile&". NUKE_POST_USERS_URL ."=$nuke_user_deleted_id";
	}
	else
	{
		$nuke_redirect = "index.$phpEx";
	}
	nuke_redirect(append_nuke_sid($nuke_redirect, true));
}


//
// Level check - not allowed deletion of Admins and Moders
//
$sql = "SELECT user_id, user_level, username
	FROM " . NUKE_USERS_TABLE . "
	WHERE user_id = $nuke_user_deleted_id";
	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Could not get user information", "", __LINE__, __FILE__, $sql);
	$row = $nuke_db->sql_fetchrow($result);
	if ($row['user_id'] == '')
	{
	    message_die(NUKE_GENERAL_MESSAGE, $lang['User_not_exist']);
	}
    $nuke_user_name = $row['username'];


//
// Count topics of User
//
$sql = "SELECT count(*) AS total
	FROM " . NUKE_BB_TOPICS_TABLE . "
    WHERE topic_poster = $nuke_user_deleted_id";
	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Error getting total topics", "", __LINE__, __FILE__, $sql);
 	  if ( $total = $nuke_db->sql_fetchrow($result) )
	  {
		  $total_topics = $total['total'];
      }
	  $nuke_db->sql_freeresult($result);

//
// Count posts of User
//
	$sql = "SELECT count(*) AS total2
	FROM " . NUKE_POSTS_TABLE . "
    WHERE poster_id = $nuke_user_deleted_id";
	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Error getting total posts", "", __LINE__, __FILE__, $sql);
	  if ( $total2 = $nuke_db->sql_fetchrow($result) )
	  {
		  $total_posts = $total2['total2'];
      }
	  $nuke_db->sql_freeresult($result);

//
// Confirm
//
if ( ($nuke_user_deleted_id) && !$confirm )
{
	// Confirm deletion
	$s_hidden_fields = '<input type="hidden" name="user_deleted_id" value="' . $nuke_user_deleted_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="delete_mode" value="' . $mode . '" />';
    if ($mode != 4)
    {
	$l_confirm = ($nuke_user_deleted_id) ? sprintf($lang['You_sure'], $total_topics, $total_posts ) . ' '. $nuke_user_name . '</b> ' . $mode_quest . '?' : $lang['No_user_specified'];
    }
    else
    {
	$l_confirm = ($nuke_user_deleted_id) ? sprintf($lang['Sure_delete_only_postings'], $total_topics, $total_posts ) . ' '. $nuke_user_name . '</b>?' : $lang['No_user_specified'];
    }
	// Output confirmation page
include("includes/nuke_page_header.php");
	$template_nuke->set_filenames(array(
		'confirm_body' => 'confirm_body.tpl'));
	$template_nuke->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Information'],
		'MESSAGE_TEXT' => $l_confirm,
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'S_CONFIRM_ACTION' => append_nuke_sid("userdel.$phpEx"),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);
  $template_nuke->pparse('confirm_body');
include("includes/page_tail.php");
}
//
// END Confirm
//

/*
єєєєєєєєєєєєєєєєєєєєєєєєєєєє    Begin  delete   єєєєєєєєєєєєєєєєєєєєєєєєєєєєєєєє
*/

include("includes/nuke_page_header.php");

$template_nuke->set_filenames(array(
'body' => 'user_delete_body.tpl'));
make_jumpbox('viewforum.'.$phpEx);

$nuke_user_id = $nuke_user_deleted_id;
if (!($deleted_userdata = get_userdata($nuke_user_id)))
{
	message_die(NUKE_GENERAL_MESSAGE, 'User is unknown!');
}
$nuke_username=$deleted_userdata['username'];


/*##############################################################################
 		START - SECTION 1 -  DELETE NUKE_USER STARTED TOPICS
//############################################################################# */

if ((($mode == 2) || ($mode == 3) || ($mode == 4)) && ($total_topics > 0))
{
  // Get User started topics
      $sql = "SELECT topic_id, forum_id, topic_title
	  FROM " . NUKE_BB_TOPICS_TABLE . "
      WHERE topic_poster = " . $nuke_user_id;
	  $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get NUKE_USER topics!", "", __LINE__, __FILE__, $sql);
      if (($nuke_usertopic_ids_count = $nuke_db->sql_numrows($result)) > '0')
      {
         $top_ids = $nuke_db->sql_fetchrowset($result);
	     // Massive topic_id
	     $topics_sql = '';
         $nuke_user_topic_titles = '';
	     foreach($top_ids as $val)
	     {
              // Listing of topic titles
              $nuke_user_topic_titles .= 'Х ['. $val['topic_id']. '] '. $val['topic_title'] . '<br>';
              // Resync forums count
              forum_topics_count_minus_one($val['forum_id']);
              // List:
              $topics_sql .= (( !empty($topics_sql) ) ? ',' : '') . $val['topic_id'];
	     }

         $template_nuke->assign_block_vars('deleted_user_topics', array(
	  	 	'TOPIC_TITLES' => $nuke_user_topic_titles,
            'L_TOPIC_TITLES' => $lang['Deleted_u_topics']
         	));

         //
         // Get posts in User Topics
         //
         $sql = 'SELECT post_id, topic_id
         FROM ' . NUKE_POSTS_TABLE . '
         WHERE topic_id IN (' . $topics_sql . ')
         ORDER BY topic_id';
	  	 $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not Get posts in User Topics", "", __LINE__, __FILE__, $sql);
         if (( $nuke_userposts_ids_count = $nuke_db->sql_numrows($result)) > '0' )
         {
            $post_ids = $nuke_db->sql_fetchrowset($result);
            // List of posts
            $posts_sql = '';
            $deleted_posts_in_u_topics_id = '';
      	    foreach($post_ids as $val)
      	    {
               $deleted_posts_in_u_topics_id .= (( !empty($deleted_posts_in_u_topics_id) ) ? ', ' : '') . $val['post_id'] . ' (' . $val['topic_id'] . ')';
               // Resync forum
               forum_postscount_decrease_by_post_id($val['post_id']);
     		   $posts_sql .= (( !empty($posts_sql) ) ? ',' : '') . $val['post_id'];
            }
            // Output
            $template_nuke->assign_block_vars('deleted_posts_in_user_topics', array(
	  	 		'NUKE_DELETED_POSTS_IN_U_TOPICS' => $deleted_posts_in_u_topics_id,
            	'L_NUKE_DELETED_POSTS_IN_U_TOPICS' => $lang['deleted_posts_in_user_topics']
         		));

            // Get IDs of users, whose posts are deleted in User Topics
            $sql = 'SELECT poster_id
            FROM ' . NUKE_POSTS_TABLE . '
            WHERE poster_id != ' . $nuke_user_id. '
            AND post_id IN (' . $posts_sql . ')';
 	  	 	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get users of posts, deleted in this User topics!", "", __LINE__, __FILE__, $sql);
            if (($num_rows = $nuke_db->sql_numrows($result)) > '0'); //Resync will be later!!!
            {
            	$nuke_user_rows = $nuke_db->sql_fetchrowset($result);
            }

            //
            // ----------------  Begin delete  -----------------
            //

            $sql = 'DELETE FROM ' . NUKE_POSTS_TEXT_TABLE . '
            WHERE post_id IN (' . $posts_sql . ')';
 	  	 	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete post texts!", "", __LINE__, __FILE__, $sql);

            $sql = 'DELETE FROM ' . NUKE_POSTS_TABLE . '
            WHERE topic_id IN (' . $topics_sql . ')';
 	  	 	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete posts!", "", __LINE__, __FILE__, $sql);

            // Count deleted
            $deleted_posts_in_usertopics = $nuke_db->sql_affectedrows();

            $template_nuke->assign_block_vars('num_deleted_posts_in_usertopics', array(
                'NUM_NUKE_DELETED_POSTS_IN_U_TOPICS' => $deleted_posts_in_usertopics,
                'L_NUM_NUKE_DELETED_POSTS_IN_U_TOPICS' => $lang['num_deleted_posts_in_usertopics']
                ));

            $sql = 'DELETE FROM ' . NUKE_BB_TOPICS_TABLE . '
	        WHERE topic_poster = ' . $nuke_user_id;
 	  	 	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete topics!", "", __LINE__, __FILE__, $sql);
	        // Count deleted
	        $deleted_user_topics = $nuke_db->sql_affectedrows();

	        $template_nuke->assign_block_vars('num_of_deleted_user_topics', array(
	        	'NUM_OF_NUKE_DELETED_TOPICS' => $deleted_user_topics,
	            'L_NUM_OF_NUKE_DELETED_TOPICS' => $lang['num_of_deleted_user_topics']
	            ));

            //
            // Get forums where deleted last posts
            //
            $sql = 'SELECT forum_id, forum_name
            FROM ' . NUKE_FORUMS_TABLE . '
            WHERE forum_last_post_id IN (' . $posts_sql . ')';
 	  	 	$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get forums last post info!", "", __LINE__, __FILE__, $sql);

            if (( $num_of_recync_forums = $nuke_db->sql_numrows($result)) > '0' )
            {
	        	$recync_forums = $nuke_db->sql_fetchrowset($result);
	              // Resync forums
	            $recynced_forums1 = '';
	            foreach($recync_forums as $val)
	            {
                  // set forums last post
                  set_forum_last_post($val['forum_id']);
                  //to output forums
                  $recynced_forums1 .= 'Х '. $val['forum_name'] . '<br>';
	            }

	            $template_nuke->assign_block_vars('forums_with_new_last_posts1', array(
	              'LIST_FORUMS_WHERE_SET_NEW_LASTPOST' => $recynced_forums1,
	              'L_FORUMS_WHERE_SET_NEW_LASTPOST' => $lang['forums_with_new_last_posts'],
	              'L_NUM_FORUMS_WHERE_SET_NEW_LASTPOST' => $lang['num_forums_where_deleted_lastpost'],
	              'NUM_FORUMS_WHERE_SET_NEW_LASTPOST' => $num_of_recync_forums
	              ));
            }

            //
            // Recync users, whose posts were deleted
            //
            // Get them
            if ( $num_rows > '0')
            {
                $nuke_user_rows_sql = '';
               	foreach($nuke_user_rows as $val)
                {
                    // List of IDs
                    $nuke_user_rows_sql .= (( !empty($nuke_user_rows_sql) ) ? ',' : '') . $val['poster_id'];
                }

                // Get their names
                $sql = 'SELECT user_id, username
                FROM ' . NUKE_USERS_TABLE . '
                WHERE user_id IN (' . $nuke_user_rows_sql . ')';
 	  	 		$result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get users info!", "", __LINE__, __FILE__, $sql);
                $num_users = $nuke_db->sql_numrows($result);
                $nuke_users_recync = $nuke_db->sql_fetchrowset($result);

                $recynced_users1 = '';
                for( $i = 0; $i < $num_users; $i++ )
              	{
				  // get post count
                  $post_count = get_post_count($nuke_users_recync[$i]['user_id']);
                  // set it
                  set_post_count($nuke_users_recync[$i]['user_id'], $post_count);
                  // list them
                  $recynced_users1 .= (( !empty($recynced_users1) ) ? ', ' : '') . $nuke_users_recync[$i]['username'];
                }

                // Number of users
               	$template_nuke->assign_block_vars('recynced_users_in_usertopics', array(
                	'LIST_RECYNCED_USERS_IN_U_TOPICS' => $recynced_users1,
                	'L_RECYNCED_USERS_IN_U_TOPICS' => $lang['recynced_users_in_usertopics'],
                    'L_TOTAL_USERS_IN_U_TOPICS' => $lang['num_of_other_users_in_u_topics'],
                    'TOTAL_USERS_IN_U_TOPICS' => $num_users
                	));
            }
       }
   }
}


//##############################################################################
//   START - SECTION 2 - DELETE TOPICS WHERE POSTS OF THE NUKE_USER AND NOTHING MORE
//##############################################################################

//Get topics
if (($mode == 3) || ($mode == 4))
{
	$sql = "SELECT topic_id
	FROM " . NUKE_POSTS_TABLE . "
	WHERE poster_id = $nuke_user_id";
	  $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get posts of User!", "", __LINE__, __FILE__, $sql);
	  if ( ( $nuke_user_posts_in_other_topics = $nuke_db->sql_numrows($result)) > '0' )
	  {
	        $otheruserpost_ids = $nuke_db->sql_fetchrowset($result);
	        // List
	        $topics_with_u_posts_sql = '';
	        foreach($otheruserpost_ids as $val)
	        {
	            $topics_with_u_posts_sql .= (( !empty($topics_with_u_posts_sql) ) ? ',' : '') . $val['topic_id'];
	        }
	        //
	        // Get topics
	        //
	        $sql = "SELECT topic_id
	        FROM " . NUKE_BB_TOPICS_TABLE . "
	        WHERE topic_id IN (" . $topics_with_u_posts_sql . ")";
	        $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get topics whith user posts!", "", __LINE__, __FILE__, $sql);
	        $topics_with_user_posts = $nuke_db->sql_fetchrowset($result);

	        // Check if posts of one User - which deleted
	        $topics_with_posts_of_user_only_sql = '';
	        $posts_in_topics_with_posts_of_user_only_sql = '';
	        foreach($topics_with_user_posts as $val)
	        {
	            $sql = "SELECT post_id
	            FROM " . NUKE_POSTS_TABLE . "
	            WHERE topic_id = " . $val['topic_id'];
	            $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Could not query posts in topic!", "", __LINE__, __FILE__, $sql);
	            $all_topic_posts_count = $nuke_db->sql_numrows($result);

	            $sql = "SELECT post_id
	            FROM " . NUKE_POSTS_TABLE . "
	            WHERE topic_id = " . $val['topic_id'] . "
	            AND poster_id = " . $nuke_user_id;
	            $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Could not query user posts topic", "", __LINE__, __FILE__, $sql);
	            $nuke_user_topic_posts_count = $nuke_db->sql_numrows($result);

	            if ($all_topic_posts_count == $nuke_user_topic_posts_count)
	            {
	                // List topics where only User's posts
	                $topics_with_posts_of_user_only_sql .= (( !empty($topics_with_posts_of_user_only_sql) ) ? ',' : '') . $val['topic_id'];
	                $posts_in_topics_with_posts_of_user_only_sql .= (( !empty($posts_in_topics_with_posts_of_user_only_sql) ) ? ',' : '') . $val['post_id'];
	            }
	        }
	        //
	        // Get these topics
	        //
	        if (!empty($topics_with_posts_of_user_only_sql))
	        {
	          $sql = "SELECT topic_id, topic_title, forum_id
	            FROM " . NUKE_BB_TOPICS_TABLE . "
	            WHERE topic_id IN (" . $topics_with_posts_of_user_only_sql . ")";
	            $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get topics where only User posts!", "", __LINE__, __FILE__, $sql);
	            $topics_with_user_posts_num = $nuke_db->sql_numrows($result);
	            $topics_with_user_posts = $nuke_db->sql_fetchrowset($result);

                $deleted_topics2_titles = '';
                $topics2_del_sql = '';
                foreach($topics_with_user_posts as $val)
	        	{
	                // ¬ывод списка тем
	                $deleted_topics2_titles .= 'Х ['. $val['topic_id']. '] '. $val['topic_title'] . '<br>';
	                // ”меньшим счЄтчик тем форума на одну тему
	                forum_topics_count_minus_one($val['forum_id']);
                    // ƒл€ запроса на удаление
                    $topics2_del_sql .= (( !empty($topics2_del_sql) ) ? ',' : '') . $val['topic_id'];
                }

	          $sql = "SELECT post_id, topic_id
	            FROM " . NUKE_POSTS_TABLE . "
	            WHERE topic_id IN (" . $topics2_del_sql . ")";
	            $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get posts of User in topics where they are only!", "", __LINE__, __FILE__, $sql);
	            $posts_in_topics_with_only_user_posts_num = $nuke_db->sql_numrows($result);
	            $posts_in_topics_with_only_user_posts = $nuke_db->sql_fetchrowset($result);

                $deleted_posts_in_u_topics2_id = '';
                $posts2_del_sql = '';
                foreach($posts_in_topics_with_only_user_posts as $val)
	        	{
                    //Resync forum
                    forum_postscount_decrease_by_post_id($val['post_id']);

                    // List topics
	       			$deleted_posts_in_u_topics2 .= (( !empty($deleted_posts_in_u_topics2) ) ? ', ' : '') . $val['post_id'] . ' ( <i>t.' . $val['topic_id'] . '</i> )';

                    // List for deletion
                    $posts2_del_sql .= (( !empty($posts2_del_sql) ) ? ',' : '') . $val['post_id'];
                }

                //
                //###################  Now delete ########################
                //
                $sql = 'DELETE FROM ' . NUKE_POSTS_TEXT_TABLE . '
                WHERE post_id IN (' . $posts2_del_sql . ')';
                $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete posts!", "", __LINE__, __FILE__, $sql);

                $sql = 'DELETE FROM ' . NUKE_POSTS_TABLE . '
                WHERE post_id IN (' . $posts2_del_sql . ')';
                $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete post!", "", __LINE__, __FILE__, $sql);
                // Count deleted
                $num_deleted_posts_in_usertopics2 = $nuke_db->sql_affectedrows();

                $sql = 'DELETE FROM ' . NUKE_BB_TOPICS_TABLE . '
                WHERE topic_id IN (' . $topics2_del_sql . ')';
                $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete topics where only User posts!", "", __LINE__, __FILE__, $sql);
                // Count deleted
                $deleted_user_topics2 = $nuke_db->sql_affectedrows();

                $template_nuke->assign_block_vars('topics_where_only_this_user_posts', array(
                    'L_NUKE_DELETED_TOPICS2' => $lang['deleted_user_topics2'],
                    'LIST_NUKE_DELETED_TOPICS2' => $deleted_topics2_titles,
                    'TOTAL_NUKE_DELETED_TOPICS2' => $deleted_user_topics2,
                    'L_TOTAL' => $lang['Total'],

                    'LIST_NUKE_DELETED_POSTS_IN_U_TOPICS2' => $deleted_posts_in_u_topics2,
                    'TOTAL_NUKE_DELETED_POSTS_IN_U_TOPICS2' => $num_deleted_posts_in_usertopics2,
                    'L_NUKE_DELETED_POSTS_IN_U_TOPICS2' => $lang['deleted_posts_in_usertopics2']
                    ));

                //
	            // Get forums to resync last posts
	            //
	            $sql = 'SELECT forum_id, forum_name
	            FROM ' . NUKE_FORUMS_TABLE . '
	            WHERE forum_last_post_id IN (' . $posts2_del_sql . ')';
	            $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get forums last posts!", "", __LINE__, __FILE__, $sql);
	            if (( $num_of_recync_forums2 = $nuke_db->sql_numrows($result)) > '0' )
	            {
                    $recync_forums2 = $nuke_db->sql_fetchrowset($result);
                    // ѕереназначение  последнего поста форума (forum_last_post_id)
                    $recynced_forums2 = '';
                    foreach($recync_forums2 as $val)
                    {
                          // set forums last post
                          set_forum_last_post($val['forum_id']);
                          //to output forums
                          $recynced_forums2 .= 'Х '. $val['forum_name'] . '<br>';
                    }
                   $template_nuke->assign_block_vars('forums_with_new_last_posts2', array(
                        'L_FORUMS_WHERE_SET_NEW_LASTPOST2' => $lang['forums_with_new_last_posts'],
                        'L_NUM_FORUMS_WHERE_SET_NEW_LASTPOST2' => $lang['num_forums_where_deleted_lastpost'],
                        'NUM_FORUMS_WHERE_SET_NEW_LASTPOST2' => $num_of_recync_forums2,
	                    'LIST_FORUMS_WHERE_SET_NEW_LASTPOST2' => $recynced_forums2,
	                    ));
	            }
			}
    }

//##################################################################
//               END - SECTION 2
//##################################################################

    //
    // Get User posts from other topics
    //
    $sql = "SELECT post_id, topic_id
    FROM " . NUKE_POSTS_TABLE . "
    WHERE poster_id = $nuke_user_id";
    $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get User posts in other topics!", "", __LINE__, __FILE__, $sql);
    if ( ( $nuke_user_posts_in_other_topics = $nuke_db->sql_numrows($result)) > '0' )
    {
	        $otheruserpost_ids = $nuke_db->sql_fetchrowset($result);
	        // List of posts
	        $otheruserpost_ids_sql = '';
	        foreach($otheruserpost_ids as $val)
	        {
	               $otheruserpost_ids_sql .= (( !empty($otheruserpost_ids_sql) ) ? ',' : '') . $val['post_id'];
                   // Listing
                   $deleted_posts_in_other_topics_list .= (( !empty($deleted_posts_in_other_topics_list) ) ? ', ' : '') . $val['post_id'] . ' ( t.' . $val['topic_id'] . ')';

	               // Resync topic replies count
	               topic_replyes_count_decrease($val['post_id']);

	               // Resync forums posts number
	               forum_postscount_decrease_by_post_id($val['post_id']);
	         }

	        $sql = 'DELETE FROM ' . NUKE_POSTS_TABLE . '
	        WHERE post_id IN (' . $otheruserpost_ids_sql .')';
	        $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete post of User in other topic!", "", __LINE__, __FILE__, $sql);
	        //Count deleted
	        $deleted_userposts = $nuke_db->sql_affectedrows();


	        $sql = 'DELETE FROM ' . NUKE_POSTS_TEXT_TABLE . '
	        WHERE post_id IN (' . $otheruserpost_ids_sql .')';
	        $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not delete post of User in other topic!", "", __LINE__, __FILE__, $sql);
	        $deleted_user_postеxts = $nuke_db->sql_affectedrows();

	        $template_nuke->assign_block_vars('all_other_user_posts_in_other_topics', array(
                'L_OTHER_POSTS' => $lang['all_other_uposts'],
                'LIST_NUKE_DELETED_OTHER_POSTS' => $deleted_posts_in_other_topics_list,

                'L_TOTAL' => $lang['Total'],
	            'NUM_OF_OTHER_POSTS' => $deleted_userposts
	            ));

	        //
	        // Get topics where deleted posts were last posts ( to resync )
	        //
	        $sql = 'SELECT topic_id, topic_title
	        FROM ' . NUKE_BB_TOPICS_TABLE . '
	        WHERE topic_last_post_id IN (' . $otheruserpost_ids_sql . ')
	        OR topic_first_post_id IN (' . $otheruserpost_ids_sql . ')';
	        $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Get topics where deleted posts were last posts ( to resync )!", "", __LINE__, __FILE__, $sql);
	        if (( $num_topics_with_deleted_fl_posts = $nuke_db->sql_numrows($result)) > '0' )
	        {
	           $topicscync_ids = $nuke_db->sql_fetchrowset($result);
	           // Resync topics
               $topics_recynced_first_or_last_posts_list = '';
	           foreach($topicscync_ids as $val)
	           {
	               // List topics
	               $topics_recynced_first_or_last_posts_list .= 'Х [ '. $val['topic_id']. ' ] '. $val['topic_title'] . '<br>';
	               topic_max_post_recync($val['topic_id']);
	               topic_min_post_recync($val['topic_id']);
               }
	           $template_nuke->assign_block_vars('topics_to_recync_first_or_lastpost', array(
                    'L_TOPIC_TO_RECYNK_FIRST_LAST_POSTS' => $lang['topics_to_recync_first_or_lastpost'],
                    'LIST_TOPICS_TO_RECYNK_FIRST_LAST_POSTS' => $topics_recynced_first_or_last_posts_list,
	                'NUM_TOPICS_TO_RECYNK_FIRST_LAST_POSTS' => $num_topics_with_deleted_fl_posts,
	                'TOTAL' => $lang['Total']
	                ));
	        }

   	     	//
	        // Resync forums last post
	        //
	        $sql = 'SELECT forum_id, forum_name
	        FROM ' . NUKE_FORUMS_TABLE . '
	        WHERE forum_last_post_id IN (' . $otheruserpost_ids_sql . ')';
	        $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Can not get forum last posts ( to resync )!", "", __LINE__, __FILE__, $sql);
	        if (( $num_of_recync_forums3 = $nuke_db->sql_numrows($result)) > '0' )
	        {
	            $recync_forums3 = $nuke_db->sql_fetchrowset($result);
	            // ѕереназначение  последнего поста форума (forum_last_post_id)
	            $recynced_forums3 = '';
	            foreach( $recync_forums3 as $val  )
	            {
	                  // set forums last post
	                  set_forum_last_post($val['forum_id']);
	                  //to output forums
	                  $recynced_forums3 .= 'Х '. $val['forum_name'] . '<br>';
	            }

	          $template_nuke->assign_block_vars('forums_where_deleted_lastpost3', array(
                'L_FORUMS_WHERE_SET_NEW_LASTPOST3' => $lang['forums_with_new_last_posts'],
	            'LIST_FORUMS_WHERE_SET_NEW_LASTPOST3' => $recynced_forums3,

	            'NUM_FORUMS_WHERE_SET_NEW_LASTPOST3' => $num_of_recync_forums3,
                'L_NUM_FORUMS_WHERE_SET_NEW_LASTPOST3' => $lang['num_forums_where_deleted_lastpost']
	            ));
	         }
   }
  else // no User posts in other topics
   {
        $template_nuke->assign_block_vars('no_other_uposts_in_other_topics', array(
            'L_NO_OTHER_UPOSTS_IN_OTHERTOPICS' => $lang['No_other_uposts_in_other_topics']
            ));
   }
}


/* #############################################################################
           START - SECTION 3 - DELETE ONLY NUKE_USER without his (her) postings
###############################################################################*/

if ($mode == 1)
{
  	$sql = "UPDATE " . NUKE_POSTS_TABLE . "
       	SET poster_id = " . NUKE_DELETED . ", post_username = '$nuke_username'
       	WHERE poster_id = $nuke_user_id";
	    $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Could not update posts for this user!", "", __LINE__, __FILE__, $sql);

    $sql = "UPDATE " . NUKE_BB_TOPICS_TABLE . "
        SET topic_poster = " . NUKE_DELETED . "
        WHERE topic_poster = $nuke_user_id";
	    $result = $nuke_db->sql_query($sql) or message_die(NUKE_GENERAL_ERROR, "Could not update topics for this user!", "", __LINE__, __FILE__, $sql);
}

/* #############################################################################

           START - SECTION 4 - DELETE THE NUKE_USER
( this code taken directly from admin_users.php of phpBB 2.0.19 - no changes in code was made)

###############################################################################*/

if ($mode != 4)
	{
			$sql = "SELECT g.group_id
				FROM " . NUKE_USER_GROUP_TABLE . " ug, " . NUKE_GROUPS_TABLE . " g
				WHERE ug.user_id = $nuke_user_id
					AND g.group_id = ug.group_id
					AND g.group_single_user = 1";
			if( !($result = $nuke_db->sql_query($sql)) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not obtain group information for this user', '', __LINE__, __FILE__, $sql);
			}

			$row = $nuke_db->sql_fetchrow($result);

			$sql = "UPDATE " . NUKE_VOTE_USERS_TABLE . "
				SET vote_user_id = " . NUKE_DELETED . "
				WHERE vote_user_id = $nuke_user_id";
			if( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not update votes for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "SELECT group_id
				FROM " . NUKE_GROUPS_TABLE . "
				WHERE group_moderator = $nuke_user_id";
			if( !($result = $nuke_db->sql_query($sql)) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not select groups where user was moderator', '', __LINE__, __FILE__, $sql);
			}

			while ( $row_group = $nuke_db->sql_fetchrow($result) )
			{
				$group_moderator[] = $row_group['group_id'];
			}

			if ( count($group_moderator) )
			{
				$update_moderator_id = implode(', ', $group_moderator);

				$sql = "UPDATE " . NUKE_GROUPS_TABLE . "
					SET group_moderator = " . $nuke_userdata['user_id'] . "
					WHERE group_moderator IN ($update_moderator_id)";
				if( !$nuke_db->sql_query($sql) )
				{
					message_die(NUKE_GENERAL_ERROR, 'Could not update group moderators', '', __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . NUKE_USERS_TABLE . "
				WHERE user_id = $nuke_user_id";
			if( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_USER_GROUP_TABLE . "
				WHERE user_id = $nuke_user_id";
			if( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete user from user_group table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_GROUPS_TABLE . "
				WHERE group_id = " . $row['group_id'];
			if( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_AUTH_ACCESS_TABLE . "
				WHERE group_id = " . $row['group_id'];
			if( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_TOPICS_WATCH_TABLE . "
				WHERE user_id = $nuke_user_id";
			if ( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete user from topic watch table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_BANLIST_TABLE . "
				WHERE ban_userid = $nuke_user_id";
			if ( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete user from banlist table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_BB_SESSIONS_TABLE . "
				WHERE session_user_id = $nuke_user_id";
			if ( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete sessions for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . NUKE_BB_SESSIONS_KEYS_TABLE . "
				WHERE user_id = $nuke_user_id";
			if ( !$nuke_db->sql_query($sql) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not delete auto-login keys for this user', '', __LINE__, __FILE__, $sql);
			}

			$sql = "SELECT privmsgs_id
				FROM " . NUKE_PRIVMSGS_TABLE . "
				WHERE privmsgs_from_userid = $nuke_user_id
					OR privmsgs_to_userid = $nuke_user_id";
			if ( !($result = $nuke_db->sql_query($sql)) )
			{
				message_die(NUKE_GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
			}

			// This little bit of code directly from the private messaging section.
			while ( $row_privmsgs = $nuke_db->sql_fetchrow($result) )
			{
				$mark_list[] = $row_privmsgs['privmsgs_id'];
			}

			if ( count($mark_list) )
			{
				$delete_sql_id = implode(', ', $mark_list);

				$delete_text_sql = "DELETE FROM " . NUKE_PRIVMSGS_TEXT_TABLE . "
					WHERE privmsgs_text_id IN ($delete_sql_id)";
				$delete_sql = "DELETE FROM " . NUKE_PRIVMSGS_TABLE . "
					WHERE privmsgs_id IN ($delete_sql_id)";

				if ( !$nuke_db->sql_query($delete_sql) )
				{
					message_die(NUKE_GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
				}

				if ( !$nuke_db->sql_query($delete_text_sql) )
				{
					message_die(NUKE_GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
				}
			}
    }
    else
    {
     set_post_count($nuke_user_id, 0);
    }

//
//  Now output
//

$template_nuke->assign_vars(array(
	'L_DELETION_TITLE' => sprintf($final_anno, $nuke_user_name),

    'RETURN_TO_INDEX' => sprintf($lang['Click_return_index'], '<a href="' . append_nuke_sid("index.$phpEx") . '">', '</a>'),
    'RETURN_TO_MEMBERLIST' => sprintf($lang['Click_return_to_authors'], '<a href="' . append_nuke_sid("memberlist.$phpEx") . '">', '</a>')
	));

if ($mode == 1)
{
   $template_nuke->assign_block_vars('resume_to_simple_deletion', array(
	 'RESUME_TO_SIMPLE_DELETION'=> sprintf($lang['Resume_to_simple_user_deletion'], $total_topics, $total_posts)
       ));
}


$template_nuke->pparse('body');

include("includes/page_tail.php");

?>