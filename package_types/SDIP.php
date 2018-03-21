<?php // DIP Package //>

require_once('DualPackage.php');

class SDIP extends DualPackage
{
	/**
	 * constructor
	 * @param string $dev device object
	 */
	function __construct($dev) { parent::__construct($dev, 6.477, 1.143, 0.5715, 2.54, 1.27); }

	/*
	 * draw image
	 * @param object $diag Diagram object on which to draw image
	 */
	function image($diag)
	{
		parent::image($diag);
		// draw orientation notch
		$n = $this->dev->{'pin-count'};
		$bx = $this->wb / 2;
		$lr = $this->lw / 2;
		$of = $this->o + $lr;
		$by = (($of * 2) + ($this->p * (($n / 2) - 1))) / 2;
		$diag->arc(0, $by, 2, 2, 0, 180);
	}

}
