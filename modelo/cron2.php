<?php
require_once(__DIR__ . '/DatabaseModel.php');

class cron2 {

    private $connection;

    public function __construct() {
        $this->connection = DatabaseConnection::getConnection();
    }

    public function auto() {
        try {
            $sql = "SELECT temperatura ,consumo, ppm, usuario_key
                    FROM datos 
                    ORDER BY fecha DESC 
                    LIMIT 1";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();  
            $result_datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result_datos) {
                $temperatura_datos = $result_datos["temperatura"];
                $consumo_datos = $result_datos["consumo"];
                $ppm_datos = $result_datos["ppm"];
                $usuario_key = $result_datos["usuario_key"];
            }
        
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }

        try {
            $sql = "INSERT INTO historial (consumo, ppm, temp, usuario_key) VALUES (:consumo, :ppm, :temp, :key)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':consumo', $consumo_datos);
            $stmt->bindValue(':ppm', $ppm_datos);
            $stmt->bindValue(':temp', $temperatura_datos);
            $stmt->bindValue(':key', $usuario_key);
            $stmt->execute();            
            
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
        
        try {
            $sqlUpdate = "UPDATE datos SET consumo = 0 WHERE usuario_key = :key";
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':key', 531378373, PDO::PARAM_INT); // Usa bindValue para valores fijos
            $stmtUpdate->execute();            
    
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }
}

$cron = new cron2();
$cron->auto();

?>
