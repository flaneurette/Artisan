<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['id'])) { 
		$pageid = $db->intcast($_REQUEST['id']);
	}
	
	if(!isset($pageid)) {
		header("location: ../error/4/");
		exit;
	}
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				// update catgories
				$category_title_vars = $db->clean($_REQUEST['category_name'],'encode');
				$table    = '`shop.categories`';
				$columns  = ['category.name','category.order','meta_title','meta_description','meta_tags'];
				$values   = [$category_title_vars,$db->clean($_POST['category_order'],'encode'),$db->clean($_POST['meta_title'],'encode'),$db->clean($_POST['meta_description'],'encode'),$db->clean($_POST['meta_tags'],'encode')];
				$db->update($table,$columns,$values,$pageid);
				$success = "Page successfully updated.";
			}
		}
	}
	
	$table    = '`shop.categories`';
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
	/ index / edit category <input type="submit" onclick="plainui.post();" class="btn" value="update page" />
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
	<input type="hidden" name="id" value="<?php echo $db->clean($result[0]['id'],'encode');?>" />
	<label>Category name</label>
	<input type="text" name="category_name" value="<?php echo $db->clean($result[0]['category.name'],'encode');?>" />
	<label>Menu order</label>
	<input type="number" name="category_order" value="<?php echo $db->clean($result[0]['category.order'],'encode');?>" width="10" size="10" />
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
