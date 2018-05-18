<?php // pinout //>
/**
 * @package	pinout
 * @author	Simon Buckwell
 * @licence	https://opensource.org/licenses/GPL-3.0 GPL-3.0
 *
 * return png image of device pinout
 *
 */
namespace Pinout;

require_once(__DIR__ . '/diagram.php');

header ('Content-Type: image/png');

try {
	// load defaults from config files
	require_once('init.php');

	// get json for device
	if (isset($_POST['dev'])) $dev = $_POST['dev'];
	else {
		$path = $_GET['f'];
		if (substr($path, -5) == '.json') $path = substr($path, 0, -5);
		if (substr($path, 0, 1) != '/') $path = '/' . $path;
		$path = $_SERVER["DOCUMENT_ROOT"] . $path;
		$path = pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME) . '.json';
		if ($path = realpath($path)) {
			// validate path
			if (substr(pathinfo($path, PATHINFO_DIRNAME), 0, strlen($_SERVER["DOCUMENT_ROOT"])) !== $_SERVER["DOCUMENT_ROOT"]) throw new \Exception('Package file not found');

			// load device file json
			$dev = file_get_contents($path);
		} else throw new \Exception('Cannot find device file');
	}
	
	// decode device JSON
	$dev = utf8_encode($dev);
	$dev = json_decode($dev);
	if (is_null($dev)) throw new \Exception('Cannot decode device JSON');

	// get diagram parameters
	$width = (!empty($_GET['w'])) ? abs((int)$_GET['w']) : WIDTH;
	$height = (!empty($_GET['h'])) ? abs((int)$_GET['h']) : HEIGHT;
	$scale = (!empty($_GET['s'])) ? abs((int)$_GET['s']) : SCALE;
	$color = (!empty($_GET['c'])) ? trim($_GET['c']) : COLOR;
	$background = (!empty($_GET['b'])) ? trim($_GET['b']) : BACKGROUND;

	// do (very) rudimentary data validation
	if ($width <= 0 || $width > 10000) throw new \Exception('Image width invalid');
	if ($height <= 0 || $height > 10000) throw new \Exception('Image height invalid');
	if ($scale < 0.1 || $scale > 100) throw new \Exception('Scale invalid');
	if (Diagram::getRGB($color) === false) throw new \Exception('Color invalid');
	if (Diagram::getRGB($background) === false) throw new \Exception('Background color invalid');

	// load class file for device
	$pkg = __DIR__ . '/package_types/' . $dev->{'package-type'} . '.php';
	if ($pkg = realpath($pkg)) {
		// validate path
		if (pathinfo($pkg, PATHINFO_DIRNAME) !== __DIR__ . '/package_types') throw new \Exception('Package file not found');
		require_once($pkg);
		$pkg = new $dev->{'package-type'}($dev);

		// render diagram
		$d = new Diagram($width, $height, $scale, $color, $background);
		$pkg->image($d);
		$d->png();
	} else throw new \Exception('Package file not found');
} catch (\Exception $e) {
	// something went wrong - inform user
	$im = imagecreatetruecolor(400, 200);
	$col = imagecolorallocate($im, 255, 255, 255);
	imagestring($im, 2, 2, 2, 'Error: ' . $e->getMessage(), $col);
	imagepng($im);
	imagedestroy($im);
}

exit();
