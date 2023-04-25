<template>
  <q-card bordered class="bg-primary text-white" style="width: 600px; max-width: 80vw;">
    <q-card-section class="row items-center q-pb-none">
      <div class="text-h6" @click="closeDialog">Ingresar</div>
      <q-space />
      <q-btn icon="close" flat round dense v-close-popup />
    </q-card-section>
    <q-card-section class="q-pt-none">
      <div class="text-center">
        <q-avatar size="80px" class="">
          <q-img src="logo.png" />
        </q-avatar>
        <template v-if="typeLogin === 'login'">
          <div class="text-subtitle2">Elijá una opción para ingresar</div>
          <div class="row justify-center q-mt-md">
            <q-btn color="blue-8" label="Ingresar con Google" no-caps
                   @click="useAuthProvider('google', null)"
                   class="full-width q-mb-xs" icon="fa-brands fa-google" rounded />
            <q-btn  label="Continuar con correo" no-caps
                    @click="typeLogin = 'email'"
                    class="full-width q-mb-xs" icon="o_email" rounded outline />
          </div>
        </template>
        <template v-if="typeLogin === 'email'">
          <div class="text-subtitle2">Ingrese su correo y contraseña</div>
          <q-card class="q-mt-md">
            <q-form @submit.prevent="login">
            <q-card-section>
              <q-input dense ou v-model="user.email" label="Correo"  type="email"
                       :rules="[val => val && val.length > 0 || 'El correo es requerido']" />
              <q-input dense v-model="user.password" label="Contraseña"
                       :type="visible ? 'text' : 'password'"
                       :rules="[val => val && val.length > 0 || 'La contraseña es requerida']">
                <template v-slot:append>
                  <q-icon @click="visible=!visible" class="cursor-pointer"
                          :name="visible ? 'visibility_off' : 'visibility'">
                    <q-tooltip>Mostrar contraseña</q-tooltip>
                  </q-icon>
                </template>
              </q-input>
              <q-btn color="primary" label="Ingresar" no-caps @click="login"
                     class="full-width q-mt-xs" icon="o_login" rounded />
              <q-btn outline color="primary" label="Registrarse" no-caps
                     @click="typeLogin = 'register'"
                     class="full-width q-mt-xs" icon="o_add" rounded />
              <q-btn flat color="primary" label="Atrás" no-caps
                     @click="typeLogin = 'login'"
                     class="full-width q-mt-xs" icon="o_arrow_back" rounded />
            </q-card-section>
            </q-form>
          </q-card>
        </template>
        <template v-if="typeLogin === 'register'">
          <div class="text-subtitle2">Registrate</div>
          <q-card class="q-mt-md">
            <q-form @submit.prevent="register">
              <q-card-section>
                <q-input dense v-model="user.name" label="Nombre"
                         :rules="[val => val && val.length > 0 || 'El nombre es requerido']" />
                <q-input dense v-model="user.email" label="Correo"  type="email"
                         :rules="[val => val && val.length > 0 || 'El correo es requerido']" />
                <q-input dense v-model="user.password" label="Contraseña"
                         :type="visible ? 'text' : 'password'"
                         :rules="[val => val && val.length > 0 || 'La contraseña es requerida']">
                  <template v-slot:append>
                    <q-icon @click="visible=!visible" class="cursor-pointer"
                            :name="visible ? 'visibility_off' : 'visibility'">
                      <q-tooltip>Mostrar contraseña</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
                <q-input dense v-model="user.password_confirmation" label="Repetir contraseña"
                         :type="visible ? 'text' : 'password'"
                         :rules="[
                           val => val && val.length > 0 || 'La contraseña es requerida',
                           val => val === user.password || 'Las contraseñas no coinciden'
                           ]">
                  <template v-slot:append>
                    <q-icon @click="visible=!visible" class="cursor-pointer"
                            :name="visible ? 'visibility_off' : 'visibility'">
                      <q-tooltip>Mostrar contraseña</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
                <q-btn color="primary" label="Registrarse" no-caps
                       type="submit"
                       class="full-width q-mt-xs" icon="o_add" rounded />
                <q-btn flat color="primary" label="Atrás" no-caps
                       @click="typeLogin = 'login'"
                       class="full-width q-mt-xs" icon="o_arrow_back" rounded />
              </q-card-section>
            </q-form>
          </q-card>
        </template>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import { Providers } from 'universal-social-auth';

export default {
  name: 'LoginComponent',
  data() {
    return {
      visible: false,
      typeLogin: 'login',
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
    };
  },
  methods: {
    login() {
      this.$q.loading.show();
      this.$axios.post('login', this.user).then((res) => {
        this.$q.loading.hide();
        this.$q.notify({
          message: `Bienvenido ${res.data.user.name}`,
          color: 'positive',
          icon: 'check_circle',
          position: 'top',
        });
        this.$store.user = res.data.user;
        this.$store.isLoggedIn = true;
        this.$axios.defaults.headers.common.Authorization = `Bearer ${res.data.token}`;
        this.$emit('closeDialog', res.data.token);
        // this.$router.push('/');
      }).catch((err) => {
        this.$q.loading.hide();
        this.$q.notify({
          message: err.response.data.message,
          color: 'negative',
          icon: 'error',
          position: 'top',
        });
      });
    },
    closeDialog() {
      this.$emit('closeDialog');
    },
    useAuthProvider(provider, proData) {
      const pro = proData;
      const ProData = pro || Providers[provider];
      this.$q.loading.show();
      this.$Oauth.authenticate(provider, ProData).then((response) => {
        if (response.code) {
          this.$axios.post(`sociallogin/${provider}`, response).then((res) => {
            // console.log(res.data);
            this.$q.loading.hide();
            this.$q.notify({
              message: `Bienvenido ${res.data.user.name}`,
              color: 'positive',
              icon: 'check_circle',
              position: 'top',
            });
            this.$emit('closeDialog', res.data.token);
            // this.$router.push('/');
            this.$store.user = res.data.user;
            this.$store.isLoggedIn = true;
            this.$axios.defaults.headers.common.Authorization = `Bearer ${res.data.token}`;
            localStorage.setItem('tokenSuper', res.data.token);
            this.loginDialog = false;
          }).catch((err) => {
            this.$q.notify({
              message: err.response.data.message,
              color: 'negative',
              icon: 'error',
              position: 'top',
            });
          }).finally(() => {
            this.$q.loading.hide();
          });
        } else {
          this.$q.loading.hide();
        }
      }).catch((err) => {
        this.$q.notify({
          message: err.response.data.message,
          color: 'negative',
          icon: 'error',
          position: 'top',
        });
        // console.log(err);
      }).finally(() => {
        // this.$q.loading.hide()
      });
    },
    register() {
      this.$q.loading.show();
      this.$axios.post('register', this.user).then((res) => {
        this.$q.notify({
          message: 'Usuario creado correctamente',
          color: 'positive',
          icon: 'check_circle',
          position: 'top',
        });
        // this.$router.push('/')
        this.$store.user = res.data.user;
        this.$store.isLoggedIn = true;
        this.$axios.defaults.headers.common.Authorization = `Bearer ${res.data.token}`;
        localStorage.setItem('tokenSuper', res.data.token);
        this.loginDialog = false;
      })
        .catch((error) => {
          // console.log(error);
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'error',
            position: 'top',
          });
        })
        .finally(() => {
          this.$q.loading.hide();
        });
    },
  },
};
</script>

<style scoped>

</style>
