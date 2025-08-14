<template>
  <q-page class="q-pa-md bg-grey-3">
    <q-card flat bordered class="bg-white">
      <!-- Toolbar -->
      <q-card-section class="row items-center q-col-gutter-md">
        <div class="col-12 col-md-4">
          <q-select
            filled dense
            v-model="selectedAreaId"
            :options="areaOptions"
            emit-value map-options
            option-value="id" option-label="area"
            label="Filtrar por área"
            clearable
            @input="applyFilters"
          >
            <template #prepend><q-icon name="school" /></template>
          </q-select>
        </div>

        <div class="col-12 col-md-4">
          <q-input
            filled dense v-model="search" label="Buscar"
            debounce="300"
            clear-icon="close"
            clearable
          >
            <template #prepend><q-icon name="search" /></template>
          </q-input>
        </div>

        <div class="col-12 col-md-4 text-right">
          <q-btn color="primary" outline icon="file_download" label="Exportar CSV" class="q-mr-sm"
                 @click="exportCsv" :disable="!rowsFiltered.length" />
          <q-btn color="secondary" outline icon="print" label="Imprimir"
                 @click="printTable" :disable="!rowsFiltered.length" />
        </div>
      </q-card-section>

      <q-separator />

      <!-- Tabla -->
      <q-card-section>
<!--        <q-table-->
<!--          flat bordered-->
<!--          :data="rowsFiltered"-->
<!--          :columns="columns"-->
<!--          row-key="id"-->
<!--          :loading="loading"-->
<!--          :filter="search"-->
<!--          :rows-per-page-options="[0]"-->
<!--        :pagination.sync="pagination"-->
<!--        no-data-label="Sin registros"-->
<!--        no-results-label="Sin resultados"-->
<!--        >-->
<!--        &lt;!&ndash; celda área &ndash;&gt;-->
<!--        <template v-slot:body-cell-area="props">-->
<!--          <q-td :props="props">-->
<!--            <q-chip color="primary" text-color="white" dense square>-->
<!--              {{ props.row.area?.area || '-' }}-->
<!--            </q-chip>-->
<!--          </q-td>-->
<!--        </template>-->

<!--        &lt;!&ndash; celda integrantes &ndash;&gt;-->
<!--        <template v-slot:body-cell-integrantes="props">-->
<!--          <q-td :props="props">-->
<!--            <q-btn dense round flat icon="groups" :label="props.row._integrantesCount"-->
<!--                   class="text-primary">-->
<!--              <q-menu anchor="bottom middle" self="top middle">-->
<!--                <q-list dense style="min-width:260px; max-height:300px" separator>-->
<!--                  <q-item-label header>Integrantes</q-item-label>-->
<!--                  <q-item v-for="(it, i) in props.row._integrantes" :key="i">-->
<!--                    <q-item-section>-->
<!--                      <div class="text-weight-medium">{{ it.nombre || '(sin nombre)' }}</div>-->
<!--                      <div class="text-caption text-grey-7">-->
<!--                        CI: {{ it.ci || '—' }} · Curso: {{ it.curso || '—' }}-->
<!--                      </div>-->
<!--                    </q-item-section>-->
<!--                  </q-item>-->
<!--                </q-list>-->
<!--              </q-menu>-->
<!--            </q-btn>-->
<!--          </q-td>-->
<!--        </template>-->

<!--        &lt;!&ndash; celda comprobante &ndash;&gt;-->
<!--        <template v-slot:body-cell-pago="props">-->
<!--          <q-td :props="props">-->
<!--            <q-btn-->
<!--              v-if="props.row.pago1"-->
<!--              dense outline color="teal" icon="attach_file" label="Ver"-->
<!--              :href="fileUrl(props.row.pago1)" target="_blank"-->
<!--            />-->
<!--            <span v-else class="text-grey">—</span>-->
<!--          </q-td>-->
<!--        </template>-->

<!--        &lt;!&ndash; celda acciones &ndash;&gt;-->
<!--        <template v-slot:body-cell-actions="props">-->
<!--          <q-td :props="props" class="text-right">-->
<!--            <q-btn dense flat round icon="edit" color="primary" @click="openEdit(props.row)">-->
<!--              <q-tooltip>Editar</q-tooltip>-->
<!--            </q-btn>-->
<!--            <q-btn dense flat round icon="delete" color="negative" @click="confirmDelete(props.row)">-->
<!--              <q-tooltip>Eliminar</q-tooltip>-->
<!--            </q-btn>-->
<!--          </q-td>-->
<!--        </template>-->
<!--        </q-table>-->
        <q-markup-table dense wrap-cells>
          <thead>
          <tr class="bg-primary text-white">
            <th>Opciones</th>
<!--            <th>ID</th>-->
            <th>Grupo</th>
            <th>Área</th>
            <th>Integrantes</th>
            <th>Fecha</th>
            <th>Comprobante</th>
<!--            <th></th>-->
          </tr>
          </thead>
          <tbody>
          <tr v-for="(row,i) in rowsFiltered" :key="row.id">
            <td class="text-right">
              <q-btn-dropdown dense color="primary" :label="`Acciones ${i +1}`" no-caps size="10px">
                <q-list>
                  <q-item clickable @click="openEdit(row)" v-close-popup>
                    <q-item-section avatar>
                      <q-icon name="edit" />
                    </q-item-section>
                    <q-item-section>Editar</q-item-section>
                  </q-item>
                  <q-item clickable @click="confirmDelete(row)" v-close-popup>
                    <q-item-section avatar>
                      <q-icon name="delete" />
                    </q-item-section>
                    <q-item-section>Eliminar</q-item-section>
                  </q-item>
<!--                  imprimir pdf-->
                  <q-item clickable @click="imprimirConstancia(row)" v-close-popup>
                    <q-item-section avatar><q-icon name="print" /></q-item-section>
                    <q-item-section>Imprimir Constancia</q-item-section>
                  </q-item>
                </q-list>
              </q-btn-dropdown>
            </td>
<!--            <td>{{ row.id }}</td>-->
            <td>{{ row.grupo_nombre || '-' }}</td>
            <td>{{ row.area?.area || '-' }}</td>
            <td class="text-center">
              <q-btn dense round flat icon="groups" :label="row._integrantesCount"
                     class="text-primary">
                <q-menu anchor="bottom middle" self="top middle">
                  <q-list dense style="min-width:260px; max-height:300px" separator>
                    <q-item-label header>Integrantes</q-item-label>
                    <q-item v-for="(it, i) in row._integrantes" :key="i">
                      <q-item-section>
                        <div class="text-weight-medium">{{ it.nombre || '(sin nombre)' }}</div>
                        <div class="text-caption text-grey-7">
                          CI: {{ it.ci || '—' }} · Curso: {{ it.curso || '—' }}
                        </div>
                      </q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
            </td>
            <td>{{ $filters.dateDmYHis(row.created_at) }}</td>
            <td class="text-center">
              <q-btn v-if="row.pago1" dense outline color="teal" icon="attach_file" label="Ver"
                     :href="fileUrl(row.pago1)" target="_blank" />
              <span v-else class="text-grey">—</span>
            </td>
          </tr>
          </tbody>
          <tfoot>
          <tr>
            <td colspan="2" class="text-right">Total:</td>
            <td class="text-center">{{ rowsFiltered.reduce((sum, r) => sum + r._integrantesCount, 0) }}</td>
            <td colspan="2"></td>
            <td class="text-right">
              <q-btn dense flat icon="file_download" label="Exportar CSV" class="q-mr-sm"
                     @click="exportCsv" :disable="!rowsFiltered.length" />
              <q-btn dense flat icon="print" label="Imprimir"
                     @click="printTable" :disable="!rowsFiltered.length" />
            </td>
          </tr>
          </tfoot>
        </q-markup-table>
      </q-card-section>

      <q-inner-loading :showing="loading">
        <q-spinner-gears size="40px"/>
      </q-inner-loading>
    </q-card>

    <!-- Dialog editar -->
    <q-dialog v-model="editDialog.show" persistent>
      <q-card style="max-width: 900px; width: 100%;">
        <q-card-section class="row items-center">
          <q-icon name="edit" class="q-mr-sm text-primary" />
          <div class="text-h6">Editar inscripción #{{ editDialog.form.id }}</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-separator/>

        <q-card-section>
          <q-form ref="editFormRef" @submit.prevent="saveEdit">
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-6">
                <q-select
                  filled dense
                  v-model="editDialog.form.area_id"
                  :options="areaOptions"
                  emit-value map-options option-value="id" option-label="area"
                  label="Área"
                  :rules="[v => !!v || 'El área es obligatoria']"
                >
                  <template #prepend><q-icon name="school" /></template>
                </q-select>
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  filled dense v-model="editDialog.form.grupo_nombre"
                  label="Nombre del grupo"
                >
                  <template #prepend><q-icon name="badge" /></template>
                </q-input>
              </div>

              <div class="col-12">
                <q-file
                  v-model="editDialog.form.pagoFile"
                  label="Reemplazar comprobante (png/jpg/pdf, máx. 5MB)"
                  filled dense clearable :counter="true"
                  accept=".png,.jpg,.jpeg,.pdf"
                >
                  <template #prepend><q-icon name="attach_file" /></template>
                </q-file>
                <div v-if="editDialog.form.pago1" class="q-mt-xs">
                  <q-btn dense outline color="teal" icon="attach_file" label="Ver actual"
                         :href="fileUrl(editDialog.form.pago1)" target="_blank" />
                </div>
              </div>

              <div class="col-12">
                <q-banner class="bg-grey-1">
                  <template #avatar><q-icon name="groups" color="primary"/></template>
                  Edita los integrantes (máximo 10).
                </q-banner>
              </div>

              <div class="col-12">
                <div class="row items-center q-mb-sm">
                  <q-space />
                  <q-btn dense outline color="primary" icon="person_add" label="Agregar"
                         @click="addIntegranteEdit" :disable="editDialog.form.integrantes.length >= 10"/>
                </div>

                <q-markup-table flat bordered>
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>CI</th>
                    <th>Curso</th>
                    <th>Tutor</th>
                    <th>Teléfono</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(al, idx) in editDialog.form.integrantes" :key="idx">
                    <td class="text-grey-7">{{ idx + 1 }}</td>
                    <td style="min-width:200px">
                      <q-input dense filled v-model="al.nombre" :rules="[req]" />
                    </td>
                    <td style="min-width:140px">
                      <q-input dense filled v-model="al.ci" :rules="[req]" />
                    </td>
                    <td style="min-width:160px">
                      <q-select dense filled v-model="al.curso"
                                :options="allowedCoursesForEdit"
                                option-value="label" option-label="label"
                                emit-value map-options
                                :rules="[req]" />
                    </td>
                    <td><q-input dense filled v-model="al.tutor" /></td>
                    <td><q-input dense filled v-model="al.telefono" /></td>
                    <td class="text-right">
                      <q-btn flat round dense icon="delete" color="negative"
                             @click="editDialog.form.integrantes.splice(idx,1)" />
                    </td>
                  </tr>
                  </tbody>
                </q-markup-table>
              </div>
            </div>

            <div class="row q-mt-md q-gutter-sm">
              <q-btn label="Guardar" type="submit" color="primary" :loading="editDialog.saving" />
              <q-btn label="Cancelar" flat color="grey-7" v-close-popup :disable="editDialog.saving" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {generarPDFInscripcion} from "src/utils/inscripcion-pdf.js";

export default {
  name: 'Inscritos',
  data () {
    return {
      loading: false,
      rows: [],
      areas: [],
      selectedAreaId: null,
      search: '',
      pagination: { page: 1, rowsPerPage: 0, sortBy: 'id', descending: true },

      editDialog: {
        show: false,
        saving: false,
        form: {
          id: null,
          area_id: null,
          grupo_nombre: '',
          pago1: null,       // ruta existente
          pagoFile: null,    // archivo nuevo (opcional)
          integrantes: []    // [{nombre, ci, curso, tutor, telefono}]
        }
      }
    }
  },
  computed: {
    areaOptions () {
      return this.areas
    },
    rowsFiltered () {
      let list = this.rows
      if (this.selectedAreaId) {
        list = list.filter(r => r.area_id === this.selectedAreaId)
      }
      // Búsqueda simple por texto en varias columnas
      const q = (this.search || '').toString().trim().toLowerCase()
      if (!q) return list
      return list.filter(r => {
        const txt = [
          r.id,
          r.grupo_nombre,
          r.area?.area,
          (r._integrantes || []).map(i => `${i.nombre} ${i.ci} ${i.curso}`).join(' ')
        ].join(' ').toLowerCase()
        return txt.indexOf(q) !== -1
      })
    },
    columns () {
      return [
        { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
        { name: 'grupo', label: 'Grupo', field: 'grupo_nombre', align: 'left', sortable: true },
        { name: 'area', label: 'Área', field: 'area', align: 'left' },
        { name: 'integrantes', label: 'Integrantes', field: '_integrantesCount', align: 'center' },
        { name: 'fecha', label: 'Fecha', field: 'created_at', align: 'left', sortable: true,
          format: v => this.$filters?.dateDmYHis ? this.$filters.dateDmYHis(v) : v
        },
        { name: 'pago', label: 'Comprobante', field: 'pago1', align: 'center' },
        { name: 'actions', label: '', field: '__a__', align: 'right' }
      ]
    },
    selectedAreaForEdit () {
      const id = this.editDialog.form.area_id
      return this.areas.find(a => a.id === id) || null
    },
    allowedCoursesForEdit () {
      const arr = []
      if (!this.selectedAreaForEdit) return arr
      for (let i = 1; i <= 6; i++) {
        const v = this.selectedAreaForEdit[`curso${i}`]
        if (v) arr.push({ label: v })
      }
      return arr
    },
    req () {
      return v => !!v || 'Obligatorio'
    }
  },
  mounted () {
    this.fetchAll()
  },
  methods: {
    rowsAsAlumnos (list) {
      const out = []
      ;(list || []).forEach(r => {
        const fecha = this.$filters?.dateDmYHis ? this.$filters.dateDmYHis(r.created_at) : (r.created_at || '')
        const area = (r.area && r.area.area) ? r.area.area : ''
        const grupo = r.grupo_nombre || ''
        const comp = r.pago1 ? this.fileUrl(r.pago1) : ''

        ;(r._integrantes || []).forEach((i, idx) => {
          out.push({
            id: r.id,                 // id de la inscripción
            nro: idx + 1,             // nro del integrante dentro del grupo
            area,
            grupo,
            alumno: i.nombre || '',
            ci: i.ci || '',
            curso: i.curso || '',
            tutor: i.tutor || '',
            telefono: i.telefono || '',
            fecha,
            comprobante: comp
          })
        })
      })
      return out
    },
    async fetchAll () {
      this.loading = true
      try {
        const [areasRes, insRes] = await Promise.all([
          this.$axios.get('/areas'),
          this.$axios.get('/inscritos')
        ])
        this.areas = areasRes.data || []
        const rows = (insRes.data || []).map(r => this.normalizeRow(r))
        this.rows = rows
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudieron cargar los datos' })
      } finally {
        this.loading = false
      }
    },
    applyFilters () {
      /* intencionalmente vacío; usamos computed rowsFiltered */
    },
    normalizeRow (r) {
      // construir arreglo de integrantes desde columnas estudiante*, ci*, curso*, tutor*, telefono*
      const integrantes = []
      for (let i = 1; i <= 10; i++) {
        if (r[`estudiante${i}`]) {
          integrantes.push({
            nombre: r[`estudiante${i}`],
            ci: r[`ci${i}`] || '',
            curso: r[`curso${i}`] || '',
            tutor: r[`tutor${i}`] || '',
            telefono: r[`telefono${i}`] || ''
          })
        }
      }
      return {
        ...r,
        _integrantes: integrantes,
        _integrantesCount: integrantes.length
      }
    },
    fileUrl (path) {
      // Tu backend sirve storage en /storage
      return `${this.$url}../storage/${path}`
    },
    openEdit (row) {
      const r = this.normalizeRow(row)
      this.editDialog.form = {
        id: r.id,
        area_id: r.area_id,
        grupo_nombre: r.grupo_nombre || '',
        pago1: r.pago1 || null,
        pagoFile: null,
        integrantes: JSON.parse(JSON.stringify(r._integrantes || []))
      }
      this.editDialog.show = true
    },
    addIntegranteEdit () {
      this.editDialog.form.integrantes.push({ nombre: '', ci: '', curso: '', tutor: '', telefono: '' })
    },
    async saveEdit () {
      const ok = await this.$refs.editFormRef.validate()
      if (!ok) return

      this.editDialog.saving = true
      try {
        const fd = new FormData()
        fd.append('area_id', this.editDialog.form.area_id)
        if (this.editDialog.form.grupo_nombre) {
          fd.append('grupo_nombre', this.editDialog.form.grupo_nombre)
        }
        if (this.editDialog.form.pagoFile) {
          fd.append('pago1', this.editDialog.form.pagoFile)
        }
        // integrantes[] como multipart array
        this.editDialog.form.integrantes.forEach((i, idx) => {
          fd.append(`integrantes[${idx}][nombre]`, i.nombre)
          fd.append(`integrantes[${idx}][ci]`, i.ci)
          fd.append(`integrantes[${idx}][curso]`, i.curso)
          fd.append(`integrantes[${idx}][tutor]`, i.tutor || '')
          fd.append(`integrantes[${idx}][telefono]`, i.telefono || '')
        })

        // Spoofing: enviar POST con _method=PUT en el BODY (Laravel lo reconoce)
        // fd.append('_method', 'POST')

        await this.$axios.post(`/inscritos/${this.editDialog.form.id}`, fd, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })

        this.$q.notify({ type: 'positive', message: 'Inscripción actualizada' })
        this.editDialog.show = false
        await this.fetchAll()
      } catch (e) {
        const msg = e?.response?.data?.message || 'No se pudo actualizar'
        this.$q.notify({ type: 'negative', message: msg })
      } finally {
        this.editDialog.saving = false
      }
    },
    async imprimirConstancia (row) {
      // normalizar por si llega sin _integrantes
      const r = this.normalizeRow(row)

      try {
        await generarPDFInscripcion({
          inscrito: {
            id: r.id,
            grupo_nombre: r.grupo_nombre,
            created_at: r.created_at,
            pago1: r.pago1
          },
          area: r.area || this.areas.find(a => a.id === r.area_id) || {},
          integrantes: r._integrantes || [],
          // el QR abrirá /inscripciones/:id en TU frontend
          baseUrl: (import.meta && import.meta.env && import.meta.env.VITE_PUBLIC_FRONT)
            ? import.meta.env.VITE_PUBLIC_FRONT
            : window.location.origin,
          assets: { left: '/images.png', right: '/logo_fni.png' }
        })
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudo generar la constancia' })
      }
    },
    confirmDelete (row) {
      this.$q.dialog({
        title: 'Eliminar',
        message: `¿Eliminar la inscripción #${row.id}? Esta acción no se puede deshacer.`,
        cancel: true,
        persistent: true,
        ok: { label: 'Eliminar', color: 'negative' },
        cancelLabel: 'Cancelar'
      }).onOk(async () => {
        try {
          await this.$axios.delete(`/inscritos/${row.id}`)
          this.$q.notify({ type: 'positive', message: 'Eliminado correctamente' })
          await this.fetchAll()
        } catch (e) {
          const msg = e?.response?.data?.message || 'No se pudo eliminar'
          this.$q.notify({ type: 'negative', message: msg })
        }
      })
    },
    exportCsv () {
      const alumnos = this.rowsAsAlumnos(this.rowsFiltered)
      if (!alumnos.length) return

      const headers = [
        'ID inscripción', 'Nº', 'Área', 'Grupo',
        'Alumno', 'CI', 'Curso', 'Tutor', 'Teléfono',
        'Fecha', 'ComprobanteURL'
      ]
      const csvRows = [headers.join(';')]

      alumnos.forEach(a => {
        const line = [
          a.id, a.nro, a.area, a.grupo,
          a.alumno, a.ci, a.curso, a.tutor, a.telefono,
          a.fecha, a.comprobante
        ].map(v => `"${String(v).replace(/"/g, '""')}"`)
        csvRows.push(line.join(';'))
      })

      const blob = new Blob([`\uFEFF${csvRows.join('\n')}`], { type: 'text/csv;charset=utf-8;' })
      const url = URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `inscritos_alumnos_${new Date().toISOString().slice(0,10)}.csv`
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      URL.revokeObjectURL(url)
    },
    printTable () {
      const alumnos = this.rowsAsAlumnos(this.rowsFiltered)
      if (!alumnos.length) return

      const rowsHtml = alumnos.map(a => `
    <tr>
      <td>${a.id}</td>
      <td>${a.nro}</td>
      <td>${a.area}</td>
      <td>${a.grupo}</td>
      <td>${a.alumno}</td>
      <td>${a.ci}</td>
      <td>${a.curso}</td>
      <td>${a.tutor}</td>
      <td>${a.telefono}</td>
      <td>${a.fecha}</td>
      <td>${a.comprobante ? `<a href="${a.comprobante}" target="_blank">ver</a>` : '—'}</td>
    </tr>
  `).join('')

      const w = window.open('', '_blank')
      w.document.write(`
    <html>
      <head>
        <title>Inscritos por alumno</title>
        <style>
          body { font-family: Arial, sans-serif; padding: 16px; color:#222; }
          h2 { margin: 0 0 12px; }
          .sub { color:#666; margin: 0 0 16px; }
          table { border-collapse: collapse; width: 100%; }
          th, td { border: 1px solid #e0e0e0; padding: 8px; font-size: 12px; }
          th { background: #1976d2; color: #fff; text-align: left; position: sticky; top: 0; }
          tr:nth-child(even){ background: #f9fbfd; }
          @media print {
            a { color: #1976d2; text-decoration: underline; }
            th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
          }
        </style>
      </head>
      <body>
        <h2>Listado de alumnos inscritos</h2>
        <div class="sub">Total alumnos: <b>${alumnos.length}</b></div>
        <table>
          <thead>
            <tr>
              <th>ID Inscripción</th>
              <th>Nº</th>
              <th>Área</th>
              <th>Grupo</th>
              <th>Alumno</th>
              <th>CI</th>
              <th>Curso</th>
              <th>Tutor</th>
              <th>Teléfono</th>
              <th>Fecha</th>
              <th>Comprobante</th>
            </tr>
          </thead>
          <tbody>${rowsHtml}</tbody>
        </table>
      </body>
    </html>
  `)
      w.document.close()
      w.focus()
      w.print()
    },
  }
}
</script>
