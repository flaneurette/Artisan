<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['delete'])) {
		if($_SESSION['uuid'] === $_REQUEST['csrf']) {			
			$result = $db->delete('shop',$db->intcast($_REQUEST['delete']));
		}
		header("Location: ../../../");
	}
	
	$result = $db->query("SELECT * from shop ORDER BY id DESC"); 
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
	/ shop
	</nav>
	<article class="main">
	<?php
	if(count($result) < 1) {
		 echo "<div id=\"dialog-message\">There are no shop products. Add some by clicking Add item.</div>";
	} else {
	?>
	<label>Shop items</label>
	<table rowspan="" width="100%" class="table-list">
	<?php 
		
		for($i=0;$i<count($result);$i++){
			
			$image = UPLOAD_DIR . $result[$i]["product.image"];
			if($result[$i]["product.image"] =='') {
				$image = UPLOAD_DIR ."thumb.png";
				} elseif(!file_exists($image)) {
				$image = UPLOAD_DIR ."thumb.png";
			}
			
			if($i % 2 !== 0) { 
				$color = "background-color: var(--lightgray);";
				} else {
				$color = "";
			}	
			
			if($result[$i]['product.stock'] <=0) {
				$stock = 'lowstock';
				} else {
				$stock = '';
			}
	?>
		<tr style="<?php echo $color;?>">
		<td width="150"><img src="<?php echo $image;?>" class="shop-list-image" width="100"/></td>
		<td><small><a href="<?php echo $db->clean(SITE,'encode');?>shop/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo  $db->clean($result[$i]['product.title'],'encode');?></a></small></td>
		<td><small class="<?php echo $stock;?>"><?php echo $db->intcast($result[$i]['product.stock']);?> left in stock</small></td>
		<td><a href="<?php echo $db->clean(SITE,'encode');?>shop/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><span class="material-symbols-outlined">edit</span></a></td>
		<td width="80"><a href="<?php echo $db->clean(SITE,'encode') . 'shop/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this shop item?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	<?php
	}
	?>
	</article>
</div>
</body>
</html>