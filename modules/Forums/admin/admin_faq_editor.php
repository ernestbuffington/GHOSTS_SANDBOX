<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
      Admin FAQ Editor 1.0.0 for phpBB 2.0.4, 2.0.5
        (c) Selven [Selven@zaion.com]
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/*****[CHANGES]**********************************************************
-=[Mod]=-
       Board Rules                             v2.0.0       06/26/2005
 ************************************************************************/

define('IN_PHPBB2', 1);

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
   $nuke_module['Faq_manager']['board_faq'] = "$file?file=faq";
   $nuke_module['Faq_manager']['bbcode_faq'] = "$file?file=bbcode";
/*****[BEGIN]******************************************
 [ Mod:     Board Rules                        v2.0.0 ]
 ******************************************************/
   $nuke_module['Faq_manager']['site_rules'] = "$file?file=rules";
/*****[END]********************************************
 [ Mod:     Board Rules                        v2.0.0 ]
 ******************************************************/
    if(file_exists($phpbb2_root_path . 'attach_mod/attachment_mod.'.$phpEx))
    {
       $nuke_module['Faq_manager']['attachment_faq'] = "$file?file=faq_attach";
    }
    if(file_exists($phpbb2_root_path . 'mods/prillian/im_main.'.$phpEx))
    {
       $nuke_module['Faq_manager']['prillian_faq'] = "$file?file=prillian_faq";
       $nuke_module['Faq_manager']['bid_faq'] = "$file?file=bid_faq";
    }
    return;
}
/* this function takes the FAQ array generated as a result
 * of include'ing the lang_faq.php file and turns it into
 * a pair of arrays, $blocks and $quests.
 *    $blocks - just contains numerically indexed block titles
 *    $quests - is in the following format:
 *      $quests[$block_number][$question_number][Q] - is the question
 *      $quests[$block_number][$question_number][A] - is the answer
 */
function faq_to_array($faq)
{
    $blocks = array();
    $quests = array();

    $block_no = -1;
    $quest_no = 0;

    for($i = 0; $i < count($faq); $i++)
    {
        if($faq[$i][0] == '--')
        {
            $block_no++;
            $blocks[$block_no] = $faq[$i][1];
            $quests[$block_no] = array();
            $quest_no = 0;
        }
        else
        {
            $quests[$block_no][$quest_no][Q] = $faq[$i][0];
            $quests[$block_no][$quest_no][A] = $faq[$i][1];
            $quest_no++;
        }
    }

    return array($blocks, $quests);
} /* END function faq_to_array */

/* this function takes the array generated by faq_to_array and changes
 * it back into lines suitable for dumping to a lang_faq.php file. It
 * returns a numerically-indexed array of said lines.
 */
function array_to_faq($blocks, $quests)
{
    $lines = array();

    for($i = 0; $i < count($blocks); $i++)
    {
        $lines[] = '$faq[] = array("--", "'.str_replace('"', '\"', $blocks[$i]).'");'."\n";

        for($j = 0; $j < count($quests[$i]); $j++)
        {
            if( !empty($quests[$i][$j][Q]) && !empty($quests[$i][$j][A]) )
            {
                $lines[] = '$faq[] = array("'.str_replace('"', '\"', $quests[$i][$j][Q]).'", "'.str_replace('"', '\"', $quests[$i][$j][A]).'");'."\n";
            }
        }

        $lines[] = "\n";
    }

    return $lines;
} /* END function array_to_faq */

/* okay here we go! */

define('IN_PHPBB2', 1);
define('Q', 0);
define('A', 1);

/* this is the header which will be dumped to the FAQ
 * file each time we dump the page. Split up the < and
 * the ?php to avoid problems parsing this file!!
 */

$faq_header = '<'."?php

/***************************************************************************
      This file was automatically generated by Admin FAQ Editor
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
// 
// To add an entry to your FAQ simply add a line to this file in this format:
// ".'$'."faq[] = array(\"question\", \"answer\");
// If you want to separate a section enter ".'$'."faq[] = array(\"--\",\"Block heading goes here if wanted\");
// Links will be created automatically
//
// DO NOT forget the ; at the end of the line.
// Do NOT put double quotes (\") in your FAQ entries, if you absolutely must then escape them ie. \\\"something\\\"
//
// The FAQ items will appear on the FAQ page in the same order they are listed in this file
//\n\n";

$faq_footer = "\n\n?" . '>';

$phpbb2_root_path = "./../";
include($phpbb2_root_path . 'extension.inc');
include('./pagestart.' . $phpEx);
include("../../../includes/functions_selects.php");
include($phpbb2_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_faq_editor.' . $phpEx);

// initially include the current FAQ or BBCode guide, depending on the file= in the query_string
$file = isset($HTTP_GET_VARS['file']) ? htmlspecialchars($HTTP_GET_VARS['file']) : 'faq';

if( !isset($HTTP_GET_VARS['language']) && !isset($HTTP_POST_VARS['language']) )
{
    $template_nuke->set_filenames(array(
        "body" => "admin/faq_select_lang_body.tpl")
    );

    $template_nuke->assign_vars(array(
        'L_LANGUAGE' => $lang['faq_select_language'],
        'LANGUAGE_SELECT' => language_select($board_config['default_lang'], 'language', $phpbb2_root_path.'language'),
        'S_ACTION' => append_nuke_sid("admin_faq_editor.$phpEx?file=$file"),
        'L_SUBMIT' => $lang['faq_retrieve'],
        'L_TITLE' => $lang['faq_editor'],
        'L_EXPLAIN' => $lang['faq_editor_explain']
    ));

    $template_nuke->pparse("body");
    include('./nuke_page_footer_admin.'.$phpEx);
    exit;
}

// get the language we want to edit
$language_nuke = isset($HTTP_GET_VARS['language']) ? $HTTP_GET_VARS['language'] : $HTTP_POST_VARS['language'];

// the FAQ which will generate our $faq array
include($phpbb2_root_path . 'language/lang_' . $language_nuke . '/lang_' . $file . '.' . $phpEx);

// change into our array
list($blocks, $quests) = faq_to_array($faq);

// if we have a mode set this means we have to do something
if(isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']))
{
    // fetch the mode and two commonly past variables
    $mode = isset($HTTP_GET_VARS['mode']) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
    $block_no = intval(isset($HTTP_GET_VARS['block']) ? $HTTP_GET_VARS['block'] : (isset($HTTP_POST_VARS['block']) ? $HTTP_POST_VARS['block'] : 0 ));
    $quest_no = intval(isset($HTTP_GET_VARS['quest']) ? $HTTP_GET_VARS['quest'] : (isset($HTTP_POST_VARS['quest']) ? $HTTP_POST_VARS['quest'] : 0 ));

    switch($mode)
    {
        // create a new block as a result of typing the block name and pressing submit
        case 'block_new':
            $blocks[] = isset($HTTP_GET_VARS['block_title']) ? $HTTP_GET_VARS['block_title'] : $HTTP_POST_VARS['block_title'];
            $quests[] = array();
            break;

        // result of pressing the delete link next to a block
        case 'block_del':
            $template_nuke->set_filenames(array(
                "confirm" => "confirm_body.tpl")
            );

            $s_hidden_fields = '<input type="hidden" name="mode" value="block_del_confirm" />';
            $s_hidden_fields .= '<input type="hidden" name="block" value="'.$block_no.'" />';

            $template_nuke->assign_vars(array(
                "MESSAGE_TITLE" => $lang['Confirm'],
                "MESSAGE_TEXT" => $lang['faq_block_delete'],

                "L_YES" => $lang['Yes'],
                "L_NO" => $lang['No'],

                "S_CONFIRM_ACTION" => append_nuke_sid("admin_faq_editor.$phpEx?file=$file&amp;language=$language_nuke"),
                "S_HIDDEN_FIELDS" => $s_hidden_fields
            ));

            $template_nuke->pparse("confirm");
            include('./nuke_page_footer_admin.'.$phpEx);

            exit;

        // result of pressing YES on the block delete confirmation 
        case 'block_del_confirm':
            if(isset($HTTP_GET_VARS['confirm']) || isset($HTTP_POST_VARS['confirm']))
            {
                for($i = $block_no; $i < count($blocks); $i++)
                {
                    $blocks[$i] = $blocks[$i+1];
                    $quests[$i] = $quests[$i+1];
                }

                $last_id = count($blocks) - 1;

                unset($blocks[$last_id]);
                unset($quests[$last_id]);
            }

            break;

        // generate the edit screen as a result of pressing the edit link
        case 'block_edit':
            $template_nuke->set_filenames(array(
                "body" => "admin/faq_block_body.tpl")
            );

            $template_nuke->assign_vars(array(
                'L_TITLE' => $lang['faq_block_rename'],
                'L_EXPLAIN' => $lang['faq_block_rename_explain'],
                'L_SUBMIT' => $lang['Submit'],
                'L_BLOCK_NAME' => $lang['faq_block_name'],

                'BLOCK_TITLE' => $blocks[$block_no],

                'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="block_do_edit"><input type="hidden" name="block" value="'.$block_no.'">',
                'S_ACTION' => append_nuke_sid("admin_faq_editor.$phpEx?file=$file&amp;language=$language_nuke")
            ));

            $template_nuke->pparse("body");
            include('./nuke_page_footer_admin.'.$phpEx);

            exit;

        // actually do the edit after pressing submit on the block edit screen
        case 'block_do_edit':
            $blocks[$block_no] = isset($HTTP_GET_VARS['block_title']) ? $HTTP_GET_VARS['block_title'] : $HTTP_POST_VARS['block_title'];
            break;

        // re-arrange the blocks after someone presses an UP link
        case 'block_up':
            if($block_no != 0)
            {
                $block_temp = $blocks[$block_no - 1];
                $quest_temp = $quests[$block_no - 1];

                $blocks[$block_no - 1] = $blocks[$block_no];
                $quests[$block_no - 1] = $quests[$block_no];

                $blocks[$block_no] = $block_temp;
                $quests[$block_no] = $quest_temp;

                unset($block_temp);
                unset($quest_temp);
            }

            break;

        // re-arrange the blocks after someone presses an DOWN link
        case 'block_dn':
            if($block_no != (count($blocks) - 1))
            {
                $block_temp = $blocks[$block_no + 1];
                $quest_temp = $quests[$block_no + 1];

                $blocks[$block_no + 1] = $blocks[$block_no];
                $quests[$block_no + 1] = $quests[$block_no];

                $blocks[$block_no] = $block_temp;
                $quests[$block_no] = $quest_temp;

                unset($block_temp);
                unset($quest_temp);
            }

            break;

        // create a new question as a result of typing a question on the main page
        case 'quest_new':
            $template_nuke->set_filenames(array(
                "body" => "admin/faq_quest_body.tpl")
            );

            $s_block_list = '';
            $s_selected_block = intval(isset($HTTP_GET_VARS['block']) ? $HTTP_GET_VARS['block'] : $HTTP_POST_VARS['block']);

            for($i = 0; $i < count($blocks); $i++)
            {
                $is_selected = ($s_selected_block == $i) ? ' selected' : '';
                $s_block_list .= '<option value="'.$i.'"'.$is_selected.'>' . $blocks[$i] . '</option>';
            }

            $template_nuke->assign_vars(array(
                'L_TITLE' => $lang['faq_quest_create'],
                'L_EXPLAIN' => $lang['faq_quest_create_explain'],
                'L_BLOCK' => $lang['faq_block'],
                'L_QUESTION' => $lang['faq_quest'],
                'L_ANSWER' => $lang['faq_answer'],
                'L_SUBMIT' => $lang['Submit'],

                'QUESTION' => htmlspecialchars(stripslashes(isset($HTTP_GET_VARS['quest_title']) ? $HTTP_GET_VARS['quest_title'] : $HTTP_POST_VARS['quest_title'])),
                'ANSWER' => '',

                'S_BLOCK_LIST' => $s_block_list,
                'S_ACTION' => append_nuke_sid("admin_faq_editor.$phpEx?file=$file&amp;language=$language_nuke"),
                'S_HIDDEN_FIELDS' => '<input name="mode" type="hidden" value="quest_create">'
            ));

            $template_nuke->pparse("body");
            include('./nuke_page_footer_admin.'.$phpEx);

            exit;

        // actually create the question when the user submits the new question form
        case 'quest_create':
            $question = isset($HTTP_GET_VARS['quest_title']) ? $HTTP_GET_VARS['quest_title'] : $HTTP_POST_VARS['quest_title'];
            $answer = str_replace("\n", "<br />", isset($HTTP_GET_VARS['answer']) ? $HTTP_GET_VARS['answer'] : $HTTP_POST_VARS['answer']);

            $new_id = count($quests[$block_no]);

            $quests[$block_no][$new_id][Q] = stripslashes($question);
            $quests[$block_no][$new_id][A] = stripslashes($answer);

            break;

        // present the question edit screen
        case 'quest_edit':
            $template_nuke->set_filenames(array(
                "body" => "admin/faq_quest_body.tpl")
            );

            $s_block_list = '';
            $s_selected_block = intval(isset($HTTP_GET_VARS['block']) ? $HTTP_GET_VARS['block'] : $HTTP_POST_VARS['block']);

            for($i = 0; $i < count($blocks); $i++)
            {
                $is_selected = ($s_selected_block == $i) ? ' selected' : '';
                $s_block_list .= '<option value="'.$i.'"'.$is_selected.'>' . $blocks[$i] . '</option>';
            }

            $template_nuke->assign_vars(array(
                'L_TITLE' => $lang['faq_quest_edit'],
                'L_EXPLAIN' => $lang['faq_quest_edit_explain'],
                'L_BLOCK' => $lang['faq_block'],
                'L_QUESTION' => $lang['faq_quest'],
                'L_ANSWER' => $lang['faq_answer'],
                'L_SUBMIT' => $lang['Submit'],

                'QUESTION' => htmlspecialchars($quests[$block_no][$quest_no][Q]),
                'ANSWER' => htmlspecialchars(str_replace("<br />", "\n", $quests[$block_no][$quest_no][A])),

                'S_BLOCK_LIST' => $s_block_list,
                'S_ACTION' => append_nuke_sid("admin_faq_editor.$phpEx?file=$file&amp;language=$language_nuke"),
                'S_HIDDEN_FIELDS' => '<input name="quest" type="hidden" value="'.$quest_no.'"><input name="old_block" type="hidden" value="'.$block_no.'"><input name="mode" type="hidden" value="quest_do_edit">'
            ));

            $template_nuke->pparse("body");
            include('./nuke_page_footer_admin.'.$phpEx);

            exit;

        case 'quest_do_edit':
            $old_block_no = intval(isset($HTTP_GET_VARS['old_block']) ? $HTTP_GET_VARS['old_block'] : $HTTP_POST_VARS['old_block']);

            $question = stripslashes(isset($HTTP_GET_VARS['quest_title']) ? $HTTP_GET_VARS['quest_title'] : $HTTP_POST_VARS['quest_title']);
            $answer = str_replace("\n", "<br />", stripslashes(isset($HTTP_GET_VARS['answer']) ? $HTTP_GET_VARS['answer'] : $HTTP_POST_VARS['answer']));

            if($block_no == $old_block_no)
            {
                // standard edit where we don't change blocks

                $quests[$block_no][$quest_no][Q] = $question;
                $quests[$block_no][$quest_no][A] = $answer;
            }
            else
            {
                // edit where we move blocks

                for($i = $quest_no; $i < count($quests[$old_block_no]); $i++)
                {
                    $quests[$old_block_no][$i] = $quests[$old_block_no][$i+1];
                }

                unset($quests[$old_block_no][count($quests[$old_block_no]) - 1]);

                $new_id = count($quests[$block_no]);

                $quests[$block_no][$new_id][Q] = $question;
                $quests[$block_no][$new_id][A] = $answer;
            }

            break;

        // delete a question: confirm box
        case 'quest_del':
            $template_nuke->set_filenames(array(
                "confirm" => "confirm_body.tpl")
            );

            $s_hidden_fields = '<input type="hidden" name="mode" value="quest_del_confirm" />';
            $s_hidden_fields .= '<input type="hidden" name="block" value="'.$block_no.'" />';
            $s_hidden_fields .= '<input type="hidden" name="quest" value="'.$quest_no.'" />';

            $template_nuke->assign_vars(array(
                "MESSAGE_TITLE" => $lang['Confirm'],
                "MESSAGE_TEXT" => $lang['faq_quest_delete'],

                "L_YES" => $lang['Yes'],
                "L_NO" => $lang['No'],

                "S_CONFIRM_ACTION" => append_nuke_sid("admin_faq_editor.$phpEx?file=$file&amp;language=$language_nuke"),
                "S_HIDDEN_FIELDS" => $s_hidden_fields
            ));

            $template_nuke->pparse("confirm");
            include('./nuke_page_footer_admin.'.$phpEx);

            exit;

        // delete is confirmed or rejected
        case 'quest_del_confirm':
            if(isset($HTTP_GET_VARS['confirm']) || isset($HTTP_POST_VARS['confirm']))
            {
                for($i = $quest_no; $i < count($quests[$block_no]); $i++)
                {
                    $quests[$block_no][$i] = $quests[$block_no][$i+1];
                }

                unset($quests[$block_no][count($quests[$block_no]) - 1]);
            }

            break;

        // move a question upwards
        case 'quest_up':
            if($quest_no != 0)
            {
                $temp = $quests[$block_no][$quest_no - 1];
                $quests[$block_no][$quest_no - 1] = $quests[$block_no][$quest_no];
                $quests[$block_no][$quest_no] = $temp;
                unset($temp);
            }

            break;

        // move a question downwards
        case 'quest_dn':
            if($quest_no != (count($quests[$block_no]) - 1))
            {
                $temp = $quests[$block_no][$quest_no + 1];
                $quests[$block_no][$quest_no + 1] = $quests[$block_no][$quest_no];
                $quests[$block_no][$quest_no] = $temp;
                unset($temp);
            }

            break;
    }

    // write these changes back to the FAQ file

    $fp = fopen($phpbb2_root_path . 'language/lang_' . $language_nuke . '/lang_' . $file . '.' . $phpEx, 'w');

    if($fp)
    {
            fwrite($fp, $faq_header);

            $lines = array_to_faq($blocks, $quests);

            for($i = 0; $i < count($lines); $i++)
            {
                fwrite($fp, $lines[$i]);
            }

            fwrite($fp, $faq_footer);
    }
    else
    {
        message_die(NUKE_GENERAL_ERROR, $lang['faq_write_file_explain'], $lang['faq_write_file'], __LINE__, __FILE__);
    }
}

// if we've got this far without exiting we just dump the default page

$template_nuke->set_filenames(array(
    "body" => "admin/faq_editor_body.tpl")
);

$template_nuke->assign_vars(array(
    'L_TITLE' => $lang['faq_editor'],
    'L_EXPLAIN' => $lang['faq_editor_explain'],

    'S_ACTION' => append_nuke_sid("admin_faq_editor.$phpEx?file=$file&amp;language=$language_nuke"),

    'L_ADD_BLOCK' => $lang['faq_block_add'],
    'L_ADD_QUESTION' => $lang['faq_quest_add'],

    'L_EDIT' => $lang['Edit'], 
    'L_DELETE' => $lang['Delete'], 
    'L_MOVE_UP' => $lang['Move_up'], 
    'L_MOVE_DOWN' => $lang['Move_down'], 

    'L_NO_QUESTIONS' => $lang['faq_no_quests'],
    'L_NO_BLOCKS' => $lang['faq_no_blocks']
));

$k = 0;

if(count($blocks) > 0)
{
    for($i = 0; $i < count($blocks); $i++)
    {
        $template_nuke->assign_block_vars("blockrow", array( 
            'BLOCK_TITLE' => $blocks[$i],
            'BLOCK_NUMBER' => "$i",
            'BLOCK_ANCHOR' => $anchor_code,

            'U_BLOCK_EDIT' => append_nuke_sid("admin_faq_editor.$phpEx?mode=block_edit&amp;block=$i&amp;file=$file&amp;language=$language_nuke"),
            'U_BLOCK_MOVE_UP' => append_nuke_sid("admin_faq_editor.$phpEx?mode=block_up&amp;block=$i&amp;file=$file&amp;language=$language_nuke"),
            'U_BLOCK_MOVE_DOWN' => append_nuke_sid("admin_faq_editor.$phpEx?mode=block_dn&amp;block=$i&amp;file=$file&amp;language=$language_nuke"),
            'U_BLOCK_DELETE' => append_nuke_sid("admin_faq_editor.$phpEx?mode=block_del&amp;block=$i&amp;file=$file&amp;language=$language_nuke")
        ));

        if(count($quests[$i]) > 0)
        {
            for($j = 0; $j < count($quests[$i]); $j++)
            {
                $template_nuke->assign_block_vars("blockrow.questrow", array( 
                    'QUEST_TITLE' => $quests[$i][$j][Q],
                    'U_QUEST' => ("../../../modules.php?name=Forums&amp;file=faq&amp;mode=$file")."#$k",

                    'U_QUEST_EDIT' => append_nuke_sid("admin_faq_editor.$phpEx?mode=quest_edit&amp;block=$i&amp;quest=$j&amp;file=$file&amp;language=$language_nuke"),
                    'U_QUEST_MOVE_UP' => append_nuke_sid("admin_faq_editor.$phpEx?mode=quest_up&amp;block=$i&amp;quest=$j&amp;file=$file&amp;language=$language_nuke"),
                    'U_QUEST_MOVE_DOWN' => append_nuke_sid("admin_faq_editor.$phpEx?mode=quest_dn&amp;block=$i&amp;quest=$j&amp;file=$file&amp;language=$language_nuke"),
                    'U_QUEST_DELETE' => append_nuke_sid("admin_faq_editor.$phpEx?mode=quest_del&amp;block=$i&amp;quest=$j&amp;file=$file&amp;language=$language_nuke")
                ));

                $k++;
            }
        }
        else
        {
            $template_nuke->assign_block_vars("blockrow.no_questions", array());
        }
    }
}
else
{
    $template_nuke->assign_block_vars("no_blocks", array());
}

$template_nuke->pparse("body");

include('./nuke_page_footer_admin.'.$phpEx);

?>