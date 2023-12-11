<h2>Crear una cuenta</h2> 
<?php use Utils\Utils; ?>
<?php if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'): ?> 
    <strong class="alert_green">Registro completado correctamente</strong> 
    <?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'): ?> 
        <strong class="alert_red">Registro fallido, introduzca bien los datos</strong> 
        <?php endif; ?>
<?php Utils::deleteSession('register'); ?>
<form action="<?=BASE_URL?>usuario/registro/" method="POST"> 

<label for="nombre">Nombre</label>
<input type="text" name="data[nombre]" required/>

<label for="apellidos">nombre de usuario</label>
<input type="text" name="data[nombre_usuario]" required/>


<label for="password">Contraseña</label>
<input type="password" name="data[contrasena]" required/>

<input type="submit" value="Registrarse"/>

<?php
if (isset($_SESSION['register_error'])) {
    echo '<p class="error-message">' . $_SESSION['register_error'] . '</p>';
    unset($_SESSION['register_error']); // Limpiar el mensaje de error después de mostrarlo
}
?>