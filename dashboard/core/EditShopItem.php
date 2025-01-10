<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['pid'])) { 
		$pageid = $db->intcast($_REQUEST['pid']);
	}
	
	if(!isset($pageid)) {
		header("location: ../error/4/");
		exit;
	}
	
	if(isset($_POST['csrf'])) {
		
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_POST['id']) && !empty($_POST['id'])) {
				
				if(isset($_FILES['product_image']) && !empty($_FILES['product_image'])) {
					$name = $cryptography->uniqueID() . '-'. basename($_FILES['product_image']["name"]);
					if(!empty($name)) {
						if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {						
							if ($_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
								$tmp_name = $_FILES['product_image']["tmp_name"];
								move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
								$product_image = $name;
							}
						}
					}
				}

				if(isset($_FILES['product_image_2']) && !empty($_FILES['product_image_2'])) {
					$name = $cryptography->uniqueID() . '-'. basename($_FILES['product_image_2']["name"]);
					if(!empty($name)) {
						if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {						
							if ($_FILES['product_image_2']['error'] == UPLOAD_ERR_OK) {
								$tmp_name = $_FILES['product_image_2']["tmp_name"];
								move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
								$product_image_2 = $name;
							}
						}
					}
				}

				if(isset($_FILES['product_image_3']) && !empty($_FILES['product_image_3'])) {
					$name = $cryptography->uniqueID() . '-'. basename($_FILES['product_image_3']["name"]);
					if(!empty($name)) {
						if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {						
							if ($_FILES['product_image_3']['error'] == UPLOAD_ERR_OK) {
								$tmp_name = $_FILES['product_image_3']["tmp_name"];
								move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
								$product_image_3 = $name;
							}
						}
					}
				}

				if(isset($_FILES['featured_image']) && !empty($_FILES['featured_image'])) {
					$name = $cryptography->uniqueID() . '-'. basename($_FILES['featured_image']["name"]);
					if(!empty($name)) {
						if(stripos($name,'.png',-4) || stripos($name,'.jpg',-4) || stripos($name,'.gif',-4)) {						
							if ($_FILES['featured_image']['error'] == UPLOAD_ERR_OK) {
								$tmp_name = $_FILES['featured_image']["tmp_name"];
								move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
								$featured_image = $name;
							}
						}
					}
				}
				
				$id 					  	= $db->clean($_POST["id"],'encode');
				$product_featured 		  	= '';
				$product_featured_carousel	= '';
				$product_title 			  	= $db->clean($_POST["product_title"],'encode');
				$product_description 	  	= $db->clean($_POST["product_description"],'encode'); 
				$product_price 			  	= $db->clean($_POST["product_price"],'encode');
				$product_stock 			  	= $db->clean($_POST["product_stock"],'encode');
				$product_category 		  	= $db->clean($_POST["product_category"],'encode'); 
				$product_catno 			  	= $db->clean($_POST["product_catno"],'encode');
				$product_format 		  	= $db->clean($_POST["product_format"],'encode'); 
				$product_type 			  	= $db->clean($_POST["product_type"],'encode'); 
				$product_weight 		  	= $db->clean($_POST["product_weight"],'encode'); 
				$product_condition		  	= $db->clean($_POST["product_condition"],'encode'); 
				$product_ean 			  	= $db->clean($_POST["product_ean"],'encode');
				
				if(isset($_POST["product_featured"])) { 
					$product_featured		  	= $db->clean($_POST["product_featured"],'encode');
				}
				
				if(isset($_POST["product_featured_carousel"])) {
					$product_featured_carousel 	= $db->clean($_POST["product_featured_carousel"],'encode');
				}
				
				$table    = 'shop';
				
				$columns  = ['product.title','product.description','product.price','product.stock','product.category','product.catno','product.format','product.type','product.weight','product.condition','product.ean','product.featured','product.featured.carousel'];
				$values   = [$product_title,$product_description,$product_price,$product_stock,$product_category,$product_catno,$product_format,$product_type,$product_weight,$product_condition,$product_ean,$product_featured,$product_featured_carousel];
			
				if(isset($product_image)) { 
					array_push($columns, 'product.image');
					array_push($values, $product_image);
				}
				
				if(isset($product_image_2)) { 
					array_push($columns, 'product.image.2');
					array_push($values, $product_image_2);
				}
				
				if(isset($product_image_3)) { 
					array_push($columns, 'product.image.3');
					array_push($values, $product_image_3);
				}
				
				if(isset($featured_image)) { 
					array_push($columns, 'product.featured.image');
					array_push($values, $featured_image);
				}				
				// update shop item
				$db->update($table,$columns,$values,$id);
				$success = "Shop item successfully updated.";
			}
		}
	}
	
	$table    = 'shop';
	$column   = 'id';
	$value    =  $pageid;
	$operator = '*';
	$result = $db->select($table,$operator,$column,$value);
	$result_categories = $db->query("SELECT * from `shop.categories` ORDER BY id DESC"); 
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
	<nav class="nav">
	/ index / edit shop item <input type="submit" onclick="plainui.post();" class="btn" name="update shop item" value="update shop item" />
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

	<?php 
		clearstatcache();
		$image = "../../" . UPLOAD_DIR . $result[0]["product.image"];
		if($result[0]["product.image"] =='') {
			$image = "../../" . UPLOAD_DIR ."thumb.png";
			} elseif(file_exists(UPLOAD_DIR . $result[0]["product.image"]) == false) {
			$image = "../../" . UPLOAD_DIR ."thumb.png";
		} else {
			$image = "../../" . UPLOAD_DIR . $result[0]["product.image"];
		}
	?>
	<h1><div name="" class="clearfix" contentEditable="true" id="titleeditor" oninput="plainui.proc('titleeditor','product.title');"><?php echo $db->clean($result[0]['product.title'],'encode');?></div></h1>
	<input type="hidden" name="product.title" id="product.title" value="<?php echo $db->clean($result[0]['product.title'],'encode');?>"  />
	<img src="<?php echo $image;?>" width="262" class="component-image"/>
	<input type="file" id="files" name="product.image" class="component-upload" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="product.image.2" class="component-upload" accept=".png,.jpg,.gif"/>
	<input type="file" id="files" name="product.image.3" class="component-upload" accept=".png,.jpg,.gif"/>
	<textarea name="product.description" id="product.description" class="textarea"><?php echo $db->clean($result[0]['product.description'],'encode');?></textarea>
	<input type="hidden" name="id" value="<?php echo $db->intcast($result[0]['id']);?>"  />
	<div name="component_text" style="overflow-y:scroll;" id="texteditor" oninput="plainui.proc('texteditor','product.description');" contentEditable="true" name="post-message" class="texteditor" placeholder="Write..."><?php echo $result[0]['product.description'];?></div>
	<div class="shop-entry-box-0">
	
		<div>
			<label>Price</label>
			<input type="number" name="product.price" value="<?php echo $db->clean($result[0]['product.price'],'encode');?>" class="shop-list-item">
			</div>
			<div>
			<label>Stock</label>
			<input type="number" name="product.stock" value="<?php echo $db->clean($result[0]['product.stock'],'encode');?>" class="shop-list-item">
			</div>
			<div>
			<label>Category</label>
			<select name="product.category" class="select">
			<?php
			for($i=0;$i<count($result_categories);$i++) {
				if($result_categories[$i]['category.name'] == $result[0]['product.category']) {
					echo "<option value=\"".$db->clean($result_categories[$i]['category.name'],'encode')."\" selected>".$db->clean($result_categories[$i]['category.name'],'encode')."</option>";
					} else {
					echo "<option value=\"".$db->clean($result_categories[$i]['category.name'],'encode')."\">".$db->clean($result_categories[$i]['category.name'],'encode')."</option>";
				}
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
			<input type="checkbox" name="product.featured" value="1" class="shop-list-item" <?php if($db->clean($result[0]['product.featured'],'encode') == '1') { echo "checked"; } ?>>
			</div>
			<div>
			<label>Carousel</label>
			<input type="checkbox" name="product.featured.carousel" value="1" class="shop-list-item" <?php if($db->clean($result[0]['product.featured.carousel'],'encode') == '1') { echo "checked"; } ?>>
			</div>
			<div>
			<label>Featured Image</label>
			<input type="file" name="featured.image" id="featured-upload" accept=".png,.jpg,.gif" class="shop-list-item"/>
			</div>
		</div>
		<div class="shop-entry-box">
			<div>
			<label>Catno.</label>
			<input type="text" name="product.catno" value="<?php echo $db->clean($result[0]['product.catno'],'encode');?>" class="shop-list-item">
			<label>Format</label>
			<input type="text" name="product.format" value="<?php echo $db->clean($result[0]['product.format'],'encode');?>" class="shop-list-item">
			<label>Type</label>
			<input type="text" name="product.type" value="<?php echo $db->clean($result[0]['product.type'],'encode');?>" class="shop-list-item">
			</div>
			<div>
			<label>Weight</label>
			<input type="text" name="product.weight" value="<?php echo $db->clean($result[0]['product.weight'],'encode');?>" class="shop-list-item">
			<label>Condition</label>
			<input type="text" name="product.condition" value="<?php echo $db->clean($result[0]['product.condition'],'encode');?>" class="shop-list-item">
			<label>EAN</label>
			<input type="text" name="product.ean" value="<?php echo $db->clean($result[0]['product.ean'],'encode');?>" class="shop-list-item">
			</div>
		</div>
		<br/>
	</div>
	</article>
	</form>
</div>
</body>
</html>
