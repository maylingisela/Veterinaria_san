<?php


require_once __DIR__ . '/funciones/Utilidades.php';
require_once __DIR__ . '/clases/Mascota.php';
require_once __DIR__ . '/clases/MascotaDAO.php';

header('Content-Type: text/html; charset=utf-8');

$dao = new MascotaDAO();

// -----------------------------------------------------------
// Si el formulario fue enviado (POST): procesar la actualización
// -----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    $datosRecibidos = [
        'nombre'               => $_POST['nombre']               ?? '',
        'especie'               => $_POST['especie']               ?? '',
        'raza'                   => $_POST['raza']                   ?? '',
        'edad'                   => $_POST['edad']                   ?? '',
        'peso_actual'            => $_POST['peso_actual']            ?? '',
        'color_senas'            => $_POST['color_senas']            ?? '',
        'nombre_responsable'     => $_POST['nombre_responsable']     ?? '',
        'telefono_emergencia'    => $_POST['telefono_emergencia']    ?? '',
    ];

    $datosLimpios = [];
    foreach ($datosRecibidos as $campo => $valor) {
        $datosLimpios[$campo] = limpiarDato((string)$valor);
    }

    if (!validarPeso($datosLimpios['peso_actual'])) {
        header('Location: editar.php?id=' . $id . '&error=peso');
        exit;
    }

    try {
        $mascota = new Mascota(
            $datosLimpios['nombre'],
            $datosLimpios['especie'],
            $datosLimpios['raza'],
            (int)$datosLimpios['edad'],
            (float)$datosLimpios['peso_actual'],
            $datosLimpios['color_senas'],
            $datosLimpios['nombre_responsable'],
            $datosLimpios['telefono_emergencia']
        );
    } catch (InvalidArgumentException $e) {
        header('Location: editar.php?id=' . $id . '&error=datos');
        exit;
    }

    $actualizado = $dao->actualizarMascota($id, $mascota);

    if ($actualizado) {
        header('Location: index.php?exito=actualizado');
    } else {
        header('Location: editar.php?id=' . $id . '&error=guardar');
    }
    exit;
}

// -----------------------------------------------------------
// Si es GET: cargar los datos actuales de la mascota
// -----------------------------------------------------------
$id = (int)($_GET['id'] ?? 0);
$mascota = $id > 0 ? $dao->obtenerPorId($id) : null;

if (!$mascota) {
    header('Location: index.php?error=1');
    exit;
}

$mensajesError = [
    'peso'    => 'El peso ingresado debe ser numérico y mayor que cero.',
    'datos'   => 'Alguno de los datos ingresados no es válido.',
    'guardar' => 'No fue posible guardar los cambios. Intente nuevamente.',
];
$errorClave = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santuario de Mascotas - Editar</title>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
    <div class="barra-nav">
        <a href="index.php">&laquo; Volver al listado</a>
    </div>

    <h1>Modificar mascota</h1>

    <?php if ($errorClave && isset($mensajesError[$errorClave])): ?>
        <div class="mensaje mensaje-error"><?= htmlspecialchars($mensajesError[$errorClave]) ?></div>
    <?php endif; ?>

    <form class="formulario" action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?= (int)$mascota['id'] ?>">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($mascota['nombre']) ?>" required>

        <label for="especie">Especie</label>
        <input type="text" id="especie" name="especie" value="<?= htmlspecialchars($mascota['especie']) ?>" required>

        <label for="raza">Raza</label>
        <input type="text" id="raza" name="raza" value="<?= htmlspecialchars($mascota['raza']) ?>" required>

        <label for="edad">Edad</label>
        <input type="number" id="edad" name="edad" min="0" value="<?= htmlspecialchars((string)$mascota['edad']) ?>" required>

        <label for="peso_actual">Peso actual (kg)</label>
        <input type="text" id="peso_actual" name="peso_actual" value="<?= htmlspecialchars((string)$mascota['peso_actual']) ?>" required>

        <label for="color_senas">Color o señas físicas</label>
        <input type="text" id="color_senas" name="color_senas" value="<?= htmlspecialchars($mascota['color_senas']) ?>" required>

        <label for="nombre_responsable">Nombre del responsable</label>
        <input type="text" id="nombre_responsable" name="nombre_responsable" value="<?= htmlspecialchars($mascota['nombre_responsable']) ?>" required>

        <label for="telefono_emergencia">Teléfono de emergencia</label>
        <input type="text" id="telefono_emergencia" name="telefono_emergencia" value="<?= htmlspecialchars($mascota['telefono_emergencia']) ?>" required>

        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>
