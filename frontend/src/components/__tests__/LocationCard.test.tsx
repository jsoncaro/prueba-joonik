/**
 * @jest-environment jsdom
 */
import { render, screen } from "@testing-library/react";
import LocationCard from "../LocationCard";

describe("LocationCard", () => {
    const locationWithImage = {
        id: 1,
        name: "Sede Principal",
        code: "SP001",
        image: "https://picsum.photos/300/200",
    };

    const locationWithoutImage = {
        id: 2,
        name: "Sucursal Secundaria",
        code: "SS002",
    };

    test("muestra el nombre y código de la ubicación", () => {
        render(<LocationCard location={locationWithImage} />);
        expect(screen.getByText("Sede Principal")).toBeInTheDocument();
        expect(screen.getByText("SP001")).toBeInTheDocument();
    });

    test("muestra la imagen si location.image existe", () => {
        render(<LocationCard location={locationWithImage} />);
        const img = screen.getByRole("img", { name: /sede principal/i });
        expect(img).toBeInTheDocument();
        expect(img).toHaveAttribute("src", locationWithImage.image);
    });

    test("no muestra imagen si location.image es undefined", () => {
        render(<LocationCard location={locationWithoutImage} />);
        const img = screen.queryByRole("img");
        expect(img).not.toBeInTheDocument();
    });
});
