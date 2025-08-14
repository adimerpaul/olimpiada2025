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
<!--                        <div class="col-auto" v-if="selectedArea.fecha2">-->
<!--                          <q-chip icon="event" color="secondary" text-color="white" square>-->
<!--                            {{ selectedArea.titulo_fecha2 || 'Fecha 2' }}: {{ formatDate(selectedArea.fecha2) }}-->
<!--                          </q-chip>-->
<!--                        </div>-->
<!--                        <div class="col-auto" v-if="selectedArea.lugar">-->
<!--                          <q-chip icon="place" color="teal" text-color="white" square>-->
<!--                            {{ selectedArea.lugar }}-->
<!--                          </q-chip>-->
<!--                        </div>-->
<!--                        <div class="col-auto" v-if="selectedArea.modalidad">-->
<!--                          <q-chip icon="meeting_room" color="indigo" text-color="white" square>-->
<!--                            {{ selectedArea.modalidad }}-->
<!--                          </q-chip>-->
<!--                        </div>-->
                      </div>
                    </div>
                  </div>

                  <!-- Nombre de grupo + comprobante -->
                  <div class="row q-col-gutter-md q-mt-sm">
                    <div class="col-12 col-md-6">
                      <q-input
                        v-model="grupoNombre"
                        label="Nombre del Grupo (opcional)"
                        filled dense
                        maxlength="255"
                      >
                        <template #prepend><q-icon name="badge" /></template>
                      </q-input>
                    </div>
                    <div class="col-12 col-md-6">
                      <q-file
                        v-model="pagoFile"
                        label="Comprobante de pago (png/jpg/pdf, máx 5MB)"
                        filled dense
                        :counter="true"
                        accept=".png,.jpg,.jpeg,.pdf"
                        clearable
                      >
                        <template #prepend><q-icon name="attach_file" /></template>
                        <template #append>
                          <q-icon name="info" class="cursor-pointer">
                            <q-tooltip>Adjunta una imagen o PDF del pago</q-tooltip>
                          </q-icon>
                        </template>
                      </q-file>
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
                      :disable="!selectedArea || integrantes.length >= 10"
                    />
                  </div>

                  <q-banner v-if="selectedArea" class="q-mt-sm bg-grey-1">
                    <template #avatar><q-icon name="info" color="primary" /></template>
                    Puedes registrar hasta <b>10</b> integrantes por grupo. Los <b>cursos</b> dependen del área seleccionada.
                  </q-banner>

                  <q-slide-transition>
                    <div v-show="integrantes.length" class="q-mt-sm">
                      <q-markup-table flat bordered>
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>CI</th>
                          <th>Tutor</th>
                          <th>Teléfono</th>
                          <th>Curso</th>
                          <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(al, idx) in integrantes" :key="idx">
                          <td class="text-grey-7">{{ idx + 1 }}</td>
                          <td style="min-width:200px">
                            <q-input dense filled v-model="al.nombre" :rules="[req]" />
                          </td>
                          <td style="min-width:140px">
                            <q-input
                              dense filled v-model="al.ci" :rules="[req]"
                              @blur="checkAreasPorCi(idx)"
                            >
                              <template #append>
                                <q-icon
                                  v-if="al._areasCount !== undefined"
                                  :name="al._areasCount >= maxAreas ? 'warning' : 'check_circle'"
                                  :color="al._areasCount >= maxAreas ? 'orange' : 'positive'"
                                />
                              </template>
                            </q-input>
                          </td>
                          <td><q-input dense filled v-model="al.tutor" /></td>
                          <td style="min-width:130px"><q-input dense filled v-model="al.telefono" /></td>
                          <td style="min-width:170px">
                            <q-select
                              dense filled v-model="al.curso"
                              :options="allowedCourses"
                              option-value="label" option-label="label"
                              emit-value
                              map-options
                              :rules="[req]"
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
                      label="Registrar"
                      type="submit"
                      color="primary"
                      unelevated
                      :loading="submitting"
                      :disable="!selectedArea || integrantes.length === 0"
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
import {generarPDFInscripcion} from "src/utils/inscripcion-pdf.js";

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

      integrantes: [],

      maxAreas: 3 // tope por CI
    }
  },
  computed: {
    areaOptions () {
      return this.areas
    },
    selectedArea () {
      return this.areas.find(a => a.id === this.selectedAreaId) || null
    },
    allowedCourses () {
      if (!this.selectedArea) return []
      const cursos = []
      for (let i = 1; i <= 6; i++) {
        const v = this.selectedArea[`curso${i}`]
        if (v) cursos.push({ label: v })
      }
      return cursos
    },
    req () {
      return (v) => !!v || 'Obligatorio'
    }
  },
  mounted () {
    this.fetchAreas()
  },
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
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudieron cargar las áreas' })
      } finally {
        this.loadingAreas = false
      }
    },
    onAreaChange () {
      this.integrantes = []
      if (this.selectedArea) this.agregarIntegrante()
    },
    agregarIntegrante () {
      if (!this.selectedArea) return
      if (this.integrantes.length >= 10) return
      this.integrantes.push({ nombre: '', ci: '', tutor: '', telefono: '', curso: '', _areasCount: undefined })
    },
    quitarIntegrante (idx) {
      this.integrantes.splice(idx, 1)
    },
    async checkAreasPorCi (idx) {
      const ci = this.integrantes[idx]?.ci
      if (!ci) return
      try {
        const { data } = await this.$axios.get(`/inscritos/areas-por-ci/${encodeURIComponent(ci)}`)
        // Vue 3: asignación directa
        this.integrantes[idx]._areasCount = Number(data?.areas ?? 0)

        if (this.integrantes[idx]._areasCount >= this.maxAreas) {
          this.$q.notify({
            type: 'warning',
            message: `El CI ${ci} ya está en ${this.integrantes[idx]._areasCount} área(s). Máximo permitido: ${this.maxAreas}.`
          })
        }
      } catch {
        this.$q.notify({ type: 'warning', message: 'No se pudo verificar áreas del CI' })
      }
    },
    async onSubmit () {
      // Validaciones simples front
      if (!this.selectedAreaId || this.integrantes.length === 0) {
        this.$q.notify({ type: 'negative', message: 'Completa área e integrantes' })
        return
      }
      // Evitar intentar inscribir CI con tope superado (front)
      for (const al of this.integrantes) {
        if ((al._areasCount ?? 0) >= this.maxAreas) {
          this.$q.notify({ type: 'negative', message: `El CI ${al.ci} superó el máximo de áreas` })
          return
        }
      }

      // Armar FormData para subir archivo + JSON
      const fd = new FormData()
      fd.append('area_id', this.selectedAreaId)
      if (this.grupoNombre) fd.append('grupo_nombre', this.grupoNombre)
      if (this.pagoFile) fd.append('pago1', this.pagoFile)

// En vez de enviar un JSON string:
      this.integrantes.forEach((i, idx) => {
        fd.append(`integrantes[${idx}][nombre]`, i.nombre)
        fd.append(`integrantes[${idx}][ci]`, i.ci)
        fd.append(`integrantes[${idx}][tutor]`, i.tutor || '')
        fd.append(`integrantes[${idx}][telefono]`, i.telefono || '')
        fd.append(`integrantes[${idx}][curso]`, i.curso)
      })

      this.submitting = true
      try {
        // Nota: En Laravel, como validamos array, haremos decode en el controller si viene como string
        // (pero arriba ya validamos formato array; aquí enviamos JSON)
        // Ajuste en controller: $integrantes = json_decode($request->input('integrantes','[]'), true);
        const resp = await this.$axios.post('/inscritos', fd, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
        this.$q.notify({ type: 'positive', message: 'Inscripción registrada' })
        generarPDFInscripcion({
          inscrito: resp.data?.inscrito || {},
          area: this.selectedArea,
          integrantes: this.integrantes,
          baseUrl: window.location.origin,
          assets: { left: '/images.png', right: '/logo_fni.png' }
        })
        this.resetForm()
      } catch (e) {
        const msg = e?.response?.data?.message || 'Error al registrar'
        this.$q.notify({ type: 'negative', message: msg })
      } finally {
        this.submitting = false
      }
    },
    resetForm () {
      this.selectedAreaId = null
      this.grupoNombre = ''
      this.pagoFile = null
      this.integrantes = []
    }
  }
}
</script>

<style scoped>
.q-card { border-radius: 16px; }
</style>
