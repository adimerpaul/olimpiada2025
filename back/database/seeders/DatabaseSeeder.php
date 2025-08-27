<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private function base(array $overrides): array
    {
        return array_merge([
            'area' => null,
            'curso1' => null,
            'curso2' => null,
            'curso3' => null,
            'curso4' => null,
            'curso5' => null,
            'curso6' => null,
            'titulo_fecha1' => null,
            'fecha1' => null,
            'titulo_fecha2' => null,
            'fecha2' => null,
            'lugar' => null,

            // EXISTENTE: modalidad = Presencial/Virtual (no tocar)
            'modalidad' => null,

            // NUEVOS CAMPOS:
            'participacion' => 'individual',     // 'individual' | 'grupo'
            'min_integrantes' => 1,
            'max_integrantes' => 1,
            'grupo_mismo_curso' => false,

            'inscripcion' => null,
            'cupos_por_grado' => null,
            'reglas_especiales' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ], $overrides);
    }

    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            ['name' => 'Admin','password' => Hash::make('admin123Admin')]
        );

        // (Opcional pero recomendado si estás resembrando)
        // DB::table('areas')->truncate();

        DB::table('areas')->insert([
            // QUÍMICA (TEÓRICA) — INDIVIDUAL 1..1
            $this->base([
                'area' => 'QUÍMICA (Teórica)',
                'curso1' => '1º Secundaria',
                'curso2' => '2º Secundaria',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Competencia Teórica',
                'fecha1' => '2025-09-05',
                'lugar' => 'Laboratorios del Depto. de Química – Bloque 300',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'grupo_mismo_curso' => false,
                'inscripcion' => 'Hasta el 02/09/2025 18:00, Secretaría Depto. Química – Ciudadela Universitaria F.N.I.',
                'cupos_por_grado' => json_encode(["1"=>10,"2"=>10,"3"=>10,"4"=>10,"5"=>10,"6"=>10]),
            ]),

            // QUÍMICA (EXPERIMENTAL) — GRUPO 3..4, MISMO CURSO, MÁX 3 GRUPOS/GRADO (si deseas aplicar)
            $this->base([
                'area' => 'QUÍMICA (Experimental)',
                'curso1' => '1º Secundaria',
                'curso2' => '2º Secundaria',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Competencia Experimental',
                'fecha1' => '2025-09-12',
                'lugar' => 'Laboratorios del Depto. de Química – Bloque 300',
                'modalidad' => 'Presencial',
                'participacion' => 'grupo',
                'min_integrantes' => 3,
                'max_integrantes' => 4,
                'grupo_mismo_curso' => true,
                'inscripcion' => 'Hasta el 09/09/2025 18:00, Secretaría Depto. Química – Ciudadela Universitaria F.N.I.',
                'cupos_por_grado' => json_encode(["1"=>10,"2"=>10,"3"=>10,"4"=>10,"5"=>10,"6"=>10]),
                'reglas_especiales' => json_encode([
                    'max_grupos_por_grado' => 3
                ]),
            ]),

            // MEDIO AMBIENTE — INDIVIDUAL 1..1
            $this->base([
                'area' => 'MEDIO AMBIENTE',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Competencia Única',
                'fecha1' => '2025-09-06',
                'lugar' => 'Ing. Química e Ing. de Alimentos',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => '27/08/2025 al 03/09/2025 (9:00–12:00 y 15:00–17:00), Secretaría Ing. Química e Ing. de Alimentos.',
                'cupos_por_grado' => json_encode(["5"=>10,"6"=>10]),
            ]),

            // BIOLOGÍA — INDIVIDUAL 1..1
            $this->base([
                'area' => 'BIOLOGÍA',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Competencia Única',
                'fecha1' => '2025-09-10',
                'lugar' => 'Ciudadela Universitaria',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 10,
                'inscripcion' => 'Hasta el 29/08/2025 12:00, Secretaría Ing. Química e Ing. de Alimentos.',
                'cupos_por_grado' => json_encode(["3"=>10,"4"=>10,"5"=>10,"6"=>10]),
            ]),

            // FÍSICA — INDIVIDUAL 1..1
            $this->base([
                'area' => 'FÍSICA',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Prueba Experimental',
                'fecha1' => '2025-10-14',
                'titulo_fecha2' => 'Prueba Teórica',
                'fecha2' => '2025-10-14',
                'lugar' => 'Laboratorio de Física – Bloque 300',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => 'Hasta el 12/09/2025 12:00, Laboratorio de Física Virtual (Aula 101, Bloque Hugo Murillo Benich).',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // MATEMÁTICA — INDIVIDUAL 1..1
            $this->base([
                'area' => 'MATEMÁTICA',
                'curso1' => '1º Secundaria',
                'curso2' => '2º Secundaria',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Etapa Clasificatoria',
                'fecha1' => '2025-09-19',
                'titulo_fecha2' => 'Etapa Final',
                'fecha2' => '2025-10-01',
                'lugar' => 'Bloque 300',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => '03/09/2025 al 12/09/2025, Oficina Olimpiada Matemática Oruro – Bloque 300, 2º piso (9:00–12:00).',
                'cupos_por_grado' => json_encode(["1"=>6,"2"=>6,"3"=>6,"4"=>6,"5"=>6,"6"=>6]),
            ]),

            // GEOGRAFÍA — INDIVIDUAL 1..1
            $this->base([
                'area' => 'GEOGRAFÍA',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Competencia Única',
                'fecha1' => '2025-09-26',
                'lugar' => 'Bloque 300',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => 'Hasta el 24/09/2025 17:00, Secretaría de Ingeniería Geológica.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // ASTRONOMÍA Y ASTROFÍSICA — INDIVIDUAL 1..1
            $this->base([
                'area' => 'ASTRONOMÍA Y ASTROFÍSICA',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Prueba Teórica',
                'fecha1' => '2025-10-05',
                'titulo_fecha2' => 'Prueba Observacional',
                'fecha2' => '2025-10-05',
                'lugar' => 'Bloque 300 – Aula 101, Bloque Hugo Murillo Benich',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => 'Hasta el 03/10/2025 12:00, Laboratorio de Física Virtual.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // ENERGÍAS — INDIVIDUAL 1..1
            $this->base([
                'area' => 'ENERGÍAS',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Prueba Teórica',
                'fecha1' => '2025-10-12',
                'titulo_fecha2' => 'Prueba Experimental',
                'fecha2' => '2025-10-12',
                'lugar' => 'Ing. Mecánica, Electromecánica, Mecatrónica',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => 'Hasta el 10/10/2025 16:00, Secretaría de la carrera.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // PROGRAMACIÓN — INDIVIDUAL 1..1
            $this->base([
                'area' => 'PROGRAMACIÓN',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Warm Up',
                'fecha1' => '2025-10-21',
                'titulo_fecha2' => 'Competencia',
                'fecha2' => '2025-10-22',
                'lugar' => 'Ingeniería de Sistemas e Informática',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 10,
                'inscripcion' => '01/09/2025 al 10/10/2025, Secretaría de SIS-INF.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // REDES Y CIBERSEGURIDAD — INDIVIDUAL 1..1
            $this->base([
                'area' => 'REDES Y CIBERSEGURIDAD',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Nivel 1',
                'fecha1' => '2025-10-23',
                'titulo_fecha2' => 'Nivel 2',
                'fecha2' => '2025-10-23',
                'lugar' => 'Laboratorios Bloque B y C – SISINF',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 10,
                'inscripcion' => '01/10/2025 al 20/10/2025, Secretaría Academia Cisco SISFNIUTO – Edif. 3 de Julio.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // ROBÓTICA — INDIVIDUAL 1..1 (categorías por curso, pero participantes individuales)
            $this->base([
                'area' => 'ROBÓTICA',
                'curso1' => '1º Secundaria',
                'curso2' => '2º Secundaria',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Registro',
                'fecha1' => '2025-10-24',
                'titulo_fecha2' => 'Prueba',
                'fecha2' => '2025-10-24',
                'lugar' => 'Ciudadela Universitaria – Bloque B SISINF',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 1,
                'inscripcion' => '01/10/2025 al 22/10/2025, Secretaría de SIS-INF.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),

            // DISEÑADORES Y FABRICADORES — INDIVIDUAL 1..1
            $this->base([
                'area' => 'DISEÑADORES Y FABRICADORES',
                'curso1' => '1º Secundaria',
                'curso2' => '2º Secundaria',
                'curso3' => '3º Secundaria',
                'curso4' => '4º Secundaria',
                'curso5' => '5º Secundaria',
                'curso6' => '6º Secundaria',
                'titulo_fecha1' => 'Categoría A',
                'fecha1' => '2025-10-26',
                'titulo_fecha2' => 'Categoría B',
                'fecha2' => '2025-10-26',
                'lugar' => 'Ing. Mecánica, Electromecánica, Mecatrónica',
                'modalidad' => 'Presencial',
                'participacion' => 'individual',
                'min_integrantes' => 1,
                'max_integrantes' => 4,
                'inscripcion' => 'Hasta el 24/10/2025 17:30, Secretaría de la carrera.',
                'cupos_por_grado' => json_encode(new \stdClass()),
            ]),
        ]);
    }
}
