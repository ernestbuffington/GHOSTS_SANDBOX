<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/
if (!defined('MODULE_FILE')) {
   die ("You can't access this file directly...");
}

global $nuke_db, $prefix, $phpbb2_root_path, $nuke_root_path, $nuke_file_path, $phpbb_root_dir, $nuke_module_name, $name, $file;
$nuke_module_name = basename(dirname(__FILE__));
$phpbb2_root_path = NUKE_PHPBB2_DIR;
get_lang($nuke_module_name);
include_once(NUKE_BASE_DIR.'header.php');
?>