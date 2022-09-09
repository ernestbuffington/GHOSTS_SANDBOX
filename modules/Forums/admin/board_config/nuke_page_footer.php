<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/************************************************************************
   Nuke-Evolution: DHTML Forum Config Admin
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : nuke_page_footer.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.0
   Date          : 09.10.2005 (mm.dd.yyyy)

   Description   : Enhanced General Admin Configuration with DHTML menu.
************************************************************************/

if (!defined('BOARD_CONFIG')) {
    die('Access Denied');
}

$template_nuke->set_filenames(array(
    "footer" => "admin/board_config/nuke_page_footer.tpl")
);

$template_nuke->assign_vars(array(
    "L_SUBMIT" => $lang['Submit'],
    "L_RESET" => $lang['Reset'],
));

$template_nuke->pparse("footer");

?>