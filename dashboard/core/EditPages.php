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
			if(isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
				// update component
				$component_title_vars = $db->clean($_REQUEST['page'],'encode');
				$table    = 'pages';
				$columns  = ['page_name','sub','ordering','meta_title','meta_description','meta_tags'];
				$values   = [$component_title_vars,0,$db->intcast($_POST['menu_order']),$db->clean($_POST['meta_title'],'encode'),$db->clean($_POST['meta_description'],'encode'),$db->clean($_POST['meta_tags'],'encode')];
				$db->update($table,$columns,$values,$pageid);
				$success = "Page successfully updated.";
			}
		}
	}
	
	$table    = 'pages';
	$column   = 'id';
	$value    =  $pageid;
	$operator = '*';
	$result = $db->select($table,$operator,$column,$value); 
	
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
	/ index / edit page <input type="submit" onclick="plainui.post();" class="btn" value="update page" />
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
	<label>Slug</label>
	<input type="text" name="page" value="<?php echo $db->clean($result[0]['page_name'],'encode');?>" />
	<label>Menu order</label>
	<input type="number" name="menu_order" value="<?php echo $db->clean($result[0]['ordering'],'encode');?>" width="10" size="10" />
	<label>Meta title</label>
	<input type="text" name="meta_title" value="<?php echo $db->clean($result[0]['meta_title'],'encode');?>" />
	<label>Meta description</label>
	<input type="text" name="meta_description" value="<?php echo $db->clean($result[0]['meta_description'],'encode');?>"/>
	<label>Meta tags</label>
	<input type="text" name="meta_tags" value="<?php echo $db->clean($result[0]['meta_tags'],'encode');?>" />
	</article>
	</form>
</div>
</body>
</html>
