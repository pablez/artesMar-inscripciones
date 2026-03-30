<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disciplina;
use App\Models\Horario;

class HorariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar disciplinas
        $map = [];
        $nombres = [
            'Jiu-Jitsu Kids',
            'Jiu Jitsu Adultos',
            'Box',
            'MMA',
            'Funcional y Acondicionamiento',
            'Karate y Kickboxing'
        ];

        foreach ($nombres as $n) {
            $d = Disciplina::firstOrCreate(['nombre' => $n], ['descripcion' => $n, 'activo' => true]);
            $map[$n] = $d->id;
        }

        $items = [
            ['dias' => 'Lunes, Miércoles y Viernes', 'disciplina' => 'Jiu-Jitsu Kids', 'categoria' => 'De 4 a 9 años', 'hora' => '16:00 - 17:00', 'sucursal' => 'Hupermall'],
            ['dias' => 'Lunes, Miércoles y Viernes', 'disciplina' => 'Jiu-Jitsu Kids', 'categoria' => 'De 9 a 14 años', 'hora' => '17:30 - 18:30', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Martes y Jueves', 'disciplina' => 'Box', 'categoria' => '14 para adelante', 'hora' => '18:30 - 19:30', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes a Viernes', 'disciplina' => 'Jiu Jitsu Adultos', 'categoria' => '>14 años', 'hora' => '06:00 - 07:00', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes a Viernes', 'disciplina' => 'Jiu Jitsu Adultos', 'categoria' => '>14 años', 'hora' => '18:30 - 19:30', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes a Viernes', 'disciplina' => 'Jiu Jitsu Adultos', 'categoria' => '>14 años', 'hora' => '19:30 - 20:30', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes, Miércoles y Viernes', 'disciplina' => 'MMA', 'categoria' => '>14 años', 'hora' => '08:30 - 09:30', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes a Viernes', 'disciplina' => 'Funcional y Acondicionamiento', 'categoria' => '>14 años', 'hora' => '10:00 - 12:00', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes y Miércoles', 'disciplina' => 'Karate y Kickboxing', 'categoria' => '>14 años', 'hora' => '06:00 - 07:00', 'sucursal' => 'Segunda Circunvalación'],
            ['dias' => 'Lunes y Miércoles', 'disciplina' => 'Karate y Kickboxing', 'categoria' => '>14 años', 'hora' => '18:30 - 19:30', 'sucursal' => 'Segunda Circunvalación'],
        ];

        foreach ($items as $it) {
            $discId = $map[$it['disciplina']] ?? null;
            Horario::firstOrCreate([
                'dias' => $it['dias'],
                'disciplina_id' => $discId,
                'categoria' => $it['categoria'],
                'hora' => $it['hora'],
                'sucursal' => $it['sucursal']
            ], [
                'titulo' => $it['disciplina'] . ' - ' . $it['categoria'],
                'dias' => $it['dias'],
                'disciplina_id' => $discId,
                'categoria' => $it['categoria'],
                'hora' => $it['hora'],
                'sucursal' => $it['sucursal'],
                'descripcion' => null
            ]);
        }
    }
}
