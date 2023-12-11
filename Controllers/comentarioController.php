<?php

namespace Controllers;

use Models\Comentario;
use Models\Ruta;
use Models\Usuario;

class ComentarioController
{

    public function guardarComentario(): void
{
    // Obtener el ID de la ruta y el nombre desde la URL
    $idRuta = filter_input(INPUT_GET, 'idRuta', FILTER_VALIDATE_INT);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $contenido = filter_input(INPUT_POST, 'contenido', FILTER_SANITIZE_STRING);

    // Obtener el usuario actual de la sesión
    $idUsuario = $_SESSION['identity']->id ?? null;

    // Verificar si se proporciona un ID de ruta, el usuario está autenticado y se proporciona un nombre
    if ($idRuta && $idUsuario && $nombre && $contenido) {

        // Verificar si el usuario ya ha comentado hoy
        $fechaActual = date('Y-m-d');
        $comentarioExistente = Comentario::getByUsuarioYRutaYFecha($idUsuario, $idRuta, $fechaActual);

        if ($comentarioExistente) {
            // El usuario ya ha comentado hoy, almacenar el mensaje en la variable de sesión
            $_SESSION['mensaje'] = "Ya has comentado hoy. No puedes hacer más comentarios.";
        } else {

            // Crear una nueva instancia de Comentario con los datos del formulario
            $nuevoComentario = new Comentario(
                null, // El ID será generado automáticamente
                $contenido,
                $idUsuario, // ID del usuario actual
                $idRuta,
                $fechaActual, // Fecha actual en formato date
                $nombre
            );

            // Guardar el nuevo comentario en la base de datos
            $nuevoComentario->save();

            // Redirigir a la página de comentarios
            header("Location: " . BASE_URL . 'ruta/comentar/?id=' . $idRuta);
            exit; // Asegurarse de que la redirección se efectúe correctamente
        }
    } else {
        // Manejar caso en que falten datos necesarios
        $_SESSION['mensaje'] = "Faltan datos necesarios para hacer el comentario.";
    }

    // Redirigir a la misma página
    header("Location: " . BASE_URL . 'ruta/comentar/?id=' . $idRuta);
    exit;
}
}