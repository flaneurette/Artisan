<?php
	include("Header.php");
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
				
				$id 	= $db->intcast($_SESSION['uid']);
				$user 	= $db->select('users','*','id',$id);
				
				// Passwords are only hashed, so they are used as typed.
				if(!isset($user[0]) || !password_verify($_POST['old_password'], $user[0]['password'])) {
					$errors = "The old password is incorrect.";
				} else if(strlen($_POST['new_password']) < 8) {
					$errors = "The new password must be at least 8 characters long.";
				} else {
					$newpassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
					$table    = 'users';
					$columns  = ['password'];
					$values   = [$newpassword];
					$db->update($table,$columns,$values,$id);
					$success = "Password successfully changed.";
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<?php include("Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header">
	<?php include("Navigation.php");?>
	</header>
	<form name="post" action="" method="POST" id="form" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<nav class="nav">
	/ index / settings <input type="submit" onclick="plainui.post();" class="btn" value="save" />
	</nav>
	<article class="main">
	<?php
	if(isset($errors)) {
		echo "<div id=\"dialog-alert\">".$errors."</div>";
	}
	if(isset($success)) {
		echo "<div id=\"dialog-success\">".$success."</div>";
	}
	?>
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<label>Old password: </label><input type="password" name="old_password" value="" placeholder="Old password" required>
	<label>New password: </label> <input type="password" name="new_password" value="" placeholder="New password" minlength="8" required>
	</article>
	</form>
</div>
</body>
</html>
