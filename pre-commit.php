<?php

//use PhpGitHooks\Infrastructure\Hook\PreCommit;
//$hook = new PreCommit();
//$hook->run();

use Planet\Code\CodeSniffer;
use Planet\Code\CodeStyle;
use Planet\Code\GitWrapper;
use Planet\Code\HookMessage;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

$vendorDir = __DIR__ . '/vendor/';
require $vendorDir . 'autoload.php';

$gitWrapper = new GitWrapper('git', __DIR__);
$phpFiles = $gitWrapper->getFileSetToBeCommitted('.php');

//print_r($phpFiles);

echo "\n";
echo HookMessage::HEADER;
echo "\n";

$exitCode = 0;
$preCommitHandler = new CodeSniffer(true, $vendorDir);
foreach ($phpFiles as $phpFile) {
    $exitCode += $preCommitHandler->analize($phpFile, CodeStyle::PHP);
}

if ($exitCode > 0) {
    echo "\n";
    echo HookMessage::COMMIT_ERROR;
    
    exit(1);
} else {
    echo "\n";
    echo "\n";
    echo HookMessage::COMMIT_OK;
    
    exit(1);
}
