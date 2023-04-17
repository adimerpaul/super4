<template>
  <q-page>
    <q-card>
      <q-card-section class="row items-center">
        <q-avatar icon="shopping_cart" color="primary" text-color="white" size="md" />
        <div class="text-h6 q-ml-sm">Mi carrito de compras</div>
        <q-space />
        <q-btn  v-if="$store.pedidos.length > 0" flat dense color="red"
                style="text-decoration: underline"
                label="Vaciar carrito" @click="eliminarPedido('all')" no-caps />
      </q-card-section>
      <q-card-section>
        <q-list bordered separator>
          <q-item v-for="(item, index) in $store.pedidos" :key="index" bordered>
            <q-item-section avatar class="flex flex-center">
<!--              <q-avatar :icon="item.rubro.icono" color="grey" text-color="white" />-->
              <q-img :src="`${$url}../images/${item.imagen}`" style="width: 50px; height: 50px;" />
<!--              <pre>{{item}}</pre>-->
            </q-item-section>
            <q-item-section>
              <q-item-label>{{item.nombre}}</q-item-label>
              <q-item-label caption>{{item.cantidad}} x {{item.precio}}Bs</q-item-label>
            </q-item-section>
            <q-item-section side>
              {{item.total}}Bs
              <q-btn flat round color="red" icon="o_delete" @click="eliminarPedido(index)" />
            </q-item-section>
          </q-item>
          <q-item v-if="$store.pedidos.length > 0">
            <q-item-section>
              <q-item-label>
                <q-btn color="primary" label="Realizar pedido" class="full-width" rounded no-caps>
                  <div class="">
                    : <span class="text-subtitle2">{{ total }}Bs</span>
                  </div>
                </q-btn>
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item v-if="$store.pedidos.length === 0">
            <q-item-section>
              <q-item-label>
                <div class="text-center text-grey-8">No hay productos en el carrito</div>
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
    </q-card>
<!--    <button @click="useAuthProvider('github', null)">auth Github</button>-->
<!--    <button @click="useAuthProvider('facebook', null)">auth Facebook</button>-->
    <button @click="useAuthProvider('google', null)">auth Google</button>
<!--    <button @click="useAuthProvider('twitter', null)">auth Twitter</button>-->
  </q-page>
</template>

<script>
import { Providers } from 'universal-social-auth';

export default {
  name: 'PedidosPage',
  methods: {
    useAuthProvider(provider, proData) {
      const pro = proData;
      const ProData = pro || Providers[provider];
      this.$q.loading.show();
      this.$Oauth.authenticate(provider, ProData).then((response) => {
        if (response.code) {
          this.$axios.post(`sociallogin/${provider}`, response).then((res) => {
            console.log(res.data);
            this.$q.loading.hide();
            this.$q.notify({
              message: 'Bienvenido',
              color: 'positive',
              icon: 'check_circle',
              position: 'top',
            });
            // this.$router.push('/');
            // this.store.user=res.data.user;
            // this.store.isLoggedIn = true;
            // this.$api.defaults.headers.common['Authorization'] = 'Bearer '+res.data.token
            // localStorage.setItem('tokenChat',res.data.token)
          }).catch((err) => {
            console.log(err);
          }).finally(() => {
            this.$q.loading.hide();
          });
        } else {
          this.$q.loading.hide();
        }
      }).catch((err) => {
        console.log(err);
      }).finally(() => {
        // this.$q.loading.hide()
      });
    },
    eliminarPedido(index) {
      this.$q.dialog({
        title: 'Eliminar',
        message: index === 'all' ? '¿Desea vaciar el carrito?' : '¿Desea eliminar el producto?',
        cancel: true,
        persistent: true,
      }).onOk(() => {
        if (index === 'all') {
          this.$store.pedidos = [];
        } else {
          this.$store.pedidos.splice(index, 1);
          this.$q.notify({
            message: index === 'all' ? 'Carrito vaciado' : 'Producto eliminado',
            color: 'positive',
            icon: 'check',
            position: 'top',
          });
        }
      }).onCancel(() => {
        // do something
      }).onDismiss(() => {
        // do something
      });
    },
  },
  computed: {
    total() {
      let total = 0;
      this.$store.pedidos.forEach((item) => {
        total += item.total;
      });
      return total;
    },
  },
};
</script>

<style scoped>

</style>
