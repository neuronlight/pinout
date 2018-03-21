/*
 * scans document for elements with "data-device" attribute, then dynamically inserts pinout image template
 */
var pname = window.location.pathname;
if (pname !== "/") {
	pname = pname.substr(1);
	pname = pname.split("/");
	pname.pop();
	pname = "/" + pname.join("/") + "/";
}
$(function() {
	$.each($("*[data-device]"), function() {
		var fname = $(this).attr("data-device") + ".json";
		if (fname.charAt(0) === "/") pname = "";
		var qs = "f=" + pname + fname;
		if ($(this).attr("data-width") != undefined) qs += "&w=" + $(this).attr("data-width");
		if ($(this).attr("data-height") != undefined) qs += "&h=" + $(this).attr("data-height");
		if ($(this).attr("data-scale") != undefined) qs += "&s=" + $(this).attr("data-scale");
		if ($(this).attr("data-color") != undefined) qs += "&c=" + $(this).attr("data-color");
		if ($(this).attr("data-background") != undefined) qs += "&b=" + $(this).attr("data-background");
		$(this).load("/vendor/neuronlight/pinout/template.php?" + qs);
	});
});
