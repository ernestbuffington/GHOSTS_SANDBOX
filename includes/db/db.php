<?php
/* -- -----------------------------------------------------------------------------
 *  PHP-Nuke Ttianium | Nuke-Evolution Xtreme: Enhanced PHP-Nuke Web Portal System
 * -- -----------------------------------------------------------------------------
 * # LAST EDIT 9/8/2022 7:24AM Ernest ALlen Buffington
 * >> Database
 *
 * @filename    db.php
 * @author      The phpBB Group
 * @version     1.11
 * @date        Nov 24, 2011
 * @notes       n/a
 *
 * -- -----------------------------------------------------------------------------
 * Legal Stuff
 * -- -----------------------------------------------------------------------------
 *
 * (c) Copyright 2001 The phpBB Group
 * support@phpbb.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */
if (!defined('NUKE_EVO') || isset($_REQUEST['dbtype'])) 
die('Quit trying to hack my website!');
$nuke_dbtype = 'mysqli';
$nuke_dbtype = strtolower($nuke_dbtype);

if (file_exists(NUKE_DB_DIR . $nuke_dbtype . '.php')):
    require_once(NUKE_DB_DIR . $nuke_dbtype . '.php');
else:
    die('Invalid Database Type Specified!');
endif;

# connect to local database
$nuke_db = new sql_db($nuke_dbhost, $nuke_dbuname, $nuke_dbpass, $nuke_dbname, false);

# Enable 86it Developer Network Support START
if(defined('network')):
$network_db = new sql_db($network_dbhost, $network_dbuname, $network_dbpass, $network_dbname, false);
endif;
# Enable 86it Developer Network Support END 

# Load local database START
if (!$nuke_db->db_connect_id): 
exit("<br /><br /><div align='center'><img src='images/error/question.png'>
<br /><br /><strong>There seems to be a problem with the MariaDB server, sorry for the inconvenience.<br /><br />We should be back shortly.</strong></div>");
endif;
# Load local database END

# Enable 86it Network Support START
if(defined('network')):
  if (!$network_db->db_connect_id): 
  exit("<br /><br /><div align='center'><img src='images/error/question.png'>
  <br /><br /><strong>There seems to be a problem with the MsriaDB server, sorry for the inconvenience.<br /><br />We should be back shortly.</strong></div>");
  endif;
endif;
# Enable 86it Network Support END