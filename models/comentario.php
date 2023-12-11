<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Comentario
{
    private ?int $id;
    private string $contenido;
    private ?int $id_usuario;
    private ?int $id_ruta;
    private string $fecha;

    private ?string $nombre;
    private BaseDatos $db;

    public function __construct(
        ?int $id,
        string $contenido,
        ?int $id_usuario,
        ?int $id_ruta,
        string $fecha,
        ?string $nombre
    ) {
        $this->db = new BaseDatos();
        $this->id = $id;
        $this->contenido = $contenido;
        $this->id_usuario = $id_usuario;
        $this->id_ruta = $id_ruta;
        $this->fecha = $fecha;
        $this->nombre = $nombre;
    }

    // Getters y Setters

    public function save()
    {
        if ($this->getId()) {
            return $this->update();
        } else {
            return $this->create();
        }
    }

    public function create(): bool
    {


        try {
            $ins = $this->db->prepare(
                "INSERT INTO rutas_comentarios (contenido, id_usuario, id_ruta, fecha, nombre) 
                 VALUES (:contenido, :id_usuario, :id_ruta, :fecha, :nombre)"
            );
            
            $ins->bindValue(':contenido', $this->contenido, PDO::PARAM_STR);
            $ins->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $ins->bindValue(':id_ruta', $this->id_ruta, PDO::PARAM_INT);
            $ins->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $ins->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            
            $ins->execute();
            
            $this->setId($this->db->lastInsertId());
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update(): bool
    {
        try {
            $upd = $this->db->prepare(
                "UPDATE comentarios 
                 SET contenido = :contenido, id_usuario = :id_usuario, id_ruta = :id_ruta, fecha = :fecha 
                 WHERE id = :id"
            );

            $upd->bindValue(':id', $this->id, PDO::PARAM_INT);
            $upd->bindValue(':contenido', $this->contenido, PDO::PARAM_STR);
            $upd->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $upd->bindValue(':id_ruta', $this->id_ruta, PDO::PARAM_INT);
            $upd->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);

            $upd->execute();
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(): bool
    {
        
        if ($this->getId()) {
            try {
                $del = $this->db->prepare("DELETE FROM rutas_comentarios WHERE id = :id");
                $del->bindValue(':id', $this->id, PDO::PARAM_INT);
                $del->execute();
                
                
                return true;
            } catch (PDOException $e) {
                
                return false;
            }
        }

        return false;
    }

    public static function getById(int $id): ?Comentario
    {
        $db = new BaseDatos();

        $cons = $db->prepare("SELECT * FROM rutas_comentarios WHERE id = :id");
        $cons->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            $cons->execute();

            if ($cons->rowCount() == 1) {
                $result = $cons->fetch(PDO::FETCH_ASSOC);
                
                return new Comentario(
                    $result['id'],
                    $result['contenido'],
                    $result['id_usuario'],
                    $result['id_ruta'],
                    $result['fecha'],
                    $result['nombre']
                );
            }
        } catch (PDOException $e) {
            return null;
        }

        return null;
    }

    public static function getAllByRutaId(int $idRuta): array
    {
        $db = new BaseDatos();
        $comentarios = [];

        $cons = $db->prepare("SELECT * FROM rutas_comentarios WHERE id_ruta = :id_ruta ORDER BY fecha DESC;");
        $cons->bindValue(':id_ruta', $idRuta, PDO::PARAM_INT);
        
        
        

        try {
            $cons->execute();

            $results = $cons->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $result) {
                $comentarios[] = new Comentario(
                    $result['id'],
                    isset($result['contenido']) ? $result['contenido'] : '',
                    $result['id_usuario'],
                    $result['id_ruta'],
                    $result['fecha'],
                    $result['nombre']
                );
            }
        } catch (PDOException $e) {
            return [];
        }

        return $comentarios;
    }

    public static function getUltimaFechaComentarioPorUsuario(int $idUsuario): ?string
    {
        $db = new BaseDatos();

        $cons = $db->prepare("SELECT ultima_fecha_comentario FROM usuarios WHERE id = :idUsuario");
        $cons->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);

        try {
            $cons->execute();
            $result = $cons->fetch(PDO::FETCH_ASSOC);

            if ($result && !empty($result['ultima_fecha_comentario'])) {
                return $result['ultima_fecha_comentario'];
            }
        } catch (PDOException $e) {
            // Manejar el error según tus necesidades
        }

        return null;
    }
    public static function getByUsuarioYRutaYFecha($idUsuario, $idRuta, $fecha)
{
    $db = new BaseDatos();

    // Consulta SQL para obtener el comentario por usuario, ruta y fecha
    $sql = "SELECT * FROM rutas_comentarios WHERE id_usuario = :idUsuario AND id_ruta = :idRuta AND fecha = :fecha";
    
    // Preparar la consulta
    $stmt = $db->prepare($sql);

    // Bind de los parámetros
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':idRuta', $idRuta, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado como un array asociativo
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultado = false;

    foreach ($comentarios as $comentario) {
        // Verificar si hay un comentario con la fecha actual
        if ($comentario['fecha'] == date('Y-m-d')) {
            $resultado = true;
            break; // Terminar el bucle si se encuentra un comentario
        }
    }

    // Devolver el resultado
    return $resultado;
}

    // Getters y Setters
    // ...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getContenido(): string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): void
    {
        $this->contenido = $contenido;
    }

    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getIdRuta(): ?int
    {
        return $this->id_ruta;
    }

    public function setIdRuta(?int $id_ruta): void
    {
        $this->id_ruta = $id_ruta;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): void
    {
        $this->nombre = $nombre;
    }
}