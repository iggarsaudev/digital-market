<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { toast } from "vue3-toastify";

const router = useRouter();
const authStore = useAuthStore();

// estado del formulario
const form = ref({
  email: "",
  password: "",
});

const isLoading = ref(false);

// manejamos el envío del formulario
const handleLogin = async () => {
  isLoading.value = true;

  try {
    await authStore.login(form.value);
    // si va bien, lanzamos toast y mandamos al catálogo
    toast.success("¡Bienvenido/a de nuevo!");
    router.push({ name: "home" });
  } catch (error) {
    // en laravel, los errores de validación vienen en "errors", y el mensaje general en "message"
    const backendError =
      error.response?.data?.errors?.email?.[0] ||
      error.response?.data?.message ||
      "Error al iniciar sesión. revisa tus credenciales.";

    // lanzamos el toast de error
    toast.error(backendError);
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div
    class="max-w-md mx-auto bg-white p-8 rounded-md shadow-sm border border-gray-100 mt-10"
  >
    <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">
      Iniciar Sesión
    </h2>

    <form @submit.prevent="handleLogin" class="space-y-5">
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1"
          >Correo electrónico</label
        >
        <input
          type="email"
          id="email"
          v-model="form.email"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-shadow"
          placeholder="tu@email.com"
        />
      </div>

      <div>
        <label
          for="password"
          class="block text-sm font-medium text-slate-700 mb-1"
          >Contraseña</label
        >
        <input
          type="password"
          id="password"
          v-model="form.password"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-shadow"
          placeholder="********"
        />
      </div>

      <button
        type="submit"
        :disabled="isLoading"
        class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
      >
        <span
          v-if="isLoading"
          class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"
        ></span>
        {{ isLoading ? "Iniciando..." : "Entrar" }}
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
      ¿No tienes cuenta?
      <router-link
        to="/register"
        class="text-indigo-600 hover:text-indigo-700 font-medium"
      >
        Regístrate aquí
      </router-link>
    </p>
  </div>
</template>
