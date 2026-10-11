<?php
	include("Session.php");
	
	// Time an account stays locked after MAX_LOGIN_ATTEMPTS wrong passwords.
	$lockout = defined('LOGIN_LOCKOUT_SECONDS') ? LOGIN_LOCKOUT_SECONDS : 900;
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(!empty($_POST['username']) && !empty($_POST['password'])) {
				
				$username = $_POST["username"];
				// The password is only compared to its hash, so it is used as typed.
				$password = $_POST["password"];
				
				$table    = 'users';
				$column   = 'username';
				$value    =  $username;
				$operator = '*';
				$result = $db->select($table,$operator,$column,$value); 
				
				$errors = "Username or password is incorrect.";
				
				if(count($result) >= 1) {
					
					$id 		= $db->intcast($result[0]['id']);
					$attempts 	= $db->intcast($result[0]['attempts']);
					$last 		= $db->intcast($result[0]['attempt_time']);
					
					// The lock is lifted once the lockout time has passed.
					if((time() - $last) > $lockout) {
						$attempts = 0;
					}
					
					if($attempts >= MAX_LOGIN_ATTEMPTS) {
						
						// Locked: the password is not even checked.
						$errors = "Too many wrong passwords. Please try again in ".ceil(($lockout - (time() - $last)) / 60)." minutes.";
						
					} else if(password_verify($password, $result[0]['password'])) {
					
						$table    = 'users';
						$columns  = ['attempts','attempt_time'];
						$values   = [0,0];
						$db->update($table,$columns,$values,$id);
						
						$profile = $result[0];
						unset($profile['password']);
						
						$_SESSION['uid'] = $id;
						$_SESSION['profile'] = $profile;
						$_SESSION['loggedin'] = '1';
						$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
						session_regenerate_id(true);
						session_commit();
						header("Location: ../index.php");
						exit;
						
					} else {
						
						$table    = 'users';
						$columns  = ['attempts','attempt_time'];
						$values   = [$attempts + 1,time()];
						$db->update($table,$columns,$values,$id);
					}
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
	<nav class="nav">
	/ login
	</nav>
	<article class="main">
	<h1>Login</h1>
	<?php
	if(isset($errors)) {
		echo "<div id=\"dialog-alert\">".$errors."</div>";
	}
	?>
		<form name="login" method="POST" action="" class="loginform">
		<input type="hidden" name="csrf" value="<?php echo $token;?>" />
		<label>Username</label>
		<input type="text" name="username" value="" required>
		<label>Password</label>
		<input type="password" name="password" value="" required>
		<input type="submit" name="" value="Login" />
	</form>
	</article>
</div>
</body>
</html>