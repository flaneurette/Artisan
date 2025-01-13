<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);

// Optional headers to consider.
header("X-Frame-Options: DENY"); 
header("X-XSS-Protection: 1; mode=block"); 
header("Strict-Transport-Security: max-age=30");
header("Referrer-Policy: same-origin");

session_start();

require("../../dashboard/resources/PHP/Class.SecureMail.php");
	
	if(isset($_POST['token']))  {
			if($_POST['token'] === $_SESSION['token']) {   
				$parameters = array( 
					'to' => 'mystryl@protonmail.com',
					'name' => $_POST['name'],
					'email' => $_POST['email'],				
					'subject' => $_POST['subject'],
					'body' => $_POST['body']
				);
				$checkForm = new \security\forms\SecureMail($parameters);
				$checkForm->sendmail();
				$checkForm->sessionmessage('Mail sent!'); 
			} 

		$checkStatus = new \security\forms\SecureMail();
		$checkStatus->showmessage();
		$checkStatus->destroysession();
	} 

$setup = new \security\forms\SecureMail();
$setup->clearmessages();
$token = $setup->getToken();
$_SESSION['token'] = $token;
?>

<h2>Secure mail form.</h2>
<p>Test form.</p>
<form action="" method="post">
<input type="hidden" name="token" value="<?php echo $token;?>">
			<label for="name">Name:</label><br>
				<input type="text" name="name" value="Jane Doe">
				<p><!-- message --></p>
			<label for="email">E-mail:</label><br>
				<input type="text" name="email" value="jane.doe@website.com">
				<p><!-- message --></p>
			<label for="subject">Subject:</label><br>			
				<input type="text" name="subject" value="Test">
				<p><!-- message --></p>
			<label for="body">Message:</label><br>
				<textarea name="body" rows="10" cols="40">Is it working? Hope so! -JD.</textarea>
				<p><!-- message --></p>
  <input type="submit" name="submit" value="Submit">
</form>
