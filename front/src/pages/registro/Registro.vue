<template>
  <q-layout view="lHh Lpr lFf">
    <!-- HEADER -->
    <q-header elevated class="bg-blue-8 text-white">
      <div class="row items-center q-py-sm q-px-md">
        <div class="col-2 col-md-1 flex flex-center">
          <q-img src="images.png" style="width: 44px" spinner-color="primary" />
        </div>
        <div class="col-8 col-md-10">
          <div class="text-center">
            <div class="text-subtitle2 text-primary">VIII OLIMPIADA DE</div>
            <div class="text-h6 text-weight-bold" style="line-height:1">
              CIENCIA Y TECNOLOGÍA F.N.I.
            </div>
          </div>
        </div>
        <div class="col-2 col-md-1 flex flex-center">
          <q-img src="logo_fni.png" style="width: 44px" spinner-color="primary" />
        </div>
      </div>
      <q-separator />
    </q-header>

    <q-page-container>
      <q-page class="bg-grey-2 q-pa-md">
        <div class="row justify-center q-col-gutter-md">

          <!-- FORM CARD -->
          <div class="col-12 col-md-6">
            <q-card flat class="bg-white q-pa-md" bordered>
              <div class="text-h6 q-mb-md">Registro</div>

              <q-form @submit.prevent="onSubmit" @reset.prevent="onReset" ref="formRef">
                <div class="row q-col-gutter-md">
                  <!-- Área -->
                  <div class="col-12">
                    <q-select
                      filled
                      v-model="selectedAreaId"
                      :options="areaOptions"
                      :loading="loading"
                      option-value="id"
                      option-label="area"
                      emit-value
                      map-options
                      dense
                      label="Selecciona tu área"
                      :rules="[val => !!val || 'El área es obligatoria']"
                      @update:model-value="onAreaChange"
                    >
                      <template #prepend>
                        <q-icon name="school" />
                      </template>
                      <template #no-option>
                        <q-item>
                          <q-item-section class="text-grey">Sin resultados</q-item-section>
                        </q-item>
                      </template>
                    </q-select>
                  </div>

                  <!-- Curso -->
                  <div class="col-12" v-if="selectedArea">
                    <q-select
                      filled
                      v-model="selectedCourse"
                      :options="allowedCourses"
                      option-value="value"
                      option-label="label"
                      emit-value
                      map-options
                      dense
                      label="Selecciona tu curso"
                      :disable="allowedCourses.length === 0"
                      :rules="[val => !!val || 'El curso es obligatorio']"
                    >
                      <template #prepend>
                        <q-icon name="class" />
                      </template>
                    </q-select>
                  </div>

                  <!-- Categoría (cuando aplica) -->
                  <div class="col-12" v-if="categoryOptions.length">
                    <q-select
                      filled
                      v-model="selectedCategory"
                      :options="categoryOptions"
                      option-value="value"
                      option-label="label"
                      emit-value
                      map-options
                      dense
                      label="Selecciona tu categoría"
                      :rules="[val => !!val || 'La categoría es obligatoria']"
                    >
                      <template #prepend>
                        <q-icon name="category" />
                      </template>
                    </q-select>
                  </div>

                  <!-- Botones -->
                  <div class="col-12">
                    <div class="row q-gutter-sm">
                      <q-btn label="Registrar" type="submit" color="primary" unelevated :loading="submitting" />
                      <q-btn label="Limpiar" type="reset" color="grey-7" flat />
                    </div>
                  </div>
                </div>
              </q-form>
            </q-card>
          </div>

          <!-- INFO CARD -->
          <div class="col-12 col-md-6" v-if="selectedArea">
            <q-card flat class="bg-white q-pa-md" bordered>
              <div class="row items-center q-mb-sm">
                <q-icon name="info" size="28px" class="text-primary q-mr-sm" />
                <div class="text-h6">Información del área</div>
              </div>

              <q-markup-table flat bordered dense class="bg-white">
                <tbody>
                <tr v-if="selectedArea.titulo_fecha1 || selectedArea.fecha1">
                  <td class="text-bold">{{ selectedArea.titulo_fecha1 || 'Fecha 1' }}</td>
                  <td>{{ formatDate(selectedArea.fecha1) }}</td>
                </tr>
                <tr v-if="selectedArea.titulo_fecha2 || selectedArea.fecha2">
                  <td class="text-bold">{{ selectedArea.titulo_fecha2 || 'Fecha 2' }}</td>
                  <td>{{ formatDate(selectedArea.fecha2) }}</td>
                </tr>
                <tr v-if="selectedArea.lugar">
                  <td class="text-bold">Lugar</td>
                  <td>{{ selectedArea.lugar }}</td>
                </tr>
                <tr v-if="selectedArea.modalidad">
                  <td class="text-bold">Modalidad</td>
                  <td>{{ selectedArea.modalidad }}</td>
                </tr>
                <tr v-if="selectedArea.inscripcion">
                  <td class="text-bold">Inscripción</td>
                  <td style="white-space: pre-wrap">{{ selectedArea.inscripcion }}</td>
                </tr>
                </tbody>
              </q-markup-table>
            </q-card>
          </div>

        </div>

        <!-- Skeleton mientras carga -->
        <q-inner-loading :showing="loading">
          <q-spinner size="42px" />
        </q-inner-loading>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'Registro',
  data () {
    return {
      loading: false,
      submitting: false,
      areas: [],
      selectedAreaId: null,
      selectedCourse: null,
      selectedCategory: null
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
      // Mapa cursos 1..6 según columnas curso1..curso6 no nulas en BD
      const map = [
        { key: 'curso1', label: '1º de Secundaria', value: 1 },
        { key: 'curso2', label: '2º de Secundaria', value: 2 },
        { key: 'curso3', label: '3º de Secundaria', value: 3 },
        { key: 'curso4', label: '4º de Secundaria', value: 4 },
        { key: 'curso5', label: '5º de Secundaria', value: 5 },
        { key: 'curso6', label: '6º de Secundaria', value: 6 }
      ]
      return map
        .filter(c => this.selectedArea[c.key] && this.selectedArea[c.key] !== '')
        .map(c => ({ label: c.label, value: c.value }))
    },
    categoryOptions () {
      if (!this.selectedArea) return []
      const name = (this.selectedArea.area || '').toUpperCase()

      if (name.includes('PROGRAMACIÓN')) {
        return [
          { label: 'Categoría A (3º-5º)', value: 'A' },
          { label: 'Categoría B (6º)', value: 'B' }
        ]
      }
      if (name.includes('ROBÓTICA')) {
        return [
          { label: 'Nivel 1 (1º-2º)', value: 'N1' },
          { label: 'Nivel 2 (3º-4º)', value: 'N2' },
          { label: 'Nivel 3 (5º-6º)', value: 'N3' }
        ]
      }
      if (name.includes('DISEÑADORES') || name.includes('FABRICADORES')) {
        return [
          { label: 'Categoría A (1º-3º)', value: 'A' },
          { label: 'Categoría B (4º-6º)', value: 'B' }
        ]
      }
      return []
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
        // Se asume que /areas devuelve registros con columnas:
        // id, area, curso1..curso6, titulo_fecha1, fecha1, titulo_fecha2, fecha2, lugar, modalidad, inscripcion
        this.areas = (data || []).map(a => ({
          ...a,
          // normalizo por si el backend manda null/undefined
          curso1: a.curso1 ?? null,
          curso2: a.curso2 ?? null,
          curso3: a.curso3 ?? null,
          curso4: a.curso4 ?? null,
          curso5: a.curso5 ?? null,
          curso6: a.curso6 ?? null
        }))
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudo cargar áreas' })
        console.error(e)
      } finally {
        this.loading = false
      }
    },
    onAreaChange () {
      // Reset dependientes
      this.selectedCourse = null
      this.selectedCategory = null
    },
    formatDate (iso) {
      if (!iso) return ''
      // Espera 'YYYY-MM-DD'
      try {
        const [y, m, d] = iso.split('-').map(Number)
        if (!y || !m || !d) return iso
        const f = new Date(y, m - 1, d)
        return f.toLocaleDateString('es-BO', { year: 'numeric', month: 'long', day: 'numeric' })
      } catch {
        return iso
      }
    },
    async onSubmit () {
      if (!this.$refs.formRef) return
      const ok = await this.$refs.formRef.validate()
      if (!ok) return

      // Payload de ejemplo (ajusta endpoint según tu backend)
      const payload = {
        area_id: this.selectedAreaId,
        curso: this.selectedCourse,       // 1..6
        categoria: this.selectedCategory  // A/B/N1/N2/N3 o null
      }

      this.submitting = true
      try {
        await this.$axios.post('/inscripciones', payload)
        this.$q.notify({ type: 'positive', message: 'Inscripción registrada' })
        this.onReset()
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudo registrar' })
        console.error(e)
      } finally {
        this.submitting = false
      }
    },
    onReset () {
      this.selectedAreaId = null
      this.selectedCourse = null
      this.selectedCategory = null
    }
  }
}
</script>

<style scoped>
/* Toques sutiles */
.q-card {
  border-radius: 16px;
}
</style>
