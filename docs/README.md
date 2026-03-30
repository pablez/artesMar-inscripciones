# 📚 Documentación de Base de Datos - Sistema de Inscripciones

## 📋 Índice de Archivos

Esta carpeta contiene la documentación completa de la base de datos del sistema de inscripciones:

### 1. **DIAGRAMA_ER.puml** 📊
   - **Formato:** PlantUML (Entidad-Relación)
   - **Contenido:** Diagrama visual de todas las entidades y sus relaciones
   - **Uso:** Abrir con herramientas PlantUML:
     - [PlantUML Online Editor](http://plantuml.com/plantuml/uml/)
     - VS Code Extension: `PlantUML` by jebbs
     - Generar PNG/SVG para documentos
   - **Incluye:**
     - Campos de cada tabla
     - Tipos de datos
     - Claves primarias (PK) y foráneas (FK)
     - Relaciones 1-a-N
     - Notas explicativas

### 2. **ANALISIS_MODELOS.md** 📖
   - **Formato:** Markdown
   - **Contenido:** Análisis detallado de cada modelo/tabla
   - **Incluye:**
     - Estructura de cada entidad
     - Campos y tipos de datos
     - Validaciones
     - Relaciones explicadas
     - Patrones de diseño (Snapshot + Live Lookup)
     - Flujo de datos típico
     - Escalabilidad futura
     - Índices importantes
   - **Mejor para:** Entender el negocio y lógica de datos

### 3. **ESQUEMA_SQL.sql** 🗄️
   - **Formato:** SQL (DDL + DML)
   - **Contenido:** Definitions de tablas, vistas y triggers
   - **Incluye:**
     - CREATE TABLE para todas las entidades
     - Foreign keys y constraints
     - Índices optimizados
     - Vistas útiles para queries comunes
     - Data inicial (seeders)
     - Comentarios explicativos
   - **Mejor para:** Engineers, DBA, implementaciones

---

## 🎯 Guía Rápida por Audiencia

### 👨‍💻 **Developer (Laravel/PHP)**
1. Lee: `ANALISIS_MODELOS.md` — Secciones "Entidades" y "Relaciones"
2. Consulta: Modelos en `/app/Models/` (código vivo)
3. Referencia: `DIAGRAMA_ER.puml` para visualizar

**Archivos clave:**
- `app/Models/User.php`
- `app/Models/Inscripcion.php`
- `app/Models/Disciplina.php`
- `app/Models/Horario.php`

### 📊 **Data Analyst / BI**
1. Abre: `ESQUEMA_SQL.sql`
2. Ejecuta: Vistas (`vw_inscripciones_completas`, `vw_estadisticas_inscripciones`)
3. Crea: Queries basadas en ejemplos al final del archivo

**Queries útiles:**
```sql
-- Inscripciones pagadas por disciplina
SELECT d.nombre, COUNT(i.id) FROM disciplinas d
LEFT JOIN inscripciones i ON d.id = i.disciplina_id AND i.estado_pago = 'pagado'
GROUP BY d.id;

-- Horarios más ocupados
SELECT h.titulo, h.dias, h.hora, COUNT(i.id) as inscritos FROM horarios h
LEFT JOIN inscripciones i ON i.disciplina_id = h.disciplina_id AND i.estado_pago = 'pagado'
GROUP BY h.id ORDER BY inscritos DESC LIMIT 10;
```

### 🏗️ **Architect / Tech Lead**
1. Lee: El archivo presente (README.md)
2. Revisa: `ANALISIS_MODELOS.md` — Sección "Patrones de Diseño"
3. Visualiza: `DIAGRAMA_ER.puml` — Relaciones y escalabilidad
4. Propone: Cambios según secciones "Extensiones Futuras"

**Decisiones de diseño:**
- **Snapshot Pattern:** Mantener histórico inmutable de inscripciones
- **Live Lookup:** `horario_actual()` para datos actualizados
- **Enum para estado_pago:** Validación a nivel BD
- **Sin relaciones para Afiliacion/Deporte:** Flexibilidad futura

### 📋 **Product Manager / Business Analyst**
1. Lee: `ANALISIS_MODELOS.md` — Sección "Flujo de Datos"
2. Ve: `DIAGRAMA_ER.puml` para contexto visual
3. Pregunta: El diagram es la respuesta a "qué datos almacenam?"

**Conceptos clave:**
- Una fecha puede tener múltiples inscritos
- El estado de pago controla si alumno puede asistir
- Cambios en horarios se ven automáticamente en inscritos

---

## 🔄 Relaciones Principales

### 1️⃣ USER ← → INSCRIPCION
```
Un usuario puede tener múltiples inscripciones
Una inscripción pertenece a un usuario (opcional)
```

### 2️⃣ DISCIPLINA ← → INSCRIPCION
```
Una disciplina tiene múltiples inscripciones
Una inscripción está vinculada a una disciplina
```

### 3️⃣ DISCIPLINA ← → HORARIO
```
Una disciplina tiene múltiples horarios
Un horario pertenece a una disciplina (opcional)
```

---

## 🔀 Patrón de Sincronización (Importante!)

### Problema
Cuando se edita un horario (ej: cambiar de 18:30 a 19:00), ¿qué pasa con las inscripciones antiguas?

### Solución (Hybrid Pattern)
1. **Inscripción almacena snapshot:**
   - `categoria`, `dias`, `hora`, `sucursal` al momento de crear
   - Para conservar histórico

2. **Inscripción consulta horario vigente:**
   - Método `horario_actual()` busca horario por `disciplina_id + dias + hora`
   - Si existe → muestra datos live (cambios reflejados)
   - Si NO existe → muestra snapshot guardado

3. **Resultado:**
   - Histórico preservado ✓
   - Cambios recientes visible ✓
   - Control granular ✓

**Ejemplo en código:**
```php
// En Inscripcion.php
public function horario_actual() {
    return Horario::where([
        ['disciplina_id', '=', $this->disciplina_id],
        ['dias', '=', $this->dias],
        ['hora', '=', $this->hora]
    ])->first();
}

// En vista admin
$horario = $inscripcion->horario_actual();
$hora_actual = $horario ? $horario->hora : $inscripcion->hora; // Fallback a snapshot
```

---

## 📊 Estadísticas Actuales

| Aspecto | Cantidad |
|---------|----------|
| **Entidades** | 6 (User, Inscripcion, Disciplina, Horario, Afiliacion, Deporte) |
| **Relaciones 1:N** | 3 |
| **Campos Totales** | 48 |
| **Índices** | 20+ (optimizados) |
| **Vistas SQL** | 3 |
| **Migraciones** | 9 archivos |

---

## 🚀 Características de Diseño

✅ **Normalización:** 3NF (Tercera Forma Normal)  
✅ **Integridad Referencial:** FK con ON DELETE SET NULL  
✅ **Índices:** Clave (user_id, disciplina_id, estado_pago, created_at)  
✅ **Enums:** estado_pago con validación a nivel BD  
✅ **Vistas:** Queries complejas simplificadas  
✅ **Flexibilidad:** Campos nullable para casos especiales  

---

## 📝 Cómo Modificar la BD

### Agregar un nuevo campo a Inscripcion

1. **Crear migración:**
   ```bash
   php artisan make:migration add_campo_to_inscripciones
   ```

2. **Editar migración:**
   ```php
   Schema::table('inscripciones', function (Blueprint $table) {
       $table->string('nuevo_campo')->nullable();
   });
   ```

3. **Actualizar modelo:**
   ```php
   // app/Models/Inscripcion.php
   protected $fillable = [
       // ... campos existentes
       'nuevo_campo'
   ];
   ```

4. **Ejecutar:**
   ```bash
   php artisan migrate
   ```

5. **Actualizar validación en Controller:**
   ```php
   $request->validate([
       'nuevo_campo' => 'nullable|string|max:255'
   ]);
   ```

---

## 🔐 Seguridad y Validaciones

**A nivel BD:**
- Unique constraints en `email` y `nombre` (disciplina)
- Foreign keys con integridad referencial
- Enums para valores fijos (estado_pago)

**A nivel Aplicación:**
- Validaciones en Controller (Request->validate())
- Hashing de contraseña (bcrypt)
- Admin check en cada operación sensible

**A nivel Modelo:**
- Mass assignment protection ($fillable)
- Casts para tipos correctos

---

## 📚 Referencias Externas

- **Laravel Eloquent:** https://laravel.com/docs/eloquent
- **PlantUML:** http://plantuml.com/
- **SQL Best Practices:** https://use-the-index-luke.com/
- **Database Normalization:** https://en.wikipedia.org/wiki/Database_normalization

---

## 📞 Contacto / Preguntas

Para cambios en la estructura de BD o aclaraciones:

1. Consulta `ANALISIS_MODELOS.md`
2. Si necesitas modificar, crea issue/PR
3. Sigue el proceso de "Cómo Modificar la BD"

---

**Última actualización:** Marzo 29, 2026  
**Versión:** 1.0  
**Estado:** Producción  

