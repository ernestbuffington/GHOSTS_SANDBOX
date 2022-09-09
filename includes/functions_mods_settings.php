<?php

/***************************************************************************
 *                            functions_mods_settings.php
 *                            ---------------------------
 *	begin			: 10/08/2003
 *	copyright		: Ptirhiik
 *	email			: admin@rpgnet-fr.com
 *	version			: 1.0.4 - 26/09/2003
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

// some standard lists
$list_yes_no = array('Yes' => 1, 'No' => 0);
define('BOARD_ADMIN', 98);

//---------------------------------------------------------------
//
//	mods_settings_get_lang() : translation keys
//
//---------------------------------------------------------------
function mods_settings_get_lang($key)
{
	global $lang;
	return ( (!empty($key) && isset($lang[$key])) ? $lang[$key] : $key );
}

//---------------------------------------------------------------
//
//	init_board_config_key() : add a key and its value to the board config table
//
//---------------------------------------------------------------
function init_board_config_key($key, $value, $force=false)
{
	global $nuke_db, $board_config, $cache;
	if (!isset($board_config[$key]))
	{
		$board_config[$key] = $value;
		$sql = "INSERT INTO " . NUKE_CONFIG_TABLE . " (config_name,config_value) VALUES('$key','$value')";
		if ( !$nuke_db->sql_query($sql) ) message_die(NUKE_GENERAL_ERROR, 'Could not add key ' . $key . ' in config table', '', __LINE__, __FILE__, $sql);
		$cache->delete('board_config', 'config');
	}
	else if ($force)
	{
		$board_config[$key] = $value;
		$sql = "UPDATE " . NUKE_CONFIG_TABLE . " SET config_value='$value' WHERE config_name='$key'";
		if ( !$nuke_db->sql_query($sql) ) message_die(NUKE_GENERAL_ERROR, 'Could not add key ' . $key . ' in config table', '', __LINE__, __FILE__, $sql);
		$cache->delete('board_config', 'config');
	}
}

//---------------------------------------------------------------
//
//	user_board_config_key() : get the user choice if defined
//
//---------------------------------------------------------------
function user_board_config_key($key, $nuke_user_field='', $over_field='')
{
	global $board_config, $nuke_userdata;

	// get the user fields name if not given
	if (empty($nuke_user_field))
	{
		$nuke_user_field = 'user_' . $key;
	}

	// get the overwrite allowed switch name if not given
	if (empty($over_field))
	{
		$over_field = $key . '_over';
	}

	// does the key exists ?
	if (!isset($board_config[$key])) return;

	// does the user field exists ?
	if (!isset($nuke_userdata[$nuke_user_field])) return;

	// does the overwrite switch exists ?
	if (!isset($board_config[$over_field]))
	{
		$board_config[$over_field] = 0; // no overwrite
	}

	// overwrite with the user data only if not overwrite sat, not anonymous, and logged in
	if (!intval($board_config[$over_field]) && ($nuke_userdata['user_id'] != NUKE_ANONYMOUS) && $nuke_userdata['session_logged_in'])
	{
		$board_config[$key] = $nuke_userdata[$nuke_user_field];
	}
	else
	{
		$nuke_userdata[$nuke_user_field] = $board_config[$key];
	}
}

//---------------------------------------------------------------
//
//	init_board_config() : get the user choice if defined
//
//---------------------------------------------------------------
function init_board_config($mod_name, $nuke_config_fields, $sub_name='', $sub_sort=0, $mod_sort=0, $menu_name='Preferences', $menu_sort=0)
{
	global $mods;

	@reset($nuke_config_fields);
	// while ( list($nuke_config_key, $nuke_config_data) = each($nuke_config_fields) )
	foreach( $nuke_config_fields as $nuke_config_key => $nuke_config_data )
	{
		if (!isset($nuke_config_data['user_only']) || !$nuke_config_data['user_only'])
		{
			// create the key value
			init_board_config_key($nuke_config_key, ( !empty($nuke_config_data['values']) ? $nuke_config_data['values'][ $nuke_config_data['default'] ] : $nuke_config_data['default']) );
			if (!empty($nuke_config_data['user']))
			{
				// create the "overwrite user choice" value
				init_board_config_key($nuke_config_key . '_over', 0);

				// get user choice value
				user_board_config_key($nuke_config_key, $nuke_config_data['user']);
			}
		}

		// deliever it for input only if not hidden
		if (!$nuke_config_data['hide'])
		{
			$mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['data'][$nuke_config_key] = $nuke_config_data;

			// sort values : overwrite only if not yet provided
			if (empty($mods[$menu_name]['sort']) || ($mods[$menu_name]['sort'] == 0) )
			{
				$mods[$menu_name]['sort'] = $menu_sort;
			}
			if (empty($mods[$menu_name]['data'][$mod_name]['sort']) || ($mods[$menu_name]['data'][$mod_name]['sort'] == 0) )
			{
				$mods[$menu_name]['data'][$mod_name]['sort'] = $mod_sort;
			}
			if (empty($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['sort']) || ($mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['sort'] == 0) )
			{
				$mods[$menu_name]['data'][$mod_name]['data'][$sub_name]['sort'] = $sub_sort;
			}
		}
	}
}

?>