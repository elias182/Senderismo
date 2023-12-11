<!-- views/rutas/modificar.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Ruta</title>
</head>
<body>

<div class="nueva-ruta">
    <h1>Modificar Ruta</h1>

    <?php if ($ruta) : ?>
        <form action="<?= BASE_URL . 'ruta/guardarModificacion/?id=' . $ruta->getId() ?>" method="post">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?= $ruta->getTitulo() ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?= $ruta->getDescripcion() ?></textarea>

            <label for="desnivel">Desnivel:</label>
            <input type="number" id="desnivel" name="desnivel" value="<?= $ruta->getDesnivel() ?>">

            <label for="distancia">Distancia:</label>
            <input type="text" id="distancia" name="distancia" value="<?= $ruta->getDistancia() ?>">

            <label for="notas">Notas:</label>
            <textarea id="notas" name="notas"><?= $ruta->getNotas() ?></textarea>

            <label for="dificultad">Dificultad:</label>
            <input type="text" id="dificultad" name="dificultad" value="<?= $ruta->getDificultad() ?>">

            <button type="submit">Guardar Modificación</button>
        </form>
    <?php else : ?>
        <p>Ruta no encontrada.</p>
    <?php endif; ?>
</div>
</body>
</html>