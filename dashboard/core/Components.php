<?php
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	session_regenerate_id();

	require("../configuration.php");
	include("../resources/PHP/Class.DB.php");
	include("../core/Cryptography.php");
	
	// login check
	if(!isset($_SESSION['loggedin'])) {
		header("Location: ".SITE."login/");
		exit;	
	}
	if($_SESSION['loggedin'] != '1') {
		header("Location: login/");
		exit;
	} 
	
	$cryptography = new Cryptography;
	$db = new sql();
	
	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
	} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
	
	$_SESSION['admin-uuid'] = $cryptography->uniqueID();
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		header("location: ../error/3/");
		exit;
	}
	
	// create a new admin token
	if(!isset($_SESSION['uuid'])) {
		$token  = $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$_SESSION['uuid'] = $token;		
	} else {
		$token = $_SESSION['uuid'];
	}
	
	if(isset($_REQUEST['delete'])) {
		if($_SESSION['uuid'] === $_REQUEST['csrf']) {			
			$result = $db->delete('components',$db->intcast($_REQUEST['delete']));
		}
		header("Location: ../../../../index.php");
		exit;
	}

	$result 		= $db->query("SELECT * FROM components order by id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../core/Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header">
	<?php include("../core/Navigation.php");?>
	</header>
	<nav class="nav">
	/ Components
	</nav>
	<article class="main">
	<label>Edit components</label>
	<table rowspan="" width="100%" class="table-list">
	<?php 

	for($i=0;$i<count($result);$i++){
		$image = "../../resources/content/" . $result[$i]["component_image"];
		if($result[$i]["component_image"] =='') {
			$image = "../../resources/content/thumb.png";
		} 
		
		if($i % 2 !== 0) { 
			$color = "background-color: var(--lightgray);";
			} else {
			$color = "";
		}
	?>
		<tr style="<?php echo $color;?>">
		<td width="150"><img src="<?php echo $image;?>" width="100"/></td>
		<td valign="top"><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['component_title'];?></a></td>
		<td valign="top"><a href="<?php echo SITE;?>API.php?filetype=unique&id=<?php echo $db->intcast($result[$i]['id']);?>" target="_blank"><span class="material-symbols-outlined">database</span></a></td>
		<td valign="top"></td>
		<td width="50" valign="top"><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><span class="material-symbols-outlined">edit</span></a></td>
		<td width="50" valign="top"><a href="<?php echo SITE . 'components/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/" onclick="return confirm('Are you sure you want to remove this component?');"><span class="material-symbols-outlined">delete</span></a></td>
		</tr>
	<?php
	}
	?>
	</table>	
	</article>
</div>
</body>
</html>
