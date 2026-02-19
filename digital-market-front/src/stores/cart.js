import { defineStore } from "pinia";
import { ref, computed, watch } from "vue";
import { toast } from "vue3-toastify";

export const useCartStore = defineStore("cart", () => {
  // Recuperamos del localStorage si existe, si no, array vacío
  const items = ref(JSON.parse(localStorage.getItem("cart_items")) || []);

  // Cada vez que 'items' cambie, guardamos en localStorage
  watch(
    items,
    (newItems) => {
      localStorage.setItem("cart_items", JSON.stringify(newItems));
    },
    { deep: true },
  );

  // Cálculos automáticos
  const totalItems = computed(() => items.value.length);

  const totalPrice = computed(() => {
    return items.value.reduce((total, item) => total + item.price, 0);
  });

  // Formateamos el total a Euros para mostrarlo
  const totalPriceFormatted = computed(() => {
    return new Intl.NumberFormat("es-ES", {
      style: "currency",
      currency: "EUR",
    }).format(totalPrice.value / 100);
  });

  // Acciones
  const addToCart = (product) => {
    // Comprobamos si ya existe en el carrito
    const exists = items.value.find((item) => item.id === product.id);
    if (exists) {
      toast.info("Este producto ya está en tu carrito.");
      return;
    }

    items.value.push(product);
    toast.success(`Añadido al carrito`);
  };

  const removeFromCart = (productId) => {
    items.value = items.value.filter((item) => item.id !== productId);
    toast.info("Producto eliminado del carrito");
  };

  const clearCart = () => {
    items.value = [];
  };

  return {
    items,
    totalItems,
    totalPrice,
    totalPriceFormatted,
    addToCart,
    removeFromCart,
    clearCart,
  };
});
