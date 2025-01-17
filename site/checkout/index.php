<?php 

	session_start();
	
	require("../../dashboard/configuration.php");
	include("../../dashboard/resources/PHP/Class.DB.php");
	include("../../dashboard/resources/PHP/Cryptography.php");
	include("../../resources/PHP/Artisan.php");
	
	$cryptography 	= new Cryptography;
	$db 			= new sql();
	$shop 			= new Artisan();
	
	$pages 			= $db->query("SELECT * FROM `pages` order by ordering ASC");
	$menu 			= $db->query("SELECT * FROM `shop.categories` order by `category.order` ASC");
	$settings 		= $db->query("SELECT * from `shop.settings`"); 
	
	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
		} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
	
	$shop->sessioncheck();
	
	if(isset($_SESSION['token']) && isset($_POST['token'])) { 
	
		if($_SESSION['token'] === $_POST['token']) {
			
			if(isset($_POST['product'])) {
				
				$table    		= 'shop';
				$column   		= 'id';
				$value    		=  $shop->intcast($_POST['id']);
				$operator 		= '*';
				$cart_result 	= $db->select($table,$operator,$column,$value);
				
				if(isset($cart_result)) { 
	
					$arr = [
						'product.id' => $shop->intcast($_POST['id']),
						'product.qty' => $shop->intcast($_POST['qty']),
						'product.title' => $shop->clean($cart_result[0]['product.title'],'encode')
					];
					
					$shop->addtocart($arr);
				}
				$_SESSION['cart'] = $shop->unique_array($_SESSION['cart'], 'product.id');
			}
		}
	}
	
	$meta = array();
	$meta[0]['meta_title'] 		 = 'Checkout';
	$meta[0]['meta_description'] = 'This is your shopping cart';
	$meta[0]['meta_tags'] 		 = 'Shopping cart, cart, basket'; 
	
?>

<!DOCTYPE html>
<html>
<head>
<?php
	include("../../components/Header.php");
?>	
</head>
<body>
<?php
	include("../../components/Head.php"); 
	include("../../components/Navigation.php"); 
?>
<article class="shop-cart-main">
	<div class="shop-cart-highlight">
		<div class="shop-cart">
<?php 	
	if(count($_SESSION['cart']) <= 0) {	
?>
	<div class="shop-cart-center">
		<div class="material-symbols-outlined" id="shop-cart-symbols">
		production_quantity_limits
		</div>
	<h2>Shopping cart is empty.</h2>
	</div>
<?php
	} else {
?>
		<form action="pay/" method="post">
		<input type="hidden" name="token" value="<?php echo $token;?>" />
		<div class="shop-nav-h1"><h1>Checkout</h1></div>
				<div class="shop-checkout-block2">
					
						<div class="shop-form-content">
								<div class="shop-form-content-div">
									<label>First name</label>
									<input type="text" name="first_name" size="15" value="" autocomplete="given-name" required>
									<label>Last name</label>
									<input type="text" name="last_name" size="15" value="" autocomplete="family-name" required>
								</div>
								<div class="shop-form-content-div">
									<label>Address</label>
									<input type="text" name="address" value="" autocomplete="street-address" required>
									<div class="shop-form-content-div">
									<label>City</label>
									<input type="text" name="city" value="" autocomplete="address-level2" required>
								</div>
						</div>
								
						<div class="shop-form-content">
								<div class="shop-form-content-div">
									<label>Zip</label>
									<input type="text" name="zip" size="5" value="" autocomplete="postal-code" required>
									<label>State</label>
									<input type="text" name="state" size="3" maxlength="3" value="" autocomplete="address-level1" required>
								</div>
								<div class="shop-form-content-div">
									<label for="email">E-mail</label>
									<input type="text" name="email" size="15" value="" autocomplete="email" required>
									<label>Country</label>
									<select name="country" value="" autocomplete="country-name" required>
										<option value="">Select country</option>
										<option value="afghanistan">afghanistan</option>
										<option value="albania">albania</option>
										<option value="algeria">algeria</option>
										<option value="andorra">andorra</option>
										<option value="angola">angola</option>
										<option value="antigua & deps">antigua & deps</option>
										<option value="argentina">argentina</option>
										<option value="armenia">armenia</option>
										<option value="australia">australia</option>
										<option value="austria">austria</option>
										<option value="azerbaijan">azerbaijan</option>
										<option value="bahamas">bahamas</option>
										<option value="bahrain">bahrain</option>
										<option value="bangladesh">bangladesh</option>
										<option value="barbados">barbados</option>
										<option value="belarus">belarus</option>
										<option value="belgium">belgium</option>
										<option value="belize">belize</option>
										<option value="benin">benin</option>
										<option value="bhutan">bhutan</option>
										<option value="bolivia">bolivia</option>
										<option value="bosnia herzegovina">bosnia herzegovina</option>
										<option value="botswana">botswana</option>
										<option value="brazil">brazil</option>
										<option value="brunei">brunei</option>
										<option value="bulgaria">bulgaria</option>
										<option value="burkina">burkina</option>
										<option value="burundi">burundi</option>
										<option value="cambodia">cambodia</option>
										<option value="cameroon">cameroon</option>
										<option value="canada">canada</option>
										<option value="cape verde">cape verde</option>
										<option value="central african rep">central african rep</option>
										<option value="chad">chad</option>
										<option value="chile">chile</option>
										<option value="china">china</option>
										<option value="colombia">colombia</option>
										<option value="comoros">comoros</option>
										<option value="congo">congo</option>
										<option value="congo democratic rep">congo democratic rep</option>
										<option value="costa rica">costa rica</option>
										<option value="croatia">croatia</option>
										<option value="cuba">cuba</option>
										<option value="cyprus">cyprus</option>
										<option value="czech republic">czech republic</option>
										<option value="denmark">denmark</option>
										<option value="djibouti">djibouti</option>
										<option value="dominica">dominica</option>
										<option value="dominican republic">dominican republic</option>
										<option value="east timor">east timor</option>
										<option value="ecuador">ecuador</option>
										<option value="egypt">egypt</option>
										<option value="el salvador">el salvador</option>
										<option value="equatorial guinea">equatorial guinea</option>
										<option value="eritrea">eritrea</option>
										<option value="estonia">estonia</option>
										<option value="ethiopia">ethiopia</option>
										<option value="fiji">fiji</option>
										<option value="finland">finland</option>
										<option value="france">france</option>
										<option value="gabon">gabon</option>
										<option value="gambia">gambia</option>
										<option value="georgia">georgia</option>
										<option value="germany">germany</option>
										<option value="ghana">ghana</option>
										<option value="greece">greece</option>
										<option value="grenada">grenada</option>
										<option value="guatemala">guatemala</option>
										<option value="guinea">guinea</option>
										<option value="guinea-bissau">guinea-bissau</option>
										<option value="guyana">guyana</option>
										<option value="haiti">haiti</option>
										<option value="honduras">honduras</option>
										<option value="hungary">hungary</option>
										<option value="iceland">iceland</option>
										<option value="india">india</option>
										<option value="indonesia">indonesia</option>
										<option value="iran">iran</option>
										<option value="iraq">iraq</option>
										<option value="ireland republic">ireland republic</option>
										<option value="israel">israel</option>
										<option value="italy">italy</option>
										<option value="ivory coast">ivory coast</option>
										<option value="jamaica">jamaica</option>
										<option value="japan">japan</option>
										<option value="jordan">jordan</option>
										<option value="kazakhstan">kazakhstan</option>
										<option value="kenya">kenya</option>
										<option value="kiribati">kiribati</option>
										<option value="korea north">korea north</option>
										<option value="korea south">korea south</option>
										<option value="kosovo">kosovo</option>
										<option value="kuwait">kuwait</option>
										<option value="kyrgyzstan">kyrgyzstan</option>
										<option value="laos">laos</option>
										<option value="latvia">latvia</option>
										<option value="lebanon">lebanon</option>
										<option value="lesotho">lesotho</option>
										<option value="liberia">liberia</option>
										<option value="libya">libya</option>
										<option value="liechtenstein">liechtenstein</option>
										<option value="lithuania">lithuania</option>
										<option value="luxembourg">luxembourg</option>
										<option value="macedonia">macedonia</option>
										<option value="madagascar">madagascar</option>
										<option value="malawi">malawi</option>
										<option value="malaysia">malaysia</option>
										<option value="maldives">maldives</option>
										<option value="mali">mali</option>
										<option value="malta">malta</option>
										<option value="marshall islands">marshall islands</option>
										<option value="mauritania">mauritania</option>
										<option value="mauritius">mauritius</option>
										<option value="mexico">mexico</option>
										<option value="micronesia">micronesia</option>
										<option value="moldova">moldova</option>
										<option value="monaco">monaco</option>
										<option value="mongolia">mongolia</option>
										<option value="montenegro">montenegro</option>
										<option value="morocco">morocco</option>
										<option value="mozambique">mozambique</option>
										<option value="myanmar">myanmar</option>
										<option value="burma">burma</option>
										<option value="namibia">namibia</option>
										<option value="nauru">nauru</option>
										<option value="nepal">nepal</option>
										<option value="netherlands">netherlands</option>
										<option value="new zealand">new zealand</option>
										<option value="nicaragua">nicaragua</option>
										<option value="niger">niger</option>
										<option value="nigeria">nigeria</option>
										<option value="norway">norway</option>
										<option value="oman">oman</option>
										<option value="pakistan">pakistan</option>
										<option value="palau">palau</option>
										<option value="panama">panama</option>
										<option value="papua new guinea">papua new guinea</option>
										<option value="paraguay">paraguay</option>
										<option value="peru">peru</option>
										<option value="philippines">philippines</option>
										<option value="poland">poland</option>
										<option value="portugal">portugal</option>
										<option value="qatar">qatar</option>
										<option value="romania">romania</option>
										<option value="russian federation">russian federation</option>
										<option value="rwanda">rwanda</option>
										<option value="st kitts & nevis">st kitts & nevis</option>
										<option value="st lucia">st lucia</option>
										<option value="saint vincent & the grenadines">saint vincent & the grenadines</option>
										<option value="samoa">samoa</option>
										<option value="san marino">san marino</option>
										<option value="sao tome & principe">sao tome & principe</option>
										<option value="saudi arabia">saudi arabia</option>
										<option value="senegal">senegal</option>
										<option value="serbia">serbia</option>
										<option value="seychelles">seychelles</option>
										<option value="sierra leone">sierra leone</option>
										<option value="singapore">singapore</option>
										<option value="slovakia">slovakia</option>
										<option value="slovenia">slovenia</option>
										<option value="solomon islands">solomon islands</option>
										<option value="somalia">somalia</option>
										<option value="south africa">south africa</option>
										<option value="south sudan">south sudan</option>
										<option value="spain">spain</option>
										<option value="sri lanka">sri lanka</option>
										<option value="sudan">sudan</option>
										<option value="suriname">suriname</option>
										<option value="swaziland">swaziland</option>
										<option value="sweden">sweden</option>
										<option value="switzerland">switzerland</option>
										<option value="syria">syria</option>
										<option value="taiwan">taiwan</option>
										<option value="tajikistan">tajikistan</option>
										<option value="tanzania">tanzania</option>
										<option value="thailand">thailand</option>
										<option value="togo">togo</option>
										<option value="tonga">tonga</option>
										<option value="trinidad & tobago">trinidad & tobago</option>
										<option value="tunisia">tunisia</option>
										<option value="turkey">turkey</option>
										<option value="turkmenistan">turkmenistan</option>
										<option value="tuvalu">tuvalu</option>
										<option value="uganda">uganda</option>
										<option value="ukraine">ukraine</option>
										<option value="united arab emirates">united arab emirates</option>
										<option value="united kingdom">united kingdom</option>
										<option value="united states">united states</option>
										<option value="uruguay">uruguay</option>
										<option value="uzbekistan">uzbekistan</option>
										<option value="vanuatu">vanuatu</option>
										<option value="vatican city">vatican city</option>
										<option value="venezuela">venezuela</option>
										<option value="vietnam">vietnam</option>
										<option value="yemen">yemen</option>
										<option value="zambia">zambia</option>									
									</select>
								</div>
						</div>
				</div>
				</div>
				<div id="shop-checkout-submit">				
					<input type="submit" id="submit" name="submit" value="Pay Now"/>
				</div>
		</form>
	<?php 
	}
	?>
	</div>
</div>
</article>
<?php		
	include("../../components/Footer.php"); 
	include("../../components/Scripts.php"); 
?>
</body>
</html>