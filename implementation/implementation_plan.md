# Plan de Implementación de Seguridad y Middleware

Este plan tiene como objetivo mejorar la seguridad estructural de "Dominican Travel" mediante la creación de un sistema de Middlewares, protegiendo contra vulnerabilidades comunes (CSRF, XSS, secuestro de sesiones) y previniendo el acceso no autorizado a los módulos (Ej: Panel de Administración, Dashboard, y endpoints de la API).

## ⚠️ User Review Required

> [!IMPORTANT]
> - ¿Existen otras rutas específicas (aparte de `/admin` y `/dashboard` y `/api`) que deban ser estrictamente limitadas a roles particulares?
> - Al implementar protección CSRF, **todos** los formularios actuales que usen método POST deberán incluir el token CSRF para funcionar, lo que requerirá actualizaciones en las vistas `.php`. ¿Estás de acuerdo con aplicar esto globalmente para la mejora de seguridad?

## Proposed Changes

---

### Arquitectura de Middleware (Core)

Actualmente las peticiones se procesan en `public/index.php` sin filtros previos. Introduciremos un sistema de capas que analice la petición antes de ejecutar la acción del controlador.

#### [NEW] [Middleware.php](file:///c:/xampp/htdocs/dominican_travel/app/core/Middleware.php)
Clase base o interfaz para estandarizar cómo se ejecutan los middlewares (`handle()` method).

#### [NEW] [RouteMiddleware.php](file:///c:/xampp/htdocs/dominican_travel/app/core/RouteMiddleware.php)
Gestor principal que registrará qué rutas ocupan cuáles middlewares y los ejecutará en cadena.

#### [MODIFY] [index.php](file:///c:/xampp/htdocs/dominican_travel/public/index.php)
- Se interceptará el flujo de control antes de inicializar la clase del controlador (línea ~139).
- Se configurarán las sesiones con parámetros de seguridad estrictos antes del `session_start()` (`httponly`, `samesite=Lax`).
- Se registrará un esquema para asociar el `$controllerKey` con los middlewares aplicables, tales como:
  - `admin` => `AuthMiddleware`, `AdminMiddleware`
  - `dashboard` => `AuthMiddleware`
  - `api` => `ApiAuthMiddleware` (según se defina la política de la API)

---

### Módulos de Middlewares (Filtros de Seguridad)

#### [NEW] [AuthMiddleware.php](file:///c:/xampp/htdocs/dominican_travel/app/middlewares/AuthMiddleware.php)
Middleware para garantizar que la solicitud es hecha por un usuario activo (`$_SESSION['logged_in']`). Si no está autenticado, detiene la ejecución y lo redirige a `/auth/login`.

#### [NEW] [AdminMiddleware.php](file:///c:/xampp/htdocs/dominican_travel/app/middlewares/AdminMiddleware.php)
Verificará específicamente que `$_SESSION['user_role'] === 'admin'`. Previene que usuarios regulares (clientes u otros roles no autorizados) accedan a los módulos de administración.

#### [NEW] [CsrfMiddleware.php](file:///c:/xampp/htdocs/dominican_travel/app/middlewares/CsrfMiddleware.php)
- Generará un token único por sesión.
- En cualquier petición con método `POST`, `PUT`, `DELETE`, validará que el token recibido coincide con el de la sesión.
- Si no coincide, rechaza la operación con un error HTTP 403 Forbidden.

#### [NEW] [SecurityHeadersMiddleware.php](file:///c:/xampp/htdocs/dominican_travel/app/middlewares/SecurityHeadersMiddleware.php)
Se ejecutará en todas las respuestas para inyectar cabeceras HTTP que mejoren la seguridad (Prevención de Clickjacking, XSS Protection, Bloqueo de MIME Sniffing, etc.).

---

### Limpieza de Código y Helpers

#### [MODIFY] [AuthController.php](file:///c:/xampp/htdocs/dominican_travel/app/controllers/AuthController.php)
Regenerar el ID de la sesión al hacer login exitoso (`session_regenerate_id(true)`) para prevenir ataques de *Session Fixation*.

#### [MODIFY] [AdminController.php](file:///c:/xampp/htdocs/dominican_travel/app/controllers/AdminController.php)
#### [MODIFY] [DashboardController.php](file:///c:/xampp/htdocs/dominican_travel/app/controllers/DashboardController.php)
Se eliminarán las llamadas a `$this->checkAdminAuth();` y `$this->checkAuth();` de los constructores, ya que esta responsabilidad ahora será delegada a nivel de Middleware, logrando que sea imposible evadir la validación aunque se añadan nuevas acciones en el futuro.

#### [NEW] [csrf.php (Helper)](file:///c:/xampp/htdocs/dominican_travel/app/helpers/csrf.php)
Una función sencilla `csrf_field()` para imprimir fácilmente `<input type="hidden" name="csrf_token" value="...">` en todos los formularios HTML de las vistas.

## Open Questions

> [!CAUTION]
> Para la API WEB (`api/custom-requests`), ¿los consumos se realizan exclusivamente desde el sitio web frontend o hay aplicaciones de terceros que la consuman?
> Si las llamadas provienen solo de la misma página mediante fetch/AJAX, las validaremos con tokens CSRF en las cabeceras además de las Cookies de Sesión.

## Verification Plan

### Automated / Manual Verification
- **Verificación de Seguridad de Sesión**: Iniciar sesión, revisar en las herramientas de desarrollo del navegador que las Cookies de Sesión tienen flags de HTTPOnly.
- **Validación de Accesos a Módulos**: Iniciar sesión con una cuenta de _cliente_ e intentar forzar el acceso a las URL de `/admin/dashboard` y `/admin/packages_create`. El middleware deberá expulsarlo.
- **Prueba CSRF Exitosas/Fallidas**: Intentar enviar un formulario POST (Ej. crear un paquete o actualizar perfil) enviando un token CSRF incorrecto o ausente; verificar que sea bloqueado de inmediato por el middleware con error 403.
- **Acceso a la API**: Manipular requests hacia `/api/custom-requests` con sesiones muertas o inválidas y validar que la API responda con el adecuado código JSON o 401 Unauthorized en lugar de volcar errores internos de PHP.
