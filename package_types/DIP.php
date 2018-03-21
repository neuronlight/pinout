<?php // DIP Package //>

require_once('DualPackage.php');

class DIP extends DualPackage
{
	/**
	 * constructor
	 * @param string $dev device object
	 */
	function __construct($dev)
	{
		// DIP packages of less than 24 pins are assumed to actually be SDIP packages
		if ($dev->{'pin-count'} < 24) parent::__construct($dev, 6.477, 1.143, 0.5715, 2.54, 1.27);
		else parent::__construct($dev, 12.954, 1.143, 0.5715, 2.54, 1.27);
	}

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
