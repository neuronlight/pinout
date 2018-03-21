# Pinout

Pinout facilitates the rendering of integrated circuit pinout diagrams from a JSON file.

## Installation

```$ composer require neuronlight/pinout```

Test the installation by browsing to ```/vendor/neuronlight/pinout/examples/```

You should see a pinout diagram for the PIC12F675 microcontroller.

## Simple usage

Include the javascript in your web page:

```HTML
<script type="text/javascript" src="/vendor/neuronlight/pinout/pinout.js"></script>
```

To have a pinout diagram rendered from a JSON use an element with a ```data-device``` attribute:

```HTML
<div data-device="pic12f675"></div>
```

where (in this case) ```pic12f675``` is the filename (sans ```.json``` extension - although it can be included) of the JSON file defining the device.

**You'll also need to include jQuery; either locally or via one of the many CDNs available**

## Defining a device

A JSON file for a device takes the form:

```JSON
{
	"name": "Serial Infrared Decoder",
	"description": "Serial Infrared Decoder based on PIC12F675 8-Bit Microcontroller",
	"package-type": "SDIP",
	"pin-count": 8,
	"pinout": {
	    "undefined": "NC",
		"pins": {
			"1": {
				"name": "VDD",
				"signal-direction": "none",
				"description": "Positive supply"
			},
			"2": {
				"name": "IR IN",
				"signal-direction": "in",
				"description": "Infrared module input"
	        },
			"3": {
				"name": "IR IN",
				"signal-direction": "in",
				"description": "Infrared module input"
	        },
			"4": [
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
			],
			"5": {
				"name": "Serial Out",
				"signal-direction": "both",
				"description": "Serial data output"
	        },
			"8": {
				"name": "VSS",
				"signal-direction": "none",
				"description": "Ground reference"
			}
		}
	}
}
```

Package types currently supported are DIP, PDIP, CERDIP, SDIP & SPDIP.

(Devices with less than 24 pins that are defined as DIP sized packages are rendered as SDIP)

## Changing the appearance of the diagram

The diagram is rendered using paramters in the default configuration file:

```JSON
{
	"color": "000",
	"background": "fff",
	"width": 640,
	"height": 480,
	"scale": 10
}
```

Some, or all of the paramters can be overridden in one of two ways.

1. An alternative configuration file (named ```pinout_config.json```) can be created and placed in the same directory as the device JSON file (parameters defined this way override those defined in the default configuration file)

2. Other attributes can be added to the HTML element (any parameters define in this way override those defined in any configuration file)

Element attributes supported are:

```data-width``` - width of diagram in pixels

```data-height``` - height of digram in pixels

```data-scale``` - scale of diagram (number of pixels per mm)

```data-color``` - diagram color (in hex M, RGB or RRGGBB format)

```data-background``` - background color



Share and enjoy.