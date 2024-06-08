<?php
// UserController.php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$_SESSION['error_message'] = ' ';

require_once(__DIR__ . '/../modelo/datos.php');

class controlador {
    private $datos;

    public function __construct() {
        $this->datos = new datos();
    }

    public function getUserData($key) {
        return $this->datos->getUserInfo($key);
    }

    public function getTemperatura() {
        $temperatura = $this->datos->getTemperatura();
        echo json_encode($temperatura);
    }
    
    public function getMetales() {
        $metales = $this->datos->getMetales();
        echo json_encode($metales);
    }
    
    public function getPredeterminado($key) {
        $f = $this->datos->getPredeterminado($key);
        return isset($f[0]['modo_predeterminado']) ? $f[0]['modo_predeterminado'] : null;
    }
    
    public function getFlujo() {
        $flujo = $this->datos->getFlujo();
        return isset($flujo[0]['flujo']) ? $flujo[0]['flujo'] : null;
    }
    
    public function getlecturas() {
        $lecturas = $this->datos->getlecturas();
        return isset($lecturas[0]['lecturas']) ? $lecturas[0]['lecturas'] : null;
    }
    
    public function getConsumo() {
        $consumo = $this->datos->getConsumo();
        return isset($consumo[0]['consumo']) ? $consumo[0]['consumo'] : null;
    }

    public function getHisticos($key) {
        return $this->datos->getHisticos($key);
    }

    public function changeFlujo($valor) {
        $datos = new datos();
        $datos->changeFlujo($valor);
    }
    
    public function changeLecturas($valor) {
        $datos = new datos();
        $datos->changeLecturas($valor);
    }
    
    public function saveTemperatura($temp, $agua, $consumo, $ppm, $pCd, $pZn, $pCr, $pPb) {
        $datos = new datos();
        $datos->saveTemperatura($temp, $agua, $consumo, $ppm, $pCd, $pZn, $pCr, $pPb);
    }
    
    function configurarValvula() {
        // Asegurarse de que el contenido recibido es del tipo esperado
        if (empty($_FILES) && empty($_POST)) {
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }
        
        $controlador = new controlador();
    
        // Asignar variables desde $_POST, inicializándolas a null si no existen
        $modeThreshold = isset($_POST['modeThreshold']) ? $_POST['modeThreshold'] : null;
        $modeTimeLimit = $_POST['modeTimeLimit'] ?? null;
        $modeDefault = $_POST['modeDefault'] ?? null;
        $modeMan = $_POST['modeMan'] ?? null;
        
        // $response = [
        //     "message" => "Configuración aplicada con éxito",
        //     "data" => [
        //         "modeThreshold" => $modeThreshold,
        //         "modeTimeLimit" => $modeTimeLimit,
        //         "modeDefault" => $modeDefault,
        //         "modeMan" => $modeMan
        //     ]
        // ];
        //Sólo ajustar modeThreshold si se envió explícitamente
        // if ($modeThreshold !== null) {
        //     $consumoA = $controlador->getConsumo();
        //     $modeThreshold += $consumoA;
        // }
        if ($modeMan != 0) {
            $modeDefault = 2;
        }
    
        $datos = new datos();
        $datos->configurarValvula($modeThreshold, $modeTimeLimit, $modeDefault);
        $datos->changeFlujo(1); // Asumiendo que 1 es el valor correcto para cerrar el flujo
    
        $response = [
            "message" => "Configuración aplicada con éxito",
            "data" => [
                "modeThreshold" => $modeThreshold,
                "modeTimeLimit" => $modeTimeLimit,
                "modeDefault" => $modeDefault,
                "modeMan" => $modeMan
            ]
        ];
    
        // Devolver la respuesta como JSON
        echo json_encode($response);
    }
    
    public function controlarConfiguracion() {
        $key = 531378373;
        $datos = new datos();
        $datosConfiguracion = $datos->getConfFlujo($key);
        
        // Verificar si se obtuvo la configuración
        if (!$datosConfiguracion) {
            throw new Exception("No se pudo obtener la configuración.");
        }
        
        // Extraer configuraciones
        $flujoActual = $datosConfiguracion['flujo'] ?? null; // 0: cerrado, 1: abierto
        // $umbralConsumo = $datos->getConsumoL($key);
        $tiempoLimite = $datosConfiguracion['modo_tiempo_limite'] ?? '00:00:00';
        
        // // Obtener el consumo actual
        // $consumoActual = $datos->getConsumo();
        
        // // Manejo del modo por consumo (umbral)
        // if ($umbralConsumo != 0 && $consumoActual >= $umbralConsumo && $flujoActual == 1) {
        //     // Si el consumo actual supera o iguala el umbral establecido, y el flujo está abierto
        //     $datos->changeFlujo(0); // Cambiar flujo a 0 (cerrado)
        //     $modeThreshold = 0;
        //     $modeTimeLimit = "00:00:00";
        //     $modeDefault = 1;
        //     $datos->configurarValvula($modeThreshold, $modeTimeLimit, $modeDefault); // Reconfigurar válvula con el modo predeterminado
        // }

    
        // Manejo del modo por tiempo
        if ($tiempoLimite != '00:00:00') {
            $horaActual = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $horaActualFormateada = $horaActual->format('H:i:s');
    
            if ($horaActualFormateada >= $tiempoLimite && $flujoActual == 1) {
                // Si la hora actual es mayor o igual al tiempo límite y el flujo está abierto, cerrar el flujo
                $datos->changeFlujo(0); // Cambiar flujo a 0 (cerrado)
                $modeThreshold = 0;
                $modeTimeLimit = "00:00:00";
                $modeDefault = 2;
                $datos->configurarValvula($modeThreshold, $modeTimeLimit, $modeDefault); // Cambiar flujo a 0 (cerrado)
            }
        }
    }

    public function editarUsuario($key) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = new datos();

            if (isset($_POST['pass'])) {
                $pass = $_POST['pass'];
                $datos->editarContrasenia($key, $pass);
            }

            if (isset($_FILES['imagen'])) {
                $imagen = $_FILES['imagen'];
                $datos->editarImagen($key, $imagen);
            }                       

            if (isset($_POST['nombre'])) {
                $nombre = $_POST['nombre'];
                $datos->editarNombre($key, $nombre);
            }

            // exit();
        } else {
            // Manejo de error si la solicitud no es de tipo POST
            echo "Error: Método no permitido";
        }
    }
    
    public function login($email, $password) {
        try {
            $datos = new datos();
    
            // Obtener el hash de la contraseña almacenada en la base de datos para este usuario
            $storedHash = $datos->getHashedPasswordByEmail($email);
            
            // Verificar si la contraseña proporcionada, cuando se hash, coincide con el hash almacenado
            if ($password == $storedHash) {
                // Redireccionar al panel de control u otra página si las credenciales son correctas
                $_SESSION['key'] = $datos->getUserIdByEmail($email); 
                header('Location: ../dashboard.php');
                exit();
            } else {
                // Configurar un mensaje de error y redireccionar a la página de login si las credenciales son inválidas
                $_SESSION['error_message'] = 'Credenciales inválidas';
                header('Location: ../login.php');
                exit();
            }
        } catch (Exception $e) {
            // Capturar cualquier excepción en la lógica del controlador
            $_SESSION['error_message'] = 'Error en el controlador: ' . $e->getMessage();
            header('Location: ../login.php');
            exit();
        }
    }

    public function register($username, $email, $password, $confirmPassword) {
        try {
            if ($password !== $confirmPassword) {
                $_SESSION['error_message'] = 'Las contraseñas no coinciden';
                header('Location: ../login.php');
                exit();
            }

            $key = mt_rand(100000000, 999999999);

            $datos = new datos();

            // Llamada al modelo para registrar al nuevo usuario
            $datos->register($key, $username, $email, $password);
            
            $_SESSION['idUsuario'] = $datos->getUserIdByEmail($email); 
            header('Location: ../dashboard.php');
            exit();
        } catch (Exception $e) {
            // Capturar cualquier excepción en la lógica del controlador
            $_SESSION['error_message'] = 'Error en el controlador: ' . $e->getMessage();
            $_SESSION['e'] = $e->getMessage();
            header('Location: ../login.php');
            exit();
        }
    }
}

if (isset($_GET['accion'])) {
    $controlador = new controlador();

    switch ($_GET['accion']) {
        case 'getData':
            $controlador->getTemperatura();
            break;
        case 'getMetales':
            $controlador->getMetales();
            break;
        case 'changeFlujo':
            $controlador->changeFlujo($_POST['valor']);
            break;
        case 'changeLecturas':
            $controlador->changeLecturas($_POST['valor']);
            break;
        case 'configurarValvula':
            $controlador->configurarValvula();
            break;
        case 'register': // Agrega esta parte para manejar las solicitudes de registro
            $username = $_POST['username'];
            $email = $_POST['emailR'];
            $password = $_POST['passwordR'];
            $confirmPassword = $_POST['repassword'];

            // Verificar si las contraseñas coinciden
            if ($password !== $confirmPassword) {
                $_SESSION['error_message'] = 'Las contraseñas no coinciden';
                header('Location: ../asfvwv.php');
            } else {
                $controlador->register($username, $email, $password, $confirmPassword);
                $_SESSION['error_message'] = "entro a la opción correcta";
            }
            break;

        case 'login': // Agrega esta parte para manejar las solicitudes de inicio de sesión
            $email = $_POST['email'];
            $password = $_POST['password'];
            $controlador->login($email, $password);
            break;

        case 'editarUsuario':
            // Llama al método editarUsuario del controlador
            $controlador->editarUsuario($_SESSION['key']);
            break;
        default:
            // Manejo de error si la acción no es reconocida
            echo "Error: Acción no válida";
            break;
    }
}

?>