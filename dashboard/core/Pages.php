<?php
	require("../configuration.php");
	include("Header.php");

	if(isset($_REQUEST['delete'])) {
		if($_SESSION['uuid'] === $_REQUEST['csrf']) {			
			$result = $db->delete('pages',$db->intcast($_REQUEST['delete']));
		}
	}
	
	$result = $db->query("SELECT * from pages ORDER BY ordering ASC"); 
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
	/ pages
	</nav>
	<article class="main">
	<label>Pages</label>
	<table rowspan="" width="100%" class="table-list">
	<?php 
	for($i=0;$i<count($result);$i++){
		if($i % 2 !== 0) { 
			$color = "background-color: var(--lightgray);";
			} else {
			$color = "";
		}	
	?>
		<tr style="<?php echo $color;?>">
		<td><a href="<?php echo SITE;?>pages/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['page_name'];?></a></td>
		<td><a href="<?php echo SITE;?>pages/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><span class="material-symbols-outlined">edit</span></a></td>
		<td><a target="_blank" href="<?php echo SITE;?>API.php?catid=<?php echo $db->intcast($result[$i]['id']);?>"><span class="material-symbols-outlined">database</span></a></td>
		<td width="500"></td>
		<td width="80"><a href="<?php echo SITE . 'pages/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this item?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	</article>
</div>
</body>
</html>
