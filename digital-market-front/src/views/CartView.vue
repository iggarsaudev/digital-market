<script setup>
import { useCartStore } from "../stores/cart";
import { Trash2 } from "lucide-vue-next";

const cartStore = useCartStore();
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
          class="w-full sm:w-auto bg-slate-900 text-white px-8 py-3 rounded-md font-medium hover:bg-slate-800 transition-colors"
        >
          Proceder al pago seguro
        </button>
      </div>
    </div>
  </div>
</template>
