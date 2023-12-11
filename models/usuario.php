<?php

namespace Models;

use Lib\BaseDatos;
use PDOException;
use PDO;

class Usuario
{
    private string|null $id;
    private string $nombre;
    private string $nombreUsuario;
    private string $contrasena;
    private string $rol;
    private BaseDatos $db;

    public function  __construct(string $id, string $nombre, string $nombreUsuario, string $contrasena, string $rol)
    {
        $this->db = new BaseDatos();
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nombreUsuario = $nombreUsuario;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getNombreUsuario(): string
    {
        return $this->nombreUsuario;
    }

    public function getContrasena(): string
    {
        return $this->contrasena;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    // Setters
    public function setId(string|null $id): void
    {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setNombreUsuario(string $nombreUsuario): void
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setContrasena(string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public static function fromArray(array $data): Usuario
    {
        return new Usuario(
            $data['id'] ?? '',
            $data['nombre'] ?? '',
            $data['nombre_usuario'] ?? '', // Cambiado para reflejar el cambio en el nombre de la columna
            $data['contrasena'] ?? '', // Cambiado para reflejar el cambio en el nombre de la columna
            $data['rol'] ?? ''
        );
    }

    public function desconecta(): void
    {
        $this->db == null;
    }

    public function save() { 
        //if(isset($contacto['Contacto']['id']
        if($this->getId()){
            
        return ;
        } else {

        return $this->create();
        }
        }

    public function create(): bool
    {
        $id = NULL;
        $nombre = $this->getNombre();
        $nombreUsuario = $this->getNombreUsuario();
        $contrasena = $this->getContrasena();
        $rol = 'usur';
        
        try {
            $ins = $this->db->prepare("INSERT INTO usuarios (id, nombre, nombre_usuario, contrasena, rol) VALUES (:id, :nombre, :nombreUsuario, :contrasena, :rol)");
            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $ins->bindValue(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $ins->bindValue(':contrasena', $contrasena, PDO::PARAM_STR);
            $ins->bindValue(':rol', $rol, PDO::PARAM_STR);
            var_dump($ins);
            $ins->execute();

            $result = true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $result = false;
        }
        return $result;
    }

    public function login(): bool|object
    {
        $result = false;
        $nombreUsuario = $this->nombreUsuario;
        $contrasena = $this->contrasena;

        $usuario = $this->buscaUsuario($nombreUsuario);

        if ($usuario !== false) {
            $verify = password_verify($contrasena, $usuario->contrasena);
            if ($verify) {
                $result = $usuario;
                $this->nombre = $usuario->nombre;
                $this->rol = $usuario->rol;
                $this->id = $usuario->id;
            }
        }

        return $result;
    }

    public function buscaUsuario($nombreUsuario): bool | object
    {
        $result = false;

        $cons = $this->db->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombreUsuario");
        $cons->bindValue(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);

        try {
            $cons->execute();
            if ($cons && $cons->rowCount() == 1) {
                $result = $cons->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $err) {
            $result = false;
        }

        return $result;
    }

}
?>