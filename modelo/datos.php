<?php
require_once(__DIR__ . '/DatabaseModel.php');


error_reporting(E_ALL);
ini_set('display_errors', '1');

class datos {

    private $connection;

    public function __construct() {
        $this->connection = DatabaseConnection::getConnection();
    }

    public function getUserInfo($key) {
        $sql = "SELECT * FROM usuario WHERE `key` = :key";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
    
        // Devuelve la información del usuario como un array asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    
    
    public function getTemperatura() {
        $sql = "SELECT * FROM datos ORDER BY fecha DESC LIMIT 1";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getMetales() {
        $sql = "SELECT * FROM metales";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getFlujo() {
        $sql = "SELECT flujo FROM datos ORDER BY fecha DESC LIMIT 1";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getlecturas() {
        $sql = "SELECT lecturas FROM datos ORDER BY fecha DESC LIMIT 1";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getConsumo() {
        $sql = "SELECT consumo FROM datos";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getConsumoL($key) {
        $sql = "SELECT modo_umbral FROM datos WHERE usuario_key = :key ";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHisticos($key) {
        $sql = "SELECT fecha, consumo, ppm, temp FROM historial WHERE usuario_key = :key";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function changeLecturas($valor) {
        try {
            $sqlUpdate = "UPDATE datos SET lecturas = :lecturas WHERE usuario_key = :key";
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':lecturas', $valor);
            $stmtUpdate->bindValue(':key', 531378373, PDO::PARAM_INT); // Usa bindValue para valores fijos
            $stmtUpdate->execute();            
    
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }
    
    public function changeFlujo($valor) {
        try {
            $sqlUpdate = "UPDATE datos SET flujo = :flujo WHERE usuario_key = :key";
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':flujo', $valor);
            $stmtUpdate->bindValue(':key', 531378373, PDO::PARAM_INT); // Usa bindValue para valores fijos
            $stmtUpdate->execute();            
    
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }
    
    public function getPredeterminado($key) {
        $sql = "SELECT modo_predeterminado FROM datos WHERE usuario_key = :key";
    
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':key', $key, PDO::PARAM_INT); // Suponiendo un valor fijo como ejemplo
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function configurarValvula($modeThreshold, $modeTimeLimit, $modeDefault) {
        try {
            // Preparar la consulta SQL para actualizar la configuración de la válvula
            $sqlUpdate = "UPDATE datos SET
                modo_umbral = :modoUmbral,
                modo_tiempo_limite = :modoTiempoLimite,
                modo_predeterminado = :modoPredeterminado
                WHERE usuario_key = :key";
    
            // Preparar la sentencia
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
    
            // Vincular los parámetros a la sentencia
            $stmtUpdate->bindParam(':modoUmbral', $modeThreshold, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':modoTiempoLimite', $modeTimeLimit, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':modoPredeterminado', $modeDefault, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':key', 531378373, PDO::PARAM_INT); // Suponiendo un valor fijo como ejemplo
    
            // Ejecutar la sentencia
            $stmtUpdate->execute();
    
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }
    
    public function getConfFlujo($key) {
        $sql = "SELECT flujo, modo_umbral, modo_tiempo_limite, modo_predeterminado FROM datos WHERE usuario_key = :key";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':key', $key); // Ejemplo de un identificador de usuario
        $stmt->execute();
    
        // Devuelve la información del usuario como un array asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function saveTemperatura($temp, $agua, $consumo, $ppm, $pCd, $pZn, $pCr, $pPb) {
        try {
            $sqlUpdate = "UPDATE datos SET temperatura = :temperatura, agua = :agua, consumo = :consumo , ppm = :ppm WHERE usuario_key = :key";
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':temperatura', $temp);
            $stmtUpdate->bindParam(':agua', $agua);
            $stmtUpdate->bindParam(':consumo', $consumo);
            $stmtUpdate->bindParam(':ppm', $ppm);
            $stmtUpdate->bindValue(':key', 531378373, PDO::PARAM_INT); // Usa bindValue para valores fijos
            $stmtUpdate->execute();   
            
            $sqlUpdate = "UPDATE metales SET pCd = :pCd, pZn = :pZn, pCr = :pCr , pPb = :pPb WHERE datos_idDatos = :key";
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':pCd', $pCd);
            $stmtUpdate->bindParam(':pZn', $pZn);
            $stmtUpdate->bindParam(':pCr', $pCr);
            $stmtUpdate->bindParam(':pPb', $pPb);
            $stmtUpdate->bindValue(':key', 531378373, PDO::PARAM_INT); // Usa bindValue para valores fijos
            $stmtUpdate->execute();
        
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }

    public function editarContrasenia($key, $pass) {
        try {
            $sql = "UPDATE usuario SET contrasenia = :pass WHERE usuario.key = :key";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':key', $key, PDO::PARAM_STR);
            $stmt->execute();
            // Puedes agregar más lógica aquí si es necesario
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }

    public function editarImagen($key, $imagen) {
        try {
            // Obtener la extensión del archivo
            $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
    
            // Crear un nombre único para el archivo
            $nombreArchivo = 'user_' . $key . '.' . $extension;
    
            // Ruta de destino para guardar la nueva imagen
            $rutaDestino = '../assets/image/usr/' . $nombreArchivo;
    
            // Obtener la ruta de la imagen actual del usuario
            $sqlImagenActual = "SELECT imgUsu FROM usuario WHERE `key` = :key";
            $stmtImagenActual = $this->connection->prepare($sqlImagenActual);
            $stmtImagenActual->bindParam(':key', $key, PDO::PARAM_STR);
            $stmtImagenActual->execute();
            $imagenActual = $stmtImagenActual->fetch(PDO::FETCH_ASSOC)['imgUsu'];
    
            // Eliminar la imagen actual si existe
            if (!empty($imagenActual) && file_exists($imagenActual)) {
                unlink($imagenActual);
            }
    
            // Mover el nuevo archivo a la ubicación deseada
            if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                // Actualizar la base de datos con la nueva ruta de la imagen
                $sql = "UPDATE usuario SET imgUsu = :imagen WHERE `key` = :key";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindParam(':imagen', $nombreArchivo, PDO::PARAM_STR);
                $stmt->bindParam(':key', $key, PDO::PARAM_STR);
                $stmt->execute();
    
                // Puedes agregar más lógica aquí si es necesario
            } else {
                throw new Exception("Error al mover el archivo a la ruta deseada.");
            }
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }          

    public function editarNombre($key, $nombre) {
        try {
            $sql = "UPDATE usuario SET nombreUsu = :nombre WHERE usuario.key = :key";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':key', $key, PDO::PARAM_STR);
            $stmt->execute();
            // Puedes agregar más lógica aquí si es necesario
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }
    
    public function getHashedPasswordByEmail($email) {
        $sql = "SELECT contrasenia FROM usuario WHERE correo = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['contrasenia'];
        } else {
            return null; // o manejar de otra manera si el correo no existe
        }
    }

    public function getUserIdByEmail($email) {
        try {
            $sql = "SELECT `key` FROM usuario WHERE correo = :email";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            // Obtener los resultados como un array asociativo
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }

    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM usuario WHERE correo = :email AND contrasenia = :password";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            // Obtener los resultados como un array asociativo
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }    

    public function register($key, $username, $email, $password) {

        try {
            // Encriptar la contraseña antes de guardarla en la base de datos

            $sql = "INSERT INTO usuario (`key`, nombreUsu, correo, contrasenia, imgUsu) VALUES (:key, :username, :email, :hashedPassword, 'usr.png')";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':key', $key, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':hashedPassword', $password, PDO::PARAM_STR); // Asegúrate de enlazar la contraseña encriptada
            $stmt->execute();
            // Puedes agregar más lógica aquí si es necesario
        } catch (PDOException $e) {
            // Captura cualquier excepción relacionada con la base de datos
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }    

}
?>
