import axios from 'axios';

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'X-API-KEY': import.meta.env.VITE_API_KEY,
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
