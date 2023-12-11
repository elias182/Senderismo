<?php

namespace Controllers;

use Lib\Pages;
use Models\Ruta;
use Utils\Utils;
use Models\Comentario;
use Models\Usuario;

class RutaController
{
    private Pages $pages;

    public function __construct()
    {
        $this->pages = new Pages();
    }

    public function listar()
    {
        // Obtener todas las rutas desde el modelo
        $rutas = Ruta::getAll();

        // Renderizar la vista con las rutas
        $this->pages->render('ruta/listar', ['rutas' => $rutas]);
    }

    public function borrar(): void
    {
        
        // Obtener el ID de la ruta 
        $idRuta = $_GET['id'] ?? null;

        



        // Verificar si se proporciona un ID de ruta
        if (!$idRuta) {

            return ;
        }

        // Obtener la ruta por su ID
        $ruta = Ruta::getById($idRuta);
        

        // Borrar la ruta si existe
        if ($ruta) {
            $ruta->delete();
        }

        // Redirigir a la lista de rutas
        $this->listar();
    }

    public function nuevaRuta() {
        $this->pages->render('ruta/nueva');
    }
    public function guardarNueva(): void
{
    $error = '';  // Variable para almacenar el mensaje de error

    // Verificar si se reciben datos por POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener y sanitizar los datos del formulario
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $desnivel = filter_input(INPUT_POST, 'desnivel', FILTER_VALIDATE_INT);
        $distancia = filter_input(INPUT_POST, 'distancia', FILTER_VALIDATE_INT);
        $notas = filter_input(INPUT_POST, 'notas', FILTER_SANITIZE_STRING);
        $dificultad = filter_input(INPUT_POST, 'dificultad', FILTER_SANITIZE_STRING);

        // Validar los datos según tus requerimientos
        if (empty($titulo) || strlen($titulo) > 255) {
            $error = 'Error: El campo de título no es válido.';
        } elseif (empty($descripcion)) {
            $error = 'Error: El campo de descripción no puede estar vacío.';
        } elseif ($desnivel === false) {
            $error = 'Error: El campo de desnivel debe ser un número entero válido.';
        } elseif ($distancia === false) {
            $error = 'Error: El campo de distancia debe ser un número entero válido.';
        } elseif (empty($notas)) {
            $error = 'Error: El campo de notas no puede estar vacío.';
        } elseif (empty($dificultad) || strlen($dificultad) > 50 || !in_array($dificultad, ['baja', 'media', 'alta'])) {
            $error = 'Error: El campo de dificultad no es válido.';
        }

        // Si hay algún error, puedes manejarlo según tus necesidades
        if (!empty($error)) {
            include 'Views/ruta/ruta_error.php';  // Incluir la vista de error
            return;
        }

        // Crear una nueva instancia de Ruta con los datos sanitizados
        $nuevaRuta = new Ruta(
            null, // El ID será generado automáticamente
            $titulo,
            $descripcion,
            $desnivel,
            $distancia,
            $notas,
            $dificultad,
            $_SESSION['identity']->id // ID del usuario actual
        );

        // Guardar la nueva ruta en la base de datos
        if ($nuevaRuta->save()) {
            // Redirigir a la página de detalles de la nueva ruta
            header("Location: " . BASE_URL . "ruta/listar/");
            exit();
        } else {
            // Mensaje de error en caso de fallo al guardar
            $error = 'Error: No se pudo guardar la nueva ruta.';
            include 'Views/ruta/ruta_error.php';  // Incluir la vista de error
            return;
        }
    } else {
        // Mensaje de error en caso de que no se envíen datos por POST
        $error = 'Error: No se han recibido datos del formulario.';
        include 'Views/ruta/ruta_error.php';  // Incluir la vista de error
        return;
    }
}
    public function modificar(): void
{
    // Obtener el ID desde la variable GET
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    // Verificar si se proporcionó un ID válido
    if ($id === null || $id <= 0) {
        // Manejar caso en el que el ID no es válido
        // Puedes redirigir a una página de error o hacer algo más
        return;
    }

    // Obtener la ruta con el ID proporcionado
    $ruta = Ruta::getById($id);

    // Verificar si la ruta existe
    if (!$ruta) {
        // Manejar caso en el que la ruta no existe
        // Puedes redirigir a una página de error o hacer algo más
        return;
    }

    // Llamar a la vista de modificar con la ruta
    $this->pages->render('ruta/modificar', ['ruta' => $ruta]);
}
public function guardarModificacion(): void
{
    // Verificar si se reciben datos por POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener el ID desde la variable POST
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        // Verificar si se proporcionó un ID válido
        if ($id === null || $id <= 0) {
            // Manejar caso en que el ID no es válido
            // Puedes redirigir a una página de error o hacer algo más
            echo "<p>ID de ruta no válido.</p>";
            exit();
        }

        // Obtener la ruta con el ID proporcionado
        $ruta = Ruta::getById($id);

        // Verificar si la ruta existe
        if (!$ruta) {
            // Manejar caso en que la ruta no existe
            // Puedes redirigir a una página de error o hacer algo más
            echo "<p>La ruta no existe.</p>";
            exit();
        }

        // Obtener y sanitizar los datos del formulario
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $desnivel = filter_input(INPUT_POST, 'desnivel', FILTER_VALIDATE_INT);
        $distancia = filter_input(INPUT_POST, 'distancia', FILTER_SANITIZE_STRING);
        $notas = filter_input(INPUT_POST, 'notas', FILTER_SANITIZE_STRING);
        $dificultad = filter_input(INPUT_POST, 'dificultad', FILTER_SANITIZE_STRING);

        // Validar los datos según tus requerimientos
        if (empty($titulo) || strlen($titulo) > 255 ||
            empty($descripcion) ||
            $desnivel === false ||
            empty($distancia) ||
            empty($notas) ||
            empty($dificultad) ||
            strlen($dificultad) > 50 ||
            !in_array($dificultad, ['baja', 'media', 'alta'])) {
                $this->pages->render('ruta/modificar', ['ruta' => $ruta]);
            echo "<p>Los datos del formulario no son válidos.</p>";
            exit();
        }

        // Actualizar los datos de la ruta con los valores del formulario
        $ruta->setTitulo($titulo);
        $ruta->setDescripcion($descripcion);
        $ruta->setDesnivel($desnivel);
        $ruta->setDistancia($distancia);
        $ruta->setNotas($notas);
        $ruta->setDificultad($dificultad);

        // Guardar los cambios en la base de datos
        if ($ruta->save()) {
            // Redirigir a la página de detalles de la ruta
            header("Location: " . BASE_URL . "ruta/listar/");
            exit();
        } else {
            // Manejar caso en que la actualización falla
            $this->pages->render('ruta/modificar', ['ruta' => $ruta]);
            echo "<p>Error al guardar los cambios en la ruta.</p>";
            exit();
        }
    }
}

public function comentar(): void
{
    // Obtener el ID de la ruta desde la URL
    $idRuta = $_GET['id'] ?? null;

    // Verificar si se proporciona un ID de ruta
    if (!$idRuta) {
        // Manejar caso en que no se proporciona un ID de ruta
        // Puedes redirigir a una página de error o hacer algo más
        return;
    }

    // Obtener la ruta con el ID proporcionado
    $ruta = Ruta::getById($idRuta);

    // Verificar si la ruta existe
    if (!$ruta) {
        // Manejar caso en que la ruta no existe
        // Puedes redirigir a una página de error o hacer algo más
        return;
    }

    // Obtener los comentarios asociados a la ruta
    $comentarios = Comentario::getAllByRutaId($idRuta);

    // Obtener el usuario desde la sesión (asumiendo que has almacenado el usuario en la sesión)
    $usuario = $_SESSION['identity'] ?? null;


    // Verificar si el usuario está autenticado
    if (!$usuario) {
        // Manejar caso en que el usuario no está autenticado
        // Puedes redirigir a una página de inicio de sesión o hacer algo más
        return;
    }

    // Llamar a la vista de comentar con la ruta, los comentarios y el usuario
    $this->pages->render('comentario/comentar', ['ruta' => $ruta, 'comentarios' => $comentarios, 'usuario' => $usuario]);
}



public function buscarRutas(): void
    {
        $terminoBusqueda = $_POST['q'] ?? '';

        

        // Realizar la búsqueda en la base de datos
        $rutasEncontradas = Ruta::buscarPorTermino($terminoBusqueda);


        // Llamar a la vista para mostrar los resultados de la búsqueda
        $this->pages->render('ruta/buscar', ['rutasEncontradas' => $rutasEncontradas]);
    }

}

?>