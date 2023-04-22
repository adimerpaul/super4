import { boot } from 'quasar/wrappers';
import axios, { AxiosInstance } from 'axios';
import { useCounterStore } from 'stores/example-store';
import UniversalSocialauth from 'universal-social-auth';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $axios: AxiosInstance;
  }
}

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: 'https://api.example.com' });
const options = {
  providers: {
    google: {
      clientId: '1035253398517-pno89s8jidafklhnpmurmttlfgrheeoj.apps.googleusercontent.com',
      redirectUri: `${import.meta.env.VITE_API_FRONT}auth/google/callback`,
    },
    facebook: {
      clientId: '************',
      redirectUri: 'https://myapp.com/auth/facebook/callback',
    },
  },
};
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
const Oauth = new UniversalSocialauth(axios, options);
export default boot(({ app, router }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api
  app.config.globalProperties.$Oauth = Oauth;
  app.config.globalProperties.$axios = axios.create({ baseURL: import.meta.env.VITE_API_BACK });
  app.config.globalProperties.$url = import.meta.env.VITE_API_BACK;
  app.config.globalProperties.$store = useCounterStore();
  const token = localStorage.getItem('tokenSuper');
  if (token) {
    useCounterStore().loading = true;
    app.config.globalProperties.$axios.defaults.headers.common.Authorization = `Bearer ${token}`;
    app.config.globalProperties.$axios.post('me').then((res) => {
      // console.log(res.data)
      useCounterStore().user = res.data;
      useCounterStore().isLoggedIn = true;
    }).catch(() => {
      app.config.globalProperties.$axios.defaults.headers.common.Authorization = '';
      useCounterStore().user = {};
      localStorage.removeItem('tokenSuper');
      useCounterStore().isLoggedIn = false;
      router.push('/');
    }).finally(() => {
      useCounterStore().loading = false;
    });
  }
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
});

export { api };
