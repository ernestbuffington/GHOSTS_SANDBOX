<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/************************************************************************
   Nuke-Evolution: Enhanced Copyright
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : showcp.php
   Author        : Quake (www.Nuke-Evolution.com)
   Version       : 1.0.0
   Date          : 11.21.2005 (mm.dd.yyyy)

   Notes         : Enhanced Copyright.
************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

function show_copyright($nuke_author_name = "", $nuke_author_user_email = "", $nuke_author_homepage = "", $license = "", $download_location = "", $nuke_module_version = "", $nuke_module_description = "") {
    if (empty($nuke_author_name)) { $nuke_author_name = "N/A"; }
    if (empty($nuke_author_user_email)) { $nuke_author_user_email = "N/A"; }
    if (!empty($nuke_author_homepage)) { $homepage = "<a href='$nuke_author_homepage' target='_blank'>Author's Homepage</a>"; } else { $homepage = "No Website Available"; }
    if (empty($license)) { $license = "N/A"; }
    if (!empty($download_location)) { $download = "<a href='$download_location' target='_blank'>Module's Download</a>"; } else { $download = "No Download Available"; }
    if (empty($nuke_module_version)) { $nuke_module_version = "N/A"; }
    if (empty($nuke_module_description)) { $nuke_module_description = "N/A"; }
    $nuke_module_name = basename(dirname($_SERVER['PHP_SELF']));
    $nuke_module_name = str_replace("_", " ", $nuke_module_name);
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n"
        ."<html>\n"
        ."<head>\n"
        ."<title>$nuke_module_name: Copyright Information</title>\n"
        ."<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1' />\n"
        ."<style type='text/css'>\n"
        ."<!--";
    echo '
body {
    font-size: 10px;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
    color: black;
    background: #D3D3D3;
}
a {
    font-size: 10px;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
    color: black;
}        
';
    echo "//-->\n"
        ."</style>\n"
        ."</head>\n"
        ."<body>\n"
        ."<center><strong>Module Copyright &copy; Information</strong><br />"
        ."$nuke_module_name module for <a href='http://www.nuke-evolution.com' target='_blank'>Nuke-Evolution</a><br /><br /></center>\n"
        ."<img src='../../images/arrow.gif' border='0' alt='' />&nbsp;<strong>Module's Name:</strong> $nuke_module_name<br />\n"
        ."<img src='../../images/arrow.gif' border='0' alt='' />&nbsp;<strong>Module's Version:</strong> $nuke_module_version<br />\n"
        ."<img src='../../images/arrow.gif' border='0' alt='' />&nbsp;<strong>Module's Description:</strong> $nuke_module_description<br />\n"
        ."<img src='../../images/arrow.gif' border='0' alt='' />&nbsp;<strong>License:</strong> $license<br />\n"
        ."<img src='../../images/arrow.gif' border='0' alt='' />&nbsp;<strong>Author's Name:</strong> $nuke_author_name<br />\n"
        ."<img src='../../images/arrow.gif' border='0' alt='' />&nbsp;<strong>Author's Email:</strong> $nuke_author_user_email<br /><br />\n"
        ."<center>[ $homepage | $download | <a href='#' onclick='javascript:self.close()'>Close</a> ]</center>\n"
        ."</body>\n"
        ."</html>";
}

?>