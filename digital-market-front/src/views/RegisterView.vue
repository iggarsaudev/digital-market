<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { toast } from "vue3-toastify";

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "", // debe llamarse así para que laravel lo valide automáticamente
});

const isLoading = ref(false);

// manejamos el registro
const handleRegister = async () => {
  isLoading.value = true;

  try {
    await authStore.register(form.value);
    toast.success("¡Cuenta creada con éxito! Bienvenido/a.");
    router.push({ name: "home" });
  } catch (error) {
    // Capturamos los errores de validación de Laravel (422)
    const errors = error.response?.data?.errors;

    if (errors?.email) {
      toast.error("Este correo ya está registrado.");
    } else if (errors?.password) {
      toast.error(
        "La contraseña debe tener al menos 8 caracteres y coincidir.",
      );
    } else {
      toast.error("Error al registrar la cuenta. Revisa tus datos.");
    }
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
      Crear Cuenta
    </h2>

    <form @submit.prevent="handleRegister" class="space-y-5">
      <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1"
          >Nombre</label
        >
        <input
          type="text"
          id="name"
          v-model="form.name"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600"
        />
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1"
          >Correo electrónico</label
        >
        <input
          type="email"
          id="email"
          v-model="form.email"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600"
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
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600"
        />
      </div>

      <div>
        <label
          for="password_confirmation"
          class="block text-sm font-medium text-slate-700 mb-1"
          >Confirmar contraseña</label
        >
        <input
          type="password"
          id="password_confirmation"
          v-model="form.password_confirmation"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600"
        />
      </div>

      <button
        type="submit"
        :disabled="isLoading"
        class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors disabled:opacity-50 flex justify-center items-center"
      >
        <span
          v-if="isLoading"
          class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"
        ></span>
        {{ isLoading ? "Registrando..." : "Crear cuenta" }}
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
      ¿Ya tienes cuenta?
      <router-link
        to="/login"
        class="text-indigo-600 hover:text-indigo-700 font-medium"
      >
        Inicia sesión
      </router-link>
    </p>
  </div>
</template>
