<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] = 'Help' . $page[ 'title_separator' ].$page[ 'title' ];

$help = "<p>Not Found</p>";

if (array_key_exists ("id", $_GET) &&
	array_key_exists ("security", $_GET) &&
	array_key_exists ("locale", $_GET) &&
	is_string($_GET['id']) &&
	is_string($_GET['locale'])) {
	$helpModules = array(
		'authbypass' => 'authbypass',
		'brute' => 'brute',
		'captcha' => 'captcha',
		'cryptography' => 'cryptography',
		'csp' => 'csp',
		'csrf' => 'csrf',
		'exec' => 'exec',
		'fi' => 'fi',
		'javascript' => 'javascript',
		'open_redirect' => 'open_redirect',
		'sqli' => 'sqli',
		'sqli_blind' => 'sqli_blind',
		'upload' => 'upload',
		'weak_id' => 'weak_id',
		'xss_d' => 'xss_d',
		'xss_r' => 'xss_r',
		'xss_s' => 'xss_s'
	);
	$helpLocales = array(
		'en' => 'help.php',
		'zh' => 'help.zh.php'
	);
	$id = $_GET['id'];
	$locale = $_GET['locale'];

	$idHasPathSyntax = strpos($id, '/') !== false || strpos($id, '\\') !== false || $id === '.' || $id === '..';
	$localeHasPathSyntax = strpos($locale, '/') !== false || strpos($locale, '\\') !== false || $locale === '.' || $locale === '..';
	if (!$idHasPathSyntax && !$localeHasPathSyntax && isset($helpModules[$id]) && isset($helpLocales[$locale])) {
		$vulnerabilitiesDirectory = realpath(__DIR__);
		$helpDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . $helpModules[$id] . DIRECTORY_SEPARATOR . 'help');
		if ($vulnerabilitiesDirectory !== false && $helpDirectory !== false) {
			$vulnerabilitiesPrefix = $vulnerabilitiesDirectory . DIRECTORY_SEPARATOR;
			$helpPrefix = $helpDirectory . DIRECTORY_SEPARATOR;
			if (strncmp($helpDirectory, $vulnerabilitiesPrefix, strlen($vulnerabilitiesPrefix)) === 0) {
				$helpFile = realpath($helpDirectory . DIRECTORY_SEPARATOR . $helpLocales[$locale]);
				if ($helpFile !== false && is_file($helpFile) &&
					strncmp($helpFile, $helpPrefix, strlen($helpPrefix)) === 0) {
					$helpContents = file_get_contents($helpFile);
					if ($helpContents !== false) {
						$help = $helpContents;
					}
				}
			}
		}
	}
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	{$help}
</div>\n";

dvwaHelpHtmlEcho( $page );

?>
