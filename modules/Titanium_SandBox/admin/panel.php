<?php
if (!defined('MODULE_FILE')) {
   die('You can\'t access this file directly...');
}

$nuke_module_name = basename(dirname(dirname(__FILE__)));

get_lang($nuke_module_name);

if ( is_mod_admin($nuke_module_name) )
{ 

echo "<div align='center'>THIS WILL BE THE NEW NUKE_ADMIN CONTROL PANEL FOR THE Titanium_SandBox (Only Admins Seee This)</div>";
}
else
{ 
    DisplayError('<strong>'._ERROR.'</strong><br /><br />' . _NO_ADMIN_RIGHTS . $nuke_module_name);
}

?>