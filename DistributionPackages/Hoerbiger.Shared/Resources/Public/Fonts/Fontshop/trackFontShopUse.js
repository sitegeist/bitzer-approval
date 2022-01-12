(function(){
	window.MTUserId = "333962424"; // From the name of the zip containing the sources. Not sure about that.
	window.MTFontIds = [
		"693229", // Trade Gothic® Next W01 Bold 
		"693250", // Trade Gothic® Next W01 Light 
		"5618550", // Trade Gothic® Next W01 Regular
	];

	const mtTracking = document.createElement("script");
	mtTracking.type = "text/javascript";
	mtTracking.async = "true";
	mtTracking.src = "/_Resources/Static/Packages/Hoerbiger.Shared/Fonts/Fontshop/mtiFontTrackingCode.js";

	(document.getElementsByTagName("head")[0]||document.getElementsByTagName("body")[0]).appendChild(mtTracking);

	// Generates and requests:
	// https://fast.fonts.net/lt/1.css?apiType=css&c=333962424&fontids=693229,693250,5618550
}()); 
