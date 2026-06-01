<script setup>
import { ref, onMounted } from "vue";
import api from "../lib/axios";
import { Package } from "lucide-vue-next";

const orders = ref([]);
const isLoading = ref(true);

onMounted(async () => {
  try {
    // Axios se encarga mágicamente de enviar tu Token de seguridad
    const response = await api.get("/user/orders");
    orders.value = response.data;
  } catch (error) {
    console.error("Error al obtener el historial:", error);
  } finally {
    isLoading.value = false;
  }
});

// Función para formatear el dinero
const formatPrice = (priceInCents) => {
  return new Intl.NumberFormat("es-ES", {
    style: "currency",
    currency: "EUR",
  }).format(priceInCents / 100);
};

// Función para formatear la fecha
const formatDate = (dateString) => {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("es-ES", options);
};
</script>

<template>
  <div class="max-w-4xl mx-auto py-10 px-4">
    <h1
      class="text-3xl font-extrabold text-slate-900 mb-8 flex items-center gap-3"
    >
      <Package class="w-8 h-8 text-indigo-600" />
      Mis Compras
    </h1>

    <div v-if="isLoading" class="text-center py-10 text-slate-500">
      Cargando tu historial...
    </div>

    <div
      v-else-if="orders.length === 0"
      class="text-center py-10 bg-slate-50 rounded-lg border border-slate-200"
    >
      <p class="text-slate-600 mb-4">
        Aún no has comprado ningún producto digital.
      </p>
      <router-link to="/" class="text-indigo-600 font-medium hover:underline">
        Ir a la tienda
      </router-link>
    </div>

    <div v-else class="space-y-6">
      <div
        v-for="order in orders"
        :key="order.id"
        class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm"
      >
        <div
          class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center flex-wrap gap-4"
        >
          <div>
            <p class="text-sm text-slate-500 mb-1">Pedido realizado el</p>
            <p class="font-medium text-slate-900">
              {{ formatDate(order.created_at) }}
            </p>
          </div>
          <div>
            <p class="text-sm text-slate-500 mb-1">Total Pagado</p>
            <p class="font-bold text-slate-900">
              {{ formatPrice(order.total_price) }}
            </p>
          </div>
          <div class="text-right">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800"
            >
              Completado
            </span>
          </div>
        </div>

        <ul class="divide-y divide-slate-200">
          <li
            v-for="item in order.items"
            :key="item.id"
            class="px-6 py-4 flex items-center justify-between"
          >
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 bg-slate-200 rounded object-cover flex-shrink-0"
              ></div>
              <div>
                <h4 class="text-lg font-medium text-slate-900">
                  {{ item.product?.name || "Producto Digital" }}
                </h4>
              </div>
            </div>

            <button
              class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded font-medium hover:bg-indigo-100 transition-colors text-sm"
            >
              Descargar Archivo
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
