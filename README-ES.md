 # Inicio rápido — ArtesMar (guía simple)

 Instalar dependencias PHP

Crear la base de datos (MySQL)

 Migraciones, seeders y storage link

 ```powershell
 php artisan migrate
 php artisan db:seed
 php artisan storage:link
 ```
# Inicio rápido — ArtesMar (guía simple)

Esta guía recoge los pasos mínimos para poner el proyecto en funcionamiento en una máquina nueva (Windows + XAMPP). Usa PowerShell desde la raíz del proyecto (`C:\xampp\htdocs\artesMar`).

Requisitos
- PHP (la versión usada por XAMPP)
- Composer
- MySQL (o MariaDB)
- Node.js + npm (opcional si vas a compilar assets)

1) Descomprime o clona el proyecto

2) Instalar dependencias PHP

```powershell
cd 'C:\xampp\htdocs\artesMar'
composer install
```

3) Configurar el entorno (.env)

Si el archivo `.env` no existe copia el ejemplo y genera la clave:

```powershell
copy .env.example .env
php artisan key:generate
```

Edita `.env` y ajusta los valores:
- APP_URL (ej. http://127.0.0.1:8000)
- DB_DATABASE, DB_USERNAME, DB_PASSWORD (tu base de datos)

4) Crear la base de datos

Usa phpMyAdmin o MySQL para crear la base de datos que indicaste en `.env`.

5) Ejecutar migraciones y seeders

```powershell
php artisan migrate --seed
```

6) Crear enlace público para archivos (storage)

```powershell
# Ejecuta PowerShell como Administrador
php artisan storage:link
```

Si `storage:link` falla en Windows, usa la alternativa de copia (funcional en desarrollo):

```powershell
# Copia todo el contenido público del storage al public/storage
robocopy 'C:\xampp\htdocs\artesMar\storage\app\public' 'C:\xampp\htdocs\artesMar\public\storage' /E
```

7) Permisos (Windows: desarrollo)

Si el servidor no puede escribir en `storage` o `bootstrap/cache`, da permisos (solo en desarrollo):

```powershell
icacls 'C:\xampp\htdocs\artesMar\storage' /grant Everyone:(OI)(CI)F /T
icacls 'C:\xampp\htdocs\artesMar\bootstrap\cache' /grant Everyone:(OI)(CI)F /T
```

8) Lavar cachés de Laravel

```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

9) (Opcional) Compilar assets front-end

```powershell
npm install
npm run dev
```

10) Levantar servidor de desarrollo

```powershell
php artisan serve --host=127.0.0.1 --port=8000
# luego abre http://127.0.0.1:8000
```

Comprobaciones rápidas
- Visita `/admin/horarios` y `/admin/afiliaciones` para verificar seeders.
- Usuario admin por defecto: `admin@artesmar.com` / `admin123` (recomienda cambiar la contraseña en producción).

Solución de problemas comunes (subida/visualización de imágenes)

- Si al subir una imagen NO aparece en la web: verifica si el archivo existe en `storage\app\public\afiliaciones`:

```powershell
Get-ChildItem 'C:\xampp\htdocs\artesMar\storage\app\public\afiliaciones' -Recurse | Select-Object FullName
```

- Si el archivo está en `storage/app/public/afiliaciones` pero la URL `http://127.0.0.1:8000/storage/afiliaciones/archivo.jpg` devuelve 404, recrea el enlace:

```powershell
# Ejecuta como Administrador
php artisan storage:link
```

- Si no puedes crear symlinks en Windows, usa `robocopy` para mantener copia física en `public/storage` (ver arriba).

- Revisa permisos (icacls) si PHP no puede escribir.

- Si la subida falla con código 413 -> aumenta `upload_max_filesize` y `post_max_size` en `C:\xampp\php\php.ini` y reinicia Apache.

- Si la respuesta es 500 -> revisa `storage\logs\laravel.log`:

```powershell
Get-Content 'C:\xampp\htdocs\artesMar\storage\logs\laravel.log' -Tail 120
```

Comandos útiles adicionales

```powershell
# Regenerar autoload
composer dump-autoload

# Limpiar vistas compiladas
php artisan view:clear

# Verificar que public/storage existe
Test-Path 'C:\xampp\htdocs\artesMar\public\storage'
```

Notas finales
- Al mover el proyecto comprimido a otra PC, recuerda ejecutar `composer install`, crear `.env` y `php artisan key:generate`, y recrear el enlace `storage:link`.
- Mantén copias de seguridad de `storage/app/public/afiliaciones` si migras imágenes entre máquinas.

Si quieres, puedo añadir estos pasos al repositorio como un script PowerShell para automatizar la configuración en Windows.
