import { createApp } from "vue";
import { createPinia } from "pinia";
import router from "./router";
import App from "./App.vue";
import Vue3Toastify from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import "./style.css";

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

// Configuramos los toasts con nuestra paleta y preferencias
app.use(Vue3Toastify, {
  autoClose: 3000,
  position: "top-right",
  theme: "colored",
  clearOnUrlChange: false,
});

app.mount("#app");
