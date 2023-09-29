#!/usr/bin/env php
<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

# Change working directory to this file's folder
chdir(__DIR__);

$cmdOptions = getopt('lv', ['local', 'verbose']);

$projectPath = "../";
$scriptPath = __DIR__;
$composerPath = "$scriptPath/composer.phar";
$currentUser = $_SERVER['USER'] ?? null;

$useLocalConfig = isset($cmdOptions['l']) || isset($cmdOptions['local']);
$verbose = isset($cmdOptions['v']) || isset($cmdOptions['verbose']);

$configFile = $useLocalConfig ? 'composer.local.json' : null; // Else use default one
$environmentPrefix = 'COMPOSER_ALLOW_XDEBUG=1';
if( $configFile ) {
	$environmentPrefix .= sprintf(' COMPOSER=%s ', $configFile) . $environmentPrefix;
}
$updateOptions = '';
if($useLocalConfig) {
	$updateOptions .= ' --prefer-source';
}

function writeError($text): void {
	fwrite(STDERR, $text . PHP_EOL);
}

function writeInfo($text): void {
	fwrite(STDOUT, $text . PHP_EOL);
}

if( $currentUser === 'root' ) {
	writeError("Please, don't use root to update the project, use your own project user !");
	exit(1);
}

chdir($projectPath);

if( !file_exists($composerPath) ) {
	copy('https://getcomposer.org/installer', 'composer-setup.php');
	`php composer-setup.php --install-dir="$scriptPath"`;
	unlink('composer-setup.php');
} else {
	if( $verbose ) {
		writeInfo('Try to update composer itself');
	}
	`php $composerPath self-update`;
}

if( $verbose ) {
	if( $useLocalConfig ) {
		writeInfo('Using local config');
	}
	
	if( $configFile ) {
		writeInfo(sprintf('Using specific config file "%s"', $configFile));
	}
}

//echo "$environmentPrefix php $composerPath update $updateOptions\n";
`$environmentPrefix php $composerPath update $updateOptions`;
