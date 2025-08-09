import React, { useState, useEffect } from "react";
import {
  Box,
  Button,
  TextField,
  Paper,
  Typography,
  Alert,
  CircularProgress,
} from "@mui/material";
import { createLocation } from "../services/locationService";

interface LocationFormProps {
  onSuccess: () => void;
}

const LocationForm: React.FC<LocationFormProps> = ({ onSuccess }) => {
  const [name, setName] = useState("");
  const [code, setCode] = useState("");
  const [image, setImage] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

useEffect(() => {
  let timer: ReturnType<typeof setTimeout>;
  if (success) {
    timer = setTimeout(() => setSuccess(null), 5000);
  }
  return () => {
    if (timer) clearTimeout(timer);
  };
}, [success]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);
    setLoading(true);

    try {
      await createLocation({ name, code, image });
      setSuccess("Sede creada correctamente");
      setName("");
      setCode("");
      setImage("");
      onSuccess();
    } catch (err: any) {
      setError(err.message || "Error al crear la sede");
    } finally {
      setLoading(false);
    }
  };

  return (
    <Paper sx={{ p: 3, mb: 3 }}>
      <Typography variant="h6" gutterBottom>
        Crear nueva sede
      </Typography>
      {error && (
        <Alert severity="error" sx={{ mb: 2 }}>
          {error}
        </Alert>
      )}
      {success && (
        <Alert severity="success" sx={{ mb: 2 }}>
          {success}
        </Alert>
      )}
      <Box component="form" onSubmit={handleSubmit}>
        <TextField
          label="Nombre"
          value={name}
          onChange={(e) => setName(e.target.value)}
          fullWidth
          margin="normal"
          required
          disabled={loading}
        />
        <TextField
          label="Código"
          value={code}
          onChange={(e) => setCode(e.target.value)}
          fullWidth
          margin="normal"
          required
          disabled={loading}
        />
        <TextField
          label="URL de Imagen"
          value={image}
          onChange={(e) => setImage(e.target.value)}
          fullWidth
          margin="normal"
          disabled={loading}
        />
        <Button
          type="submit"
          variant="contained"
          color="primary"
          sx={{ mt: 2 }}
          disabled={loading}
          startIcon={
            loading ? <CircularProgress size={20} color="inherit" /> : null
          }
        >
          {loading ? "Creando..." : "Crear"}
        </Button>
      </Box>
    </Paper>
  );
};

export default LocationForm;
