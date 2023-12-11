<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Ruta
{
    private ?int $id;
    private string $titulo;
    private ?string $descripcion;
    private ?int $desnivel;
    private ?float $distancia;
    private ?string $notas;
    private ?string $dificultad;
    private ?int $id_usuario;
    private BaseDatos $db;

    public function __construct(
        ?int $id,
        string $titulo,
        ?string $descripcion,
        ?int $desnivel,
        ?float $distancia,
        ?string $notas,
        ?string $dificultad,
        ?int $id_usuario
    ) {
        $this->db = new BaseDatos();
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->desnivel = $desnivel;
        $this->distancia = $distancia;
        $this->notas = $notas;
        $this->dificultad = $dificultad;
        $this->id_usuario = $id_usuario;
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
                "INSERT INTO rutas (titulo, descripcion, desnivel, distancia, notas, dificultad, id_usuario) 
                 VALUES (:titulo, :descripcion, :desnivel, :distancia, :notas, :dificultad, :id_usuario)"
            );
            
            $ins->bindValue(':titulo', $this->titulo, PDO::PARAM_STR);
            $ins->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $ins->bindValue(':desnivel', $this->desnivel, PDO::PARAM_INT);
            $ins->bindValue(':distancia', $this->distancia, PDO::PARAM_STR);
            $ins->bindValue(':notas', $this->notas, PDO::PARAM_STR);
            $ins->bindValue(':dificultad', $this->dificultad, PDO::PARAM_STR);
            $ins->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            
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
                "UPDATE rutas 
                 SET titulo = :titulo, descripcion = :descripcion, desnivel = :desnivel, 
                     distancia = :distancia, notas = :notas, dificultad = :dificultad, id_usuario = :id_usuario 
                 WHERE id = :id"
            );

            $upd->bindValue(':id', $this->id, PDO::PARAM_INT);
            $upd->bindValue(':titulo', $this->titulo, PDO::PARAM_STR);
            $upd->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $upd->bindValue(':desnivel', $this->desnivel, PDO::PARAM_INT);
            $upd->bindValue(':distancia', $this->distancia, PDO::PARAM_STR);
            $upd->bindValue(':notas', $this->notas, PDO::PARAM_STR);
            $upd->bindValue(':dificultad', $this->dificultad, PDO::PARAM_STR);
            $upd->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);

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
                // Eliminar comentarios asociados a la ruta
                $delComentarios = $this->db->prepare("DELETE FROM rutas_comentarios WHERE id_ruta = :idRuta");
                $delComentarios->bindParam(':idRuta', $this->id, PDO::PARAM_INT);
                $delComentarios->execute();
    
                // Eliminar la ruta
                $delRuta = $this->db->prepare("DELETE FROM rutas WHERE id = :id");
                $delRuta->bindParam(':id', $this->id, PDO::PARAM_INT);
                $delRuta->execute();
    
                return true;
            } catch (PDOException $e) {
                // Puedes manejar el error según tus necesidades
                return false;
            }
        }
    
        return false;
    }

    public static function getById(int $id): ?Ruta
    {
        $db = new BaseDatos();

        $cons = $db->prepare("SELECT * FROM rutas WHERE id = :id");
        $cons->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            $cons->execute();

            if ($cons->rowCount() == 1) {
                $result = $cons->fetch(PDO::FETCH_ASSOC);
                
                return new Ruta(
                    $result['id'],
                    $result['titulo'],
                    $result['descripcion'],
                    $result['desnivel'],
                    $result['distancia'],
                    $result['notas'],
                    $result['dificultad'],
                    $result['id_usuario']
                );
            }
        } catch (PDOException $e) {
            return null;
        }

        return null;
    }

    public static function getAll(): array
    {
        $db = new BaseDatos();
        $rutas = [];

        $cons = $db->query("SELECT * FROM rutas");

        try {
            $cons->execute();

            $results = $cons->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $result) {
                $rutas[] = new Ruta(
                    $result['id'],
                    $result['titulo'],
                    $result['descripcion'],
                    $result['desnivel'],
                    $result['distancia'],
                    $result['notas'],
                    $result['dificultad'],
                    $result['id_usuario']
                );
            }
        } catch (PDOException $e) {
            return [];
        }

        return $rutas;
    }

    public static function buscarPorTermino(string $termino): array
    {
        $db = new BaseDatos();
        $rutasEncontradas = [];

        $consulta = $db->prepare("SELECT * FROM rutas WHERE titulo LIKE :termino");
        $consulta->bindValue(':termino', '%' . $termino . '%', PDO::PARAM_STR);


        try {
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $resultado) {
                $rutasEncontradas[] = new Ruta(
                    $resultado['id'],
                    $resultado['titulo'],
                    $resultado['descripcion'],
                    $resultado['desnivel'],
                    $resultado['distancia'],
                    $resultado['notas'],
                    $resultado['dificultad'],
                    $resultado['id_usuario']
                    // Incluye otros campos según sea necesario...
                );
            }
        } catch (PDOException $e) {
            echo $e;
        }

        return $rutasEncontradas;
    }




    // Getters y Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getDesnivel(): ?int
    {
        return $this->desnivel;
    }

    public function setDesnivel(?int $desnivel): void
    {
        $this->desnivel = $desnivel;
    }

    public function getDistancia(): ?float
    {
        return $this->distancia;
    }

    public function setDistancia(?float $distancia): void
    {
        $this->distancia = $distancia;
    }

    public function getNotas(): ?string
    {
        return $this->notas;
    }

    public function setNotas(?string $notas): void
    {
        $this->notas = $notas;
    }

    public function getDificultad(): ?string
    {
        return $this->dificultad;
    }

    public function setDificultad(?string $dificultad): void
    {
        $this->dificultad = $dificultad;
    }

    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }
}