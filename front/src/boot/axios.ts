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
const Oauth = new UniversalSocialauth(axios, options);
export default boot(({ app }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api
  app.config.globalProperties.$Oauth = Oauth;
  app.config.globalProperties.$axios = axios.create({ baseURL: import.meta.env.VITE_API_BACK });
  app.config.globalProperties.$url = import.meta.env.VITE_API_BACK;
  app.config.globalProperties.$store = useCounterStore();
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
});

export { api };
