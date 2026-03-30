<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Disciplina;
use App\Models\Afiliacion;
use App\Models\Horario;
use App\Models\Deporte;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@artesmar.com')],
            [
                'name' => 'Administrador',
                'password' => bcrypt(env('ADMIN_PASSWORD', 'admin123')),
            ]
        );

        // Crear disciplinas
        $disciplinas = [
            [
                'nombre' => 'Jiu Jitsu Brasileño',
                'descripcion' => 'Arte marcial de suelo que se enfoca en el grappling y la lucha en el suelo',
                'activo' => true
            ],
            [
                'nombre' => 'Boxeo',
                'descripcion' => 'Arte marcial de golpes con puños, técnica y resistencia',
                'activo' => true
            ],
            [
                'nombre' => 'Kickboxing',
                'descripcion' => 'Arte marcial que combina técnicas de puños y patadas',
                'activo' => true
            ],
            [
                'nombre' => 'Muay Thai',
                'descripcion' => 'Arte marcial tailandés que utiliza puños, codos, rodillas y patadas',
                'activo' => true
            ],
            [
                'nombre' => 'MMA',
                'descripcion' => 'Artes marciales mixtas que combina varias disciplinas',
                'activo' => true
            ]
        ];

        foreach ($disciplinas as $disciplina) {
            Disciplina::firstOrCreate(
                ['nombre' => $disciplina['nombre']],
                $disciplina
            );
        }

        // Crear afiliaciones
        $afiliaciones = [
            [
                'titulo' => 'Membresía Básica',
                'descripcion' => 'Acceso a todas las clases básicas de lunes a viernes'
            ],
            [
                'titulo' => 'Membresía Premium',
                'descripcion' => 'Acceso ilimitado a todas las clases y entrenamientos personalizados'
            ],
            [
                'titulo' => 'Membresía Estudiante',
                'descripcion' => 'Descuento especial para estudiantes con horarios flexibles'
            ]
        ];

        foreach ($afiliaciones as $afiliacion) {
            Afiliacion::firstOrCreate(
                ['titulo' => $afiliacion['titulo']],
                $afiliacion
            );
        }

        // Crear horarios
        $horarios = [
            [
                'titulo' => 'Mañanas',
                'dias' => 'Lun-Vie',
                'hora' => '06:00 - 08:00',
                'descripcion' => 'Horario matutino para entrenamientos intensivos'
            ],
            [
                'titulo' => 'Tarde',
                'dias' => 'Lun-Vie',
                'hora' => '18:00 - 20:00',
                'descripcion' => 'Horario vespertino después del trabajo'
            ],
            [
                'titulo' => 'Fines de Semana',
                'dias' => 'Sáb-Dom',
                'hora' => '10:00 - 12:00',
                'descripcion' => 'Entrenamientos de fin de semana'
            ]
        ];

        foreach ($horarios as $horario) {
            Horario::firstOrCreate(
                ['titulo' => $horario['titulo']],
                $horario
            );
        }

        // Crear deportes
        $deportes = [
            [
                'nombre' => 'Jiu Jitsu',
                'descripcion' => 'Arte marcial de agarre y sumisión'
            ],
            [
                'nombre' => 'Boxeo',
                'descripcion' => 'Arte marcial de puños con técnica y resistencia'
            ],
            [
                'nombre' => 'Muay Thai',
                'descripcion' => 'Arte marcial tailandés con uso de ocho extremidades'
            ],
            [
                'nombre' => 'MMA',
                'descripcion' => 'Artes marciales mixtas combinando múltiples disciplinas'
            ]
        ];

        foreach ($deportes as $deporte) {
            Deporte::firstOrCreate(
                ['nombre' => $deporte['nombre']],
                $deporte
            );
        }

        // Ejecutar seeders adicionales (por ejemplo los horarios detallados)
        $this->call([
            HorariosSeeder::class,
        ]);
    }
}
