<?php // initialise //>
namespace Pinout;

// font
define('FONT', __DIR__ .'/FreeSans.ttf');

// look for and load config file if found
$path = $_GET['f'];
$path = pathinfo($path, PATHINFO_DIRNAME).'/pinout_config.json';
$path = $_SERVER["DOCUMENT_ROOT"] . $path;
parseConfig($path);

// load default config - will populate any constants not already defined
$path = __DIR__ . '/default_config.json';
parseConfig($path, true);

/**
 * parse the config file specified by the path
 * @param string $cfg_path path of configuration file
 */
function parseConfig($cfg_path)
{
	if (file_exists($cfg_path)) {
		// get and decode json
		$cfg = file_get_contents($cfg_path);
		$cfg = utf8_encode($cfg);
		$cfg = json_decode($cfg);

		if (is_null($cfg)) throw new \Exception('Cannot decode config file');

		// define constants
		$valid_keys = ['width', 'height', 'scale', 'color', 'background'];
		foreach ($cfg as $k => $v) {
			if (in_array($k, $valid_keys)) {
				$key = __NAMESPACE__ . '\\' . strtoupper($k);
				if (!defined($key)) define($key, $v);
			}
		}
	}
}
