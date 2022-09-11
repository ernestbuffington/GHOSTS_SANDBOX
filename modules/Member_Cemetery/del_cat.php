<?php
if (!defined('MODULE_FILE')) die ("You can't access this file directly...");
global $prefix, $nuke_db, $cookie, $nuke_user;
$index = 1;
require_once("mainfile.php");
$nuke_module_name = basename(dirname(__FILE__));
get_lang($nuke_module_name);
$nuke_userinfo = getusrinfo( $nuke_user );
$nuke_userid = $nuke_userinfo["user_id"];
$catname=@htmlentities($catname);
if (!isset($nuke_userid) || $nuke_userid == "")
$nuke_userid=0;
# If no was pressed
if (isset($action) && $action==_NO)
Header("Location: modules.php?name=".$nuke_module_name);
# If yes was pressed
if (isset($action)  && $action==_YES && isset($catid) && $catid!=""):
	$delmarksquery = "delete from ".$prefix."_cemetery where category_id=$catid AND user_id=$nuke_userid";
	$delcatquery   = "delete from ".$prefix."_cemetery_cat where category_id=$catid AND user_id=$nuke_userid";
	$nuke_db->sql_query ($delmarksquery,$nuke_db);
	$nuke_db->sql_query ($delcatquery,$nuke_db);
	Header("Location: modules.php?name=".$nuke_module_name);
endif;
$pagetitle = " - " . _DELETECATEGORY;
include("header.php");
OpenTable();
echo "<center><span class=storytitle>"._DELETECATEGORY."</span></center><P>\n";
echo "<center><a href=modules.php?name=".$nuke_module_name.">". _CATEGORIES ."</a> | <a href=modules.php?name=".$nuke_module_name."&amp;file=edit_cat>"._NEWCATEGORY."</a> | <a href=modules.php?name=".$nuke_module_name."&amp;file=edit_mark>"._NEWBOOKMARK."</a></center>";
CloseTable();
OpenTable();
?>
<center><? echo _DELETECATEGORYCONFIRM ?> '<?=$catname?>'?<p> <strong><? echo _BOOKMARKSNUKE_DELETED ?></strong><p>
<p>
<form action=modules.php>
<input type=hidden name=name value="<?=$nuke_module_name?>">
<input type=hidden name=file value="del_cat">
<input type=hidden name=catid value="<?=$catid?>">
<input type=submit name=action value="<? echo _YES?>">&nbsp;&nbsp;&nbsp;<input type=submit name=action value="<? echo _NO ?>">
</form>
</center>
<?
CloseTable();
include("footer.php");
?> 
