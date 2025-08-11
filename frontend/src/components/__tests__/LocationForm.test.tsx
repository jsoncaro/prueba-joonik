/**
 * @jest-environment jsdom
 */
import { render, screen, fireEvent, waitFor, act } from "@testing-library/react";
import LocationForm from "../LocationForm";
import * as locationService from "../../services/locationService";

// Mock del servicio createLocation
jest.mock("../../services/locationService");

describe("LocationForm", () => {
    const mockCreateLocation = locationService.createLocation as jest.MockedFunction<typeof locationService.createLocation>;
    const onSuccessMock = jest.fn();

    beforeEach(() => {
        jest.clearAllMocks();
    });

    test("renderiza campos y botón correctamente", () => {
        render(<LocationForm onSuccess={onSuccessMock} />);

        expect(screen.getByLabelText(/Nombre/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/Código/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/URL de Imagen/i)).toBeInTheDocument();
        expect(screen.getByRole("button", { name: /Crear/i })).toBeInTheDocument();
    });

    test("actualiza estado al cambiar inputs", () => {
        render(<LocationForm onSuccess={onSuccessMock} />);
        const nombreInput = screen.getByLabelText(/Nombre/i);
        const codigoInput = screen.getByLabelText(/Código/i);
        const imagenInput = screen.getByLabelText(/URL de Imagen/i);

        fireEvent.change(nombreInput, { target: { value: "Sede 1" } });
        fireEvent.change(codigoInput, { target: { value: "ABC123" } });
        fireEvent.change(imagenInput, { target: { value: "http://img.jpg" } });

        expect(nombreInput).toHaveValue("Sede 1");
        expect(codigoInput).toHaveValue("ABC123");
        expect(imagenInput).toHaveValue("http://img.jpg");
    });

    test("envía formulario con éxito y limpia campos, llama onSuccess", async () => {
        mockCreateLocation.mockResolvedValueOnce(undefined);
        render(<LocationForm onSuccess={onSuccessMock} />);

        fireEvent.change(screen.getByLabelText(/Nombre/i), { target: { value: "Sede 1" } });
        fireEvent.change(screen.getByLabelText(/Código/i), { target: { value: "ABC123" } });
        fireEvent.change(screen.getByLabelText(/URL de Imagen/i), { target: { value: "http://img.jpg" } });

        fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

        // Espera que el loading desaparezca y el mensaje éxito aparezca
        await waitFor(() => {
            expect(screen.getByText(/Sede creada correctamente/i)).toBeInTheDocument();
        });

        // Los inputs deben estar vacíos
        expect(screen.getByLabelText(/Nombre/i)).toHaveValue("");
        expect(screen.getByLabelText(/Código/i)).toHaveValue("");
        expect(screen.getByLabelText(/URL de Imagen/i)).toHaveValue("");

        // onSuccess debe haberse llamado
        expect(onSuccessMock).toHaveBeenCalled();
    });

    test("muestra error si createLocation falla", async () => {
        mockCreateLocation.mockRejectedValueOnce(new Error("Error de red"));
        render(<LocationForm onSuccess={onSuccessMock} />);

        fireEvent.change(screen.getByLabelText(/Nombre/i), { target: { value: "Sede 1" } });
        fireEvent.change(screen.getByLabelText(/Código/i), { target: { value: "ABC123" } });

        fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

        await waitFor(() => {
            expect(screen.getByText(/Error de red/i)).toBeInTheDocument();
        });

        // onSuccess NO se llama en error
        expect(onSuccessMock).not.toHaveBeenCalled();
    });

    test("deshabilita inputs y muestra loading mientras crea", async () => {
        let resolvePromise: () => void;
        const promise = new Promise<void>((resolve) => {
            resolvePromise = resolve;
        });
        mockCreateLocation.mockReturnValueOnce(promise);

        render(<LocationForm onSuccess={onSuccessMock} />);

        fireEvent.change(screen.getByLabelText(/Nombre/i), { target: { value: "Sede 1" } });
        fireEvent.change(screen.getByLabelText(/Código/i), { target: { value: "ABC123" } });

        fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

        expect(screen.getByLabelText(/Nombre/i)).toBeDisabled();
        expect(screen.getByLabelText(/Código/i)).toBeDisabled();
        expect(screen.getByRole("button", { name: /Creando.../i })).toBeDisabled();

        // Resolvemos la promesa para que termine
        act(() => {
            resolvePromise!();
        });

        await waitFor(() => {
            expect(screen.getByRole("button", { name: /Crear/i })).not.toBeDisabled();
        });
    });

    test("oculta mensaje de éxito después de 5 segundos", async () => {
        jest.useFakeTimers();
        mockCreateLocation.mockResolvedValueOnce(undefined);
        render(<LocationForm onSuccess={onSuccessMock} />);

        fireEvent.change(screen.getByLabelText(/Nombre/i), { target: { value: "Sede 1" } });
        fireEvent.change(screen.getByLabelText(/Código/i), { target: { value: "ABC123" } });

        fireEvent.click(screen.getByRole("button", { name: /Crear/i }));

        await waitFor(() => {
            expect(screen.getByText(/Sede creada correctamente/i)).toBeInTheDocument();
        });

        // Envuelve el avance de timers en act para evitar warning
        act(() => {
            jest.advanceTimersByTime(5000);
        });

        await waitFor(() => {
            expect(screen.queryByText(/Sede creada correctamente/i)).not.toBeInTheDocument();
        });

        jest.useRealTimers();
    });
});
