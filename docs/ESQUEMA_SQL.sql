-- ============================================================================
-- BASE DE DATOS: inscripciones_db
-- Sistema de Inscripciones - Artes Marciales
-- ============================================================================

-- ---------------------------------------------------------
-- Tabla 1: users (Usuarios del Sistema)
-- ---------------------------------------------------------
CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_users_email (email),
    INDEX idx_users_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMENT ON TABLE users IS 'Usuarios: admin y alumnos';
COMMENT ON COLUMN users.email_verified_at IS 'NULL si email no verificado';
COMMENT ON COLUMN users.password IS 'Hash bcrypt, nunca plaintext';


-- ---------------------------------------------------------
-- Tabla 2: disciplinas (Artes Marciales)
-- ---------------------------------------------------------
CREATE TABLE disciplinas (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) UNIQUE NOT NULL,
    descripcion TEXT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_disciplinas_nombre (nombre),
    INDEX idx_disciplinas_activo (activo),
    INDEX idx_disciplinas_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMENT ON TABLE disciplinas IS 'Disciplinas/artes marciales: Jiu Jitsu, Boxeo, etc.';
COMMENT ON COLUMN disciplinas.activo IS 'Controla si está disponible para inscribirse';


-- ---------------------------------------------------------
-- Tabla 3: horarios (Franjas Horarias)
-- ---------------------------------------------------------
CREATE TABLE horarios (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    dias VARCHAR(255) NOT NULL COMMENT 'Ej: Lun-Vie, Sáb-Dom, Lun/Mié/Vie',
    hora VARCHAR(255) NOT NULL COMMENT 'Ej: 18:30–19:30',
    categoria VARCHAR(255) NULL COMMENT 'Ej: 9-14 años, Adultos',
    sucursal VARCHAR(255) NULL COMMENT 'Ubicación/local',
    descripcion TEXT NULL,
    disciplina_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    CONSTRAINT fk_horarios_disciplina 
        FOREIGN KEY (disciplina_id) 
        REFERENCES disciplinas(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE,
    
    INDEX idx_horarios_disciplina_id (disciplina_id),
    INDEX idx_horarios_dias (dias),
    INDEX idx_horarios_hora (hora),
    INDEX idx_horarios_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMENT ON TABLE horarios IS 'Horarios disponibles por disciplina';
COMMENT ON COLUMN horarios.disciplina_id IS 'FK: Puede ser NULL para eventos especiales';


-- ---------------------------------------------------------
-- Tabla 4: inscripciones (Registros de Inscripción)
-- ---------------------------------------------------------
CREATE TABLE inscripciones (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    
    -- Relaciones
    user_id BIGINT UNSIGNED NULL,
    disciplina_id BIGINT UNSIGNED NULL,
    
    -- Datos personales (snapshot + input)
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefono VARCHAR(255) NULL,
    
    -- Datos de horario (snapshot al momento de inscripción)
    categoria VARCHAR(255) NULL COMMENT 'Snapshot del horario.categoria',
    dias VARCHAR(255) NULL COMMENT 'Snapshot del horario.dias',
    hora VARCHAR(255) NULL COMMENT 'Snapshot del horario.hora',
    sucursal VARCHAR(255) NULL COMMENT 'Snapshot del horario.sucursal',
    
    -- Datos adicionales
    mensaje TEXT NULL,
    comprobante VARCHAR(255) NULL COMMENT 'Path to uploaded file',
    
    -- Pago
    estado_pago ENUM('pendiente', 'pendiente_verificacion', 'pagado', 'rechazado') 
        DEFAULT 'pendiente',
    fecha_pago TIMESTAMP NULL DEFAULT NULL,
    
    -- Admin notes
    notas_admin TEXT NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    -- Foreign Keys
    CONSTRAINT fk_inscripciones_user 
        FOREIGN KEY (user_id) 
        REFERENCES users(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE,
    
    CONSTRAINT fk_inscripciones_disciplina 
        FOREIGN KEY (disciplina_id) 
        REFERENCES disciplinas(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE,
    
    -- Índices
    INDEX idx_inscripciones_user_id (user_id),
    INDEX idx_inscripciones_disciplina_id (disciplina_id),
    INDEX idx_inscripciones_email (email),
    INDEX idx_inscripciones_estado_pago (estado_pago),
    INDEX idx_inscripciones_created_at (created_at),
    INDEX idx_inscripciones_disciplina_dias_hora (disciplina_id, dias, hora)
        COMMENT 'Para búsqueda de horario_actual()'
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMENT ON TABLE inscripciones IS 'Registro de inscripción a clases';
COMMENT ON COLUMN inscripciones.user_id IS 'FK: user que realizó inscripción (puede ser NULL)';
COMMENT ON COLUMN inscripciones.categoria IS 'IMPORTANTE: es snapshot, consultar horarios para dato live';
COMMENT ON COLUMN inscripciones.estado_pago IS 'ENUM: pendiente, pendiente_verificacion, pagado, rechazado';


-- ---------------------------------------------------------
-- Tabla 5: afiliaciones (Membresías)
-- ---------------------------------------------------------
CREATE TABLE afiliaciones (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    url VARCHAR(255) NULL,
    imagen VARCHAR(255) NULL COMMENT 'Path: public/storage/afiliaciones/...',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_afiliaciones_titulo (titulo),
    INDEX idx_afiliaciones_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMENT ON TABLE afiliaciones IS 'Membresías/afiliaciones disponibles (sin relaciones externas)';
COMMENT ON COLUMN afiliaciones.imagen IS 'Imagen de portada para landing page';


-- ---------------------------------------------------------
-- Tabla 6: deportes (Tipos de Deporte)
-- ---------------------------------------------------------
CREATE TABLE deportes (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_deportes_nombre (nombre),
    INDEX idx_deportes_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMENT ON TABLE deportes IS 'Tipos de deporte/arte marcial (sin relaciones externas)';


-- ---------------------------------------------------------
-- Tabla 7: migrations (Control de versión de BD)
-- ---------------------------------------------------------
CREATE TABLE migrations (
    id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    migration VARCHAR(255) NOT NULL UNIQUE,
    batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Tabla 8: cache (Cache de sesiones/datos)
-- ---------------------------------------------------------
CREATE TABLE cache (
    key VARCHAR(255) NOT NULL PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL,
    
    INDEX idx_cache_expiration (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Tabla 9: jobs (Cola de tareas)
-- ---------------------------------------------------------
CREATE TABLE jobs (
    id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    reserved_at INT UNSIGNED NULL DEFAULT NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT NOT NULL,
    
    INDEX idx_jobs_queue (queue),
    INDEX idx_jobs_reserved_at (reserved_at),
    INDEX idx_jobs_available_at (available_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- VISTAS ÚTILES PARA QUERIES
-- ============================================================================

-- Inscripciones con datos completos (user + disciplina)
CREATE VIEW vw_inscripciones_completas AS
SELECT 
    i.id,
    i.created_at,
    i.nombre,
    i.email,
    i.telefono,
    u.name AS usuario_nombre,
    u.email AS usuario_email,
    d.nombre AS disciplina_nombre,
    i.categoria,
    i.dias,
    i.hora,
    i.sucursal,
    i.estado_pago,
    i.fecha_pago,
    i.notas_admin
FROM inscripciones i
LEFT JOIN users u ON i.user_id = u.id
LEFT JOIN disciplinas d ON i.disciplina_id = d.id
ORDER BY i.created_at DESC;

-- Horarios vigentes por disciplina
CREATE VIEW vw_horarios_activos AS
SELECT 
    h.id,
    h.titulo,
    d.nombre AS disciplina,
    h.dias,
    h.hora,
    h.categoria,
    h.sucursal,
    h.descripcion,
    COUNT(i.id) AS total_inscripciones
FROM horarios h
LEFT JOIN disciplinas d ON h.disciplina_id = d.id
LEFT JOIN inscripciones i ON (
    i.disciplina_id = h.disciplina_id 
    AND i.dias = h.dias 
    AND i.hora = h.hora 
    AND i.estado_pago = 'pagado'
)
GROUP BY h.id
ORDER BY h.dias, h.hora;

-- Estadísticas de inscripciones
CREATE VIEW vw_estadisticas_inscripciones AS
SELECT 
    d.nombre AS disciplina,
    COUNT(i.id) AS total_inscripciones,
    COUNT(CASE WHEN i.estado_pago = 'pagado' THEN 1 END) AS pagadas,
    COUNT(CASE WHEN i.estado_pago = 'pendiente' THEN 1 END) AS pendientes,
    COUNT(CASE WHEN i.estado_pago = 'rechazado' THEN 1 END) AS rechazadas
FROM disciplinas d
LEFT JOIN inscripciones i ON d.id = i.disciplina_id
GROUP BY d.id, d.nombre;

-- ============================================================================
-- TRIGGERS ÚTILES (Comentados como ejemplo)
-- ============================================================================

/*
-- Actualizar fecha_pago automáticamente cuando estado cambia a 'pagado'
DELIMITER //
CREATE TRIGGER trg_inscripcion_pagado BEFORE UPDATE ON inscripciones
FOR EACH ROW BEGIN
    IF NEW.estado_pago = 'pagado' AND OLD.estado_pago != 'pagado' THEN
        SET NEW.fecha_pago = NOW();
    END IF;
END //
DELIMITER ;
*/

-- ============================================================================
-- DATOS INICIALES (SEEDERS)
-- ============================================================================

-- Admin usuario
INSERT INTO users (name, email, password, created_at, updated_at) VALUES (
    'Administrador',
    'admin@artesmar.com',
    '$2y$12$...', -- bcrypt('admin123')
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Disciplinas iniciales
INSERT INTO disciplinas (nombre, descripcion, activo, created_at, updated_at) VALUES
('Jiu Jitsu Brasileño', 'Arte marcial de suelo que se enfoca en el grappling', TRUE, NOW(), NOW()),
('Boxeo', 'Arte marcial de golpes con puños, técnica y resistencia', TRUE, NOW(), NOW()),
('Kickboxing', 'Arte marcial que combina técnicas de puños y patadas', TRUE, NOW(), NOW()),
('Muay Thai', 'Arte marcial tailandés que utiliza puños, codos, rodillas y patadas', TRUE, NOW(), NOW()),
('MMA', 'Artes marciales mixtas que combina varias disciplinas', TRUE, NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Horarios iniciales
INSERT INTO horarios (titulo, dias, hora, descripcion, created_at, updated_at) VALUES
('Mañanas', 'Lun-Vie', '06:00 - 08:00', 'Horario matutino para entrenamientos intensivos', NOW(), NOW()),
('Tarde', 'Lun-Vie', '18:00 - 20:00', 'Horario vespertino después del trabajo', NOW(), NOW()),
('Fines de Semana', 'Sáb-Dom', '10:00 - 12:00', 'Entrenamientos de fin de semana', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- ============================================================================
-- INFORMACIÓN ÚTIL DE CONSULTAS
-- ============================================================================

-- Total de inscritos por disciplina
-- SELECT d.nombre, COUNT(i.id) as total FROM disciplinas d 
-- LEFT JOIN inscripciones i ON d.id = i.disciplina_id 
-- GROUP BY d.id;

-- Inscripciones pendientes de pago
-- SELECT * FROM inscripciones WHERE estado_pago IN ('pendiente', 'pendiente_verificacion');

-- Horarios con cupo lleno (más de 15 inscritos pagos)
-- SELECT h.*, COUNT(i.id) as inscritos FROM horarios h
-- LEFT JOIN inscripciones i ON (i.disciplina_id = h.disciplina_id AND i.estado_pago = 'pagado')
-- GROUP BY h.id HAVING inscritos > 15;
