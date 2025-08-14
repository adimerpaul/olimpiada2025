<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <q-page class="q-pa-md bg-grey-3">
        <div class="row justify-center">
          <div class="col-12 col-md-10">
            <q-card flat bordered class="bg-white">

              <!-- HEADER -->
              <q-card-section class="row items-center">
                <q-avatar square size="40px" class="q-mr-sm"><img src="/images.png"></q-avatar>
                <div class="col">
                  <div class="text-caption">VIII OLIMPIADA DE</div>
                  <div class="text-subtitle1 text-weight-bold">CIENCIA Y TECNOLOGÍA F.N.I.</div>
                </div>
                <q-avatar square size="40px"><img src="/logo_fni.png"></q-avatar>
              </q-card-section>
              <q-separator />

              <!-- ESTADOS -->
              <q-card-section v-if="loading" class="text-grey-7">
                <q-skeleton type="text" /><q-skeleton type="rect" height="120px" />
              </q-card-section>

              <q-banner v-else-if="error" class="bg-red-1 text-red-10">
                <template #avatar><q-icon name="error" color="red-10" /></template>
                {{ error }}
              </q-banner>

              <!-- CONTENIDO -->
              <template v-else>
                <q-card-section class="row items-center">
                  <div class="col">
                    <div class="text-h6">Constancia de Inscripción</div>
                    <div class="text-caption text-grey-7">
                      Fecha: {{ fechaIns }} · ID: {{ ins?.id }}
                    </div>
                  </div>

                  <div class="col-auto q-gutter-xs">
<!--                    <q-btn outline color="primary" icon="picture_as_pdf" label="Descargar PDF"-->
<!--                           @click="descargarPdf" />-->
<!--                    <q-btn v-if="ins?.pago1" outline color="teal" icon="attach_file" label="Comprobante"-->
<!--                           :href="fileUrl(ins.pago1)" target="_blank" />-->
                  </div>
                </q-card-section>

                <q-card-section>
                  <div class="q-pa-md bg-grey-1" style="border-radius: 12px;">
                    <div class="row q-col-gutter-md items-center">
                      <div class="col-12 col-md">
                        <div class="text-caption text-grey-7">Área</div>
                        <div class="text-subtitle1 text-weight-medium">{{ area?.area || '-' }}</div>
                      </div>
                      <div class="col-6 col-md">
                        <div class="text-caption text-grey-7">Modalidad</div>
                        <div class="text-body1">{{ area?.modalidad || '-' }}</div>
                      </div>
                      <div class="col-6 col-md">
                        <div class="text-caption text-grey-7">Lugar</div>
                        <div class="text-body1 ellipsis">{{ area?.lugar || '—' }}</div>
                      </div>
                      <div class="col-12 col-md">
                        <div class="text-caption text-grey-7">Grupo</div>
                        <div class="text-body1">{{ ins?.grupo_nombre || '—' }}</div>
                      </div>
                    </div>
                  </div>
                </q-card-section>

                <q-card-section>
                  <q-markup-table flat bordered>
                    <thead>
                    <tr class="bg-primary text-white">
                      <th class="text-left">#</th>
                      <th class="text-left">Nombre</th>
                      <th class="text-left">CI</th>
                      <th class="text-left">Curso</th>
                      <th class="text-left">Tutor</th>
                      <th class="text-left">Teléfono</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(al, i) in integrantes" :key="i">
                      <td>{{ i + 1 }}</td>
                      <td>{{ al.nombre }}</td>
                      <td>{{ al.ci || '—' }}</td>
                      <td>{{ al.curso || '—' }}</td>
                      <td>{{ al.tutor || '—' }}</td>
                      <td>{{ al.telefono || '—' }}</td>
                    </tr>
                    <tr v-if="!integrantes.length">
                      <td colspan="6" class="text-grey-7">Sin integrantes</td>
                    </tr>
                    </tbody>
                  </q-markup-table>
                </q-card-section>
              </template>

              <q-inner-loading :showing="loading">
                <q-spinner-gears size="40px" />
              </q-inner-loading>
            </q-card>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { generarPDFInscripcion } from 'src/utils/inscripcion-pdf'

export default {
  name: 'InscripcionesPublic',
  data () {
    return {
      loading: false,
      error: '',
      ins: null,           // inscripción (JSON del API)
      area: null,          // ins.area
      integrantes: []      // [{nombre, ci, curso, tutor, telefono}]
    }
  },
  computed: {
    id () { return this.$route.params.id },
    fechaIns () {
      const v = this.ins?.created_at
      return v ? this.$filters.dateDmYHis(v) : ''
    }
  },
  async created () {
    await this.fetchInscripcion()
  },
  methods: {
    async fetchInscripcion () {
      this.loading = true
      this.error = ''
      try {
        // usa tu endpoint JSON; si lo tienes protegido, crea uno público (ej. /public/inscritos/:id)
        const { data } = await this.$axios.get(`/inscritos/${this.id}`)
        this.ins = data
        this.area = data?.area || null
        this.integrantes = this.normalizeIntegrantes(data)
      } catch (e) {
        this.error = e?.response?.status === 404
          ? 'Inscripción no encontrada.'
          : (e?.response?.data?.message || 'No se pudo cargar la inscripción.')
      } finally {
        this.loading = false
      }
    },
    normalizeIntegrantes (r) {
      const out = []
      for (let i = 1; i <= 10; i++) {
        if (r[`estudiante${i}`]) {
          out.push({
            nombre: r[`estudiante${i}`],
            ci: r[`ci${i}`] || '',
            curso: r[`curso${i}`] || '',
            tutor: r[`tutor${i}`] || '',
            telefono: r[`telefono${i}`] || ''
          })
        }
      }
      return out
    },
    fileUrl (path) {
      return `${this.$url}../storage/${path}`  // tu helper habitual
    },
    async descargarPdf () {
      try {
        await generarPDFInscripcion({
          inscrito: this.ins,
          area: this.area,
          integrantes: this.integrantes,
          baseUrl: window.location.origin // así el QR abre /inscripciones/:id en tu front
        })
      } catch (e) {
        this.$q.notify({ type: 'warning', message: 'No se pudo generar el PDF.' })
      }
    }
  }
}
</script>
