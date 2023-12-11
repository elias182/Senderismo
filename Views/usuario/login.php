<?php if(!isset($_SESSION['identity'])): ?>
<h2>Login</h2>
<form action="<?=BASE_URL?>usuario/login/" method="post"> 
<label for="user">nombre de usuario</label>
<input type="user" name="data[nombre_usuario]" id="user" />
<label for="password">Contraseña</label>
<input type="password" name="data[contrasena]" id="contrasena"/> 
<input type="submit" value="Enviar" />
</form>
<?php else: ?>
<h3><?=$_SESSION['identity']->nombre?></h3>
<?php endif; ?>