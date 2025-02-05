<?php
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	
	require("../configuration.php");
	include("../resources/PHP/Class.DB.php");
	include("Cryptography.php");

	$cryptography = new Cryptography;
	$db = new sql();
	
	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
	} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
	
	$_SESSION['admin-uuid'] = $cryptography->uniqueID();
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		header("location: ../error/3/");
		exit;
	}
	
	// create a new admin token
	if(!isset($_SESSION['uuid'])) {
		$token  = $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$_SESSION['uuid'] = $token;		
	} else {
		$token = $_SESSION['uuid'];
	}
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_REQUEST['username']) && !empty($_REQUEST['password'])) {
				
				$username = $db->clean($_POST["username"],'encode');
				$password = $db->clean($_POST["password"],'encode');
		
				$userprofile = [];
				$result = [];
				
				$table    = 'users';
				$column   = 'username';
				$value    =  $username;
				$operator = '*';
				$result = $db->select($table,$operator,$column,$value); 
				$result_attempt = $db->query('select * from `users` WHERE role = 1');
				
				if(count($result) >= 1 && !password_verify($password, $result[0]['password'])) {
					if($result_attempt[0]['attempts'] >= MAX_LOGIN_ATTEMPTS) {
						header("location: ../error/1/");
						$may_login = false;
						exit;
					}	
				} else if(count($result) >= 1 && password_verify($password, $result[0]['password'])) {
					
					$id = 1;
					$table    = 'users';
					$columns  = ['attempts'];
					$values   = [0];
					$db->update($table,$columns,$values,$id);
					
					$_SESSION['uid'] = $db->intcast($result[0]['id']);
					$_SESSION['profile'] = $result[0];
					$_SESSION['loggedin'] = '1';
					$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
					session_regenerate_id();
					session_commit();
					 header("Location: ../index.php");
					 exit;
					} else {
					$id = 1;
					$table    = 'users';
					$columns  = ['attempts'];
					$values   = [$result_attempt[0]['attempts'] + 1];
					$db->update($table,$columns,$values,$id);
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