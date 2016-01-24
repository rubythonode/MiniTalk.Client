<?php
REQUIRE_ONCE str_replace(DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'process'.DIRECTORY_SEPARATOR.'index.php','',$_SERVER['SCRIPT_FILENAME']).'/configs/init.config.php';
header("Content-type: text/json; charset=utf-8",true);

$action = Request('action');
$results = new stdClass();
if ($action == 'dependency') {
	$dependency = Request('dependency');
	$version = Request('version');
	
	$check = CheckDependency($dependency,$version);
	$results->success = true;
	$results->installed = $check->installed;
	$results->installedVersion = $check->installedVersion;
	$results->dependency = $dependency;
	$results->version = $version;
}

if ($action == 'directory') {
	$directory = Request('directory');
	$permission = Request('permission');
	
	$results->success = true;
	$results->directory = $directory;
	$results->created = CheckDirectoryPermission(__MINITALK_PATH__.DIRECTORY_SEPARATOR.$directory,$permission);
	$results->permission = $permission;
}

if ($action == 'config') {
	$config = Request('config');
	$results->success = true;
	$results->config = $config;
	$results->not_exists = !file_exists(__MINITALK_PATH__.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.$config.'.config.php');
}

if ($action == 'install') {
	REQUIRE_ONCE __MINITALK_PATH__.'/classes/DB/mysql.class.php';
	
	$package = json_decode(file_get_contents(__MINITALK_PATH__.'/package.json'));
	
	$errors = array();
	$key = Request('key') ? Request('key') : $errors['key'] = 'key';
	$admin_id = Request('admin_id') ? Request('admin_id') : $errors['admin_id'] = 'admin_id';
	$admin_password = Request('admin_password') ? Request('admin_password') : $errors['admin_password'] = 'admin_password';
	
	$db = new stdClass();
	$db->type = 'mysql';
	$db->host = Request('db_host');
	$db->username = Request('db_id');
	$db->password = Request('db_password');
	$db->database = Request('db_name');
	
	if ($db->type == 'mysql') {
		$mysqli = new mysql();
		if ($mysqli->check($db) === false) {
			$errors['db_host'] = $errors['db_id'] = $errors['db_password'] = $errors['db_name'] = 'db';
		} else {
			$dbConnect = new mysql($db);
			$dbConnect->setPrefix(__MINITALK_DB_PREFIX__);
		}
	}
	
	if (count($errors) == 0) {
		$results->success = false;
		
		$keyFile = @file_put_contents(__MINITALK_PATH__.'/configs/key.config.php','<?php /*'.PHP_EOL.$key.PHP_EOL.'*/ ?>');
		$dbFile = @file_put_contents(__MINITALK_PATH__.'/configs/db.config.php','<?php /*'.PHP_EOL.Encoder(json_encode($db),$key).PHP_EOL.'*/ ?>');
		$adminFile = @file_put_contents(__MINITALK_PATH__.'/configs/admin.config.php','<?php /*'.PHP_EOL.Encoder(json_encode(array('id'=>$admin_id,'password'=>$admin_password)),$key).PHP_EOL.'*/ ?>');
		
		if ($keyFile !== false && $dbFile !== false && $adminFile !== false) {
			if (CreateDatabase($dbConnect,$package->databases) == true) {
				$results->success = true;
			} else {
				$results->message = 'table';
			}
		} else {
			$results->message = 'file';
		}
	} else {
		$results->success = false;
		$results->errors = $errors;
	}
}

if ($action == 'rebuild') {
	REQUIRE_ONCE __MINITALK_PATH__.'/classes/DB/mysql.class.php';
	
	$package = json_decode(file_get_contents(__MINITALK_PATH__.'/package.json'));
	
	$dbConnect = new mysql($_CONFIGS->db);
	$dbConnect->setPrefix(__MINITALK_DB_PREFIX__);
	
	if (CreateDatabase($dbConnect,$package->databases) == true) {
		$results->success = true;
	} else {
		$results->meesage = 'table';
	}
}

exit(json_encode($results,JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
?>