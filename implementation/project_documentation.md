# Documentación Técnica - Dominican Travel

## 1. Introducción
Dominican Travel es una plataforma integral de gestión turística diseñada para agencias de viajes. El sistema permite la gestión de paquetes, excursiones, traslados y la creación de cotizaciones profesionales para clientes finales.

## 2. Arquitectura del Sistema
El proyecto sigue el patrón de diseño **MVC (Modelo-Vista-Controlador)**, lo que separa la lógica de negocio de la interfaz de usuario.

### Estructura de Directorios
- **/app**: Corazón de la aplicación.
  - **/controllers**: Gestionan las peticiones y coordinan la lógica entre modelos y vistas.
  - **/models**: Interactúan con la base de datos y contienen las reglas de negocio.
  - **/views**: Plantillas HTML/PHP que se muestran al usuario.
  - **/core**: Clases base del sistema (Router, Controller, Model, Database, Translator).
  - **/middleware**: Capas de seguridad y validación (AuthMiddleware).
- **/config**: Archivos de configuración global y conexión a DB.
- **/public**: Único punto de acceso público.
  - **index.php**: Front Controller que inicia la aplicación.
  - **/assets**: Recursos estáticos (CSS, JS, Imágenes).
  - **/uploads**: Archivos subidos por el usuario (logos, imágenes de paquetes).

## 3. Seguridad del Sistema
La seguridad ha sido un pilar fundamental en el desarrollo del proyecto.

### A. Autenticación y Autorización
- **Gestión de Sesiones**: Uso de sesiones PHP seguras para mantener el estado del usuario.
- **Middleware de Protección**: Rutas administrativas protegidas por `AuthMiddleware`, que verifica el rol y estado activo del usuario antes de permitir el acceso.
- **Roles de Usuario**: Diferenciación clara entre `admin`, `agent` y `client`.

### B. Protección de Datos (SQL Injection)
- **PDO (PHP Data Objects)**: Se utilizan sentencias preparadas para todas las consultas a la base de datos.
- **Parámetros Vinculados**: Nunca se concatenan variables directamente en SQL, eliminando el riesgo de inyección SQL.

### C. Seguridad de Contraseñas
- **Hashing**: Las contraseñas se almacenan cifradas utilizando el algoritmo `BCRYPT` (vía `password_hash`).
- **Validación Robusta**: Implementación de políticas de complejidad (Longitud, mayúsculas, números y caracteres especiales).

### D. Seguridad en Formularios (CSRF)
- **Tokens CSRF**: Generación y validación de tokens únicos por sesión para prevenir ataques de falsificación de peticiones en sitios cruzados.

### E. Prevención de XSS (Cross-Site Scripting)
- **Sanitización de Salida**: Uso sistemático de `htmlspecialchars()` en todas las vistas para evitar la ejecución de scripts maliciosos inyectados por usuarios.

## 4. Módulos Principales

### Módulo de Cotizaciones (Premium)
- **Cálculo Dinámico**: Gestión de Subtotal, ITBIS (18% ajustable) y Total en tiempo real.
- **Buscadores Inteligentes**: Integración de `Choices.js` para búsquedas rápidas en listas de clientes y servicios.
- **Términos y Condiciones**: Textos legales predefinidos y editables para mayor profesionalidad.

### Letrero de Aeropuerto (Herramienta Standalone)
- Herramienta para generar carteles de bienvenida con el logo de la empresa.
- **Interactividad**: Permite editar el nombre del cliente directamente en la vista antes de imprimir.
- **Optimización de Impresión**: Diseño que oculta controles de interfaz en el papel impreso.

### Panel de Administración (Dashboard)
- Métricas en tiempo real de ventas, clientes y solicitudes.
- Gestión completa de inventario (Paquetes, Excursiones, Traslados).

## 5. Especificaciones Técnicas
- **Lenguaje**: PHP 8.1+
- **Base de Datos**: MySQL / MariaDB
- **Frontend**: Vanilla CSS, JavaScript (ES6+), FontAwesome 6, Google Fonts (Sora/Outfit).
- **Traducciones**: Sistema de i18n mediante archivos de idioma (ES/EN).

---
*Documentación generada el 22 de Abril de 2026 para el equipo de Dominican Travel.*
