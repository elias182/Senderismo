<main class="main">

<?php if (!empty($rutasEncontradas)) : ?>
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
                    <?php foreach ($rutasEncontradas as $ruta) : ?>
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
            <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin') : ?>
            <div class="new-route-button">
                <a href="<?= BASE_URL . 'ruta/nuevaRuta/'?>">Nueva Ruta</a>
            </div>
        <?php endif; ?>
        <?php else : ?>
            <p>No hay rutas disponibles.</p>
        <?php endif; ?>
        </main>