<template>
  <q-layout view="lHh Lpr lFf">
    <!-- HEADER -->
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-btn flat round dense icon="menu" @click="toggleLeftDrawer" aria-label="Menú" />

        <q-avatar square size="32px" class="q-ml-sm">
          <img src="images.png">
        </q-avatar>

        <q-toolbar-title>
          <div class="text-subtitle2 text-caption">VIII OLIMPIADA</div>
          <div class="text-weight-bold text-caption" style="line-height:1;">CIENCIA Y TECNOLOGÍA F.N.I.</div>
        </q-toolbar-title>

        <q-space />
        <!-- Usuario + menú -->
        <q-btn flat dense no-caps>
          <q-icon name="account_circle" size="24px" class="q-mr-xs" />
          <span class="ellipsis">{{ user.name || 'Usuario' }}</span>

          <q-menu transition-show="jump-down" transition-hide="jump-up">
            <q-list style="min-width: 200px">
              <q-item v-ripple clickable v-close-popup to="/perfil">
                <q-item-section avatar><q-icon name="person" /></q-item-section>
                <q-item-section>Mi perfil</q-item-section>
              </q-item>

              <q-separator />

              <q-item v-ripple clickable @click="logout">
                <q-item-section avatar><q-icon name="logout" /></q-item-section>
                <q-item-section>Cerrar sesión</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
      </q-toolbar>
    </q-header>

    <!-- DRAWER -->
    <q-drawer v-model="leftDrawerOpen" show-if-above bordered :width="240" class="bg-primary">
      <q-scroll-area style="height: 100%;">
        <q-list padding class=" text-white">
          <q-item-label header class="text-white q-pt-md q-pl-sm">Menú</q-item-label>

          <q-item clickable v-ripple to="/menu" exact
                  class="drawer-item"
                  exact-active-class="drawer-active">
            <q-item-section avatar><q-icon name="dashboard" /></q-item-section>
            <q-item-section>Panel</q-item-section>
          </q-item>

          <q-item clickable v-ripple to="/inscritos" exact
                  class="drawer-item"
                  exact-active-class="drawer-active">
            <q-item-section avatar><q-icon name="list_alt" /></q-item-section>
            <q-item-section>Inscritos</q-item-section>
            <q-item-section side v-if="inscritosCount > 0">
              <q-badge color="white" text-color="primary" :label="inscritosBadge" />
            </q-item-section>
          </q-item>

          <q-item clickable v-ripple to="/areas" exact
                  class="drawer-item"
                  exact-active-class="drawer-active">
            <q-item-section avatar><q-icon name="school" /></q-item-section>
            <q-item-section>Áreas</q-item-section>
          </q-item>

          <q-separator spaced color="white" class="q-mx-md" />

          <q-item clickable v-ripple class="drawer-item" @click="logout">
            <q-item-section avatar><q-icon name="logout" /></q-item-section>
            <q-item-section>Cerrar sesión</q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <!-- CONTENT -->
    <q-page-container>
      <router-view />
    </q-page-container>

    <!-- FOOTER -->
    <q-footer bordered class="bg-grey-1 text-grey-7">
      <div class="row items-center q-px-md q-py-xs">
        <div class="col">© {{ year }} F.N.I.</div>
        <q-space />
        <div class="col-auto">Quasar v{{ $q.version }}</div>
      </div>
    </q-footer>
  </q-layout>
</template>

<script>
export default {
  name: 'MainLayout',
  data () {
    return {
      leftDrawerOpen: false,
      inscritosCount: 0
    }
  },
  computed: {
    user () {
      return this.$store && this.$store.user ? this.$store.user : {}
    },
    year () {
      return new Date().getFullYear()
    },
    inscritosBadge () {
      return this.inscritosCount > 99 ? '99+' : this.inscritosCount
    }
  },
  created () {
    this.fetchInscritosCount()
  },
  methods: {
    toggleLeftDrawer () {
      this.leftDrawerOpen = !this.leftDrawerOpen
    },
    async fetchInscritosCount () {
      try {
        const { data } = await this.$axios.get('/inscritos/count')
        this.inscritosCount = data && typeof data.count === 'number' ? data.count : 0
      } catch (e) {
        this.inscritosCount = 0
      }
    },
    async logout () {
      try { await this.$axios.post('/logout') } catch (e) {}
      localStorage.removeItem('tokenOlim')
      if (this.$axios && this.$axios.defaults?.headers?.common) {
        delete this.$axios.defaults.headers.common.Authorization
      }
      if (this.$store) {
        this.$store.isLogged = false
        this.$store.user = {}
        this.$store.permissions = []
      }
      this.$q.notify({ type: 'info', message: 'Sesión cerrada' })
      this.$router.replace('/login')
    }
  }
}
</script>

<style scoped>
/* Drawer fondo primary con degradado */
.drawer-list {
  background: linear-gradient(180deg, #1976D2 0%, #1565C0 100%); /* primary -> blue-8 */
  min-height: 100%;
}

/* Ítems del drawer */
.drawer-item {
  color: rgba(255, 255, 255, 0.9);
  margin: 4px 10px;
  border-radius: 10px;
  transition: background .15s ease, transform .05s ease;
}
.drawer-item:hover {
  background: rgba(255, 255, 255, 0.12);
}
.drawer-item .q-icon {
  opacity: .95;
}

/* Ítem activo exacto */
.drawer-active {
  background: rgba(255, 255, 255, 0.22) !important;
  color: #fff !important;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,0.25);
}
.drawer-active .q-icon,
.drawer-active .q-item__label {
  color: #fff !important;
}

/* texto que no se desborda en header */
.ellipsis {
  max-width: 160px;
  display: inline-block;
  vertical-align: middle;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
