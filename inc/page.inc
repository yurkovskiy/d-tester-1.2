<?php

/**
 * @package d-tester
 * @subpackage tester subsystem
 * @version 1.1 RC1
 * @name page class
 * @author Luke Welling, Lora Thompson
 * @author Yuriy Bezgachnyuk, IF, Ukraine (modified for d-tester)
 * @version 0.1
 * Update: 16/10/2006 22:00 GMT +02:00
 */

class page
{
	/**
	 * HTML code for any web page
	 *
	 * @var array
	 */
	var $content = array();

	/**
	 * Web page title
	 *
	 * @var string
	 */
	var $title;

	/**
	 * Path to cascading styles sheet (CSS) file
	 *
	 * @var string
	 */
	var $css_file = "./styles/index.css";

	/**
	 * Copyright information
	 *
	 * @var string
	 */
	var $copyright;

	/**
	 * Main menu array description
	 *
	 * @var array
	 */
	var $nav = array();

	/**
	 * Install web page title property
	 *
	 * @param string $newtitle
	 */
	function SetTitle($newtitle) {
		$this->title = $newtitle;
	}

	/**
	 * Setting web page CSS file property
	 *
	 * @param string $new_css_file
	 */
	function SetCSSFile($new_css_file) {
		$this->css_file = $new_css_file;
	}

	/**
	 * Setting web page HTML/XHTML code
	 *
	 * @param mixed array $newcontent
	 */
	function SetContent($newcontent) {
		$this->content = $newcontent;
	}

	/**
	 * Setting copyright class property
	 *
	 * @param string $copyright_inf
	 */
	function SetCopyright($copyright_inf)
	{
		$this->copyright = $copyright_inf;
	}

	/**
	 * Display web page title
	 *
	 */
	function DisplayTitle()
	{
		echo "\n<!-- Page Title -->\n<title>{$this->title}</title>\n";
	}

	/**
	 * Display web page header
	 * header - is only design part for some web page
	 *
	 */
	function DisplayHeader()
	{
		echo "\n<!-- Header Images -->\n";
		echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
		//echo "<tr>\n<td widht=\"20%\" valign=\"middle\" align=\"left\"><img hspace=\"3\" src=\"./admin/images/biglogo.gif\"></td>\n";
		echo "<td valign=\"middle\" allign=\"center\" width=\"60%\">&nbsp;</td>\n";
		echo "<td width=\"20%\" valign=\"middle\" align=\"right\">&nbsp;</td>\n";
		echo "</tr>\n</table>\n";
	}

	/**
	 * Generating HTML code for link CSS file
	 *
	 */
	function DisplayCSS()
	{
		echo "\n<!-- link CSS file -->\n<link rel=\"stylesheet\" href=\"{$this->css_file}\" type=\"text/css\">\n";
	}

	/**
	 * Display web page footer
	 *
	 */
	function DisplayFooter()
	{
		echo "<br>\n<!-- Page Footer -->\n<div align=\"center\" class=\"copyright\">{$this->copyright}</div>\n";

	}

	/**
	 * SetNavigationBar(array) - setting class nav property
	 */	
	function SetNavigationBar($new_nav_bar)
	{
		$this->nav = $new_nav_bar;
	}

	/**
	 * Checking current page URL address 
	 *
	 * @param string $url
	 * @return BOOL
	 */
	function IsURLCurrentPage($url)
	{
		$menu_url = $_SERVER['REQUEST_URI'];
		if((strstr($menu_url,"/")!=null)
		||(strstr($menu_url,"index.php") != null)) $menu_url = "./index.php?action=main";

		if(strstr($menu_url,$url) == null)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Display One Menu Item element
	 *
	 * @param int $width
	 * @param string $name
	 * @param string $url
	 * @param BOOL $active
	 */
	function DisplayNavigationBarItem($width, $name, $url, $active = false)
	{
		if(!$active)
		{
			echo "<td class=\"NavItem\" align=\"center\" valign=\"middle\" width=\"{$width}%\"><a href=\"{$url}\" title=\"{$name}\">{$name}</a></td>\n";
		}
	}

	/**
	 * Display navigation bar (menu)
	 *
	 */
	function DisplayNavigationBar()
	{
		echo "\n<!-- Navigation Bar -->\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tbl_hrefs\">\n<tr>\n";
		$width = round(100 / (count($this->nav) - 1));
		while (list($name, $url) = each($this->nav))
		{
			$this->DisplayNavigationBarItem($width, $name, $url, $this->IsURLCurrentPage($url));
		}
		echo "</tr>\n</table>\n";
	}

	/**
	 * Display web page meta tags 
	 *
	 */
	function DisplayMeta()
	{
		echo "\n<!-- Meta data -->\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">\n";
		echo "<meta http-equiv=\"cache-control\" content=\"no-cache\">\n<meta name=\"Author\" content=\"Yuriy Bezgachnyuk\">\n";
	}

	/**
     * Display (Generating) HTML web page code to a output stream
     *
     */
	function DisplayPage()
	{
		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		echo "<html lang=\"uk\" version=\"4.01\">\n<head>\n";
		$this->DisplayMeta();
		$this->DisplayCSS();
		$this->DisplayTitle();
		echo "</head>\n<body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">\n";
		//$this->DisplayHeader();
		//$this->DisplayNavigationBar();
		if ($this->content != null)
		{
			echo "\n<!-- Page Content -->\n";

			foreach ($this->content as $element) {
				echo $element;
			}

		}
		echo "\n";
		$this->DisplayFooter();
		echo "</body>\n</html>\n";
	}
}

?>