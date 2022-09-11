<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                             lang_functions.php
 *                            -------------------
 *   begin                : Sat, Jan 04, 2003
 *   copyright            : (C) 2003 Meik Sievertsen
 *   email                : acyd.burn@gmx.de
 *
 *   $Id: lang_functions.php,v 1.5 2003/03/16 18:38:31 acydburn Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if (!defined('IN_PHPBB2'))
{
    die('Hacking attempt');
}

//
// Delete Language Variables within a given Block
// $contents == Language File; $short_name == Module Name
// This function is mainly for deleting blocks out of already parsed language files
//
function delete_language_block($contents, $short_name)
{
    $new_content = '';

    $new_content = preg_replace("/\n\/\/([ ]+)\[" . preg_quote($short_name) . "\](.*?)\[\/" . preg_quote($short_name) . "\]\n/s", "", $contents);
    return ($new_content);    
}

// This is for parsing the imported Language Packs... module rows are set as [module:module_name]
function get_modules_from_lang_block($lang_block)
{
    $ret_array = array();

    $in_block = FALSE;
    $block_name = '';
    $content = explode("\n", $lang_block);
    @reset($content);

    while (list($key, $data) = @each($content))
    {
        if (!$in_block)
        {
            if (preg_match("/(.*?)\[module:(.*?)\]/", $data))
            {
                $in_block = TRUE;
                $block_name = preg_replace("/(.*?)\[module:(.*?)\]/", "\\2", $data);
                $block_name = trim($block_name);
                $ret_array[$block_name] = '';
            }
        }
        else
        {
            if (preg_match("/\[\/module:" . preg_quote($block_name) . "\]/", $data))
            {
                $in_block = FALSE;
            }
            else
            {
                if ($ret_array[$block_name] != '')
                {
                    $ret_array[$block_name] .= "\n";
                }
                $ret_array[$block_name] .= trim($data);
            }
        }
    }

    return $ret_array;
}

// Get provided Languages from an Module
function get_module_languages($short_name)
{
    global $phpbb2_root_path;

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';
    $language_nukes = array();

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    if( $dir = @opendir($language_nuke_directory) )
    {
        while( $sub_dir = @readdir($dir) )
        {
            if( !is_file($language_nuke_directory . '/' . $sub_dir) && !is_link($language_nuke_directory . '/' . $sub_dir) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
            {
                if (strstr($sub_dir, 'lang_'))
                {
                    $language_nukes[] = trim($sub_dir);
                }
            }
        }
        
        closedir($dir);
    }

    $found_languages = array();

    // Ok, go through all Languages and generate the Language Array
    for ($i = 0; $i < count($language_nukes); $i++)
    {
        $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nukes[$i] . '/lang_modules.php';
        $file_content = implode('', file($language_nuke_file));
        if (trim($file_content) != '')
        {
            // Get Content and find out if this Module is there
            if ((preg_match("/.*?\/\/[ ]\[" . preg_quote($short_name) . "\]./si", $file_content)) && (preg_match("/.*?\/\/[ ]\[\/" . preg_quote($short_name) . "\]./si", $file_content)) )
            {
                $found_languages[] = str_replace('lang_', '', $language_nukes[$i]);
            }
        }
    }

    return ($found_languages);
}

// Get Languages available on this system
function get_all_installed_languages()
{
    global $phpbb2_root_path;

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';
    $language_nukes = array();

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    if( $dir = @opendir($language_nuke_directory) )
    {
        while( $sub_dir = @readdir($dir) )
        {
            if( !is_file($language_nuke_directory . '/' . $sub_dir) && !is_link($language_nuke_directory . '/' . $sub_dir) && $sub_dir != "." && $sub_dir != ".." && $sub_dir != "CVS" )
            {
                if (strstr($sub_dir, 'lang_'))
                {
                    $language_nukes[] = trim($sub_dir);
                }
            }
        }
        
        closedir($dir);
    }

    $found_languages = array();

    // Ok, go through all Languages and generate the Language Array
    for ($i = 0; $i < count($language_nukes); $i++)
    {
        $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nukes[$i] . '/lang_modules.php';
        if (file_exists($language_nuke_file))
        {
            $found_languages[] = $language_nukes[$i]; 
        }
    }

    return ($found_languages);
}

// has module content within this language ?
function module_is_in_lang($short_name, $language_nuke)
{
    global $phpbb2_root_path;

    $found = FALSE;
    
    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';
    $file_content = implode('', file($language_nuke_file));
    if (trim($file_content) != '')
    {
        // Get Content and find out if this Module is there
        if ((preg_match("/.*?\/\/[ ]\[" . preg_quote(trim($short_name)) . "\]./si", $file_content)) && (preg_match("/.*?\/\/[ ]\[\/" . preg_quote(trim($short_name)) . "\]./si", $file_content)) )
        {
            $found = TRUE;
        }
    }

    return ($found);
}

// Get Language Entries from given Module and Language
function get_lang_entries($short_name, $language_nuke)
{
    global $phpbb2_root_path;

    $lang_entries = array();
    
    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';
    include($language_nuke_file);
    $keys = array();
    eval('$current_lang = $' . trim($short_name) . ';');
        
    if (is_array($current_lang))
    {
        $i = 0;
        foreach ($current_lang as $key => $value)
        {
            $lang_entries[$i]['key'] = $key;
            $lang_entries[$i]['value'] = $value;
            $i++;
        }
    }

    return ($lang_entries);
}

// Set specific language key, $value is the new key value
function set_lang_entry($language_nuke, $nuke_module_id, $key, $value)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path;

    $language_nuke = trim($language_nuke);
    $nuke_module_id = intval($nuke_module_id);
    $lang_key = trim($key);
    $lang_value = trim($value);

    $sql = "SELECT short_name FROM " . MODULES_TABLE . " WHERE module_id = " . $nuke_module_id;

    if (!($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
    }
    
    if ($nuke_db->sql_numrows($result) == 0)
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get Module ' . $nuke_module_id);
    }
        
    $row = $nuke_db->sql_fetchrow($result);
    $short_name = trim($row['short_name']);
    $lang_entries = array();

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';
    include($language_nuke_file);
    $keys = array();
    eval('$current_lang = $' . trim($short_name) . ';');
        
    if (is_array($current_lang))
    {
        $i = 0;
        foreach ($current_lang as $key => $value)
        {
            if (trim($key) == $lang_key)
            {
                $lang_entries[$i]['key'] = trim($lang_key);
                $lang_entries[$i]['value'] = trim($lang_value);
                $i++;
            }
            else
            {
                $lang_entries[$i]['key'] = trim($key);
                $lang_entries[$i]['value'] = trim($value);
                $i++;
            }
        }
    }

    // Write Language File
    $data = '';
    for ($i = 0; $i < count($lang_entries); $i++)
    {
        $data .= '$lang[\'' . $lang_entries[$i]['key'] . '\'] = \'' . $lang_entries[$i]['value'] . '\';';
        $data .= "\n";
    }
    
    chmod($language_nuke_directory, $directory_mode);
        
    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
        
    if (!file_exists($language_nuke_file))
    {
        $contents = "<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
\n\n\n?>";
    }
    else
    {
        chmod($language_nuke_file, $file_mode);
        $contents = implode('', @file($language_nuke_file));
        $contents = delete_language_block($contents, $short_name);
    }
        
    $contents = str_replace('?>', '', $contents);
    $contents = trim($contents) . "\n";

    // add the BEGIN
    $contents .= "\n// [" . $short_name . "]\n";
    $contents .= "\$" . $short_name . " = array();\n\n";
    // add the language file
    $contents = $contents . str_replace('$lang', '$' . $short_name, $data) . "\n";
    // add the END and closing tag
    $contents .= "// [/" . $short_name . "]\n\n";
    $contents .= "?>";

    if (!($fp = fopen($language_nuke_file, 'wt')))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
    }

    fwrite($fp, $contents, strlen($contents));
    fclose($fp);

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);
}

// Set specific language block, $lang_block is the new language definition block as string
function set_lang_block($language_nuke, $nuke_module_id, $lang_block)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path;

    $language_nuke = trim($language_nuke);
    $nuke_module_id = intval($nuke_module_id);
    $lang_block = trim($lang_block);

    $sql = "SELECT short_name FROM " . MODULES_TABLE . " WHERE module_id = " . $nuke_module_id;

    if (!($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
    }
    
    if ($nuke_db->sql_numrows($result) == 0)
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get Module ' . $nuke_module_id);
    }
        
    $row = $nuke_db->sql_fetchrow($result);
    $short_name = trim($row['short_name']);
    $lang_entries = array();

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';

    // Write Language File
    chmod($language_nuke_directory, $directory_mode);
        
    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
        
    if (!file_exists($language_nuke_file))
    {
        $contents = "<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
\n\n\n?>";
    }
    else
    {
        chmod($language_nuke_file, $file_mode);
        $contents = implode('', @file($language_nuke_file));
        $contents = delete_language_block($contents, $short_name);
    }
        
    $contents = str_replace('?>', '', $contents);
    $contents = trim($contents) . "\n";

    // add the BEGIN
    $contents .= "\n// [" . $short_name . "]\n";
    $contents .= "\$" . $short_name . " = array();\n\n";
    // add the language file
    $contents = $contents . str_replace('$lang', '$' . $short_name, $lang_block) . "\n";
    // add the END and closing tag
    $contents .= "// [/" . $short_name . "]\n\n";
    $contents .= "?>";

    if (!($fp = fopen($language_nuke_file, 'wt')))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
    }

    fwrite($fp, $contents, strlen($contents));
    fclose($fp);

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);
}

// Add new key to a modules language block
function lang_add_new_key($language_nuke, $nuke_module_id, $add_key, $add_value)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path;

    $language_nuke = trim($language_nuke);
    $nuke_module_id = intval($nuke_module_id);
    $add_key = trim($add_key);
    $add_value = trim($add_value);

    $sql = "SELECT short_name FROM " . MODULES_TABLE . " WHERE module_id = " . $nuke_module_id;

    if (!($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
    }
    
    if ($nuke_db->sql_numrows($result) == 0)
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get Module ' . $nuke_module_id);
    }
        
    $row = $nuke_db->sql_fetchrow($result);
    $short_name = trim($row['short_name']);
    $lang_entries = array();

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';
    include($language_nuke_file);
    $keys = array();
    eval('$current_lang = $' . trim($short_name) . ';');
        
    if (is_array($current_lang))
    {
        $i = 0;
        foreach ($current_lang as $key => $value)
        {
            if (trim($key) == $add_key)
            {
                return (FALSE);
            }
            else
            {
                $lang_entries[$i]['key'] = trim($key);
                $lang_entries[$i]['value'] = trim($value);
                $i++;
            }
        }
    }

    // Write Language File
    $data = '';
    for ($i = 0; $i < count($lang_entries); $i++)
    {
        $data .= '$lang[\'' . $lang_entries[$i]['key'] . '\'] = \'' . $lang_entries[$i]['value'] . '\';';
        $data .= "\n";
    }
    
    $data .= '$lang[\'' . $add_key . '\'] = \'' . $add_value . '\';';
    $data .= "\n";

    chmod($language_nuke_directory, $directory_mode);
        
    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
        
    if (!file_exists($language_nuke_file))
    {
        $contents = "<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
\n\n\n?>";
    }
    else
    {
        chmod($language_nuke_file, $file_mode);
        $contents = implode('', @file($language_nuke_file));
        $contents = delete_language_block($contents, $short_name);
    }
        
    $contents = str_replace('?>', '', $contents);
    $contents = trim($contents) . "\n";

    // add the BEGIN
    $contents .= "\n// [" . $short_name . "]\n";
    $contents .= "\$" . $short_name . " = array();\n\n";
    // add the language file
    $contents = $contents . str_replace('$lang', '$' . $short_name, $data) . "\n";
    // add the END and closing tag
    $contents .= "// [/" . $short_name . "]\n\n";
    $contents .= "?>";

    if (!($fp = fopen($language_nuke_file, 'wt')))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
    }

    fwrite($fp, $contents, strlen($contents));
    fclose($fp);

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);
}

// Delete key out of language block
function delete_lang_key($language_nuke, $nuke_module_id, $key_name)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path;

    $language_nuke = trim($language_nuke);
    $nuke_module_id = intval($nuke_module_id);
    $key_name = trim($key_name);

    $sql = "SELECT short_name FROM " . MODULES_TABLE . " WHERE module_id = " . $nuke_module_id;

    if (!($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
    }
    
    if ($nuke_db->sql_numrows($result) == 0)
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get Module ' . $nuke_module_id);
    }
        
    $row = $nuke_db->sql_fetchrow($result);
    $short_name = trim($row['short_name']);
    $lang_entries = array();

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';
    include($language_nuke_file);
    $keys = array();
    eval('$current_lang = $' . trim($short_name) . ';');
        
    if (is_array($current_lang))
    {
        $i = 0;
        foreach ($current_lang as $key => $value)
        {
            if (trim($key) != $key_name)
            {
                $lang_entries[$i]['key'] = trim($key);
                $lang_entries[$i]['value'] = trim($value);
                $i++;
            }
        }
    }

    // Write Language File
    $data = '';
    for ($i = 0; $i < count($lang_entries); $i++)
    {
        $data .= '$lang[\'' . $lang_entries[$i]['key'] . '\'] = \'' . $lang_entries[$i]['value'] . '\';';
        $data .= "\n";
    }
    
    chmod($language_nuke_directory, $directory_mode);
        
    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
        
    if (!file_exists($language_nuke_file))
    {
        $contents = "<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
\n\n\n?>";
    }
    else
    {
        chmod($language_nuke_file, $file_mode);
        $contents = implode('', @file($language_nuke_file));
        $contents = delete_language_block($contents, $short_name);
    }
        
    $contents = str_replace('?>', '', $contents);
    $contents = trim($contents) . "\n";

    // add the BEGIN
    $contents .= "\n// [" . $short_name . "]\n";
    $contents .= "\$" . $short_name . " = array();\n\n";
    // add the language file
    $contents = $contents . str_replace('$lang', '$' . $short_name, $data) . "\n";
    // add the END and closing tag
    $contents .= "// [/" . $short_name . "]\n\n";
    $contents .= "?>";

    if (!($fp = fopen($language_nuke_file, 'wt')))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
    }

    fwrite($fp, $contents, strlen($contents));
    fclose($fp);

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);
}

// Add Empty Language
function add_empty_language($new_language)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path, $lang;

    $language_nuke = trim($new_language);

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    chmod($language_nuke_directory, $directory_mode);

    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';

    $sql = "SELECT short_name FROM " . MODULES_TABLE;

    if (!($result = $nuke_db->sql_query($sql)) )
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get short name', "", __LINE__, __FILE__, $sql);
    }
    
    if ($nuke_db->sql_numrows($result) == 0)
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to get Modules');
    }
        
    $rows = $nuke_db->sql_fetchrowset($result);
    $num_rows = $nuke_db->sql_numrows($result);

    for ($i = 0; $i < $num_rows; $i++)
    {
        $short_name = trim($rows[$i]['short_name']);

        if (!file_exists($language_nuke_file))
        {
            $contents = "<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
\n\n\n?>";
        }
        else
        {
            chmod($language_nuke_file, $file_mode);
            $contents = implode('', @file($language_nuke_file));
            $contents = delete_language_block($contents, $short_name);
        }
        
        $contents = str_replace('?>', '', $contents);
        $contents = trim($contents) . "\n";

        // add the BEGIN
        $contents .= "\n// [" . $short_name . "]\n";
        $contents .= "\$" . $short_name . " = array();\n\n";
        // add the END and closing tag
        $contents .= "// [/" . $short_name . "]\n\n";
        $contents .= "?>";

        if (!($fp = fopen($language_nuke_file, 'wt')))
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
        }

        fwrite($fp, $contents, strlen($contents));
        fclose($fp);
    }

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);

}

// Add new Language, use schema
function add_new_language($new_language, $lang_schema)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path, $lang;

    $language_nuke = trim($new_language);
    $lang_schema = trim($lang_schema);

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    $schema_language_file = $phpbb2_root_path . 'modules/language/' . $lang_schema . '/lang_modules.php';

    if (!file_exists($schema_language_file))
    {
        add_empty_language($new_language);
        return;
    }
    
    chmod($language_nuke_directory, $directory_mode);

    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';
    $contents = implode('', @file($schema_language_file));

    if (file_exists($language_nuke_file))
    {
        chmod($language_nuke_file, $file_mode);
    }

    if (!($fp = fopen($language_nuke_file, 'wt')))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
    }

    fwrite($fp, $contents, strlen($contents));
    fclose($fp);

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);
}

// Add Language, Language Content is provided
function add_new_language_predefined($new_language, $nuke_modules)
{
    global $directory_mode, $file_mode, $nuke_db, $phpbb2_root_path, $lang;

    // Module content is defined as array(short_name, content)

    $language_nuke = trim($new_language);

    $language_nuke_directory = $phpbb2_root_path . 'modules/language';

    if (!file_exists($language_nuke_directory))
    {
        message_die(NUKE_GENERAL_ERROR, 'Unable to find Language Directory');
    }

    chmod($language_nuke_directory, $directory_mode);

    if (!file_exists($language_nuke_directory . '/' . $language_nuke))
    {
        @umask(0);
        mkdir($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }
    else
    {
        chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    }

    $language_nuke_file = $phpbb2_root_path . 'modules/language/' . $language_nuke . '/lang_modules.php';

    @reset($nuke_modules);
    while (list($short_name, $lang_content) = each($nuke_modules))
    {
        $short_name = trim($short_name);

        if (!file_exists($language_nuke_file))
        {
            $contents = "<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
\n\n\n?>";
        }
        else
        {
            chmod($language_nuke_file, $file_mode);
            $contents = implode('', @file($language_nuke_file));
            $contents = delete_language_block($contents, $short_name);
        }
        
        $contents = str_replace('?>', '', $contents);
        $contents = trim($contents) . "\n";

        // add the BEGIN
        $contents .= "\n// [" . $short_name . "]\n";
        $contents .= "\$" . $short_name . " = array();\n\n";
        // add the END and closing tag
        $contents .= trim(str_replace('$lang', '$' . $short_name, $lang_content));
        $contents .= "\n\n// [/" . $short_name . "]\n\n";
        $contents .= "?>";

        if (!($fp = fopen($language_nuke_file, 'wt')))
        {
            message_die(NUKE_GENERAL_ERROR, 'Unable to write to: ' . $language_nuke_file);
        }

        fwrite($fp, $contents, strlen($contents));
        fclose($fp);
    }

    chmod($language_nuke_file, $file_mode);
    chmod($language_nuke_directory . '/' . $language_nuke, $directory_mode);
    chmod($language_nuke_directory, $directory_mode);
}

function delete_complete_language($language_nuke)
{
    global $nuke_db, $phpbb2_root_path;

    $language_nuke = trim($language_nuke);

    clear_directory('modules/language/' . $language_nuke);
}

?>