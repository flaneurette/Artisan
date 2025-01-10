<?php
	
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['reason'])) {
		
		switch($_REQUEST['reason']) {
			case 1:
			$error_message = "You have reached the maximum login attempts, please contact your database administrator to lift restriction.";
			break;
			case 2:
			$error_message = "Anti CSRF token is incorrect.";
			break;
			case 3:
			$error_message = "Could not initialize a session. Possible reasons: session data might be full or not possible to create a session. For security reasons the administration panel cannot be loaded. Exiting.";
			break;
			case 4:
			$error_message = "PageID is required to edit this page.";
			break;
			default:
			$error_message = "We've encountered an unknown error.";
			break;
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
	/ error
	</nav>
	<article class="main">
	<h1>Error</h1>
		<div id="dialog-alert"><?php echo $error_message;?></div>
	</form>
	</article>
</div>
</body>
</html>
