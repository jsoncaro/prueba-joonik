/**
 * @jest-environment jsdom
 */
import { render, screen, waitFor } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import LocationList from '../LocationList';
import * as locationService from '../../services/locationService';

jest.mock('../../services/locationService');

const mockGetLocations = locationService.getLocations as jest.MockedFunction<typeof locationService.getLocations>;

const mockLocations = [
  { id: 1, name: 'Sede A', code: 'A01', image: 'imgA.jpg' },
  { id: 2, name: 'Sede B', code: 'B02', image: 'imgB.jpg' },
];

describe('LocationList', () => {
  beforeEach(() => {
    mockGetLocations.mockReset();
    mockGetLocations.mockResolvedValue({
      data: mockLocations,
      total: 10,
    });
  });

  test('renderiza lista de sedes', async () => {
    render(<LocationList />);
    expect(screen.getByText(/Crear nueva sede/i)).toBeInTheDocument();
    expect(screen.getByText(/Lista de Sedes/i)).toBeInTheDocument();

    // Espera que se carguen las sedes y aparezcan
    await waitFor(() => {
      expect(screen.getByText('Sede A')).toBeInTheDocument();
      expect(screen.getByText('A01')).toBeInTheDocument();
      expect(screen.getByText('Sede B')).toBeInTheDocument();
      expect(screen.getByText('B02')).toBeInTheDocument();
    });
  });

  test('busca con filtros y actualiza lista', async () => {
    render(<LocationList />);

    const nameInput = screen.getByLabelText(/Filtrar por nombre/i);
    const codeInput = screen.getByLabelText(/Filtrar por código/i);
    const buscarBtn = screen.getByRole('button', { name: /Buscar/i });

    // Cambiar filtros
    await userEvent.type(nameInput, 'Sede A');
    await userEvent.type(codeInput, 'A01');

    mockGetLocations.mockResolvedValueOnce({
      data: [mockLocations[0]],
      total: 1,
    });

    // Click en buscar
    await userEvent.click(buscarBtn);

    await waitFor(() => {
      expect(mockGetLocations).toHaveBeenLastCalledWith({
        name: 'Sede A',
        code: 'A01',
        page: 1,
        per_page: 5,
      });
    });

    expect(screen.getByText('Sede A')).toBeInTheDocument();
    expect(screen.queryByText('Sede B')).not.toBeInTheDocument();
  });

  test('cambia página en paginación', async () => {
    render(<LocationList />);
    await waitFor(() => screen.getByText('Sede A'));

    const nextPageBtn = screen.getByLabelText('Go to next page');
    mockGetLocations.mockResolvedValueOnce({
      data: [mockLocations[1]],
      total: 10,
    });

    await userEvent.click(nextPageBtn);

    await waitFor(() => {
      expect(mockGetLocations).toHaveBeenLastCalledWith(
        expect.objectContaining({
          page: 2,
          per_page: 5,
        })
      );
    });

    expect(screen.getByText('Sede B')).toBeInTheDocument();
  });

  test('cambia filas por página en paginación', async () => {
    render(<LocationList />);
    await waitFor(() => screen.getByText('Sede A'));

    const rowsPerPageSelect = screen.getByRole('combobox', { name: /Filas por página/i });

    // Abrir dropdown
    await userEvent.click(rowsPerPageSelect);

    // Seleccionar opción "10"
    const option10 = await screen.findByRole('option', { name: '10' });
    await userEvent.click(option10);

    await waitFor(() => {
      expect(mockGetLocations).toHaveBeenLastCalledWith(
        expect.objectContaining({
          page: 1,
          per_page: 10,
        })
      );
    });
  });
});
