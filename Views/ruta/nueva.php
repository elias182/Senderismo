<div class="nueva-ruta">
<h1>Nueva Ruta</h1>

    <form action="<?= BASE_URL . 'ruta/guardarNueva/' ?>" method="post">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"></textarea>

        <label for="desnivel">Desnivel:</label>
        <input type="number" id="desnivel" name="desnivel">

        <label for="distancia">Distancia:</label>
        <input type="number" id="distancia" name="distancia">

        <label for="notas">Notas:</label>
        <textarea id="notas" name="notas"></textarea>

        <label for="dificultad">Dificultad:</label>
        <input type="text" id="dificultad" name="dificultad">

        <button type="submit">Guardar Ruta</button>
    </form>