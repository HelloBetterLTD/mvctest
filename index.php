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

if(file_exists(BASE_PATH . '/vendor/autoload.php')) {
	require_once BASE_PATH . '/vendor/autoload.php';
}
else {
	user_error('It doesnt look like you\'ve done a composer update. For more information <a href="https://github.com/SilverStripers/mvctest/wiki/Installation" target="_blank">Click here</a><br>', E_USER_WARNING);
	exit(1);
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


set_error_handler(function($errNo, $errStr, $errFile, $errLine){
	if (!(error_reporting() & $errNo)) {
		return;
	}

	echo "<div class='ss_error'><div class='ss_error__inner'>";

	switch ($errNo) {
		case E_USER_ERROR:
			echo "<b>Error</b> [$errNo] $errStr<br />\n";
			echo "  Fatal error on line $errLine in file $errFile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...\n";
			echo "<pre>";
			Debug::display_filter_backtrace(debug_backtrace());
			echo "</pre>";
			echo "</div>";
			echo "<link rel=\"stylesheet\" href=\"" .  Router::get_base() . "/lib/static/framework.css\" type=\"text/css\">";
			exit(1);
			break;

		case E_USER_WARNING:
			View::framework_css();
			Debug::log_error("<b>WARNING</b> [$errNo] $errStr<br />", debug_backtrace());
			break;

		case E_USER_NOTICE:
			View::framework_css();
			Debug::log_error("<b>NOTICE</b> [$errNo] $errStr<br />", debug_backtrace());
			break;

		default:
			View::framework_css();
			Debug::log_error("Unknown error type: [$errNo] $errStr<br>$errFile Line {$errLine}", debug_backtrace());
			break;
	}

	echo '</div></div>';

	/* Don't execute PHP internal error handler */
	return true;
});





$loader = ClassLoader::instance();
$loader->registerAutoLoader();

DB::init();


//
// add basic routes
//
Router::add_route('build', 'BuildController');


Router::route();
