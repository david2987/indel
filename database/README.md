# Instalación del Sistema de Promociones

## Pasos para configurar la base de datos

1. **Crear la base de datos:**
   - Abre phpMyAdmin o tu cliente MySQL
   - Importa el archivo `promociones.sql` que se encuentra en la carpeta `database/`
   - O ejecuta el script SQL manualmente

2. **Configurar la conexión:**
   - Abre el archivo `config/database.php`
   - Ajusta las constantes según tu configuración:
     ```php
     define('DB_HOST', 'localhost');  // Tu servidor MySQL
     define('DB_USER', 'root');      // Tu usuario MySQL
     define('DB_PASS', '');          // Tu contraseña MySQL
     define('DB_NAME', 'indel_db'); // Nombre de la base de datos
     ```

3. **Crear el directorio de imágenes:**
   - Asegúrate de que existe el directorio `Recursos/imagenes/promociones/`
   - Si no existe, créalo manualmente con permisos de escritura (755)

## Configuración del Backoffice

1. **Credenciales de acceso:**
   - Por defecto, las credenciales son:
     - Usuario: `admin`
     - Contraseña: `admin123`
   - **IMPORTANTE:** Cambia estas credenciales en `admin/login.php` antes de poner en producción

2. **Acceso al panel:**
   - URL: `http://localhost/indel/admin/`
   - O la URL correspondiente según tu configuración

## Funcionalidades

- ✅ Crear nuevas promociones
- ✅ Editar promociones existentes
- ✅ Eliminar promociones
- ✅ Subir imágenes
- ✅ Activar/desactivar promociones
- ✅ Ordenar promociones
- ✅ Establecer fechas de inicio y fin
- ✅ Visualización pública en la página de promociones

## Notas de Seguridad

- Cambia las credenciales de acceso antes de producción
- Considera implementar autenticación más robusta (hash de contraseñas, tokens, etc.)
- Protege el directorio `admin/` con .htaccess si es necesario
- Valida y sanitiza todas las entradas del usuario

