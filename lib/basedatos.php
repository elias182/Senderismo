<?php

namespace lib;

use PDO;
use PDOException;

class BaseDatos extends PDO {
    private mixed $resultado;

    public function __construct(
        private $tipo_de_base = 'mysql',
        private string $servidor = SERVIDOR,
        private string $usuario = USUARIO,
        private string $pass = PASS,
        private string $base_datos = BASE_DATOS
    ) {
        try {
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );
            parent::__construct("{$this->tipo_de_base}:dbname={$this->base_datos};host={$this->servidor}", $this->usuario, $this->pass, $opciones);
        } catch (PDOException $e) {
            echo "errores" . $e->getMessage();
        }
    }





    // public function consulta(string $querySQL): void {
    //     $this->resultado = $this->query($querySQL); // Utilizar $this directamente como instancia de PDO
    // }

    // public function extraer_registro(): mixed {
    //     return ($fila = $this->resultado->fetch(PDO::FETCH_ASSOC)) ? $fila : false;
    // }

    // public function extraer_todos(): array {
    //     return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function filasAfectadas(): int {
    //     return $this->resultado->rowCount();
    // }
    // public function prepara(string $querySQL):string {
    //     return $this->resultado->prepare($querySQL);
    // }
}