<?php // dual package base //>
/**
 * @abstract
 * abstract class from which dual packages (DIP, SOIC, etc) are extended from
 *
 */
abstract class DualPackage
{
	/**
	 * constructor
	 * @param string $dev device object
	 * @param float $wb width of package body in mm
	 * @param float $lw pin/lead width in mm
	 * @param float $ll pin/lead potrusion distance in mm
	 * @param float $p pin pitch in mm
	 * @param float $o pin offset in mm (distance from edge of body to the edge of the pin)
	 */
	function __construct($dev, $wb, $lw, $ll, $p, $o)
	{
		$this->dev = $dev;
		$this->wb = $wb;
		$this->lw = $lw;
		$this->ll = $ll;
		$this->p = $p;
		$this->o = $o;
	}

	/*
	 * draw image
	 * @param object $diag Diagram object on which to draw image
	 */
	function image($diag)
	{
		// pre calculate more useful values
		$n = $this->dev->{'pin-count'};
		$bx = $this->wb / 2;
		$lx = $bx + $this->ll;
		$lr = $this->lw / 2;
		$of = $this->o + $lr;
		$by = (($of * 2) + ($this->p * (($n / 2) - 1))) / 2;

		// draw body
		$pxy = [[-$bx, $by],[$bx, $by], [$bx, -$by], [-$bx, -$by]];
		$diag->polygon($pxy);

		// draw leads/pins
		$y = $by - $of;
		for ($i = 0; $i < $n; $i += 2) {
			$pxy = [[-$bx, $y + $lr], [-$lx, $y + $lr], [-$lx, $y - $lr], [-$bx, $y - $lr]];
			$diag->openpolygon($pxy);
			$pxy = [[$bx, $y + $lr], [$lx, $y + $lr], [$lx, $y - $lr], [$bx, $y - $lr]];
			$diag->openpolygon($pxy);
			$y -= $this->p;
		}

		// draw signal directions
		$x = $lx + 1;
		for ($i = 1; $i <= $n; $i++) {
			if (!empty($this->dev->pinout->pins->{(string)$i})) {
				$pin = $this->dev->pinout->pins->{(string)$i};
				if (!is_array($pin)) $pin = [$pin];
				if ($i <= $n / 2) {
					$y = ($by - $of) - ($this->p * ($i - 1));
					$xm = -1;
				} else {
					$y = -($by - $of) + ($this->p * ($i - (($n / 2) + 1)));
					$xm = 1;
				}
				$in = false;
				$out = false;
				foreach ($pin as $p) {
					$sd = $p->{'signal-direction'};
					$in = $in || ($sd == 'in') || ($sd == 'both');
					$out = $out || ($sd == 'out') || ($sd == 'both');
				}
				$diag->line($x * $xm, $y, ($x + 1.5) * $xm, $y, $in, $out, 0.4, 0.4);
			}
		}

		// draw labels
		$x = $lx + 3.2;
		for ($i = 1; $i <= $n; $i++) {
			if (!empty($this->dev->pinout->pins->{(string)$i})) $lbl = $this->dev->pinout->pins->{(string)$i};
			elseif (!empty($this->dev->pinout->undefined)) $lbl = $this->dev->pinout->undefined;
			else $lbl = null;
			if (!empty($lbl)) {
				if ($i <= $n / 2) {
					$y = ($by - $of) - ($this->p * ($i - 1));
					$xm = -1;
				} else {
					$y = -($by - $of) + ($this->p * ($i - (($n / 2) + 1)));
					$xm = 1;
				}
				$diag->text($lbl, $x * $xm, $y, 1, ($xm == -1) ? 3 : 1, 0);
			}
		}

		// draw device name
		if ($n >= 14) $diag->text($this->dev->name, 0, 0, 1.1, 2, 90);
		else $diag->text($this->dev->name, 0, $by + 4, 1.1, 2, 0);
	}
}
