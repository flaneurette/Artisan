<?php

class Artisan {

	const MINMAX 	= 10000; // Constants. 
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
	
	function unique_array($array, $needle=false) {
		
		if(is_array($array)) {
			
			$arraynew = [];
			$c = count($array);
			$i=0;
			foreach($array as $key => $value) {
				if($needle) {
					if(!in_array($array[$key][$needle],array_column($arraynew,$needle))) {
						array_push($arraynew,$array[$i]);
					}
				} else {
					if(!in_array($array[$i],$arraynew)) {
					    array_push($arraynew,$array[$i]);
					}		        
				}
			 $i++;
			}
			
		return $arraynew;
		} else {
		return false;
		}
	}

	public function sessioncheck() 
	{ 
		if(isset($_SESSION['cart'])) {
			if(isset($_SESSION['cart'][0])) {
				if($_SESSION['cart'][0] === NULL || $_SESSION['cart'] === NULL ) {
					$_SESSION['cart'] = [];
				}
			}
		}
		return true;
	}
	
	public function sessioncount()  
	{
		$c = 0;
		if(isset($_SESSION['cart'])) {
			if(isset($_SESSION['cart'][0])) {
				$c = count($_SESSION['cart']);
			} else {}
		} 
		return $c;
	}
	
	public function addtocart($obj) 
	{ 
		
		$c = $this->sessioncount();
		
		if($obj['product.qty'] > 200) {
			$obj['product.qty'] = 1;
		}
	
		if(isset($_SESSION['cart'])) {
			$_SESSION['cart'] = $this->unique_array($_SESSION['cart'], 'product.id');
		} else {
			$_SESSION['cart'] = [];
			$_SESSION['cart'] = $this->unique_array($_SESSION['cart'], 'product.id');
		}
		
		if($c > 0 ) { 

			for($i = 0; $i < $c; $i++) {
				
					if(!isset($_SESSION['cart'][$i]['product.id'])) {
						return 'Session could not be initialized due to offset error. Please reload the page.';
					}
		
					if($_SESSION['cart'][$i]['product.id'] == $obj['product.id']) {
						
						if($obj['product.qty'] < 1) {
							$obj['product.qty'] = 0;
							} elseif($obj['product.qty'] > 200) {
							$obj['product.qty'] = 1;
						} else {}
						
						if(($_SESSION['cart'][$i]['product.qty'] + $obj['product.qty']) > 200) {
						} else {
						
						$_SESSION['cart'][$i]['product.qty'] = ($_SESSION['cart'][$i]['product.qty'] + $obj['product.qty']);
						}
						} else {
						array_push($_SESSION['cart'],$obj);
					}
			}
			
		} else {
			$_SESSION['cart'] = [];
			array_push($_SESSION['cart'],$obj);
		}

		return true;
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
							
							if($array[$i]['product.id'] == $needle) {
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
			
			if($array[$i]['product.id'] == $id) {
				$array[$i]['product.qty'] = (int) $qty;
			}
			
			$i++;
		}
		
		return $array;
	}
		

	public function getcart() 
	{ 
			if(isset($_SESSION['cart'])) { 

				$array = [];
				
				foreach($_SESSION['cart'] as $item) { 
					array_push($array,cleanArray($item));
				} 
				
			}  else {
				$_SESSION['cart'] = array();
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
		$find 		= [' ','_','=','+','&','.'];
		$replace 	= ['-','-','-','-','-','-'];
		$string 	= str_replace($find,$replace,strtolower($string));
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
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
				$data =  htmlspecialchars($string,ENT_QUOTES,'UTF-8');
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