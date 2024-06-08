<?php
// DatabaseConnection.php
//session_start();
require_once('cone.php'); // Usando una ruta relativa

class DatabaseConnection {
    private static $connection;

    public static function getConnection() {
        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    "mysql:host=".DatabaseConfig::$host.";dbname=".DatabaseConfig::$database,
                    DatabaseConfig::$username,
                    DatabaseConfig::$password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Agrega un mensaje de depuración aquí
            } catch (PDOException $e) {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}

?>