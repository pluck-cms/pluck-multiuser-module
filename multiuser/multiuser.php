<?php
//Make sure the file isn't accessed directly.
defined('IN_PLUCK') or exit('Access denied!');

require_once ('data/modules/multiuser/functions.php');
if (isset($error['multiuser']) && basename($_SERVER['PHP_SELF']) == 'admin.php') echo '<div class="error">'.$error['multiuser'].'</div>';

function multiuser_info() {
	return array(
		'name'          => 'multiuser',
		'intro'         => 'multiuser intro',
		'version'       => '0.1',
		'author'        => 'A_Bach',
		'website'       => 'http://www.pluck.ekyo.pl',
		'icon'          => '../../image/themes.png',
		'compatibility' => '4.7'
	);
}
function multiuser_admin_editpage_before() {
	if (isset($_SESSION['pluck_multiuser_login']) && in_array('1', get_role_user(array_keys(get_role_user(), $_SESSION['pluck_login'])))) {
		echo 'brak uprawnień';
		die('brak uprawnień');
	}
}
?>