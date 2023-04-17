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
                  <q-btn push color="red-10" label="Ingresar" no-caps />
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
  </q-layout>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
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

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink,
  },

  setup() {
    const leftDrawerOpen = ref(false);

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
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
});
</script>
