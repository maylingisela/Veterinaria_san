<?php


require_once __DIR__ . '/clases/MascotaDAO.php';

header('Content-Type: text/html; charset=utf-8');

// -----------------------------------------------------------
// Paginación: cantidad de registros por página y página actual
// -----------------------------------------------------------
$porPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) {
    $paginaActual = 1;
}

$dao = new MascotaDAO();
$totalRegistros = $dao->contarMascotas();
$totalPaginas = (int)ceil($totalRegistros / $porPagina);
if ($totalPaginas < 1) {
    $totalPaginas = 1;
}
if ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$offset = ($paginaActual - 1) * $porPagina;
$mascotas = $dao->listarMascotas($porPagina, $offset);

// -----------------------------------------------------------
// Mensajes de error (formulario de registro)
// -----------------------------------------------------------
$mensajesError = [
    'peso'    => 'El peso ingresado debe ser numérico y mayor que cero.',
    'guardar' => 'No fue posible registrar la mascota. Intente nuevamente.',
];
$errorClave = $_GET['error'] ?? null;

// -----------------------------------------------------------
// Mensajes de éxito (registrar / actualizar / eliminar)
// -----------------------------------------------------------
$mensajesExito = [
    'creado'      => 'La mascota fue registrada correctamente.',
    'actualizado' => 'La mascota fue actualizada correctamente.',
    'eliminado'   => 'La mascota fue eliminada correctamente.',
];
$exitoClave = $_GET['exito'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santuario de Mascotas - Registro</title>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
    <h1>Registro de Mascota</h1>

    <?php if ($errorClave && isset($mensajesError[$errorClave])): ?>
        <div class="mensaje mensaje-error"><?= htmlspecialchars($mensajesError[$errorClave]) ?></div>
    <?php endif; ?>

    <?php if ($exitoClave && isset($mensajesExito[$exitoClave])): ?>
        <div class="mensaje mensaje-exito"><?= htmlspecialchars($mensajesExito[$exitoClave]) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?>
        <div class="mensaje mensaje-error">
            Ocurrió un error al procesar la solicitud. Intente nuevamente.
        </div>
    <?php endif; ?>

    <form class="formulario" action="registrar.php" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="especie">Especie</label>
        <input type="text" id="especie" name="especie" required>

        <label for="raza">Raza</label>
        <input type="text" id="raza" name="raza" required>

        <label for="edad">Edad</label>
        <input type="number" id="edad" name="edad" min="0" required>

        <label for="peso_actual">Peso actual (kg)</label>
        <input type="text" id="peso_actual" name="peso_actual" required>

        <label for="color_senas">Color o señas físicas</label>
        <input type="text" id="color_senas" name="color_senas" required>

        <label for="nombre_responsable">Nombre del responsable</label>
        <input type="text" id="nombre_responsable" name="nombre_responsable" required>

        <label for="telefono_emergencia">Teléfono de emergencia</label>
        <input type="text" id="telefono_emergencia" name="telefono_emergencia" required>

        <button type="submit">Registrar mascota</button>
    </form>

    <h1>Mascotas registradas</h1>

    <?php if (empty($mascotas)): ?>
        <p class="sin-datos">Todavía no hay mascotas registradas.</p>
    <?php else: ?>
        <table class="tabla-mascotas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Edad</th>
                    <th>Peso (kg)</th>
                    <th>Color / Señas</th>
                    <th>Responsable</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mascotas as $mascota): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$mascota['id']) ?></td>
                        <td><?= htmlspecialchars($mascota['nombre']) ?></td>
                        <td><?= htmlspecialchars($mascota['especie']) ?></td>
                        <td><?= htmlspecialchars($mascota['raza']) ?></td>
                        <td><?= htmlspecialchars((string)$mascota['edad']) ?></td>
                        <td><?= htmlspecialchars((string)$mascota['peso_actual']) ?></td>
                        <td><?= htmlspecialchars($mascota['color_senas']) ?></td>
                        <td><?= htmlspecialchars($mascota['nombre_responsable']) ?></td>
                        <td><?= htmlspecialchars($mascota['telefono_emergencia']) ?></td>
                        <td class="acciones">
                            <a class="btn-icono btn-editar"
                               href="editar.php?id=<?= (int)$mascota['id'] ?>"
                               title="Modificar">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                </svg>
                            </a>
                            <button class="btn-icono btn-eliminar"
                                    type="button"
                                    title="Eliminar"
                                    onclick="confirmarEliminar(<?= (int)$mascota['id'] ?>, '<?= htmlspecialchars(addslashes($mascota['nombre'])) ?>')">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                    <path d="M10 11v6"></path>
                                    <path d="M14 11v6"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulario oculto usado para enviar la eliminación por POST -->
        <form id="formEliminar" action="eliminar.php" method="POST" style="display:none;">
            <input type="hidden" name="id" id="idEliminar" value="">
        </form>

        <div class="paginacion">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i === $paginaActual): ?>
                    <span class="pagina-btn pagina-actual"><?= $i ?></span>
                <?php else: ?>
                    <a class="pagina-btn" href="index.php?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a class="pagina-btn pagina-siguiente" href="index.php?pagina=<?= $paginaActual + 1 ?>">&raquo;</a>
            <?php else: ?>
                <span class="pagina-btn pagina-siguiente deshabilitado">&raquo;</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <script>
        function confirmarEliminar(id, nombre) {
            var confirmado = confirm('¿Está seguro de que desea eliminar a "' + nombre + '"? Esta acción no se puede deshacer.');
            if (confirmado) {
                document.getElementById('idEliminar').value = id;
                document.getElementById('formEliminar').submit();
            }
        }
    </script>
</body>
</html>
