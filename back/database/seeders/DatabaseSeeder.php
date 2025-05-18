<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Colegio;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
        $user = User::create([
            'name' => 'Administrador',
            'username' => 'Admin',
            'password' => bcrypt('admin123Admin'),
            'role' => 'Admin',
        ]);
        $user = User::create([
            'name' => 'Adimer Paul Chambi Ajata',
            'username' => 'Adimer',
            'password' => bcrypt('adimer123Adimer'),
            'role' => 'User',
        ]);
        Area::create([
            'id'=>1,
            'area' => 'QUÍMICA',
            'curso' => 'De 1º a 6º de Secundaria.',
            'fecha' => 'Viernes 5 de septiembre.',
            'lugar' => 'Laboratorios del Depto. de Química',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Prueba teórica hasta el martes 2 de septiembre a horas 18:00.'
        ]);
        Area::create([
            'id'=>2,
            'area' => 'MEDIO AMBIENTE',
            'curso' => '5º y 6º de Secundaria.',
            'fecha' => 'Sábado 6 de septiembre (conmemorando Día Internacional del aire Limpio por un Cielo Azul).',
            'lugar' => 'Ambientes de la carrera Ing. Química e Ing. de Alimentos',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Del 27 de agosto al 3 de septiembre, en secretaria de Carrera de Ing. Química e Ing. de Alimentos – Ciudadela Universitaria F.N.I. (Zona Sud), horarios de atención 9:00 a 12:00 y de 15:00 a 17:00.'
        ]);
        Area::create([
            'id'=>3,
            'area' => 'BIOLOGÍA',
            'curso' => 'De 3º a 6º de Secundaria.',
            'fecha' => 'Viernes 10 de septiembre a Horas: 10:00 a.m.',
            'lugar' => 'Ciudadela Universitaria',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el día viernes 29 de agosto a horas 12:00 p.m., en secretaria de Carrera de Ingeniería Química e Ingeniería de Alimentos – Ciudadela Universitaria F.N.I. (Zona Sud), horarios de atención 9:00 am a 12:00 p.m.'
        ]);
        Area::create([
            'id'=>4,
            'area' => 'FÍSICA',
            'curso' => 'De 3º a 6º de Secundaria.',
            'fecha' => 'Prueba experimental: Domingo 14 de octubre Horas. 09:00 a.m. Prueba teórica: Domingo 14 de octubre Horas 11:00 a.m.',
            'lugar' => 'Laboratorio de Física',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el viernes 12 de septiembre a horas 12:00 p.m., en el Laboratorio de Física Virtual (Aula 101, Planta Baja del Bloque Hugo Murillo Benich, Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>5,
            'area' => 'MATEMÁTICA',
            'curso' => 'De 1º a 6º de Secundaria.',
            'fecha' => 'Etapa CLASIFICATORIA. Viernes 19 de septiembre. 1° a 3° Sec. de 14:30 a 15:30 4° a 6° Sec. de 16:30 a 17:30 Etapa FINAL. Miércoles 1 de octubre. Horas: 15:00 (Duración de dos horas)',
            'lugar' => 'BLOQUE 300 (Ambos)',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Del 3 al 12 de septiembre, en la Oficina de la Olimpiada Matemática Oruro, ubicada en la Ciudadela Universitaria F.N.I. (Zona Sud) en Bloque 300, 2do. Piso (Edif. de Ciclo Básico), horarios de atención: lunes a viernes 9:00 a 12:00.'
        ]);
        Area::create([
            'id'=>6,
            'area' => 'GEOGRAFÍA',
            'curso' => 'De 3º a 6º de Secundaria.',
            'fecha' => 'Viernes 26 de septiembre. Horas: 14:30.',
            'lugar' => 'BLOQUE 300',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el martes 24 de septiembre a horas 17:00, en secretaria de la Carrera de Ingeniería Geológica – Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>7,
            'area' => 'ASTRONOMÍA y ASTROFÍSICA',
            'curso' => 'De 3º a 6º de Secundaria.',
            'fecha' => 'Prueba teórica: Domingo 5 de octubre. Horas: 16:30 a 18:30 Prueba observacional Domingo 5 de octubre. Horas: 18:30 a 20:30',
            'lugar' => 'BLOQUE 300 Aula 101, Bloque Hugo Murillo Benich',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el viernes 3 de octubre a horas 12:00 p.m., en el Laboratorio de Física Virtual (Aula 101, Planta baja del Bloque Hugo Murillo Benich, Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>8,
            'area' => 'ENERGÍAS',
            'curso' => 'De 3º a 6º de Secundaria.',
            'fecha' => 'Prueba Teórica Domingo 12 de octubre. Horas: 9:00 a.m. Prueba Experimental Domingo 12 de octubre. Horas: 14:30',
            'lugar' => 'Ambientes de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el viernes 10 de octubre a horas 16:00, en secretaria de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica – Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>9,
            'area' => 'PROGRAMACIÓN',
            'curso' => 'Categoría A: Programación. Nivel Básico.: 3ro, 4to o 5to de secundaria. Categoría B: Programación. Nivel Avanzado: 6to de secundaria Warm Up: Martes 21 de octubre. Horas: 14:30 Competencia: Miércoles 22 de octubre. Horas: 09:00',
            'fecha' => 'Warm Up: Martes 21 de octubre. Horas: 14:30 Competencia: Miércoles 22 de octubre. Horas: 09:00',
            'lugar' => 'Ambientes de Ingeniería de SIS-INF',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el viernes 17 de octubre a horas 17:30, en secretaria de la Carrera de Ingeniería de Sistemas e Informática – Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>10,
            'area' => 'REDES Y CIBERSEGURIDAD',
            'curso' => '4º a 6º de Secundaria.',
            'fecha' => 'Realización de la prueba.Nivel 1 Jueves 23 de octubre. Horas 8:30 a 11:30 Realización de la prueba. Nivel 2 Jueves 23 de octubre. Horas 14:30 a 17:30.',
            'lugar' => 'Laboratorios Bloque B y Bloque C – Carrera de Ingeniería de SISINF – Ciudadela Universitaria F.N.I. (Zona Sud)',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Desde el 1 al 22 de octubre hasta horas 17:30, en secretaria de la Carrera de Ingeniería de Sistemas e Informática – Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>11,
            'area' => 'ROBÓTICA',
            'curso' => 'Nivel 1: Estudiantes hasta 2° de secundaria. Nivel 2: Estudiantes de 3° y 4° de secundaria. Nivel 3: Estudiantes de 5° y 6° de secundaria. Registro Viernes 24 de octubre de horas 14:30 a 15:30 Prueba Viernes 24 de octubre Desde horas 15:30 a 18:00',
            'fecha' => 'Registro Viernes 24 de octubre de horas 14:30 a 15:30 Prueba Viernes 24 de octubre Desde horas 15:30 a 18:00',
            'lugar' => 'Ambientes de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el viernes 24 de octubre a horas 17:30, en secretaria de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica – Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);
        Area::create([
            'id'=>12,
            'area' => 'DISEÑADORES Y FABRICADORES',
            'curso' => 'Categoría A 1º a 3º de secundaria. Categoría B 4º a 6º de secundaria Competencia Categoría A Domingo 26 de octubre. Horas: 9:00 am. Categoría B Domingo 26 de octubre. Horas: 14:00',
            'fecha' => 'Competencia Categoría A Domingo 26 de octubre. Horas: 9:00 am. Categoría B Domingo 26 de octubre. Horas: 14:00',
            'lugar' => 'Ambientes de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica',
            'modalidad' => 'Presencial',
            'inscripcion' => 'Hasta el viernes 24 de octubre a horas 17:30, en secretaria de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica – Ciudadela Universitaria F.N.I. (Zona Sud).'
        ]);

//        INSERT INTO `colegio` (`nombre`) VALUES
//    ('ADOLFO BALLIVIAN 1 '),
//('ADVENTISTA ELENA G. DE WHITE'),
//('ALCIRA CARDONA TORRICO 2'),
//('ALEMAN'),
//('AMERICANO'),
//('ANGLO AMERICANO'),
//('ANICETO ARCE SECUNDARIA'),
//('ANTOFAGASTA'),
//('ANTONIO JOSE DE SAINZ'),
//('ARRIETA'),
//('BETHANIA'),
//('BOLIVIA'),
//('BOLIVIA DE VINTO SECUNDARIA'),
//('CARMEN GUZMAN DE MIER 1'),
//('CATOLICO SAN FRANCISCO'),
//('CENTRO DE INFORMATICA SAN MIGUEL'),
//('COMIBOL ORURO 2'),
//('DON BOSCO'),
//('DONATO VASQUEZ'),
//('EJERCITO NACIONAL SECUNDARIO'),
//('EVANGELICO  WILLIAM BOOTH'),
//('EVANGELICO FILADELFIA'),
//('FERROVIARIA SECUNDARIA'),
//('FRANCISCO FAJARDO 2'),
//('GENOVEVA JIMENEZ'),
//('GUIDO VILLAGÓMEZ SECUNDARIO'),
//('HIJOS DEL SOL 2'),
//('IGNACIO LEON  2'),
//('ILDEFONSO MURGUIA'),
//('INSCO SECUNDARIA'),
//('JESUS DE NAZARETH  2'),
//('JESUS-MARIA 2'),
//('JHON FITZGERALD KENNEDY 2'),
//('JHON FITZGERALD KENNEDY 3'),
//('JORGE OBLITAS SECUNDARIA'),
//('JOSE MARIA SIERRA GALVARRO'),
//('JUAN LECHIN OQUENDO'),
//('JUAN MISAEL SARACHO SECUNDARIA'),
//('JUAN PABLO SECUNDARIA'),
//('JUANA AZURDUY DE PADILLA SECUNDARIA'),
//('LA KANTUTA 3'),
//('LA SALLE'),
//('LA SALLE TARDE'),
//('LANI'),
//('LOS ANGELES DE NAZARIA IGNACIA'),
//('LUIS MARIO CAREAGA 2'),
//('MARCELO QUIROGA SANTA CRUZ'),
//('MARCOS BELTRAN AVILA SECUNDARIA'),
//('MARIA QUIROZ SECUNDARIA'),
//('MARISCAL SUCRE SECUNDARIO'),
//('NACIONAL BOLIVIA'),
//('NACIONAL MIXTO HUAJARA'),
//('NACIONAL SAN JOSE'),
//('NACIONES UNIDAS'),
//('NINO QUIRQUINCHO FELIZ'),
//('NUESTRA SENORA DEL SOCAVON 2'),
//('ORURO SECUNDARIA'),
//('OSCAR UNZAGA DE LA VEGA 2'),
//('PANTALEON DALENCE SECUNDARIA'),
//('REEKIE'),
//('ROTARIA ORURO OTTAWA'),
//('SAN IGNACIO DE LOYOLA'),
//('SANTA MARÍA MAGDALENA POSTEL'),
//('SANTA ROSA 2'),
//('SEBASTIAN PAGADOR 2'),
//('SIMÓN BOLÍVAR SECUNDARIA'),
//('UNIÓN  BOLIVIA  JAPÓN'),
//('VIRGEN DEL MAR 3');
        Colegio::create([
            [ 'nombre' => 'ADOLFO BALLIVIAN 1'],
            [ 'nombre' => 'ADVENTISTA ELENA G. DE WHITE'],
            [ 'nombre' => 'ALCIRA CARDONA TORRICO 2'],
            [ 'nombre' => 'ALEMAN'],
            [ 'nombre' => 'AMERICANO'],
            [ 'nombre' => 'ANGLO AMERICANO'],
            [ 'nombre' => 'ANICETO ARCE SECUNDARIA'],
            [ 'nombre' => 'ANTOFAGASTA'],
            [ 'nombre' => 'ANTONIO JOSE DE SAINZ'],
            [ 'nombre' => 'ARRIETA'],
            [ 'nombre' => 'BETHANIA'],
            [ 'nombre' => 'BOLIVIA'],
            [ 'nombre' => 'BOLIVIA DE VINTO SECUNDARIA'],
            [ 'nombre' => 'CARMEN GUZMAN DE MIER 1'],
            [ 'nombre' => 'CATOLICO SAN FRANCISCO'],
            [ 'nombre' => 'CENTRO DE INFORMATICA SAN MIGUEL'],
            [ 'nombre' => 'COMIBOL ORURO 2'],
            [ 'nombre' => 'DON BOSCO'],
            [ 'nombre' => 'DONATO VASQUEZ'],
            [ 'nombre' => 'EJERCITO NACIONAL SECUNDARIO'],
            [ 'nombre' => 'EVANGELICO  WILLIAM BOOTH'],
            [ 'nombre' => 'EVANGELICO FILADELFIA'],
            [ 'nombre' => 'FERROVIARIA SECUNDARIA'],
            [ 'nombre' => 'FRANCISCO FAJARDO 2'],
            [ 'nombre' => 'GENOVEVA JIMENEZ'],
            [ 'nombre' => "GUIDO VILLAGÓMEZ SECUNDARIO"],
            [ "nombre" => "HIJOS DEL SOL 2"],
            [ "nombre" => "IGNACIO LEON  2"],
            [ "nombre" => "ILDEFONSO MURGUIA"],
            [ "nombre" => "INSCO SECUNDARIA"],
            [ "nombre" => "JESUS DE NAZARETH  2"],
            [ "nombre" => "JESUS-MARIA 2"],
            [ "nombre" => "JHON FITZGERALD KENNEDY 2"],
            [ "nombre" => "JHON FITZGERALD KENNEDY 3"],
            [ "nombre" => "JORGE OBLITAS SECUNDARIA"],
            [ "nombre" => "JOSE MARIA SIERRA GALVARRO"],
            [ "nombre" => "JUAN LECHIN OQUENDO"],
            [ "nombre" => "JUAN MISAEL SARACHO SECUNDARIA"],
            [ "nombre" => "JUAN PABLO SECUNDARIA"],
            [ "nombre" => "JUANA AZURDUY DE PADILLA SECUNDARIA"],
            [ "nombre" => "LA KANTUTA 3"],
            [ "nombre" => "LA SALLE"],
            [ "nombre" => "LA SALLE TARDE"],
            [ "nombre" => "LANI"],
            [ "nombre" => "LOS ANGELES DE NAZARIA IGNACIA"],
            [ "nombre" => "LUIS MARIO CAREAGA 2"],
            [ "nombre" => "MARCELO QUIROGA SANTA CRUZ"],
            [ "nombre" => "MARCOS BELTRAN AVILA SECUNDARIA"],
            [ "nombre" => "MARIA QUIROZ SECUNDARIA"],
            [ "nombre" => 'MARISCAL SUCRE SECUNDARIO'],
            [ 'nombre' => 'NACIONAL BOLIVIA'],
            [ 'nombre' => 'NACIONAL MIXTO HUAJARA'],
            [ 'nombre' => 'NACIONAL SAN JOSE'],
            [ 'nombre' => 'NACIONES UNIDAS'],
            [ 'nombre' => 'NINO QUIRQUINCHO FELIZ'],
            [ 'nombre' => 'NUESTRA SENORA DEL SOCAVON 2'],
            [ 'nombre' => 'ORURO SECUNDARIA'],
            [ 'nombre' => 'OSCAR UNZAGA DE LA VEGA 2'],
            [ 'nombre' => 'PANTALEON DALENCE SECUNDARIA'],
            [ 'nombre' => 'REEKIE'],
            [ 'nombre' => 'ROTARIA ORURO OTTAWA'],
            [ 'nombre' => 'SAN IGNACIO DE LOYOLA'],
            [ 'nombre' => 'SANTA MARÍA MAGDALENA POSTEL'],
            [ 'nombre' => 'SANTA ROSA 2'],
            [ 'nombre' => 'SEBASTIAN PAGADOR 2'],
            [ 'nombre' => 'SIMÓN BOLÍVAR SECUNDARIA'],
            [ 'nombre' => 'UNIÓN  BOLIVIA  JAPÓN'],
            [ 'nombre' => 'VIRGEN DEL MAR 3']
        ]);
    }
}
