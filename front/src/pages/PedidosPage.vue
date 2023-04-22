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
                <q-btn color="primary" label="Realizar pedido"
                       @click="realizarPedido" class="full-width" rounded no-caps>
                  <div class="">
                    : <q-badge color="white" text-color="black" dense
                               class="text-bold">{{ total }}Bs</q-badge>
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
    <q-dialog v-model="loginDialog" persistent>
      <LoginComponent  @closeDialog="closeDialog"/>
    </q-dialog>
    <q-dialog v-model="pedidoDialog" persistent>
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <q-avatar icon="shopping_cart" color="primary" text-color="white" size="md" />
          <div class="text-h6 q-ml-sm">Realizar pedido</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <template v-if="status === 'pedido'">
            <div class="text-center text-h6">Elije una forma de entrega</div>
            <div class="row">
              <div class="col-6 flex flex-center">
                <q-btn color="primary" push no-caps dense
                       @click="status = 'envio';sale.type = 'delivery'">
                  <div class="row items-center no-wrap">
                    <q-icon left name="local_shipping" />
                    <div class="text-center">
                      Delivery <br>
                      Tu pedido llega a tu casa
                    </div>
                  </div>
                </q-btn>
              </div>
              <div class="col-6 flex flex-center">
                <q-btn color="primary" push no-caps dense
                       @click="status = 'envio';sale.type = 'store'">
                  <div class="row items-center no-wrap">
                    <q-icon left name="store" />
                    <div class="text-center">
                      Recoger en tienda <br>
                      Recoge tu pedido en la tienda
                    </div>
                  </div>
                </q-btn>
              </div>
            </div>
          </template>
          <template v-if="status === 'envio'">
            <div class="row">
              <div class="col-10 col-sm-11">
                <q-select dense outlined label="Direccion"
                          v-model="sale.address_id" :options="addresses"
                          map-options
                          option-value="id"
                          option-label="address"
                          emit-value
                />
              </div>
              <div class="col-2 col-sm-1 flex flex-center">
                <q-btn color="primary" no-caps dense icon="add" round @click="addressDialog = true">
                  <q-tooltip>Crear direccion</q-tooltip>
                </q-btn>
              </div>
              <div class="col-12">
                <pre>{{addresses}}</pre>
                <pre>{{sale}}</pre>
              </div>
            </div>
            <q-btn color="primary" label="Atras" icon="arrow_back" dense no-caps
                   @click="status = 'pedido'"/>
          </template>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="addressDialog" persistent>
      <q-card style="width: 500px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <q-avatar icon="add_location" color="primary" text-color="white" size="md" />
          <div class="text-h6 q-ml-sm">Crear direccion</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <q-form @submit="addressCreate" @reset="addressDialog = false" class="q-gutter-md">
            <q-input outlined dense v-model="address.address" label="Dirección" />
            <q-input outlined dense v-model="address.description" label="Descripción" />
            <div style="height:500px" class="items-center">
              <l-map ref="map" @click="clickMaps"
                     :zoom="13" :maxZoom="17" :center="[-17.970, -67.1111 ]" >
                <l-tile-layer
                  :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                  layer-type="base"
                  name="OpenStreetMap"
                ></l-tile-layer>
                <l-marker :lat-lng="markerLatLng" @moveend="ondragend" draggable  ></l-marker>
                <l-control position="topright" >
                  <q-btn @click="styleMap=!styleMap"
                         icon="map" class="bg-primary text-white" dense round></q-btn>
                </l-control>
              </l-map>
            </div>
            <div class="row">
              <div class="col-6">
                <q-btn color="primary" label="Crear" type="submit" no-caps dense />
              </div>
              <div class="col-6">
                <q-btn color="primary" label="Cancelar" type="reset" no-caps dense />
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import LoginComponent from 'components/LoginComponent.vue';
import {
  LMap,
  LTileLayer,
  LMarker,
  LControl,
} from '@vue-leaflet/vue-leaflet';

import 'leaflet/dist/leaflet.css';

export default {
  name: 'PedidosPage',
  components: {
    LoginComponent,
    LMap,
    LTileLayer,
    LMarker,
    LControl,
  },
  data() {
    return {
      styleMap: true,
      markerLatLng: [-17.970, -67.1111],
      pedidoDialog: false,
      addressDialog: false,
      loginDialog: false,
      status: '',
      sale: {},
      invoices: [],
      addresses: [],
      address: {},
    };
  },
  created() {
    this.invoicesGet();
    this.addressesGet();
  },
  methods: {
    clickMaps(e) {
      if (e.latlng) {
        this.markerLatLng = e.latlng;
      }
    },
    ondragend(e) {
      console.log(e.target);
      // this.markerLatLng = [e.target._latlng.lat, e.target._latlng.lng];
    },
    addressCreate() {
      this.$axios.post('address', this.address).then(() => {
        this.addressesGet();
        this.address = {};
        this.addressDialog = false;
      });
    },
    addressesGet() {
      this.addresses = [{ id: 0, address: 'Elejir una direccion' }];
      this.$axios.get('address').then((response) => {
        this.addresses = [...this.addresses, ...response.data];
      });
    },
    invoicesGet() {
      this.invoices = [{ id: 0, name: 'Elejir una factura' }];
      this.$axios.get('invoices').then((response) => {
        this.invoices = response.data;
      });
    },
    closeDialog() {
      this.loginDialog = false;
    },
    realizarPedido() {
      if (this.$store.isLoggedIn) {
        this.pedidoDialog = true;
        this.status = 'pedido';
      } else {
        this.loginDialog = true;
      }
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
            actions: [{ icon: 'close', color: 'white' }],
            timeout: 1000,
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
