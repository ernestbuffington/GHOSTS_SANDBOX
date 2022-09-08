<?php 
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
 ## LAST EDIT ## 09-08-2022 5:46AM by Ernest Allen Buffington
/*======================================================================= 
 *                               constants.php
 *                            -------------------
 *   begin                : Saturday', Feb 13', 2001
 *   copyright            : ('C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   Id: constants.php,v 1.47.2.5 2004/11/18 17:49:42 acydburn Exp
 *
 *   Resume               : Thursday', Sep 08', 2022
 *   copyright            : ('C) 2001 to 2022 The 86it Developers Network
 *   email                : webmaster@php-nuke-titanium.86it.us
 *   
 *   https://www.php-nuke-titanium.86it.us/modules.php?name=Network_projects&op=Project&project_id=76
 *   This version belongs to PHP-Nuke Titanium v4.0.1b
 *
 *   Id: constants.php,v 1.99.2.5 2022/9/8 12:00:00 TheGhost Exp
 =======================================================================*/
/*======================================================================= 
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 =======================================================================*/
/*========================[CHANGES]===================================== 
-=[Mod]=-
      Recent Topics                            v1.2.4       06/11/2005
      Global Announcements                     v1.2.8       06/13/2005
      Quick Search                             v3.0.1       08/23/2005
      Staff Site                               v2.0.3       06/24/2005
      Forum ACP Administration Links           v1.0.0       06/26/2005
      Advanced Time Management                 v2.1.2       07/26/2005
      XData                                    v1.0.3       02/08/2007
      At a Glance Options                      v1.0.0       08/17/2005
      Initial Usergroup                        v1.0.1       08/25/2005
      Report Posts                             v1.2.3       08/30/2005
	  Member Country Flags                     v2.0.8       09/08/2022
	  Birthdays                                v3.0.1       09/08/2022
	  Thank You Mod                            v1.1.9       09/08/2022
	  Users Reputations Systems                v1.0.0       05/25/2009
	  Inline Banner Ad                         v1.2.3       05/26/2009
	  Email topic to friend                    v1.0.0       05/26/2009
	  Arcade                                   v3.0.2       05/29/2009
      Who viewed a topic                       v1.0.9       09/08/2022
	  File Respository                         v1.0.0     
 =======================================================================*/
if (!defined('IN_PHPBB2') && !defined('NUKE_EVO') OR !defined('NUKE_TITANIUM')){die('Hacking attempt');}
# Network Support
#character set define XHTML1.0
define("_NUKE_CHARSET","utf-8");
define("_NUKE_LANG_DIRECTION","ltr");
define("_NUKE_LANGCODE","en");
define("_NUKE_MIME", "text/html"); 

# Debug Level
#define('NUKE_DEBUG', 0); // Debugging off
define('NUKE_DEBUG', 1);  // Debugging on
# User Levels <- Do not change the values of NUKE_USER or NUKE_ADMIN
define('NUKE_DELETED', -1);
define('NUKE_ANONYMOUS', 1);
define('NUKE_USER', 1);
define('NUKE_ADMIN', 2);
define('NUKE_MOD', 3);
# User related
define('NUKE_USER_ACTIVATION_NONE', 0);
define('NUKE_USER_ACTIVATION_SELF', 1);
define('NUKE_USER_ACTIVATION_ADMIN', 2);
define('NUKE_USER_AVATAR_NONE', 0);
define('NUKE_USER_AVATAR_UPLOAD', 1);
define('NUKE_USER_AVATAR_REMOTE', 2);
define('NUKE_USER_AVATAR_GALLERY', 3);
# Group settings
define('NUKE_GROUP_OPEN', 0);
define('NUKE_GROUP_CLOSED', 1);
define('NUKE_GROUP_HIDDEN', 2);
# [ Mod: Initial Usergroup v1.0.1 ]
define('NUKE_GROUP_INITIAL_NO', 0);
define('NUKE_GROUP_INITIAL_YES', 1);
# Forum state
define('NUKE_FORUM_UNLOCKED', 0);
define('NUKE_FORUM_LOCKED', 1);
# [ Mod: Thank You Mod v1.1.8 ]
# Forum Thanks state
define('NUKE_FORUM_UNTHANKABLE', 0);
define('NUKE_FORUM_THANKABLE', 1);
# [ Mod: Thank You Mod v1.1.8 ]
# Topic status
define('NUKE_TOPIC_UNLOCKED', 0);
define('NUKE_TOPIC_LOCKED', 1);
define('NUKE_TOPIC_MOVED', 2);
define('NUKE_TOPIC_WATCH_NOTIFIED', 1);
define('NUKE_TOPIC_WATCH_UN_NOTIFIED', 0);
# Topic types
define('NUKE_POST_NORMAL', 0);
define('NUKE_POST_STICKY', 1);
define('NUKE_POST_ANNOUNCE', 2);
define('NUKE_POST_GLOBAL_ANNOUNCE', 3);
# Error codes
define('NUKE_GENERAL_MESSAGE', 200);
define('NUKE_GENERAL_ERROR', 202);
define('NUKE_CRITICAL_MESSAGE', 203);
define('NUKE_CRITICAL_ERROR', 204);
# Private messaging
define('NUKE_PRIVMSGS_READ_MAIL', 0);
define('NUKE_PRIVMSGS_NEW_MAIL', 1);
define('NUKE_PRIVMSGS_SENT_MAIL', 2);
define('NUKE_PRIVMSGS_SAVED_IN_MAIL', 3);
define('NUKE_PRIVMSGS_SAVED_OUT_MAIL', 4);
define('NUKE_PRIVMSGS_UNREAD_MAIL', 5);
# URL PARAMETERS
define('NUKE_POST_TOPIC_URL', 't');
define('NUKE_POST_CAT_URL', 'c');
define('NUKE_POST_FORUM_URL', 'f');
define('NUKE_POST_USERS_URL', 'u');
define('NUKE_POST_POST_URL', 'p');
define('NUKE_POST_GROUPS_URL', 'g');
# Session parameters
define('NUKE_SESSION_METHOD_COOKIE', 100);
define('NUKE_SESSION_METHOD_GET', 101);
# Page numbers for session handling
define('NUKE_PAGE_INDEX', 0);
define('NUKE_PAGE_LOGIN', -1);
define('NUKE_PAGE_SEARCH', -2);
define('NUKE_PAGE_REGISTER', -3);
define('NUKE_PAGE_PROFILE', -4);
define('NUKE_PAGE_VIEWONLINE', -6);
define('NUKE_PAGE_VIEW_MEMBERS', -7);
define('NUKE_PAGE_FAQ', -8);
define('NUKE_PAGE_POSTING', -9);
define('NUKE_PAGE_PRIVMSGS', -10);
define('PAGE_GROUPCP', -11);
# [ Base: Who viewed a topic v1.0.3 ]
define('NUKE_PAGE_TOPIC_VIEW', -1032);
# [ Mod: Users Reputations Systems v1.0.0 ]
define('NUKE_PAGE_REPUTATION', -1280);
# [ Mod: Arcade v3.0.2 ]
define('NUKE_PAGE_GAME', -50);
define('NUKE_PAGE_ARCADES', -51);
define('NUKE_PAGE_TOPARCADES', -52);
define('NUKE_PAGE_STATARCADES', -53);
define('NUKE_PAGE_SCOREBOARD', -54);
# [ Mod: Staff Site v2.0.3 ]
define('NUKE_PAGE_STAFF', -12);
# [ Base: Recent Topics v1.2.4 ]
define('NUKE_PAGE_RECENT', -33);
define('NUKE_PAGE_TOPIC_OFFSET', 5000);
# Auth settings
define('NUKE_AUTH_LIST_ALL', 0);
define('NUKE_AUTH_ALL', 0);
define('NUKE_AUTH_REG', 1);
define('NUKE_AUTH_ACL', 2);
define('NUKE_AUTH_MOD', 3);
define('NUKE_AUTH_ADMIN', 5);
define('NUKE_AUTH_VIEW', 1);
define('NUKE_AUTH_READ', 2);
define('NUKE_AUTH_POST', 3);
define('NUKE_AUTH_REPLY', 4);
define('NUKE_AUTH_EDIT', 5);
define('NUKE_AUTH_DELETE', 6);
define('NUKE_AUTH_ANNOUNCE', 7);
define('NUKE_AUTH_STICKY', 8);
define('NUKE_AUTH_POLLCREATE', 9);
define('NUKE_AUTH_VOTE', 10);
define('NUKE_AUTH_ATTACH', 11);
# [ Mod: Global Announcements v1.2.8 ]
define('NUKE_AUTH_GLOBALANNOUNCE', 12);
define('NUKE_HIDDEN_CAT', 0); // NOTE: change this value to the forum id, of the forum, witch you would like to be hidden
# Nuke-Evolution Core Tables
define('_AUTHOR_TABLE', $prefix.'_authors');
define('_AUTONEWS_TABLE', $prefix.'_autonews');
define('_BLOCKS_TABLE', $prefix.'_blocks');
define('_COMMENTS_TABLE', $prefix.'_comments');
define('_COUNTER_TABLE', $prefix.'_counter');
define('_COUNTRY_TABLE', $prefix.'_country');
define('_EVOCONFIG_TABLE', $prefix.'_evolution');
define('_EVO_CONFIG_TABLE', $prefix.'_evolution_config');
define('_HEADLINES_TABLE', $prefix.'_headlines');
define('_MAIN_TABLE', $prefix.'_main');
define('_META_TABLE', $prefix.'_meta');
define('_MESSAGE_TABLE', $prefix.'_message');
define('_MODULES_TABLE', $prefix.'_modules');
define('_MODULES_CATEGORIES_TABLE', $prefix.'_modules_cat');
define('_MODULES_CONFIG_TABLE', $prefix.'_modules_config');
define('_MODULES_EXLINKS_TABLE', $prefix.'_modules_links');
define('_MODULES_POPUPS_TABLE', $prefix.'_modules_popups');
define('_MOSTONLINE_TABLE', $prefix.'_mostonline');
define('_NUKE_CONFIG_TABLE', $prefix.'_config');
define('_QUEUE_TABLE', $prefix.'_queue');
define('_REFERER_TABLE', $prefix.'_referer');
define('_SECURITY_BOT_TABLE', $prefix.'_security_agents');
define('_SESSION_TABLE', $prefix.'_session');
define('_THEMES_TABLE', $prefix.'_themes');
define('_THEMES_INFO_TABLE', $prefix.'_themes_info');
define('_WELCOME_PM_TABLE', $prefix.'_welcome_pm');
define('_USERS_WHO_BEEN', $prefix.'_users_who_been');
# Admin failed login check
define('_FAILED_LOGIN_INFO_TABLE', $prefix.'_admin_fc');
# Error-Log
define('_ERROR_TABLE', $prefix.'_errors');
define('_ERROR_CONFIG_TABLE', $prefix.'_errors_config');
# Evo_UserBlock
define('_BLOCK_EVO_USERINFO_TABLE', $prefix.'_evo_userinfo');
define('_BLOCK_EVO_USERINFO_ADDONS_TABLE', $prefix.'_evo_userinfo_addons');
# FAQ
define('_FAQ_ANSWER_TABLE', $prefix.'_faqanswer');
define('_FAQ_CATEGORIES_TABLE', $prefix.'_faqcategories');
# Honeypot
define('_HONEYPOT_TABLE', $prefix.'_honeypot');
define('_HONEYPOT_CONFIG_TABLE', $prefix.'_honeypot_config');
# Link Us
define('_LINKUS_CONFIG_TABLE', $prefix.'_link_us_config');
define('_LINKUS_TABLE', $prefix.'_link_us');
# News
define('_NSNNE_FUNC_TABLE', $prefix.'_nsnne_func');
define('_NSNNE_CONFIG_TABLE', $prefix.'_nsnne_config');
# Sommaire (not pre-installed within Evo)
define('_SOMMAIRE_TABLE', $prefix.'_sommaire');
define('_SOMMAIRE_CATEGORIES_TABLE', $prefix.'_sommaire_categories');
# Statistics
define('_STATS_HOUR_TABLE', $prefix.'_stats_hour');
# Stories Archive
define('_STORIES_TABLE', $prefix.'_stories');
define('_STORIES_CATEGORIES_TABLE', $prefix.'_stories_cat');
# Supporters
define('_NSNSP_SITES_TABLE', $prefix.'_nsnsp_sites');
define('_NSNSP_CONFIG_TABLE', $prefix.'_nsnsp_config');
# Surveys
define('_POLL_COMMENTS_TABLE', $prefix.'_pollcomments');
define('_POLL_DESC_TABLE', $prefix.'_poll_desc');
define('_POLL_DATA_TABLE', $prefix.'_poll_data');
define('_POLL_CHECK_TABLE', $prefix.'_poll_check');
# Topics
define('_TOPICS_TABLE', $prefix.'_topics');
# Web Links
define('_WEBLINKS_CONFIG_TABLE', $prefix.'_links_config');
define('_WEBLINKS_CATEGORIES_TABLE', $prefix.'_links_categories');
define('_WEBLINKS_SUBCATEGORIES_TABLE', $prefix.'_links_subcategories');
define('_WEBLINKS_LINKS_TABLE', $prefix.'_links_links');
define('_WEBLINKS_NEWLINK_TABLE', $prefix.'_links_newlink');
define('_WEBLINKS_EDITORIALS_TABLE', $prefix.'_links_editorials');
define('_WEBLINKS_VOTEDATA_TABLE', $prefix.'_links_votedata');
define('_WEBLINKS_MODREQUEST_TABLE', $prefix.'_links_modrequest');
# Your Account (CNBYA)
define('_CNBYA_CONFIG_TABLE', $prefix.'_cnbya_config');
define('_CNBYA_VALUE_TABLE', $prefix.'_cnbya_value');
define('_CNBYA_FIELD_TABLE', $prefix.'_cnbya_field');
define('_CNBYA_VALUE_TEMP_TABLE', $prefix.'_cnbya_value_temp');
# Table names
define('NUKE_AUC_TABLE', $prefix.'_bbadvanced_username_color');
define('NUKE_AUTH_ACCESS_TABLE', $prefix.'_bbauth_access');
define('NUKE_BANLIST_TABLE', $prefix.'_bbbanlist');
define('NUKE_CATEGORIES_TABLE', $prefix.'_bbcategories');
define('NUKE_CONFIG_TABLE', $prefix.'_bbconfig');
define('NUKE_DISALLOW_TABLE', $prefix.'_bbdisallow');
define('NUKE_FORUMS_TABLE', $prefix.'_bbforums');
define('NUKE_GROUPS_TABLE', $prefix.'_bbgroups');
define('NUKE_POSTS_TABLE', $prefix.'_bbposts');
define('NUKE_POSTS_TEXT_TABLE', $prefix.'_bbposts_text');
define('NUKE_PRIVMSGS_TABLE', $prefix.'_bbprivmsgs');
define('NUKE_PRIVMSGS_TEXT_TABLE', $prefix.'_bbprivmsgs_text');
define('NUKE_PRIVMSGS_IGNORE_TABLE', $prefix.'_bbprivmsgs_ignore');
define('NUKE_PRUNE_TABLE', $prefix.'_bbforum_prune');
define('NUKE_RANKS_TABLE', $prefix.'_bbranks');
define('NUKE_SEARCH_TABLE_RESULTS', $prefix.'_bbsearch_results');
define('NUKE_SEARCH_WORD_TABLE', $prefix.'_bbsearch_wordlist');
define('NUKE_SEARCH_MATCH_TABLE', $prefix.'_bbsearch_wordmatch');
define('NUKE_BB_SESSIONS_TABLE', $prefix.'_bbsessions');
define('NUKE_BB_SESSIONS_KEYS_TABLE', $prefix.'_bbsessions_keys');
define('NUKE_SMILIES_TABLE', $prefix.'_bbsmilies');
# [ Mod: Thank You Mod v1.1.8 ]
define('NUKE_THANKS_TABLE', $prefix.'_bbthanks');
define('NUKE_BB_THEMES_TABLE', $prefix.'_bbthemes');
define('NUKE_BB_THEMES_NAME_TABLE', $prefix.'_bbthemes_name');
define('NUKE_BB_TOPICS_TABLE', $prefix.'_bbtopics');
# [ Mod: Email topic to friend v1.0.0 ]
define('NUKE_TOPICS_EMAIL_TABLE', $prefix.'_bbtopics_email');
define('NUKE_TOPICS_WATCH_TABLE', $prefix.'_bbtopics_watch');
define('NUKE_USER_GROUP_TABLE', $prefix.'_bbuser_group');
define('NUKE_USERS_TABLE', $nuke_user_prefix.'_users');
define('NUKE_USERS_TEMP_TABLE', $nuke_user_prefix.'_users_temp');
define('NUKE_WORDS_TABLE', $prefix.'_bbwords');
define('NUKE_VOTE_DESC_TABLE', $prefix.'_bbvote_desc');
define('NUKE_VOTE_RESULTS_TABLE', $prefix.'_bbvote_results');
define('NUKE_VOTE_USERS_TABLE', $prefix.'_bbvote_voters');
# [ Base: Who viewed a topic v1.0.3 ]
define('NUKE_TOPIC_VIEW_TABLE', $prefix.'_bbtopic_view'); 
# [ Mod: Users Reputations Systems v1.0.0 ]
define('NUKE_REPUTATION_TABLE', $prefix.'_bbreputation');
define('NUKE_REPUTATION_CONFIG_TABLE', $prefix.'_bbreputation_config');
# [ Mod: Member Country Flags v2.0.7 ]
define('NUKE_FLAG_TABLE', $prefix.'_bbflags');
# [ Mod: XData v1.0.3 ]
define('NUKE_XDATA_FIELDS_TABLE', $prefix.'_bbxdata_fields');
define('NUKE_XDATA_DATA_TABLE', $prefix.'_bbxdata_data');
define('NUKE_XDATA_AUTH_TABLE', $prefix.'_bbxdata_auth');
define('NUKE_XD_AUTH_ALLOW', 1);
define('NUKE_XD_AUTH_DENY', 0);
define('NUKE_XD_AUTH_DEFAULT', 2);
define('NUKE_XD_DISPLAY_NORMAL', 1);
define('NUKE_XD_DISPLAY_ROOT', 2);
define('NUKE_XD_DISPLAY_NONE', 0);
define('NUKE_XD_REGEXP_MANDITORY', "/.+/");
define('NUKE_XD_REGEXP_LETTERS', "/^[(A-Z)|(a-z)]{1,}$/");
define('NUKE_XD_REGEXP_NUMBERS', "/^[0-9]{1,}$/");
# [ Mod: Report Posts v1.2.3 ]
define('NUKE_REPORT_POST_NEW', 1);
define('NUKE_REPORT_POST_CLOSED', 2);
define('NUKE_POST_REPORTS_TABLE', $prefix.'_bbpost_reports');
# [ Mod: Quick Search v3.0.1 ]
define('NUKE_QUICK_SEARCH_TABLE', $prefix.'_bbquicksearch');
# [ Mod: Forum ACP Administration Links v1.0.0 ]
define('ADMIN_NUKE', "../../../".$admin_file.".php");
define('HOME_NUKE', "../../../index.php");
# [ Mod: Advanced Time Management v2.1.2 ]
define('MANUAL', 0);
define('MANUAL_DST', 1);
define('SERVER_SWITCH', 2);
define('FULL_SERVER', 3);
define('SERVER_PC', 4);
define('FULL_PC', 6);
# [ Mod: Log Moderator Actions v1.1.6 ]
define('NUKE_BB_LOGS_TABLE', $prefix.'_bblogs');
define('NUKE_BB_LOGS_CONFIG_TABLE', $prefix.'_bblogs_config');
define('NUKE_BB_LOG_ACTIONS_VERSION', '1.1.6');
# [ Mod: At a Glance Options v1.0.0 ]
define('NUKE_GLANCE_NONE', 0); # Not being used anywhere 9/8/2022
define('NUKE_GLANCE_ALL', 1);
define('NUKE_GLANCE_INDEX', 2);
define('NUKE_GLANCE_FORUMS', 3);
define('NUKE_GLANCE_TOPICS', 4);
define('NUKE_GLANCE_INDEX_AND_TOPICS', 5);
define('NUKE_GLANCE_INDEX_AND_FORUMS', 6);
define('NUKE_GLANCE_FORUMS_AND_TOPICS', 7);
# [ Mod: Birthdays v3.0.0 ]
define('NUKE_BIRTHDAY_ALL',0);
define('NUKE_BIRTHDAY_DATE',1);
define('NUKE_BIRTHDAY_AGE',2);
define('NUKE_BIRTHDAY_NONE',3);
define('NUKE_BIRTHDAY_EMAIL',1);
define('NUKE_BIRTHDAY_POPUP',2);
define('NUKE_BIRTHDAY_PM',3);
# [ Mod: Arcade v3.0.2 ]
define('NUKE_GAMES_TABLE', $prefix.'_bbgames');
define('NUKE_SCORES_TABLE', $prefix.'_bbscores');
define('NUKE_GAMEHASH_TABLE', $prefix.'_bbgamehash');
define('NUKE_HACKGAME_TABLE', $prefix.'_bbhackgame');
define('NUKE_ARCADE_CATEGORIES_TABLE', $prefix.'_bbarcade_categories');
define('NUKE_ARCADE_TABLE', $prefix.'_bbarcade');
define('NUKE_AUTH_ARCADE_ACCESS_TABLE', $prefix.'_bbauth_arcade_access');
define('NUKE_COMMENTS_TABLE', $prefix.'_bbarcade_comments'); 
define('NUKE_ARCADE_FAV_TABLE', $prefix.'_bbarcade_fav');
# [ Mod: Inline Banner Ad v1.2.3 ]
define('ALL', 1);
define('NUKE_ADS_TABLE', $prefix.'_bbinline_ads');
# File Repository
define('_FILE_REPOSITORY_CATEGORIES', $prefix.'_file_repository_categories');
define('_FILE_REPOSITORY_COMMENTS',   $prefix.'_file_repository_comments');
define('_FILE_REPOSITORY_EXTENSIONS', $prefix.'_file_repository_extensions');
define('_FILE_REPOSITORY_FILES',    $prefix.'_file_repository_files');
define('_FILE_REPOSITORY_ITEMS',    $prefix.'_file_repository_items');
define('_FILE_REPOSITORY_SCREENSHOTS',  $prefix.'_file_repository_screenshots');
define('_FILE_REPOSITORY_SETTINGS',   $prefix.'_file_repository_settings');
define('_FILE_REPOSITORY_THEMES',   $prefix.'_file_repository_themes');
define('AUTHORS_TABLE', $prefix.'_authors');
define('EVOLUTION_CONFIG_TABLE', $prefix.'_evolution');
define('META_TABLE', $prefix.'_meta');
define('EVOLUTION_SESSIONS_TABLE', $prefix.'_session');
define('USERS_BEEN_TABLE', $prefix.'_users_who_been');
define('NUKE_MAIN_THEMES_TABLE', $prefix.'_themes'); // not being used anywhere as of 9/8/2022
define('HONEYPOT_TABLE', $prefix.'_honeypot');
define('IP_TO_COUNTRY_TABLE', $prefix.'_nsnst_ip2country');
?>
