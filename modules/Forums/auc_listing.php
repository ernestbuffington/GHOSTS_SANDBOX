<?php
/***************************************************************************
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System 
 ***************************************************************************/
/***************************************************************************
 *                             auc_listing.php
 *                            -----------------
 *        Version            : 1.0.5
 *        Email            : austin@phpbb-amod.com
 *        Site            : http://phpbb-tweaks.com
 *        Copyright        : aUsTiN-Inc 2003/5 
 ***************************************************************************/
/***************************************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ***************************************************************************/ 
if(!defined('MODULE_FILE')) 
exit("You can't access this file directly...");

if ($popup != "1")
{
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
include($phpbb2_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_auc.' . $phpEx);

# Start session management 
$nuke_userdata = session_pagestart($nuke_user_ip, NUKE_PAGE_INDEX); 
init_userprefs($nuke_userdata); 
# End session management 

$group = (!empty($HTTP_POST_VARS['id'])) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id']; 
$exist = $HTTP_GET_VARS['group'];        
    
$template_nuke->set_filenames(array('body' => 'auc_listing_body.tpl') );    
        
if($exist):

  if($exist == "admins"): 
     $group_name = str_replace("%s", "", $lang['Admin_online_color']);        
     $g = NUKE_ADMIN;
  elseif($exist == "mods"): 
     $group_name = str_replace("%s", "", $lang['Mod_online_color']);
     $g = NUKE_MOD;
  elseif($exist == "less_admins"): 
     $group_name = str_replace("%s", "", $lang['Super_Mod_online_color']);    
     $g = LESS_ADMIN;
  endif;
                                    
  $template_nuke->assign_vars(array(
  "T_L" => $lang['listing_left'], 
  "T_C_2" => $group_name, 
  "T_R" => $lang['listing_right'])
  ); 
             
  $i = 1;
                                                                    
  $q = "SELECT * FROM ".NUKE_USERS_TABLE." 
                 WHERE user_level = '".$g."' 
                 ORDER BY user_id ASC"; 
  
  $r = $nuke_db->sql_query($q);
     
  while($row1 = $nuke_db->sql_fetchrow($r)):
  
     $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
     $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 

     $www = ($row1['user_website']) ? '<a href="'.$row1['user_website'].'" target="_userwww"><img src="'.$images['icon_www'].'" alt="'.$lang['Visit_website'].'" title="'
	 .$lang['Visit_website'].'" border="0" /></a>' : '';
        
	 $mailto = ($board_config['board_email_form']) ? append_sid("profile.$phpEx?mode=email&amp;".NUKE_POST_USERS_URL.'='.$row1['user_id']) : 'mailto:'.$row1['user_email'];            
     
	 $mail = ($row1['user_email']) ? '<a href="'.$mailto.'"><img src="'.$images['icon_email'].'" alt="'.$lang['Send_email'].'" title="'.$lang['Send_email']
	 .'" border="0" /></a>' : '';
        
	 $pmto = append_sid("privmsg.$phpEx?mode=post&amp;". NUKE_POST_USERS_URL ."=$row1[user_id]");
     $pm = '<a href="'.$pmto.'"><img src="'.$images['icon_pm'] .'" alt="'.$lang['Send_private_message'].'" title="'.$lang['Send_private_message'].'" border="0" /></a>';
     $pro = append_sid("profile.$phpEx?mode=viewprofile&amp;".NUKE_POST_USERS_URL."=$row1[user_id]");
     $profile = '<a href="'.$pro.'"><img src="'.$images['icon_profile'].'" alt="'.$lang['Profile'].'" title="'.$lang['Profile'].'" border="0" /></a>';        
        
     $info = $profile." ".$pm;
     
	 if($www)
	 $info .= " ".$www;
        
	 if($mail)    
	 $info .= " ".$mail;
        
     if ($row1['user_level'] == NUKE_ADMIN)
     $style_color = '#'.$theme['fontcolor3'];
     elseif ($row1['user_level'] == NUKE_MOD)
     $style_color = '#'.$theme['fontcolor2'];
     elseif ($row['user_level'] == LESS_ADMIN)
     $style_color = '#'.$theme['fontcolor4'];
                    
     $template_nuke->assign_block_vars("colors", array(
      "NUKE_USER"         => "<font color='".$style_color."'>".$row1['username']."</font>", 
      "ROW_CLASS"    => $row_class,
      "INFO_LINE"    => $info)
      ); 
      $i++;        
     endwhile;            

elseif($group):
 
   $sql = "SELECT * FROM ".$prefix."_bbadvanced_username_color
                    WHERE group_id = '".$group."' "; 
					
   if(!$result = $nuke_db->sql_query($sql)) 
   message_die(NUKE_GENERAL_ERROR, "Error Selecting Group Name.", "", __LINE__, __FILE__, $sql); 
   
   $row = $nuke_db->sql_fetchrow($result);
             
   $i = 1;
                                                                    
   $q = "SELECT * FROM ".NUKE_USERS_TABLE." 
                  WHERE user_color_gi <> '' 
                  AND user_allow_viewonline = 1
				  ORDER BY username ASC"; 
   
   $r = $nuke_db->sql_query($q);
   $row1 = $nuke_db->sql_fetchrowset($r);
            
   for($a = 0; $a < count($row1); $a++):
     if(!$row1[$a]['user_id']) 
     break;
                                        
     if(preg_match('/--'. $group .'--/i', $row1[$a]['user_color_gi'])):
       $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];  
       $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 
                      
       $www = ($row1[$a]['user_website']) ? '<a href="'.$row1[$a]['user_website'].'" target="_userwww"><img src="'.$images['icon_www'].'" alt="'.$lang['Visit_website'] 
	   .'" title="'.$lang['Visit_website'].'" border="0" /></a>' : '';
	   
       $mailto = ($board_config['board_email_form']) ? append_sid("profile.$phpEx?mode=email&amp;".NUKE_POST_USERS_URL.'='.$row1[$a]['user_id']) : 'mailto:'.$row1[$a]['user_email'];            
       $mail = ($row1[$a]['user_email']) ? '<a href="'.$mailto.'"><img src="'.$images['icon_email'].'" alt="'.$lang['Send_email'].'" title="'.$lang['Send_email'] 
	   .'" border="0" /></a>' : '';
       
	   $pmto = append_sid("privmsg.$phpEx?mode=post&amp;".NUKE_POST_USERS_URL."=".$row1[$a]['user_id']);
       $pm = '<a href="'.$pmto.'"><img src="'.$images['icon_pm'].'" alt="'.$lang['Send_private_message'].'" title="'.$lang['Send_private_message'].'" border="0" /></a>';
	   
       $pro = append_sid("profile.$phpEx?mode=viewprofile&amp;".NUKE_POST_USERS_URL."=".$row1[$a]['user_id']);
	   
       $profile = '<a href="'.$pro.'"><img src="'.$images['icon_profile'].'" alt="'.$lang['Profile'].'" title="'.$lang['Profile'].'" border="0" /></a>';
	                   
       $info = $profile .' '. $pm;
                
       if($www)
       $info .= ' '.$www; 
       if($mail)
       $info .= ' '.$mail;
            
       $i++;
                        
       $template_nuke->assign_block_vars('colors', array(
       'NUKE_USER' => UsernameColor($row1[$a]['username']), 
       'ROW_CLASS' => $row_class,
       'INFO_LINE' => $info)
        ); 
      endif;    
   endfor;

else:
   nuke_redirect('index.'. $phpEx, TRUE); 
endif;        
   
   if($i == 1)
   message_die(NUKE_GENERAL_MESSAGE, sprintf($lang['listing_none'], '<strong>'. $row['group_name'] .'</strong>'));
                
   $template_nuke->assign_vars(array(
   "T_L" => $lang['listing_left'], 
   "T_C_2" => $row['group_name'], 
   "T_R" => $lang['listing_right'])
   ); 
                            
// Generate page
include('includes/nuke_page_header.'.$phpEx);
$template_nuke->pparse('body');
include('includes/page_tail.'.$phpEx);
?>
