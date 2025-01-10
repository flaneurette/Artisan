<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['token'])) {
		if($_REQUEST['token'] === $_SESSION['uuid']) {
			
			if(isset($_REQUEST['delete'])) {			
				$result = $db->delete('`shop.orders`',$db->intcast($_REQUEST['delete']));
			}

			if(isset($_REQUEST['fulfill'])) {
				$table    = '`shop.orders`';
				$columns  = ['order.fulfilled','order.status'];
				$values   = [1,'paid'];
				$db->update($table,$columns,$values,$db->intcast($_REQUEST['fulfill']));
			}
		}
	}

	$orders_unfulfilled = $db->query("SELECT * from `shop.orders` WHERE `order.fulfilled` != '1' order by id DESC"); 
	$orders_fulfilled 	= $db->query("SELECT * from `shop.orders` WHERE `order.fulfilled` = '1' order by id DESC"); 
	$settings 			= $db->query("SELECT * from `shop.settings`"); 
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
	/ index / shop orders 
	</nav>
	<article class="main">
	<label>New orders</label>
	<table rowspan="" width="100%" class="table-list">
	<tr>
	<td>Id</td>
	<td>Date</td>
	<td>Status</td>
	<td>Total</td>
	<td>Fulfill</td>
	<td>Detail</td>
	<td>X</td>
	</tr>
	<?php 
		for($i=0;$i<count($orders_unfulfilled);$i++){
			
			if($i % 2 !== 0) { 
				$color = "background-color: var(--lightgray);";
				} else {
				$color = "";
			}	
			
			$order_id = explode('-',$orders_unfulfilled[$i]['order.id']);
	?>
		<tr style="<?php echo $color;?>">
		<td><small><?php echo $db->clean($order_id[0],'encode');?></small></td>
		<td><small><?php echo date("M tS Y H:i",$db->clean($orders_unfulfilled[$i]['order.date'],'encode'));?></small></td>
		<td><small><?php echo $db->clean($orders_unfulfilled[$i]['order.status'],'encode');?></small></td>
		<td><small><?php echo $db->clean($settings[0]['settings.currency'],'encode').number_format($db->intcast($orders_unfulfilled[$i]['order.total']),2);?></small></td>
		<td><a href="?fulfill=<?php echo $db->clean($orders_unfulfilled[$i]['id'],'encode');?>&token=<?php echo $token;?>" target="_self" onclick="return confirm('Are you sure you want to fulfill this order and mark it as shipped?');"><span class="material-symbols-outlined">published_with_changes</span></a></td>
		<td><a href="order-pick/<?php echo $db->intcast($orders_unfulfilled[$i]['id']);?>/<?php echo $token;?>/" target="_blank"><span class="material-symbols-outlined">contract</span></a></td>
		<td><a href="<?php echo SITE . 'shop-orders/'.$token;?>/delete/<?php echo $db->intcast($orders_unfulfilled[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this order?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	<?php
	}
	?>
	</table>
	<?php
	if(isset($orders_fulfilled) && count($orders_fulfilled) >0) {
	?>
	<label>Fulfilled orders</label>
	<table rowspan="" width="100%" class="table-list">
	<tr>
	<td>Id</td>
	<td>Date</td>
	<td>Status</td>
	<td>Total</td>
	<td>Fulfill</td>
	<td>Detail</td>
	<td>X</td>
	</tr>
	<?php 
		for($i=0;$i<count($orders_fulfilled);$i++){
			
			if($i % 2 !== 0) { 
				$color = "background-color: var(--lightgray);";
				} else {
				$color = "";
			}	
			
			$order_id = explode('-',$orders_fulfilled[$i]['order.id']);
	?>
		<tr style="<?php echo $color;?>">
		<td><small><?php echo $db->clean($order_id[0],'encode');?></small></td>
		<td><small><?php echo date("M tS Y H:i",$db->clean($orders_fulfilled[$i]['order.date'],'encode'));?></small></td>
		<td><small><?php echo $db->clean($orders_fulfilled[$i]['order.status'],'encode');?></small></td>
		<td><small><?php echo $db->clean($settings[0]['settings.currency'],'encode').number_format($db->intcast($orders_fulfilled[$i]['order.total']),2);?></small></td>
		<td>Fulfilled</td>
		<td><a href="order-pick/<?php echo $db->intcast($orders_fulfilled[$i]['id']);?>/<?php echo $token;?>/" target="_blank"><span class="material-symbols-outlined">contract</span></a></td>
		<td width="80"><a href="<?php echo SITE . 'shop-orders/'.$token;?>/delete/<?php echo $db->intcast($orders_fulfilled[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this order?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	<?php 
	}
	?>
	</article>
	</form>
</div>
</body>
</html>
