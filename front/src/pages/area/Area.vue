<template>
  <q-page class="q-pa-md bg-grey-3">
    <q-card flat bordered class="bg-white">

      <!-- Toolbar -->
      <q-card-section class="row items-center q-col-gutter-md">
        <div class="col-12 col-md-4">
          <q-input v-model="search" filled dense label="Buscar área..." clearable>
            <template #prepend><q-icon name="search" /></template>
          </q-input>
        </div>
        <div class="col-12 col-md-8 text-right">
          <q-btn color="primary" icon="add" label="Nueva Área" unelevated @click="openCreate" />
        </div>
      </q-card-section>

      <q-separator />

      <!-- Tabla -->
      <q-card-section>
        <q-markup-table dense wrap-cells>
          <thead>
          <tr class="bg-primary text-white">
            <th>Área</th>
            <th>Cursos</th>
            <th>Fechas</th>
            <th>Lugar / Modalidad</th>
            <th>Integrantes</th>
            <th>Acciones</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="a in rowsFiltered" :key="a.id">
            <td class="text-weight-medium">{{ a.area }}</td>

            <td>
              <q-chip
                v-for="(label, key) in coursesFromRow(a)"
                :key="key" color="primary" text-color="white" dense square class="q-mr-xs q-mb-xs">
                {{ label }}
              </q-chip>
              <span v-if="!hasAnyCourse(a)" class="text-grey">—</span>
            </td>

            <td>
              <div v-if="a.fecha1 || a.titulo_fecha1">
                <q-icon name="event" class="q-mr-xs" /> {{ a.titulo_fecha1 || 'Fecha 1' }}:
                <b>{{ formatDate(a.fecha1) }}</b>
              </div>
              <div v-if="a.fecha2 || a.titulo_fecha2">
                <q-icon name="event" class="q-mr-xs" /> {{ a.titulo_fecha2 || 'Fecha 2' }}:
                <b>{{ formatDate(a.fecha2) }}</b>
              </div>
              <span v-if="!a.fecha1 && !a.fecha2" class="text-grey">—</span>
            </td>

            <td>
              <div v-if="a.lugar"><q-icon name="place" class="q-mr-xs" /> {{ a.lugar }}</div>
              <div v-if="a.modalidad"><q-icon name="meeting_room" class="q-mr-xs" /> {{ a.modalidad }}</div>
              <span v-if="!a.lugar && !a.modalidad" class="text-grey">—</span>
            </td>

            <td>
              <div v-if="a.min_integrantes || a.max_integrantes">
                <q-badge color="blue-8" text-color="white" class="q-mr-xs">
                  {{ a.min_integrantes || 1 }} – {{ a.max_integrantes || 10 }}
                </q-badge>
              </div>
              <q-chip v-if="a.grupo_mismo_curso" dense color="orange-9" text-color="white" icon="rule">
                Mismo curso
              </q-chip>
              <span v-if="!a.min_integrantes && !a.max_integrantes && !a.grupo_mismo_curso" class="text-grey">—</span>
            </td>

            <td class="text-right">
              <q-btn dense flat round icon="edit" color="primary" @click="openEdit(a)">
                <q-tooltip>Editar</q-tooltip>
              </q-btn>
              <q-btn dense flat round icon="delete" color="negative" @click="confirmDelete(a)">
                <q-tooltip>Eliminar</q-tooltip>
              </q-btn>
            </td>
          </tr>
          </tbody>
        </q-markup-table>
      </q-card-section>

      <q-inner-loading :showing="loading">
        <q-spinner-gears size="40px" />
      </q-inner-loading>
    </q-card>

    <!-- Dialog create/edit -->
    <q-dialog v-model="dlg.show" persistent>
      <q-card style="max-width: 980px; width: 100%;">
        <q-card-section class="row items-center">
          <q-icon :name="dlg.isEdit ? 'edit' : 'add'" class="q-mr-sm text-primary" />
          <div class="text-h6">{{ dlg.isEdit ? 'Editar área' : 'Nueva área' }}</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup :disable="dlg.saving" />
        </q-card-section>

        <q-separator />

        <q-card-section>
          <q-form ref="formRef" @submit.prevent="save">
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-6">
                <q-input v-model="form.area" filled dense label="Nombre del área"
                         :rules="[v => !!v || 'Obligatorio']">
                  <template #prepend><q-icon name="school" /></template>
                </q-input>
              </div>

              <div class="col-12 col-md-3">
                <q-input v-model="form.titulo_fecha1" filled dense label="Título Fecha 1">
                  <template #prepend><q-icon name="title" /></template>
                </q-input>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="form.fecha1" filled dense label="Fecha 1 (YYYY-MM-DD)" mask="####-##-##">
                  <template #append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy transition-show="scale" transition-hide="scale">
                        <q-date v-model="form.fecha1" mask="YYYY-MM-DD" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>

              <div class="col-12 col-md-3">
                <q-input v-model="form.titulo_fecha2" filled dense label="Título Fecha 2">
                  <template #prepend><q-icon name="title" /></template>
                </q-input>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="form.fecha2" filled dense label="Fecha 2 (YYYY-MM-DD)" mask="####-##-##">
                  <template #append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy transition-show="scale" transition-hide="scale">
                        <q-date v-model="form.fecha2" mask="YYYY-MM-DD" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>

              <div class="col-12 col-md-6">
                <q-input v-model="form.lugar" filled dense label="Lugar">
                  <template #prepend><q-icon name="place" /></template>
                </q-input>
              </div>

              <div class="col-12 col-md-6">
                <q-input v-model="form.modalidad" filled dense label="Modalidad (Presencial/Virtual)">
                  <template #prepend><q-icon name="meeting_room" /></template>
                </q-input>
              </div>

              <div class="col-12">
                <q-input v-model="form.inscripcion" type="textarea" filled autogrow
                         label="Notas/Información de inscripción" />
              </div>

              <!-- Cursos -->
              <div class="col-12">
                <q-banner class="bg-grey-1 q-mb-sm">
                  <template #avatar><q-icon name="class" color="primary" /></template>
                  Selecciona los cursos habilitados (si está activado se guarda su rótulo).
                </q-banner>
                <div class="row q-col-gutter-sm">
                  <div v-for="n in 6" :key="n" class="col-6 col-md-2">
                    <q-toggle v-model="coursesToggles[n]" :label="defaultCourseLabel(n)" />
                  </div>
                </div>
              </div>

              <!-- Cupos por grado -->
              <div class="col-12">
                <q-banner class="bg-grey-1 q-mb-sm">
                  <template #avatar><q-icon name="format_list_numbered" color="primary" /></template>
                  Cupos por grado (opcional).
                </q-banner>
                <div class="row q-col-gutter-sm">
                  <div v-for="n in 6" :key="'cupo' + n" class="col-6 col-md-2">
                    <q-input
                      v-model.number="cupos[n]"
                      type="number" min="0" filled dense
                      :label="`Cupo ${n}º`" />
                  </div>
                </div>
              </div>

              <!-- Límites de integrantes y regla "mismo curso" -->
              <div class="col-12">
                <q-banner class="bg-grey-1 q-mb-sm">
                  <template #avatar><q-icon name="group" color="primary" /></template>
                  Límites de integrantes por grupo y regla de pertenecer al mismo curso.
                </q-banner>
                <div class="row q-col-gutter-sm">
                  <div class="col-12 col-md-3">
                    <q-input v-model.number="form.min_integrantes" type="number" :min="1" filled dense
                             label="Mínimo integrantes" />
                  </div>
                  <div class="col-12 col-md-3">
                    <q-input v-model.number="form.max_integrantes" type="number" :min="1" filled dense
                             label="Máximo integrantes" />
                  </div>
                  <div class="col-12 col-md-6 flex items-center">
                    <q-toggle v-model="form.grupo_mismo_curso" label="Todos del mismo curso" />
                  </div>
                </div>
              </div>

              <!-- Reglas especiales (JSON libre) -->
              <div class="col-12">
                <q-input v-model="reglasString" type="textarea" filled autogrow
                         label="Reglas especiales (JSON, opcional)"
                         hint='Ej: {"quimica_experimental":{"grupo_min":3,"grupo_max":4}}' />
              </div>
            </div>

            <div class="row q-mt-md q-gutter-sm">
              <q-btn :loading="dlg.saving" type="submit" color="primary" :label="dlg.isEdit ? 'Guardar' : 'Crear'" />
              <q-btn flat color="grey-7" label="Cancelar" v-close-popup :disable="dlg.saving" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
export default {
  name: 'AreaCrud',
  data () {
    return {
      loading: false,
      rows: [],
      search: '',
      dlg: { show: false, isEdit: false, saving: false },
      form: {
        id: null,
        area: '',
        curso1: null, curso2: null, curso3: null, curso4: null, curso5: null, curso6: null,
        titulo_fecha1: '', fecha1: '',
        titulo_fecha2: '', fecha2: '',
        lugar: '', modalidad: '', inscripcion: '',
        cupos_por_grado: null,
        reglas_especiales: null,
        min_integrantes: 1,
        max_integrantes: 10,
        grupo_mismo_curso: false
      },
      coursesToggles: { 1:false,2:false,3:false,4:false,5:false,6:false },
      cupos: { 1:null,2:null,3:null,4:null,5:null,6:null },
      reglasString: ''
    }
  },
  computed: {
    rowsFiltered () {
      const q = (this.search || '').toLowerCase().trim()
      if (!q) return this.rows
      return this.rows.filter(r =>
        [r.area, r.lugar, r.modalidad, r.titulo_fecha1, r.titulo_fecha2]
          .filter(Boolean).join(' ').toLowerCase().includes(q)
      )
    }
  },
  mounted () {
    this.fetchAreas()
  },
  methods: {
    async fetchAreas () {
      this.loading = true
      try {
        const { data } = await this.$axios.get('/areas')
        this.rows = data || []
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudieron cargar las áreas' })
      } finally {
        this.loading = false
      }
    },
    formatDate (iso) {
      if (!iso) return ''
      try {
        const [y,m,d] = iso.split('-').map(Number)
        return new Date(y, m-1, d).toLocaleDateString('es-BO', { year:'numeric', month:'long', day:'numeric' })
      } catch { return iso }
    },
    coursesFromRow (a) {
      const o = {}
      for (let i = 1; i <= 6; i++) {
        const v = a[`curso${i}`]
        if (v) o[`curso${i}`] = v
      }
      return o
    },
    hasAnyCourse (a) {
      for (let i = 1; i <= 6; i++) if (a[`curso${i}`]) return true
      return false
    },
    defaultCourseLabel (n) {
      return `${n}º Secundaria`
    },
    openCreate () {
      this.dlg.isEdit = false
      this.dlg.show = true
      this.resetForm()
    },
    openEdit (row) {
      this.dlg.isEdit = true
      this.dlg.show = true
      this.resetForm()

      // cargar en form
      this.form = {
        id: row.id,
        area: row.area || '',
        curso1: row.curso1 || null,
        curso2: row.curso2 || null,
        curso3: row.curso3 || null,
        curso4: row.curso4 || null,
        curso5: row.curso5 || null,
        curso6: row.curso6 || null,
        titulo_fecha1: row.titulo_fecha1 || '',
        fecha1: row.fecha1 || '',
        titulo_fecha2: row.titulo_fecha2 || '',
        fecha2: row.fecha2 || '',
        lugar: row.lugar || '',
        modalidad: row.modalidad || '',
        inscripcion: row.inscripcion || '',
        cupos_por_grado: row.cupos_por_grado || null,
        reglas_especiales: row.reglas_especiales || null,
        min_integrantes: row.min_integrantes ?? 1,
        max_integrantes: row.max_integrantes ?? 10,
        grupo_mismo_curso: !!row.grupo_mismo_curso
      }

      // toggles cursos
      for (let i = 1; i <= 6; i++) {
        this.coursesToggles[i] = !!this.form[`curso${i}`]
      }

      // cupos
      const c = this.form.cupos_por_grado || {}
      for (let i = 1; i <= 6; i++) this.cupos[i] = c[String(i)] ?? null

      // reglas JSON en textarea
      this.reglasString = this.form.reglas_especiales ? JSON.stringify(this.form.reglas_especiales, null, 2) : ''
    },
    resetForm () {
      this.form = {
        id: null,
        area: '',
        curso1: null, curso2: null, curso3: null, curso4: null, curso5: null, curso6: null,
        titulo_fecha1: '', fecha1: '',
        titulo_fecha2: '', fecha2: '',
        lugar: '', modalidad: '', inscripcion: '',
        cupos_por_grado: null,
        reglas_especiales: null,
        min_integrantes: 1,
        max_integrantes: 10,
        grupo_mismo_curso: false
      }
      this.coursesToggles = { 1:false,2:false,3:false,4:false,5:false,6:false }
      this.cupos = { 1:null,2:null,3:null,4:null,5:null,6:null }
      this.reglasString = ''
    },
    buildPayload () {
      // mapear toggles -> cursoX (rótulo o null)
      for (let i = 1; i <= 6; i++) {
        this.form[`curso${i}`] = this.coursesToggles[i] ? this.defaultCourseLabel(i) : null
      }

      // construir cupos_por_grado (solo llaves con valor numérico)
      const cuposObj = {}
      for (let i = 1; i <= 6; i++) {
        const v = this.cupos[i]
        if (v !== null && v !== '' && !isNaN(v)) cuposObj[String(i)] = Number(v)
      }
      this.form.cupos_por_grado = Object.keys(cuposObj).length ? cuposObj : null

      // validar JSON de reglas_especiales si el textarea tiene algo
      if ((this.reglasString || '').trim() !== '') {
        try {
          this.form.reglas_especiales = JSON.parse(this.reglasString)
        } catch {
          this.$q.notify({ type: 'warning', message: 'El JSON de Reglas especiales no es válido.' })
          throw new Error('invalid_json')
        }
      } else {
        this.form.reglas_especiales = null
      }

      // validar límites min/max
      const minI = Number(this.form.min_integrantes ?? 1)
      const maxI = Number(this.form.max_integrantes ?? 1)
      if (!Number.isFinite(minI) || minI < 1) {
        this.$q.notify({ type: 'warning', message: 'El mínimo de integrantes debe ser un número ≥ 1.' })
        throw new Error('invalid_min')
      }
      if (!Number.isFinite(maxI) || maxI < minI) {
        this.$q.notify({ type: 'warning', message: 'El máximo de integrantes debe ser ≥ al mínimo.' })
        throw new Error('invalid_max')
      }
      this.form.min_integrantes = minI
      this.form.max_integrantes = maxI

      // devolver copia limpia
      const {
        id, area, curso1,curso2,curso3,curso4,curso5,curso6,
        titulo_fecha1,fecha1,titulo_fecha2,fecha2,
        lugar,modalidad,inscripcion, cupos_por_grado, reglas_especiales,
        min_integrantes, max_integrantes, grupo_mismo_curso
      } = this.form

      const payload = {
        area,
        curso1,curso2,curso3,curso4,curso5,curso6,
        titulo_fecha1,fecha1,titulo_fecha2,fecha2,
        lugar,modalidad,inscripcion,
        cupos_por_grado, reglas_especiales,
        min_integrantes, max_integrantes, grupo_mismo_curso
      }
      return { id, payload }
    },
    async save () {
      const ok = await this.$refs.formRef.validate()
      if (!ok) return

      let id, payload
      try {
        ({ id, payload } = this.buildPayload())
      } catch {
        return
      }

      this.dlg.saving = true
      try {
        if (this.dlg.isEdit && id) {
          await this.$axios.put(`/areas/${id}`, payload)
          this.$q.notify({ type: 'positive', message: 'Área actualizada' })
        } else {
          await this.$axios.post('/areas', payload)
          this.$q.notify({ type: 'positive', message: 'Área creada' })
        }
        this.dlg.show = false
        await this.fetchAreas()
      } catch (e) {
        const msg = e?.response?.data?.message || 'No se pudo guardar el área'
        this.$q.notify({ type: 'negative', message: msg })
      } finally {
        this.dlg.saving = false
      }
    },
    confirmDelete (row) {
      this.$q.dialog({
        title: 'Eliminar',
        message: `¿Eliminar el área "${row.area}"? Esta acción no se puede deshacer.`,
        cancel: true,
        persistent: true,
        ok: { label: 'Eliminar', color: 'negative' },
        cancelLabel: 'Cancelar'
      }).onOk(async () => {
        try {
          await this.$axios.delete(`/areas/${row.id}`)
          this.$q.notify({ type: 'positive', message: 'Área eliminada' })
          await this.fetchAreas()
        } catch (e) {
          const msg = e?.response?.data?.message || 'No se pudo eliminar'
          this.$q.notify({ type: 'negative', message: msg })
        }
      })
    }
  }
}
</script>
