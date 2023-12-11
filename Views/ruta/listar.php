<?php
use Zebra_Pagination as ZebraPagination;

// Define default values for $itemsPerPage and $currentPage
$itemsPerPage = 6; // Set your desired default value
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Use the query string if available, otherwise default to 1

$zebraPagination = new ZebraPagination();
$zebraPagination->records(count($rutas));
$zebraPagination->records_per_page($itemsPerPage);
$zebraPagination->padding(2); // Adjust padding as needed
$zebraPagination->selectable_pages(10); // Adjust the number of selectable pages as needed

$start = ($currentPage - 1) * $itemsPerPage;
$end = $start + $itemsPerPage;

$rutasToShow = array_slice($rutas, $start, $itemsPerPage, true);

?>

<main class="main">
    <h1>Listado de Rutas</h1>

    <?php if (!empty($rutasToShow)) : ?>
        <table class="ruta-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Desnivel</th>
                    <th>Distancia</th>
                    <th>Dificultad</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rutasToShow as $ruta) : ?>
                    <tr>
                        <td><?= $ruta->getTitulo() ?></td>
                        <td><?= $ruta->getDescripcion() ?></td>
                        <td><?= $ruta->getDesnivel() ?></td>
                        <td><?= $ruta->getDistancia() ?></td>
                        <td><?= $ruta->getDificultad() ?></td>
                        <td>
                            <a href="<?= BASE_URL . 'ruta/comentar/?id=' . $ruta->getId() ?>" class="comment-link">Comentar</a>
                            <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin') : ?>
                                <a href="<?= BASE_URL . 'ruta/borrar/?id=' . $ruta->getId() ?>" class="delete-link">Borrar</a>
                                <a href="<?= BASE_URL . 'ruta/modificar/?id=' . $ruta->getId() ?>" class="edit-link">Modificar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // Render pagination
        echo $zebraPagination->render(true); // Pass true to render the pagination HTML
        ?>

    <?php else : ?>
        <p>No hay rutas disponibles.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin') : ?>
        <div class="new-route-button">
            <a href="<?= BASE_URL . 'ruta/nuevaRuta/' ?>">Nueva Ruta</a>
        </div>
    <?php endif; ?>

</main>

</body>
</html>
