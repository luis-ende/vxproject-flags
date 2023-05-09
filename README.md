### VX Project Flags

# Proyecto VX Project Sitio Principal

## Requerimientos

- Laravel 10 (Vite) / Blade Components
- PostgreSQL 15
- [Tailwind 3](https://tailwindcss.com/docs/installation)
- Vue.js 3
- Laravel Homestead (Ambiente de desarrollo)

## Ambiente de desarrollo

### Compilación de assets

- Para generar los assets desde la carpeta raíz del proyecto ejecutar: `npm run build`
    - Eventualmente puede ser necesario ejecutar primero `npm install`
- `npm run dev` permite ver los cambios realizados en el código en el navegador sin tener que compilar los assets cada vez
- Run `npm run dev` desde el host (no usando el comando sail)

### Paquetes utilizados

- Traducciones de mensajes Laravel: [https://laravel-lang.com/](https://laravel-lang.com/)
- Íconos svg en templates Balde: Blade UI Kit - [https://github.com/blade-ui-kit/blade-icons](https://github.com/blade-ui-kit/blade-icons)
- Gestión de fuentes Google: Laravel Google Fonts - [https://github.com/spatie/laravel-google-fonts](https://github.com/spatie/laravel-google-fonts) 