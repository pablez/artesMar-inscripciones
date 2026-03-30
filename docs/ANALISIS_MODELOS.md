# 📊 Análisis de Base de Datos - Sistema de Inscripciones

## 📌 Resumen Ejecutivo

El sistema de inscripciones para artes marciales utiliza **6 entidades** con relaciones 1-a-N:
- **3 entidades Core:** User, Inscripcion, Disciplina (con relaciones)
- **2 entidades Relacionales:** Horario (vinculada a Disciplina)
- **2 entidades Auxiliares:** Afiliacion, Deporte (sin relaciones externas)

**Patrón de Sincronización:** Las inscripciones almacenan snapshot de datos pero consultan horarios vigentes para actualización automática.

---

## 🗂️ Entidades Detalladas

### 1. **USER** (Usuarios del Sistema)

```
┌─────────────────────────────┐
│        USER                 │
├─────────────────────────────┤
│ PK id: bigint               │
│ name: string                │
│ email: string [UNIQUE]      │
│ password: string (hashed)   │
│ email_verified_at: timestamp│
│ remember_token: string      │
│ created_at: timestamp       │
│ updated_at: timestamp       │
└─────────────────────────────┘
```

**Características:**
- Extiende `Authenticatable` (Laravel)
- Email es único (para login)
- **Admin detectado por:** `email === env('ADMIN_EMAIL', 'admin@artesmar.com')`
- **Relación:** `hasMany(Inscripcion)`
- **Total de campos:** 8

**Validaciones Implícitas:**
- Email válido (por autenticación Laravel)
- Contraseña hasheada (never plaintext)

---

### 2. **INSCRIPCION** (Registro de Clase)

```
┌──────────────────────────────────────┐
│        INSCRIPCION                   │
├──────────────────────────────────────┤
│ PK id: bigint                        │
│ FK user_id: bigint (nullable)        │
│ FK disciplina_id: bigint (nullable)  │
│ nombre: string                       │
│ email: string                        │
│ telefono: string (nullable)          │
│ categoria: string (nullable)         │
│ dias: string (nullable)              │
│ hora: string (nullable)              │
│ sucursal: string (nullable)          │
│ mensaje: text (nullable)             │
│ comprobante: string (nullable)       │
│ estado_pago: enum                    │
│ fecha_pago: timestamp (nullable)     │
│ notas_admin: text (nullable)         │
│ created_at: timestamp                │
│ updated_at: timestamp                │
└──────────────────────────────────────┘
```

**Estado Pago (ENUM):**
- `pendiente` — Inscripción nueva, sin pago
- `pendiente_verificacion` — Comprobante cargado, esperando verificación
- `pagado` — Pagado, alumno habilitado
- `rechazado` — Pago rechazado o inscripción cancelada

**Relaciones:**
- `belongsTo(User)` — Alumno que realizó la inscripción
- `belongsTo(Disciplina)` — Disciplina seleccionada
- `horario_actual()` — Método helper para obtener horario sincronizado

**Métodos de Sincronización (Nuevos):**
```php
public function horario_actual()        // Busca horario por disciplina+dias+hora
public function getSucursalSincronizada()    // Obtiene sucursal viva del horario
public function getCategoriaSincronizada()   // Obtiene categoría viva del horario
public function getDescripcionHorario()      // Obtiene descripción del horario
```

**Patrón:** Snapshot + Live Lookup
- **Almacena:** Copia de días, hora, sucursal en el momento de inscripción (histórico)
- **Consulta:** Horario vigente cuando se necesita mostrar datos actualizados
- **Ventaja:** Historial inmutable + Datos actuales sincronizados

**Total de campos:** 18

---

### 3. **DISCIPLINA** (Arte Marcial Ofrecida)

```
┌──────────────────────────┐
│        DISCIPLINA        │
├──────────────────────────┤
│ PK id: bigint            │
│ nombre: string [UNIQUE]  │
│ descripcion: text        │
│ activo: boolean (=true)  │
│ created_at: timestamp    │
│ updated_at: timestamp    │
└──────────────────────────┘
```

**Características:**
- Nombres únicos (no pueden repetirse)
- `activo` controla si se muestra en inscripciones
- **Relaciones:**
  - `hasMany(Inscripcion)` — Inscripciones para esta disciplina
  - `hasMany(Horario)` — Horarios disponibles

**Ejemplos:**
- Jiu Jitsu Brasileño
- Boxeo
- Kickboxing
- Muay Thai
- MMA

**Total de campos:** 6

---

### 4. **HORARIO** (Franjas Horarias)

```
┌──────────────────────────────────┐
│          HORARIO                 │
├──────────────────────────────────┤
│ PK id: bigint                    │
│ FK disciplina_id: bigint (nullable)│
│ titulo: string                   │
│ dias: string                     │
│ hora: string                     │
│ categoria: string (nullable)     │
│ sucursal: string (nullable)      │
│ descripcion: text (nullable)     │
│ created_at: timestamp            │
│ updated_at: timestamp            │
└──────────────────────────────────┘
```

**Relación:**
- `belongsTo(Disciplina)` — Asociado a disciplina (opcional)

**Dias (opciones predefinidas):**
- `Lunes a Viernes`
- `Lun / Mié / Vie`
- `Mar / Jue`
- `Sábados`
- `Lun / Mié`

**Hora (formato):**
- Ej: `18:30–19:30`, `06:00 - 08:00`

**Sincronización con Inscripciones:**
- Cuando un horario cambia (ej: de 18:30 a 19:00)
- Las inscripciones que usan ese horario lo reflejan automáticamente
- porque `Inscripcion.horario_actual()` busca por disciplina+dias+hora

**Total de campos:** 10

---

### 5. **AFILIACION** (Membresías)

```
┌──────────────────────────────┐
│        AFILIACION            │
├──────────────────────────────┤
│ PK id: bigint                │
│ titulo: string               │
│ descripcion: text            │
│ url: string (nullable)       │
│ imagen: string (nullable)    │
│ created_at: timestamp        │
│ updated_at: timestamp        │
└──────────────────────────────┘
```

**Características:**
- **Sin relaciones externas** (entidad independiente)
- Almacena imagen en `public/storage/afiliaciones/`
- URL puede ser enlace externo o ruta SPA

**Ejemplos:**
- Membresía Básica
- Membresía Premium
- Membresía Estudiante

**Total de campos:** 7

---

### 6. **DEPORTE** (Tipos de Deporte)

```
┌──────────────────────────┐
│        DEPORTE           │
├──────────────────────────┤
│ PK id: bigint            │
│ nombre: string           │
│ descripcion: text        │
│ created_at: timestamp    │
│ updated_at: timestamp    │
└──────────────────────────┘
```

**Características:**
- **Sin relaciones externas** (entidad independiente)
- Similar a Disciplina pero más simplificado
- Usado para categorización futura

**Total de campos:** 5

---

## 📊 Relaciones (1-a-N)

### Relación 1: User ← → Inscripcion

```
USER (1) ——— (N) INSCRIPCION

user_id: bigint NOT NULL | FOREIGN KEY
ON DELETE: SET NULL | ON UPDATE: CASCADE
```

- Un usuario puede tener **múltiples inscripciones**
- Una inscripción pertenece a **un usuario** (opcional, puede no tener cuenta)
- Índice en `inscripciones.user_id` para queries rápidas

---

### Relación 2: Disciplina ← → Inscripcion

```
DISCIPLINA (1) ——— (N) INSCRIPCION

disciplina_id: bigint NOT NULL | FOREIGN KEY
ON DELETE: SET NULL | ON UPDATE: CASCADE
```

- Una disciplina tiene **múltiples inscripciones**
- Una inscripción está asociada a **una disciplina** (opcional)
- Cuando se elimina una disciplina, inscripciones quedan con NULL

---

### Relación 3: Disciplina ← → Horario

```
DISCIPLINA (1) ——— (N) HORARIO

disciplina_id: bigint NULL | FOREIGN KEY
ON DELETE: SET NULL | ON UPDATE: CASCADE
```

- Una disciplina tiene **múltiples horarios**
- Un horario está asociado a **una disciplina** (opcional)
- Un horario puede existir sin disciplina (para eventos especiales)

---

## 🔄 Flujo de Datos: Inscripción Typical

```
1. Usuario se registra/loguea
   └─ Crea USER

2. Usuario va a /inscripciones
   └─ Ve Disciplinas activas
   └─ Ve Horarios por disciplina
   └─ Selecciona horario

3. Usuario llena formulario y envía
   └─ Crea INSCRIPCION con:
      - user_id
      - disciplina_id
      - nombre, email, teléfono
      - categoria, dias, hora, sucursal (snapshot del horario)
      - estado_pago = 'pendiente'

4. Admin verifica inscripción
   └─ Revisa comprobante
   └─ Actualiza estado_pago = 'pagado'
   └─ SetFecha_pago = NOW()

5. Vista admin muestra datos sincronizados
   └─ Busca horario_actual() por disciplina+dias+hora
   └─ Si existe → muestra datos live (incluye cambios recientes)
   └─ Si NO existe → muestra snapshot guardado
```

---

## 🔐 Validaciones por Entidad

### USER
- Email must be unique
- Email must be valid format
- Password must be hashed (never stored plaintext)

### INSCRIPCION
- nombre: required, string, max 255
- email: required, email
- telefono: nullable, string
- disciplina_id: nullable, exists in disciplinas
- categoria: nullable, string
- dias: required, string
- hora: required, string
- sucursal: nullable, string
- comprobante: nullable, image, max 5MB
- estado_pago: in [pendiente, pagado, rechazado, pendiente_verificacion]
- notas_admin: nullable, string, max 1000

### DISCIPLINA
- nombre: required, unique, string, max 255
- descripcion: nullable, string
- activo: boolean (default true)

### HORARIO
- titulo: required, string, max 255
- dias: required, string
- disciplina_id: nullable, exists in disciplinas
- categoria: nullable, string
- hora: required, string
- sucursal: nullable, string
- descripcion: nullable, string

### AFILIACION
- titulo: required, string, max 255
- descripcion: required, string
- url: nullable, url format
- imagen: nullable, image, max 5MB

### DEPORTE
- nombre: required, string, max 255
- descripcion: nullable, string

---

## 📈 Índices Importantes

```sql
-- Por performance
users.email (UNIQUE)
disciplinas.nombre (UNIQUE)
inscripciones.user_id (INDEX)
inscripciones.disciplina_id (INDEX)
inscripciones.estado_pago (INDEX)
inscripciones.created_at (INDEX)
horarios.disciplina_id (INDEX)
```

---

## 🎯 Patrones de Diseño Utilizados

### 1. **Snapshot Pattern** (Inscripción)
- Almacena copia de datos al momento de inscripción
- Permite histórico inmutable
- **Ejemplo:** Si un horario cambia de 18:30 a 19:00, inscripciones antiguas conservan 18:30

### 2. **Live Lookup Pattern** (Sincronización)
- Método `horario_actual()` busca horario vigente *en tiempo de lectura*
- Refleja cambios recientes sin actualizar inscrip histórica
- **Ejemplo:** Admin ve hora actualizada en panel

### 3. **Soft State Pattern** (Inscripción nullable)
- Inscripción puede existir sin user_id o disciplina_id
- Flexible para diferentes flows de entrada
- **Ejemplo:** Inscripción anónima luego vinculada a usuario

---

## 🚀 Escalabilidad Futura

**Posibles Extensiones:**

1. **Pago real (Stripe/MercadoPago)**
   - Agregar `transaction_id` a INSCRIPCION
   - Agregar tabla `payments` con detalles

2. **Multi-sucursal**
   - Nueva entidad `SUCURSAL` para centralizar info
   - FK en HORARIO hacia SUCURSAL

3. **Inventario de cupos**
   - Agregar `cupo_maximo`, `inscritos_actuales` a HORARIO
   - Control de disponibilidad

4. **Calificaciones/Feedback**
   - Nueva entidad `REVIEW` con FK a INSCRIPCION
   - Rating, comentarios, fecha

5. **Horarios recurrentes**
   - Pasar de strings a structured data
   - Crear `SCHEDULE` pattern con recurrencia

---

## 📋 Estado Actual

**Tablas Activas:** 6
**Relaciones:** 3 (1-a-N)
**Campos Totales:** 48
**Migraciones:** 9 archivos
**Modelos:** 6 clases PHP

**Última mejora (Marzo 2026):** Sincronización automática de horarios en inscripciones + CRUD completo para disciplinas y deportes

---

## 📞 Contacto / Cambios Requeridos

Para cambios en la estructura:

1. Crear nueva migración: `php artisan make:migration add_campo_to_tabla`
2. Actualizar Model: agregar campo a `$fillable`
3. Actualizar validaciones en Controller
4. Actualizar vista si aplica

