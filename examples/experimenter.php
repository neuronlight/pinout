<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Pinout Experimenter</title>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script type="text/javascript">
	  function render()
	  {
		  var w = document.getElementById("pinout_w").value;
		  var h = document.getElementById("pinout_h").value;
		  document.getElementById("result").style.width = w + "px";
		  document.getElementById("result").style.height = h + "px";
		  var action = "w=" + w;
		  action += "&h=" + h;
		  action += "&s=" + document.getElementById("pinout_s").value;
		  action += "&c=" + document.getElementById("pinout_c").value.substr(1);
		  action += "&b=" + document.getElementById("pinout_b").value.substr(1);
		  document.getElementById("formdata").action = "/vendor/neuronlight/pinout/pinout.php?" + action;
		  document.getElementById("formdata").submit();
	  }
  </script>
  <style type="text/css">
	  iframe {
		  border: none;
	  }
	  p {
		  float:left;
		  margin-right:20px;
	  }
	  div.clear {
		  clear: both;
	  }
  </style>
</head>
<body>
<h1>Pinout Experimenter</h1>
<form id="formdata" target="result" method="post">
	<textarea id="dev" name="dev" style="width: 100%; height: 25em;">{
		"name": "PIC12F675",
		"description": "8-Pin FLASH-Based 8-Bit CMOS Microcontroller",
		"package-type": "SDIP",
		"pin-count": 8,
		"pinout": {
			"pins": {
				"1": {
					"name": "VDD",
					"signal-direction": "none",
					"description": "Positive supply"
				},
				"2": [
						{
							"name": "GP5",
							"signal-direction": "both",
							"description": "Bi-directional I/O w/ programmable pull-up and interrupt-on-change"
						},
						{
							"name": "T1CKI",
							"signal-direction": "in",
							"description": "Timer1 clock in"
						},
						{
							"name": "OSC1",
							"signal-direction": "none",
							"description": "Crystal/resonator"
						},
						{
							"name": "CLKIN",
							"signal-direction": "in",
							"description": "External clock input/RC oscillator connection"
						}
				]
				,"3": [
						{
							"name": "GP4",
							"signal-direction": "both",
							"description": "Bi-directional I/O w/ programmable pull-up and interrupt-on-change"
						},
						{
							"name": "AN3",
							"signal-direction": "in",
							"description": "A/D Channel 3 input"
						},
						{
							"name": "T1G",
							"inverted": true,
							"signal-direction": "in",
							"description": "Timer1 gate"
						},
						{
							"name": "OSC2",
							"signal-direction": "none",
							"description": "Crystal/resonator"
						},
						{
							"name": "CLKOUT",
							"signal-direction": "out",
							"description": "FOSC/4 output"
						}
				]
				,"4": [
						{
							"name": "GP3",
							"signal-direction": "in",
							"description": "Input port w/ interrupt-on-change"
						},
						{
							"name": "MCLR",
							"inverted": true,
							"signal-direction": "in",
							"description": "Master clear"
						},
						{
							"name": "VPP",
							"signal-direction": "in",
							"description": "Programming voltage"
						}
				]
				,"5": [
						{
							"name": "GP2",
							"signal-direction": "both",
							"description": "Bi-directional I/O w/ programmable pull-up and interrupt-on-change"
						},
						{
							"name": "AN2",
							"signal-direction": "in",
							"description": "A/D Channel 2 input"
						},
						{
							"name": "T0CKI",
							"signal-direction": "in",
							"description": "Timer0 clock input"
						},
						{
							"name": "INT",
							"signal-direction": "in",
							"description": "External interrupt"
						},
						{
							"name": "COUT",
							"signal-direction": "out",
							"description": "Comparator output"
						}
				]
				,"6": [
						{
							"name": "GP1",
							"signal-direction": "both",
							"description": "Bi-directional I/O w/ programmable pull-up and interrupt-on-change"
						},
						{
							"name": "AN1",
							"signal-direction": "in",
							"description": "A/D Channel 1 input"
						},
						{
							"name": "CIN-",
							"signal-direction": "in",
							"description": "Comparator input"
						},
						{
							"name": "VREF",
							"signal-direction": "in",
							"description": "External voltage reference"
						},
						{
							"name": "ICSPCLK",
							"signal-direction": "in",
							"description": "Serial programming clock"
						}
				]
				,"7": [
						{
							"name": "GP0",
							"signal-direction": "both",
							"description": "Bi-directional I/O w/ programmable pull-up and interrupt-on-change"
						},
						{
							"name": "AN0",
							"signal-direction": "in",
							"description": "A/D Channel 0 input"
						},
						{
							"name": "CIN+",
							"signal-direction": "in",
							"description": "Comparator input"
						},
						{
							"name": "ICSPDAT",
							"signal-direction": "both",
							"description": "Serial programming I/O"
						}
				],
				"8": {
					"name": "VSS",
					"signal-direction": "none",
					"description": "Ground reference"
				}
			}
		}
	}</textarea>
</form>
<p>
	<label for="pinout_w">Width:</label>
	<input id="pinout_w" name="pinout_width" type="number" min="100" max="3840" value="640" />
</p>
<p>
	<label for="pinout_h">Height:</label>
	<input id="pinout_h" type="number" min="100" max="2160" value="320" />
</p>
<p>
	<label for="pinout_c">Scale:</label>
	<input id="pinout_s" type="number" min="0.1" max="1000" value="10" />
</p>
<p>
	<label for="pinout_c">Color:</label>
	<input id="pinout_c" type="color" value="#44ff00" />
</p>
<p>
	<label for="pinout_b">Background:</label>
	<input id="pinout_b" type="color" value="#101010" />
</p>
<p>
	<input type="button" onclick="render();" value="Render" />
</p>
<div class="clear"></div>
<iframe id="result" name="result" style="width:100%"></iframe>
</body>
</html>
