<?php

class DB {
	private static $instance;
	private $connection;
	private function __construct() {
		$this->connection = new mysqli(HOST,USERNAME,PASSWORD,DATABASE);
		$this->connection->set_charset(CHARSET);
	}
	public static function init() {
		if(is_null(self::$instance)) {
			self::$instance = new DB();
		}
		return self::$instance;
	}
	public function __call($name, $args){
		if(method_exists($this->connection, $name)) {
			 return call_user_func_array(array($this->connection, $name), $args);
		} else {
			 trigger_error('Unknown Method ' . $name . '()', E_USER_WARNING);
			 return false;
		}
	}
}
class sql {
	
	protected $database;
	private $statement;
	
	public function __construct() {
		$this->database = DB::init();
	}
	
	public function request() {
		// custom request query, returns true, void.
		$this->database->query($sql);
		return true;
	}
	
	public function q($sql) {
		return $this->database->query($sql);
	}
	
	public function query($sql) {
		// select query, returns array.
		$req = $this->q($sql);
		for ($set = array (); $row = $req->fetch_assoc(); $set[] = $row);
		return $set;
	}
	
	public function free($result) {
		$result->free();
	}
	
 	public function close() {  
		$this->database->close();
	}
	public function bindvalues($values) {
		$type ='';
		foreach($values as $var){
			$chartype = substr((string)gettype($var),0,1);
			$type .= (!in_array($chartype,array("i","d","s"))) ? "b" : $chartype;
		}
		return $type;
	}
		
	public function select($table,$query,$col,$value) {
		
		$value = isset($value) ? $value : exit;
	
		$q = "SELECT ".$this->clean($query,'query')." FROM ".$this->clean($table,'table')." WHERE ".$this->clean($col,'cols')." = ?";
		$stmt  = $this->database->prepare($q);
		
		if(is_int($value)) {
			$stmt->bind_param("i", $value);
			} else {
			$stmt->bind_param("s", $value);
		}
	
		if($stmt != NULL) {
			if($stmt->execute()) {
			$stmt->store_result();
			$res = [];
			$data = [];
			$array = [];
			$meta = $stmt->result_metadata();
			while($field = $meta->fetch_field()) {
				  $res[] = &$data[$field->name];
			}
			call_user_func_array(array($stmt, 'bind_result'), $res);
			$i=0;
			while($stmt->fetch()) {
				  $array[$i] = array();
				  foreach($data as $k=>$v)
				  $array[$i][$k] = $v;
				  $i++;
			}
			$stmt->close();
			}
		}			
		return $array;
		// returns array with all records, read more:
		// http://php.net/manual/en/class.mysqli-stmt.php		
	}

	public function search($table,$query,$col,$value) {
		
		$value = isset($value) ? $value : exit;
		
		$values = ["%$value%","%$value%"];
		
		$q = "SELECT ".$this->clean($query,'query')." FROM ".$this->clean($table,'table')." WHERE `product.title` LIKE ? OR `product.description` LIKE ?";
		$stmt  = $this->database->prepare($q);
		$type   = $this->bindvalues($values);
		$params = array($type);
		
		for($t=0;$t<count($values);$t++) {
			$params[] = $this->clean($values[$t],'cols');
		}
		
		$tmp = array();
		foreach($params as $key => $value) $tmp[$key] = &$params[$key];
		call_user_func_array(array($stmt, 'bind_param'), $tmp);
		
	
		if($stmt != NULL) {
			if($stmt->execute()) {
			$stmt->store_result();
			$res = [];
			$data = [];
			$array = [];
			$meta = $stmt->result_metadata();
			while($field = $meta->fetch_field()) {
				  $res[] = &$data[$field->name];
			}
			call_user_func_array(array($stmt, 'bind_result'), $res);
			$i=0;
			while($stmt->fetch()) {
				  $array[$i] = array();
				  foreach($data as $k=>$v)
				  $array[$i][$k] = $v;
				  $i++;
			}
			$stmt->close();
			}
		}			
		return $array;	
	}
	
	// returns ID of last inserted row, or false.
	public function insert($table,$columns,$values) {

		$countcols = count($columns);
		$countvalues = count($values);
		
		if($countcols < 1 or $countcols != $countvalues) {
			exit;
		}		
		
		$query  = "INSERT INTO ".$this->clean($table,'table')." (". $this->clean(implode(", ", $columns),'cols') .") VALUES (". substr(str_repeat('?,', $countvalues),0,-1) .")";
		$stmt   = $this->database->prepare($query);
		$type   = $this->bindvalues($values);
		$params = array($type);
		
		for($t=0;$t<$countvalues;$t++) {
			$params[] = $this->clean($values[$t],'cols');
		}
		
		$tmp = array();
		foreach($params as $key => $value) $tmp[$key] = &$params[$key];
		call_user_func_array(array($stmt, 'bind_param'), $tmp);
		$stmt->execute();
		$id = $stmt->insert_id;
		$stmt->close();
		if(is_int($id)) { 
			return $id;
			} else {
			return false;
		}
	}
	
	public function update($table,$columns,$values,$id) {
		
		$id = isset($id) ? $id : exit;
		$pid = (int)$id;
		$countcols = count($columns);
		$countvalues = count($values);
		$queryset = [''];
		
		$query = 'UPDATE '.$this->clean($table,'table').' SET ';
		for($k=0;$k<$countcols;$k++) {
			$query .= '`'.$this->clean($columns[$k],'cols').'` = ? ,';				
		}
			
		$query = substr($query,0,-1);
		$query .= ' WHERE `id` = ?';
		$stmt  = $this->database->prepare($query);
		$type  = $this->bindvalues($values);
		$type .= 'i';
		
		$params = array($type);
		for($t=0;$t<$countvalues;$t++) {
			$params[] = $values[$t];
		}
		
		$params[] = $pid;
		$tmp = array();
		foreach($params as $key => $value) $tmp[$key] = &$params[$key];
		
		call_user_func_array(array($stmt, 'bind_param'), $tmp);
		
		$stmt->execute();
		$stmt->close();
		
		return true;
	}
	
	public function countrows($table,$column,$value,$lock=0) {
		
		$numrows = 0;
		$query = "SELECT COUNT(*) FROM `".$this->clean($table,'table')."` WHERE ".$this->clean($column,'cols')." = ? ";
		
		// prevents race condition when checking rownumbers.
		if($lock == 'LOCK' || $lock == 1) {
			$query .=" FOR UPDATE";
		}
		
		$stmt  = $this->database->prepare($query);

		if(is_int($value)) {
			$stmt->bind_param("i", $value);
			} else {
			$stmt->bind_param("s", $value);
		}
	
		if($stmt != NULL) {
			
			$stmt->execute();
			$stmt->bind_result($count);
			while ($stmt->fetch()) {
				return $count;
			}
			$stmt->close();
		}
			
		return $numrows;
	}
	
	public function delete($table,$id) {
		
		$id = isset($id) ? (int)$id : exit;
		$query = "DELETE FROM ".$this->clean($table,'table')." WHERE id = ? LIMIT 1";
		$stmt  = $this->database->prepare($query);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();	
		
	}	
	
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
	
	public function clean($string,$method='') {
		
		$data = '';
		
		switch($method) {
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
			case 'query':
				$search  = ['`','"','\'',';'];
				$replace = ['','','',''];
				$data = str_replace($search,$replace,$string);
			break;
			case 'cols':
				// comma is allowed for selecting multiple columns.
				$search  = ['"','\'',';'];
				$replace = ['','',''];
				$data = str_replace($search,$replace,$string);
			break;
			case 'table':
				$search  = ['"',',','\'',';','$','%'];
				$replace = ['','','','','',''];
				$data = str_replace($search,$replace,$string);
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