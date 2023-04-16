<template>
  <q-page>
    <CarouselComponent/>
    <div class="text-h6 text-center q-mt-md">Nuestros Catálogos</div>
    <div style="position: relative; width: 100%; overflow: hidden;border: 1px solid #ccc;">
      <q-icon name="chevron_left" size="2.5rem" color="white"
              style="position: absolute; left: 0; top: 50%;
              transform: translateY(-50%);z-index: 1;background: rgba(128,128,128,0.4);"
              @click="scrollMinus" class="cursor-pointer"/>
      <q-scroll-area ref="scrollAreaRef" style="height: 135px; width: 100%; overflow: auto;">
        <div class="row no-wrap">
          <q-card v-for="r in rubros" :key="r.id"
                  style="width: 140px;height: 120px;overflow: hidden"
                  class="q-ma-xs bg-blue-1 cursor-pointer" flat bordered
                  @click="rubroFilter(r.id)"
          >
            <q-card-section class="text-center">
              <q-avatar color="white" text-color="grey" :icon="r.icono" />
              <div class="">{{ r.nombre }}</div>
            </q-card-section>
          </q-card>
        </div>
      </q-scroll-area>
      <q-icon name="chevron_right" size="2.5rem" color="white"
              style="position: absolute; right: 0; top: 50%;
              transform: translateY(-50%);z-index: 1;background: rgba(128,128,128,0.4);"
              @click="scrollMore" class="cursor-pointer"/>
    </div>
    <div class="text-h6 text-center q-mt-md">Nuestros Productos</div>
    <q-input v-model="search" outlined label="Buscar Producto"
             class="q-ma-md" clearable counter
             hint="Ej: Hamburguesa, Pizza, etc." :debounce="500"
             @update:model-value="productSearch"
    ></q-input>
    <div class="row">
      <div class="col-6 col-md-2 q-pa-xs" v-for="p in products" :key="p.id">
        <q-card @click="openDialog(p)" class="cursor-pointer">
          <q-img :src="`${$url}../images/${p.imagen}`" width="100%" height="150px">
            <div class="absolute-bottom text-subtitle2 text-center"
                 style="line-height: 1.5;padding: 0px;">
              {{ p.nombre }} <br>
              {{ p.precio }} Bs.
            </div>
          </q-img>
        </q-card>
      </div>
    </div>
    <q-dialog v-model="card">
      <q-card style="width: 400px; max-width: 100%;">
        <q-img :src="`${$url}../images/${product.imagen}`" width="100%" height="300px">
          <div class="absolute-bottom text-subtitle2 text-center"
               style="line-height: 1.5;padding: 0px;">
            {{ product.nombre }} <br>
            {{ product.precio }} Bs.
          </div>
        </q-img>

        <q-card-section>
          <q-btn
            fab
            color="primary"
            :icon="product.rubro.icono"
            class="absolute"
            style="top: 0; right: 12px; transform: translateY(-50%);"
          />

          <div class="row no-wrap items-center">
            <div class="col text-h6 ellipsis">
              {{ product.nombre }}
            </div>
            <div class="col-auto text-grey text-caption q-pt-md row no-wrap items-center">
              <q-icon :name="product.rubro.icono" />
              {{ product.rubro.nombre }}
            </div>
          </div>

          <q-rating v-model="product.star" :max="5" size="32px" />
        </q-card-section>

        <q-card-section class="q-pt-none">
<!--          <div class="text-subtitle1">-->
<!--            $・{{ product.nombre }}-->
<!--          </div>-->
          <div class="text-caption text-grey">
            {{ product.descripcion }}
          </div>
          <div class="text-subtitle2 row items-center q-mb-xs">
            Cantidad
            <q-space />
            <q-btn flat dense icon="remove_circle_outline"
                   color="red" @click="minusCantidad(product)" />
            <q-select v-model="product.cantidad" dense outlined class="text-center"
                      options-dense
                      :options="cantidades" />
            <q-btn flat dense icon="add_circle_outline"
                   color="green" @click="plusCantidad(product)" />
          </div>
          <q-input type="textarea" label="Notas para el pedido"
                   outlined hint="Ej: Sin cebolla, etc." rows="2"
                   counter :max="100" clearable
                    v-model="product.notas" />
        </q-card-section>

        <q-separator />

        <q-card-actions align="center">
          <q-btn class="full-width" rounded color="primary" no-caps
                 @click="addToCart(product)">
            <div class="container flex justify-between">
              <div>
                <q-avatar size="sm" color="red" text-color="white">{{ product.cantidad }}</q-avatar>
              </div>
              <div>Agregar al Carrito</div>
              <div class="text-bold">{{ total }} Bs</div>
            </div>
          </q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import CarouselComponent from 'components/CarouselComponent.vue';

export default defineComponent({
  name: 'IndexPage',
  components: { CarouselComponent },
  data() {
    return {
      search: '',
      rubros: [],
      products: [],
      cantidades: [],
      pT: [],
      product: {},
      card: false,
    };
  },
  created() {
    for (let i = 1; i <= 20; i += 1) {
      this.cantidades.push(i);
    }
    this.$axios.get('rubros').then((response) => {
      this.rubros = response.data;
    });
    this.$axios.get('productos').then((response) => {
      this.products = response.data;
      this.pT = response.data;
    });
  },
  methods: {
    minusCantidad(p: any) {
      if (p.cantidad > 1) {
        p.cantidad -= 1;
      }
    },
    plusCantidad(p: any) {
      if (p.cantidad < 20) {
        p.cantidad += 1;
      }
    },
    openDialog(p: any) {
      this.product = p;
      this.product.cantidad = 1;
      this.card = true;
    },
    rubroFilter(id: number) {
      if (id === 0) {
        this.products = this.pT;
      } else {
        this.products = this.pT.filter((p: any) => p.rubro_id === id);
      }
    },
    productSearch(search: string) {
      // console.log(search);
      if (search == null || search.length === 0) {
        this.products = this.pT;
      } else if (search.length > 0) {
        const s = search.toLowerCase();
        this.products = this.pT.filter((p: any) => p.descripcion.toLowerCase().includes(s));
      } else {
        this.products = [];
      }
    },
    scrollMinus() {
      const number = this.$refs.scrollAreaRef.getScrollPosition('horizontal').left - 300;
      // console.log(number);
      this.$refs.scrollAreaRef.setScrollPosition('horizontal', number, 300);
    },
    scrollMore() {
      const number = this.$refs.scrollAreaRef.getScrollPosition('horizontal').left + 300;
      // console.log(number);
      this.$refs.scrollAreaRef.setScrollPosition('horizontal', number, 300);
    },
  },
  computed: {
    total() {
      return this.product.cantidad * this.product.precio;
    },
  },
});
</script>
<style>
.container {
  width: 100%;
}
</style>
