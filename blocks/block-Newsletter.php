<?php

if(!defined('NUKE_EVO')) exit;

global $nuke_db, $nuke_user_prefix, $nuke_userinfo;

if (is_user()) {
    $newsletter = $nuke_userinfo['newsletter'];
    $nuke_user_id = $nuke_userinfo['user_id'];
    if ($newsletter) {
        $message = _NEWSLETTERBLOCKSUBSCRIBED;
        $action = '<form action="'.$_SERVER['REQUEST_URI'].'" method="post"><input type="submit" name="nb_unsubscribe" value="'._NEWSLETTERBLOCKUNSUBSCRIBE.'" /></form>';
        if (isset($_POST['nb_unsubscribe'])) {
            $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET newsletter='0' WHERE user_id='$nuke_user_id'");
            nuke_redirect($_SERVER['REQUEST_URI']);
        }
    } else {
        $message = _NEWSLETTERBLOCKNOTSUBSCRIBED;
        $action = '<form action="'.$_SERVER['REQUEST_URI'].'" method="post"><input type="submit" name="nb_subscribe" value="'._NEWSLETTERBLOCKSUBSCRIBE.'" /></form>';
        if (isset($_POST['nb_subscribe'])) {
            $nuke_db->sql_query("UPDATE ".$nuke_user_prefix."_users SET newsletter='1' WHERE user_id='$nuke_user_id'");
            nuke_redirect($_SERVER['REQUEST_URI']);
        }
    }
} else {
    $message = _NEWSLETTERBLOCKREGISTER;
    $action = '<a href="modules.php?name=Your_Account&amp;op=new_user" title="'._NEWSLETTERBLOCKREGISTERNOW.'">'._NEWSLETTERBLOCKREGISTERNOW.'</a>';
}

$content = '<div align="center"><img src="images/admin/newsletter.png" alt="'._NEWSLETTER.'" title="'._NEWSLETTER.'" /><br /><br />'.$message.'<br /><br />'.$action.'</div>';

?>