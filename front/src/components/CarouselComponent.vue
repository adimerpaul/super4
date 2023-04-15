<template>
  <q-carousel
    v-model="slide"
    next-icon="arrow_forward_ios"
    prev-icon="arrow_back_ios"
    swipeable
    animated
    control-color="black"
    navigation-icon="radio_button_unchecked"
    navigation-active-icon="radio_button_checked"
    navigation
    autoplay
    infinite
    arrows
    class="rounded-borders"
    height="auto"
  >
    <q-carousel-slide class="q-pa-none q-ma-none" :name="item.id"
                      v-for="(item, index) in carrusel_comida_rapida" :key="index">
      <div class="row">
        <div class="col-12 col-md-6 flex flex-center q-pa-xs">
          <div class="">
            <div class="text-h6" v-html="item.titulo"></div>
            <div class="text-subtitle2" v-html="item.subtitulo"></div>
            <div class="text-center">
              <q-btn :dense="$q.screen.lt.md?true:false" type="a" href="https://bit.ly/Pedidos_SuperHamburguesa" target="_blank"  color="primary" label="Realiza tu pedido" no-caps icon="o_shopping_cart" class="q-mt-md" />
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 flex flex-center">
          <q-img :src="item.imagen" height="300px" width="300px" />
        </div>
      </div>
    </q-carousel-slide>
  </q-carousel>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'CarouselComponent',
  data() {
    return {
      slide: 1,
      carrusel_comida_rapida: [
        {
          id: 1,
          titulo: 'Prueba nuestras deliciosas alitas🤤',
          subtitulo: 'ᑭroducto nuevo😍🍔Super hamburguesas <br>'
            + '✅ALITAS DULCES<br>'
            + '✅ALITAS AGRIDULCES<br>'
            + '✅ALITAS PICANTES <br>'
            + 'a solo Bs.30',
          imagen: 'images/332405373_1173754489982141_2658959462106547509_n.jpg',
        },
        {
          id: 2,
          titulo: 'ᑭᖇ〇Ⲙ〇 Viernes Loco🔈😍<br>'
            + '𝑳𝒂 𝑺𝒆𝒎𝒂𝒏𝒂 𝑳𝒐𝒄𝒂',
          subtitulo: 'Todos los Viernes🔈<br>'
            + '2 PIPOCAS DE POLLO + 2 LIMONADAS 🍗🥤Bs.60😄<br>'
            + '2 ALITAS PICANTES 🍗🥤Bs.50😄',
          imagen: 'images/337697191_1515974042224707_7065300601604513083_n.jpg',
        },
        {
          id: 3,
          titulo: 'ᑭᖇ〇Ⲙ〇 Jueves Loco🔈😍<br>'
            + '𝑳𝒂 𝑺𝒆𝒎𝒂𝒏𝒂 𝑳𝒐𝒄𝒂',
          subtitulo: 'Todos los Jueves🔈<br>'
            + '✅2 Wraps Club y Cesar 🍔Bs.50<br>'
            + '✅Torta 20 Pak + 1/4 kilo de helado🍨al mismo costo',
          imagen: 'images/337302357_772238157428808_341779882866862230_n.jpg',
        },
        {
          id: 4,
          titulo: 'ᑭᖇ〇Ⲙ〇 Miércoles Loco🔈😍<br>'
            + '𝑳𝒂 𝑺𝒆𝒎𝒂𝒏𝒂 𝑳𝒐𝒄𝒂',
          subtitulo: 'Todos los Miércoles🔈<br>'
            + '2 𝐂𝐎𝐌𝐁𝐎 ESCOLAR+2 JUGOS 🍗🥤Bs.48😄<br>'
            + '2 𝐂𝐎𝐌𝐁𝐎 DUO+2 JUGOS 🍗🥤Bs.60😄',
          imagen: 'images/337539818_711523497341572_5870473917693930077_n.jpg',
        },
      ],
    };
  },
});
</script>

<style scoped>

</style>
