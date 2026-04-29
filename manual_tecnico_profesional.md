# DOCUMENTACIÓN TÉCNICA Y FUNCIONAL: DOMINICAN TRAVEL
**Sistema Integral de Gestión Turística y Cotizaciones Profesionales**

---

## ÍNDICE DE CONTENIDOS

1.  **CAPÍTULO 1: INTRODUCCIÓN**
    *   1.1. Propósito del Sistema
    *   1.2. Alcance del Proyecto
2.  **CAPÍTULO 2: ARQUITECTURA DE SOFTWARE**
    *   2.1. Patrón Modelo-Vista-Controlador (MVC)
    *   2.2. Estructura de Directorios y Organización de Archivos
    *   2.3. Motor de Rutas y Front Controller
3.  **CAPÍTULO 3: ESPECIFICACIONES TÉCNICAS**
    *   3.1. Tecnologías de Backend
    *   3.2. Tecnologías de Frontend
    *   3.3. Gestión de Base de Datos
4.  **CAPÍTULO 4: SEGURIDAD Y PROTECCIÓN DE INFORMACIÓN**
    *   4.1. Autenticación y Control de Accesos
    *   4.2. Prevención de Ataques Comunes (SQLi, XSS, CSRF)
    *   4.3. Cifrado de Credenciales y Datos Sensibles
5.  **CAPÍTULO 5: MÓDULOS Y FUNCIONALIDADES OPERATIVAS**
    *   5.1. Módulo de Cotizaciones Profesionales
    *   5.2. Herramienta de Señalización de Aeropuerto
    *   5.3. Progressive Web App (PWA) e Instalación Móvil
6.  **CAPÍTULO 6: INFRAESTRUCTURA Y DESPLIEGUE**
    *   6.1. Configuración de Entornos (Local y Producción)
    *   6.2. Directivas de Servidor (.htaccess)
7.  **CAPÍTULO 7: CONCLUSIÓN**

---

## CAPÍTULO 1: INTRODUCCIÓN

### 1.1. Propósito del Sistema
El sistema Dominican Travel ha sido desarrollado como una solución centralizada para la automatización de procesos operativos en agencias de viajes. Su objetivo principal es optimizar la creación de cotizaciones detalladas y la gestión de inventario turístico, proporcionando una interfaz profesional tanto para el personal administrativo como para el cliente final.

### 1.2. Alcance del Proyecto
La plataforma abarca desde la exposición de productos turísticos (paquetes, excursiones y traslados) hasta la formalización de propuestas comerciales, incluyendo una capa de movilidad que permite la instalación del sistema como aplicación nativa en dispositivos móviles.

---

## CAPÍTULO 2: ARQUITECTURA DE SOFTWARE

### 2.1. Patrón Modelo-Vista-Controlador (MVC)
El sistema se fundamenta en el patrón MVC, garantizando una separación clara entre la lógica de negocio, la persistencia de datos y la interfaz de usuario. Esta arquitectura facilita el mantenimiento escalable y la integración de nuevos módulos sin comprometer la integridad del núcleo del sistema.

### 2.2. Estructura de Directorios
*   **/app**: Contiene la lógica privada del sistema.
    *   *Controllers*: Controladores que gestionan el flujo de datos.
    *   *Models*: Clases que representan las entidades y la lógica de base de datos.
    *   *Views*: Componentes de la interfaz de usuario.
    *   *Core*: Clases fundamentales que sostienen el framework (Router, Database, Model, Controller).
*   **/public**: Directorio público y único punto de entrada para el servidor web, garantizando que el código fuente del sistema permanezca inaccesible directamente.

### 2.3. Motor de Rutas y Front Controller
El sistema implementa un Front Controller (`index.php`) que canaliza todas las peticiones a través de un motor de rutas personalizado. Esto permite el uso de URLs amigables (SEO friendly) y una gestión centralizada de la seguridad y el flujo de navegación.

---

## CAPÍTULO 3: ESPECIFICACIONES TÉCNICAS

### 3.1. Tecnologías de Backend
El desarrollo se ha realizado en **PHP 8.1+**, aprovechando las mejoras en tipado y rendimiento de las versiones recientes. La comunicación con el servidor de base de datos se realiza mediante la extensión **PDO**, garantizando compatibilidad y seguridad.

### 3.2. Tecnologías de Frontend
*   **HTML5 y CSS3**: Uso de variables CSS para la gestión de temas y diseño responsivo avanzado.
*   **JavaScript (ES6+)**: Implementación de lógica asíncrona para cálculos en tiempo real y gestión de la interfaz.
*   **Librerías especializadas**: `Choices.js` para la gestión de buscadores avanzados y `jsPDF` para la exportación de documentos.

### 3.3. Gestión de Base de Datos
Se utiliza **MySQL** como sistema de gestión de bases de datos relacionales, con una estructura normalizada que asegura la integridad referencial y el rendimiento en consultas complejas.

---

## CAPÍTULO 4: SEGURIDAD Y PROTECCIÓN DE INFORMACIÓN

### 4.1. Autenticación y Control de Accesos
El sistema implementa una gestión de sesiones robusta. El acceso a las funcionalidades administrativas está restringido mediante una capa de **Middleware**, que valida el estado de la sesión y los privilegios (roles) del usuario antes de procesar cualquier petición.

### 4.2. Prevención de Ataques Comunes
*   **SQL Injection**: Prevención total mediante el uso obligatorio de sentencias preparadas y parámetros vinculados en todas las transacciones de base de datos.
*   **Cross-Site Scripting (XSS)**: Sanitización estricta de todas las salidas de datos hacia el navegador mediante funciones de escape de caracteres.
*   **Cross-Site Request Forgery (CSRF)**: Implementación de tokens de validación únicos por sesión para asegurar que las peticiones de modificación de datos provengan exclusivamente de formularios legítimos de la aplicación.

### 4.3. Cifrado de Credenciales
Las contraseñas de los usuarios no se almacenan en texto plano. Se utiliza el algoritmo de cifrado **BCRYPT** a través de la función `password_hash()` de PHP, garantizando que la información sensible permanezca protegida incluso en caso de acceso no autorizado a la base de datos.

---

## CAPÍTULO 5: MÓDULOS Y FUNCIONALIDADES OPERATIVAS

### 5.1. Módulo de Cotizaciones Profesionales
Este módulo permite la generación de propuestas comerciales con cálculo dinámico de impuestos (ITBIS), gestión de servicios itemizados y selección de clientes mediante buscadores inteligentes. Los términos y condiciones se cargan de forma predeterminada pero conservan la capacidad de edición manual para cada caso específico.

### 5.2. Herramienta de Señalización de Aeropuerto
Funcionalidad diseñada para el personal de operaciones en aeropuertos. Permite la edición dinámica de nombres de clientes y el ajuste de dimensiones de texto en tiempo real, optimizando el diseño para una impresión profesional con la identidad visual de la agencia.

### 5.3. Progressive Web App (PWA)
La plataforma ha sido transformada en una **PWA**, permitiendo:
*   Instalación en dispositivos Android e iOS sin pasar por tiendas de aplicaciones.
*   Pantalla de carga personalizada e icono de aplicación dedicado.
*   Navegación independiente del navegador tradicional (modo standalone).

---

## CAPÍTULO 6: INFRAESTRUCTURA Y DESPLIEGUE

### 6.1. Configuración de Entornos
El sistema incluye un mecanismo de detección de entorno que ajusta automáticamente las variables de conexión y URLs basándose en el `HTTP_HOST`. Esto elimina errores de configuración al migrar el código de entornos de desarrollo (Localhost) a producción (Hostinger).

### 6.2. Directivas de Servidor (.htaccess)
Se han implementado archivos de configuración para Apache que gestionan:
*   Redirección automática hacia protocolos seguros (**HTTPS**).
*   Control de permisos de acceso a archivos críticos del sistema.
*   Gestión de tipos MIME para el correcto funcionamiento de los manifiestos de aplicación.

---

## CAPÍTULO 7: CONCLUSIÓN
El proyecto Dominican Travel representa una herramienta sólida y segura, construida bajo estándares modernos de ingeniería web. Su arquitectura modular y sus protocolos de seguridad avanzados garantizan una operación confiable y una experiencia de usuario de alto nivel para la industria turística dominicana.

---
**Fecha de Entrega:** 22 de Abril de 2026
**Responsable Técnico:** @pedro urena
