<?php

	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
	
	session_start(); 
	require(__DIR__."/../resources/PHP/Bootstrap.php");
		
	if(!empty($_REQUEST['id']) >=1) { 
		$pageid 	=  $db->intcast($_REQUEST['id']);
		$table    	= 'components';
		$column   	= 'id';
		$value    	=  $pageid;
		$operator 	= '*';
		$result 	= $db->select($table,$operator,$column,$value);
	} else if(!empty($_REQUEST['catid']) >=1) { 
		$pageid 	=  $db->intcast($_REQUEST['catid']);
		$table    	= 'components';
		$column   	= 'pid';
		$value    	=  $pageid;
		$operator 	= '*';
		$result 	= $db->select($table,$operator,$column,$value);
		} else {
		$result = $db->query("SELECT * from `components` ORDER BY id ASC");
	}

	if(isset($_REQUEST['filetype']) && ($_REQUEST['filetype'] == 'array' || $_REQUEST['filetype'] == 'csv')) {
		header('Content-Type: text/plain; charset=utf-8');
	} else {
		header('Content-Type: application/json; charset=utf-8');
	}

	if(isset($_REQUEST['filetype'])) {
		if($_REQUEST['filetype'] == 'array') {
			for($i=0;$i<count($result);$i++) {
				print_r($result[$i]);
			}
		} elseif($_REQUEST['filetype'] == 'unique') {
			for($i = 0; $i < count($result); $i++) {
				$result[$i]["component_title_".$i] = $result[$i]["component_title"];
				$result[$i]["component_text_".$i] = $result[$i]["component_text"];
				$result[$i]["component_image_".$i] = $result[$i]["component_image"];
				unset($result[$i]["component_title"]);
				unset($result[$i]["component_text"]);
				unset($result[$i]["component_image"]);
			}
			echo json_encode($result);
		} elseif($_REQUEST['filetype'] == 'csv') {
			for($i=0;$i<count($result);$i++) {
				$data  = $result[$i]['id'].',';
				$data .= $result[$i]['pid']. ',"';
				$data .= addslashes($result[$i]['component_title']). '","';
				$data .= addslashes($result[$i]['component_text']). '",';
				$data .= $result[$i]['component_image'];
				$data .= PHP_EOL;
				echo $data;
			}
		} else {
			echo json_encode($result);
		}
		
	} else {
		echo json_encode($result);
	}
	session_write_close();
?>