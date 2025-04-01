<?php
// database.php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        
        $config = @include __DIR__ . '/../config/config.php';

        if ($config) {
            $this->host     = $config['database']['host'];
            $this->db_name = $config['database']['dbname'];
            $this->username = $config['database']['username'];
            $this->password = $config['database']['password'];
        } else {
            // Fallback to hardcoded values (for local dev)
            $this->host     = 'localhost';
            $this->db_name  = 'local_db';
            $this->username = 'root';
            $this->password = '';
        }
    }
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        
        return $this->conn;
    }
}
?>