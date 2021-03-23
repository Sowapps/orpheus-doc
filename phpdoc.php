#!/usr/bin/env php
<?php

/*
 * Use configuration file phpdoc.dist.xml
 * php phpdoc.php
 * 
 * With no configuration file (DO NOT USE)
 * php phpdoc.php -d vendor/orpheus/ -t html/
 */

$phpDocumentorVersion = 'v3.0.0';
$phpDocumentorDownloadUrl = sprintf('https://github.com/phpDocumentor/phpDocumentor/releases/download/%s/phpDocumentor.phar', $phpDocumentorVersion);
$phpDocumentorFile = 'phpDocumentor.phar';
$docSource = 'vendor/orpheus/';
$docTarget = 'html/';
$docCache = 'cache/';

function writeInfo($text) {
	echo "\e[34m" . $text . "\e[0m" . PHP_EOL;
}

function writeSuccess($text) {
	echo "\e[32m" . $text . "\e[0m" . PHP_EOL . PHP_EOL;
}

# Change working directory to this file's folder
chdir(__DIR__);

if( !file_exists($phpDocumentorFile) ) {
	writeInfo(sprintf('Downloading new %s...', $phpDocumentorFile));
	file_put_contents($phpDocumentorFile, fopen($phpDocumentorDownloadUrl, 'r'));
	writeSuccess('File downloaded');
}

writeInfo('Updating composer...');
passthru('composer update');
writeSuccess('Composer up-to-date');

writeInfo('Generating new documentation...');
passthru(sprintf('php phpDocumentor.phar run -d %s -t %s --cache-folder %s', $docSource, $docTarget, $docCache));
writeSuccess('Documentation generated');
