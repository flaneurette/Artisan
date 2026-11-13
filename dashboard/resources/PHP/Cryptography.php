<?php

include_once("Message.php");
include_once("Sanitize.php");

#[AllowDynamicProperties]
class Cryptography {

	CONST MINHASHBYTES = 32; 		// Min. of bytes for secure hash.
	CONST MAXHASHBYTES = 64; 		// Max. of bytes for secure hash, more increases cost. Max. recommended: 256 bytes.
	CONST MINMERSENNE  = 0xff; 		// Min. value of the Mersenne twister.
	CONST MAXMERSENNE  = 0xffffffff; 	// Max. value of the Mersenne twister.
	
	private $pwd 	= 'ArtisanIsAShoppingCart'; // TODO +May 2022: Should be set upon installation
	
	public function __construct($params = array()) 
	{ 
		$this->init($params);
		$this->messages = new Message;
		$this->sanitizer = new Sanitizer;
	}
	
	public function password()
	{
		return $this->pwd;
	}
	
	/**
	* Initializes object.
	* @param array $params
	* @throws Exception
	*/	
	
    	public function init($params)
    	{
			
		try {
			isset($params['var'])  ? $this->var  = $params['var'] : false; 
			} catch(Exception $e) {}
    	}

	public function pseudoNonce($max=0xffffffff) {
		$tmp_nonce = mt_rand(0,$max).mt_rand(0,$max).mt_rand(0,$max).mt_rand(0,$max);
		return $tmp_nonce;
	}
	
	public function getToken() {
		
		$bytes = 0;
		
		if (function_exists('random_bytes')) {
			$len   = mt_rand(self::MINHASHBYTES,self::MAXHASHBYTES);
        		$bytes .= bin2hex(random_bytes($len));
    		}
		if (function_exists('openssl_random_pseudo_bytes')) {
			$len   = mt_rand(self::MINHASHBYTES,self::MAXHASHBYTES);
        		$bytes .= bin2hex(openssl_random_pseudo_bytes($len));
    		}
		
		if(strlen($bytes) < 128) {
			$bytes .= mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE)
				. mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) 
				. mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) 
				. mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE); 
		}
		
		$token = hash('sha512',$bytes);
		
		if(isset($this->token) && $this->token != false) 
		{ 
			if(strlen($this->token) < 128) {
				$this->messages->message('Issue found: session token is too short.'); 
				} else {
				return $this->sanitizer->sanitize($this->token,'alphanum'); 
			}
		} else { 
		return $token;
		} 
	} 

	public function uniqueID() {
		
		$len_id 	= 0;
		$bytes_id 	= 0;
		
		if (function_exists('random_bytes')) {
			$len   = mt_rand(self::MINHASHBYTES,self::MAXHASHBYTES);
        		$bytes_id .= bin2hex(random_bytes($len));
    		}
		if (function_exists('openssl_random_pseudo_bytes')) {
			$len   = mt_rand(self::MINHASHBYTES,self::MAXHASHBYTES);
        		$bytes_id .= bin2hex(openssl_random_pseudo_bytes($len));
    		}
		
		if(strlen($bytes_id) < 128) {
			$bytes_id .= mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE)
				. mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) 
				. mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) 
				. mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE) . mt_rand(self::MINMERSENNE,self::MAXMERSENNE); 
		}
		
		$token_id 	= hash('sha512',$bytes_id);
		$uniqueid  	= substr($token_id,0,12);
		
		return $uniqueid;
	}

	/**
	* Encryption function (requires OpenSSL)
	* @param string $plaintext
	* @return string|false raw bytes: IV (16) + HMAC (32) + ciphertext
	*/

	public function encrypt($plaintext) {

		if (!function_exists('openssl_encrypt')) {
			$this->messages->message('Encryption failed: OpenSSL is not supported or enabled on this PHP instance.');
			return false;
		}

		// Separate keys for encryption and MAC, derived from the master key
		$key    = $this->password(); // Password is set above at the Constants
		$encKey = hash_hmac('sha256', 'enc', $key, true);
		$macKey = hash_hmac('sha256', 'mac', $key, true);

		$iv = random_bytes(16);
		$ciphertext_raw = openssl_encrypt($plaintext, 'AES-256-CTR', $encKey, OPENSSL_RAW_DATA, $iv);
		if ($ciphertext_raw === false) {
			return false;
		}

		$hmac = hash_hmac('sha256', $iv . $ciphertext_raw, $macKey, true); // IV is covered too
		return $iv . $hmac . $ciphertext_raw;
	}

	/**
	* Decryption function (requires OpenSSL)
	* @param string $ciphertext raw bytes from encrypt()
	* @return string|false plaintext, or false if tampered, wrong key, or no OpenSSL
	*/

	public function decrypt($ciphertext) {

		if (!function_exists('openssl_decrypt')) {
			$this->messages->message('Decryption failed: OpenSSL is not supported or enabled on this PHP instance.');
			return false;
		}
		if (!is_string($ciphertext) || strlen($ciphertext) < 48) {
			return false;
		}

		$key    = $this->password(); // Password is set above at the Constants
		$encKey = hash_hmac('sha256', 'enc', $key, true);
		$macKey = hash_hmac('sha256', 'mac', $key, true);

		$iv             = substr($ciphertext, 0, 16);
		$hmac           = substr($ciphertext, 16, 32);
		$ciphertext_raw = substr($ciphertext, 48);

		$calcmac = hash_hmac('sha256', $iv . $ciphertext_raw, $macKey, true);
		if (!hash_equals($hmac, $calcmac)) { // timing-attack safe, checked BEFORE decrypting
			return false;
		}

		return openssl_decrypt($ciphertext_raw, 'AES-256-CTR', $encKey, OPENSSL_RAW_DATA, $iv);
	}
	
}
?>
