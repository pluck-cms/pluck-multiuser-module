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
function check_role_user_name($role_id, $name) {
	return in_array($role_id, get_role_user(array_keys(get_users_list(), $name)[0]));
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
function choose_role($id = null) {
	if (isset($id))
		$role = get_role_user($id);
	echo '<input type="checkbox" id="cont3" name="cont3[]"'; echo (isset($role[1])) ? 'checked' : ''; echo ' value="1" />Add / edit pages';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]"'; echo (isset($role[2])) ? 'checked' : ''; echo ' value="2" />Manage images';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]"'; echo (isset($role[3])) ? 'checked' : ''; echo ' value="3" />Manage files';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]"'; echo (isset($role[4])) ? 'checked' : ''; echo ' value="4" />Manage modules';
	echo '<br /><input type="checkbox" id="cont3" name="cont3[]"'; echo (isset($role[5])) ? 'checked' : ''; echo ' value="5" />Change options';
}

function multiuser_save_user($name, $pass, $role, $id = null) {
	if (!isset($id)) {
		file_put_contents('data/settings/modules/multiuser/users.php', $name . "\t" . hash('sha512', $pass) . "\t" . serialize(($role)? $role : '0') . "\n", FILE_APPEND | LOCK_EX);
		redirect('?module=multiuser', 0);
	}
	else {
		$users = file('data/settings/modules/multiuser/users.php');
		//if we are changing pass, then hash the pass, else pass is already hashed.
		if (!empty($pass))
			$pass = hash('sha512', $pass);
		else
			$pass = get_pass_user($id);
		$users[$id] = $name . "\t" .  $pass . "\t" . serialize(($role)? $role : '0') . "\n";
		file_put_contents('data/settings/modules/multiuser/users.php', $users, LOCK_EX);
		redirect('?module=multiuser', 0);
	}
}
?>