<!-- views/rutas/comentar.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentar Ruta</title>
</head>
<body>
<div class="comentarios">
    <h1>Comentar Ruta</h1>

    <?php if ($ruta) : ?>
        <h2>Datos de la Ruta</h2>
        
        <p>Título: <?= $ruta->getTitulo() ?></p>
        <p>Descripción: <?= $ruta->getDescripcion() ?></p>
        <p>Dificultad: <?= $ruta->getDificultad() ?></p>
        <p>Desnivel: <?= $ruta->getDesnivel() ?></p>
        <p>distancia: <?= $ruta->getDistancia() ?></p>
        <p>Notas: <?= $ruta->getNotas() ?></p>


        <!-- Agrega más campos según sea necesario -->

        <h2>Comentarios</h2>
        <?php if ($comentarios) : ?>
            <ul>
                <?php foreach ($comentarios as $comentario) : ?>
                    <li>
                        <p>Nombre: <?= $comentario->getNombre() ?></p>
                        <p>Contenido: <?= $comentario->getContenido() ?></p>
                        
                        <p>Fecha: <?= $comentario->getFecha() ?></p>
                        <!-- Agrega más campos según sea necesario -->
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No hay comentarios para esta ruta.</p>
        <?php endif; ?>

        <h2>Nuevo Comentario</h2>
        <!-- En la vista 'comentario/comentar' -->
        
<form action="<?= BASE_URL . 'comentario/guardarComentario/?idRuta=' . $ruta->getId() ?>" method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    
    <label for="contenido">Contenido:</label>
    <input type="text" id="contenido" name="contenido" required>

    
    <button type="submit">Comentar</button>

</form>
    <?php else : ?>
        <p>Ruta no encontrada.</p>
    <?php endif; ?>
    </div>


    <?php
// Mostrar el mensaje si existe
if (isset($_SESSION['mensaje'])) {
    echo "<p>{$_SESSION['mensaje']}</p>";
    // Limpiar la variable de sesión después de mostrar el mensaje
    unset($_SESSION['mensaje']);
}
?>
</body>
    
</html>