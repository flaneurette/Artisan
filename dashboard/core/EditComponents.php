<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['pid'])) { 
		$pageid = $db->intcast($_REQUEST['pid']);
	}

	if(!isset($pageid)) {
		header("location: ../error/4/");
		exit;
	}
	
	if(isset($_POST['csrf'])) {
		
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_POST['count'])) { 
				$len = $db->intcast($_POST['count']);
				} else {
				$len = 1;
			}
			
			for($i = 0; $i < $len; $i++) {
			
				$image = false;
				if(isset($_FILES['resource_'.$i]) && !empty($_FILES['resource_'.$i])) {
					$name = basename($_FILES['resource_'.$i]["name"]);
					if(!empty($name)) {
						if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {						
							if ($_FILES['resource_'.$i]['error'] == UPLOAD_ERR_OK) {
								$tmp_name = $_FILES['resource_'.$i]["tmp_name"];
								move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
								$image = $name;
							}
						}
					}
				}
			
				$id = $db->intcast($_POST['id'.$i]);
				$component_title_vars = $_POST['component_title_' . $i];
				$component_text_vars  = $_POST['component_text_' . $i];
				$table    = 'components';
				if($image != false) { 
					$columns  = ['component_title','component_text','component_image'];
					$values   = [$component_title_vars,$component_text_vars,$image];
					} else {
					$columns  = ['component_title','component_text'];
					$values   = [$component_title_vars,$component_text_vars];		
				}
				$db->update($table,$columns,$values,$id);
				header("Location: ../../../index.php");
			}
		}
	}
	
	$table    = 'components';
	$column   = 'pid';
	$value    =  $pageid;
	$operator = '*';
	$result = $db->select($table,$operator,$column,$value); 

	$table    = 'pages';
	$column   = 'id';
	$value    =  $pageid;
	$operator = '*';
	$result_header = $db->select($table,$operator,$column,$value);	
	
?>
<!DOCTYPE html>
<html>
<head>
<?php include("Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header" >
	<?php include("Navigation.php");?>
	</header>
	<nav class="nav">
	/ index / edit / components on <?php echo $result_header[0]['page_name'];?> <input type="submit" onclick="plainui.post();" class="btn" value="update" />
	</nav>
	<article class="main">
	<form name="post" action="" method="POST" id="form" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="edit" value="1" />
	<input type="hidden" name="pageid" value="<?php echo $pageid;?>" />
	<input type="hidden" name="count" id="count" value="<?php echo count($result);?>" />
	<?php 
	for($i=0;$i<count($result);$i++){
		$image = "../../../../resources/content/" . $result[$i]["component_image"];
		if($result[$i]["component_image"] =='') {
			$image = "../../../resources/content/thumb.png";
		} 
	?>
		<h1><div name="" class="clearfix" contentEditable="true" id="titleditor-<?php echo $i;?>" oninput="plainui.proc('titleditor-<?php echo $i;?>','component_title_<?php echo $i;?>');"><?php echo $db->clean($result[$i]['component_title'],'encode');?></div></h1>
		<img src="<?php echo $image;?>" width="262" class="component-image"/><input type="file" id="files" name="resource_<?php echo $i;?>" class="component-upload" accept=".png,.jpg,.gif"/>
		<input type="hidden" name="component_title_<?php echo $i;?>" id="component_title_<?php echo $i;?>" value="<?php echo $result[$i]['component_title'];?>"  />
		<textarea id="component_text_<?php echo $i;?>" name="component_text_<?php echo $i;?>" class="textarea"></textarea>
		<input type="hidden" name="id<?php echo $i;?>" value="<?php echo $db->intcast($result[$i]['id']);?>"  />
		<div name="component_text" style="overflow-y:scroll;" contentEditable="true" name="post-message" class="texteditor" id="texteditor-<?php echo $i;?>" oninput="plainui.proc('texteditor-<?php echo $i;?>','component_text_<?php echo $i;?>');" placeholder="Write..."><?php echo $result[$i]['component_text'];?></div>
	<?php
	}
	?>
	</form>
	</article>
</div>
</body>
</html>