var page = require('webpage').create();
var args = require('system').args;
var url = args[1];
//var filename = args[2];
var ip = args[2];
page.settings.resourceTimeout = 2000;
page.customHeaders = {
  "X-Forwarded-For": ip,
  "DNT": "1"
};

page.onResourceRequested = function (request) {
	var match = request.url.match(/---\/---/g);
	if (match != null) {
		console.log(JSON.parse(JSON.stringify(request, undefined, 4)).url);
	}
};
	
page.open(url, function () {
	window.setTimeout(function () {
		phantom.exit();
	},600);     
});