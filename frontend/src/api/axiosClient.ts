import axios from 'axios';

/* const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'X-API-KEY': import.meta.env.VITE_API_KEY,
  },
}); */

//Cambio para tener compatibilidad con los test unitarios
const axiosClient = axios.create({
  baseURL: "http://localhost:8000/api/v1",
  headers: {
    'Content-Type': 'application/json',
    'X-API-KEY': "b05bf18e70585c6f37c34cf758ad777b",
  },
});

axiosClient.interceptors.response.use(
  (response) => response,
  (error) => {
    const message = error.response?.data?.message || error.response?.data?.error?.message || 'Error desconocido';
    return Promise.reject({ message, status: error.response?.status });
  },
);

export default axiosClient;
