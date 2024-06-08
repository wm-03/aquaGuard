<?php
require_once(__DIR__ . '/DatabaseModel.php');
require_once(__DIR__ . '/../controlador/controlador.php');

class cron {

    private $connection;

    public function __construct() {
        $this->connection = DatabaseConnection::getConnection();
    }
}

$controlador = new controlador();
$controlador->controlarConfiguracion();
?>
