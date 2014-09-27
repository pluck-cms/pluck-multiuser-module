<?php
//This is a module for pluck, an opensource content management system
//Website: http://www.pluck-cms.org

//Make sure the file isn't accessed directly.
defined('IN_PLUCK') or exit('Access denied!');

require_once ('data/modules/multiuser/functions.php');
if (isset($error['multiuser']) && basename($_SERVER['PHP_SELF']) == 'admin.php') echo '<div class="error">'.$error['multiuser'].'</div>';

function multiuser_info() {
global $lang;
	return array(
		'name'          => $lang['multiuser']['title'],
		'intro'         => $lang['multiuser']['intro'],
		'version'       => '0.1 pre-alfa',
		'author'        => 'A_Bach',
		'website'       => 'http://www.pluck.ekyo.pl',
		'icon'          => '../../image/themes.png',
		'compatibility' => '4.7'
	);
}
function multiuser_admin_editpage_before() {
global $lang;
	if (!check_role_user_name('1', $_SESSION['pluck_login'])) {
		echo $lang['multiuser']['no_permisions'];
		die($lang['multiuser']['no_permisions']);
	}
}
?>