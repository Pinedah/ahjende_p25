<?php
// =====================================
// FUNCIONES UTILITARIAS PARA WEBSOCKETS
// =====================================
// Fecha: 21 Julio 2025
// Autor: GitHub Copilot Assistant
// Descripción: Funciones para enviar mensajes WebSocket desde el servidor

/**
 * Envía un mensaje WebSocket utilizando la API externa
 * @param array $mensaje Array con el mensaje a enviar
 * @return bool True si se envió correctamente, false en caso contrario
 */
function enviarMensajeWebSocket($mensaje) {
    try {
        $url = 'https://socket.ahjende.com/api/broadcast';
        
        $data = json_encode([
            'tipo' => $mensaje['tipo'] ?? 'actualizacion',
            'tabla' => $mensaje['tabla'] ?? '',
            'accion' => $mensaje['accion'] ?? 'modificado',
            'datos' => $mensaje['datos'] ?? [],
            'timestamp' => date('Y-m-d H:i:s'),
            'origen' => 'p25_persistencia_plantel'
        ]);
        
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => $data,
                'timeout' => 5
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        // Log para debugging
        file_put_contents('debug_websocket.log', '[' . date('Y-m-d H:i:s') . '] WebSocket enviado: ' . $data . ' Resultado: ' . ($result ? 'SUCCESS' : 'FAILED') . "\n", FILE_APPEND);
        
        return $result !== false;
    } catch (Exception $e) {
        // Log del error
        file_put_contents('debug_websocket.log', '[' . date('Y-m-d H:i:s') . '] Error WebSocket: ' . $e->getMessage() . "\n", FILE_APPEND);
        return false;
    }
}

/**
 * Envía notificación cuando un ejecutivo cambia de plantel
 * @param int $id_ejecutivo ID del ejecutivo
 * @param int $plantel_anterior ID del plantel anterior
 * @param int $plantel_nuevo ID del plantel nuevo
 * @param string $nombre_ejecutivo Nombre del ejecutivo
 */
function notificarCambioPlantelEjecutivo($id_ejecutivo, $plantel_anterior, $plantel_nuevo, $nombre_ejecutivo) {
    $mensaje = [
        'tipo' => 'ejecutivo_cambio_plantel',
        'tabla' => 'ejecutivo',
        'accion' => 'cambio_plantel',
        'datos' => [
            'id_eje' => $id_ejecutivo,
            'nom_eje' => $nombre_ejecutivo,
            'plantel_anterior' => $plantel_anterior,
            'plantel_nuevo' => $plantel_nuevo,
            'nuevo_plantel' => $plantel_nuevo, // Para compatibilidad con código existente
            'mensaje' => "Ejecutivo $nombre_ejecutivo cambió del plantel $plantel_anterior al plantel $plantel_nuevo"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

/**
 * Envía notificación cuando se actualiza el conteo de citas de un plantel
 * @param int $id_plantel ID del plantel
 * @param string $nombre_plantel Nombre del plantel
 * @param array $estadisticas Estadísticas del plantel
 */
function notificarActualizacionCitasPlantel($id_plantel, $nombre_plantel, $estadisticas) {
    $mensaje = [
        'tipo' => 'actualizacion_citas_plantel',
        'tabla' => 'plantel_citas',
        'accion' => 'actualizado',
        'datos' => [
            'id_pla' => $id_plantel,
            'nom_pla' => $nombre_plantel,
            'estadisticas' => $estadisticas,
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

/**
 * Envía notificación cuando se mueve un ejecutivo
 * @param int $id_ejecutivo ID del ejecutivo
 * @param int $padre_anterior ID del padre anterior
 * @param int $padre_nuevo ID del padre nuevo
 * @param string $nombre_ejecutivo Nombre del ejecutivo
 */
function notificarMovimientoEjecutivo($id_ejecutivo, $padre_anterior, $padre_nuevo, $nombre_ejecutivo) {
    $mensaje = [
        'tipo' => 'ejecutivo_movimiento',
        'tabla' => 'ejecutivo',
        'accion' => 'movido',
        'datos' => [
            'id_eje' => $id_ejecutivo,
            'nom_eje' => $nombre_ejecutivo,
            'padre_anterior' => $padre_anterior,
            'nuevo_padre' => $padre_nuevo,
            'mensaje' => "Ejecutivo $nombre_ejecutivo fue movido en la jerarquía"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

/**
 * Envía notificación cuando una cita cambia de plantel
 * @param int $id_cita ID de la cita
 * @param int $plantel_anterior ID del plantel anterior
 * @param int $plantel_nuevo ID del plantel nuevo
 * @param string $motivo Motivo del cambio
 */
function notificarCambioPlantelCita($id_cita, $plantel_anterior, $plantel_nuevo, $motivo = null) {
    $mensaje = [
        'tipo' => 'cita_cambio_plantel',
        'tabla' => 'cita',
        'accion' => 'cambio_plantel',
        'datos' => [
            'id_cit' => $id_cita,
            'plantel_anterior' => $plantel_anterior,
            'plantel_nuevo' => $plantel_nuevo,
            'motivo' => $motivo,
            'mensaje' => "Cita #$id_cita migrada del plantel $plantel_anterior al plantel $plantel_nuevo"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

/**
 * Envía notificación cuando se desasocia una cita de un ejecutivo
 * @param int $id_cita ID de la cita
 * @param int $ejecutivo_anterior ID del ejecutivo anterior
 * @param string $motivo Motivo de la disociación
 */
function notificarDisociacionCitaEjecutivo($id_cita, $ejecutivo_anterior, $motivo = null) {
    $mensaje = [
        'tipo' => 'cita_disociacion',
        'tabla' => 'cita',
        'accion' => 'disociada',
        'datos' => [
            'id_cit' => $id_cita,
            'ejecutivo_anterior' => $ejecutivo_anterior,
            'motivo' => $motivo,
            'mensaje' => "Cita #$id_cita fue disociada del ejecutivo #$ejecutivo_anterior"
        ]
    ];
    
    return enviarMensajeWebSocket($mensaje);
}

/**
 * Obtiene las estadísticas actuales de un plantel para WebSocket
 * @param int $id_plantel ID del plantel
 * @param mysqli $connection Conexión a la base de datos
 * @return array Estadísticas del plantel
 */
function obtenerEstadisticasPlantel($id_plantel, $connection) {
    $query = "SELECT 
                COUNT(*) as total_citas,
                COUNT(CASE WHEN eli_cit = 1 THEN 1 END) as citas_activas,
                COUNT(CASE WHEN id_eje2 IS NOT NULL THEN 1 END) as citas_con_ejecutivo,
                COUNT(CASE WHEN id_eje2 IS NULL THEN 1 END) as citas_sin_ejecutivo,
                COUNT(CASE WHEN DATE(cit_cit) = CURDATE() THEN 1 END) as citas_hoy
              FROM cita 
              WHERE pla_cit = $id_plantel";
              
    $result = mysqli_query($connection, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    
    return [
        'total_citas' => 0,
        'citas_activas' => 0,
        'citas_con_ejecutivo' => 0,
        'citas_sin_ejecutivo' => 0,
        'citas_hoy' => 0
    ];
}

?>
