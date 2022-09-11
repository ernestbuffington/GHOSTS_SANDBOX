<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/

/*****[BEGIN]******************************************
 [ Base:    Nuke Patched                       v3.1.0 ]
 ******************************************************/
if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}
/*****[END]********************************************
 [ Base:    Nuke Patched                       v3.1.0 ]
 ******************************************************/

if (is_admin())
{
    if ($popup != "1"){
        $nuke_module_name = basename(dirname(__FILE__));
        require("modules/".$nuke_module_name."/nukebb.php");
    }
    else
    {
        $phpbb2_root_path = 'modules/Forums/';
    }

    define('IN_PHPBB2', true);
    include($phpbb2_root_path . 'extension.inc');
    include($phpbb2_root_path . 'common.'.$phpEx);
    include('includes/functions_search.'.$phpEx);

    // Start session management
    $nuke_userdata = session_nuke_pagestart($nuke_user_ip, NUKE_PAGE_SEARCH);
    init_userprefs($nuke_userdata);
    // End session management

    //*****  check users and user groups ****//

    $sql = "SELECT user_id, username
        FROM " . NUKE_USERS_TABLE ."
        WHERE user_id > 0";
    if ( !($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Could not obtain user list', '', __LINE__, __FILE__, $sql);
    }

    $liste ='';
    while ( $row = $nuke_db->sql_fetchrow($result) )
    {
       $nuke_username = $row['username'];
       $nuke_user_id = $row['user_id'];
       $nuke_usergroup = '';
        
       $sql1 = "SELECT ug.group_id
              FROM " . NUKE_USER_GROUP_TABLE ." ug, ". NUKE_GROUPS_TABLE. " g
              WHERE ug.user_id = $nuke_user_id
                AND ug.group_id = g.group_id
                AND g.group_single_user  = 1
                ";
                  
       if ( ($result1 = $nuke_db->sql_query($sql1)) )
       {
           $row1 = $nuke_db->sql_fetchrow($result1);
              $nuke_usergroup =( ( $row1['group_id'] != '' ) ? $row1['group_id'] : 'User has no user group'.$row1 );
              
       }

              if (!($row1['group_id'] != ''))
              {
                  
             $sql2 = "SELECT MAX(group_id) AS total
                FROM " . NUKE_GROUPS_TABLE;
             if ( !($result2 = $nuke_db->sql_query($sql2)) )
             {
                message_die(NUKE_GENERAL_ERROR, 'Could not obtain next group_id information', '', __LINE__, __FILE__, $sq2l);
             }

             if ( !($row2 = $nuke_db->sql_fetchrow($result2)) )
             {
                message_die(NUKE_GENERAL_ERROR, 'Could not obtain next group_id information', '', __LINE__, __FILE__, $sql2);
             }
             $group_id = $row2['total'] + 1;
              
              
             $sql3 = "INSERT INTO " . NUKE_GROUPS_TABLE . " (group_id, group_name, group_description, group_single_user, group_moderator)
                VALUES ($group_id, '', 'Personal User', 1, 0)";
             if ( !($result3 = $nuke_db->sql_query($sql3)) )
             {
                message_die(NUKE_GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql3);
             }

             $sql4 = "INSERT INTO " . NUKE_USER_GROUP_TABLE . " (user_id, group_id, user_pending)
                VALUES ($nuke_user_id, $group_id, 0)";
             if( !($result4 = $nuke_db->sql_query($sql4)) )
             { 
                message_die(NUKE_GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql4);
             }

                  
                 $nuke_usergroup = $nuke_usergroup.', adding user group '.$group_id;
              }


       $liste .= ( ( $liste != '' ) ? '<br /> ' : '' ) . $nuke_username.' <b>'.$nuke_usergroup.'</b>';
    }

    message_die(NUKE_GENERAL_MESSAGE,'Users:<br />'.$liste);
}
else
{
    nuke_redirect('index.php');
}

?>