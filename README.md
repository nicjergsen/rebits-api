# Rebits API

## Descripción

Rebits API es un sistema básico de mantención de vehículos que permite la gestión de vehículos y usuarios, incluyendo la capacidad de cambiar el dueño de un vehículo y mantener un histórico de dueños.

El sistema está conformado por una API Rest desarrollada en Laravel y por parte del front se desarrolla una SPA usando VueJs que consume esta API.

## Tecnologías

- **Backend:** Laravel 8
- **Frontend:** Vue.js

## Instalación

Sigue estos pasos para instalar y configurar el proyecto en tu entorno local:

1. **Clona el repositorio:**

   ```bash
   git clone https://github.com/nicjergsen/rebits-api.git
   cd rebits-api

2. **Instala las dependencias:**

    ```bash
    composer install
    npm install

3. **Copia el archivo de configuración de ejemplo y configura tu entorno:**

    ```bash
    cp .env.example .env

- Edita el archivo .env con las credenciales de tu base de datos y las del servidor de correo.

4. **Genera la clave de la app:**
    ```bash
    php artisan key:generate

5. **Realiza las migraciones:**
    ```bash
    php artisan migrate

6. **Compila los assets del frontend:**
    ```bash
    npm run dev


## Uso

### Funcionalidades Principales

1. **CRUD de Vehículos:**
   - Crear, leer, actualizar y eliminar vehículos (soft deleting).
   - Atributos del vehículo:
     - `Id` (numérico)
     - `Marca` (string)
     - `Modelo` (string)
     - `Patente` (string)
     - `Año` (numérico)
     - `Dueño` (usuario)
     - `Precio` (numérico)

2. **CRUD de Usuarios:**
   - Crear, leer, actualizar y eliminar usuarios  (soft deleting).
   - Atributos del usuario:
     - `Id` (numérico)
     - `Nombre` (string)
     - `Apellidos` (string)
     - `Correo` (string)

3. **Cambio de Dueño de Vehículo:**
   - Permite cambiar el dueño de un vehículo en la edición de éste.
   - Mantiene un histórico de los dueños de vehículos en una tabla de históricos.

4. **Carga de Usuarios y Vehículos desde un archivo Excel:**
   - Formato del archivo Excel:
     - `Nombre` (usuario)
     - `Apellidos` (usuario)
     - `Correo` (usuario)
     - `Marca` (Vehículo)
     - `Modelo` (Vehículo)
     - `Patente` (Vehículo)
     - `Año` (Vehículo)
     - `Precio` (Vehículo)
   - Validaciones y notificaciones:
     - En caso de que el usuario exista, sólo se asocia el vehículo.
     - Valida que el vehículo no se encuentre ya cargado (por patente).
     - Al completar la carga de excel, se envía un correo a `support@rebits.cl` informando si se realizó con éxito o si hubo errores.

### Consideraciones
    - Utilizar Laravel en versión 8 o superior. ✔️
    - Realizar la función DELETE es opcional, pero en caso de generarla utilizar soft deleting de Laravel. ✔️
    - Utilizar algún framework de frontend (idealmente VueJS o Livewire). ✔️
    - Subir sistema a Github, Bitbucket u otro repositorio GIT. ✔️
    - Generar al menos una prueba unitaria en el código. ✔️

## Prueba Unitaria

### Actualización de Vehículo y Cambio de Propietario
Esta prueba unitaria valida el proceso de actualización de un vehículo en el sistema, incluyendo el cambio de propietario. El escenario comienza creando dos usuarios y un vehículo asociado al primer usuario. Luego, se simula la actualización de los datos del vehículo, cambiando la marca, modelo, patente, año, precio y asociando el vehículo al segundo usuario mediante su correo electrónico. La prueba verifica que la actualización se realice correctamente y que el historial de dueños refleje correctamente el cambio de propietario.

Para realizar la prueba hay que ejecutar el codigo:

    ```bash
    php artisan test --filter=UpdateVehicleTest

