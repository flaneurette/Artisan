<?php

class Artisan {

	const MINMAX 	= 10000; // Constants. 
	const MAXQTY 	= 200; // Max quantity per product in the cart.
	// Call it within the class:  self::MINMAX
	private $var 	= 0; // Private variable
	
	public function __construct($params = array()) 
	{ 
		$this->init($params);
	}
	
    public function init($params)
    	{
		try {
			isset($params['var'])  ? $this->var  = $params['var'] : false; 
			} catch(Exception $e) {
			$this->message('Problem initializing:'.$e->getMessage());
		}
    }
	
	public function message($string) 
	{
		return $string;
	}	
	
	public function addtocart($obj) 
	{ 
		$qty = (int) $obj['product_qty'];
		
		if($qty < 1 || $qty > self::MAXQTY) {
			$qty = 1;
		}
		
		$obj['product_qty'] = $qty;
	
		if(!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
			$_SESSION['cart'] = [];
		}
		
		foreach($_SESSION['cart'] as $key => $item) {
			if(isset($item['product_id']) && $item['product_id'] == $obj['product_id']) {
				if(($item['product_qty'] + $qty) <= self::MAXQTY) {
					$_SESSION['cart'][$key]['product_qty'] = ($item['product_qty'] + $qty);
				}
				return true;
			}
		}
		
		array_push($_SESSION['cart'],$obj);

		return true;
	} 	
	
	/**
	* Checks the session cart against the shop inventory: removes items that no 
	* longer exist or are out of stock, and limits quantities to 1 - stock.
	* @param object $db
	* @return boolean true if the cart had to be changed.
	*/
	public function validatecart($db) 
	{ 
		$changed = false;
		$cart = [];
		
		if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
			
			foreach($_SESSION['cart'] as $item) {
				
				if(!isset($item['product_id'])) {
					$changed = true;
					continue;
				}
				
				$result = $db->select('shop','*','id',$this->intcast($item['product_id']));
				$max 	= isset($result[0]) ? min((int) $result[0]['product_stock'], self::MAXQTY) : 0;
				$qty 	= (int) $item['product_qty'];
				
				if($max < 1) {
					$changed = true;
					continue;
				}
				
				if($qty < 1) {
					$qty = 1;
					$changed = true;
				} elseif($qty > $max) {
					$qty = $max;
					$changed = true;
				}
				
				$item['product_qty'] = $qty;
				array_push($cart,$item);
			}
		}
		
		$_SESSION['cart'] = $cart;
		
		return $changed;
	}
	
	/**
	* Translates a Mollie payment into the order status Artisan stores.
	* @param object $payment
	* @return string
	*/
	public function paymentstatus($payment) 
	{
		if($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
			return 'paid';
		} elseif($payment->isOpen()) {
			return 'open';
		} elseif($payment->isPending()) {
			return 'pending';
		} elseif($payment->isFailed()) {
			return 'failed';
		} elseif($payment->isExpired()) {
			return 'expired';
		} elseif($payment->isCanceled()) {
			return 'cancelled';
		} elseif($payment->hasRefunds()) {
			return 'refunded';
		} elseif($payment->hasChargebacks()) {
			return 'charged back';
		}
		return 'unknown';
	}
	
	public function deletefromcart($needle=false) {
		
		$array = $_SESSION['cart'];
		
		if($needle != false) {
			if(is_array($array)) {
				$c = count($array); 
				$i=0;
				foreach($array as $key => $value) {
					if($needle) {  
						if(in_array($needle,$array[$i])) {
							
							if($array[$i]['product_id'] == $needle) {
								unset($array[$i]); 
							}
						}
					}
				 $i++;
				}
			}
		}
		
		$array = array_values($array);

		return $array;
	}
	
	
	public function updatecart($id,$qty) {
		
		$array = $_SESSION['cart'];
		
		$i=0;
		
		foreach($array as $key => $value) {
			
			if($array[$i]['product_id'] == $id) {
				$array[$i]['product_qty'] = max(1, min((int) $qty, self::MAXQTY));
			}
			
			$i++;
		}
		
		return $array;
	}
		

	/**
	* SEO-ing URL.
	* @param string
	* @return string
	*/
	public function seoUrl($string) 
	{
		// only what the rewrite rules accept in a URL: a-z, 0-9 and dashes.
		$string = preg_replace('/[^a-z0-9]+/','-',strtolower((string) $string));
		$string = trim($string,'-');
		return ($string === '') ? 'item' : $string;
	}	
	
	/**
	* Reverse SEO URL.
	* @param string
	* @return string
	*/
	public function revSeo($string) 
	{
		if(strlen($string) > 150) {
			$this->message("Error: string length in category is too large.");
			return false;
			exit;
			} else {
			$string = preg_replace('/[^a-zA-Z-0-9\-]/','', $string);
			return str_replace('-',' ',strtolower($string));
		}
	}

	/**
	* Cast integers
	* @param int
	* @return integer
	*/
	public function intcast($int) {
		
		if(strlen($int) > 32) {
			$int = 0;
		}
		
		if(!is_numeric($int)) {
			$int = intval($int);
		}
		
		if(is_string($int)) {
			$this->clean($int,'num');
		}
		
		if($int >=0) {
			$int = intval($int);
			$int = (int)$int;
		}

		return (int)$int;
	}
	
	/**
	* Sanitizes a string or array
	* @param string
	* @return data
	*/
	public function clean($string,$method='') {
		
		$data = '';
		
		switch($method) {
			case 'url':
				$search  = ['\\','/','-','_'];
				$replace = ['',' ',' ',' '];
				$data = str_replace($search,$replace,$string);
			break;
			case 'paths':
				$search  = ['../'];
				$replace = ['',];
				$data = str_replace($search,$replace,$string);
			break;
			case 'navigate':
				$data =  substr($data,0, 30);
				$data =  preg_replace('/[a-zA-Z-0-9\-]+/', '', $string);
				$data =  htmlspecialchars($string,ENT_QUOTES,'UTF-8');
			break;
			case 'alpha':
				$data =  preg_replace('/[a-zA-Z]+/','', $string);
			break;
			case 'num':
				$data =  preg_replace('/[0-9]+/','', $string);
			break;
			case 'unicode':
				$data =  preg_replace("/[[:alnum:][:space:]]/u", '', $string);
			break;
			case 'encode':
				$data =  htmlspecialchars((string) $string,ENT_QUOTES,'UTF-8');
			break;
			case 'html':
				$data = htmlspecialchars($string,ENT_QUOTES,'UTF-8');
				$data = preg_replace(
					array('#href=&quot;(https?://.*?)&quot;#', '#&lt;(/?(?:p|div|span|i|strong|b|a|br|em|u|ul|li|ol)(\shref=".*?")?/?)&gt;#'), 
					array( 'href="\1"', '<\1>' ), $data
				);
			break;
			default:
			return $data;
			}
		return $data;
	}
}

?>