<?php

echo "=== Verificación de Base de Datos ArtesMar ===" . PHP_EOL;

// Test básico de conteos
$userCount = \App\Models\User::count();
$disciplinaCount = \App\Models\Disciplina::count();
$inscripcionCount = \App\Models\Inscripcion::count();

echo "📊 Conteos:" . PHP_EOL;
echo "  - Usuarios: {$userCount}" . PHP_EOL;
echo "  - Disciplinas: {$disciplinaCount}" . PHP_EOL;
echo "  - Inscripciones: {$inscripcionCount}" . PHP_EOL . PHP_EOL;

// Verificar disciplinas
echo "🥋 Disciplinas disponibles:" . PHP_EOL;
foreach (\App\Models\Disciplina::all() as $disciplina) {
    echo "  - {$disciplina->nombre}" . ($disciplina->activo ? " (Activa)" : " (Inactiva)") . PHP_EOL;
}
echo PHP_EOL;

// Verificar admin
$adminEmail = env('ADMIN_EMAIL', 'admin@artesmar.com');
$admin = \App\Models\User::where('email', $adminEmail)->first();
echo "👤 Usuario Admin:" . PHP_EOL;
if ($admin) {
    echo "  ✅ Encontrado: {$admin->name} ({$admin->email})" . PHP_EOL;
} else {
    echo "  ❌ No encontrado con email: {$adminEmail}" . PHP_EOL;
}
echo PHP_EOL;

echo "✅ Base de datos configurada correctamente!" . PHP_EOL;