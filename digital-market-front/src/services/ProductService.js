import api from "../lib/axios";

export default {
  // Obtener todos los productos
  async getProducts() {
    return await api.get("/products");
  },

  // Obtener un solo producto
  async getProduct(slug) {
    return await api.get(`/products/${slug}`);
  },
};
