<?php

// header.inc - definitions HTML output functions

function page_begin($title) {
	echo "<html version=\"4.01\">\n<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">\n";
	echo "<meta http-equiv=\"cache-control\" content=\"no-cache\">\n";
	echo "<meta name=\"Author\" content=\"Юрій Безгачнюк\">\n";
	echo "<link rel=\"stylesheet\" href=\"styles/base.css\" type=\"text/css\">\n";
	echo "<title>{$title}</title>\n</head>\n";
	echo "<body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">\n";
}

function page_end() {
	echo "</body>\n</html>\n";
}

?>