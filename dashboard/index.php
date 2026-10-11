<?php
	include("core/Header.php");
	
	// Warn as long as the password that Artisan is installed with has not been changed.
	$result_user = $db->select('users','*','id',$db->intcast($_SESSION['uid']));
	$default_password = isset($result_user[0]) && password_verify('admin', $result_user[0]['password']);
	
	$result_orders 	= $db->query("SELECT * from `shop_orders` where `order_fulfilled` != 1"); 
	$result_shop 	= $db->query("SELECT * from shop ORDER BY id DESC LIMIT 10");
	$result_stock 	= $db->query("SELECT * from shop WHERE `product_stock` = 0"); 	
	$result 		= $db->query("SELECT * FROM components order by id DESC LIMIT 10");
	
	if(isset($result_stock)) {
		if(count($result_stock) >=1) {
			$stock = "intro-circle-red";
			} else {
			$stock = "intro-circle";
		}
	}

	if(isset($result_orders)) {
		if(count($result_orders) >=1) {
			$neworder = "intro-circle-green";
			} else {
			$neworder = "intro-circle";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<?php include("core/Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header">
	<?php include("core/Navigation.php");?>
	</header>
	<nav class="nav">
	Welcome to the Artisan admin
	</nav>
	<article class="main">
	<?php
	if($default_password) {
		echo "<div id=\"dialog-alert\">You are still using the default password. <a href=\"".$db->clean(SITE,'encode')."settings/\">Change it under admin settings.</a></div>";
	}
	?>
	<div class="intro-list">
		<a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/shop-settings/" target="_self"><div class="intro-circle">Announcement</div></a>
		<a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/shop-orders/" target="_self"><div class="<?php echo $neworder;?>">Orders</div></a>
		<!-- <a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/shop-customers/" target="_self"><div class="intro-circle">Customers</div></a>
		<a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/shop-invoices/" target="_self"><div class="intro-circle">Invoices</div></a> -->
		<a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/shop/" target="_self"><div class="<?php echo $stock;?>">Inventory</div></a>
		<a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/shop/add/" target="_self"><div class="intro-circle">Add item</div></a> 
		<a href="<?php echo $db->clean(WEBSITE,'encode');?>dashboard/components/" target="_self"><div class="intro-circle">Components</div></a>
	</div>

	<?php
	if(count($result_shop) < 1) {
		 echo "<div id=\"dialog-message\">There are no shop products. Add some by clicking Add item.</div>";
	} else {
	?>
	<label>Last ten added shop items</label>
	<table rowspan="" width="100%" class="table-list">
	<?php 
		
		for($i=0;$i<count($result_shop);$i++){
			
			$image = str_replace('../../','../',UPLOAD_DIR) . $result_shop[$i]["product_image"];
			if($result_shop[$i]["product_image"] =='') {
				$image = str_replace('../../','../',UPLOAD_DIR) ."thumb.png";
				} elseif(!file_exists($image)) {
				$image = str_replace('../../','../',UPLOAD_DIR) ."thumb.png";
			}
			
			if($i % 2 !== 0) { 
				$color = "background-color: var(--lightgray);";
				} else {
				$color = "";
			}	
	?>
		<tr style="<?php echo $color;?>">
		<td width="150"><img src="<?php echo $image;?>" class="shop-list-image" width="100"/></td>
		<td><a href="<?php echo $db->clean(SITE,'encode');?>shop/edit/<?php echo $db->intcast($result_shop[$i]['id']);?>/"><?php echo $db->clean($result_shop[$i]['product_title'],'encode');?></a></td>
		<td><?php echo $db->intcast($result_shop[$i]['product_stock']);?> left in stock</td>
		<td><a href="<?php echo $db->clean(SITE,'encode');?>shop/edit/<?php echo $db->intcast($result_shop[$i]['id']);?>/"><span class="material-symbols-outlined">edit</span></a></td>
		<td width="80"><a href="<?php echo $db->clean(SITE,'encode') . 'shop/'.$token;?>/delete/<?php echo $db->intcast($result_shop[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this shop item?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	<?php 
	}
	?>
	
	<label>Last ten added components on pages</label>
	<table rowspan="" width="100%" class="table-list">
	<?php 

	for($i=0;$i<count($result);$i++){
		$image = str_replace('../../','../',UPLOAD_DIR). $result[$i]["component_image"];
		if($result[$i]["component_image"] =='') {
			$image = str_replace('../../','../',UPLOAD_DIR) ."resources/content/thumb.png";
		} 
		
		if($i % 2 !== 0) { 
			$color = "background-color: var(--lightgray);";
			} else {
			$color = "";
		}
	?>
		<tr style="<?php echo $color;?>">
		<td width="150"><img src="<?php echo $image;?>" width="100"/></td>
		<td valign="top"><a href="<?php echo $db->clean(SITE,'encode');?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $db->clean($result[$i]['component_title'],'encode');?></a></td>
		<td valign="top"><a href="<?php echo $db->clean(SITE,'encode');?>API.php?filetype=unique&id=<?php echo $db->intcast($result[$i]['id']);?>" target="_blank"><span class="material-symbols-outlined">database</span></a></td>
		<td valign="top"></td>
		<td width="50" valign="top"><a href="<?php echo $db->clean(SITE,'encode');?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><span class="material-symbols-outlined">edit</span></a></td>
		<td width="50" valign="top"><a href="<?php echo $db->clean(SITE,'encode') . 'components/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this component?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	<?php
	}
	?>
	</table>	
	</article>
</div>
</body>
</html>
