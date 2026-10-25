<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_REQUEST['old_password']) && !empty($_REQUEST['new_password'])) {
				
				$newpassword1 = $_REQUEST['new_password'];
				$newpassword2 = password_hash($newpassword1, PASSWORD_DEFAULT);
				$id = 1;
				$table    = 'users';
				$columns  = ['password'];
				$values   = [$newpassword2];
				$db->update($table,$columns,$values,$id);
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
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<label>Old password: </label><input type="password" name="old_password" value="" placeholder="Old password" required>
	<label>New password: </label> <input type="password" name="new_password" value="" placeholder="New password" required>
	</article>
	</form>
</div>
</body>
</html>
