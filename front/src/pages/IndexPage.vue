<template>
  <q-page>
    <CarouselComponent/>
    <div style="position: relative; width: 100%; overflow: hidden;border: 1px solid #ccc;">
      <q-icon name="chevron_left" size="2.5rem" color="white"
              style="position: absolute; left: 0; top: 50%;
              transform: translateY(-50%);z-index: 1;background: rgba(128,128,128,0.4);"
              @click="scrollMinus"/>
      <q-scroll-area ref="scrollAreaRef" style="height: 150px; width: 100%; overflow: auto;">
        <div class="row no-wrap">
          <q-card v-for="n in 100" :key="n"
                  style="width: 140px;height: 140px;overflow: hidden" class="q-ma-xs" flat bordered>
            <q-card-section class="text-center">
              <q-icon name="o_lunch_dining" size="2rem" color="grey"/>
              <div class="">Card {{ n }}
                Lorem ipsum dolor sit amet
              </div>
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
    };
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
