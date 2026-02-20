import { defineStore } from "pinia";
import { ref, computed } from "vue";
import api from "../lib/axios";

// Funciones auxiliares para evitar que "undefined" rompa la app
const getStoredUser = () => {
  try {
    const item = localStorage.getItem("auth_user");
    return item && item !== "undefined" ? JSON.parse(item) : null;
  } catch (error) {
    return null;
  }
};

const getStoredToken = () => {
  const item = localStorage.getItem("auth_token");
  return item && item !== "undefined" ? item : null;
};

export const useAuthStore = defineStore("auth", () => {
  // estado
  const user = ref(getStoredUser());
  const token = ref(getStoredToken());

  // getters
  const isAuthenticated = computed(() => !!token.value);

  // acciones
  const login = async (credentials) => {
    const response = await api.post("/login", credentials);

    const actualToken = response.data.token || response.data.access_token;

    token.value = actualToken;
    user.value = response.data.user;

    localStorage.setItem("auth_token", actualToken);
    localStorage.setItem("auth_user", JSON.stringify(user.value));

    api.defaults.headers.common["Authorization"] = `Bearer ${actualToken}`;
  };

  const register = async (data) => {
    const response = await api.post("/register", data);

    const actualToken = response.data.token || response.data.access_token;

    token.value = actualToken;
    user.value = response.data.user;

    localStorage.setItem("auth_token", actualToken);
    localStorage.setItem("auth_user", JSON.stringify(user.value));

    api.defaults.headers.common["Authorization"] = `Bearer ${actualToken}`;
  };

  const logout = async () => {
    try {
      if (token.value) {
        await api.post("/logout");
      }
    } catch (error) {
      console.error("Error al cerrar sesión", error);
    } finally {
      token.value = null;
      user.value = null;
      localStorage.removeItem("auth_token");
      localStorage.removeItem("auth_user");
      delete api.defaults.headers.common["Authorization"];
    }
  };

  return { user, token, isAuthenticated, login, register, logout };
});
