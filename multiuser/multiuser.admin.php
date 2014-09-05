<?php

//Make sure the file isn't accessed directly.
defined('IN_PLUCK') or exit('Access denied!');

require_once ('data/modules/multiuser/functions.php');

function multiuser_pages_admin() {
	global $lang;
	$module_page_admin[] = array(
		'func'  => 'multiuser',
		'title' => $lang['multiuser']['title']
	);
	$module_page_admin[] = array(
		'func'  => 'deleteuser',
		'title' => $lang['multiuser']['delete_user']
	);
	$module_page_admin[] = array(
		'func'  => 'newuser',
		'title' => $lang['multiuser']['new_user']
	);
	$module_page_admin[] = array(
		'func'  => 'edituser',
		'title' => $lang['multiuser']['edit_user']
	);
	return $module_page_admin;
}

function multiuser_page_admin_multiuser() {
	global $lang;
	showmenudiv($lang['multiuser']['new'], null, 'data/image/newpage.png', '?module=multiuser&page=newuser');
	echo '<p>'.$lang['multiuser']['list'],'</p>';

	//Show users
	$users = get_users_list();
	
	if ($users) {
		foreach ($users as $id => $user)
		show_user_box($id, $user);
	}
}

function multiuser_page_admin_newuser() {
	global $cont1, $cont2, $cont3, $lang;

	//If form is posted...
	if (isset($_POST['save'])) {
		//login
		if (empty($_POST['cont1']))
			$error['login'] = show_error($lang['multiuser']['no_login'], 1, true);
		if (in_array($_POST['cont1'], get_users_list()))
			$error['login'] = show_error($lang['multiuser']['login_exists'], 1, true);
		//pass
		if (empty($_POST['cont2'])) 
			$error['pass'] = show_error($lang['multiuser']['no_pass'], 1, true);
		//roles
		$roles = array();
		foreach ($cont3 as $role) {
			$roles[$role] = $role;
		}
		unset ($role);

		if (!isset($error))
			multiuser_save_user($cont1, $cont2, $roles);
	}
	
	?>
	<form method="post" action="">
		<p>
			<label class="kop2" for="cont1"><?php echo $lang['multiuser']['login'] ?></label>
			<br />
			<input name="cont1" id="cont1" type="text" value="" />
			<?php if (isset($error['login'])) echo $error['login']; ?>
		</p>
		<p>
			<label class="kop2" for="cont2"><?php echo $lang['multiuser']['pass'] ?></label>
			<br />
			<input name="cont2" id="cont2" type="text" value="" />
			<?php if (isset($error['pass'])) echo $error['pass']; ?>
		</p>
		<p>
			<label class="kop2" for="cont3"><?php echo $lang['multiuser']['role']; ?></label>
			<br />
			<?php choose_role(); ?>
		</p>
		<?php show_common_submits('?module=multiuser'); ?>
	</form>
	<?php
}

function multiuser_page_admin_deleteuser() {
	global $lang, $var1;

	if($var1 > 0) {
		$users = file('data/settings/modules/multiuser/users.php');
		unset($users[$var1]);
		file_put_contents('data/settings/modules/multiuser/users.php', $users, LOCK_EX);
	}
	redirect('?module=multiuser', 0);
}

function multiuser_page_admin_edituser() {
	global $cont1, $cont2, $cont3, $lang, $var1;
	
	if($var1 > 0) {
		$users = file('data/settings/modules/multiuser/users.php');
		$user = $users[$var1];
		unset($users);
		list($name, $pass, $role) = explode("\t", $user);
		unserialize($role);
		
		//If form is posted...
		if (isset($_POST['save'])) {
			//login
			if (empty($_POST['cont1']))
				$error['login'] = show_error($lang['multiuser']['no_login'], 1, true);
			//roles
			$roles = array();
			foreach ($cont3 as $role) {
				$roles[$role] = $role;
			}
			unset ($role);

			if (!isset($error))
				multiuser_save_user($cont1, $cont2, $roles, $var1);
		}
	
		?>
		<form method="post" action="">
			<p>
				<label class="kop2" for="cont1"><?php echo $lang['multiuser']['login'] ?></label>
				<br />
				<input name="cont1" id="cont1" type="text" value="<?php echo $name; ?>" />
				<?php if (isset($error['login'])) echo $error['login']; ?>
			</p>
			<p>
				<label class="kop2" for="cont2"><?php echo $lang['multiuser']['pass'] ?></label>
				<br />
				<input name="cont2" id="cont2" type="text" value="" />
				<?php if (isset($error['pass'])) echo $error['pass']; ?>
			</p>
			<p>
				<label class="kop2" for="cont3"><?php echo $lang['multiuser']['role']; ?></label>
				<br />
				<?php choose_role($var1); ?>
			</p>
			<?php show_common_submits('?module=multiuser'); ?>
		</form>
		<?php
		
		
	}
}
?>