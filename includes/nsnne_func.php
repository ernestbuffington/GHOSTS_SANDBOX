<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/********************************************************/
/* NSN News                                             */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://nukescripts.86it.us                           */
/* Copyright (c)2000-2005 by NukeScripts Network         */
/********************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
      Caching System                           v1.0.0       10/31/2005
 ************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

function ne_save_config($nuke_config_name, $nuke_config_value){
    global $prefix, $nuke_db, $cache;
    $nuke_db->sql_query("UPDATE ".$prefix."_nsnne_config SET config_value='$nuke_config_value' WHERE config_name='$nuke_config_name'");
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
    $cache->delete('news', 'config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
}

function ne_get_configs(){
    global $prefix, $nuke_db, $cache;
    static $nuke_config;
    if(isset($nuke_config)) return $nuke_config;
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
    if(($nuke_config = $cache->load('news', 'config')) === false) {
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        $nuke_configresult = $nuke_db->sql_query("SELECT config_name, config_value FROM ".$prefix."_nsnne_config");
        while (list($nuke_config_name, $nuke_config_value) = $nuke_db->sql_fetchrow($nuke_configresult)) {
            $nuke_config[$nuke_config_name] = $nuke_config_value;
        }
        $nuke_db->sql_freeresult($nuke_configresult);
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
        $cache->save('news', 'config', $nuke_config);
    }
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
    return $nuke_config;
}

function automated_news() 
{
    global $prefix, $multilingual, $currentlang, $nuke_db;
    
	$result = $nuke_db->sql_query('SELECT * FROM '.$prefix.'_autonews WHERE datePublished<="'.date('Y-m-d G:i:s', time()).'"');
    
	while ($row2 = $nuke_db->sql_fetchrow($result)) 
	{
        $title = addslashes($row2['title']);
        $hometext = addslashes($row2['hometext']);
        $bodytext = addslashes($row2['bodytext']);
        $notes = addslashes($row2['notes']);

        $nuke_db->sql_query("INSERT INTO ".$prefix."_stories VALUES (NULL, 
		                                              '$row2[catid]', 
													    '$row2[aid]', 
														    '$title', 
											  '$row2[datePublished]',
											   '$row2[dateModified]', 
													     '$hometext', 
														 '$bodytext', 
														         '0', 
																 '0', 
													  '$row2[topic]', 
												  '$row2[informant]', 
												            '$notes', 
													  '$row2[ihome]', 
												  '$row2[alanguage]', 
												      '$row2[acomm]', 
													             '0', 
																 '0', 
																 '0', 
																 '0', 
												 '$row2[associated]', 
												                 '0', 
																 '1')");
    }
    if ($nuke_db->sql_numrows($result)) 
	{
        $nuke_db->sql_query('DELETE FROM '.$prefix.'_autonews WHERE datePublished<="'.date('Y-m-d G:i:s', time()).'"');
    }
    $nuke_db->sql_freeresult($result);
}

?>