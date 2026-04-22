<?php

class Translator
{
    private static $instance = null;
    private $language = 'es';
    private $translations = [];

    private function __construct()
    {
        $this->detectLanguage();
        $this->loadTranslations();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 🔑 SE LLAMA CUANDO CAMBIA ?lang=
    public function reload()
    {
        $this->detectLanguage();
        $this->loadTranslations();
    }

    private function detectLanguage()
    {
        // Fuerza el español en el backend ya que el cliente web usa Google Translate
        $this->language = 'es';
    }

    private function loadTranslations()
    {
        $file = APP_ROOT . '/app/lang/es.php';

        if (file_exists($file)) {
            $this->translations = require $file;
        } else {
            $this->translations = [];
        }
    }

    public function get($key, $default = null)
    {
        return $this->translations[$key] ?? ($default ?? $key);
    }

    public function getCurrentLanguage()
    {
        return 'es';
    }

    public function getAvailableLanguages()
    {
        return [
            'es' => 'Español'
        ];
    }
}
