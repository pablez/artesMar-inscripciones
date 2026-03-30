# Documentación del AdminController

Este documento proporciona una explicación detallada del controlador `AdminController.php`, que gestiona todas las funciones administrativas de la aplicación JUGA Martial Arts.

## Índice
1. [Visión General](#visión-general)
2. [Seguridad y Acceso](#seguridad-y-acceso)
3. [Gestión de Inscripciones](#gestión-de-inscripciones)
4. [Gestión de Afiliaciones](#gestión-de-afiliaciones)
5. [Gestión de Horarios](#gestión-de-horarios)
6. [Gestión de Deportes](#gestión-de-deportes)
7. [Gestión de Disciplinas](#gestión-de-disciplinas)

## Visión General

El `AdminController` es el componente principal para la gestión administrativa de la aplicación. Proporciona funcionalidades CRUD (Crear, Leer, Actualizar, Eliminar) para los siguientes modelos:

- Inscripciones
- Afiliaciones
- Horarios
- Deportes
- Disciplinas

Todas las funciones están protegidas por el método `isAdmin()` que verifica que el usuario autenticado tenga permisos de administración.

## Seguridad y Acceso

### `isAdmin()`
- **Descripción**: Método privado que verifica si el usuario actual tiene privilegios de administrador.
- **Lógica**: Comprueba si el usuario está autenticado y si su correo electrónico coincide con el valor de la variable de entorno `ADMIN_EMAIL` (valor por defecto: 'admin@artesmar.com').
- **Retorno**: Booleano (`true` si es administrador, `false` en caso contrario).
- **Uso**: Este método se llama al inicio de cada función del controlador para restringir el acceso solo a administradores.

## Gestión de Inscripciones

### `inscripciones()`
- **Descripción**: Muestra todas las inscripciones en el panel administrativo.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**: 
  - Obtiene todas las inscripciones ordenadas por fecha de creación (descendente).
  - Carga las relaciones de usuario y disciplina para mostrar datos completos.
  - Pagina los resultados (20 por página).
- **Retorno**: Vista `admin.inscripciones` con datos de inscripciones paginados.

### `destroyInscripcion($id)`
- **Descripción**: Elimina una inscripción específica.
- **Parámetros**: 
  - `$id` (int): ID de la inscripción a eliminar.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**: 
  - Busca la inscripción por ID o lanza error 404 si no existe.
  - Elimina la inscripción.
- **Retorno**: Redirección a la lista de inscripciones con mensaje de éxito.

### `updateEstado(Request $request, $id)`
- **Descripción**: Actualiza el estado de pago de una inscripción.
- **Parámetros**:
  - `$request` (Request): Contiene los datos del formulario:
    - `estado_pago`: Estado nuevo ('pendiente', 'pagado', 'rechazado', 'pendiente_verificacion').
    - `notas_admin`: Notas administrativas opcionales.
  - `$id` (int): ID de la inscripción a actualizar.
- **Validación**: Verifica que el estado sea válido y las notas no excedan 1000 caracteres.
- **Operaciones**:
  - Actualiza el estado de pago y notas.
  - Si el estado es 'pagado', establece `fecha_pago` a la fecha actual.
  - Si no es 'pagado', establece `fecha_pago` a `null`.
- **Retorno**: Redirección a la lista de inscripciones con mensaje de éxito.

## Gestión de Afiliaciones

### `afiliaciones()`
- **Descripción**: Muestra todas las afiliaciones en el panel administrativo.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**: Obtiene todas las afiliaciones ordenadas por fecha de creación (descendente).
- **Retorno**: Vista `admin.afiliaciones` con datos de afiliaciones.

### `storeAfiliacion(Request $request)`
- **Descripción**: Crea una nueva afiliación.
- **Parámetros**:
  - `$request` (Request): Contiene los datos del formulario:
    - `titulo`: Título de la afiliación (requerido).
    - `descripcion`: Descripción (requerido).
    - `url`: URL opcional.
    - `imagen`: Archivo de imagen opcional (jpeg, png, jpg, gif, máx 5MB).
- **Validación**: Verifica que los campos requeridos estén presentes y que la imagen tenga formato y tamaño correctos.
- **Operaciones**:
  - Crea un array con los datos básicos.
  - Si hay imagen, la guarda en 'afiliaciones' dentro del disco 'public' y añade la ruta al array.
  - Crea un nuevo registro en la base de datos.
- **Retorno**: Redirección a la lista de afiliaciones con mensaje de éxito.
- **Nota**: Requiere que el enlace simbólico `storage:link` esté creado para que las imágenes sean accesibles.

### `updateAfiliacion(Request $request, $id)`
- **Descripción**: Actualiza una afiliación existente.
- **Parámetros**:
  - `$request` (Request): Igual que en `storeAfiliacion`.
  - `$id` (int): ID de la afiliación a actualizar.
- **Validación**: Igual que en `storeAfiliacion`.
- **Operaciones**:
  - Obtiene la afiliación existente o lanza 404 si no existe.
  - Prepara un array con los datos actualizados.
  - Si hay nueva imagen:
    - Elimina la imagen anterior si existe.
    - Guarda la nueva imagen y actualiza la ruta.
  - Actualiza el registro en la base de datos.
- **Retorno**: Redirección a la lista de afiliaciones con mensaje de éxito.

### `destroyAfiliacion($id)`
- **Descripción**: Elimina una afiliación específica.
- **Parámetros**:
  - `$id` (int): ID de la afiliación a eliminar.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**:
  - Busca la afiliación por ID o lanza error 404 si no existe.
  - Elimina la afiliación.
- **Retorno**: Redirección a la lista de afiliaciones con mensaje de éxito.
- **Nota**: La eliminación no elimina el archivo de imagen asociado del almacenamiento.

## Gestión de Horarios

### `horarios()`
- **Descripción**: Muestra todos los horarios en el panel administrativo.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**: 
  - Obtiene todos los horarios ordenados por fecha de creación (descendente).
  - Obtiene todas las disciplinas ordenadas por nombre para el formulario.
- **Retorno**: Vista `admin.horarios` con datos de horarios y disciplinas.

### `storeHorario(Request $request)`
- **Descripción**: Crea un nuevo horario.
- **Parámetros**:
  - `$request` (Request): Contiene los datos del formulario:
    - `titulo`: Título del horario (requerido).
    - `dias`: Días de la semana (requerido).
    - `disciplina_id`: ID de la disciplina relacionada (opcional).
    - `categoria`: Categoría del horario (opcional, ej. "Niños", "Adultos").
    - `hora`: Hora del horario (requerido).
    - `sucursal`: Sucursal donde se imparte (opcional).
    - `descripcion`: Descripción adicional (opcional).
- **Validación**: Verifica que los campos requeridos estén presentes y que la disciplina exista.
- **Operaciones**: Crea un nuevo registro en la base de datos con los datos proporcionados.
- **Retorno**: Redirección a la lista de horarios con mensaje de éxito.

### `updateHorario(Request $request, $id)`
- **Descripción**: Actualiza un horario existente.
- **Parámetros**:
  - `$request` (Request): Igual que en `storeHorario`.
  - `$id` (int): ID del horario a actualizar.
- **Validación**: Igual que en `storeHorario`.
- **Operaciones**:
  - Obtiene el horario existente o lanza 404 si no existe.
  - Actualiza el registro con los datos proporcionados.
- **Retorno**: Redirección a la lista de horarios con mensaje de éxito.

### `destroyHorario($id)`
- **Descripción**: Elimina un horario específico.
- **Parámetros**:
  - `$id` (int): ID del horario a eliminar.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**:
  - Busca el horario por ID o lanza error 404 si no existe.
  - Elimina el horario.
- **Retorno**: Redirección a la lista de horarios con mensaje de éxito.

## Gestión de Deportes

### `deportes()`
- **Descripción**: Muestra todos los deportes en el panel administrativo.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**: Obtiene todos los deportes ordenados por fecha de creación (descendente).
- **Retorno**: Vista `admin.deportes` con datos de deportes.
- **Nota**: La funcionalidad de creación ha sido desactivada en la interfaz.

### `updateDeporte(Request $request, $id)`
- **Descripción**: Actualiza un deporte existente.
- **Parámetros**:
  - `$request` (Request): Contiene los datos del formulario:
    - `nombre`: Nombre del deporte (requerido).
  - `$id` (int): ID del deporte a actualizar.
- **Validación**: Verifica que el nombre esté presente y no exceda 255 caracteres.
- **Operaciones**:
  - Obtiene el deporte existente o lanza 404 si no existe.
  - Actualiza el nombre.
- **Retorno**: Redirección a la lista de deportes con mensaje de éxito.

### `destroyDeporte($id)`
- **Descripción**: Elimina un deporte específico.
- **Parámetros**:
  - `$id` (int): ID del deporte a eliminar.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**:
  - Busca el deporte por ID o lanza error 404 si no existe.
  - Elimina el deporte.
- **Retorno**: Redirección a la lista de deportes con mensaje de éxito.

## Gestión de Disciplinas

### `disciplinas()`
- **Descripción**: Muestra todas las disciplinas en el panel administrativo.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**: 
  - Obtiene todas las disciplinas ordenadas por fecha de creación (descendente).
  - Incluye el conteo de inscripciones relacionadas con cada disciplina.
- **Retorno**: Vista `admin.disciplinas` con datos de disciplinas.

### `storeDisciplina(Request $request)`
- **Descripción**: Crea una nueva disciplina.
- **Parámetros**:
  - `$request` (Request): Contiene los datos del formulario:
    - `nombre`: Nombre de la disciplina (requerido).
    - `activo`: Estado de activación (opcional, booleano).
- **Validación**: Verifica que el nombre esté presente y no exceda 255 caracteres.
- **Operaciones**:
  - Crea un nuevo registro con el nombre y estado activo.
  - Si no se especifica 'activo', se establece como `true` por defecto.
- **Retorno**: Redirección a la lista de disciplinas con mensaje de éxito.

### `updateDisciplina(Request $request, $id)`
- **Descripción**: Actualiza una disciplina existente.
- **Parámetros**:
  - `$request` (Request): Igual que en `storeDisciplina`.
  - `$id` (int): ID de la disciplina a actualizar.
- **Validación**: Igual que en `storeDisciplina`.
- **Operaciones**:
  - Obtiene la disciplina existente o lanza 404 si no existe.
  - Actualiza el nombre y el estado activo.
  - Si no se especifica 'activo', mantiene el valor actual.
- **Retorno**: Redirección a la lista de disciplinas con mensaje de éxito.

### `destroyDisciplina($id)`
- **Descripción**: Elimina una disciplina específica.
- **Parámetros**:
  - `$id` (int): ID de la disciplina a eliminar.
- **Seguridad**: Verifica que el usuario sea administrador.
- **Operaciones**:
  - Busca la disciplina por ID o lanza error 404 si no existe.
  - Elimina la disciplina.
- **Retorno**: Redirección a la lista de disciplinas con mensaje de éxito.
- **Nota**: Esta acción puede afectar a las inscripciones y horarios relacionados, dependiendo de las relaciones definidas en los modelos.

## Solución de problemas comunes

### Problemas al subir imágenes (afiliaciones)

Si al mover el proyecto a otra PC no puedes subir imágenes en `/admin/afiliaciones`, verifica:

1. **Enlace simbólico**: Ejecuta `php artisan storage:link` para crear el enlace entre `storage/app/public` y `public/storage`.

2. **Permisos de directorios**: Asegúrate de que los directorios `storage` y `bootstrap/cache` tengan permisos de escritura:
   ```
   chmod -R 775 storage bootstrap/cache  # Linux/Mac
   icacls storage /grant Everyone:(OI)(CI)F /T  # Windows
   ```

3. **Configuración PHP**: Verifica los límites de subida en `php.ini`:
   ```
   upload_max_filesize = 8M
   post_max_size = 8M
   ```

4. **Archivo .env**: Asegúrate de tener un archivo `.env` con `APP_KEY` generado:
   ```
   php artisan key:generate
   ```

5. **Dependencias**: Ejecuta `composer install` para instalar todas las dependencias.

6. **Cachés**: Limpia las cachés de configuración:
   ```
   php artisan config:clear
   php artisan cache:clear
   ```