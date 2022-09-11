<?php
if (!defined('MODULE_FILE')) die("You can't access this file directly...");
global $prefix, $nuke_db, $cookie, $nuke_user, $theme_name;
$index = 1;
require_once("mainfile.php");
$nuke_module_name = basename(dirname(__FILE__));
get_lang($nuke_module_name);
$pagetitle = "86it Developers Network - My "._MARKSTITLE;
include("header.php");
$nuke_userinfo = getusrinfo( $nuke_user );
$nuke_userid = $nuke_userinfo["user_id"];
$catname=@htmlentities($catname);
if(!isset($nuke_userid) || $nuke_userid== "")
$nuke_userid = 0;
# Sometimes we don't know the category name
if((!isset($catname) || $catname== "") && (isset($category) && $category != "")):
	$getname="select name from ".$prefix."_cemetery_cat where category_id='$category'";
	$getnameres=$nuke_db->sql_query ($getname,$nuke_db);
	$namerow=@$nuke_db->sql_fetchrow($getnameres,$nuke_db);
	$catname=$namerow['name'];
endif;
OpenTable();
# space at the top of the page
echo '<div align="center" style="padding-top:6px;">'; 
echo '</div>';
$headstone =  '<img class="tooltip-html copyright absmiddle" alt="" title="" width="40" src="modules/Member_Cemetery/images/icons8-cemetery-30.png" />';
$toes =  '<img class="tooltip-html copyright" alt="" title="" width="30" src="modules/Member_Cemetery/images/icons8-death-96.png" />';
echo "<center><span class=title><strong><h1>".$headstone." ".$catname." ".$headstone."</h1></strong></span></center><P>\n";
echo "<center>[ <a href=modules.php?name=".$nuke_module_name.">"._CATEGORIES."</a> | <a href=modules.php?name=".$nuke_module_name."&amp;file=edit_mark&amp;catid=$category>"._NEWBOOKMARK."</a> | <a href=modules.php?name=".$nuke_module_name."&amp;file=edit_cat>"._NEWCATEGORY."</a> ]</center>";
echo "<hr />";
$marks_query = "SELECT `id`,`name`,`url`,`description`,`mod_date`,`popup` FROM ".$prefix."_cemetery WHERE user_id=".$nuke_userid." AND category_id='".$category ."' ORDER BY `name`";
$marks_res = $nuke_db->sql_query ($marks_query,$nuke_db);
echo "<table width=98%>\n<tr class=boxtitle>
      <td width=37%>
	  <img src=\"themes/".$theme_name."/images/invisible_pixel.gif\" alt=\"\" width=\"25\" height=\"1\" />
	  <strong>"._OBIT_WEBSITE."</strong>
	  </td>
	  <td width=35%><div align=\"left\"><strong>Information</strong></div></td>
	  <td width=15%><div align=\"middle\"><strong>Modified</strong></div>
	  </td><td width=5%><strong>Edit</strong>
	  </td><td width=8%><strong>Delete</strong>
	  </td></tr>\n";
for ($i=0;$i<@$nuke_db->sql_numrows  ($marks_res,$nuke_db);$i++):
	$marks_row = @$nuke_db->sql_fetchrow($marks_res,$nuke_db);
    global $nuke_db;
    list($fixed_markurl) = $nuke_db->sql_ufetchrow("SELECT `url` FROM `".$prefix."_cemetery` WHERE `id`='".$marks_row['id']."'", SQL_NUM);
	if ($marks_row['popup']==1):
		echo "<tr class=\"boxlist\">
		<td><img src=\"themes/".$theme_name."/images/invisible_pixel.gif\" alt=\"\" width=\"15\" height=\"1\" />
		<a href=".$fixed_markurl." target=_tab>".$marks_row['name']."</a>
		</td>
		<td>".$marks_row['description']."</td>
		<td><div align=\"center\">".$marks_row['mod_date']."<div></td>
		<td>&nbsp;<a class=\"title\" href=modules.php?name=".$nuke_module_name."&amp;file=edit_mark&amp;catid=$category&amp;markname=".urlencode($marks_row['name'])."&amp;markcomment=".urlencode($marks_row['description'])."&amp;markid=".$marks_row['id']."&amp;popup=".$marks_row['popup']."><img src='modules/".$nuke_module_name."/images/pencil.gif' width=12 height=12 border=0></a>
		</td>
		<td>&nbsp;&nbsp;&nbsp;<a href=modules.php?name=".$nuke_module_name."&amp;file=del_mark&amp;catid=".$category."&amp;markname=".urlencode($marks_row['name'])."&amp;markid=".$marks_row['id']."&amp;catname=".$catname."><img src=modules/".$nuke_module_name."/admin/trash.png width=12 height=12 border=0></a>
		</td>
		</tr>\n";
	else:
		echo "<tr class=boxlist>
		<td><img src=\"themes/".$theme_name."/images/invisible_pixel.gif\" alt=\"\" width=\"15\" height=\"1\" />
		<a href=".$fixed_markurl.">".$marks_row['name']."</a>
		</td><td>".$marks_row['description']."</td>
		<td><div align=\"center\">".$marks_row['mod_date']."<div></td>
		<td>&nbsp;
		<a href=modules.php?name=".$nuke_module_name."&amp;file=edit_mark&amp;catid=$category&amp;markname=".urlencode($marks_row['name'])."&amp;markcomment=".urlencode($marks_row['description'])."&amp;markid=".$marks_row['id']."&amp;popup=".$marks_row['popup']."><img src='modules/".$nuke_module_name."/images/pencil.gif' width=12 height=12 border=0></a>
		</td>
		<td>&nbsp;&nbsp;&nbsp;
		<a href=modules.php?name=".$nuke_module_name."&amp;file=del_mark&amp;catid=".$category."&amp;markname=".urlencode($marks_row['name'])."&amp;markid=".$marks_row['id']."&amp;catname=".$catname."><img src=modules/".$nuke_module_name."/admin/trash.png width=12 height=12 border=0></a>
		</td>
		</tr>\n";
	endif;
endfor;
echo "</table>";
@$nuke_db->sql_freeresult($marks_res);
echo "<hr />";
echo "<center>[ <a href=modules.php?name=".$nuke_module_name.">"._CATEGORIES."</a> | <a href=modules.php?name=".$nuke_module_name."&amp;file=edit_mark&amp;catid=$category>"._NEWBOOKMARK."</a> | <a href=modules.php?name=".$nuke_module_name."&amp;file=edit_cat>"._NEWCATEGORY."</a> ]</center>";
CloseTable();
include("footer.php");
?> 
