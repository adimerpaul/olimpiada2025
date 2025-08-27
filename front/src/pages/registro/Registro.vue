<template>
  <q-layout view="lHh Lpr lFf">
    <!-- HEADER -->
    <q-header elevated class="bg-primary text-white">
      <div class="row items-center q-py-sm q-px-md">
        <q-img src="images.png" style="width: 44px" class="q-mr-md" />
        <div class="col text-center">
          <div class="text-subtitle2">VIII OLIMPIADA DE</div>
          <div class="text-h6 text-weight-bold">CIENCIA Y TECNOLOGÍA F.N.I.</div>
        </div>
        <q-img src="logo_fni.png" style="width: 44px" />
      </div>
      <q-separator color="white" />
    </q-header>

    <q-page-container>
      <q-page class="q-pa-md bg-grey-3">
        <div class="row q-col-gutter-md justify-center">
          <div class="col-12 col-md-10">
            <q-card flat bordered class="bg-white">
              <q-card-section class="row items-center">
                <q-icon name="groups" size="md" class="q-mr-sm text-primary" />
                <div class="text-h6">Registro de Grupo</div>
              </q-card-section>

              <q-separator />

              <q-card-section>
                <q-form @submit.prevent="onSubmit" ref="formRef">
                  <!-- Área -->
                  <div class="row q-col-gutter-md">
                    <div class="col-12 col-md-6">
                      <q-select
                        filled dense
                        v-model="selectedAreaId"
                        :options="areaOptions"
                        option-value="id"
                        option-label="area"
                        emit-value
                        map-options
                        label="Selecciona el Área"
                        :loading="loadingAreas"
                        :rules="[val => !!val || 'El área es obligatoria']"
                        @update:model-value="onAreaChange"
                      >
                        <template #prepend><q-icon name="school" /></template>
                      </q-select>
                    </div>

                    <div class="col-12 col-md-6" v-if="selectedArea">
                      <div class="row q-col-gutter-xs">
                        <div class="col-auto" v-if="selectedArea.fecha1">
                          <div class="bg-blue-9 q-pa-xs text-white text-weight-bold">
                            {{ selectedArea.titulo_fecha1 || 'Fecha 1' }}: {{ formatDate(selectedArea.fecha1) }}
                          </div>
                        </div>
                        <div class="col-auto" v-if="selectedArea.fecha2">
                          <div class="bg-green-9 q-pa-xs text-white text-weight-bold">
                            {{ selectedArea.titulo_fecha2 || 'Fecha 2' }}: {{ formatDate(selectedArea.fecha2) }}
                          </div>
                        </div>
                        <div class="col-auto" v-if="selectedArea.lugar">
                          <div class="bg-teal-9 q-pa-xs text-white text-weight-bold">
                            <q-icon name="place" class="q-mr-xs" />
                            {{ selectedArea.lugar }}
                          </div>
                        </div>
                        <div class="col-auto" v-if="selectedArea.modalidad">
                          <div class="bg-indigo-9 q-pa-xs text-white text-weight-bold">
                            <q-icon name="meeting_room" class="q-mr-xs" />
                            {{ selectedArea.modalidad }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Grupo + comprobante -->
                  <div class="row q-col-gutter-md q-mt-sm">
                    <div class="col-12 col-md-6">
                      <q-input v-model="grupoNombre" label="Nombre del Grupo (opcional)" filled dense maxlength="255">
                        <template #prepend><q-icon name="badge" /></template>
                      </q-input>
                    </div>
                    <div class="col-12 col-md-6">
                      <q-file
                        v-model="pagoFile"
                        label="Comprobante de pago (png/jpg/pdf, máx 5MB)"
                        filled dense :counter="true"
                        accept=".png,.jpg,.jpeg,.pdf" clearable
                      >
                        <template #prepend><q-icon name="attach_file" /></template>
                      </q-file>
                    </div>
                  </div>

                  <!-- DATOS DEL PROFESOR(A) TUTOR(A) -->
                  <div class="q-mt-md">
                    <div class="text-subtitle2 text-weight-bold q-mb-sm">Datos del Profesor(a) Tutor(a)</div>
                    <div class="row q-col-gutter-md">
                      <div class="col-12 col-md-4">
                        <q-input v-model="tutor_paterno" label="Apellido paterno" filled dense :rules="[req]" />
                      </div>
                      <div class="col-12 col-md-4">
                        <q-input v-model="tutor_materno" label="Apellido materno" filled dense />
                      </div>
                      <div class="col-12 col-md-4">
                        <q-input v-model="tutor_nombre" label="Nombres" filled dense :rules="[req]" />
                      </div>
                      <div class="col-12 col-md-4">
                        <q-input v-model="tutor_celular" label="Celular" filled dense />
                      </div>
                      <div class="col-12 col-md-8">
                        <q-input v-model="tutor_correo" label="Correo (Gmail)" type="email" filled dense />
                      </div>
                    </div>
                  </div>

                  <!-- DATOS UNIDAD EDUCATIVA -->
                  <div class="q-mt-md">
                    <div class="text-subtitle2 text-weight-bold q-mb-sm">Unidad Educativa</div>
                    <div class="row q-col-gutter-md">
                      <div class="col-12 col-md-8">
                        <q-input v-model="colegio" label="Nombre de la unidad educativa" filled dense :rules="[req]" />
                      </div>
                      <div class="col-12 col-md-4">
                        <q-input v-model="ciudad" label="Localidad / Ciudad" filled dense />
                      </div>
                    </div>
                  </div>

                  <!-- Integrantes -->
                  <div class="row items-center q-mt-md">
                    <q-icon name="assignment_ind" class="q-mr-sm text-primary" />
                    <div class="text-subtitle1">Integrantes</div>
                    <q-space />
                    <q-btn
                      unelevated color="primary" icon="person_add" label="Agregar"
                      @click="agregarIntegrante"
                      :disable="!selectedArea || integrantes.length >= maxIntegrantes"
                    />
                  </div>

                  <!-- Banner dinámico por área -->
                  <q-banner v-if="selectedArea" class="q-mt-sm bg-grey-1">
                    <template #avatar><q-icon name="info" color="primary" /></template>
                    Puedes registrar
                    <b v-if="minIntegrantes && minIntegrantes > 0">{{ minIntegrantes }}</b>
                    <span v-if="minIntegrantes && minIntegrantes > 0"> a </span>
                    <b>{{ maxIntegrantes }}</b>
                    integrantes por grupo.
                    <span v-if="selectedArea.grupo_mismo_curso"> Todos los integrantes deben ser del mismo curso.</span>
                    Los <b>cursos</b> dependen del área seleccionada.
                  </q-banner>

                  <q-slide-transition>
                    <div v-show="integrantes.length" class="q-mt-sm">
                      <q-markup-table flat bordered>
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Apellidos</th>
                          <th>Nombres</th>
                          <th>CI</th>
                          <th>Teléfono</th>
                          <th>Curso</th>
                          <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(al, idx) in integrantes" :key="idx">
                          <td class="text-grey-7">{{ idx + 1 }}</td>
                          <td style="min-width:180px">
                            <q-input dense filled v-model="al.apellidos" :rules="[req]" />
                          </td>
                          <td style="min-width:180px">
                            <q-input dense filled v-model="al.nombres" :rules="[req]" />
                          </td>
                          <td style="min-width:140px">
                            <q-input dense filled v-model="al.ci" :rules="[req]" @blur="checkAreasPorCi(idx)">
                              <template #append>
                                <q-icon
                                  v-if="al._areasCount !== undefined"
                                  :name="al._areasCount >= maxAreas ? 'warning' : 'check_circle'"
                                  :color="al._areasCount >= maxAreas ? 'orange' : 'positive'"
                                />
                              </template>
                            </q-input>
                          </td>
                          <td style="min-width:130px">
                            <q-input dense filled v-model="al.telefono" />
                          </td>
                          <td style="min-width:160px">
                            <q-select
                              dense filled v-model="al.curso"
                              :options="allowedCourses"
                              option-value="label" option-label="label"
                              emit-value map-options :rules="[req]"
                            />
                          </td>
                          <td class="text-right">
                            <q-btn flat round dense icon="delete" color="negative" @click="quitarIntegrante(idx)" />
                          </td>
                        </tr>
                        </tbody>
                      </q-markup-table>
                    </div>
                  </q-slide-transition>

                  <!-- Botones -->
                  <div class="row q-mt-lg q-gutter-sm">
                    <q-btn
                      label="Registrar" type="submit" color="primary" unelevated
                      :loading="submitting"
                      :disable="!selectedArea || integrantes.length < minIntegrantes"
                    />
                    <q-btn label="Limpiar" flat color="grey-7" @click="resetForm" />
                  </div>
                </q-form>
              </q-card-section>
            </q-card>
          </div>
        </div>

        <q-inner-loading :showing="loadingAreas || submitting">
          <q-spinner-gears size="40px" />
        </q-inner-loading>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { generarPDFInscripcionMat } from "src/utils/inscripcion-pdf.js";

export default {
  name: 'RegistroGrupo',
  data () {
    return {
      areas: [],
      loadingAreas: false,
      submitting: false,

      selectedAreaId: null,
      grupoNombre: '',
      pagoFile: null,

      // Tutor
      tutor_paterno: '',
      tutor_materno: '',
      tutor_nombre: '',
      tutor_celular: '',
      tutor_correo: '',

      // Unidad educativa
      colegio: '',
      ciudad: '',

      integrantes: [],
      maxAreas: 3   // máximo de áreas por CI (regla por persona)
    }
  },
  computed: {
    areaOptions () { return this.areas },
    selectedArea () { return this.areas.find(a => a.id === this.selectedAreaId) || null },
    allowedCourses () {
      if (!this.selectedArea) return []
      const cursos = []
      for (let i = 1; i <= 6; i++) {
        const v = this.selectedArea[`curso${i}`]
        if (v) cursos.push({ label: v })
      }
      return cursos
    },
    // ⬇️ límites por área
    maxIntegrantes () {
      const m = Number(this.selectedArea?.max_integrantes)
      return Number.isFinite(m) && m > 0 ? m : 10
    },
    minIntegrantes () {
      const m = Number(this.selectedArea?.min_integrantes)
      return Number.isFinite(m) && m > 0 ? m : 1
    },
    req () { return v => !!v || 'Obligatorio' }
  },
  mounted () { this.fetchAreas() },
  methods: {
    formatDate (iso) {
      if (!iso) return ''
      try {
        const [y,m,d] = iso.split('-').map(Number)
        const f = new Date(y, m-1, d)
        return f.toLocaleDateString('es-BO', { year: 'numeric', month: 'long', day: 'numeric' })
      } catch { return iso }
    },
    async fetchAreas () {
      this.loadingAreas = true
      try {
        const { data } = await this.$axios.get('/areas')
        this.areas = data || []
      } catch { this.$q.notify({ type: 'negative', message: 'No se pudieron cargar las áreas' }) }
      finally { this.loadingAreas = false }
    },
    onAreaChange () {
      this.integrantes = []
      if (this.selectedArea) this.agregarIntegrante()
    },
    agregarIntegrante () {
      if (!this.selectedArea) return
      if (this.integrantes.length >= this.maxIntegrantes) return
      this.integrantes.push({ apellidos: '', nombres: '', ci: '', telefono: '', curso: '', _areasCount: undefined })
    },
    quitarIntegrante (idx) { this.integrantes.splice(idx, 1) },
    async checkAreasPorCi (idx) {
      const ci = this.integrantes[idx]?.ci
      if (!ci) return
      try {
        const { data } = await this.$axios.get(`/inscritos/areas-por-ci/${encodeURIComponent(ci)}`)
        this.integrantes[idx]._areasCount = Number(data?.areas ?? 0)
        if (this.integrantes[idx]._areasCount >= this.maxAreas) {
          this.$q.notify({ type: 'warning', message: `El CI ${ci} ya está en ${this.integrantes[idx]._areasCount} área(s). Máximo: ${this.maxAreas}.` })
        }
      } catch { this.$q.notify({ type: 'warning', message: 'No se pudo verificar áreas del CI' }) }
    },
    async submitForm(){
      const fd = new FormData()
      fd.append('area_id', this.selectedAreaId)
      if (this.grupoNombre) fd.append('grupo_nombre', this.grupoNombre)
      if (this.pagoFile) fd.append('pago1', this.pagoFile)

      // Tutor & UE
      if (this.tutor_paterno) fd.append('tutor_paterno', this.tutor_paterno)
      if (this.tutor_materno) fd.append('tutor_materno', this.tutor_materno)
      if (this.tutor_nombre)  fd.append('tutor_nombre',  this.tutor_nombre)
      if (this.tutor_celular) fd.append('tutor_celular', this.tutor_celular)
      if (this.tutor_correo)  fd.append('tutor_correo',  this.tutor_correo)
      if (this.colegio) fd.append('colegio', this.colegio)
      if (this.ciudad)  fd.append('ciudad',  this.ciudad)

      // Integrantes (compatibles con backend)
      this.integrantes.forEach((i, idx) => {
        fd.append(`integrantes[${idx}][apellidos]`, i.apellidos)
        fd.append(`integrantes[${idx}][nombres]`, i.nombres)
        fd.append(`integrantes[${idx}][ci]`, i.ci)
        fd.append(`integrantes[${idx}][telefono]`, i.telefono || '')
        fd.append(`integrantes[${idx}][curso]`, i.curso)
      })

      this.submitting = true
      try {
        await this.$axios.post('/inscritos', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
        this.$q.notify({ type: 'positive', message: 'Inscripción registrada' })

        // PDF estilo "formulario escrito"
        generarPDFInscripcionMat({
          tutor: { paterno: this.tutor_paterno, materno: this.tutor_materno, nombres: this.tutor_nombre, celular: this.tutor_celular, email: this.tutor_correo },
          colegio: { nombre: this.colegio, localidad: this.ciudad },
          grado: [...new Set(this.integrantes.map(i => i.curso))].join(', '),
          estudiantes: this.integrantes.map(i => ({ apellidos: i.apellidos, nombres: i.nombres, carnet: i.ci }))
        })

        this.resetForm()
      } catch (e) {
        const msg = e?.response?.data?.message || 'Error al registrar'
        this.$q.notify({ type: 'negative', message: msg })
      } finally { this.submitting = false }
    },
    async onSubmit () {
      // Validaciones básicas
      if (!this.selectedAreaId || this.integrantes.length === 0) {
        this.$q.notify({ type: 'negative', message: 'Completa área e integrantes' }); return
      }

      // Validar mínimo y máximo por área
      if (this.integrantes.length < this.minIntegrantes) {
        this.$q.notify({ type: 'warning', message: `Debes registrar al menos ${this.minIntegrantes} integrante(s).` }); return
      }
      if (this.integrantes.length > this.maxIntegrantes) {
        this.$q.notify({ type: 'warning', message: `Máximo permitido: ${this.maxIntegrantes} integrante(s) para esta área.` }); return
      }

      // Validar máximo de áreas por CI (regla individual)
      for (const al of this.integrantes) {
        if ((al._areasCount ?? 0) >= this.maxAreas) {
          this.$q.notify({ type: 'negative', message: `El CI ${al.ci} superó el máximo de áreas` }); return
        }
      }

      // Si el área exige mismo curso, verificar
      if (this.selectedArea?.grupo_mismo_curso) {
        const setCursos = new Set(this.integrantes.map(i => (i.curso || '').trim()).filter(Boolean))
        if (setCursos.size > 1) {
          this.$q.notify({ type: 'negative', message: 'Esta área exige que todos los integrantes sean del mismo curso.' }); return
        }
      }

      // Confirmación
      this.$q.dialog({
        cancel: true,
        html: true,
        ok: { label: 'Sí, registrar', color: 'primary' },
        message: `
          <div class="text-h6">Confirmar Registro</div>
          <div class="q-mt-sm">
            Una vez registrado, no podrás modificar los datos.<br/>
            <b>Nota:</b> Entregar el presente registro y realizar el pago de acuerdo a la convocatoria.
          </div>
        `,
        color: 'primary',
        persistent: true
      }).onOk(() => this.submitForm())
    },
    resetForm () {
      this.selectedAreaId = null
      this.grupoNombre = ''
      this.pagoFile = null
      this.tutor_paterno = this.tutor_materno = this.tutor_nombre = ''
      this.tutor_celular = this.tutor_correo = ''
      this.colegio = this.ciudad = ''
      this.integrantes = []
    }
  }
}
</script>

<style scoped>
.q-card { border-radius: 16px; }
</style>
