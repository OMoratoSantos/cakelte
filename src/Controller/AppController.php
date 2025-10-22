<?php
declare(strict_types=1);

namespace CakeLte\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    function subirArchivo(array $file, string $destDir = "uploads/", array $allowedExts = ['jpg','jpeg','png','pdf'], int $maxSize = 5242880): array
    {
        // 1️⃣ Verificar errores de subida
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error al subir el archivo. Código: ' . $file['error']];
        }

        // 2️⃣ Verificar tamaño
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'El archivo excede el tamaño máximo permitido.'];
        }

        // 3️⃣ Verificar extensión
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExts)) {
            return ['success' => false, 'message' => 'Tipo de archivo no permitido: ' . $ext];
        }

        // 4️⃣ Crear carpeta si no existe
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // 5️⃣ Generar nombre único
        $uniqueName = uniqid('file_', true) . '.' . $ext;
        $destPath = rtrim($destDir, '/') . '/' . $uniqueName;

        // 6️⃣ Mover archivo al destino
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return ['success' => true, 'message' => 'Archivo subido correctamente.', 'path' => $destPath];
        } else {
            return ['success' => false, 'message' => 'No se pudo guardar el archivo.'];
        }
}


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
