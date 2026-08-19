<?php
/**
 * registrar.php
 * -----------------------------------------------------------
 * Flujo esperado del sistema:
 * 1. Recibir los datos de la mascota.
 * 2. Limpiar la información ingresada.
 * 3. Crear un objeto de la clase Mascota.
 * 4. Validar que el peso sea correcto.
 * 5. Establecer una conexión segura con la base de datos.
 * 6. Guardar la información mediante una consulta preparada.
 * 7. Mostrar un mensaje indicando si el registro fue exitoso
 *    o si ocurrió un error.
 */

require_once __DIR__ . '/funciones/Utilidades.php';
require_once __DIR__ . '/clases/Mascota.php';
require_once __DIR__ . '/clases/MascotaDAO.php';

header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// -----------------------------------------------------------
// 1. Recibir los datos de la mascota
// -----------------------------------------------------------
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

// -----------------------------------------------------------
// 2. Limpiar la información ingresada
// -----------------------------------------------------------
$datosLimpios = [];
foreach ($datosRecibidos as $campo => $valor) {
    $datosLimpios[$campo] = limpiarDato((string)$valor);
}

// -----------------------------------------------------------
// 4. Validar que el peso sea correcto (antes de crear el objeto,
//    para poder mostrar un mensaje de error controlado)
// -----------------------------------------------------------
if (!validarPeso($datosLimpios['peso_actual'])) {
    header('Location: index.php?error=peso');
    exit;
}

// -----------------------------------------------------------
// 3. Crear un objeto de la clase Mascota
// -----------------------------------------------------------
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
    header('Location: index.php?error=peso');
    exit;
}

// -----------------------------------------------------------
// 5. Establecer conexión segura y 6. Guardar mediante consulta
//    preparada (todo esto ocurre dentro de MascotaDAO)
// -----------------------------------------------------------
$dao = new MascotaDAO();
$resultado = $dao->guardarMascota($mascota);

// -----------------------------------------------------------
// 7. Mostrar mensaje de éxito o error
// -----------------------------------------------------------
if ($resultado) {
    // El registro queda reflejado en la tabla de index.php
    header('Location: index.php?exito=creado');
} else {
    header('Location: index.php?error=guardar');
}
exit;
