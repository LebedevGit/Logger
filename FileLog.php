<?php

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class FileLog extends AbstractLogger implements LoggerInterface
{
    public $template = "{date} {level} - {message} {context}";

    private $config;

    public function __construct()
    {
        $this->config = include('config.php');
        if (!file_exists($this->config['filePath'])) {
            touch($this->config['filePath']);
        }
    }

    public function getDate()
    {
        return (new \DateTime())->format($this->config['dateFormat']);
    }

    public function contextToString($context)
    {
        // converting context array into json string, if it's not empty
        return !empty($context) ? json_encode($context) : null;
    }

    public function log($level, $message, array $context = [])
    {
        file_put_contents($this->config['filePath'], trim(strtr($this->template, [
            '{date}' => $this->getDate(),
            '{level}' => $level,
            '{message}' => $message,
            '{context}' => $this->contextToString($context),
        ])) . PHP_EOL, FILE_APPEND);
    }
}
