<?php 
/*=======================================================================
  86it Network Config File
 =======================================================================*/
## LAST EDIT 09/08/2022 6:31AM Ernest Allen Buffington
/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if(realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) exit('Access Denied');

global $network_dbhost, $network_dbname, $network_dbuname, $network_db, $network_prefix; 
define('network', 'enabled');
if ( defined('network') ):
$network_dbhost       = 'localhost';
$network_dbname       = 'hub_db';
$network_dbuname      = 'hub_barebones';
$network_dbpass       = 'xwdNPADv86bb';
$network_prefix = 'network';
endif;
?>
