import axiosClient from "../api/axiosClient";

export interface LocationFilters {
  name?: string;
  code?: string;
  page?: number;
  per_page?: number;
}

export interface LocationPayload {
  name: string;
  code: string;
  image: string;
}

export const getLocations = async (filters?: LocationFilters) => {
  const response = await axiosClient.get('/locations', { params: filters });
  return response.data;
};

export const createLocation = async (payload: LocationPayload) => {
  const response = await axiosClient.post('/locations', payload);
  return response.data?.data ?? response.data;
};
