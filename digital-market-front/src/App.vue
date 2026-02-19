<script setup>
import { useRouter } from "vue-router";
import { ShoppingBag } from "lucide-vue-next";
import { useAuthStore } from "./stores/auth";
import { toast } from "vue3-toastify";

const router = useRouter();
const authStore = useAuthStore();

// Manejamos el cierre de sesión
const handleLogout = async () => {
  // Llamamos a Pinia (que a su vez llama a Laravel para destruir el token)
  await authStore.logout();

  // Avisamos al usuario
  toast.info("Has cerrado sesión correctamente.");

  // Lo mandamos a la página de inicio
  router.push({ name: "home" });
};
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800 flex flex-col">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-10">
      <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between"
      >
        <router-link to="/" class="flex items-center gap-2">
          <div
            class="w-8 h-8 bg-indigo-600 rounded-md flex items-center justify-center text-white font-bold"
          >
            D
          </div>
          <span class="font-bold text-xl tracking-tight">DigitalMarket</span>
        </router-link>

        <div class="flex items-center gap-4">
          <template v-if="!authStore.isAuthenticated">
            <router-link
              to="/login"
              class="text-sm font-medium text-slate-600 hover:text-indigo-600"
            >
              Entrar
            </router-link>
            <router-link
              to="/register"
              class="text-sm font-medium bg-indigo-600 text-white px-3 py-1.5 rounded-md hover:bg-indigo-700 transition-colors"
            >
              Registro
            </router-link>
          </template>

          <template v-else>
            <span class="text-sm font-medium text-slate-500 hidden sm:block">
              Hola,
              <strong class="text-slate-800">{{
                authStore.user?.name || "Usuario"
              }}</strong>
            </span>
            <button
              @click="handleLogout"
              class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors"
            >
              Salir
            </button>
          </template>

          <button
            class="relative p-2 text-slate-600 hover:text-indigo-600 transition-colors ml-2 border-l border-gray-200 pl-4"
          >
            <ShoppingBag class="w-6 h-6" />
            <span
              class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full"
            >
              0
            </span>
          </button>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full flex-grow">
      <router-view />
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-8">
      <div class="max-w-7xl mx-auto px-4 text-center text-slate-500 text-sm">
        &copy; {{ new Date().getFullYear() }} DigitalMarket. Proyecto Portfolio
        Ignacio García Sausor Laravel + Vue.
      </div>
    </footer>
  </div>
</template>
