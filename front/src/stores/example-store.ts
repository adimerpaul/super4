import { defineStore } from 'pinia';

export const useCounterStore = defineStore('counter', {
  state: () => ({
    counter: 0,
    pedidos: [],
    productos: [],
    user: {},
    isLoggedIn: false,
    rubros: [],
    loading: false,
    pF: [],
  }),
  getters: {
    doubleCount: (state) => state.counter * 2,
  },
  actions: {
    increment() {
      this.counter += 1;
    },
  },
});
