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
            <q-item >
              <q-item-section side v-for="e in essentialLinks" :key="e.title">
                <q-btn flat class="text-bold q-mr-sm text-white" :label="e.title" :to="e.link" />
              </q-item-section>
              <q-item-section side v-if="!$q.screen.lt.md">
                <q-btn flat round color="white" icon="o_shopping_bag" class="text-bold q-mr-sm"
                       to="/pedidos">
                  <q-badge floating color="red" v-if="$store.pedidos.length > 0">
                    {{cantidadPedidos}}
                  </q-badge>
                </q-btn>
              </q-item-section>
              <q-item-section>
                <q-item-label class="cursor-pointer">
                  <q-btn push color="red-10" label="Ingresar" @click="ingresar"
                         no-caps v-if="!$store.isLoggedIn" :loading="$store.loading" />
                </q-item-label>
              </q-item-section>
              <q-btn-dropdown outline no-caps dense v-if="$store.isLoggedIn">
                <template v-slot:label>
                  <div class="row items-center no-wrap">
                    <q-avatar v-if="$store.isLoggedIn" size="32px">
                      <img :src="`${$url}../images/${$store.user.avatar}`" />
                    </q-avatar>
                  </div>
                </template>
                <q-list>
                  <q-item clickable v-close-popup v-ripple>
                    <q-item-section avatar><q-icon name="person" /></q-item-section>
                    <q-item-section><q-item-label>Mi perfil</q-item-label></q-item-section>
                  </q-item>
                  <q-item clickable v-close-popup v-ripple>
                    <q-item-section avatar><q-icon name="o_shopping_bag" /></q-item-section>
                    <q-item-section><q-item-label>Mis pedidos</q-item-label></q-item-section>
                  </q-item>
                  <q-item clickable v-close-popup v-ripple to="/pedidos">
                    <q-item-section avatar><q-icon name="o_shopping_cart" /></q-item-section>
                    <q-item-section><q-item-label>
                      Mi carrito({{cantidadPedidos}})
                    </q-item-label></q-item-section>
                  </q-item>
                  <q-item clickable v-close-popup v-ripple @click="logout">
                    <q-item-section avatar><q-icon name="exit_to_app" /></q-item-section>
                    <q-item-section><q-item-label>Salir</q-item-label></q-item-section>
                  </q-item>
                </q-list>
              </q-btn-dropdown>
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
    <q-dialog v-model="loginDialog" persistent>
      <LoginComponent  @closeDialog="closeDialog"/>
    </q-dialog>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink.vue';
import LoginComponent from 'components/LoginComponent.vue';

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
    LoginComponent,
  },
  data() {
    return {
      visible: false,
      essentialLinks: linksList,
      leftDrawerOpen: false,
      loginDialog: false,
      typeLogin: 'login',
    };
  },
  methods: {
    closeDialog() {
      this.loginDialog = false;
    },
    logout() {
      this.$q.dialog({
        message: '¿Quieres cerrar sesión?',
        title: 'Salir',
        ok: {
          push: true,
        },
        cancel: {
          push: true,
          color: 'negative',
        },
      }).onOk(() => {
        this.$q.loading.show();
        this.$axios.post('logout').then(() => {
          this.$axios.defaults.headers.common.Authorization = '';
          this.$store.user = {};
          localStorage.removeItem('tokenSuper');
          this.$store.isLoggedIn = false;
          this.$q.loading.hide();
        });
      });
    },
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
    ingresar() {
      this.loginDialog = true;
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
