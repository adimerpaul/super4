<template>
  <q-layout view="hHh Lpr lFf">
    <q-header>
      <div class="row">
        <div class="q-page-container col">
          <q-toolbar class="bg-black">
            <q-item clickable v-ripple>
              <q-item-section side>
                <q-avatar rounded size="48px">
                  <img src="logo.png" />
<!--                  <q-badge floating color="teal">new</q-badge>-->
                </q-avatar>
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  <div style="line-height: 85%;font-size: 1.5em" class="text-bold">
                    Super<br>
                    Hamburgesas
                  </div>
                </q-item-label>
<!--                <q-item-label caption>2 new messages</q-item-label>-->
              </q-item-section>
<!--              <q-item-section side>-->
<!--                3 min ago-->
<!--              </q-item-section>-->
            </q-item>
            <q-space />
              <q-btn v-if="$q.screen.lt.md" flat round
                     icon="menu" class="text-bold q-mr-sm" @click="toggleLeftDrawer" />
<!--            <template>-->
<!--              <q-btn stretch-->
<!--                     v-for="e in essentialLinks" :key="e.title" :label="e.title"/>-->
<!--            </template>-->
            <q-item >
              <q-item-section side v-for="e in essentialLinks" :key="e.title">
                <q-btn flat class="text-bold q-mr-sm text-white" :label="e.title" :to="e.link" />
              </q-item-section>
              <q-item-section side>
                <q-btn flat round color="white" icon="o_shopping_bag" class="text-bold q-mr-sm"
                       to="/pedidos">
                  <q-badge floating color="red" v-if="$store.pedidos.length > 0">
                    {{cantidadPedidos}}
                  </q-badge>
                </q-btn>
              </q-item-section>
              <q-item-section>
                <q-item-label>
                  <q-btn push color="red-10" label="Ingresar" @click="ingresar" no-caps />
                </q-item-label>
              </q-item-section>
              <q-item-section side>
              </q-item-section>
            </q-item>
          </q-toolbar>
        </div>
      </div>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      bordered
    >
      <q-list>
<!--        <q-item-label header>Essential Links</q-item-label>-->
        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
    <q-dialog v-model="loginDialig" persistent>
      <q-card bordered class="bg-primary text-white" style="width: 600px; max-width: 80vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Ingresar</div>
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
                       class="full-width q-mb-xs" icon="fa-brands fa-google" rounded />
                <q-btn  label="Continuar con correo" no-caps
                        @click="typeLogin = 'email'"
                        class="full-width q-mb-xs" icon="o_email" rounded outline />
              </div>
            </template>
            <template v-if="typeLogin === 'email'">
              <div class="text-subtitle2">Ingrese su correo y contraseña</div>
              <q-card class="q-mt-md">
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
                  <q-btn color="primary" label="Ingresar" no-caps
                         class="full-width q-mt-xs" icon="o_login" rounded />
                  <q-btn outline color="primary" label="Registrarse" no-caps
                         @click="typeLogin = 'register'"
                         class="full-width q-mt-xs" icon="o_add" rounded />
                  <q-btn flat color="primary" label="Atrás" no-caps
                         @click="typeLogin = 'login'"
                         class="full-width q-mt-xs" icon="o_arrow_back" rounded />
                </q-card-section>
              </q-card>
            </template>
            <template v-if="typeLogin === 'register'">
              <div class="text-subtitle2">Registrate</div>
              <q-card class="q-mt-md">
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
                  <q-input dense v-model="user.repeatPassword" label="Repetir contraseña"
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
                         class="full-width q-mt-xs" icon="o_add" rounded />
                  <q-btn flat color="primary" label="Atrás" no-caps
                         @click="typeLogin = 'login'"
                         class="full-width q-mt-xs" icon="o_arrow_back" rounded />
                </q-card-section>
              </q-card>
            </template>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>

  </q-layout>
</template>

<script lang="ts">
import EssentialLink from 'components/EssentialLink.vue';

const linksList = [
  {
    title: 'Principal',
    link: '/',
  },
  {
    title: 'Menu',
    link: '/menu',
  },
  {
    title: 'Contacto',
    link: '/contacto',
  },
  {
    title: 'Ubicación',
    link: '/ubicacion',
  },
];

export default {
  name: 'MainLayout',

  components: {
    EssentialLink,
  },
  data() {
    return {
      visible: false,
      tab: 'login',
      user: {
        email: '',
        password: '',
        repeatPassword: '',
        name: '',
      },
      essentialLinks: linksList,
      leftDrawerOpen: false,
      loginDialig: false,
      typeLogin: 'login',
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
    ingresar() {
      this.loginDialig = true;
      this.typeLogin = 'login';
    },
  },
  computed: {
    cantidadPedidos() {
      let cantidad = 0;
      this.$store.pedidos.forEach((pedido) => {
        cantidad += pedido.cantidad;
      });
      return cantidad;
    },
  },
};
</script>
