
"use strict"

var dlabSettingsOptions = {};

function getUrlParams(dParam) 
{
	var dPageURL = window.location.search.substring(1),
		dURLVariables = dPageURL.split('&'),
		dParameterName,
		i;

	for (i = 0; i < dURLVariables.length; i++) {
		dParameterName = dURLVariables[i].split('=');

		if (dParameterName[0] === dParam) {
			return dParameterName[1] === undefined ? true : decodeURIComponent(dParameterName[1]);
		}
	}
}

(function($) {
	
	"use strict"

	dlabSettingsOptions = {
		typography: "roboto",
		version: "light",
		layout: "vertical",
		primary: "color_2",
		secondary: "color_2",
		headerBg: "color_2",
		navheaderBg: "color_1",
		sidebarBg: "color_2",
		sidebarStyle: "full",
		sidebarPosition: "fixed",
		headerPosition: "fixed",
		containerLayout: "full",
	};
	
	new dlabSettings(dlabSettingsOptions); 

	jQuery(window).on('resize',function()
	{
        dlabSettingsOptions.containerLayout = $('#container_layout').val();
		new dlabSettings(dlabSettingsOptions); 
	});
	
})(jQuery);