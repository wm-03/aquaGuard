<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login & Registration</title>
    <link rel="stylesheet" href="assets/css/login.css">
  </head>
  <body>
    <div class="form-container">
      <!-- Login Form -->
      <form id="login-form" class="form" style="display: block" action="controlador/controlador.php?accion=login" method="post">
        <?php
        if (isset($_SESSION['error_message'])) {
          echo $_SESSION['error_message'];
        }
        ?>
        <h2>Iniciar Sesión</h2>
        <div class="form-control">
          <label for="login-email">Correo</label>
          <input type="email" id="login-email" name="email" required />
        </div>
        <br />
        <div class="form-control">
          <label for="login-password">Contraseña</label>
          <input type="password" id="login-password" name="password" required />
        </div>
        <button type="submit">Iniciar Sesión</button>
        <div class="form-footer">
          <a href="#">Olvidaste tú contraseña?</a>
          <button type="button" onclick="showRegister()">
            No tienes una cueta? Registrate aquí
          </button><br><br>
        </div>
      </form>




      <!-- Registration Form -->
      <form id="register-form" class="form" style="display: none" action="controlador/controlador.php?accion=register" method="post">
        <h2>Registro</h2>
        <div class="form-control">
          <label for="username">Nombre De Usuario</label>
          <input type="text" id="username" name="username" placeholder="Nombre de usuario" required />
        </div><br>
        <div class="form-control">
          <label for="register-email">Correo</label>
          <input type="email" id="register-email" name="emailR" placeholder="Correo" required />
        </div><br>
        <div class="form-control">
          <label for="register-password">Contraseña</label>
          <input type="password" id="register-password" name="passwordR" placeholder="Contraseña" required />
          <input type="password" name="repassword" placeholder="Confirmar contraseña" required>
        </div>
        <button type="submit">Registrarse</button>
        <div class="form-footer">
          <button type="button" onclick="showLogin()">
            Ya tienes una cuenta? Iniciar Sesión
          </button>
        </div>
      </form>
    </div>








    <script>
      // JavaScript functions to show and hide forms
      function showRegister() {
        document.getElementById("login-form").style.display = "none";
        document.getElementById("register-form").style.display = "block";
      }

      function showLogin() {
        document.getElementById("register-form").style.display = "none";
        document.getElementById("login-form").style.display = "block";
      }
    </script>
  </body>
</html>
