<?php
require '../../vendor/autoload.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

class SSHConnection
{
    private static $instance = null;
    private $connection;
    private $sftp;

    private function __construct($host, $port, $username)
    {
        $this->connection = new SSH2($host, $port);
        $key = PublicKeyLoader::load(file_get_contents(dirname(__FILE__).'/id_ed25519'));

        if (!$this->connection->login($username, $key)) {
            throw new Exception('Failed to authenticate with SSH server');
        }

        $this->sftp = new SFTP($host, $port);
        if (!$this->sftp->login($username, $key)) {
            throw new Exception('Failed to authenticate with SFTP server');
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

    public function executeCommandWithOutput($command, $output)
    {
        return $this->connection->exec($command, $output);
    }

    public function createDirectoryIfNotExists($remoteDir)
    {
        if (!$this->sftp->file_exists($remoteDir)) {
            if (!$this->sftp->mkdir($remoteDir, -1, true)) {  // recursive creation
                throw new Exception('Failed to create remote directory: ' . $remoteDir);
            }
        }
    }

    public function uploadFile($localFilePath, $remoteFilePath) {
        $remoteDir = dirname($remoteFilePath);
        $this->createDirectoryIfNotExists($remoteDir);
        
        if (!$this->sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
            throw new Exception('Failed to upload file via SFTP');
        }

        echo "File uploaded successfully to $remoteFilePath";
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    //  function __wakeup() {}
}

?>
