<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useCartStore } from "../stores/cart";
import { useAuthStore } from "../stores/auth";
import { Trash2 } from "lucide-vue-next";
import api from "../lib/axios";
import { toast } from "vue3-toastify";

const cartStore = useCartStore();
const authStore = useAuthStore();
const router = useRouter();
const isProcessing = ref(false);

// Función que se comunica con Laravel
const handleCheckout = async () => {
  // Comprobamos si está logueado
  if (!authStore.isAuthenticated) {
    toast.warning("Debes iniciar sesión para procesar el pago.");
    router.push({ name: "login" });
    return;
  }

  isProcessing.value = true;

  try {
    // Extraemos SOLO los IDs de los productos
    const productIds = cartStore.items.map((item) => item.id);

    // Hacemos la petición POST a nuestra API protegida
    const response = await api.post("/checkout", {
      product_ids: productIds,
    });

    // Si Laravel nos devuelve la URL de Stripe, redirigimos al usuario
    if (response.data.url) {
      window.location.href = response.data.url;
    }
  } catch (error) {
    console.error("Error de checkout:", error);
    toast.error("Error al conectar con la pasarela de pago.");
  } finally {
    isProcessing.value = false;
  }
};
</script>

<template>
  <div
    class="max-w-4xl mx-auto bg-white p-8 rounded-md shadow-sm border border-gray-100 mt-6"
  >
    <h2 class="text-3xl font-bold text-slate-900 mb-8">Tu Carrito</h2>

    <div v-if="cartStore.items.length === 0" class="text-center py-12">
      <p class="text-gray-500 text-lg mb-6">Tu carrito está vacío.</p>
      <router-link
        to="/"
        class="bg-indigo-600 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-700 transition-colors"
      >
        Explorar productos
      </router-link>
    </div>

    <div v-else>
      <ul class="divide-y divide-gray-200 mb-8">
        <li
          v-for="item in cartStore.items"
          :key="item.id"
          class="py-6 flex items-center justify-between"
        >
          <div class="flex items-center gap-4">
            <img
              :src="item.image_url"
              :alt="item.name"
              class="w-16 h-16 object-cover rounded-md bg-gray-100"
            />
            <div>
              <h3 class="text-lg font-bold text-slate-800">{{ item.name }}</h3>
              <p class="text-sm text-gray-500">Producto digital</p>
            </div>
          </div>

          <div class="flex items-center gap-6">
            <span class="font-bold text-slate-900">{{
              item.price_formatted
            }}</span>
            <button
              @click="cartStore.removeFromCart(item.id)"
              class="text-red-500 hover:text-red-700 transition-colors p-2"
              title="Eliminar del carrito"
            >
              <Trash2 class="w-5 h-5" />
            </button>
          </div>
        </li>
      </ul>

      <div class="border-t border-gray-200 pt-6 flex flex-col items-end">
        <div class="flex justify-between w-full sm:w-1/2 text-xl mb-6">
          <span class="font-medium text-slate-700">Total a pagar:</span>
          <span class="font-bold text-slate-900">{{
            cartStore.totalPriceFormatted
          }}</span>
        </div>

        <button
          @click="handleCheckout"
          :disabled="isProcessing"
          class="w-full sm:w-auto bg-slate-900 text-white px-8 py-3 rounded-md font-medium hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
        >
          <span
            v-if="isProcessing"
            class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"
          ></span>
          {{
            isProcessing
              ? "Redirigiendo a Stripe..."
              : "Proceder al pago seguro"
          }}
        </button>
      </div>
    </div>
  </div>
</template>
