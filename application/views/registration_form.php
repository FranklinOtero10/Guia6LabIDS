<html>
<?php
// Validamos si el usuario está logueado
// Si está logueado entonces le redireccionamos
// a la pantalla principal, en nuestro caso
// es la pantalla de Estudiantes
if (isset($this->session->userdata['logged_in'])) {
    redirect("/estudiantes");
}
?>

<head>
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login.css">
</head>

<body>
    <div id="main">
        <div id="login">
            <h2>Registro</h2>
            <hr />
            <div class="error_msg">
                <?= validation_errors() ?>
            </div>
            <form action="<?= site_url('user_authentication/new_user_registration') ?>" method="POST">
                <div class="error_msg">
                    <?php if (isset($message_display)) : ?>
                        <?= $message_display; ?>
                    <?php endif; ?>
                </div>
                <label>Usuario:</label><br>
                <input type="text" name="username" />
                <br>
                <br>
                <label>Email:</label><br>
                <input type="email" name="email_value" />
                <br>
                <br>
                <label>Contraseña:</label><br>
                <input type="password" name="password" />
                <br>
                <br>
                <input type="submit" value="Registrarse" />
            </form>
            <a href="<?php echo base_url() ?> ">Iniciar sesión</a>
        </div>
    </div>
</body>

</html>