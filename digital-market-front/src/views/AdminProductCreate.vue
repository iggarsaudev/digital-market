<script setup>
import { ref, shallowRef } from "vue";
import { useRouter } from "vue-router";
import api from "../lib/axios";
import { PlusCircle } from "lucide-vue-next";
import { toast } from "vue3-toastify";

const router = useRouter();
const isSubmitting = ref(false);

// 🛡️ TRUCO SENIOR: Usamos shallowRef para archivos.
// Así Vue no corrompe los datos binarios al intentar hacerlos reactivos.
const imageFile = shallowRef(null);

const form = ref({
  name: "",
  description: "",
  price: "",
});

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  // 🛡️ SEGURO ANTI-ERRORES: Comprobamos el tamaño en el frontend (2MB máximo)
  if (file.size > 2 * 1024 * 1024) {
    toast.error("La imagen es demasiado grande. Máximo 2MB.");
    event.target.value = ""; // Vaciamos el input
    imageFile.value = null;
    return;
  }

  imageFile.value = file;
};

const createProduct = async () => {
  isSubmitting.value = true;
  try {
    const priceInCents = Math.round(parseFloat(form.value.price) * 100);

    const formData = new FormData();
    formData.append("name", form.value.name);
    formData.append("description", form.value.description);
    formData.append("price", priceInCents);

    if (imageFile.value) {
      formData.append("image", imageFile.value);
    }

    // Axios mandará el FormData limpio, con sus boundaries generados automáticamente
    await api.post("/products", formData);

    toast.success("¡Producto creado con éxito!");
    router.push("/");
  } catch (error) {
    console.error("Error al crear producto:", error);
    // Ahora si falla, te mostrará el mensaje de error real en el cartelito rojo
    const errorMsg =
      error.response?.data?.message || "Hubo un error al crear el producto.";
    toast.error(errorMsg);
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="max-w-2xl mx-auto py-10 px-4">
    <h1
      class="text-3xl font-extrabold text-slate-900 mb-8 flex items-center gap-3"
    >
      <PlusCircle class="w-8 h-8 text-indigo-600" />
      Nuevo Producto Digital
    </h1>

    <form
      @submit.prevent="createProduct"
      class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 space-y-6"
    >
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2"
          >Nombre del producto</label
        >
        <input
          v-model="form.name"
          type="text"
          required
          placeholder="Ej. Curso de Vue 3 Avanzado"
          class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2"
          >Imagen del producto</label
        >
        <input
          type="file"
          accept="image/jpeg, image/png, image/webp"
          @change="handleImageUpload"
          class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2"
          >Precio (en Euros)</label
        >
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <span class="text-slate-500 sm:text-sm">€</span>
          </div>
          <input
            v-model="form.price"
            type="number"
            step="0.01"
            min="0.50"
            required
            placeholder="19.99"
            class="w-full pl-8 border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2"
          >Descripción</label
        >
        <textarea
          v-model="form.description"
          rows="4"
          required
          placeholder="Explica qué incluye esta descarga digital..."
          class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        ></textarea>
      </div>

      <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
        <router-link
          to="/"
          class="px-4 py-2 text-slate-600 font-medium hover:bg-slate-50 rounded-md transition-colors"
        >
          Cancelar
        </router-link>
        <button
          type="submit"
          :disabled="isSubmitting"
          class="bg-indigo-600 text-white px-6 py-2 rounded-md font-medium hover:bg-indigo-700 transition-colors disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <span v-if="isSubmitting">Guardando...</span>
          <span v-else>Publicar Producto</span>
        </button>
      </div>
    </form>
  </div>
</template>
