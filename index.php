<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 1:28 PM
 * To change this template use File | Settings | File Templates.
 */

error_reporting(E_ALL | E_STRICT);

$path = dirname(__FILE__);

define('BASE_PATH', $path);
define('TEMPLATE_PATH', $path . '/theme/templates');


if(file_exists(BASE_PATH . '/cache')){
	define('TEMP_PATH', BASE_PATH . '/cache');
}
else {
	$tmpFileName = str_replace(DIRECTORY_SEPARATOR, '-', BASE_PATH);
	$tmpFileName = str_replace(':', '--', $tmpFileName);
	define('TEMP_PATH', sys_get_temp_dir() . 'mvc_test' . $tmpFileName);
	if(!file_exists(TEMP_PATH)){
		mkdir(TEMP_PATH);
	}
}

set_error_handler(function($errNo, $errStr, $errFile, $errLine){
	if (!(error_reporting() & $errNo)) {
		return;
	}

	switch ($errNo) {
		case E_USER_ERROR:
			echo "<b>Error</b> [$errNo] $errStr<br />\n";
			echo "  Fatal error on line $errLine in file $errFile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...<br />\n";
			exit(1);
			break;

		case E_USER_WARNING:
			echo "<b>My WARNING</b> [$errNo] $errStr<br />\n";
			break;

		case E_USER_NOTICE:
			echo "<b>My NOTICE</b> [$errNo] $errStr<br />\n";
			break;

		default:
			echo "Unknown error type: [$errNo] $errStr<br>$errFile Line {$errLine}<br />\n";
			break;
	}

	/* Don't execute PHP internal error handler */
	return true;
});


if(file_exists(BASE_PATH . '/vendor/autoload.php')) {
	require_once BASE_PATH . '/vendor/autoload.php';
}
else {
	user_error('It doesnt look like you\'ve done a composer update.', E_USER_WARNING);
}

require_once('utils/ClassLoader.php');
require_once('utils/Object.php');
require_once('utils/TokenisedRegularExpression.php');
require_once('utils/Manifest.php');
require_once('utils/ClassManifest.php');
require_once('utils/ConfigManifest.php');

if(Manifest::make_manifest()){
	Manifest::reload_manifest();
}

$loader = ClassLoader::instance();
$loader->registerAutoLoader();

DB::init();


//
// add basic routes
//
Router::add_route('build', 'BuildController');


Router::route();
