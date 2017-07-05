<?php

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class BDLog extends AbstractLogger implements LoggerInterface
{
    public $dsn;
    public $connection;
    
    private $config;

    public function __construct()
    {
        $this->config = include('config.php');
    }
    
    function connect() {
		try {
            $this->dsn = "mysql:host=".$this->config['host'].";dbname=".$this->config['db'];
            $this->connection = new PDO($this->dsn, $this->config['username'], $this->config['password']);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
		} catch(Exception $e) {
			die($e->getMessage());
            return false;
		}
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
        if ( $this->connect() ) {
            $stmt = $this->connection->prepare(
                'INSERT INTO ' . $this->config['table'] . ' (date, level, message, context) ' .
                'VALUES (:date, :level, :message, :context)'
            );

            $stmt->execute(array(
                'date' => $this->getDate(),
                'level' => $level,
                'message' => $message,
                'context' => $this->contextToString($context)
            ));
        } else {
            die('Cannot connect to database.');
        }
    }
}
