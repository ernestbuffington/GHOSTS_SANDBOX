<?php
/*=======================================================================
 PHP-Nuke Titanium v3.0.0 : Enhanced PHP-Nuke Web Portal System
 =======================================================================*/
/************************************************************************
   Nuke-Evolution: Server Info Administration
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : online.php
   Author(s)     : Technocrat (www.Nuke-Evolution.com)
   Version       : 1.0.0
   Date          : 05.19.2005 (mm.dd.yyyy)

   Notes         : Evo User Block Who Is Online Module
************************************************************************/
// ONLINE STATS

if(!defined('NUKE_EVO')) 
exit ("Illegal File Access");

global $evouserinfo_addons, $evouserinfo_online;

function evouserinfo_get_members_online() 
{
    global $prefix, $nuke_db, $lang_evo_userblock, $evouserinfo_addons, $nuke_user_prefix, $nuke_userinfo, $board_config, $Default_Theme;

    $sql = "SELECT w.uname, 
	              w.module, 
				     w.url, 
			   w.host_addr, 
			   u.user_from, 
			   u.user_rank, 
			     u.user_id, 
			  u.user_level, 
   u.user_allow_viewonline, 
          u.user_from_flag, 
		     u.user_avatar, 
		u.user_avatar_type, 
		u.user_allowavatar, 
		      u.user_email, 
		  u.user_viewemail, 
		    u.user_regdate, 
			  u.user_posts, 
			       u.theme FROM ".$prefix."_session 
				   
				   AS w LEFT JOIN ".$nuke_user_prefix."_users AS u 
				   ON u.username = w.uname 
				   WHERE w.guest = '0' 
				   OR w.guest = '2' 
				   
				   ORDER BY u.user_level 
				   
				   DESC, u.user_rank DESC, u.username";
    
	$result = $nuke_db->sql_query($sql);
    $i = 1;
    $hidden = 0;
    $out = array();
    $out['text'] = '';
    
	while ($session = $nuke_db->sql_fetchrow($result)) 
    {                                   # spacer
        $num 			= ($i < 10) ? ''.'0'.$i : $i;
		$uname 			= $session['uname'];
        $uname_color 	= UsernameColor($session['uname']);
        $level 			= $session['user_level'];
        $nuke_module 		= $session['module'];
        $url 			= $session['url'];
        $url 			= str_replace("&", "&amp;", $url);
        $where 			= '&nbsp;&nbsp;<a href="'.$url.'" alt="'.$nuke_module.'" title="'.$nuke_module.'">'.$num.'</a>.&nbsp;';
        $where 			= (is_admin()) ? $where : $num.'.&nbsp;';
        $nuke_user_from 		= $session['user_from'];
        $nuke_user_flag 		= str_replace('.png','',$session['user_from_flag']);
        
		if ($evouserinfo_addons['online_country_flag'] == 'yes'):
        $nuke_user_flag = (($session['user_from_flag']) ? '<span class="countries '.$nuke_user_flag.'" title="'.$nuke_user_from.'"></span>&nbsp;' : '');
        else:
        $nuke_user_flag = '';
        endif;

        switch( $session['user_avatar_type'] ):
        
            case NUKE_USER_AVATAR_UPLOAD:
            $poster_avatar = ( $board_config['allow_avatar_upload'] ) 
			? '<img src="'.$board_config['avatar_path'].'/'.$session['user_avatar'].'" alt="" border="0" />' : '';
            break;
            case NUKE_USER_AVATAR_REMOTE:
            $poster_avatar = '<img src="'.$session['user_avatar'].'" style="width: '.$board_config['avatar_max_width'].'; height: '.$board_config['avatar_max_height'].';" alt="" border="0" />';
            break;
            case NUKE_USER_AVATAR_GALLERY:
            $poster_avatar = ( $board_config['allow_avatar_local'] ) 
			? '<img src="'.$board_config['avatar_gallery_path'].'/'.$session['user_avatar'].'" alt="" border="0" />' : '';
            break;
        
        endswitch;

        /**
		 * Mod: Tooltip to display user information.
		 * @since 2.0.9e
		 */
        if ($evouserinfo_addons['online_tooltip'] == 'yes'):

	        $tooltip_userinfo_overlay  = '<div style="width: 300px;">';
	        
			# user name in tool tip
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['LOGIN']['USERNAME'].'<span>'.$uname_color.'</span></div>';
	        
	         # admins can always see what someones email address is
			 if (is_admin()):
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['EMAIL'].'<span>'.(($session['user_viewemail'] == 0) 
			? '<a href="mailto:'.$session['user_email'].'">'.$session['user_email'].'</a>' : $lang_evo_userblock['BLOCK']['ONLINE']['HIDDEN']).'</span></div>';
             else: 
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['EMAIL'].'<span>'.(($session['user_viewemail'] == 1) 
			? '<a href="mailto:'.$session['user_email'].'">'.$session['user_email'].'</a>' : $lang_evo_userblock['BLOCK']['ONLINE']['HIDDEN']).'</span></div>';
	         endif;
	        
			# member since in tool tip view
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['SINCE'].'<span>'.$session['user_regdate'].'</span></div>';
	        
			# post count in tooltip view
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['POST_COUNT'].'<span>
			<a href="modules.php?name=Forums&amp;file=search&amp;search_author='.$uname.'">'.$session['user_posts'].'</a></span></div>';
	        
			# current users theme in tooltip view
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['THEME'].'<span>'.(($session['theme']) 
			? $session['theme'] : $Default_Theme).'</span></div>';
	        
			# what the person in the online list are viewing at the moment - should only be available for admins
			if (is_admin()):
			$tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['VIEWING'].'<span>'.(($session['module']) 
			? '<a href="'.$session['url'].'">'.str_replace('_',' ',$session['module']).'</a>' : 
			'<a href="'.$session['url'].'">'.$lang_evo_userblock['BLOCK']['ONLINE']['HOME'].'</a>').'</span></div>';
	         endif;
			 
			 # ip address in tooltips for the person visting the website
			 if (is_admin()):
	         $tooltip_userinfo_overlay .= '  <div class="user_tooltip">'.$lang_evo_userblock['BLOCK']['ONLINE']['IP'].'<span>'.$session['host_addr'].'</span></div>';
	         endif;
	        $tooltip_userinfo_overlay .= '</div>';

	        # add the overlay
			$tooltip_userinfo = ' class="tooltip-html-side-interact" title="'.str_replace('"','\'',$tooltip_userinfo_overlay).'"';

	    else:
	    	$tooltip_userinfo = ' title="'.$lang_evo_userblock['BLOCK']['ONLINE']['VIEW'].'&nbsp;'.$uname.'\'s '.$lang_evo_userblock['BLOCK']['ONLINE']['PROFILE'].'"';
	    endif;

        if ($session['user_allow_viewonline']):
        
            if ($level == 2):
            $admin_user_level_image = 
			( $evouserinfo_addons['online_user_level_image'] == 'yes' ) 
			? '&nbsp;<img style="width: 32px; height: 8px" src="images/evo_userinfo/admin.gif" alt="">' : '';
            $out['text'] .= $where.$nuke_user_flag.'<a href="modules.php?name=Profile&amp;mode=viewprofile&amp;u=
			'.$session['user_id'].'"'.$tooltip_userinfo.'>'.$uname_color.'</a>'.$admin_user_level_image.'<br />';
            elseif ($level == 3):
            $staff_user_level_image = 
			( $evouserinfo_addons['online_user_level_image'] == 'yes' ) 
			? '&nbsp;<img style="width: 32px; height: 8px" src="images/evo_userinfo/staff.gif" alt="">' : '';
            $out['text'] .= $where.$nuke_user_flag.'<a href="modules.php?name=Profile&amp;mode=viewprofile&amp;u=
			'.$session['user_id'].'"'.$tooltip_userinfo.'>'.$uname_color.'</a>'.$staff_user_level_image.'<br />';
            else:
            $out['text'] .= $where.$nuke_user_flag.'<a href="modules.php?name=Profile&amp;mode=viewprofile&amp;u='.$session['user_id'].'"'.$tooltip_userinfo.'>'.$uname_color.'</a><br />';
            endif;
        
            elseif (is_admin() || $nuke_userinfo['user_id'] == $session['user_id']):
        
            if ($level == 2):
            $admin_user_level_image = 
			( $evouserinfo_addons['online_user_level_image'] == 'yes' ) 
			? '&nbsp;<img style="width: 32px; height: 8px" src="images/evo_userinfo/admin.gif" alt="">' : '';
            $out['text'] .= $where.$nuke_user_flag.'<a href="modules.php?name=Profile&amp;mode=viewprofile&amp;u=
			'.$session['user_id'].'"'.$tooltip_userinfo.'><i>'.$uname_color.'</i></a>'.$admin_user_level_image.'<br />';
            elseif ($level == 3):
            $staff_user_level_image = 
			( $evouserinfo_addons['online_user_level_image'] == 'yes' ) 
			? '&nbsp;<img style="width: 32px; height: 8px" src="images/evo_userinfo/staff.gif" alt="">' : '';
            $out['text'] .= $where.$nuke_user_flag.'<a href="modules.php?name=Profile&amp;mode=viewprofile&amp;u=
			'.$session['user_id'].'"'.$tooltip_userinfo.'><i>'.$uname_color.'</i></a>'.$staff_user_level_image.'<br />';
            else:
            $out['text'] .= $where.$nuke_user_flag.'<a href="modules.php?name=Profile&amp;mode=viewprofile&amp;u='.$session['user_id'].'"'.$tooltip_userinfo.'><i>'.$uname_color.'</i></a><br />';
            endif;
            $hidden++;

        else:
            $hidden++;
        endif;
        $i++;
    }
    $i--;
    $out['hidden'] = $hidden;
    $out['total'] = $i;
    $out['visible'] = $i-$hidden;
    $nuke_db->sql_freeresult($result);
    return $out;
}

function evouserinfo_get_guests_online($start) 
{
    global $prefix, $nuke_db, $lang_evo_userblock, $identify;
    $result = $nuke_db->sql_query("SELECT uname, url, module, host_addr FROM ".$prefix."_session WHERE guest='1' OR guest='3'");
    $out['total'] = $nuke_db->sql_numrows($result);
    $out['text'] = '';
    $i = $start;
    while ($session = $nuke_db->sql_fetchrow($result)):

        $num = ($i < 10) ? '0'.$i : $i;
        
        $nuke_module = $session['module'];
        $url = $session['url'];
        $url = str_replace("&", "&amp;", $url);
           //$where = '<a data-user-country="'.$session['host_addr'].'" href="'.$url.'" alt="'.$nuke_module.'" title="'.$nuke_module.'">'.$num.'</a>.&nbsp;';
           //$where = (is_admin()) ? $where : $num.'.&nbsp;';
        
		$where 			= '&nbsp;&nbsp;<a class="tooltip-html-side-interact tooltipstered" href="'.$url.'" alt="'.$nuke_module.'" title="'.$url.'">'.$num.'.&nbsp;';
        $where 			= (is_admin()) ? $where : '&nbsp;&nbsp;'.$num.'.&nbsp;';
        
		if(!is_admin()):
            $out['text'] .= $where.$lang_evo_userblock['BLOCK']['ONLINE']['GUEST']."</a><br />\n";
        else:
        
            $nuke_user_agent = $identify->identify_agent();
            if($nuke_user_agent['engine'] == 'bot'):
                $out['text'] .= $where.$nuke_user_agent['ua']."</a><br />\n";
            else:
                // $out['text'] .= "<br />".$where.$session['uname']."\n";
                $out['text'] .= $where.$session['uname']."</a><br />\n";
            endif;
        
        endif;
        $i++;
    
    endwhile;
    $nuke_db->sql_freeresult($result);
    return $out;
}

function evouserinfo_online_display($members, $guests) 
{
    global $lang_evo_userblock, $evouserinfo_addons, $nuke_userinfo;
    $out = '';
    if($evouserinfo_addons['online_show_members'] == 'yes'):
    
        $out .= '<div style="font-weight: bold">'.$lang_evo_userblock['BLOCK']['ONLINE']['STATS'].'</div>';

        $out .= '<div style="padding-left: 10px;">';
        $out .= '<font color="gold"><i class="fas fa-radiation-alt" aria-hidden="true"></i></font>&nbsp;'.$lang_evo_userblock['BLOCK']['ONLINE']['MEMBERS'].'<span style="float:right">'.$members['total'].'&nbsp;&nbsp;</span>';
        $out .= '</div>'; 

        if($evouserinfo_addons['online_show_hv'] == 'yes'):

            $out .= '<div style="padding-left: 10px;">';
            $out .= '<font color="gold"><i class="fas fa-radiation-alt" aria-hidden="true"></i></font>&nbsp;'.$lang_evo_userblock['BLOCK']['ONLINE']['VISIBLE'].'<span style="float:right">'.$members['visible'].'&nbsp;&nbsp;</span>';
            $out .= '</div>';

            $out .= '<div style="padding-left: 10px;">';
            $out .= '<font color="gold"><i class="fas fa-radiation-alt" aria-hidden="true"></i></font>&nbsp;'.$lang_evo_userblock['BLOCK']['ONLINE']['HIDDEN'].'<span style="float:right">'.$members['hidden'].'&nbsp;&nbsp;</span>';
            $out .= '</div>';

        endif;

        $out .= '<div style="padding-left: 10px;">';
        $out .= '<font color="gold"><i class="fas fa-radiation-alt" aria-hidden="true"></i></font>&nbsp;'.$lang_evo_userblock['BLOCK']['ONLINE']['GUESTS'].'<span style="float:right">'.$guests['total'].'&nbsp;&nbsp;</span>';
        $out .= '</div>';

        $out .= '<div style="padding-left: 10px;">';
        $out .= '<font color="gold"><i class="fas fa-radiation-alt" aria-hidden="true"></i></font>&nbsp;'.$lang_evo_userblock['BLOCK']['ONLINE']['TOTAL'].'<span style="float:right">'.($guests['total']+$members['total']).'&nbsp;&nbsp;</span><hr />';
        $out .= '</div>';
    
    endif;

    $out .= '<div style="font-weight: bold">Member(s) Online</div>';

    if($evouserinfo_addons['online_scroll'] == 'yes'):
    
        $out .= '<div style="overflow:auto; max-height:150px; width:100%">';
        $out .= $lang_evo_userblock['BLOCK']['ONLINE']['MEMBERS'].$lang_evo_userblock['BLOCK']['BREAK'].'<br />'.$members['text'].'<br />'.$lang_evo_userblock['BLOCK']['ONLINE']['GUESTS'].$lang_evo_userblock['BLOCK']['BREAK'].'<br />'.$guests['text'];
        $out .= '</div>';
     
    else:
    
        if ($members['total'] > 0):

            //$out .= '<div style="font-weight: bold">&nbsp;&nbsp;Portal '.$lang_evo_userblock['BLOCK']['ONLINE']['MEMBERS'].'</div>';
            $out .= '<div>'.$members['text'].'</div>';

        endif;

        if ($guests['total'] > 0):

            $out .= '<br/><div style="font-weight: bold">&nbsp;&nbsp;'.$lang_evo_userblock['BLOCK']['ONLINE']['GUESTS'].'</div>';
            $out .= '<div>'.$guests['text'].'</div>';

        endif;
    
    endif;
    return $out;
}

$evouserinfo_online_members = evouserinfo_get_members_online();
$evouserinfo_online_guests = evouserinfo_get_guests_online($evouserinfo_online_members['total']+1);
$evouserinfo_online = evouserinfo_online_display($evouserinfo_online_members, $evouserinfo_online_guests);
?>
