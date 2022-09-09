<?php 
#########################################################################
# PHP-Nuke Titanium Network : Enhanced PHP-Nuke Web Portal System       #
#########################################################################
# [CHANGES]                                                             #
# fixed closing meta tag                                                #
######################################################################### 
/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!defined('NUKE_EVO')) { die("You can't access this file directly..."); }

  global $nuke_db, $prefix, $nuke_cache;

//Load dynamic meta tags from database           
if(($facebookmetatags = $nuke_cache->load('metatagsfacebook', 'config')) === false) 
{
  //Caching System v3.0.0
  $facebookmetatags = array();
  $sql = 'SELECT meta_name, meta_content FROM '.$prefix.'_meta_facebook';
  $result = $nuke_db->sql_query($sql, true);
  $i=0;

  while(list($facebook_meta_name, $facebook_meta_content) = $nuke_db->sql_fetchrow($result, SQL_NUM)) 
  {
      $facebookmetatags[$i] = array();
      $facebookmetatags[$i]['meta_name'] = $facebook_meta_name;
      $facebookmetatags[$i]['meta_content'] = $facebook_meta_content;
      $i++;
  }
  unset($i);
  $nuke_db->sql_freeresult($result);

 //Caching System v3.0.0
  $nuke_cache->save('metatagsfacebook', 'config', $facebookmetatags);
}

//Finally output the meta tags
for($i=0,$j=count($facebookmetatags);$i<$j;$i++) 
{
	$facebook_metatag = $facebookmetatags[$i];
    $facebook_metastring .= "<meta property=\"".$facebook_metatag['meta_name']."\" content=\"".$facebook_metatag['meta_content']."\" />\n";
}

echo $facebook_metastring;
?>