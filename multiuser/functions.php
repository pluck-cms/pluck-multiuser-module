<?php
//Make sure the file isn't accessed directly.
defined('IN_PLUCK') or exit('Access denied!');

//Check if module is installed
if (!file_exists('multiuser.php')) {
	@copy('data/modules/multiuser/lib/multiuser.php', 'multiuser.php');
	if (!file_exists('multiuser.php')) $error['multiuser'] = 'Nie można zainstalować modułu multiuser. Musisz ręcznie skopiować plik multiuser.php do katalogu głównego.';
}

function get_users_list() {
	$users = file('data/settings/modules/multiuser/users.php');
	unset($users[0]); //dont read first line
	foreach ($users as $key => $user) {
		$users[$key] = explode("\t", $user, 2);
		$users[$key] = $users[$key][0];
	}
	unset ($user);

	return $users;
}
function get_role_user($id) {
	$users = file('data/settings/modules/multiuser/users.php');
	$user = $users[$id];
	unset($users);
	list($name, $pass, $role) = explode("\t", $user);
	return unserialize($role);
}
function get_pass_user($id) {
	$users = file('data/settings/modules/multiuser/users.php');
	$user = $users[$id];
	unset($users);
	list($name, $pass, $role) = explode("\t", $user);
	return $pass;
}
function show_user_box($id, $user) {
	global $lang;
	?>
	<div class="menudiv">
			<span>
				<img src="data/image/page.png" alt="" />
			</span>
			<span class="title-page"><?php echo $user; ?></span>
			<span>
				<a href="?module=multiuser&amp;page=edituser&amp;var1=<?php echo $id; ?>">
					<img src="data/image/edit.png" title="<?php echo $lang['multiuser']['edit_user']; ?>" alt="<?php echo $lang['multiuser']['edit']; ?>" />
				</a>
			</span>
			<span>
				<a href="?module=multiuser&amp;page=deleteuser&amp;var1=<?php echo $id; ?>">
					<img src="data/image/delete.png" title="<?php echo $lang['multiuser']['delete_user']; ?>" alt="<?php echo $lang['multiuseer']['delete_user']; ?>" />
				</a>
			</span>
		</div>
	<?php
}
function choose_role() {
	echo '<input type="checkbox" id="cont3" name="cont3[]" value="1" />Add / edit pages';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]" value="2" />Manage images';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]" value="3" />Manage files';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]" value="4" />Manage modules';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]" value="5" />Change options';
}

function multiuser_save_user($name, $pass, $role) {
	file_put_contents('data/settings/modules/multiuser/users.php', $name . "\t" . hash('sha512', $pass) . "\t" . serialize(($role)? $role : '0') . "\n", FILE_APPEND | LOCK_EX);
	redirect('?module=multiuser', 0);
}
?>