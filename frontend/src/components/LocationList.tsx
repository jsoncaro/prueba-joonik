import React, { useEffect, useState } from 'react';
import {
  Container,
  Typography,
  TextField,
  Button,
  CircularProgress,
  Alert,
  Box,
  Stack,
  TablePagination
} from '@mui/material';
import LocationCard from './LocationCard';
import LocationForm from './LocationForm';
import { getLocations } from '../services/locationService';

interface Location {
  id: number;
  name: string;
  code: string;
  image?: string;
}

const LocationList: React.FC = () => {
  const [locations, setLocations] = useState<Location[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);
  const [filters, setFilters] = useState<{ name: string; code: string }>({
    name: '',
    code: '',
  });

  const [page, setPage] = useState(0);
  const [rowsPerPage, setRowsPerPage] = useState(5);
  const [total, setTotal] = useState(0);

  const fetchLocations = async (useFilters = true, newPage = page, newRowsPerPage = rowsPerPage) => {
    setLoading(true);
    setError(null);
    try {
      const params = {
        ...(useFilters && filters.name && { name: filters.name }),
        ...(useFilters && filters.code && { code: filters.code }),
        page: newPage + 1,
        per_page: newRowsPerPage
      };
      const data = await getLocations(params);

      if (data?.data && typeof data.total === 'number') {
        setLocations(data.data);
        setTotal(data.total);
      } else {
        setLocations(Array.isArray(data) ? data : []);
        setTotal(Array.isArray(data) ? data.length : 0);
      }
    } catch (err: any) {
      setError(err?.message || 'Error al cargar las sedes');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchLocations(false);
  }, []);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFilters({ ...filters, [e.target.name]: e.target.value });
  };

  const handleSearch = () => {
    setPage(0);
    fetchLocations(true, 0, rowsPerPage);
  };

  const handleClear = () => {
    setFilters({ name: '', code: '' });
    setPage(0);
    fetchLocations(false, 0, rowsPerPage);
  };

  const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter') handleSearch();
  };

  const handleChangePage = (_: unknown, newPage: number) => {
    setPage(newPage);
    fetchLocations(true, newPage, rowsPerPage);
  };

  const handleChangeRowsPerPage = (event: React.ChangeEvent<HTMLInputElement>) => {
    const newRows = parseInt(event.target.value, 10);
    setRowsPerPage(newRows);
    setPage(0);
    fetchLocations(true, 0, newRows);
  };

  return (
    <Container sx={{ py: 3 }}>
      {/* Form para crear nueva sede */}
      <LocationForm onSuccess={() => fetchLocations(true)} />

      <Typography variant="h4" gutterBottom>
        Lista de Sedes
      </Typography>

      {/* Filtros */}
      <Stack direction={{ xs: 'column', sm: 'row' }} spacing={2} sx={{ mb: 2 }}>
        <TextField
          label="Filtrar por nombre"
          name="name"
          value={filters.name}
          onChange={handleChange}
          onKeyDown={handleKeyDown}
          fullWidth
        />
        <TextField
          label="Filtrar por código"
          name="code"
          value={filters.code}
          onChange={handleChange}
          onKeyDown={handleKeyDown}
          fullWidth
        />
        <Button variant="contained" onClick={handleSearch}>
          Buscar
        </Button>
        <Button variant="outlined" onClick={handleClear}>
          Limpiar
        </Button>
      </Stack>

      {/* Estados */}
      {loading ? (
        <Box display="flex" justifyContent="center" sx={{ mt: 4 }}>
          <CircularProgress />
        </Box>
      ) : error ? (
        <Alert severity="error">{error}</Alert>
      ) : locations.length === 0 ? (
        <Alert severity="info">No se encontraron sedes</Alert>
      ) : (
        <>
          {/* Grid de tarjetas */}
          <Box
            sx={{
              display: 'grid',
              gap: 2,
              gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))',
              mt: 2,
            }}
          >
            {locations.map((loc) => (
              <LocationCard key={loc.id} location={loc} />
            ))}
          </Box>

          {/* Paginación */}
          <TablePagination
            component="div"
            count={total}
            page={page}
            onPageChange={handleChangePage}
            rowsPerPage={rowsPerPage}
            onRowsPerPageChange={handleChangeRowsPerPage}
            labelRowsPerPage="Filas por página"
          />
        </>
      )}
    </Container>
  );
};

export default LocationList;
