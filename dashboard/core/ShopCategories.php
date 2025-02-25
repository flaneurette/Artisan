<?php
	require("../configuration.php");
	include("Header.php");

	if(isset($_REQUEST['delete'])) {
		if($_SESSION['uuid'] === $_REQUEST['csrf']) {			
			$result = $db->delete('`shop.categories`',$db->intcast($_REQUEST['delete']));
		}
	}

	if(isset($_POST['csrf'])) {
		
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_POST['add'])) {
				// insert category
				$category_title_vars = $db->clean($_REQUEST['category_name'],'encode');
				$table    = '`shop.categories`';
				$columns  = ['`category.name`','meta_title','meta_description','meta_tags'];
				$values   = [$category_title_vars,$db->clean($_POST['meta_title'],'encode'),$db->clean($_POST['meta_description'],'encode'),$db->clean($_POST['meta_tags'],'encode')];
				$db->insert($table,$columns,$values);
				$success = "Category successfully added.";
			}
			if(isset($_POST['ordering'])) {
				
				if($db->intcast($_POST['max']) >= 20) {
					exit;
					} else {
					$max = $db->intcast($_POST['max']);
				}
				
				for($i=1;$i<=$max;$i++) {
					$order = explode(':',$_POST['order'.$i]);
					$table    = '`shop.categories`';
					$columns  = ['category.order'];
					$values   = [$db->intcast($order[1])];
					$db->update($table,$columns,$values,$db->intcast($order[0]));			
				}
				
				$success = "Category ordering successfully updated.";
			}
			
		}
	}
	
$result = $db->query("SELECT * from `shop.categories` ORDER BY `category.order` ASC"); 
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
	/ shop / categories
	</nav>
	<article class="main">
	<?php
	if(isset($errors)) {
		echo "<div id=\"dialog-alert\">".$errors."</div>";
	}
	if(isset($success)) {
		echo "<div id=\"dialog-success\">".$success."</div>";
	}
	if(count($result) < 1) {
		 echo "<div id=\"dialog-message\">There are no shop categories. Add some.</div>";
	} else {
	?>
	
	<label>Shop categories</label>
	<form action="" method="post">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="ordering" value="1" />
	<table rowspan="" width="100%" class="table-list" id="dragndrop">
	<?php 
		
		$j=1;
		for($i=0;$i<count($result);$i++){
			
			if($i % 2 !== 0) { 
				$color = "background-color: var(--lightgray);";
				} else {
				$color = "";
			}	
	?>
		<tr style="<?php echo $color;?>" id="table<?php echo $db->intcast($result[$i]['id']);?>" class="order">
		<td><a href="<?php echo $db->clean(SITE,'encode');?>shop/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['category.name'];?></a></td>
		<td><a href="<?php echo $db->clean(SITE,'encode');?>shop/categories/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><span class="material-symbols-outlined">edit</span></a></td>
		<td width="80"><a href="<?php echo $db->clean(SITE,'encode') . 'shop/categories/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this category?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
		<input type="hidden" name="order<?php echo $j;?>" id="input<?php echo $j;?>" value="" />
	<?php
	$j++;
	}
	?>
	</table>
	<input type="hidden" name="max" value="<?php echo count($result);?>" />
	<input type="submit" name="" value="Update order" />
	</form>
	<?php
	}
	?>
	<label>Add category</label>
	<form name="categories" action="" method="post">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="add" value="1" />
	<table rowspan="" width="100%" class="table-list">
		<tr>
			<td>
			<label>Category name:</label><input type="text" name="category.name" value="" class="shop-list-item">
			<label>Meta title</label>
			<input type="text" name="meta_title" value="" placeholder="" />
			<label>Meta description</label>
			<input type="text" name="meta_description" value="" placeholder="" />
			<label>Meta tags</label>
			<input type="text" name="meta_tags" value="" placeholder="A comma separated list" />
			<input type="submit" name="" value="Add category" />
			</td>
		</tr>
	</table>
	</form>
	
	</article>
</div>
<script>
	c = new dragndrop();
	c.tableClass = 'order';
	c.tableId = 'table';
	c.inputId = 'input';
	c.init();
</script>
</body>
</html>