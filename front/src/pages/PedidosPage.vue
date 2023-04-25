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
              <q-img :src="`${$url}../images/${item.imagen}`" style="width: 50px; height: 50px;" />
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
              <div class="col-6 q-pr-xs flex flex-center">
                <q-btn color="primary" push no-caps dense
                       @click="status = 'envio';sale.type = 'delivery'">
                  <div class="row items-center no-wrap">
                    <q-icon left name="local_shipping" />
                    <div class="text-center">
                      <span class="text-bold">Delivery</span> <br>
                      Tu pedido llega a tu casa
                    </div>
                  </div>
                </q-btn>
              </div>
              <div class="col-6 q-pl-xs flex flex-center">
                <q-btn color="primary" push no-caps dense
                       @click="status = 'envio';sale.type = 'store'">
                  <div class="row items-center no-wrap">
                    <q-icon left name="store" />
                    <div class="text-center">
                      <span class="text-bold">Recoger en tienda</span> <br>
                      Recoge tu pedido en la tienda
                    </div>
                  </div>
                </q-btn>
              </div>
            </div>
          </template>
          <template v-if="status === 'envio'">
            <div class="row">
              <div class="col-10 col-sm-11" v-if="sale.type === 'delivery'">
                <q-select dense outlined label="Direccion"
                          v-model="sale.address_id" :options="addresses"
                          map-options
                          option-value="id"
                          option-label="address"
                          emit-value
                />
              </div>
              <div class="col-2 col-sm-1 flex flex-center" v-if="sale.type === 'delivery'">
                <q-btn color="primary" no-caps dense icon="add" round @click="adressClick">
                  <q-tooltip>Crear direccion</q-tooltip>
                </q-btn>
              </div>
              <div class="col-12" v-if="sale.type === 'delivery'">
                <div class="text-bold">El costo de envio es de
                  {{addressSelected.deliveryAmount}} Bs
                </div>
              </div>
              <div class="col-10 col-sm-11 q-mt-md">
                <q-select dense outlined label="Facturación"
                          v-model="sale.invoice_id" :options="invoices"
                          map-options
                          option-value="id"
                          option-label="name"
                          emit-value
                />
              </div>
              <div class="col-2 col-sm-1 q-mt-md flex flex-center">
                <q-btn color="primary" no-caps dense icon="add" round @click="invoiceClick">
                  <q-tooltip>Crear direccion</q-tooltip>
                </q-btn>
              </div>
              <div class="col-12 q-mt-md">
                <div>
                  <q-btn color="primary" label="Realizar pedido"
                         @click="realizarPedido" class="full-width" rounded no-caps>
                    <div class="">
                      : <q-badge color="white" text-color="black" dense
                                 class="text-bold">{{ total }}Bs</q-badge>
                    </div>
                  </q-btn>
                </div>
              </div>
              <div class="col-12 q-mt-xs">
                <q-btn color="primary" label="Atras" icon="arrow_back" dense size="12px" no-caps
                       @click="status = 'pedido'"/>
              </div>
            </div>
          </template>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="addressDialog" persistent>
      <q-card style="width: 400px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <q-avatar icon="add_location" color="primary" text-color="white" size="md" />
          <div class="text-h6 q-ml-sm">Crear direccion</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <q-form @submit="addressCreate" @reset="addressDialog = false">
            <q-input outlined dense v-model="address.address" label="Dirección"
                     :rules="[val => val.length > 0 || 'La dirección es requerida']" />
            <q-input outlined dense v-model="address.description" label="Descripción"
                      :rules="[val => val.length > 0 || 'La descripción es requerida']" />
            <div class="text-bold">
              Selecciona tu ubicación en el mapa <span>(puede arrastrar el marcador)</span>
            </div>
            <div style="height:400px" class="items-center">
              <l-map ref="map" @click="clickMaps"
                     :zoom="13" :maxZoom="17"
                     :center="[markerLatLng[0], markerLatLng[1]]">
                <l-tile-layer
                  :url="styleMap?`https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`:`https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}`"
                  layer-type="base"
                  name="OpenStreetMap"
                ></l-tile-layer>
                <l-marker :lat-lng="markerLatLng" @moveend="ondragend" draggable  ></l-marker>
                <l-control position="topright" >
                  <q-btn @click="styleMap=!styleMap"
                         color="white" text-color="black" dense
                         :icon="styleMap?'o_satellite':'o_map'"
                         :label="styleMap?'Satelite':'Mapa'"
                         no-caps
                  ></q-btn>
                </l-control>
              </l-map>
            </div>
            <div class="row">
              <div class="col-6 flex flex-center">
                <q-btn color="primary" :loading="loading"
                       label="Cancelar" type="reset" no-caps dense icon="close" />
              </div>
              <div class="col-6 flex flex-center">
                <q-btn color="primary" :loading="loading"
                       label="Crear" type="submit" no-caps dense icon="add" />
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="invoiceDialog" persistent>
      <q-card style="width: 400px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <q-avatar icon="add_location" color="primary" text-color="white" size="md" />
          <div class="text-h6 q-ml-sm">Crear Facturación</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <q-form @submit="invoiceCreate" @reset="invoiceDialog = false">
            <q-input outlined dense v-model="invoice.name" label="Razon social"
                     :rules="[val => val.length > 0 || 'La razon social es requerida']" />
            <q-input outlined dense v-model="invoice.number" label="CI/NIT"
                      :rules="[val => val.length > 0 || 'La CI/NIT es requerida']" />
            <div class="row">
              <div class="col-6 flex flex-center">
                <q-btn color="primary" :loading="loading"
                       label="Cancelar" type="reset" no-caps dense icon="close" />
              </div>
              <div class="col-6 flex flex-center">
                <q-btn color="primary" :loading="loading"
                       label="Crear" type="submit" no-caps dense icon="add" />
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
      markerLatLng: [-17.969953, -67.115180],
      pedidoDialog: false,
      addressDialog: false,
      loginDialog: false,
      status: '',
      sale: {
        details: this.$store.pedidos,
      },
      invoices: [],
      addresses: [],
      address: {},
      loading: false,
      invoice: {},
      invoiceDialog: false,
    };
  },
  created() {
    this.invoicesGet();
    this.addressesGet();
  },
  methods: {
    invoiceCreate() {
      this.loading = true;
      this.$axios.post('/invoices', this.invoice)
        .then(() => {
          this.loading = false;
          this.invoiceDialog = false;
          this.invoicesGet();
          this.$q.notify({
            color: 'positive',
            message: 'Facturación creada correctamente',
            position: 'top',
          });
        })
        .catch((err) => {
          this.loading = false;
          this.$q.notify({
            color: 'negative',
            message: err.response.data.message,
          });
        });
    },
    invoiceClick() {
      this.invoice = {
        number: '',
        name: '',
      };
      this.invoiceDialog = true;
    },
    adressClick() {
      this.addressDialog = true;
      this.address = {
        address: '',
        description: '',
        lat: this.markerLatLng[0],
        lng: this.markerLatLng[1],
      };
    },
    clickMaps(e) {
      if (e.latlng) {
        this.markerLatLng = [e.latlng.lat, e.latlng.lng];
      }
    },
    ondragend(e) {
      /* eslint no-underscore-dangle: 0 */
      this.markerLatLng = [e.target._latlng.lat, e.target._latlng.lng];
    },
    addressCreate() {
      // eslint-disable-next-line prefer-destructuring
      this.address.lat = this.markerLatLng[0] ? this.markerLatLng[0] : this.markerLatLng.lat;
      // eslint-disable-next-line prefer-destructuring
      this.address.lng = this.markerLatLng[1] ? this.markerLatLng[1] : this.markerLatLng.lng;
      this.loading = true;
      this.$axios.post('address', this.address).then(() => {
        this.loading = false;
        this.addressesGet();
        this.address = {};
        this.addressDialog = false;
        this.$q.notify({
          color: 'positive',
          message: 'Direccion creada correctamente',
          position: 'top',
        });
        // this.sale.address_id = res.data.id;
      }).catch((err) => {
        this.loading = false;
        this.$q.notify({
          color: 'negative',
          message: err.response.data.message,
        });
      });
    },
    addressesGet() {
      this.addresses = [{ id: 0, address: 'Elejir una direccion' }];
      this.$axios.get('address').then((response) => {
        if (response.data.length === 0) {
          this.sale.address_id = 0;
        } else {
          const adressActive = response.data.find((element) => element.active === '1');
          if (adressActive) {
            this.sale.address_id = adressActive.id;
          } else {
            this.sale.address_id = response.data[0].id;
          }
        }
        response.data.forEach((element) => {
          element.address = `${element.address} - ${element.description}`;
        });
        this.addresses = [...this.addresses, ...response.data];
      });
    },
    invoicesGet() {
      this.invoices = [{ id: 0, name: 'Elejir una factura' }];
      this.$axios.get('invoices').then((response) => {
        if (response.data.length === 0) {
          this.sale.invoice_id = 0;
        } else {
          const invoiceActive = response.data.find((element) => element.active === '1');
          if (invoiceActive) {
            this.sale.invoice_id = invoiceActive.id;
          } else {
            this.sale.invoice_id = response.data[0].id;
          }
        }
        response.data.forEach((element) => {
          element.name = `${element.name} - ${element.number}`;
        });
        this.invoices = [...this.invoices, ...response.data];
      });
    },
    closeDialog(token) {
      this.loginDialog = false;
      this.$axios.defaults.headers.common.Authorization = `Bearer ${token}`;
      this.invoicesGet();
      this.addressesGet();
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
    addressSelected() {
      return this.addresses.find((element) => element.id === this.sale.address_id);
    },
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
