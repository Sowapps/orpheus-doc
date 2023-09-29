#!/usr/bin/env php
<?php

/*
 * Use configuration file phpdoc.dist.xml
 * php phpdoc.php
 * 
 * With no configuration file (DO NOT USE)
 * php phpdoc.php -d vendor/orpheus/ -t html/
 */

$phpDocumentorDownloadUrl = 'https://phpdoc.org/phpDocumentor.phar';
$phpDocumentorFile = 'phpDocumentor.phar';
$docSource = realpath('../orpheus/');
$docProject = dirname(__DIR__);
$docOutput = $docProject . '/html';
$docCache = $docProject . '/cache';
$docConfig = $docProject . '/phpdoc.xml';// Path are now working with config file

function writeInfo($text): void {
	echo "\e[34m" . $text . "\e[0m" . PHP_EOL;
}

function writeSuccess($text): void {
	echo "\e[32m" . $text . "\e[0m" . PHP_EOL . PHP_EOL;
}

# Change working directory to this file's folder
$executionPath = __DIR__;
$phpDocumentorPath = $executionPath . '/' . $phpDocumentorFile;
//chdir($executionPath);
//chdir(__DIR__ . '/..');


// phpDocumentor is currently auto corrupting itself
//if( file_exists($phpDocumentorPath) ) {
//	unlink($phpDocumentorPath);
//}

writeInfo(sprintf('Source to generate documentation : %s', $docSource));
writeInfo(sprintf('Phar location : %s', $phpDocumentorPath));
writeInfo(sprintf('Configuration : %s', $docConfig));
writeInfo(sprintf('Output path : %s', $docOutput));
writeInfo(sprintf('Cache path : %s', $docCache));

if( !file_exists($phpDocumentorPath) ) {
	writeInfo(sprintf('Downloading new phar from %s ...', $phpDocumentorDownloadUrl));
	file_put_contents($phpDocumentorPath, fopen($phpDocumentorDownloadUrl, 'r'));
	writeSuccess('File downloaded');
}

//writeInfo('Updating composer...');
//passthru('composer update');
//writeSuccess('Composer up-to-date');

//chdir($docSource);
writeInfo(sprintf('Generating new documentation (%s)...', getcwd()));
//$command = sprintf('php phpDocumentor.phar run -d %s -t %s --cache-folder %s --no-ignore-symlinks', $docSource, $docTarget, $docCache);
$command = sprintf('php -d phar.readonly=on %s run -d "%s" --config "%s"', $phpDocumentorPath, $docSource, $docConfig);
echo $command . "\n";
passthru($command);
writeSuccess('Documentation generated');
