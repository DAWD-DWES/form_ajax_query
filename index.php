<?php
define("RETRO_NOMBRE_FORMATO", "El nombre debe estar formado por al menos 3 caracteres de palabra.");
define("RETRO_EMAIL_FORMATO", "El correo debe tener un formato correcto.");
define("RETRO_PASS_NO_REPETIDO", "Las contraseñas introducidas deben ser iguales.");
define("RETRO_PASS_FORMATO", "El password debe tener una minúscula, mayúscula, dígito y carácter especial.");

$usuario = filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW);
$password1 = filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW);
$password2 = filter_input(INPUT_POST, 'password2', FILTER_UNSAFE_RAW);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación del lado del servidor
    $errorUsuarioFormato = !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\-]+(\s+[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\- ]+){0,5}$/", $usuario ?? '') || mb_strlen(trim($usuario ?? '')) < 3;
    $errorPasswordFormato = !preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}/", $password1 ?? '');
    $errorPasswordNoRepetido = $password1 !== $password2;
    $errorEmailFormato = !preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $email ?? '');

    if ($errorUsuarioFormato) {
        $errors['usuario'] = RETRO_NOMBRE_FORMATO;
    }
    if ($errorPasswordFormato) {
        $errors['password1'] = RETRO_PASS_FORMATO;
    }
    if ($errorPasswordNoRepetido) {
        $errors['password2'] = RETRO_PASS_NO_REPETIDO;
    }
    if ($errorEmailFormato) {
        $errors['email'] = RETRO_EMAIL_FORMATO;
    }

    // Si la petición es AJAX, devolver JSON
    if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => empty($errors),
            'errors' => $errors
        ]);
        exit;
    }

    // Si no es AJAX y no hay errores, marcar como procesado
    $procesa = empty($errors);
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    </head>
    <body class="bg-info">
        <div class="container mt-5">
            <?php if ($procesa ?? false): ?>
                <div class="alert alert-success" id="mensaje" role="alert">
                    Registro realizado con éxito
                </div>
            <?php endif ?>
            <div class="d-flex justify-content-center h-100">
                <div class="card w-50">
                    <div class="card-header">
                        <h3><i class="bi bi-gear p-2"></i>Registro</h3>
                    </div>
                    <div class="card-body">
                        <form id="registro" method="POST" action="index.php" novalidate>
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control <?= isset($errors['usuario']) ? 'is-invalid' : '' ?>" 
                                       id="usuario" name="usuario" placeholder="usuario" 
                                       value="<?= htmlspecialchars($usuario ?? '') ?>">
                                <div class="invalid-feedback"><?= $errors['usuario'] ?? '' ?></div>
                            </div>

                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control <?= isset($errors['password1']) ? 'is-invalid' : '' ?>" 
                                       id="password1" name="password1" placeholder="contraseña" 
                                       value="<?= htmlspecialchars($password1 ?? '') ?>">
                                <div class="invalid-feedback"><?= $errors['password1'] ?? '' ?></div>
                            </div>

                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control <?= isset($errors['password2']) ? 'is-invalid' : '' ?>" 
                                       id="password2" name="password2" placeholder="Repite la contraseña" 
                                       value="<?= htmlspecialchars($password2 ?? '') ?>">
                                <div class="invalid-feedback"><?= $errors['password2'] ?? '' ?></div>
                            </div>

                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" placeholder="e-Mail" 
                                       value="<?= htmlspecialchars($email ?? '') ?>">
                                <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                            </div>

                            <div class="text-end">
                                <input type="submit" value="Registrar" class="btn btn-info" name="enviar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="validar.js"></script>
    </body>
</html>
