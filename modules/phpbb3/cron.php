<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

use Symfony\Component\HttpFoundation\RedirectResponse;

if(defined('PHPBB3_MODULE') ):
$nuke_module_name = basename(dirname(__FILE__));
require(NUKE_PHPBB3_DIR . 'nukebb.php');
define('IN_PHPBB', true);
$phpbb_root_path = (defined('NUKE_PHPBB3_DIR')) ? NUKE_PHPBB3_DIR : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include(NUKE_PHPBB3_DIR . 'extension.inc');
include(NUKE_PHPBB3_DIR . 'common.php');
else:

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
endif;

// Do not update users last page entry
$user->session_begin(false);
$auth->acl($user->data);

$cron_type = $request->variable('cron_type', '');

$get_params_array = $request->get_super_global(\phpbb\request\request_interface::GET);

/** @var \phpbb\controller\helper $controller_helper */
$controller_helper = $phpbb_container->get('controller.helper');
$response = new RedirectResponse(
	$controller_helper->route('phpbb_cron_run', $get_params_array, false),
	301
);
$response->send();
?>
