<?php
class User {
    private $conn;
    private $table_name = "users";
    
    public $id;
    public $username;
    public $email;
    public $password;
    public $direccion;
    public $telefono;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Método para crear un nuevo usuario
    public function create() {

        $query = "INSERT INTO " . $this->table_name . " (username, email, password, direccion, telefono) VALUES (:username, :email, :password, :direccion, :telefono)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar datos
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        
        // Hash de la contraseña
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Vincular los valores
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Método para verificar si el usuario existe
    public function emailExists() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Vincular
        $stmt->bindParam(":email", $this->email);
        
        // Ejecutar
        $stmt->execute();
        
        // Obtener número de filas
        $num = $stmt->rowCount();
        
        if($num > 0) {
            // Obtener los valores
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar valores a propiedades del objeto
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            
            return true;
        }
        
        return false;
    }
    public function getUsr($id) {
        $stmt = $this->conn->prepare("
            SELECT id, username, email, direccion, telefono
            FROM users
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>