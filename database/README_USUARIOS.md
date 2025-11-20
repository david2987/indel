# Sistema de Autenticación y Administración

## Instalación

### 1. Crear las tablas de base de datos

Ejecuta los siguientes scripts SQL en orden:

1. **Primero, crea la base de datos y tabla de promociones** (si aún no lo has hecho):
   ```sql
   -- Ejecutar database/indel.sql
   ```

2. **Luego, crea las tablas de usuarios y grupos**:
   ```sql
   -- Ejecutar database/usuarios_grupos.sql
   ```

   O ejecuta ambos scripts desde phpMyAdmin o tu cliente MySQL preferido.

### 2. Verificar la configuración de base de datos

Asegúrate de que el archivo `config/database.php` tenga las credenciales correctas:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');  // Cambiar según tu configuración
define('DB_NAME', 'indel_db');
```

### 3. Crear directorio de imágenes de promociones

Asegúrate de que existe el directorio:
```
Recursos/imagenes/promociones/
```

Si no existe, créalo con permisos de escritura (755).

## Credenciales por Defecto

Después de ejecutar el script SQL, se crea un usuario administrador:

- **Usuario:** `admin`
- **Contraseña:** `admin123`

**IMPORTANTE:** Cambia la contraseña del usuario administrador después del primer acceso.

## Estructura del Sistema

### Tablas Creadas

1. **usuarios**: Almacena información de usuarios del sistema
   - username, email, password (hash bcrypt)
   - nombre, apellido
   - activo, ultimo_acceso

2. **grupos**: Define grupos de usuarios con diferentes permisos
   - nombre, descripcion
   - activo

3. **usuarios_grupos**: Relación muchos a muchos entre usuarios y grupos
   - usuario_id, grupo_id

4. **promociones**: Tabla existente para gestionar promociones
   - titulo, descripcion, imagen
   - fecha_inicio, fecha_fin
   - activo, orden

### Grupos por Defecto

- **Administradores**: Acceso completo al sistema (pueden gestionar usuarios y grupos)
- **Editores**: Permisos de edición
- **Visualizadores**: Solo lectura

## Acceso al Backoffice

1. **URL de Login:**
   ```
   http://localhost/indel/admin/login.php
   ```

2. **Panel Principal:**
   ```
   http://localhost/indel/admin/index.php
   ```

3. **Gestión de Usuarios y Grupos** (solo administradores):
   ```
   http://localhost/indel/admin/usuarios.php
   ```

## Funcionalidades

### Sistema de Autenticación
- ✅ Login con base de datos
- ✅ Hash de contraseñas con bcrypt
- ✅ Gestión de sesiones
- ✅ Control de acceso por grupos
- ✅ Verificación de permisos

### Administración de Promociones
- ✅ Crear, editar y eliminar promociones
- ✅ Subir imágenes
- ✅ Activar/desactivar promociones
- ✅ Ordenar promociones
- ✅ Fechas de inicio y fin

### Gestión de Usuarios (Solo Administradores)
- ✅ Crear, editar y eliminar usuarios
- ✅ Asignar usuarios a grupos
- ✅ Activar/desactivar usuarios
- ✅ Ver último acceso

### Gestión de Grupos (Solo Administradores)
- ✅ Crear, editar y eliminar grupos
- ✅ Ver cantidad de usuarios por grupo
- ✅ Activar/desactivar grupos

## Seguridad

- Las contraseñas se almacenan con hash bcrypt
- El login no está visible en el navbar público
- Verificación de sesión en todas las páginas administrativas
- Control de acceso por grupos
- Validación y sanitización de datos

## Notas Importantes

1. **Cambiar credenciales por defecto** antes de poner en producción
2. **Proteger el directorio admin/** con .htaccess si es necesario
3. **Hacer backup regular** de la base de datos
4. **No compartir** las credenciales de administrador

## Solución de Problemas

### Error de conexión a la base de datos
- Verifica las credenciales en `config/database.php`
- Asegúrate de que MySQL/MariaDB esté corriendo
- Verifica que la base de datos `indel_db` existe

### Error al subir imágenes
- Verifica permisos del directorio `Recursos/imagenes/promociones/`
- Asegúrate de que el directorio existe

### No puedo iniciar sesión
- Verifica que las tablas se crearon correctamente
- Verifica que el usuario administrador existe en la base de datos
- Revisa los logs de PHP para errores

