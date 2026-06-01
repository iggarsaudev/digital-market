import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
  headers: {
    Accept: "application/json",
  },
});

// interceptor: se ejecuta ANTES de que la petición salga hacia laravel
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("auth_token");

  // si tenemos un token guardado, lo inyectamos en la cabecera
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

export default api;
