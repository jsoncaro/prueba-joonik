
# Prueba Técnica para Desarrollador Full Stack (Senior) - Laravel, React, TypeScript

---

## 1. Requisitos y configuración de entorno

### Requisitos

- **General:**
  - Git
  - Docker y Docker Compose (opcional, recomendado)
- **Backend:**
  - PHP 8.1+
  - Composer
  - SQLite (ya configurado en Laravel)
- **Frontend:**
  - Node.js 18+ (recomendado)
  - npm o yarn

### Configuración del entorno

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/jsoncaro/prueba-joonik.git
   cd prueba-joonik
   ```

2. Backend:
   - Copiar archivo de entorno:
     ```bash
     cp backend/.env.example backend/.env
     ```
   - Configurar las variables importantes en `backend/.env`, por ejemplo:
     ```
     DB_CONNECTION=sqlite
     API_KEY=tu_api_key_secreta
     ```
   - Crear base de datos SQLite:
     ```bash
     touch backend/database/database.sqlite
     ```
   - Instalar dependencias y preparar la base de datos:
     ```bash
     cd backend
     composer install
     php artisan key:generate
     php artisan migrate --seed
     ```

3. Frontend:
   - Copiar archivo de entorno:
     ```bash
     cp frontend/.env.example frontend/.env
     ```
   - Configurar las variables en `frontend/.env`, como:
     ```
     VITE_API_BASE_URL=http://127.0.0.1:8000/api/v1
     VITE_API_KEY=b05bf18e70585c6f37c34cf758ad777b
     ```
   - Instalar dependencias:
     ```bash
     cd ../frontend
     npm install
     ```

---

## 2. Comandos para levantar backend y frontend

### Backend

- Levantar servidor Laravel local:
  ```bash
  cd backend
  php artisan serve
  ```
  > URL por defecto: http://127.0.0.1:8000

- Comandos útiles adicionales:
  - Ejecutar migraciones y seeders:
    ```bash
    php artisan migrate --seed
    ```
  - Ejecutar linters y análisis estático:
    ```bash
    composer lint
    composer test
    ```

### Frontend

- Levantar servidor de desarrollo React con Vite:
  ```bash
  cd frontend
  npm run dev
  ```
  > URL por defecto: http://localhost:3000

- Comandos útiles adicionales:
  - Ejecutar lint:
    ```bash
    npm run lint
    ```
  - Ejecutar tests:
    ```bash
    npm test
    ```
  - Construir versión para producción:
    ```bash
    npm run build
    ```

---

## 3. Uso de API Key y rutas versionadas

- Todas las peticiones HTTP a la API deben incluir el header:

  ```
  X-API-KEY: <tu_api_key>
  ```

- Las rutas de la API están versionadas bajo el prefijo `/api/v1/`. Algunos endpoints:

  | Método | Ruta                 | Descripción                      |
  |--------|----------------------|---------------------------------|
  | GET    | `/api/v1/locations`  | Listar sedes (paginado y filtrado) |
  | POST   | `/api/v1/locations`  | Crear nueva sede                |

- El middleware del backend valida la API Key para autenticar las solicitudes.

---

## 4. Cómo correr los tests

### Backend

- Ejecutar tests con PHPUnit:
  ```bash
  cd backend
  php artisan test --coverage
  ```

- Ejecutar linters y análisis estático:
  ```bash
  composer lint
  composer test
  ```

### Frontend

- Ejecutar tests con Jest y React Testing Library:
  ```bash
  cd frontend
  npm test
  npm test -- --coverage
  ```

- Ejecutar linters:
  ```bash
  npm run lint
  ```

---

## Estructura del repositorio

```
/backend        # Backend Laravel 12 API REST
/frontend       # Frontend React + TypeScript + Vite
/scripts       # Scripts para automatización, docker-compose, etc
/docs          # Documentación adicional
```

---

## Notas finales

- Se recomienda el uso de Docker Compose para levantar ambos servicios simultáneamente (opcional).
- Los commits deben seguir la convención [Conventional Commits](https://www.conventionalcommits.org/).
- La base de datos SQLite se utiliza para facilitar la configuración y pruebas locales.
- Para pruebas de producción se puede configurar un archivo `.env.production` adecuado.
- Mantener las dependencias actualizadas para evitar vulnerabilidades.

---

Si tienes dudas o sugerencias, no dudes en abrir un issue o contactar al autor del repositorio.
