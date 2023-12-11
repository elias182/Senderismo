<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

session_start();

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__) ; // para acceder a1 contenido del archivo . env
$dotenv->safeLoad(); // si no existe no nos marca error


require_once "config/config.php";



use Controllers\FrontController;
FrontController::main();
?>


</body>
</html>