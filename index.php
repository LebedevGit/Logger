<?php
include 'Logger.php';

class SampleClass {
    public $foo = 'bar';
}

$sampleObj = new SampleClass;

$log = Logger::getLogger('StdoutLog');
$log->info('Information text', array($sampleObj));
