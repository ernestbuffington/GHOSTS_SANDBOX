<?php
/*=======================================================================
 PHP-Nuke Titanium v3.0.0 : Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Server Info Administration
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : good_afternoon.php
   Author(s)     : Technocrat (www.Nuke-Evolution.com)
   Version       : 1.0.0
   Date          : 05.19.2005 (mm.dd.yyyy)

   Notes         : Evo User Block Good Afternoon Module
************************************************************************/

if(!defined('NUKE_EVO')) {
   die ("Illegal File Access");
}

global $evouserinfo_addons, $evouserinfo_good_afternoon, $lang_evo_userblock;


function evouserinfo_create_date($format, $gmepoch, $tz)
{
    global $board_config, $lang, $nuke_userdata, $pc_dateTime;
    
	static $translate;
    
    if (!defined('NUKE_ANONYMOUS')) {
        define('NUKE_ANONYMOUS', 1);
        define('MANUAL', 0);
        define('MANUAL_DST', 1);
        define('SERVER_SWITCH', 2);
        define('FULL_SERVER', 3);
        define('SERVER_PC', 4);
        define('FULL_PC', 6);
    }

    if ( empty($translate) && $board_config['default_lang'] != 'english' && is_array($lang['datetime']))
    {
        @reset($lang['datetime']);
    
	    while ( list($match, $replace) = @each($lang['datetime']) )
        {
            $translate[$match] = $replace;
        }
    }


    if ( $nuke_userdata['user_id'] != NUKE_ANONYMOUS )
    {
        switch ( $nuke_userdata['user_time_mode'] )
        {
            case MANUAL_DST:
                $dst_sec = $nuke_userdata['user_dst_time_lag'] * 60;
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
                break;
            case SERVER_SWITCH:
                $dst_sec = date('I', $gmepoch) * $nuke_userdata['user_dst_time_lag'] * 60;
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
                break;
            case FULL_SERVER:
                return ( !empty($translate) ) ? strtr(@date($format, $gmepoch), $translate) : @date($format, $gmepoch);
                break;
            case SERVER_PC:
                if ( isset($pc_dateTime['pc_timezoneOffset']) )
                {
                    $tzo_sec = $pc_dateTime['pc_timezoneOffset'];
                } else
                {
                    $nuke_user_pc_timeOffsets = explode("/", $nuke_userdata['user_pc_timeOffsets']);
                    $tzo_sec = $nuke_user_pc_timeOffsets[0];
                }
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
                break;
            case FULL_PC:
                if ( isset($pc_dateTime['pc_timeOffset']) )
                {
                    $tzo_sec = $pc_dateTime['pc_timeOffset'];
                } else
                {
                    $nuke_user_pc_timeOffsets = explode("/", $nuke_userdata['user_pc_timeOffsets']);
                    $tzo_sec = (isset($nuke_user_pc_timeOffsets[1])) ? $nuke_user_pc_timeOffsets[1] : '';
                }
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
                break;
            default:
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
                break;
        }
    } 
	else
    {
        switch ( $board_config['default_time_mode'] )
        {
            case MANUAL_DST:
                $dst_sec = $board_config['default_dst_time_lag'] * 60;
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
                break;
            case SERVER_SWITCH:
                $dst_sec = date('I', $gmepoch) * $board_config['default_dst_time_lag'] * 60;
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec), $translate) : @gmdate($format, $gmepoch + (3600 * $tz) + $dst_sec);
                break;
            case FULL_SERVER:
                return ( !empty($translate) ) ? strtr(@date($format, $gmepoch), $translate) : @date($format, $gmepoch);
                break;
            case SERVER_PC:
                if ( isset($pc_dateTime['pc_timezoneOffset']) )
                {
                    $tzo_sec = $pc_dateTime['pc_timezoneOffset'];
                } else
                {
                    $tzo_sec = 0;
                }
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
                break;
            case FULL_PC:
                if ( isset($pc_dateTime['pc_timeOffset']) )
                {
                    $tzo_sec = $pc_dateTime['pc_timeOffset'];
                } else
                {
                    $tzo_sec = 0;
                }
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + $tzo_sec), $translate) : @gmdate($format, $gmepoch + $tzo_sec);
                break;
            default:
                return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
                break;
        }
    }
}

if(is_user()) 
{
    global $nuke_userinfo;
    $uname = UsernameColor($nuke_userinfo['username']);
} 
else 
{
    $uname = $lang_evo_userblock['BLOCK']['ANON'];
}

global $nuke_userinfo;

if(is_user() && isset($nuke_userinfo) && is_array($nuke_userinfo)) 
{
    $evouserinfo_time = evouserinfo_create_date('G', time(), $nuke_userinfo['user_timezone']);
} 
else 
{
    global $board_config;

    $evouserinfo_time = evouserinfo_create_date('G', time(), $board_config['board_timezone']);
}

$evouserinfo_good_afternoon = "<div align=\"center\">";
//Morning
if ($evouserinfo_time >= 0 && $evouserinfo_time <= 11) 
{
    $evouserinfo_good_afternoon .= $lang_evo_userblock['BLOCK']['AFTERNOON']['MORNING']."&nbsp;";
//Afternoon
} 
else 
if ($evouserinfo_time >= 12 && $evouserinfo_time <= 17) {
    $evouserinfo_good_afternoon .= $lang_evo_userblock['BLOCK']['AFTERNOON']['AFTERNOON']."&nbsp;";
//Evening
} 
else 
if ($evouserinfo_time >= 18 && $evouserinfo_time <= 23) {
    $evouserinfo_good_afternoon .= $lang_evo_userblock['BLOCK']['AFTERNOON']['EVENING']."&nbsp;";
}
//Username
$evouserinfo_good_afternoon .= "<br /><strong>".$uname."</strong></div>";
//$evouserinfo_good_afternoon .= "<br />\n";
?>
