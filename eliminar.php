<?php


require_once __DIR__ . '/clases/MascotaDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_POST['id'];

$dao = new MascotaDAO();
$resultado = $dao->eliminarMascota($id);

if ($resultado) {
    header('Location: index.php?exito=eliminado');
} else {
    header('Location: index.php?error=1');
}
exit;
