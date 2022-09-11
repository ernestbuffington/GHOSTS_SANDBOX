<?php
/** PORT DATE 09/06/2022 to 09/09/2022
*
* This file is a part of PHP-AN602 v3.3.x
*
* Ernest Allen Buffington of The 86it Developers Network
* is the author of PHP-ANG602 and this port of phpBB v3.3.x
* 
* You may contact TheGhost AKA Ernest Allen Buffington
* email: <webmaster@an602.86it.us>
* cell: 813-846-2865 
*
* @copyright (c) Brandon Maintenance Management <https://www.facebook.com/brandon.maintenance>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* This file is part of a Fork of phpBB v3.3.8 Forum Software package.
*
* Original @copyright (c) phpBB Limited <https://www.phpbb.com>
* Original @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

use Symfony\Component\HttpFoundation\RedirectResponse;

if(defined('PHPBB3_MODULE') ):                                             #### ADD Ernest Allen Buffington 09/09/2022
$nuke_module_name = basename(dirname(__FILE__));                                #### ADD Ernest Allen Buffington 09/09/2022
require(NUKE_PHPBB3_DIR . 'nukebb.' . $phpEx);                             #### ADD Ernest Allen Buffington 09/09/2022
define('IN_PHPBB', true);                                                  #### ADD Ernest Allen Buffington 09/09/2022
$phpbb_root_path = (defined('NUKE_PHPBB3_DIR')) ? NUKE_PHPBB3_DIR : './';  #### ADD Ernest Allen Buffington 09/09/2022
$phpEx = substr(strrchr(__FILE__, '.'), 1);                                #### ADD Ernest Allen Buffington 09/09/2022
include(NUKE_PHPBB3_DIR . 'extension.inc');                                #### ADD Ernest Allen Buffington 09/09/2022
include(NUKE_PHPBB3_DIR . 'common.' . $phpEx);                             #### ADD Ernest Allen Buffington 09/09/2022
else:                                                                      #### ADD Ernest Allen Buffington 09/09/2022

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
endif;                                                                     #### ADD Ernest Allen Buffington 09/09/2022

// Start session management
$user->session_begin();
$auth->acl($user->data);

$post_id		= $request->variable('p', 0);
$pm_id			= $request->variable('pm', 0);

$redirect_route_name = ($pm_id === 0) ? 'phpbb_report_post_controller' : 'phpbb_report_pm_controller';

/** @var \phpbb\controller\helper $controller_helper */
$controller_helper = $phpbb_container->get('controller.helper');
$response = new RedirectResponse(
	$controller_helper->route($redirect_route_name, array(
		'id'	=> ($pm_id === 0) ? $post_id : $pm_id,
	), false),
	301
);
$response->send();
?>
