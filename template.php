<?php // html template for pinout //>
$qs = [];
$qs[] = 'f=' . $_GET['f'];
if (isset($_GET['w'])) $qs[] = 'w=' . $_GET['w'];
if (isset($_GET['h'])) $qs[] = 'h=' . $_GET['h'];
if (isset($_GET['s'])) $qs[] = 's=' . $_GET['s'];
if (isset($_GET['c'])) $qs[] = 'c=' . $_GET['c'];
if (isset($_GET['b'])) $qs[] = 'b=' . $_GET['b'];
$qs = implode('&amp;', $qs);
$src = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME) . '/pinout.php?' . $qs; ?>
<img src="<?= $src ?>" />
