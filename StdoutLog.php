<?php

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class StdoutLog extends AbstractLogger implements LoggerInterface
{
    public $template = "{date} {level} - {message} {context}";
    
    private $config;
    
    public function __construct()
    {
        $this->config = include('config.php');
    }

    public function getDate()
    {
        return (new DateTime())->format($this->config['dateFormat']);
    }

    public function contextToString($context)
    {
        return !empty($context) ? json_encode($context) : null;
    }

    public function Log($level, $message, array $context = [])
    {
        $out = fopen('php://output', 'w');
        fputs($out, trim(strtr($this->template, [
            '{date}' => $this->getDate(),
            '{level}' => $level,
            '{message}' => $message,
            '{context}' => $this->contextToString($context),
        ])));
        fclose($out);
    }
}
