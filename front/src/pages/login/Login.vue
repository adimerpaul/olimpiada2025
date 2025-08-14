<template>
  <q-layout view="lHh Lpr lFf">
  <q-page-container>
    <q-page class="flex flex-center bg-grey-3">
      <q-card bordered flat class="q-pa-lg" style="width: 100%; max-width: 420px;">
        <div class="text-center q-mb-md">
          <q-img src="images.png" style="width:56px" class="q-mb-sm" />
          <div class="text-h6 text-weight-bold">Iniciar sesión</div>
          <div class="text-caption text-grey-7">VIII Olimpiada F.N.I.</div>
        </div>

        <q-form @submit.prevent="onLogin" ref="formRef">
          <q-input
            v-model="email" type="text" filled dense label="Usuario"
            :rules="[v => !!v || 'El correo es obligatorio']">
            <template #prepend><q-icon name="mail" /></template>
          </q-input>

          <q-input
            v-model="password" :type="showPwd ? 'text' : 'password'"
            filled dense label="Contraseña" class="q-mt-sm"
            :rules="[v => !!v || 'La contraseña es obligatoria']">
            <template #prepend><q-icon name="lock" /></template>
            <template #append>
              <q-icon :name="showPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                      @click="showPwd = !showPwd" />
            </template>
          </q-input>

          <div class="row items-center q-mt-sm">
            <q-checkbox v-model="remember" label="Recordarme" class="text-grey-7" />
            <q-space />
            <q-btn :loading="loading" type="submit" color="primary" label="Entrar" unelevated />
          </div>
        </q-form>

        <q-banner v-if="error" class="q-mt-md bg-red-1 text-red-10" rounded>
          <template #avatar><q-icon name="error" color="red-10" /></template>
          {{ error }}
        </q-banner>
      </q-card>
    </q-page>
  </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      email: '',
      password: '',
      showPwd: false,
      remember: true,
      loading: false,
      error: '',
      checking: false
    }
  },
  async mounted () {
    await this.redirectIfLogged()
  },
  methods: {
    async redirectIfLogged () {
      const token = localStorage.getItem('tokenOlim')
      if (!token) return

      this.checking = true
      // monta el token en axios para chequear /me
      this.$axios.defaults.headers.common.Authorization = `Bearer ${token}`
      try {
        await this.$axios.get('/me') // si responde OK, el token es válido
        this.$router.replace('/menu')
      } catch (e) {
        // token inválido: lo limpiamos y seguimos en login
        localStorage.removeItem('tokenOlim')
        delete this.$axios.defaults.headers.common.Authorization
      } finally {
        this.checking = false
      }
    },
    async onLogin () {
      this.error = ''
      const ok = await this.$refs.formRef.validate()
      if (!ok) return

      this.loading = true
      try {
        const { data } = await this.$axios.post('/login', {
          username: this.email,
          password: this.password
        })
        const token = data?.token
        const user = data?.user

        if (!token) throw new Error('Token no recibido')
        localStorage.setItem('tokenOlim', token)
        this.$store.user = user
        // refresca header Authorization en el boot
        this.$axios.defaults.headers.common.Authorization = `Bearer ${token}`
        this.$q.notify({ type: 'positive', message: 'Bienvenido 👋' })
        this.$router.push('/menu')
      } catch (e) {
        const msg = e?.response?.data?.errors?.email?.[0] ||
          e?.response?.data?.message ||
          'No se pudo iniciar sesión'
        this.error = msg
        this.$q.notify({ type: 'negative', message: msg })
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
