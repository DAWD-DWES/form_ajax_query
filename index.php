<?php
define("RETRO_NOMBRE_FORMATO", "El nombre de estar formado por al menos 3 caracteres de palabra");
define("RETRO_EMAIL_FORMATO", "El correo debe tener un formato correcto");
define("RETRO_PASS_NO_REPETIDO", "Los passwords introducidos deben de ser iguales");
define("RETRO_PASS_FORMATO", "El password debe tener una minúscula, mayúscula, digito y caracter espercial");

if (filter_has_var(INPUT_POST, 'enviar')) {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW);
    $errorUsuarioFormato = (filter_var($usuario, FILTER_VALIDATE_REGEXP, ["options" => [
                    "regexp" => "/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\-]+(\s+[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\-]+){0,5}$/"]]) === false);
    $password1 = filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_UNSAFE_RAW);
    $errorPasswordNoRepetido = ($password1 !== $password2);
    $errorPasswordFormato = (filter_var($password1, FILTER_VALIDATE_REGEXP, ["options" => [
                    "regexp" => "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}/"]]) === false);
    $email = filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW);
    $errorEmailFormato = (filter_var($email, FILTER_VALIDATE_REGEXP, ["options" => [
                    "regexp" => "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i"]]) === false);
    $errors = [];
    if ($errorUsuarioFormato) {
        $errors['usuario'] = RETRO_NOMBRE_FORMATO;
    }
    if ($errorPasswordNoRepetido) {
        $errors['password2'] = RETRO_PASS_NO_REPETIDO;
    } else if ($errorPasswordFormato) {
        $errors['password2'] = RETRO_PASS_FORMATO;
    }
    if ($errorEmailFormato) {
        $errors['email'] = RETRO_EMAIL_FORMATO;
    }
    if (filter_has_var(INPUT_POST, 'enviar')) {
        $response['success'] = empty($errors);
        $response['errors'] = $errors;
        header('Content-type: application/json');
        echo json_encode($response);
        die;
    } else {
        $procesa = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <!-- Bootstrap Font Icon CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <title>Registro</title>
    </head>
    <body class="bg-info">
        <div class="container mt-5">
            <?php if (isset($procesa)): ?>
                <div class="alert alert-success" id="mensaje" role="alert">
                    Registro realizado con éxito
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-center h-100">
                    <div class="card w-50">
                        <div class="card-header">
                            <h3><i class="bi bi-gear p-2"></i>Registro</h3>
                        </div>
                        <div class="card-body">
                            <form id="registro" name="registro" action="index.php" method="POST" novalidate>
                                <div class="input-group my-2">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control"  placeholder="usuario" 
                                           id="usuario" name="usuario" value="<?= $usuario ?? '' ?>" autofocus>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                                <div class="input-group my-2">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" placeholder="contraseña" id="password1" name="password1" 
                                           value="<?= $password1 ?? '' ?>">
                                </div>
                                <div class="input-group my-2">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control"  
                                           placeholder="Repita la contraseña" id="password2" name="password2" value="<?= $password2 ?? '' ?>">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                                <div class="input-group my-2">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control"
                                           placeholder="e-Mail" name="email" id="email" value="<?= $email ?? '' ?>">
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <input type="submit" value="Registrar" class="btn btn-info" name="enviar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity = "sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin = "anonymous"></script>
        <script src="validar.js"></script>
    </body>
</html>
