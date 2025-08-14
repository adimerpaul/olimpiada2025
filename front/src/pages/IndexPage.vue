<template>
  <q-page class="q-pa-md bg-grey-3">
    <!-- KPIs -->
    <div class="row q-col-gutter-md q-mb-md">
      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="kpi-card">
          <q-card-section class="row items-center">
            <q-avatar color="primary" text-color="white" icon="dashboard" />
            <div class="col q-ml-md">
              <div class="text-caption text-grey-7">Áreas</div>
              <div class="text-h6 text-weight-bold">{{ totalAreas }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="kpi-card">
          <q-card-section class="row items-center">
            <q-avatar color="indigo" text-color="white" icon="assignment" />
            <div class="col q-ml-md">
              <div class="text-caption text-grey-7">Grupos</div>
              <div class="text-h6 text-weight-bold">{{ totalGrupos }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="kpi-card">
          <q-card-section class="row items-center">
            <q-avatar color="teal" text-color="white" icon="groups" />
            <div class="col q-ml-md">
              <div class="text-caption text-grey-7">Alumnos</div>
              <div class="text-h6 text-weight-bold">{{ totalAlumnos }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="kpi-card">
          <q-card-section class="row items-center">
            <q-avatar color="orange" text-color="white" icon="schedule" />
            <div class="col q-ml-md">
              <div class="text-caption text-grey-7">Último registro</div>
              <div class="text-subtitle2 text-weight-medium">
                {{ lastCreatedAt || '—' }}
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-8">
        <q-card flat bordered>
          <q-card-section class="text-subtitle1 text-weight-bold">
            Alumnos por área
          </q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart
              type="bar"
              height="320"
              :options="areaAlumnosOptions"
              :series="areaAlumnosSeries"
            />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card flat bordered>
          <q-card-section class="text-subtitle1 text-weight-bold">
            Grupos por área
          </q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart
              type="donut"
              height="320"
              :options="areaGruposOptions"
              :series="areaGruposSeries"
            />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-8">
        <q-card flat bordered class="q-mt-md">
          <q-card-section class="text-subtitle1 text-weight-bold">
            Inscripciones por día (últimos 30)
          </q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart
              type="line"
              height="300"
              :options="insDiaOptions"
              :series="insDiaSeries"
            />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card flat bordered class="q-mt-md">
          <q-card-section class="text-subtitle1 text-weight-bold">
            Cursos más inscritos
          </q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart
              type="bar"
              height="300"
              :options="cursosOptions"
              :series="cursosSeries"
            />
          </q-card-section>
        </q-card>
      </div>
    </div>

    <q-inner-loading :showing="loading">
      <q-spinner-gears size="40px" />
    </q-inner-loading>
  </q-page>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
  name: 'IndexPage',
  components: { apexchart: VueApexCharts }, // si ya lo registraste globalmente, puedes quitar esta línea
  data () {
    return {
      loading: false,
      areas: [],
      rows: [],

      // KPI
      totalAreas: 0,
      totalGrupos: 0,
      totalAlumnos: 0,
      lastCreatedAt: '',

      // Charts
      areaAlumnosSeries: [],
      areaAlumnosOptions: {},
      areaGruposSeries: [],
      areaGruposOptions: {},
      insDiaSeries: [],
      insDiaOptions: {},
      cursosSeries: [],
      cursosOptions: {}
    }
  },
  mounted () {
    this.load()
  },
  methods: {
    async load () {
      this.loading = true
      try {
        const [areasRes, insRes] = await Promise.all([
          this.$axios.get('/areas'),
          this.$axios.get('/inscritos')
        ])
        this.areas = areasRes.data || []
        this.rows = (insRes.data || []).map(r => this.normalizeRow(r))

        this.buildKpis()
        this.buildCharts()
      } catch (e) {
        this.$q.notify({ type: 'negative', message: 'No se pudo cargar el dashboard' })
      } finally {
        this.loading = false
      }
    },

    normalizeRow (r) {
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

    buildKpis () {
      this.totalAreas = this.areas.length
      this.totalGrupos = this.rows.length
      this.totalAlumnos = this.rows.reduce((a, r) => a + (r._integrantesCount || 0), 0)

      const last = this.rows
        .map(r => r.created_at)
        .filter(Boolean)
        .sort()
        .slice(-1)[0]
      this.lastCreatedAt = last
        ? (this.$filters?.dateDmYHis ? this.$filters.dateDmYHis(last) : new Date(last).toLocaleString())
        : ''
    },

    buildCharts () {
      // --- Alumnos por área / Grupos por área
      const alumnosPorArea = {}
      const gruposPorArea = {}
      this.rows.forEach(r => {
        const area = (r.area && r.area.area) ? r.area.area : 'Sin área'
        alumnosPorArea[area] = (alumnosPorArea[area] || 0) + r._integrantesCount
        gruposPorArea[area] = (gruposPorArea[area] || 0) + 1
      })
      const areaLabels = Object.keys(alumnosPorArea)
      const alumnosData = areaLabels.map(k => alumnosPorArea[k])
      const gruposData = areaLabels.map(k => gruposPorArea[k])

      this.areaAlumnosSeries = [{ name: 'Alumnos', data: alumnosData }]
      this.areaAlumnosOptions = {
        chart: { toolbar: { show: false } },
        xaxis: { categories: areaLabels, labels: { rotateAlways: false, trim: true } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'light' }
      }

      this.areaGruposSeries = gruposData
      this.areaGruposOptions = {
        chart: { toolbar: { show: false } },
        labels: areaLabels,
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, formatter: (v) => `${v.toFixed(1)}%` },
        tooltip: { theme: 'light' }
      }

      // --- Inscripciones por día (últimos 30)
      const byDay = {}
      this.rows.forEach(r => {
        const d = new Date(r.created_at)
        if (!isNaN(d * 1)) {
          const key = d.toISOString().slice(0, 10)
          byDay[key] = (byDay[key] || 0) + r._integrantesCount // suma alumnos por día
        }
      })
      const days = this.lastNDays(30)
      const dayCats = days.map(d => d.key)
      const dayData = dayCats.map(k => byDay[k] || 0)

      this.insDiaSeries = [{ name: 'Alumnos', data: dayData }]
      this.insDiaOptions = {
        chart: { toolbar: { show: false } },
        stroke: { width: 3, curve: 'smooth' },
        xaxis: { categories: dayCats },
        dataLabels: { enabled: false },
        tooltip: { theme: 'light' }
      }

      // --- Cursos más inscritos (Top 10)
      const cursos = {}
      this.rows.forEach(r => {
        (r._integrantes || []).forEach(i => {
          if (!i.curso) return
          cursos[i.curso] = (cursos[i.curso] || 0) + 1
        })
      })
      const cursoPairs = Object.entries(cursos).sort((a, b) => b[1] - a[1]).slice(0, 10)
      const cursoLabels = cursoPairs.map(p => p[0])
      const cursoData = cursoPairs.map(p => p[1])

      this.cursosSeries = [{ name: 'Alumnos', data: cursoData }]
      this.cursosOptions = {
        chart: { toolbar: { show: false } },
        plotOptions: { bar: { horizontal: true } },
        xaxis: { categories: cursoLabels },
        dataLabels: { enabled: false },
        tooltip: { theme: 'light' }
      }
    },

    lastNDays (n) {
      const out = []
      const today = new Date()
      for (let i = n - 1; i >= 0; i--) {
        const d = new Date(today)
        d.setDate(today.getDate() - i)
        const key = d.toISOString().slice(0, 10)
        out.push({ key, d })
      }
      return out
    }
  }
}
</script>

<style scoped>
.kpi-card {
  border-radius: 14px;
  background: #fff;
}
</style>
