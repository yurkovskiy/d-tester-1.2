<!--
function show_pic(test,pic) {
	var url;
	var options;
	if(pic=="") return false;
	url=test+"/"+pic;
	options='toolbar=no,scrollbars=yes,status=no,menubar=no,resizable=yes,top=10,left=10,height=300,width=450';
	window.open(url,'_blank',options);
}

function open_rURL(url, range) {
	var oUrl = url + "range=" +range;
	window.open(oUrl, "mainFrame", "");
}
//-->