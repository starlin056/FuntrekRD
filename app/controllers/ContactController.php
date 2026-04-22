<?php

class ContactController extends Controller
{
    private $adminEmail = 'funtrek@gmail.com'; // ← CAMBIA ESTE CORREO
    private $whatsapp   = '18091234567'; // ← WhatsApp con código país (sin +)

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleForm();
            return;
        }

        $this->view('contact/index', [
            'title'    => 'Contacto - Dominican Travel',
            'whatsapp' => $this->whatsapp
        ]);
    }

    private function handleForm()
    {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$name || !$email || !$message) {
            $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
            $this->redirect('/contact');
        }

        /* ======================
           EMAIL AL ADMIN
        ====================== */
        $subjectAdmin = "Nuevo contacto desde Dominican Travel";
        $bodyAdmin = "
        Nombre: $name
        Correo: $email
        Teléfono: $phone

        Mensaje:
        $message
        ";

        $headersAdmin  = "From: $email\r\n";
        $headersAdmin .= "Reply-To: $email\r\n";

        mail($this->adminEmail, $subjectAdmin, $bodyAdmin, $headersAdmin);

        /* ======================
           EMAIL AL CLIENTE
        ====================== */
        $subjectClient = "Hemos recibido tu solicitud - Dominican Travel";
        $bodyClient = "
        Hola $name,

        Gracias por contactarnos.
        Hemos recibido tu mensaje y uno de nuestros asesores te responderá en breve.

        Resumen de tu solicitud:
        --------------------------------
        $message
        --------------------------------

        Dominican Travel
        ";

        $headersClient  = "From: Dominican Travel <{$this->adminEmail}>\r\n";
        mail($email, $subjectClient, $bodyClient, $headersClient);

        $_SESSION['success'] = 'Tu mensaje fue enviado correctamente.';
        $this->redirect('/contact');
    }


}
