<?php
// src/Service/LocaleService.php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setDefaultLocale(string $locale)
    {
        // Set the default locale in the session
        $this->session->set('_locale', $locale);
    }
}
