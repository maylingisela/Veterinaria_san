# Santuario de Mascotas — Sistema de Registro

Sistema en PHP + MySQL para registrar mascotas rescatadas, cumpliendo
con los requisitos: base de datos, conexión segura, clase Mascota
con encapsulamiento, validación de peso, limpieza de datos y
guardado mediante herencia y consultas preparadas.

## Estructura del proyecto

```
santuario_mascotas/
├── config/
│   └── Conexion.php        -> Requisito 2: Conexión segura
├── clases/
│   ├── Mascota.php         -> Requisito 3: Clase Mascota (encapsulamiento)
│   ├── RegistroBD.php      -> Clase base (herencia)
│   └── MascotaDAO.php      -> Requisito 6: Guardado (hereda de RegistroBD)
├── funciones/
│   └── Utilidades.php      -> Requisitos 4 y 5: validación de peso y limpieza
├── sql/
│   └── crear_base_datos.sql -> Requisito 1: Base de datos y tabla Mascotas
├── index.php                -> Formulario de registro
└── registrar.php            -> Ejecuta el flujo completo (pasos 1 al 7)
```

## Cómo ejecutarlo

1. Crear la base de datos ejecutando `sql/crear_base_datos.sql` en MySQL.
2. Ajustar las credenciales (`usuario`, `clave`) en `config/Conexion.php`
   si son distintas a las de tu entorno local.
3. Colocar la carpeta `santuario_mascotas` en tu servidor local
   (por ejemplo, dentro de `htdocs` si usas XAMPP).
4. Abrir `index.php` en el navegador, llenar el formulario y enviarlo.
5. `registrar.php` se encarga de limpiar los datos, validar el peso,
   crear el objeto `Mascota`, conectarse a la base de datos y
   guardar el registro con una consulta preparada, mostrando un
   mensaje de éxito o error al final.

## Flujo esperado del sistema (implementado en registrar.php)

1. Recibir los datos de la mascota.
2. Limpiar la información ingresada.
3. Crear un objeto de la clase Mascota.
4. Validar que el peso sea correcto.
5. Establecer una conexión segura con la base de datos.
6. Guardar la información mediante una consulta preparada.
7. Mostrar un mensaje indicando si el registro fue exitoso o si
   ocurrió un error.
