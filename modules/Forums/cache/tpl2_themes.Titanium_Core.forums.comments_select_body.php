<?php

// eXtreme Styles mod cache. Generated on Fri, 09 Sep 2022 11:20:47 +0000 (time=1662722447)

?><table border="0" cellpadding="3" cellspacing="4" width="100%">
<td align="left"><span class="nav"><a href="<?php echo isset($this->vars['U_INDEX']) ? $this->vars['U_INDEX'] : $this->lang('U_INDEX'); ?>" class="nav"><?php echo isset($this->vars['L_INDEX']) ? $this->vars['L_INDEX'] : $this->lang('L_INDEX'); ?></a></span>
<span class="nav">&nbsp;->&nbsp;<?php echo isset($this->vars['NAV_DESC']) ? $this->vars['NAV_DESC'] : $this->lang('NAV_DESC'); ?></span>
<span class="nav">&nbsp;->&nbsp;Arcade Comments</span>
</td>
</table>

<table width='100%' cellpadding="5" cellspacing="1" border="0" class="forumline" align="center">
<?php

$comment_select_count = ( isset($this->_tpldata['comment_select.']) ) ?  sizeof($this->_tpldata['comment_select.']) : 0;
for ($comment_select_i = 0; $comment_select_i < $comment_select_count; $comment_select_i++)
{
 $comment_select_item = &$this->_tpldata['comment_select.'][$comment_select_i];
 $comment_select_item['S_ROW_COUNT'] = $comment_select_i;
 $comment_select_item['S_NUM_ROWS'] = $comment_select_count;

?>
  <tr>
          <th colspan='2' class="row4"><span class="cattitle">Select a Game</span></th>
  </tr>

<form method='post' name='submit' action='<?php echo isset($comment_select_item['S_ACTION']) ? $comment_select_item['S_ACTION'] : ''; ?>'>
<tr><td colspan='2' align='center' class='row2'>You currently hold <?php echo isset($comment_select_item['HIGHSCORE_COUNT']) ? $comment_select_item['HIGHSCORE_COUNT'] : ''; ?> highscores.</td></tr>
<tr><td width="50%" align='center' class='row2'>Select a game to enter or edit a comment:</td>
<td width="50%" align='center' class='row2'><?php echo isset($comment_select_item['HIGHSCORE_SELECT']) ? $comment_select_item['HIGHSCORE_SELECT'] : ''; ?></td></tr>
<tr><td colspan='2' align='center' class='row2'><input type='submit' name='submit' value='Add/Edit' class='mainoption' /></td></tr>
</form>
<?php

} // END comment_select

if(isset($comment_select_item)) { unset($comment_select_item); } 

?>
<?php

$comment_settings_count = ( isset($this->_tpldata['comment_settings.']) ) ?  sizeof($this->_tpldata['comment_settings.']) : 0;
for ($comment_settings_i = 0; $comment_settings_i < $comment_settings_count; $comment_settings_i++)
{
 $comment_settings_item = &$this->_tpldata['comment_settings.'][$comment_settings_i];
 $comment_settings_item['S_ROW_COUNT'] = $comment_settings_i;
 $comment_settings_item['S_NUM_ROWS'] = $comment_settings_count;

?>
  <tr>
          <th colspan='2' class="row4"><span class="cattitle">User Settings</span></th>
  </tr>

<form method='post' name='submit' action='<?php echo isset($comment_settings_item['S_ACTION_PM']) ? $comment_settings_item['S_ACTION_PM'] : ''; ?>'>
<tr>
<td class="row1">Enable Arcade PM<br />
<span class="gensmall">If enabled, you will receive a private message when you lose a highscore.</span>
</td>
<td class="row2">
<input type="radio" name="user_allow_arcadepm" value="1" <?php echo isset($comment_settings_item['USER_ALLOW_ARCADEPM_YES']) ? $comment_settings_item['USER_ALLOW_ARCADEPM_YES'] : ''; ?> />
<?php echo isset($comment_settings_item['L_YES']) ? $comment_settings_item['L_YES'] : ''; ?>
<input type="radio" name="user_allow_arcadepm" value="0" <?php echo isset($comment_settings_item['USER_ALLOW_ARCADEPM_NO']) ? $comment_settings_item['USER_ALLOW_ARCADEPM_NO'] : ''; ?> />
<?php echo isset($comment_settings_item['L_NO']) ? $comment_settings_item['L_NO'] : ''; ?>
</td>
</tr>
<tr><td colspan='2' align='center' class='row2'><input type='submit' name='submit' value='Submit' class='mainoption' /></td></tr>
</form>
<?php

} // END comment_settings

if(isset($comment_settings_item)) { unset($comment_settings_item); } 

?>
</table>
<br />