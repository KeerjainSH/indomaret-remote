<?php

require '../vendor/autoload.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

class SSHConnection
{
    private static $instance = null;
    private $connection;

    private function __construct($host, $port, $username)
    {
        $this->connection = new SSH2($host, $port);
        $key = PublicKeyLoader::load(file_get_contents(dirname(__FILE__).'/id_ed25519'));

        if (!$this->connection->login($username, $key)) {
            throw new Exception('Failed to authenticate with SSH server');
        }
    }

    public static function getInstance($host, $port, $username)
    {
        if (self::$instance === null) {
            self::$instance = new self($host, $port, $username);
        }
        return self::$instance;
    }

    public function executeCommand($command)
    {
        return $this->connection->exec($command);
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    //  function __wakeup() {}
}

?>
