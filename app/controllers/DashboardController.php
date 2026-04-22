<?php

class DashboardController extends Controller
{
    private $bookingModel;
    private $userModel;

    public function __construct()
    {
        $this->checkAuth();
        $this->bookingModel = new Booking();
        $this->userModel = new User();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $bookings = $this->bookingModel->getUserBookings($userId);

        $data = [
            'title' => 'Mi Dashboard - FUNTREK RD',
            'user_name' => $_SESSION['user_name'] ?? 'Usuario',
            'bookings' => $bookings
        ];

        $this->view('dashboard/index', $data);
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? ''
            ];

            // Validaciones
            if (empty($userData['full_name'])) {
                $error = 'El nombre completo es requerido.';
            } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Email inválido.';
            } else {
                $existingUser = $this->userModel->findBy('email', $userData['email']);
                if ($existingUser && $existingUser['id'] != $userId) {
                    $error = 'Este email ya está registrado.';
                } else {
                    if (empty($userData['password'])) {
                        unset($userData['password']);
                    } else {
                        // Validar complejidad si se proporcionó una nueva
                        $passwordError = $this->validatePasswordComplexity($userData['password']);
                        if ($passwordError) {
                            $error = $passwordError;
                        }
                    }

                    if (!isset($error)) {
                        $passwordChanged = !empty($userData['password']);
                        if ($this->userModel->updateProfile($userId, $userData)) {
                            // Enviar confirmación si se cambió la clave
                            if ($passwordChanged) {
                                $emailService = new Email();
                                $emailService->sendPasswordChangedNotification($userData['email']);
                            }

                            $_SESSION['user_name'] = $userData['full_name'];
                            $_SESSION['user_email'] = $userData['email'];
                            $_SESSION['success'] = 'Perfil actualizado exitosamente.';
                            $this->redirect('/dashboard');
                        } else {
                            $error = 'Error al actualizar el perfil.';
                        }
                    }
                }
            }
        }

        $data = [
            'title' => 'Mi Perfil - Dominican Travel',
            'user' => $user,
            'error' => $error ?? null
        ];

        $this->view('dashboard/profile', $data);
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('/auth/login');
        }
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




    // Ver todas las reservas del cliente
    public function bookings()
    {
        $userId = $_SESSION['user_id'];
        $bookings = $this->bookingModel->getUserBookings($userId);

        $data = [
            'title' => 'Mis Reservas - FUNTREK RD',
            'bookings' => $bookings
        ];

        $this->view('dashboard/bookings', $data);
    }

    // Ver detalle de una reserva específica
    public function booking($id)
    {
        $userId = $_SESSION['user_id'];
        $booking = $this->bookingModel->getUserBookingDetail($userId, $id);

        if (!$booking) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            $this->redirect('/dashboard/bookings');
        }

        $data = [
            'title' => 'Detalle de Reserva - FUNTREK RD',
            'booking' => $booking
        ];

        $this->view('dashboard/booking_detail', $data);
    }

    // Solicitar cancelación de reserva
    public function requestCancelBooking($id)
    {
        $userId = $_SESSION['user_id'];
        $booking = $this->bookingModel->getUserBookingDetail($userId, $id);

        if (!$booking) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            $this->redirect('/dashboard/bookings');
        }

        // Solo permitir cancelar si está pendiente o confirmada
        if ($booking['status'] === 'cancelled' || $booking['status'] === 'completed') {
            $_SESSION['error'] = 'No se puede cancelar esta reserva.';
            $this->redirect('/dashboard/booking/' . $id);
        }

        // Actualizar estado a "cancelled"
        $this->bookingModel->update($id, ['status' => 'cancelled']);
        $_SESSION['success'] = 'Tu solicitud de cancelación ha sido procesada.';

        // Enviar notificación al admin (opcional)
        if (defined('SEND_EMAILS') && SEND_EMAILS) {
            // Aquí podrías enviar un email al admin
        }

        $this->redirect('/dashboard/booking/' . $id);
    }


}
