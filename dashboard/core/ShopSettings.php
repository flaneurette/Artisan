<?php
	include("Header.php");
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_REQUEST['settings_email']) && !empty($_REQUEST['settings_email'])) {
				$id = 1;
				$table    = '`shop_settings`';
				$columns  = ['settings_announcement','settings_email','settings_currency','settings_country_code','settings_free','settings_shipping','settings_mollie_api','settings_theme'];
				$values   = [$_POST['settings_announcement'],$_POST['settings_email'],$_POST['settings_currency'],$_POST['settings_currency_code'],(float) $_POST['settings_free'],(float) $_POST['settings_shipping'],$_POST['settings_mollie_api'],$_POST['settings_theme']];
				$db->update($table,$columns,$values,$id);
				$success = "Settings successfully changed.";
			}
		}
	}

	$settings = $db->query("SELECT * from `shop_settings`"); 
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
	/ index / shop settings <input type="submit" onclick="plainui.post();" class="btn" value="save" />
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
	<label>Shop Announcement: </label><input type="text" name="settings_announcement" value="<?php echo $db->clean($settings[0]['settings_announcement'],'encode');?>">
	<label>Theme: </label>
	<select name="settings_theme" class="select">
		<option value="white" <?php if($db->clean($settings[0]['settings_theme'],'encode') =='white') { echo "selected"; }?>>White theme design</option>
		<option value="black" <?php if($db->clean($settings[0]['settings_theme'],'encode') =='black') { echo "selected"; }?>>Black theme design</option>
		<option value="paper" <?php if($db->clean($settings[0]['settings_theme'],'encode') =='paper') { echo "selected"; }?>>Paper theme design</option>
		<option value="custom" <?php if($db->clean($settings[0]['settings_theme'],'encode') =='custom') { echo "selected"; }?>>Custom theme design</option>
	</select>
	<hr>
	<label>Shop E-mail: </label><input type="text" name="settings_email" value="<?php echo $db->clean($settings[0]['settings_email'],'encode');?>" required>
	<!-- <label>Paypal E-mail: </label> <input type="text" name="settings_paypal" value="<?php echo $db->clean($settings[0]['settings_paypal'],'encode');?>" required> -->
	<label>Mollie API key: </label> <input type="text" name="settings_mollie_api" value="<?php echo $db->clean($settings[0]['settings_mollie_api'],'encode');?>" required>	
	<label>Currency: </label> <input type="text" name="settings_currency" value="<?php echo $db->clean($settings[0]['settings_currency'],'encode');?>" required>
	<label>Currency code: </label> <input type="text" name="settings_currency_code" minlength="3" maxlength="3" value="<?php echo $db->clean($settings[0]['settings_country_code'],'encode');?>" required>
	<!-- <label>Tax: </label> <input type="text" name="settings_tax" value="<?php echo $db->clean($settings[0]['settings_tax'],'encode');?>" required> -->
	<label>Shipping cost: </label> <input type="text" name="settings_shipping" value="<?php echo $db->clean($settings[0]['settings_shipping'],'encode');?>" required>
	<label>Free shipping above: </label> <input type="text" name="settings_free" value="<?php echo $db->clean($settings[0]['settings_free'],'encode');?>" required>
	</article>
	</form>
</div>
</body>
</html>
