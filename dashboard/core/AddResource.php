<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_FILES['resource']) && !empty($_FILES['resource'])) {
				foreach ($_FILES["resource"]["error"] as $key => $error) {
					$name = basename($_FILES["resource"]["name"][$key]);
					if(!empty($name)) {
						if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {	
							if ($error == UPLOAD_ERR_OK) {
								$tmp_name = $_FILES["resource"]["tmp_name"][$key];
								move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
								$success = "File(s) uploaded, and are now available in the lightbox.";

							} else {
								$errors = "File could not be uploaded, make sure Artisan can write to ../resources/content/";
							}
						} else {
							$errors = "File could not be uploaded, allowed filetypes: .png, .jpg, .gif";
						}
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
	<form name="post" action="" method="POST" id="form" autocomplete="off"  enctype="multipart/form-data">
	<nav class="nav">
	/ index / add resources <input type="submit" onclick="plainui.post();" class="btn" value="add" />
	</nav>
	<article class="main">
	<label>Resources</label>
	<?php
	if(isset($errors)) {
		echo "<div id=\"dialog-alert\">".$errors."</div>";
	}
	if(isset($success)) {
		echo "<div id=\"dialog-success\">".$success."</div>";
	}
	?>
	<table rowspan="" width="100%" class="table-list">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="file" id="files" name="resource[]" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="resource[]" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="resource[]" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="resource[]" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="resource[]" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="resource[]" accept=".png,.jpg,.gif"/>
	</table>
	</article>
	</form>
</div>
</body>
</html>
