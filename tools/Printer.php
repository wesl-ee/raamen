<?php namespace Raamen;
class Printer {
	private $lang;
	private $theme;
	function setLang($lang) {
		$this->lang = $lang;
		putenv("LANG=$lang");
		setlocale(LC_ALL, $lang);

		$domain = "Raamen";
		bindtextdomain($domain, "locale");
		textdomain($domain);
	}
	function setTheme($theme) {
		$this->theme = $theme;
	}
	function toHtml($object) {
		$this->preambleHtml($object->title(), $object->description());
		$object->toHtml();
		$this->closingHtml();
	}
	function preambleHtml($title, $description) {
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description, ENT_QUOTES);
		$stylesheet = htmlspecialchars(CONFIG_WEBROOT . "style.css");
		$lang = htmlspecialchars($this->lang, ENT_QUOTES);
		$theme = htmlspecialchars($this->theme, ENT_QUOTES);
		print <<<HTML
<!DOCTYPE HTML>
<html lang="$lang">
<head>
	<link rel=stylesheet type="text/css" href="$stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>$title</title>
	<meta name=description content="$description">
</head>
<body>
HTML;
	}
	function closingHtml() {
		$dmcamail = CONFIG_DMCA_MAIL;
		print <<<HTML
<hr />

HTML;
		print <<<HTML
		<span>Raamen File Browser v1</span>,
		<span>created by <a href="https://wesl.ee/">
		wesl-ee
		</a></span><br/>
		<span>This instance is hosted by <a href="mailto:$dmcamail">
		$dmcamail</a></span>
HTML;
		print <<<HTML
<aside>
<img style="border: 1px solid" src="based.gif"><br />
Raamen is back <strong><a href="https://wesl.ee">TYBG</a></strong>!<br />
Browse the code <a href="https://github.com/wesl-ee/raamen">on GitHub</a>
</aside>
</footer>
</body>
</html>

HTML;
	}
	function dl($object) {
		$object->dl();
	}
	/*
	* Helpful error rendering... expects $error[0] to be an
	* HTTP status code. How nice!
	*/
	function error($error) { switch($error[0]) {
	case 403:
		header("{$_SERVER[SERVER_PROTOCOL]} 403 Forbidden");
		include "errors/403.php";
	break; case 301:
		$location = $error[1];
		header("$_SERVER[SERVER_PROTOCOL]} 301 See Other");
		header("Location: " . $location);
	break; case 404:
		header("$_SERVER[SERVER_PROTOCOL]} 404 Not Found");
		include "errors/404.php";
	} die; }
}
