<?php
class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login()
    {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            $this->redirectToDashboard();
        }

        $data = ['title' => 'Iniciar Sesión'];

        // Configuración de límites de intentos
        $maxAttempts = 5;
        $lockoutTime = 15 * 60; // 15 minutos en segundos

        // Verificar si el usuario está bloqueado por demasiados intentos
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxAttempts) {
            $timeSinceLastAttempt = time() - $_SESSION['last_login_attempt'];
            if ($timeSinceLastAttempt < $lockoutTime) {
                $minutesLeft = ceil(($lockoutTime - $timeSinceLastAttempt) / 60);
                $data['error'] = "Has excedido el número de intentos permitidos. Por favor, inténtalo de nuevo en {$minutesLeft} minutos.";
                $this->view('auth/login', $data);
                return;
            } else {
                // El tiempo de bloqueo ha expirado, reiniciar intentos
                $_SESSION['login_attempts'] = 0;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->authenticate($email, $password);

            if ($user) {
                // Limpiar intentos al iniciar sesión correctamente
                unset($_SESSION['login_attempts'], $_SESSION['last_login_attempt']);

                // Guardar en sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                 // SEGURIDAD: Regenerar ID de sesión para prevenir fijación de sesión
                 AuthMiddleware::regenerateSession();

                // Registrar último login
                $this->userModel->updateLastLogin($user['id']);

                // Redirigir según rol o reserva pendiente
                if (isset($_SESSION['booking_redirect'])) {
                    $redirectUrl = $_SESSION['booking_redirect'];
                    unset($_SESSION['booking_redirect']);
                    $this->redirect($redirectUrl);
                } else {
                    $this->redirectToDashboard();
                }
            } else {
                // Registrar intento fallido
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                $_SESSION['last_login_attempt'] = time();

                $intentosRestantes = $maxAttempts - $_SESSION['login_attempts'];
                if ($intentosRestantes > 0) {
                    $data['error'] = "Credenciales incorrectas. Te quedan {$intentosRestantes} intentos.";
                } else {
                    $data['error'] = 'Demasiados intentos fallidos. Tu acceso ha sido bloqueado temporalmente.';
                }
            }
        }

        $this->view('auth/login', $data);
    }

    public function register()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            $this->redirectToDashboard();
        }

        $data = ['title' => 'Registrarse'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'role' => 'client'
            ];

            $errors = $this->validateRegistration($userData);

            if ($userData['password'] !== ($_POST['confirm_password'] ?? '')) {
                $errors['password'] = 'Las contraseñas no coinciden.';
            }

            if (empty($errors)) {
                if ($this->userModel->findBy('email', $userData['email'])) {
                    $data['error'] = 'El email ya está registrado.';
                } elseif ($this->userModel->findBy('username', $userData['username'])) {
                    $data['error'] = 'El nombre de usuario ya está en uso.';
                } else {
                    $userId = $this->userModel->create($userData);

                    if ($userId) {
                        $user = $this->userModel->findById($userId);
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['full_name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['logged_in'] = true;

                         // SEGURIDAD: Regenerar ID de sesión para prevenir fijación de sesión
                         AuthMiddleware::regenerateSession();

                        $this->redirectToDashboard();
                    } else {
                        $data['error'] = 'Error al registrar usuario. Por favor intenta de nuevo.';
                    }
                }
            } else {
                $data['errors'] = $errors;
                $data['form_data'] = $_POST;
            }
        }

        $this->view('auth/register', $data);
    }

    public function logout()
    {
        // Limpiar sesión
        session_unset();
        session_destroy();

        // Redirigir al login
        $this->redirect('/auth/login');
    }


    private function validateRegistration($data)
    {
        $errors = [];

        if (strlen($data['username']) < 3) {
            $errors['username'] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Por favor ingresa un email válido.';
        }

        // Validación mejorada de contraseña
        $passwordError = $this->validatePasswordComplexity($data['password']);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'El nombre completo es requerido.';
        }

        return $errors;
    }

    /**
     * Valida la complejidad de la contraseña con Regex
     */
    private function validatePasswordComplexity($password)
    {
        if (strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return 'La contraseña debe incluir al menos una mayúscula.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            return 'La contraseña debe incluir al menos una minúscula.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            return 'La contraseña debe incluir al menos un número.';
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return 'La contraseña debe incluir al menos un carácter especial.';
        }
        return null;
    }

    /* =====================================================
       FLUJO DE RESTABLECIMIENTO DE CONTRASEÑA
    ===================================================== */

    public function forgotPassword()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            $this->redirectToDashboard();
        }

        $data = ['title' => 'Recuperar Contraseña'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Ingresa un email válido.';
            } else {
                $user = $this->userModel->findBy('email', $email);
                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    if ($this->userModel->setResetToken($email, $token, $expires)) {
                        $resetLink = APP_URL . '/auth/resetPassword/' . $token;
                        $emailService = new Email();
                        if ($emailService->sendPasswordResetEmail($email, $resetLink)) {
                            $data['success'] = 'Se ha enviado un enlace de recuperación a tu correo.';
                        } else {
                            $data['error'] = 'Error al enviar el correo. Intenta más tarde.';
                        }
                    }
                } else {
                    // Por seguridad, no revelamos si el email existe, pero aquí podemos actuar según el requerimiento.
                    $data['success'] = 'Si el correo está registrado, recibirás un enlace de recuperación en breve.';
                }
            }
        }

        $this->view('auth/forgot_password', $data);
    }

    public function resetPassword($token = null)
    {
        if (!$token) {
            $this->redirect('/auth/login');
        }

        $user = $this->userModel->validateResetToken($token);
        if (!$user) {
            $data = [
                'title' => 'Enlace Inválido',
                'error' => 'El enlace de recuperación es inválido o ha expirado.'
            ];
            $this->view('auth/forgot_password', $data);
            return;
        }

        $data = [
            'title' => 'Nueva Contraseña',
            'token' => $token
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($password !== $confirmPassword) {
                $data['error'] = 'Las contraseñas no coinciden.';
            } else {
                $passwordError = $this->validatePasswordComplexity($password);
                if ($passwordError) {
                    $data['error'] = $passwordError;
                } else {
                    if ($this->userModel->updatePasswordByToken($token, $password)) {
                        // Enviar email de confirmación
                        $emailService = new Email();
                        $emailService->sendPasswordChangedNotification($user['email']);

                        $_SESSION['success'] = 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
                        $this->redirect('/auth/login');
                    } else {
                        $data['error'] = 'Error al actualizar la contraseña.';
                    }
                }
            }
        }

        $this->view('auth/reset_password', $data);
    }

    private function redirectToDashboard()
    {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/dashboard');
        }
    }

    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }
}

