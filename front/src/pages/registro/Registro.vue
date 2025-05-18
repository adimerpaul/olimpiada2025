<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
<!--      <q-toolbar>-->
<!--        <q-btn-->
<!--            flat-->
<!--            dense-->
<!--            round-->
<!--            icon="menu"-->
<!--            aria-label="Menu"-->
<!--        />-->
<!--        <q-toolbar-title>-->
      <div class="row">
        <div class="col-12 col-md-1"></div>
        <div class="col-2 col-md-2 flex flex-center">
          <q-img src="images.png" style="width: 50px" />
        </div>
        <div class="col-8 col-md-6 text-center flex flex-center q-pa-md">
          <div  style="width: 250px; text-align: center;wrap-option: wrap; line-height: 1;">
            VIII OLIMPIADA DE
            CIENCIA Y TECNOLOGÍA
            F.N.I.
          </div>
        </div>
        <div class="col-2 col-md-2 flex flex-center">
          <q-img src="logo_fni.png" style="width: 50px" />
        </div>
        <div class="col-12 col-md-1"></div>
      </div>

<!--        </q-toolbar-title>-->
<!--        <div>Quasar v{{ $q.version }}</div>-->
<!--      </q-toolbar>-->
    </q-header>
    <q-page-container>
<!--      <div style="width: 100px; text-align: center;outline: 1px solid red;wrap-option: wrap;">-->
<!--        VIII OLIMPIADA DE-->
<!--        CIENCIA Y TECNOLOGÍA-->
<!--        F.N.I.-->
<!--      </div>-->
      <q-page class="bg-grey-3 q-pa-md">
        <div class="row">
          <div class="col-12 col-md-3"></div>
          <div class="col-12 col-md-6">
            <label class="text-bold">Selecione su area</label>
<!--            option-label="area"-->
<!--            option-value="id"-->
            <q-select
                filled
                v-model="area"
                :options="areas"
                emit-value
                dense
                option-label="area"
                @update:modelValue="getCursos"
                map-options
                class="q-mb-md"/>
            <label class="text-bold">Selecione su curso</label>
<!--            option-label="curso"-->
<!--            option-value="id"-->
            <q-select
                filled
                v-model="curso"
                :options="cursos"
                emit-value
                dense
                option-label="curso"
                map-options
                class="q-mb-md"/>
            <template v-if="area.curso == 'Categoría A: Programación. Nivel Básico.: 3ro, 4to o 5to de secundaria. Categoría B: Programación. Nivel Avanzado: 6to de secundaria Warm Up: Martes 21 de octubre. Horas: 14:30 Competencia: Miércoles 22 de octubre. Horas: 09:00'">
              <label class="text-bold">Selecione su categoria</label>
              <q-select
                  filled
                  v-model="categoria"
                  :options="categorias"
                  emit-value
                  dense
                  option-label="categoria"
                  map-options
                  class="q-mb-md"/>
            </template>
            <template v-if="area.curso == 'Nivel 1: Estudiantes hasta 2° de secundaria. Nivel 2: Estudiantes de 3° y 4° de secundaria. Nivel 3: Estudiantes de 5° y 6° de secundaria. Registro Viernes 24 de octubre de horas 14:30 a 15:30 Prueba Viernes 24 de octubre Desde horas 15:30 a 18:00'">
              <label class="text-bold">Selecione su categoria</label>
              <q-select
                  filled
                  v-model="categoria"
                  :options="categorias"
                  emit-value
                  dense
                  option-label="categoria"
                  map-options
                  class="q-mb-md"/>
            </template>
            <template v-if="area.curso == 'Categoría A 1º a 3º de secundaria. Categoría B 4º a 6º de secundaria Competencia Categoría A Domingo 26 de octubre. Horas: 9:00 am. Categoría B Domingo 26 de octubre. Horas: 14:00'">
              <label class="text-bold">Selecione su categoria</label>
              <q-select
                  filled
                  v-model="categoria"
                  :options="categorias"
                  emit-value
                  dense
                  option-label="categoria"
                  map-options
                  class="q-mb-md"/>
            </template>
          </div>
          <div class="col-12 col-md-3"></div>
        </div>
        <pre>{{areas}}</pre>
<!--        [-->
<!--        {-->
<!--        "id": 1,-->
<!--        "area": "QUÍMICA",-->
<!--        "curso": "De 1º a 6º de Secundaria.",-->
<!--        "fecha": "Viernes 5 de septiembre.",-->
<!--        "lugar": "Laboratorios del Depto. de Química",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Prueba teórica hasta el martes 2 de septiembre a horas 18:00."-->
<!--        },-->
<!--        {-->
<!--        "id": 2,-->
<!--        "area": "MEDIO AMBIENTE",-->
<!--        "curso": "5º y 6º de Secundaria.",-->
<!--        "fecha": "Sábado 6 de septiembre (conmemorando Día Internacional del aire Limpio por un Cielo Azul).",-->
<!--        "lugar": "Ambientes de la carrera Ing. Química e Ing. de Alimentos",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Del 27 de agosto al 3 de septiembre, en secretaria de Carrera de Ing. Química e Ing. de Alimentos – Ciudadela Universitaria F.N.I. (Zona Sud), horarios de atención 9:00 a 12:00 y de 15:00 a 17:00."-->
<!--        },-->
<!--        {-->
<!--        "id": 3,-->
<!--        "area": "BIOLOGÍA",-->
<!--        "curso": "De 3º a 6º de Secundaria.",-->
<!--        "fecha": "Viernes 10 de septiembre a Horas: 10:00 a.m.",-->
<!--        "lugar": "Ciudadela Universitaria",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el día viernes 29 de agosto a horas 12:00 p.m., en secretaria de Carrera de Ingeniería Química e Ingeniería de Alimentos – Ciudadela Universitaria F.N.I. (Zona Sud), horarios de atención 9:00 am a 12:00 p.m."-->
<!--        },-->
<!--        {-->
<!--        "id": 4,-->
<!--        "area": "FÍSICA",-->
<!--        "curso": "De 3º a 6º de Secundaria.",-->
<!--        "fecha": "Prueba experimental: Domingo 14 de octubre Horas. 09:00 a.m. Prueba teórica: Domingo 14 de octubre Horas 11:00 a.m.",-->
<!--        "lugar": "Laboratorio de Física",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el viernes 12 de septiembre a horas 12:00 p.m., en el Laboratorio de Física Virtual (Aula 101, Planta Baja del Bloque Hugo Murillo Benich, Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 5,-->
<!--        "area": "MATEMÁTICA",-->
<!--        "curso": "De 1º a 6º de Secundaria.",-->
<!--        "fecha": "Etapa CLASIFICATORIA. Viernes 19 de septiembre. 1° a 3° Sec. de 14:30 a 15:30 4° a 6° Sec. de 16:30 a 17:30 Etapa FINAL. Miércoles 1 de octubre. Horas: 15:00 (Duración de dos horas)",-->
<!--        "lugar": "BLOQUE 300 (Ambos)",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Del 3 al 12 de septiembre, en la Oficina de la Olimpiada Matemática Oruro, ubicada en la Ciudadela Universitaria F.N.I. (Zona Sud) en Bloque 300, 2do. Piso (Edif. de Ciclo Básico), horarios de atención: lunes a viernes 9:00 a 12:00."-->
<!--        },-->
<!--        {-->
<!--        "id": 6,-->
<!--        "area": "GEOGRAFÍA",-->
<!--        "curso": "De 3º a 6º de Secundaria.",-->
<!--        "fecha": "Viernes 26 de septiembre. Horas: 14:30.",-->
<!--        "lugar": "BLOQUE 300",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el martes 24 de septiembre a horas 17:00, en secretaria de la Carrera de Ingeniería Geológica – Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 7,-->
<!--        "area": "ASTRONOMÍA y ASTROFÍSICA",-->
<!--        "curso": "De 3º a 6º de Secundaria.",-->
<!--        "fecha": "Prueba teórica: Domingo 5 de octubre. Horas: 16:30 a 18:30 Prueba observacional Domingo 5 de octubre. Horas: 18:30 a 20:30",-->
<!--        "lugar": "BLOQUE 300 Aula 101, Bloque Hugo Murillo Benich",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el viernes 3 de octubre a horas 12:00 p.m., en el Laboratorio de Física Virtual (Aula 101, Planta baja del Bloque Hugo Murillo Benich, Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 8,-->
<!--        "area": "ENERGÍAS",-->
<!--        "curso": "De 3º a 6º de Secundaria.",-->
<!--        "fecha": "Prueba Teórica Domingo 12 de octubre. Horas: 9:00 a.m. Prueba Experimental Domingo 12 de octubre. Horas: 14:30",-->
<!--        "lugar": "Ambientes de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el viernes 10 de octubre a horas 16:00, en secretaria de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica – Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 9,-->
<!--        "area": "PROGRAMACIÓN",-->
<!--        "curso": "Categoría A: Programación. Nivel Básico.: 3ro, 4to o 5to de secundaria. Categoría B: Programación. Nivel Avanzado: 6to de secundaria Warm Up: Martes 21 de octubre. Horas: 14:30 Competencia: Miércoles 22 de octubre. Horas: 09:00",-->
<!--        "fecha": "Warm Up: Martes 21 de octubre. Horas: 14:30 Competencia: Miércoles 22 de octubre. Horas: 09:00",-->
<!--        "lugar": "Ambientes de Ingeniería de SIS-INF",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el viernes 17 de octubre a horas 17:30, en secretaria de la Carrera de Ingeniería de Sistemas e Informática – Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 10,-->
<!--        "area": "REDES Y CIBERSEGURIDAD",-->
<!--        "curso": "4º a 6º de Secundaria.",-->
<!--        "fecha": "Realización de la prueba.Nivel 1 Jueves 23 de octubre. Horas 8:30 a 11:30 Realización de la prueba. Nivel 2 Jueves 23 de octubre. Horas 14:30 a 17:30.",-->
<!--        "lugar": "Laboratorios Bloque B y Bloque C – Carrera de Ingeniería de SISINF – Ciudadela Universitaria F.N.I. (Zona Sud)",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Desde el 1 al 22 de octubre hasta horas 17:30, en secretaria de la Carrera de Ingeniería de Sistemas e Informática – Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 11,-->
<!--        "area": "ROBÓTICA",-->
<!--        "curso": "Nivel 1: Estudiantes hasta 2° de secundaria. Nivel 2: Estudiantes de 3° y 4° de secundaria. Nivel 3: Estudiantes de 5° y 6° de secundaria. Registro Viernes 24 de octubre de horas 14:30 a 15:30 Prueba Viernes 24 de octubre Desde horas 15:30 a 18:00",-->
<!--        "fecha": "Registro Viernes 24 de octubre de horas 14:30 a 15:30 Prueba Viernes 24 de octubre Desde horas 15:30 a 18:00",-->
<!--        "lugar": "Ambientes de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el viernes 24 de octubre a horas 17:30, en secretaria de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica – Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        },-->
<!--        {-->
<!--        "id": 12,-->
<!--        "area": "DISEÑADORES Y FABRICADORES",-->
<!--        "curso": "Categoría A 1º a 3º de secundaria. Categoría B 4º a 6º de secundaria Competencia Categoría A Domingo 26 de octubre. Horas: 9:00 am. Categoría B Domingo 26 de octubre. Horas: 14:00",-->
<!--        "fecha": "Competencia Categoría A Domingo 26 de octubre. Horas: 9:00 am. Categoría B Domingo 26 de octubre. Horas: 14:00",-->
<!--        "lugar": "Ambientes de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica",-->
<!--        "modalidad": "Presencial",-->
<!--        "inscripcion": "Hasta el viernes 24 de octubre a horas 17:30, en secretaria de la carrera de Ingeniería Mecánica, Electromecánica, Mecatrónica – Ciudadela Universitaria F.N.I. (Zona Sud)."-->
<!--        }-->
<!--        ]-->
      </q-page>
    </q-page-container>
  </q-layout>
</template>
<script>
export default {
  name: 'Registro',
  data(){
    return {
      areas:[],
      area: '',
      cursos: [],
      curso: '',
      categorias: [],
      categoria: '',
      nivels: [],
      nivel: '',
    }
  },
  mounted() {
    this.getAreas()
  },
  methods:{
    getAreas(){
      this.$axios.get('/areas').then(response => {
          this.areas = response.data
        }).catch(error => {
          console.error('Error fetching areas:', error)
        })
    },
    getCursos(){
      // const areaSeleccionada = this.areas.find(area => area.id === this.area)
      this.curso = ''
      this.categoria = ''
      this.nivel = ''
      if (this.area.area == 'QUÍMICA' || this.area.area == 'MATEMÁTICA' || this.area.area == 'BIOLOGÍA' || this.area.area == 'FÍSICA' || this.area.area == 'GEOGRAFÍA' || this.area.area == 'ASTRONOMÍA y ASTROFÍSICA' || this.area.area == 'ENERGÍAS'){
        this.cursos=[
          {id: 1, curso: '1º de Secundaria'},
          {id: 2, curso: '2º de Secundaria'},
          {id: 3, curso: '3º de Secundaria'},
          {id: 4, curso: '4º de Secundaria'},
          {id: 5, curso: '5º de Secundaria'},
          {id: 6, curso: '6º de Secundaria'}
        ]
      }
      else if ( this.area.area == 'MEDIO AMBIENTE' || this.area.area == 'REDES Y CIBERSEGURIDAD' || this.area.area == 'ROBÓTICA' || this.area.area == 'DISEÑADORES Y FABRICADORES'){
        this.cursos=[
          {id: 1, curso: '1º de Secundaria'},
          {id: 2, curso: '2º de Secundaria'},
          {id: 3, curso: '3º de Secundaria'},
          {id: 4, curso: '4º de Secundaria'},
          {id: 5, curso: '5º de Secundaria'},
          {id: 6, curso: '6º de Secundaria'}
        ]
      }
      // else if (this.area.curso == '5º y 6º de Secundaria.'){
      //   this.cursos=[
      //     {id: 1, curso: '5º de Secundaria'},
      //     {id: 2, curso: '6º de Secundaria'}
      //   ]
      // }else if (this.area.curso == 'De 3º a 6º de Secundaria.'){
      //   this.cursos=[
      //     {id: 1, curso: '3º de Secundaria'},
      //     {id: 2, curso: '4º de Secundaria'},
      //     {id: 3, curso: '5º de Secundaria'},
      //     {id: 4, curso: '6º de Secundaria'}
      //   ]
      // }else if (this.area.curso == 'De 3º a 6º de Secundaria.'){
      //   this.cursos=[
      //     {id: 1, curso: '3º de Secundaria'},
      //     {id: 2, curso: '4º de Secundaria'},
      //     {id: 3, curso: '5º de Secundaria'},
      //     {id: 4, curso: '6º de Secundaria'}
      //   ]
      // }else if(this.area.curso == 'Categoría A: Programación. Nivel Básico.: 3ro, 4to o 5to de secundaria. Categoría B: Programación. Nivel Avanzado: 6to de secundaria Warm Up: Martes 21 de octubre. Horas: 14:30 Competencia: Miércoles 22 de octubre. Horas: 09:00'){
      //   this.cursos=[
      //     {id: 1, curso: '3º de Secundaria'},
      //     {id: 2, curso: '4º de Secundaria'},
      //     {id: 3, curso: '5º de Secundaria'},
      //     {id: 4, curso: '6º de Secundaria'}
      //   ]
      //   this.categorias = [
      //     {id: 1, categoria: 'Categoría A'},
      //     {id: 2, categoria: 'Categoría B'}
      //   ]
      // }else if(this.area.curso == 'Nivel 1: Estudiantes hasta 2° de secundaria. Nivel 2: Estudiantes de 3° y 4° de secundaria. Nivel 3: Estudiantes de 5° y 6° de secundaria. Registro Viernes 24 de octubre de horas 14:30 a 15:30 Prueba Viernes 24 de octubre Desde horas 15:30 a 18:00'){
      //   this.cursos=[
      //     {id: 1, curso: '1º de Secundaria'},
      //     {id: 2, curso: '2º de Secundaria'},
      //     {id: 3, curso: '3º de Secundaria'},
      //     {id: 4, curso: '4º de Secundaria'},
      //     {id: 5, curso: '5º de Secundaria'},
      //     {id: 6, curso: '6º de Secundaria'}
      //   ]
      //   this.categorias = [
      //     {id: 1, categoria: 'Nivel 1'},
      //     {id: 2, categoria: 'Nivel 2'},
      //     {id: 3, categoria: 'Nivel 3'}
      //   ]
      // }
      // else if(this.area.curso == 'Categoría A 1º a 3º de secundaria. Categoría B 4º a 6º de secundaria Competencia Categoría A Domingo 26 de octubre. Horas: 9:00 am. Categoría B Domingo 26 de octubre. Horas: 14:00'){
      //   this.cursos=[
      //     {id: 1, curso: '1º de Secundaria'},
      //     {id: 2, curso: '2º de Secundaria'},
      //     {id: 3, curso: '3º de Secundaria'},
      //     {id: 4, curso: '4º de Secundaria'},
      //     {id: 5, curso: '5º de Secundaria'},
      //     {id: 6, curso: '6º de Secundaria'}
      //   ]
      //   this.categorias = [
      //     {id: 1, categoria: 'Categoría A'},
      //     {id: 2, categoria: 'Categoría B'}
      //   ]
      // }
      else{
        this.cursos = []
      }
    }
  }
}
</script>
