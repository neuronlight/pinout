<?php // diagram class //>
/**
 * @package	pinout
 * @author	Simon Buckwell
 *
 * class for drawing images on a scaled x-y plane
 */
namespace Pinout;

class Diagram
{
	public $im;			// image resource
	private $col;		// colour
	private $sc;		// scale
	private $cx;		// centre x
	private $cy;		// centre y

	/**
	 * class constructor
	 * @param int $width width of image in pixels
	 * @param int $height height of image in pixels
	 * @param float $sc scale of dimensions (by convention, the number of pxels per mm)
	 * @param string $col colour of drawn elements in 6-hex or 3-hex format
	 * @param string $background colour of background in 6-hex or 3-hex format
	 */
	function __construct($width, $height, $sc, $col, $background)
	{
		$this->im = imagecreatetruecolor($width, $height);
		$c = Diagram::getRGB($col);
		$this->col = imagecolorallocate($this->im, $c['r'], $c['g'], $c['b']);
		$c = Diagram::getRGB($background);
		$c = imagecolorallocate($this->im, $c['r'], $c['g'], $c['b']);
		imagefilledrectangle($this->im, 0, 0, imagesx($this->im) - 1, imagesy($this->im) - 1, $c);
		$this->sc = $sc;
		$this->cx = imagesx($this->im) / 2;
		$this->cy = imagesy($this->im) / 2;
	}

	/**
	 * class destructor
	 */
	function __destruct() { imagedestroy($this->im); }

	/**
	 * outputs png of diagram
	 */
	function png() { imagepng($this->im); }

	/**
	 * translates coordinates in array
	 * @param array $p array of floats of format: [x1, y1, x2, y2, x3, y3, ..., xn, yn]
	 * @return array array of translated floats in the above format
	 */
	function tp($p)
	{
		foreach ($p as $i => &$v) {
			$v = ($i % 2) == 0 ? $this->tx($v) : $this->ty($v);
		}
		return $p;
	}

	/**
	 * translates x coordinate
	 * @param float $x
	 * @return float
	 */
	function tx($x) { return $this->cx + ($x * $this->sc); }

	/**
	 * translates y coordinate
	 * @param float $y
	 * @return float
	 */
	function ty($y) { return $this->cy - ($y * $this->sc); }

	/**
	 * returns scaled scaler
	 * @param float $l
	 * @return float
	 */
	function sc($l) { return $this->sc * $l; }

	/**
	 * draw line with optional arrowheads
	 * @param float $x1 x coorinate of start of line
	 * @param float $y1 y coorinate of start of line
	 * @param float $x2 x coorinate of end of line
	 * @param float $y2 y coorinate of end of line
	 * @param bool $start_arrow arrow head rendered at start of line if true
	 * @param bool $end_arrow arrow head rendered at end of line if true
	 * @param float $arrow_width width of arrow head
	 * @param float $arrow_length length of arrow head
	 */
	function line($x1, $y1, $x2, $y2, $start_arrow = false, $end_arrow = false, $arrow_width = 0, $arrow_length = 0)
	{
		// draw line
		imageline($this->im, $this->tx($x1), $this->ty($y1), $this->tx($x2), $this->ty($y2), $this->col);

		//draw arrows
		if ($start_arrow || $end_arrow) {
			// calculate orthogonal unit vectors
			$dx = $x2 - $x1;
			$dy = $y2 - $y1;
			$l = sqrt(($dx * $dx) + ($dy * $dy));
			$ix = $dx / $l;
			$iy = $dy / $l;
			$jx = -$iy;
			$jy = $ix;
			// half arrow width
			$arrow_width /= 2;
			// draw start arrow
			if ($start_arrow) {
				imageline($this->im, $this->tx($x1), $this->ty($y1), $this->tx($x1 + ($ix * $arrow_length) + ($jx * $arrow_width)), $this->ty($y1 + ($iy * $arrow_length) + ($jy * $arrow_width)), $this->col);
				imageline($this->im, $this->tx($x1), $this->ty($y1), $this->tx($x1 + ($ix * $arrow_length) - ($jx * $arrow_width)), $this->ty($y1 + ($iy * $arrow_length) - ($jy * $arrow_width)), $this->col);
			}
			// draw end arrow
			if ($end_arrow) {
				imageline($this->im, $this->tx($x2), $this->ty($y2), $this->tx($x2 - ($ix * $arrow_length) + ($jx * $arrow_width)), $this->ty($y2 - ($iy * $arrow_length) + ($jy * $arrow_width)), $this->col);
				imageline($this->im, $this->tx($x2), $this->ty($y2), $this->tx($x2 - ($ix * $arrow_length) - ($jx * $arrow_width)), $this->ty($y2 - ($iy * $arrow_length) - ($jy * $arrow_width)), $this->col);
			}
		}
	}

	/**
	 * draw polygon
	 * @param array $pxy array of coordinates of the format [[x1, y1], [x2, y2], ..., [xn, yn]]
	 */
	function polygon($pxy)
	{
		$c = count($pxy);
		$pxyl = [];
		foreach ($pxy as $p) {
			$pxyl[] = $p[0];
			$pxyl[] = $p[1];
		}
		imagepolygon($this->im, $this->tp($pxyl), $c, $this->col);
	}

	/**
	 * draw open polygon
	 * @param array $pxy array of coordinates of the format [[x1, y1], [x2, y2], ..., [xn, yn]]
	 */
	function openpolygon($pxy)
	{
		$c = count($pxy);
		for ($i = 1; $i != $c; $i++) {
			$this->line($pxy[$i - 1][0], $pxy[$i - 1][1], $pxy[$i][0], $pxy[$i][1]);
		}
	}

	/**
	 * draw arc
	 * @param float $x x coordinate of arc centre
	 * @param float $y y coordinate of arc centre
	 * @param float $rx arc radius in direction of x axis
	 * @param float $ry arc radius in direction of y axis
	 * @param float $a1 start angle in degrees (3 o'clock position is 0)
	 * @param float $a2 end angle in degrees
	 */
	function arc($x, $y, $rx, $ry, $a1, $a2) { imagearc($this->im, $this->tx($x), $this->ty($y), $this->sc($rx), $this->sc($ry), $a1, $a2, $this->col); }

	/**
	 * draw text
	 * @param array|object|string $c content/text to draw (designed to accept contents of 'pin' properties of dev object)
	 * @param float $x x coordinate of position of text
	 * @param float $y y coordinate of position of text
	 * @param float $sc relative scale of text
	 * @param int $al alignment of text; 1 - right of xy coords, 2 - centred on xy coords, 3 - left of xy coords (text always aligned vertially centred)
	 * @param float $an angle of text in degrees
	 */
	function text($c, $x, $y, $sc, $al, $an)
	{
		// ensure $c is array of objects
		if (is_string($c)) $c = [(object)['name' => $c]];
		elseif (!is_array($c)) $c = [$c];

		// calculate orthogonal unit vectors of text orientation
		$anr = deg2rad($an);
		$ix = cos($anr);
		$iy = sin($anr);
		$jx = -$iy;
		$jy = $ix;

		// calc y offset (& overline height)
		$oy = imagettfbbox($this->sc * $sc, 0, FONT, 'X');
		$oy = abs($oy[5] - $oy[1]);
		$ol = $oy + 2;
		$oy /= 2;

		// calc x offset
		$st = '';
		$sep = '';
		foreach ($c as &$i) {
			$st .= $sep;
			$sep = ' / ';
			if (!isset($i->inverted)) $i->inverted = false;
			if ($i->inverted) {
				$bb = imagettfbbox($this->sc * $sc, 0, FONT, $st);
				$i->x1 = abs($bb[4] - $bb[0]);
				$st .= $i->name;
				$bb = imagettfbbox($this->sc * $sc, 0, FONT, $st);
				$i->x2 = abs($bb[4] - $bb[0]);
				// adjust for sometimes janky output from imagettfbox
				$i->x1 -= 2;
				$i->x2 -= 2;
			} else $st .= $i->name;
		}
		if ($al == 1) $ox = 0;
		else {
			$bb = imagettfbbox($this->sc * $sc, 0, FONT, $st);
			$ox = abs($bb[4] - $bb[0]);
			if ($al == 2) $ox /= 2;
		}

		// adjust coordinates
		$x = $this->tx($x) - ($ix * $ox) - ($jx * $oy);
		$y = $this->ty($y) + ($iy * $ox) + ($jy * $oy);

		// render text
		imagettftext($this->im, $this->sc * $sc, $an, $x, $y, $this->col, FONT,  $st);

		// render overlines
		$x += $jx * $ol;
		$y -= $jy * $ol;
		foreach ($c as $i) {
			if ($i->inverted) {
				imageline($this->im, $x + ($ix * $i->x1), $y + ($jx * $i->x1), $x + ($ix * $i->x2), $y + ($jx * $i->x2), $this->col);
			}
		}
	}

	/**
	 * return associative array from RGB hex strings
	 * @param string $hex string of 1, 3 or 6 hex digits
	 * @return array|bool associative array of ints of the format ['r' => (int), 'g' => (int), 'b' => (int)], false if not valid
	 */
	static function getRGB($hex)
	{
		$rgb = false;

		// clean up string
		$hex = strtolower($hex);
		$hex = preg_replace('/[^0-9a-f]/', '', $hex);

		// expand 1 digit to 3 digits
		if (strlen($hex) == 1) $hex = $hex . $hex . $hex;

		// expand 3 to 6 digits
		if (strlen($hex) == 3) $hex = substr($hex, 0, 1) . substr($hex, 0, 1) . substr($hex, 1, 1) . substr($hex, 1, 1) . substr($hex, 2, 1) . substr($hex, 2, 1);

		// convert
		if (strlen($hex) == 6) {
			$rgb = [];
			$rgb['r'] = hexdec(substr($hex, 0, 2));
			$rgb['g'] = hexdec(substr($hex, 2, 2));
			$rgb['b'] = hexdec(substr($hex, 4, 2));
		}

		return $rgb;
	}
}
