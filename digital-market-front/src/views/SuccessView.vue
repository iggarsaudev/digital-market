<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useCartStore } from "../stores/cart";
import { CheckCircle } from "lucide-vue-next";
import api from "../lib/axios";

const route = useRoute();
const cartStore = useCartStore();
const isVerifying = ref(true);

onMounted(async () => {
  // Capturamos el session_id que Stripe pone en la URL al volver
  const sessionId = route.query.session_id;

  if (sessionId) {
    // Vaciamos el carrito visualmente para el usuario
    cartStore.clearCart();

    // Avisamos al backend para que verifique y marque la orden como 'paid'
    try {
      await api.post("/checkout/verify", {
        session_id: sessionId,
      });
      // console.log("Pago verificado y registrado en la base de datos.");
    } catch (error) {
      // console.error("Error al verificar el pago en el servidor:", error);
    } finally {
      isVerifying.value = false;
    }
  } else {
    isVerifying.value = false;
  }
});
</script>

<template>
  <div class="max-w-2xl mx-auto text-center py-20">
    <CheckCircle class="w-24 h-24 text-emerald-500 mx-auto mb-6" />

    <h1 class="text-4xl font-extrabold text-slate-900 mb-4">
      ¡Pago Completado!
    </h1>

    <p class="text-lg text-slate-600 mb-8">
      Gracias por tu compra. Tu pedido ha sido procesado correctamente y tus
      productos digitales ya están listos.
    </p>

    <router-link
      to="/"
      class="bg-indigo-600 text-white px-8 py-3 rounded-md font-medium hover:bg-indigo-700 transition-colors inline-block"
    >
      Volver a la tienda
    </router-link>
  </div>
</template>
