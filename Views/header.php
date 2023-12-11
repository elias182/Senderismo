<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senderismo - Explora la naturaleza</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
</head>
<body>

<header class="header">
    <div class="logo">
        <h1>SENDERISMO</h1>
    </div>
    <nav class="nav">
        <ul>
            <?php if (isset($_SESSION['identity'])): ?>
                <li><a href="<?= BASE_URL ?>ruta/listar/">Todas las Rutas</a></li>
                <li><a href="<?= BASE_URL ?>usuario/logout/">Cerrar sesión</a></li>
                <?php if ($_SESSION['identity']->rol == 'admin'): ?>
                    <!-- Opciones adicionales para administradores -->
                <?php endif; ?>
                <li>
                    <?=$_SESSION['identity']->nombre?>
                </li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>usuario/registro/">Crear cuenta</a></li>
                <li><a href="<?= BASE_URL ?>usuario/identifica/">Identifícate</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="search">
        <form action="<?= BASE_URL . 'ruta/buscarRutas/'?>" method="post">
            <div class="search-container">
                <input type="text" name="q" placeholder="Buscar rutas..." class="search-input">
                <button type="submit" class="search-button">
                    buscar
                </button>
            </div>
        </form>
    </div>
</header>

<!-- Resto del contenido -->

</body>
</html>