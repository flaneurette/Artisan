<?php
	
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_REQUEST['component_title']) && !empty($_REQUEST['component_title'])) {
				
				
				if(isset($_FILES['main_image']) && !empty($_FILES['main_image'])) {
					foreach ($_FILES["main_image"]["error"] as $key => $error) {
						$name = basename($_FILES["main_image"]["name"][$key]);
						if(!empty($name)) {
							if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {	
								if ($error == UPLOAD_ERR_OK) {
									$tmp_name = $_FILES["main_image"]["tmp_name"][$key];
									$image = $name;
									move_uploaded_file($tmp_name, UPLOAD_DIR. "$name");
									$success = "Component successfully added.";
								} else { } 
							}
						}
					}
				}
				
				
				if(isset($image)) { 
					$id = $db->intcast($_POST['pageid']);
					$component_title_vars = $_POST['component_title'];
					$component_text_vars  = $_POST['component_text'];
					$table    = 'components';
					$columns  = ['pid','component_title','component_text','component_image'];
					$values   = [$id,$component_title_vars,$component_text_vars,$image];
					$db->insert($table,$columns,$values);
				} else {
					$id = $db->intcast($_POST['pageid']);
					$component_title_vars = $_POST['component_title'];
					$component_text_vars  = $_POST['component_text'];
					$table    = 'components';
					$columns  = ['pid','component_title','component_text'];
					$values   = [$id,$component_title_vars,$component_text_vars];
					$db->insert($table,$columns,$values);
				}
				
			}
		}
	}
	
	$result = $db->query("SELECT * FROM pages");
	
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
	/ index / add / component on 		<select name="pageid">
		<?php 
		for($i=0;$i<count($result);$i++) { 
		?> 
		<option value="<?php echo $result[$i]['id'];?>"><?php echo $result[$i]['page_name'];?></option>
		<?php 
		} 
		?>
		</select> <input type="submit" onclick="document.getElementById('form').submit();" class="btn" value="add component" />
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
	<input type="hidden" name="edit" value="1" />
		<h1><div name="" contentEditable="true" id="titleditor" oninput="plainui.proc('titleditor','component_title');">Title</div></h1>
		<input type="hidden" name="component_title" id="component_title" value=""  />
		<textarea id="component_text" name="component_text" class="textarea"></textarea>
		<div name="component_text" style="overflow-y:scroll;" contentEditable="true" name="post-message" class="texteditor2" id="texteditor" oninput="plainui.proc('texteditor','component_text');" placeholder="Write...">Text...</div>
		<label>Main image</label><input type="file" name="main_image[]" accept=".png,.jpg,.gif"/>
	</form>
	</article>
</div>
</body>
</html>
