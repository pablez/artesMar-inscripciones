<?php

use App\Models\User;
use App\Models\Disciplina;
use App\Models\Inscripcion;

require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de Base de Datos Normalizada ===" . PHP_EOL . PHP_EOL;

// Contar registros
echo "Usuarios: " . User::count() . PHP_EOL;
echo "Disciplinas: " . Disciplina::count() . PHP_EOL;
echo "Inscripciones: " . Inscripcion::count() . PHP_EOL . PHP_EOL;

// Listar disciplinas
echo "Disciplinas disponibles:" . PHP_EOL;
foreach (Disciplina::all() as $disciplina) {
    echo "- {$disciplina->nombre}: {$disciplina->descripcion}" . PHP_EOL;
}
echo PHP_EOL;

// Verificar usuario admin
$admin = User::where('email', env('ADMIN_EMAIL', 'admin@artesmar.com'))->first();
if ($admin) {
    echo "Usuario admin encontrado: {$admin->name} ({$admin->email})" . PHP_EOL;
} else {
    echo "No se encontró usuario admin" . PHP_EOL;
}
echo PHP_EOL;

// Crear inscripción de prueba
$user = User::first();
$disciplina = Disciplina::first();

if ($user && $disciplina) {
    try {
        $inscripcion = Inscripcion::create([
            'nombre' => 'Prueba Test',
            'email' => 'prueba@test.com',
            'telefono' => '12345678',
            'user_id' => $user->id,
            'disciplina_id' => $disciplina->id,
            'categoria' => 'Adultos',
            'dias' => 'Lun-Vie',
            'hora' => '18:00-19:00',
            'sucursal' => 'Principal'
        ]);
        
        echo "✅ Inscripción de prueba creada exitosamente" . PHP_EOL;
        echo "- ID: {$inscripcion->id}" . PHP_EOL;
        echo "- Usuario: {$inscripcion->user->name}" . PHP_EOL;
        echo "- Disciplina: {$inscripcion->disciplina->nombre}" . PHP_EOL;
        
    } catch (Exception $e) {
        echo "❌ Error creando inscripción: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "❌ No hay usuarios o disciplinas para crear inscripción de prueba" . PHP_EOL;
}