<?php
	include("Header.php");
	
	
	if(isset($_POST['csrf'])) {
		
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_POST['add']) && !empty($_POST['add'])) {
				
				if(isset($_FILES['product_image'])) {
					$name = Upload::image($_FILES['product_image']['name'],$_FILES['product_image']['tmp_name'],$_FILES['product_image']['error'],$cryptography->uniqueID().'-');
					if($name !== false) {
						$product_image = $name;
					}
				}

				if(isset($_FILES['product_image_2'])) {
					$name = Upload::image($_FILES['product_image_2']['name'],$_FILES['product_image_2']['tmp_name'],$_FILES['product_image_2']['error'],$cryptography->uniqueID().'-');
					if($name !== false) {
						$product_image_2 = $name;
					}
				}

				if(isset($_FILES['product_image_3'])) {
					$name = Upload::image($_FILES['product_image_3']['name'],$_FILES['product_image_3']['tmp_name'],$_FILES['product_image_3']['error'],$cryptography->uniqueID().'-');
					if($name !== false) {
						$product_image_3 = $name;
					}
				}

				if(isset($_FILES['featured_image'])) {
					$name = Upload::image($_FILES['featured_image']['name'],$_FILES['featured_image']['tmp_name'],$_FILES['featured_image']['error'],$cryptography->uniqueID().'-');
					if($name !== false) {
						$featured_image = $name;
					}
				}
				
				$product_featured 		  	= 0;
				$product_featured_carousel	= 0;
				$product_title 			  	= $_POST["product_title"];
				$product_description 	  	= $_POST["product_description"]; 
				$product_price 			  	= (float) $_POST["product_price"];
				$product_stock 			  	= $db->intcast($_POST["product_stock"]);
				$product_category 		  	= $_POST["product_category"]; 
				$product_catno 			  	= $_POST["product_catno"];
				$product_format 		  	= $_POST["product_format"]; 
				$product_type 			  	= $_POST["product_type"]; 
				$product_weight 		  	= $_POST["product_weight"]; 
				$product_condition		  	= $_POST["product_condition"]; 
				$product_ean 			  	= $_POST["product_ean"];
				
				if(isset($_POST["product_featured"])) { 
					$product_featured		  	= 1;
				}
				
				if(isset($_POST["product_featured_carousel"])) {
					$product_featured_carousel 	= 1;
				}
				
				$table    = 'shop';
				
				$columns  = ['`product_title`','`product_description`','`product_price`','`product_stock`','`product_category`','`product_catno`','`product_format`','`product_type`','`product_weight`','`product_condition`','`product_ean`','`product_featured`','`product_featured_carousel`'];
				$values   = [$product_title,$product_description,$product_price,$product_stock,$product_category,$product_catno,$product_format,$product_type,$product_weight,$product_condition,$product_ean,$product_featured,$product_featured_carousel];
			
				if(isset($product_image)) { 
					array_push($columns, '`product_image`');
					array_push($values, $product_image);
				}
				
				if(isset($product_image_2)) { 
					array_push($columns, '`product_image_2`');
					array_push($values, $product_image_2);
				}
				
				if(isset($product_image_3)) { 
					array_push($columns, '`product_image_3`');
					array_push($values, $product_image_3);
				}
				
				if(isset($featured_image)) { 
					array_push($columns, '`product_featured_image`');
					array_push($values, $featured_image);
				}				
				// create shop item
				$db->insert($table,$columns,$values);
				$success = "Shop item successfully created.";
			}
		}
	}
	$result_categories = $db->query("SELECT * from `shop_categories` ORDER BY id DESC");
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
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="add" value="1" />
	<nav class="nav">
	/ index / add shop item <input type="submit" onclick="plainui.post();" class="btn" name="add shop item" value="add shop item" />
	</nav>
	<article class="main">
	<?php
	if(isset($errors)) {
		echo "<div id=\"dialog-alert\">".$errors."</div>";
	}
	if(isset($success)) {
		echo "<div id=\"dialog-success\">".$success."</div>";
	}
	$image = UPLOAD_DIR . "thumb.png";
	?>
	<h1><div name="" class="clearfix" contentEditable="true" id="titleeditor" oninput="plainui.text('titleeditor','product_title');">Title</div></h1>
	<input type="hidden" name="product_title" id="product_title" value=""  />
	<img src="<?php echo $image;?>" width="262" class="component-image"/>
	<input type="file" id="files" name="product_image" class="component-upload" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="product_image_2" class="component-upload" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="product_image_3" class="component-upload" accept=".png,.jpg,.gif"/>
	<textarea name="product_description" id="product_description" class="textarea"></textarea>
	<div name="component_text" style="overflow-y:scroll;" id="texteditor" oninput="plainui.proc('texteditor','product_description');" contentEditable="true" name="post-message" class="texteditor" placeholder="Write...">Text</div>
	<div class="shop-entry-box-0">
	
		<div>
			<label>Price</label>
			<input type="number" name="product_price" step="any" value="" class="shop-list-item" min="1" required>
			</div>
			<div>
			<label>Stock</label>
			<input type="number" name="product_stock" value="" class="shop-list-item" min="1" required>
			</div>
			<div>
			<label>Category</label>
			<select name="product_category" class="select">
			<?php
			for($i=0;$i<count($result_categories);$i++) {
				echo "<option value=\"".$db->clean($result_categories[$i]['category_name'],'encode')."\">".$db->clean($result_categories[$i]['category_name'],'encode')."</option>";
			}
			?>
			</select>
		</div>
	</div>
	<div id="show-more" onclick="plainui.show('shop-items-more');">Show more options</div>
	<div class="shop-items-more" id="shop-items-more">
		<div class="shop-entry-box">
			<div>
			<label>Featured</label>
			<input type="checkbox" name="product_featured" value="1" class="shop-list-item">
			</div>
			<div>
			<label>Carousel</label>
			<input type="checkbox" name="product_featured_carousel" value="1" class="shop-list-item">
			</div>
			<div>
			<label>Featured Image</label>
			<input type="file" name="featured.image" id="featured-upload" accept=".png,.jpg,.gif" class="shop-list-item"/>
			</div>
		</div>
		<div class="shop-entry-box">
			<div>
			<label>Catno.</label>
			<input type="text" name="product_catno" value="" class="shop-list-item">
			<label>Format</label>
			<input type="text" name="product_format" value="" class="shop-list-item">
			<label>Type</label>
			<input type="text" name="product_type" value="" class="shop-list-item">
			</div>
			<div>
			<label>Weight</label>
			<input type="text" name="product_weight" value="" class="shop-list-item">
			<label>Condition</label>
			<input type="text" name="product_condition" value="" class="shop-list-item">
			<label>EAN</label>
			<input type="text" name="product_ean" value="" class="shop-list-item">
			</div>
		</div>
		<br/>
	</div>
	</article>
	</form>
</div>
</body>
</html>
