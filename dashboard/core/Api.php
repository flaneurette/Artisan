<?php
	require("../configuration.php");
	include("Header.php");
	$result = $db->query("SELECT * from pages ORDER BY id ASC"); 
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
	/ API
	</nav>
	<article class="main">
	<label>API</label>
	<table width="100%" class="table-list">
		<?php 
		for($i=0;$i<count($result);$i++){	
			if($i % 2 !== 0) { 
				$color = "background-color: var(--lightgray);";
				} else {
				$color = "";
			}
		?>
		<tr style="<?php echo $color;?>">
		<td width="100"><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['page_name'];?></a></td>
		<td width="400"><a target="_blank" href="<?php echo SITE;?>API.php?catid=<?php echo $db->intcast($result[$i]['id']);?>"><span class="material-symbols-outlined">database</span></a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	</article>
</div>
</body>
</html>
