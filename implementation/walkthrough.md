# Walkthrough: Seguridad Reforzada y Recuperación de Contraseña

Se ha completado la implementación del sistema de seguridad de contraseñas y el flujo de restablecimiento vía email. Estas mejoras garantizan que las cuentas de los usuarios sean más difíciles de vulnerar y que tengan un método autónomo para recuperar el acceso.

## Cambios Realizados

### 1. Validación de Contraseñas (Backend y Frontend)
- **Regex Backend**: Se implementó una validación estricta en `AuthController` y `DashboardController`. Ahora se exige:
  - Mínimo 8 caracteres.
  - Al menos una **Mayúscula** y una **Minúscula**.
  - Al menos un **Número**.
  - Al menos un **Carácter Especial** (!@#$%...).
- **Checklist Frontend**: Las vistas de **Registro**, **Cambio de Contraseña** y **Perfil** ahora incluyen una lista visual que se marca en verde en tiempo real mientras el usuario escribe.

### 2. Sistema de Restablecimiento de Contraseña
- **Token Seguro**: Generación de tokens criptográficos de un solo uso con expiración de 1 hora.
- **Flujo de Email**: Integración con `PHPMailer` para enviar enlaces de recuperación profesionales y seguros.
- **Nuevas Vistas**: 
  - `forgot_password.php`: Solicitud de enlace.
  - `reset_password.php`: Establecimiento de la nueva clave con validación en tiempo real.
- **Notificaciones Automáticas**:
  - Se agregó el envío de un correo de confirmación de seguridad cada vez que se detecta un cambio de contraseña (ya sea vía recuperación o desde el perfil).

### 3. Base de Datos
- Se preparó el esquema para soportar los campos `reset_token` y `reset_expires`.

> [!IMPORTANT]
> **Acción Requerida**: Debido a que el servicio de MySQL no estaba accesible durante la ejecución automatizada, debes ejecutar el siguiente comando SQL en tu panel de **phpMyAdmin** para que el sistema de recuperación funcione:
> ```sql
> ALTER TABLE users 
> ADD COLUMN reset_token VARCHAR(255) NULL AFTER last_login,
> ADD COLUMN reset_expires DATETIME NULL AFTER reset_token;
> ```

## Archivos Modificados/Creados

- **Modelos**: [User.php](file:///c:/xampp/htdocs/dominican_travel/app/models/User.php) (Gestión de tokens)
- **Núcleo**: [Email.php](file:///c:/xampp/htdocs/dominican_travel/app/core/Email.php) (Plantilla de correo)
- **Controladores**: 
  - [AuthController.php](file:///c:/xampp/htdocs/dominican_travel/app/controllers/AuthController.php) (Lógica de validación y reset)
  - [DashboardController.php](file:///c:/xampp/htdocs/dominican_travel/app/controllers/DashboardController.php) (Validación en perfil)
- **Vistas**:
  - [register.php](file:///c:/xampp/htdocs/dominican_travel/app/views/auth/register.php) (Checklist visual)
  - [login.php](file:///c:/xampp/htdocs/dominican_travel/app/views/auth/login.php) (Link de recuperación)
  - [profile.php](file:///c:/xampp/htdocs/dominican_travel/app/views/dashboard/profile.php) (Checklist visual)
  - [NEW] `forgot_password.php`
  - [NEW] `reset_password.php`

## Verificación Realizada

- [x] Verificada la lógica de Regex en el código.
- [x] Verificada la integración de PHPMailer (el sistema ya estaba enviando correos de reserva).
- [x] Verificado el diseño neumórfico y responsive de las nuevas vistas.
