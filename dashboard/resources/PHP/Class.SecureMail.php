<?php

namespace security\forms;

#[\AllowDynamicProperties]
class SecureMail
{

	const XMAILER			= 'Secure Mail';
	const LANGUAGE			= 'en'; 
	const MIMEVERSION		= '1.0';
	const TRANSFERENCODING 	= '8bit';
	const CHARSET 			= 'UTF-8';
	const MAILFORMAT		= 'Flowed';
	const DELSP				= 'Yes'; 
	const OPTPARAM			= '-f';
	const WORD_WRAP			= true;
	const WORD_WRAP_VALUE	= 70;
	const MAXBODYSIZE 		= 15000; 
	const MAXFIELDSIZE 		= 350; 
	const TEMPLATE_START	= '{{';  
	const TEMPLATE_END		= '}}';
	const PHPENCODING 		= 'UTF-8'; 
	const MINHASHBYTES		= 32;
	const MAXHASHBYTES		= 64; 
	const MINMERSENNE		= 0xff; 
	const MAXMERSENNE		= 0xffffffff; 
	const SUPRESSMAILERROR  = true; 
	const SENSITIVITY		= true;	
	const SENSITIVITY_VALUE	= 'Normal';

	public function __construct($params = array()) 
	{ 
		$this->init($params);
	}
	
	/**
	* @var array form parameters.
	*/	
	public $fields = array();
	
	/**
	* Initializes object.
	* @param array $params
	* @throws Exception
	*/	
    public function init($params=[])
        {
		try {
			
			isset($params['to']) ? $this->fields['to'] = $params['to'] : 'info@'.$_SERVER['HTTP_HOST']; 
			isset($params['name']) ? $this->fields['name'] = $params['name'] : ''; 
			isset($params['email']) ? $this->fields['email'] = $params['email']  : ''; 				
			isset($params['subject']) ? $this->fields['subject'] = $params['subject'] : '';
			isset($params['body']) ? $this->body['body'] = $params['body'] : false;
		} catch(Exception $e) {
		}
    }
		
	/**
	* The main mail function.
	* @return mixed boolean.
	*/	
	public function sendmail() 
	{	
	
		$mime_headers = [];
		$from    = 'Shop <no-reply@'.$_SERVER['HTTP_HOST'].'>'; 		
		$to      = $this->clean($this->fields['to'],'field');
		$email    = $this->clean($this->fields['email'],'field');
		$subject = $this->clean($this->fields['subject'],'field');
		$message = $this->body['body'];
		
		$headers = [
			'From'                      	=> 'Shop <no-reply@'.$_SERVER['HTTP_HOST'].'>',
			'Sender'                    	=> 'Shop <no-reply@'.$_SERVER['HTTP_HOST'].'>',
			'Return-Path'               	=> 'Shop <no-reply@'.$_SERVER['HTTP_HOST'].'>',
			'MIME-Version'              	=> self::MIMEVERSION,
			'Content-Type'              	=> 'text/html; charset='.self::CHARSET.'; format='.self::MAILFORMAT.'; delsp='.self::DELSP,
			'Content-Language'				=> self::LANGUAGE,
			'Content-Transfer-Encoding' 	=> self::TRANSFERENCODING,
			'X-Mailer'                  	=> self::XMAILER,
			'Date'							=> date('r'),
			'Message-Id'					=> $this->generateBytes(),
		];
		
		if(self::SENSITIVITY == true) {
			$custom = array('Sensitivity' => self::SENSITIVITY_VALUE);
			$headers = array_merge($headers,$custom);
		}			
		
		foreach ($headers as $key => $value) {
			$mime_headers[] = "$key: $value";
		}
		
		$mail_headers = join("\n", $mime_headers);
		
		$message .= "\n\n";
		
		if(self::WORD_WRAP == true) {
			$message = wordwrap($message, self::WORD_WRAP_VALUE, "\r\n");
		}
		
		if(self::SUPRESSMAILERROR == true) {
			$send = @mail($to, $subject, $message, $mail_headers, self::OPTPARAM . $from);
			} else {
			$send = mail($to, $subject, $message, $mail_headers, self::OPTPARAM . $from);
		}
		return TRUE;
	}
	
	/**
	* Parses html templates.
	* @return string html code.
	*/
	public function parseTemplate($template,$parameters) {	
		$html = '';
		if(file_exists($template)) {
			$html = file_get_contents($template);
			if(is_array($parameters) && is_string($html)) {
				foreach ($parameters as $key => $value) {
					$html = str_ireplace(self::TEMPLATE_START.$key.self::TEMPLATE_END, $value, $html);
				}
			}
		} else {
			return FALSE; // e-mail cannot be send.	
		}
		
		return $html;
	}
	
 	/**
	* Generates psuedo random bytes for the message-id.
	* @return mixed string.
	*/
	public function generateBytes()
	{
		$bytes = '';
		
		if (function_exists('random_bytes')) {
        		$bytes .= bin2hex(random_bytes(16));
    		}
		
		if (function_exists('openssl_random_pseudo_bytes')) {
        		$bytes .= bin2hex(openssl_random_pseudo_bytes(16));
    		}	
		
		if(strlen($bytes) < 16) {
			$bytes .= mt_rand(self::MINMERSENNE,self::MAXMERSENNE); 
			$bytes .= mt_rand(self::MINMERSENNE,self::MAXMERSENNE); 
			$bytes .= mt_rand(self::MINMERSENNE,self::MAXMERSENNE); 
			$bytes .= mt_rand(self::MINMERSENNE,self::MAXMERSENNE); 
		}
		
		$pseudobytes = substr($bytes,0,16);
		
		$cnum = preg_replace( '/[^0-9]/', '', microtime(false));
		
		return sprintf("<%s.%s@%s>", base_convert($cnum, 10, 36), base_convert(bin2hex($pseudobytes), 16, 36), $this->clean($_SERVER['HTTP_HOST'],'domain'));
	}

	/**
	* Cleans a string.
	* @return string
	*/		
	public function clean($string,$method) {
		
		$buffer=self::MAXFIELDSIZE;
		
		$data = '';
		switch($method) { 
			case 'alpha':
				$this->data =  preg_replace('/[^a-zA-Z]/','', $string);
			break;
			case 'alphanum':
				$this->data =  preg_replace('/[^a-zA-Z-0-9]/','', $string);
			break;
			case 'field':
				$this->data =  preg_replace('/[^A-Za-z0-9-_.@\s+]/','', $string);
			break;			
			case 'num':
				$this->data =  preg_replace('/[^0-9]/','', $string);
			break;
			case 'unicode':
				$this->data =  preg_replace("/[^[:alnum:][:space:]]/u", '', $string);
			break;
			case 'encode':
				$this->data =  htmlspecialchars($string,ENT_QUOTES,self::PHPENCODING);
			break;
			case 'entities':
				$this->data =  htmlentities($string, ENT_QUOTES | ENT_HTML5, self::PHPENCODING);
			break;
			case 'domain':
				$this->data =  str_ireplace(array('http://','www.'),array('',''),$string);
			break;				
			}
		return $this->data;
	}
}
							
?>