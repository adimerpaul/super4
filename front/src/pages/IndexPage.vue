<template>
  <q-page>
    <CarouselComponent/>
    <div class="text-h6 text-center q-mt-md">Nuestros Productos</div>
    <div style="position: relative; width: 100%; overflow: hidden;border: 1px solid #ccc;">
      <q-icon name="chevron_left" size="2.5rem" color="white"
              style="position: absolute; left: 0; top: 50%;
              transform: translateY(-50%);z-index: 1;background: rgba(128,128,128,0.4);"
              @click="scrollMinus"/>
      <q-scroll-area ref="scrollAreaRef" style="height: 135px; width: 100%; overflow: auto;">
        <div class="row no-wrap">
          <q-card v-for="r in rubros" :key="r.id"
                  style="width: 140px;height: 120px;overflow: hidden"
                  class="q-ma-xs bg-blue-1" flat bordered>
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
              @click="scrollMore"/>
    </div>
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
      rubros: [],
    };
  },
  created() {
    this.$axios.get('rubros').then((response) => {
      this.rubros = response.data;
    });
  },
  methods: {
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
});
</script>
