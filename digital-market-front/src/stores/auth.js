import { defineStore } from "pinia";
import { ref, computed } from "vue";
import api from "../lib/axios";

export const useAuthStore = defineStore("auth", () => {
  // estado
  const user = ref(null);
  const token = ref(localStorage.getItem("auth_token") || null);

  // getters
  const isAuthenticated = computed(() => !!token.value);

  // mutaciones privadas
  const setAuth = (userData, authToken) => {
    user.value = userData;
    token.value = authToken;
    localStorage.setItem("auth_token", authToken);
  };

  const clearAuth = () => {
    user.value = null;
    token.value = null;
    localStorage.removeItem("auth_token");
  };

  // acciones públicas
  const login = async (credentials) => {
    const response = await api.post("/login", credentials);
    setAuth(response.data.user, response.data.access_token);
  };

  const register = async (userData) => {
    const response = await api.post("/register", userData);
    setAuth(response.data.user, response.data.access_token);
  };

  const logout = async () => {
    try {
      await api.post("/logout");
    } catch (error) {
      console.error("error al cerrar sesión en el servidor", error);
    } finally {
      // siempre limpiamos el frontend, aunque el servidor falle
      clearAuth();
    }
  };

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
  };
});
