<?php
declare(strict_types=1);

namespace CakeLte\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    /**
     * Registra una acción realizada por el usuario autenticado
     *
     * @param string $action Descripción de la acción realizada
     * @param array|null $data Datos adicionales (opcional)
     * @return void
     */
    protected function logUserAction(string $action, ?array $data = null): void
    {
        $user = $this->request->getAttribute('identity');
        $username = $user ? $user->get('username') : 'Invitado';

        $message = sprintf(
            '[%s] Acción: %s | Datos: %s',
            $username,
            $action,
            json_encode($data ?? [])
        );

        // Guarda en logs/app.log
        Log::write('info', $message);
    }
}
