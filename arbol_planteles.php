<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 11 - Árbol Recursivo por Plantel</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- jsTree CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/themes/default/style.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jsTree JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/jstree.min.js"></script>
    
    <style>
        /* Estilos para planteles */
        .plantel-container {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            min-height: 400px;
            position: relative;
        }
        
        .plantel-header {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        
        .plantel-tree {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            min-height: 320px;
        }
        
        /* Estilos para drag & drop entre planteles */
        .plantel-container.drop-zone {
            border: 3px dashed #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .plantel-container.drop-zone .plantel-header {
            background-color: #28a745;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        /* Estilos para drag & drop mejorados */
        .jstree-dnd-helper {
            background: #007bff !important;
            color: white !important;
            border-radius: 3px !important;
            padding: 5px 10px !important;
            font-weight: bold !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
        }
        
        .jstree-dnd-helper .jstree-icon {
            color: white !important;
        }
        
        /* Indicador visual para drop zones */
        .jstree-drop {
            background-color: rgba(0, 123, 255, 0.15) !important;
            border: 2px dashed #007bff !important;
            border-radius: 3px !important;
        }
        
        /* Estilos para nodos siendo arrastrados */
        .jstree-dragged {
            opacity: 0.6 !important;
        }
        
        /* Mensaje de estado para drag & drop */
        .drag-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .drag-status.error {
            background: #dc3545;
        }
        
        .drag-status.success {
            background: #28a745;
        }
        
        /* Estilos para mejorar jsTree con sangría tipo árbol de directorios */
        .jstree-default .jstree-node {
            min-height: 36px;
            line-height: 36px;
            margin: 2px 0;
            min-width: 24px;
        }
        
        .jstree-default .jstree-anchor {
            line-height: 36px;
            height: 36px;
            padding: 0 10px 0 8px;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .jstree-default .jstree-icon {
            width: 18px;
            height: 18px;
            line-height: 18px;
            margin-top: 9px;
            margin-right: 8px;
        }
        
        /* Sangría y líneas de conexión jerárquicas mejoradas */
        .jstree-default .jstree-children {
            margin-left: 40px;
            position: relative;
        }
        
        /* Línea vertical principal */
        .jstree-default .jstree-children:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 0;
            bottom: 18px;
            width: 2px;
            background: linear-gradient(to bottom, #007bff, #0056b3);
            opacity: 0.7;
            border-radius: 1px;
        }
        
        /* Líneas horizontales para cada nodo */
        .jstree-default .jstree-children .jstree-node {
            position: relative;
        }
        
        .jstree-default .jstree-children .jstree-node:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 18px;
            width: 22px;
            height: 2px;
            background: linear-gradient(to right, #007bff, #0056b3);
            opacity: 0.7;
            border-radius: 1px;
        }
        
        /* Ocultar línea vertical en el último nodo */
        .jstree-default .jstree-children .jstree-node:last-child:after {
            content: '';
            position: absolute;
            left: -26px;
            top: 20px;
            bottom: -18px;
            width: 4px;
            background: white;
            z-index: 1;
        }
        
        /* Hover effects para nodos */
        .jstree-default .jstree-hovered {
            background: rgba(0, 123, 255, 0.15);
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
        }
        
        .jstree-default .jstree-clicked {
            background: rgba(0, 123, 255, 0.25);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }
        
        /* Estilos específicos por nivel de profundidad mejorados */
        .jstree-root-level > .jstree-anchor {
            font-weight: bold;
            border-left: 4px solid #007bff;
            padding-left: 12px;
            background: linear-gradient(to right, rgba(0, 123, 255, 0.1), transparent);
            font-size: 15px;
        }
        
        .jstree-level-2 > .jstree-anchor {
            border-left: 3px solid #28a745;
            padding-left: 10px;
            background: linear-gradient(to right, rgba(40, 167, 69, 0.08), transparent);
            font-size: 14px;
        }
        
        .jstree-level-3 > .jstree-anchor {
            border-left: 2px solid #ffc107;
            padding-left: 8px;
            background: linear-gradient(to right, rgba(255, 193, 7, 0.08), transparent);
            font-style: italic;
            font-size: 13px;
        }
        
        /* Estilos adicionales para niveles más profundos */
        .jstree-level-4 > .jstree-anchor {
            border-left: 2px solid #dc3545;
            padding-left: 8px;
            background: linear-gradient(to right, rgba(220, 53, 69, 0.08), transparent);
            font-size: 13px;
            opacity: 0.9;
        }
        
        .jstree-level-5 > .jstree-anchor {
            border-left: 2px solid #6f42c1;
            padding-left: 8px;
            background: linear-gradient(to right, rgba(111, 66, 193, 0.08), transparent);
            font-size: 12px;
            opacity: 0.9;
        }
        
        /* Mejorar los iconos por tipo de nodo */
        .jstree-default .jstree-icon.fas {
            color: #007bff;
        }
        
        .jstree-default .jstree-icon.text-success {
            color: #28a745 !important;
        }
        
        .jstree-default .jstree-icon.text-danger {
            color: #dc3545 !important;
        }
        
        /* Efectos visuales para nodos inactivos */
        .jstree-default .jstree-node[data-type="inactive"] > .jstree-anchor {
            opacity: 0.7;
            background-color: #f8f9fa;
        }
        
        /* =============================================
           ESTILOS PARA SEMÁFORO DE SESIÓN P15
           ============================================= */
        
        /* Indicador de semáforo al lado del nombre */
        .semaforo-sesion {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
            margin-right: 4px;
            position: relative;
            top: 1px;
            border: 1px solid rgba(0,0,0,0.2);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .semaforo-sesion.verde {
            background-color: #28a745;
            box-shadow: 0 0 6px rgba(40, 167, 69, 0.5);
        }
        
        .semaforo-sesion.amarillo {
            background-color: #ffc107;
            box-shadow: 0 0 6px rgba(255, 193, 7, 0.5);
        }
        
        .semaforo-sesion.rojo {
            background-color: #dc3545;
            box-shadow: 0 0 6px rgba(220, 53, 69, 0.5);
        }
        
        .semaforo-sesion.sin_sesion {
            background-color: #6c757d;
            box-shadow: 0 0 6px rgba(108, 117, 125, 0.5);
        }
        
        /* Tooltip para mostrar información del semáforo */
        .semaforo-tooltip {
            position: absolute;
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1000;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        
        .semaforo-tooltip.show {
            opacity: 1;
        }
        
        .semaforo-tooltip::before {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }
        
        /* Mejorar el aspecto de los nodos con semáforo */
        .jstree-default .jstree-anchor {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .jstree-anchor-content {
            flex-grow: 1;
            display: flex;
            align-items: center;
        }
        
        .jstree-anchor-indicators {
            display: flex;
            align-items: center;
            margin-left: 8px;
        }
        
        /* Estilos para el nombre del ejecutivo */
        .ejecutivo-nombre {
            flex-grow: 1;
        }
        
        /* Animación para el semáforo */
        .semaforo-sesion {
            animation: semaforoPulse 2s infinite;
        }
        
        @keyframes semaforoPulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        /* Desactivar animación en semáforos verdes para no distraer */
        .semaforo-sesion.verde {
            animation: none;
        }
        
        /* Estilos para estadísticas del semáforo */
        .stats-semaforo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .stat-semaforo {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .stat-semaforo .semaforo-sesion {
            position: static;
            top: auto;
            animation: none;
        }
        
        /* Leyenda del semáforo */
        .semaforo-leyenda {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .semaforo-leyenda h6 {
            margin-bottom: 12px;
            color: #495057;
            font-weight: bold;
        }
        
        .leyenda-items {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #495057;
        }
        
        .leyenda-item .semaforo-sesion {
            position: static;
            top: auto;
            animation: none;
        }
        
        /* Panel de control */
        .control-panel {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .info-panel {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .badge {
            font-size: 0.8em;
            padding: 0.4em 0.6em;
        }
        
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        
        .modal-header .close {
            color: white;
            opacity: 0.8;
        }
        
        .modal-header .close:hover {
            opacity: 1;
        }
        
        /* Estilos para estadísticas */
        .stats-container {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .stat-item {
            text-align: center;
            padding: 10px;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 0.9em;
            color: #6c757d;
        }
        
        /* Mejorar las zonas de drop para incluir elementos internos */
        .plantel-container.drop-zone * {
            pointer-events: none; /* Evitar que los elementos internos interfieran con el drop */
        }
        
        .plantel-container.drop-zone .plantel-tree {
            pointer-events: all; /* Permitir drops en el área del árbol */
        }
        
        .plantel-container.drop-zone .jstree-node {
            pointer-events: all; /* Permitir drops en los nodos */
        }
        
        .plantel-container.drop-zone .jstree-anchor {
            pointer-events: all; /* Permitir drops en los enlaces de nodos */
        }
        
        /* Estilo visual cuando el drop es válido */
        .plantel-container.drop-zone .plantel-tree {
            background-color: rgba(40, 167, 69, 0.1) !important;
            border: 2px dashed #28a745 !important;
            border-radius: 8px !important;
        }
        
        /* =====================================
           ESTILOS PARA BADGES DE CONTEO DE CITAS - P18
           ===================================== */
        
        /* Badge para citas propias (blanco) */
        .badge-citas-propias {
            background-color: #ffffff;
            color: #333333;
            border: 1px solid #dee2e6;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .badge-citas-propias:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
            transform: scale(1.05);
            color: #007bff;
        }
        
        /* Badge para citas recursivas (morado) */
        .badge-citas-recursivas {
            background-color: #6f42c1;
            color: #ffffff;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .badge-citas-recursivas:hover {
            background-color: #563d7c;
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(111, 66, 193, 0.3);
        }
        
        /* Badge para citas de plantel (azul) */
        .badge-citas-plantel {
            background-color: #6f42c1;
            color: #ffffff;
            margin-left: 5px;
            font-size: 0.75em;
            cursor: pointer;
            transition: all 0.2s;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .badge-citas-plantel:hover {
            background-color: #563d7c;
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(111, 66, 193, 0.3);
        }
        
        /* Contenedor para los badges de citas */
        .badges-citas-container {
            display: inline-block;
            margin-left: 8px;
        }
        
        /* Estilos para WebSocket */
        .websocket-changed {
            background-color: #ffeb3b !important;
            transition: background-color 0.3s ease;
        }
        
        /* Específico para ejecutivos movidos */
        .ejecutivo-movido {
            background-color: #ffeb3b !important;
            animation: pulsoAmarilloIntensivo 3s ease-in-out;
            border-radius: 6px !important;
            box-shadow: 0 0 10px rgba(255, 235, 59, 0.7) !important;
        }
        
        @keyframes pulsoAmarilloIntensivo {
            0% { 
                background-color: #ffeb3b !important; 
                transform: scale(1);
                box-shadow: 0 0 10px rgba(255, 235, 59, 0.7);
            }
            25% { 
                background-color: #fff176 !important; 
                transform: scale(1.02);
                box-shadow: 0 0 15px rgba(255, 235, 59, 0.8);
            }
            50% { 
                background-color: #ffeb3b !important; 
                transform: scale(1);
                box-shadow: 0 0 10px rgba(255, 235, 59, 0.7);
            }
            75% { 
                background-color: #fff59d !important; 
                transform: scale(1.01);
                box-shadow: 0 0 8px rgba(255, 235, 59, 0.6);
            }
            100% { 
                background-color: transparent !important; 
                transform: scale(1);
                box-shadow: none;
            }
        }
        
        .websocket-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            animation: fadeInOut 3s ease-in-out;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-10px); }
            20% { opacity: 1; transform: translateY(0); }
            80% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
        
        .websocket-status {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .websocket-status.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .websocket-status.danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .websocket-status.warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        
        .websocket-log {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 8px;
            height: 60px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.8em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <h1 class="text-center mb-4">Práctica 11 - Árbol Recursivo por Plantel</h1>
        
        <!-- Panel de control -->
        <div class="control-panel">
            <div class="row">
                <div class="col-md-5">
                    <h4>Gestión de Ejecutivos por Plantel</h4>
                    <p class="text-muted">Arrastra y suelta ejecutivos entre planteles para reorganizar la estructura</p>
                </div>
                <div class="col-md-7">
                    <!-- WebSocket Status y Controls -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div id="websocket-status" class="websocket-status warning">
                                <strong>WebSocket:</strong> <span id="websocket-status-text">Conectando...</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="websocket-log" id="websocket-log">
                                <div id="websocket-log-text">Logs de WebSocket aparecerán aquí...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Control ID - P20 -->
                    <div class="form-row mb-3">
                        <div class="col-md-2">
                            <label for="mi-id-ejecutivo" class="small"><strong>Mi ID:</strong></label>
                            <input type="number" id="mi-id-ejecutivo" class="form-control form-control-sm" value="1" min="1" max="999">
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary mr-2" onclick="mostrarModalCrear()">
                            <i class="fas fa-plus"></i> Nuevo Ejecutivo
                        </button>
                        <button class="btn btn-secondary" onclick="recargarTodos()">
                            <i class="fas fa-sync"></i> Recargar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leyenda del Semáforo de Sesión P15 -->
        <div class="semaforo-leyenda">
            <h6><i class="fas fa-traffic-light"></i> Semáforo de Sesión - Práctica 15</h6>
            <div class="leyenda-items">
                <div class="leyenda-item">
                    <span class="semaforo-sesion verde"></span>
                    <span>Verde: ≤ 1 día</span>
                </div>
                <div class="leyenda-item">
                    <span class="semaforo-sesion amarillo"></span>
                    <span>Amarillo: 2-3 días</span>
                </div>
                <div class="leyenda-item">
                    <span class="semaforo-sesion rojo"></span>
                    <span>Rojo: ≥ 4 días</span>
                </div>
                <div class="leyenda-item">
                    <span class="semaforo-sesion sin_sesion"></span>
                    <span>Sin sesión</span>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="row" id="estadisticas">
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="total-ejecutivos">0</div>
                    <div class="stat-label">Total Ejecutivos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="ejecutivos-activos">0</div>
                    <div class="stat-label">Activos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="ejecutivos-ocultos">0</div>
                    <div class="stat-label">Ocultos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="total-planteles">3</div>
                    <div class="stat-label">Planteles</div>
                </div>
            </div>
        </div>
        
        <!-- Planteles -->
        <div class="row" id="planteles-container">
            <!-- Los planteles se cargarán dinámicamente aquí -->
        </div>
        
        <!-- Panel de información -->
        <div class="info-panel" id="info-panel" style="display: none;">
            <h5>Información del Ejecutivo Seleccionado</h5>
            <div id="ejecutivo-info"></div>
        </div>
    </div>
    
    <!-- Mensaje de drag & drop -->
    <div class="drag-status" id="drag-status"></div>
    
    <!-- Modal para Crear/Editar -->
    <div class="modal fade" id="modalEjecutivo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Crear Ejecutivo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEjecutivo">
                        <input type="hidden" id="ejecutivo_id" name="id_eje">
                        
                        <div class="form-group">
                            <label for="ejecutivo_nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ejecutivo_nombre" name="nom_eje" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivo_telefono">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ejecutivo_telefono" name="tel_eje" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivo_plantel">Plantel <span class="text-danger">*</span></label>
                            <select class="form-control" id="ejecutivo_plantel" name="id_pla" required>
                                <option value="">Seleccione un plantel</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="ejecutivo_padre">Jefe Inmediato</label>
                            <select class="form-control" id="ejecutivo_padre" name="id_padre">
                                <option value="">Sin jefe (Raíz)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ejecutivo_activo" name="eli_eje" value="1" checked>
                                <label class="form-check-label" for="ejecutivo_activo">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEjecutivo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Variables globales
        var planteles = [];
        var ejecutivos = [];
        var nodoSeleccionado = null;
        var modoEdicion = false;
        
        // Variables WebSocket
        var socket = null;
        var websocketUrl = 'wss://socket.ahjende.com/wss/?encoding=text';
        var miIdEjecutivo = 1; // ID del ejecutivo actual
        var reconectarIntento = 0;
        var maxReconectarIntentos = 5;
        
        // Inicialización
        $(document).ready(function() {
            // Configurar evento para cambio de ID ejecutivo
            $('#mi-id-ejecutivo').on('change', function() {
                miIdEjecutivo = parseInt($(this).val()) || 1;
                log('ID ejecutivo cambiado a: ' + miIdEjecutivo);
            });
            
            // Inicializar WebSocket
            inicializarWebSocket();
            
            aplicarFiltrosDesdeURL();
            // Cargar planteles primero, luego ejecutivos
            cargarPlanteles().then(function() {
                return cargarEjecutivos();
            }).then(function() {
                // Cargar conteos de citas por plantel después de cargar todo
                cargarCitasPorPlantel();
            }).catch(function(error) {
                console.error('Error en la inicialización:', error);
            });
        });
        
        function aplicarFiltrosDesdeURL() {
            // Obtener parámetros de la URL
            var params = obtenerParametrosURL();
            
            // Aplicar otros filtros si existen (reservado para futuras funcionalidades)
            console.log('Parámetros URL:', params);
        }
        
        function obtenerParametrosURL() {
            var params = {};
            var queryString = window.location.search.substring(1);
            var vars = queryString.split('&');
            
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                if (pair.length === 2) {
                    params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
                }
            }
            return params;
        }
        
        // =====================================
        // FUNCIONES DE WEBSOCKET
        // =====================================
        
        function inicializarWebSocket() {
            if (socket) {
                socket.close();
            }
            
            log('Conectando a WebSocket...');
            actualizarEstadoWebSocket('warning', 'Conectando...');
            
            socket = new WebSocket(websocketUrl);
            
            socket.onopen = function() {
                log('✅ WebSocket conectado');
                actualizarEstadoWebSocket('success', 'Conectado');
                reconectarIntento = 0;
                
                // Mostrar badge de conexión exitosa
                mostrarBadgeWebSocket('success', 'WebSocket conectado');
            };
            
            socket.onmessage = function(event) {
                var mensaje = JSON.parse(event.data);
                log('📨 Mensaje recibido: ' + JSON.stringify(mensaje));
                
                // Ignorar mis propios mensajes
                if (mensaje.id_ejecutivo === miIdEjecutivo) {
                    log('⏭️ Ignorando mi propio mensaje');
                    return;
                }
                
                // Procesar diferentes tipos de mensajes
                if (mensaje.tipo === 'ejecutivo_actualizado') {
                    procesarActualizacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_creado') {
                    procesarCreacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_movido') {
                    procesarMovimientoWebSocket(mensaje);
                } else if (mensaje.tipo === 'ejecutivo_estado_cambiado') {
                    procesarCambioEstadoWebSocket(mensaje);
                }
            };
            
            socket.onclose = function() {
                log('🔴 WebSocket desconectado');
                actualizarEstadoWebSocket('danger', 'Desconectado');
                
                // Intentar reconectar
                if (reconectarIntento < maxReconectarIntentos) {
                    reconectarIntento++;
                    log('Intentando reconectar... (' + reconectarIntento + '/' + maxReconectarIntentos + ')');
                    setTimeout(inicializarWebSocket, 2000 * reconectarIntento);
                } else {
                    log('❌ Máximo número de intentos de reconexión alcanzado');
                    mostrarBadgeWebSocket('danger', 'WebSocket desconectado');
                }
            };
            
            socket.onerror = function(error) {
                log('❌ Error WebSocket: ' + error);
                actualizarEstadoWebSocket('danger', 'Error de conexión');
            };
        }
        
        function enviarMensajeWebSocket(tipo, datos) {
            if (socket && socket.readyState === WebSocket.OPEN) {
                var mensaje = {
                    tipo: tipo,
                    id_ejecutivo: miIdEjecutivo,
                    timestamp: new Date().toISOString(),
                    ...datos
                };
                
                socket.send(JSON.stringify(mensaje));
                log('📤 Mensaje enviado: ' + JSON.stringify(mensaje));
            } else {
                log('⚠️ WebSocket no está conectado, no se pudo enviar mensaje');
            }
        }
        
        function procesarActualizacionWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('🔄 Procesando actualización de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo en la estructura y actualizar
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                if (mensaje.campo && mensaje.hasOwnProperty('valor')) {
                    ejecutivo[mensaje.campo] = mensaje.valor;
                    log('Campo ' + mensaje.campo + ' actualizado a: ' + mensaje.valor);
                }
                
                // Regenerar los árboles para mostrar cambios
                generarArbolesPorPlantel();
                
                // Aplicar feedback visual
                aplicarFeedbackVisualEjecutivo(mensaje.id_eje, mensaje.campo);
                
                // Mostrar badge de actualización
                mostrarBadgeWebSocket('info', 'Ejecutivo actualizado: ' + (mensaje.campo || 'datos'));
            }
        }
        
        function procesarCreacionWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('➕ Procesando creación de ejecutivo ID: ' + mensaje.id_eje);
            
            // Recargar ejecutivos para mostrar el nuevo
            cargarEjecutivos().then(function() {
                cargarCitasPorPlantel();
                mostrarBadgeWebSocket('success', 'Nuevo ejecutivo creado');
            });
        }
        
        function procesarMovimientoWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('🔄 Procesando movimiento de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo y actualizar su posición
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                if (mensaje.hasOwnProperty('nuevo_padre')) {
                    ejecutivo.id_padre = mensaje.nuevo_padre;
                }
                if (mensaje.hasOwnProperty('nuevo_plantel')) {
                    ejecutivo.id_pla = mensaje.nuevo_plantel;
                }
                
                // Regenerar los árboles para mostrar cambios
                generarArbolesPorPlantel();
                
                // Aplicar feedback visual específico para movimiento
                setTimeout(function() {
                    aplicarFeedbackVisualEjecutivo(mensaje.id_eje, 'movimiento');
                }, 100);
                
                // Mostrar badge de notificación
                mostrarBadgeWebSocket('warning', 'Ejecutivo movido por otro usuario');
            }
        }
        
        function procesarCambioEstadoWebSocket(mensaje) {
            if (!mensaje.id_eje) {
                return;
            }
            
            log('🔄 Procesando cambio de estado de ejecutivo ID: ' + mensaje.id_eje);
            
            // Buscar el ejecutivo y actualizar su estado
            var ejecutivo = ejecutivos.find(e => e.id_eje == mensaje.id_eje);
            if (ejecutivo) {
                ejecutivo.eli_eje = mensaje.nuevo_estado;
                
                // Regenerar los árboles para mostrar cambios
                generarArbolesPorPlantel();
                
                // Aplicar feedback visual
                aplicarFeedbackVisualEjecutivo(mensaje.id_eje, 'estado');
                
                // Mostrar badge de cambio de estado
                var estadoTexto = mensaje.nuevo_estado == 1 ? 'activado' : 'desactivado';
                mostrarBadgeWebSocket('info', 'Ejecutivo ' + estadoTexto);
            }
        }
        
        function aplicarFeedbackVisualEjecutivo(id_eje, campo) {
            console.log('Aplicando feedback visual para ejecutivo:', id_eje, 'campo:', campo);
            
            // Buscar el nodo del ejecutivo en todos los árboles
            $('.plantel-tree').each(function() {
                var treeId = $(this).attr('id');
                var tree = $('#' + treeId);
                var node = tree.jstree('get_node', 'ejecutivo_' + id_eje);
                
                if (node && node.id) {
                    var nodeElement = tree.find('#' + node.id + '_anchor');
                    if (nodeElement.length) {
                        console.log('Aplicando estilo a nodo:', node.id, 'en árbol:', treeId);
                        
                        // Aplicar clase específica para movimiento
                        if (campo === 'movimiento') {
                            nodeElement.addClass('ejecutivo-movido');
                            console.log('Clase ejecutivo-movido aplicada por 3 segundos');
                            setTimeout(function() {
                                nodeElement.removeClass('ejecutivo-movido');
                                console.log('Clase ejecutivo-movido removida');
                            }, 3000); // Duración aumentada para movimiento
                        } else {
                            // Para otros cambios, usar el estilo general
                            nodeElement.addClass('websocket-changed');
                            setTimeout(function() {
                                nodeElement.removeClass('websocket-changed');
                            }, 2000);
                        }
                    } else {
                        console.warn('No se encontró elemento del nodo:', node.id);
                    }
                } else {
                    console.warn('No se encontró nodo ejecutivo_' + id_eje + ' en árbol:', treeId);
                }
            });
        }
        
        function mostrarBadgeWebSocket(tipo, mensaje) {
            var badge = $('<div class="badge badge-' + tipo + ' websocket-badge">' + mensaje + '</div>');
            $('body').append(badge);
            
            // Remover badge después de la animación
            setTimeout(function() {
                badge.remove();
            }, 3000);
        }
        
        function actualizarEstadoWebSocket(tipo, mensaje) {
            var statusElement = $('#websocket-status');
            var statusText = $('#websocket-status-text');
            
            statusElement.removeClass('success danger warning');
            statusElement.addClass(tipo);
            statusText.text(mensaje);
        }
        
        function log(mensaje) {
            var time = new Date().toLocaleTimeString();
            var logElement = $('#websocket-log-text');
            var currentLog = logElement.text();
            
            // Mantener solo los últimos 3 mensajes
            var lines = currentLog.split('\n').filter(function(line) { return line.trim() !== ''; });
            if (lines.length >= 3) {
                lines = lines.slice(-2);
            }
            
            lines.push('[' + time + '] ' + mensaje);
            logElement.text(lines.join('\n'));
            
            // Scroll automático
            $('#websocket-log')[0].scrollTop = $('#websocket-log')[0].scrollHeight;
            
            // También log en consola para debugging
            console.log('[WebSocket] ' + mensaje);
        }
        
        // =====================================
        // FUNCIONES DE CARGA DE DATOS
        // =====================================
        
        function cargarPlanteles() {
            return $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: { action: 'obtener_planteles' },
                dataType: 'json'
            }).done(function(response) {
                console.log('Respuesta planteles:', response);
                if(response.success) {
                    planteles = response.data;
                    console.log('Planteles cargados:', planteles.length);
                    generarPlanteles();
                    cargarSelectPlanteles();
                } else {
                    console.error('Error planteles:', response.message);
                    throw new Error('Error al cargar planteles: ' + response.message);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error AJAX planteles:', {xhr: xhr, status: status, error: error});
                console.error('Respuesta completa:', xhr.responseText);
                throw new Error('Error de conexión al cargar planteles');
            });
        }
        
        function cargarEjecutivos() {
            // Obtener fechas de filtro si existen
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            var datosEnvio = { 
                action: 'obtener_ejecutivos_con_citas'
            };
            
            // Agregar filtros de fecha si están definidos
            if (fechaInicio) {
                datosEnvio.fecha_inicio = fechaInicio;
            }
            if (fechaFin) {
                datosEnvio.fecha_fin = fechaFin;
            }
            
            return $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: datosEnvio,
                dataType: 'json'
            }).done(function(response) {
                console.log('Respuesta ejecutivos con citas:', response);
                if(response.success) {
                    ejecutivos = response.data;
                    console.log('Ejecutivos cargados:', ejecutivos.length);
                    console.log('Datos de ejecutivos:', ejecutivos);
                    
                    // Debug: mostrar distribución
                    var distribucion = {};
                    ejecutivos.forEach(function(ej) {
                        var plantel = ej.id_pla || 'Sin plantel';
                        distribucion[plantel] = (distribucion[plantel] || 0) + 1;
                    });
                    console.log('Distribución por plantel:', distribucion);
                    
                    // Debug: verificar ejecutivos por cada plantel
                    planteles.forEach(function(p) {
                        var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == p.id_pla);
                        console.log('Plantel', p.nom_pla, '(ID:', p.id_pla, ') tiene', ejecutivosPlantel.length, 'ejecutivos');
                        ejecutivosPlantel.forEach(function(ej) {
                            console.log('  -', ej.nom_eje, 'Activo:', ej.eli_eje, 'Padre:', ej.id_padre, 'Citas propias:', ej.citas_propias, 'Citas recursivas:', ej.citas_recursivas);
                        });
                    });
                    
                    generarArbolesPorPlantel();
                    actualizarEstadisticas();
                } else {
                    console.error('Error ejecutivos:', response.message);
                    throw new Error('Error al cargar ejecutivos: ' + response.message);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error AJAX ejecutivos:', {xhr: xhr, status: status, error: error});
                console.error('Respuesta completa:', xhr.responseText);
                throw new Error('Error de conexión al cargar ejecutivos');
            });
        }
        
        // =====================================
        // FUNCIONES DE GENERACIÓN DE PLANTELES
        // =====================================
        
        function generarPlanteles() {
            var html = '';
            
            planteles.forEach(function(plantel) {
                // Calcular conteo de ejecutivos por plantel
                var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == plantel.id_pla);
                
                html += `
                    <div class="col-md-4">
                        <div class="plantel-container" data-plantel-id="${plantel.id_pla}">
                            <div class="plantel-header">
                                <i class="fas fa-building"></i> ${plantel.nom_pla}
                                <div class="badge badge-light" id="count-plantel-${plantel.id_pla}">
                                    ${ejecutivosPlantel.length}
                                </div>
                                <span class="badge badge-citas-plantel ml-2" id="badge-citas-plantel-${plantel.id_pla}" onclick="verDetallesCitasPlantel(${plantel.id_pla})" title="Cargando citas..." style="display: none;">0</span>
                            </div>
                            <div class="plantel-tree" id="jstree-plantel-${plantel.id_pla}"></div>
                        </div>
                    </div>
                `;
            });
            
            $('#planteles-container').html(html);
            
            // Cargar conteo de citas por plantel de forma asíncrona
            cargarCitasPorPlantel();
        }
        
        function cargarCitasPorPlantel() {
            // Obtener fechas de filtro si existen
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            planteles.forEach(function(plantel) {
                var datosEnvio = { 
                    action: 'obtener_citas_totales_por_plantel',
                    id_pla: plantel.id_pla
                };
                
                // Agregar filtros de fecha si están definidos
                if (fechaInicio) {
                    datosEnvio.fecha_inicio = fechaInicio;
                }
                if (fechaFin) {
                    datosEnvio.fecha_fin = fechaFin;
                }
                
                $.ajax({
                    url: 'server/controlador_ejecutivos.php',
                    type: 'POST',
                    data: datosEnvio,
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            var totalCitas = response.data.total_citas;
                            var badgeElement = $('#badge-citas-plantel-' + plantel.id_pla);
                            
                            if(totalCitas > 0) {
                                badgeElement.text(totalCitas + (totalCitas >= 2 ? ' citas totales' : ' cita total'))
                                .attr('title', 'Citas totales del plantel: ' + totalCitas)
                                .show();
                            } else {
                                badgeElement.hide();
                            }
                        } else {
                            console.error('Error al obtener citas del plantel', plantel.id_pla, ':', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX citas plantel', plantel.id_pla, ':', error);
                    }
                });
            });
        }
        
        function generarArbolesPorPlantel() {
            console.log('=== GENERANDO ÁRBOLES POR PLANTEL ===');
            console.log('Planteles disponibles:', planteles.length);
            console.log('Ejecutivos disponibles:', ejecutivos.length);
            
            planteles.forEach(function(plantel) {
                //console.log('Procesando plantel:', plantel.nom_pla, '(ID:', plantel.id_pla, ')');
                
                var ejecutivosPlantel = ejecutivos.filter(ej => ej.id_pla == plantel.id_pla);
                
                var nodosTree = generarNodosJsTree(ejecutivosPlantel);
                
                // Actualizar contador
                $('#count-plantel-' + plantel.id_pla).text(ejecutivosPlantel.length);
                
                // Generar árbol
                var treeId = '#jstree-plantel-' + plantel.id_pla;
                
                // Verificar que el elemento existe
                if($(treeId).length === 0) {
                    console.error('ERROR: Elemento', treeId, 'no encontrado');
                    return;
                }
                
                // Destruir árbol existente si existe
                if($(treeId).hasClass('jstree')) {
                    $(treeId).jstree('destroy');
                    console.log('Árbol anterior destruido para:', treeId);
                }
                
                // Validar que hay nodos para mostrar
                if(nodosTree.length === 0) {
                    $(treeId).html('<p class="text-center text-muted">No hay ejecutivos en este plantel</p>');
                    return;
                }
                
                // Crear nuevo árbol usando closure para preservar el contexto del plantel
                (function(currentPlantel, currentTreeId, currentNodes) {
                    $(currentTreeId).jstree({
                        'core': {
                            'data': currentNodes,
                            'check_callback': function(operation, node, parent, position, more) {
                                if(operation === 'move_node') {
                                    return true; // Permitir movimiento
                                }
                                return true;
                            },
                            'themes': {
                                'responsive': true,
                                'variant': 'large',
                                'stripes': false,
                                'dots': true,
                                'icons': true
                            }
                        },
                        'plugins': ['dnd', 'types', 'state'],
                        'state': {
                            'key': 'jstree_plantel_' + currentPlantel.id_pla
                        },
                        'dnd': {
                            'is_draggable': function(node) {
                                return true;
                            },
                            'copy': false,
                            'large_drop_target': true,
                            'large_drag_target': true,
                            'touch': true,
                            'inside_pos': 'last',
                            'check_while_dragging': true,
                            'always_copy': false,
                            'drag_selection': false,
                            'use_html5': true,
                            // Permitir drops externos en cualquier parte del árbol
                            'check_callback': function(operation, node, parent, position, more) {
                                // Permitir drops externos (entre planteles)
                                if(operation === 'dnd_start' || operation === 'dnd_stop') {
                                    return true;
                                }
                                
                                // Para movimientos dentro del mismo árbol
                                if(operation === 'move_node') {
                                    return true;
                                }
                                
                                // Para drops externos
                                if(operation === 'copy_node' || operation === 'move_node') {
                                    return true;
                                }
                                
                                return true;
                            }
                        },
                        'types': {
                            'default': {
                                'icon': 'fas fa-user'
                            },
                            'inactive': {
                                'icon': 'fas fa-user-slash'
                            }
                        }
                    }).on('ready.jstree', function() {
                        console.log('✅ Árbol jsTree inicializado correctamente para:', currentPlantel.nom_pla);
                        console.log('   - Nodos creados:', currentNodes.length);
                        
                        // Expandir todos los nodos para mostrar la estructura completa
                        $(currentTreeId).jstree('open_all');
                        
                        // Aplicar estilos adicionales para mejorar la visualización jerárquica
                        setTimeout(function() {
                            // Verificar cuántos nodos están visibles
                            var visibleNodes = $(currentTreeId).find('.jstree-node').length;
                            console.log('   - Nodos visibles en el DOM:', visibleNodes);
                            
                            // Agregar clases específicas para mejorar la visualización por niveles
                            $(currentTreeId).find('.jstree-node[aria-level="1"]').addClass('jstree-root-level');
                            $(currentTreeId).find('.jstree-node[aria-level="2"]').addClass('jstree-level-2');
                            $(currentTreeId).find('.jstree-node[aria-level="3"]').addClass('jstree-level-3');
                            $(currentTreeId).find('.jstree-node[aria-level="4"]').addClass('jstree-level-4');
                            $(currentTreeId).find('.jstree-node[aria-level="5"]').addClass('jstree-level-5');
                            
                            // Añadir iconos específicos por nivel
                            $(currentTreeId).find('.jstree-root-level .jstree-icon').removeClass('fas fa-user').addClass('fas fa-crown');
                            $(currentTreeId).find('.jstree-level-2 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-tie');
                            $(currentTreeId).find('.jstree-level-3 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-friends');
                            $(currentTreeId).find('.jstree-level-4 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-check');
                            $(currentTreeId).find('.jstree-level-5 .jstree-icon').removeClass('fas fa-user').addClass('fas fa-user-plus');
                            
                            console.log('   - Clases de nivel aplicadas correctamente');
                        }, 150);
                    }).on('select_node.jstree', function(e, data) {
                        var ejecutivo = ejecutivos.find(ej => ej.id_eje == data.node.id);
                        if(ejecutivo) {
                            console.log('Ejecutivo seleccionado:', ejecutivo.nom_eje);
                            mostrarInformacionEjecutivo(ejecutivo);
                            nodoSeleccionado = ejecutivo;
                        }
                    }).on('move_node.jstree', function(e, data) {
                        // Manejar movimiento dentro del mismo plantel
                        var ejecutivoId = data.node.id;
                        var nuevoPadreId = data.parent === '#' ? null : data.parent;
                        
                        console.log('Movimiento dentro del plantel:', currentPlantel.id_pla, 'ejecutivo:', ejecutivoId, 'nuevo padre:', nuevoPadreId);
                        moverEjecutivo(ejecutivoId, nuevoPadreId, currentPlantel.id_pla);
                    }).on('dnd_start.vakata', function(e, data) {
                        console.log('DND Start desde árbol:', currentTreeId, 'plantel:', currentPlantel.id_pla);
                        // Establecer variables globales para drag & drop entre planteles
                        if(data.data && data.data.nodes && data.data.nodes.length > 0) {
                            draggedNode = data.data.nodes[0];
                            draggedFromPlantel = currentPlantel.id_pla;
                            draggedExecutivo = ejecutivos.find(ej => ej.id_eje == draggedNode);
                            console.log('Variables drag establecidas:', {draggedNode, draggedFromPlantel, draggedExecutivo});
                        }
                    });
                })(plantel, treeId, nodosTree);
            });
            
            // Configurar drag & drop entre planteles después de crear los árboles
            setTimeout(function() {
                configurarDragDropEntrePlanteles();
                
                // Inicializar tooltips para el semáforo de sesión
                $('[data-toggle="tooltip"]').tooltip({
                    placement: 'top',
                    trigger: 'hover',
                    delay: { show: 300, hide: 100 }
                });
                
                console.log('Tooltips del semáforo inicializados');
            }, 500);
        }
        
        function generarNodosJsTree(ejecutivosPlantel) {
            var nodos = [];
            var nodosMap = {};
            
            console.log('=== GENERANDO NODOS JSTREE ===');
            console.log('Ejecutivos recibidos:', ejecutivosPlantel.length);
            console.log('Lista de ejecutivos:', ejecutivosPlantel);
            
            // Crear nodos
            ejecutivosPlantel.forEach(function(ejecutivo, index) {
                console.log('Procesando ejecutivo', index + 1, ':', ejecutivo.nom_eje);
                
                var icono = ejecutivo.eli_eje == 1 ? 'fas fa-user text-success' : 'fas fa-user-slash text-danger';
                var badge = ejecutivo.eli_eje == 1 ? 
                    '<span class="badge badge-success ml-1">Activo</span>' : 
                    '<span class="badge badge-danger ml-1">Inactivo</span>';
                
                // Generar indicador de semáforo de sesión P15
                var semaforoHtml = '';
                var tooltipText = '';
                
                console.log('  - Datos semáforo ejecutivo', ejecutivo.nom_eje, ':', {
                    semaforo_sesion: ejecutivo.semaforo_sesion,
                    dias_desde_ultima_sesion: ejecutivo.dias_desde_ultima_sesion,
                    ult_eje: ejecutivo.ult_eje
                });
                
                if (ejecutivo.semaforo_sesion) {
                    var colorSemaforo = ejecutivo.semaforo_sesion;
                    
                    // Generar texto del tooltip
                    switch(colorSemaforo) {
                        case 'verde':
                            tooltipText = 'Sesión reciente (≤1 día)';
                            if (ejecutivo.dias_desde_ultima_sesion !== null) {
                                tooltipText += ' - Último acceso: ' + ejecutivo.dias_desde_ultima_sesion + ' día(s)';
                            }
                            break;
                        case 'amarillo':
                            tooltipText = 'Sesión moderada (2-3 días)';
                            if (ejecutivo.dias_desde_ultima_sesion !== null) {
                                tooltipText += ' - Último acceso: ' + ejecutivo.dias_desde_ultima_sesion + ' día(s)';
                            }
                            break;
                        case 'rojo':
                            tooltipText = 'Sesión antigua (≥4 días)';
                            if (ejecutivo.dias_desde_ultima_sesion !== null) {
                                tooltipText += ' - Último acceso: ' + ejecutivo.dias_desde_ultima_sesion + ' día(s)';
                            }
                            break;
                        case 'sin_sesion':
                            tooltipText = 'Sin registro de sesión';
                            break;
                    }
                    
                    semaforoHtml = '<span class="semaforo-sesion ' + colorSemaforo + '" title="' + tooltipText + '" data-toggle="tooltip"></span>';
                    console.log('  - Semáforo generado:', colorSemaforo, 'HTML:', semaforoHtml);
                } else {
                    console.warn('  - No hay datos de semáforo para ejecutivo', ejecutivo.nom_eje);
                }
                
                // Generar emojis de planteles asociados
                var plantelesEmojis = '';
                if (ejecutivo.planteles_asociados_array && ejecutivo.planteles_asociados_array.length > 0) {
                    // Crear un emoji 🕋 por cada plantel asociado
                    plantelesEmojis = ' ' + '🕋'.repeat(ejecutivo.planteles_asociados_array.length);
                    console.log('  - Planteles asociados:', ejecutivo.planteles_asociados_array.length, 'planteles');
                }
                
                // Construir badges de conteo de citas - P18
                var badgesCitas = '';
                var badgesPropias = ejecutivo.citas_propias || 0;
                var badgesRecursivas = ejecutivo.citas_recursivas || 0;
                
                if (badgesPropias > 0) {
                    badgesCitas += '<span class="badge badge-citas-propias" onclick="verDetallesCitas(' + ejecutivo.id_eje + ', \'propias\')" title="Citas propias: ' + badgesPropias + '">' + badgesPropias + '</span>';
                }
                if (badgesRecursivas > 0) {
                    badgesCitas += '<span class="badge badge-citas-recursivas" onclick="verDetallesCitas(' + ejecutivo.id_eje + ', \'recursivas\')" title="Citas totales (recursivas): ' + badgesRecursivas + '">' + badgesRecursivas + '</span>';
                }
                
                console.log('  - Badges citas:', 'Propias:', badgesPropias, 'Recursivas:', badgesRecursivas);
                
                // Verificar si el padre existe en el mismo plantel
                var parent = '#'; // Por defecto es raíz
                if (ejecutivo.id_padre) {
                    var padreExiste = ejecutivosPlantel.find(ej => ej.id_eje == ejecutivo.id_padre);
                    if (padreExiste) {
                        parent = ejecutivo.id_padre;
                        console.log('  - Padre encontrado:', padreExiste.nom_eje, 'ID:', padreExiste.id_eje);
                    } else {
                        console.warn('  - Padre', ejecutivo.id_padre, 'no encontrado en el mismo plantel para ejecutivo', ejecutivo.nom_eje, '- se pondrá como raíz');
                    }
                } else {
                    console.log('  - Sin padre, será nodo raíz');
                }
                
                var nodo = {
                    'id': ejecutivo.id_eje,
                    'text': '<div class="jstree-anchor-content"><span class="ejecutivo-nombre">' + ejecutivo.nom_eje + '</span><div class="jstree-anchor-indicators">' + semaforoHtml + plantelesEmojis + ' ' + badge + badgesCitas + '</div></div>',
                    'icon': icono,
                    'parent': parent,
                    'data': ejecutivo,
                    'type': ejecutivo.eli_eje == 1 ? 'default' : 'inactive'
                };
                
                console.log('  - Nodo creado:', {
                    id: nodo.id,
                    text: ejecutivo.nom_eje,
                    parent: nodo.parent,
                    activo: ejecutivo.eli_eje
                });
                
                nodos.push(nodo);
                nodosMap[ejecutivo.id_eje] = nodo;
            });
            
            console.log('Total nodos generados:', nodos.length);
            console.log('Nodos finales:', nodos);
            
            // Validar estructura jerárquica
            var nodosRaiz = nodos.filter(n => n.parent === '#');
            var nodosHijos = nodos.filter(n => n.parent !== '#');
            console.log('Nodos raíz:', nodosRaiz.length, 'Nodos hijos:', nodosHijos.length);
            
            // Mostrar estructura de árbol
            nodosRaiz.forEach(function(raiz) {
                console.log('RAÍZ:', raiz.data.nom_eje);
                mostrarHijos(raiz, nodos, '  ');
            });
            
            return nodos;
        }
        
        function mostrarHijos(nodo, todosLosNodos, prefijo) {
            var hijos = todosLosNodos.filter(n => n.parent == nodo.id);
            hijos.forEach(function(hijo) {
                console.log(prefijo + '└─ ' + hijo.data.nom_eje);
                mostrarHijos(hijo, todosLosNodos, prefijo + '  ');
            });
        }
        
        // =====================================
        // FUNCIONES DE DRAG & DROP
        // =====================================
        
        function configurarDragDrop() {
            // Esta función ya no es necesaria, se reemplazó por configurarDragDropEntrePlanteles
        }
        
        function configurarDragDropEntrePlanteles() {
            console.log('Configurando drag & drop entre planteles...');
            
            // Limpiar eventos anteriores
            $('.plantel-container').off('dragover.planteles dragenter.planteles dragleave.planteles drop.planteles');
            
            // Asegurar que los contenedores de planteles sean drop zones
            $('.plantel-container').each(function() {
                var $container = $(this);
                var plantelId = $container.data('plantel-id');
                
                console.log('Configurando drop zone para plantel:', plantelId);
                
                $container.on('dragover.planteles dragenter.planteles', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if(draggedNode && draggedFromPlantel && $(this).data('plantel-id') != draggedFromPlantel) {
                        var targetPlantelId = $(this).data('plantel-id');
                        var targetPlantel = planteles.find(p => p.id_pla == targetPlantelId);
                        
                        // Solo log la primera vez que entra a la zona
                        if(!$(this).hasClass('drop-zone')) {
                            console.log('🎯 PLANTEL DESTINO:', targetPlantel ? targetPlantel.nom_pla : 'Desconocido');
                            
                            // Resaltar visualmente el plantel destino
                            $(this).addClass('plantel-destino-highlight');
                        }
                        
                        $(this).addClass('drop-zone');
                    }
                });
                
                // También agregar eventos a los elementos internos del plantel (jsTree nodes)
                $container.on('dragover.planteles dragenter.planteles', '.jstree-node, .jstree-anchor, .plantel-tree', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Propagar el evento al contenedor padre
                    var container = $(this).closest('.plantel-container');
                    if(draggedNode && draggedFromPlantel && container.data('plantel-id') != draggedFromPlantel) {
                        var targetPlantelId = container.data('plantel-id');
                        var targetPlantel = planteles.find(p => p.id_pla == targetPlantelId);
                        
                        if(!container.hasClass('drop-zone')) {
                            console.log('🎯 PLANTEL DESTINO (sobre nodo):', targetPlantel ? targetPlantel.nom_pla : 'Desconocido');
                            container.addClass('plantel-destino-highlight');
                        }
                        
                        container.addClass('drop-zone');
                    }
                });
                
                $container.on('drop.planteles', '.jstree-node, .jstree-anchor, .plantel-tree', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Propagar el drop al contenedor padre
                    var container = $(this).closest('.plantel-container');
                    container.trigger('drop.planteles');
                });
                
                $container.on('dragleave.planteles', function(e) {
                    e.stopPropagation();
                    
                    // Solo remover la clase si realmente salimos del contenedor
                    var rect = this.getBoundingClientRect();
                    var x = e.originalEvent.clientX;
                    var y = e.originalEvent.clientY;
                    
                    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                        $(this).removeClass('drop-zone plantel-destino-highlight');
                    }
                });
                
                // También manejar dragleave en elementos internos
                $container.on('dragleave.planteles', '.jstree-node, .jstree-anchor, .plantel-tree', function(e) {
                    // Solo actuar si salimos completamente del contenedor padre
                    var container = $(this).closest('.plantel-container');
                    var rect = container[0].getBoundingClientRect();
                    var x = e.originalEvent.clientX;
                    var y = e.originalEvent.clientY;
                    
                    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                        container.removeClass('drop-zone plantel-destino-highlight');
                    }
                });
                
                $container.on('drop.planteles', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('📍 DROP EJECUTADO');
                    $(this).removeClass('drop-zone plantel-destino-highlight');
                    
                    var targetPlantel = $(this).data('plantel-id');
                    var plantelDestinoInfo = planteles.find(p => p.id_pla == targetPlantel);
                    
                    // LOG DEL PLANTEL DESTINO
                    console.log('🎯 PLANTEL DESTINO:', plantelDestinoInfo ? plantelDestinoInfo.nom_pla : 'Desconocido');
                    
                    if(draggedExecutivo && plantelDestinoInfo) {
                        console.log('👤 EJECUTIVO MOVIDO A:', plantelDestinoInfo.nom_pla);
                    }
                    
                    if(draggedNode && draggedFromPlantel && targetPlantel != draggedFromPlantel) {
                        var ejecutivoId = draggedNode;
                        
                        if(draggedExecutivo) {
                            console.log('Iniciando movimiento de ejecutivo:', {
                                nombre: draggedExecutivo.nom_eje,
                                id: ejecutivoId,
                                desde: draggedFromPlantel,
                                hacia: targetPlantel
                            });
                            
                            // Mover ejecutivo a nuevo plantel (sin padre - será raíz en el nuevo plantel)
                            moverEjecutivo(ejecutivoId, null, targetPlantel);
                        } else {
                            console.error('No se encontró información del ejecutivo');
                            mostrarMensajeDragDrop('Error: No se encontró información del ejecutivo', false, true);
                        }
                    } else if(targetPlantel == draggedFromPlantel) {
                        console.log('No se mueve porque es el mismo plantel');
                    } else {
                        console.warn('Faltan datos para el movimiento:', {
                            draggedNode: !!draggedNode,
                            draggedFromPlantel: !!draggedFromPlantel,
                            targetPlantel: !!targetPlantel
                        });
                    }
                });
            });
            
            console.log('Drag & drop entre planteles configurado');
            
            // Configuración adicional usando delegación de eventos para mayor robustez
            // Esto asegura que los drops funcionen en cualquier elemento hijo del contenedor
            $(document).on('dragover.delegado dragenter.delegado', '.plantel-container', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if(draggedNode && draggedFromPlantel && $(this).data('plantel-id') != draggedFromPlantel) {
                    var targetPlantelId = $(this).data('plantel-id');
                    var targetPlantel = planteles.find(p => p.id_pla == targetPlantelId);
                    
                    if(!$(this).hasClass('drop-zone')) {
                        console.log('🎯 PLANTEL DESTINO (delegado):', targetPlantel ? targetPlantel.nom_pla : 'Desconocido');
                        $(this).addClass('plantel-destino-highlight');
                    }
                    $(this).addClass('drop-zone');
                }
            });
            
            $(document).on('drop.delegado', '.plantel-container', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Solo procesar si tenemos las variables necesarias
                if(!draggedNode || !draggedFromPlantel) {
                    return;
                }
                
                var targetPlantel = $(this).data('plantel-id');
                var plantelDestinoInfo = planteles.find(p => p.id_pla == targetPlantel);
                
                console.log('📍 DROP DELEGADO EN PLANTEL:', plantelDestinoInfo ? plantelDestinoInfo.nom_pla : 'Desconocido');
                $(this).removeClass('drop-zone plantel-destino-highlight');
                
                if(targetPlantel && targetPlantel != draggedFromPlantel && draggedExecutivo) {
                    console.log('👤 EJECUTIVO MOVIDO A (delegado):', plantelDestinoInfo.nom_pla);
                    
                    // Resetear variables antes de mover
                    var tempEjecutivoId = draggedNode;
                    var tempTargetPlantel = targetPlantel;
                    
                    draggedNode = null;
                    draggedFromPlantel = null;
                    draggedExecutivo = null;
                    
                    // Mover ejecutivo a nuevo plantel
                    moverEjecutivo(tempEjecutivoId, null, tempTargetPlantel);
                }
            });
        }
        
        function moverEjecutivo(ejecutivoId, nuevoPadreId, nuevoPlantelId) {
            console.log('=== FUNCIÓN MOVER EJECUTIVO ===');
            console.log('moverEjecutivo llamada con:', {
                ejecutivoId: ejecutivoId,
                nuevoPadreId: nuevoPadreId,
                nuevoPlantelId: nuevoPlantelId
            });
            
            // Validar que tenemos un ID válido
            if(!ejecutivoId || ejecutivoId === '') {
                console.error('ID de ejecutivo no válido:', ejecutivoId);
                mostrarMensajeDragDrop('Error: ID de ejecutivo no válido', false, true);
                return;
            }
            
            // Validar que tenemos un plantel válido
            if(!nuevoPlantelId || nuevoPlantelId === '') {
                console.error('ID de plantel no válido:', nuevoPlantelId);
                mostrarMensajeDragDrop('Error: ID de plantel no válido', false, true);
                return;
            }
            
            // Buscar información del ejecutivo para validación
            var ejecutivo = ejecutivos.find(ej => ej.id_eje == ejecutivoId);
            if(!ejecutivo) {
                console.error('No se encontró el ejecutivo con ID:', ejecutivoId);
                mostrarMensajeDragDrop('Error: No se encontró el ejecutivo', false, true);
                return;
            }
            
            // Buscar información del plantel destino
            var plantelDestino = planteles.find(p => p.id_pla == nuevoPlantelId);
            if(!plantelDestino) {
                console.error('No se encontró el plantel con ID:', nuevoPlantelId);
                mostrarMensajeDragDrop('Error: No se encontró el plantel destino', false, true);
                return;
            }
            
            console.log('Validación exitosa - Moviendo:', {
                ejecutivo: ejecutivo.nom_eje,
                plantelAnterior: ejecutivo.id_pla,
                plantelDestino: plantelDestino.nom_pla
            });
            
            mostrarMensajeDragDrop('Moviendo ejecutivo...', false, false);
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: {
                    action: 'mover_ejecutivo',
                    id_eje: ejecutivoId,
                    id_padre: nuevoPadreId,
                    id_pla: nuevoPlantelId
                },
                dataType: 'json',
                success: function(response) {
                    console.log('=== RESPUESTA DEL SERVIDOR ===');
                    console.log('Respuesta completa:', response);
                    
                    if(response.success) {
                        console.log('Movimiento exitoso');
                        mostrarMensajeDragDrop('✓ Ejecutivo movido correctamente', true, false);
                        
                        // Actualizar datos locales
                        if(ejecutivo) {
                            var padreAnterior = ejecutivo.id_padre;
                            var plantelAnterior = ejecutivo.id_pla;
                            
                            ejecutivo.id_padre = nuevoPadreId;
                            ejecutivo.id_pla = nuevoPlantelId;
                            console.log('Datos locales actualizados');
                            
                            // Enviar mensaje WebSocket de movimiento
                            enviarMensajeWebSocket('ejecutivo_movido', {
                                id_eje: ejecutivoId,
                                nuevo_padre: nuevoPadreId,
                                nuevo_plantel: nuevoPlantelId,
                                padre_anterior: padreAnterior,
                                plantel_anterior: plantelAnterior,
                                nombre_ejecutivo: ejecutivo.nom_eje
                            });
                        }
                        
                        // Recargar vista
                        recargarTodos();
                        
                        // Limpiar estado del drag
                        limpiarEstadoDrag();
                        
                        // Aplicar feedback visual local después del reload
                        setTimeout(function() {
                            aplicarFeedbackVisualEjecutivo(ejecutivoId, 'movimiento');
                        }, 500);
                    } else {
                        console.error('Error del servidor:', response.message);
                        mostrarMensajeDragDrop('✗ Error: ' + response.message, false, true);
                        limpiarEstadoDrag();
                        recargarTodos();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== ERROR DE CONEXIÓN ===');
                    console.error('Error AJAX:', error);
                    console.error('Status:', status);
                    console.error('Respuesta completa:', xhr.responseText);
                    
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        console.error('Error parseado:', errorResponse);
                        mostrarMensajeDragDrop('✗ Error: ' + errorResponse.message, false, true);
                    } catch(e) {
                        mostrarMensajeDragDrop('✗ Error de conexión: ' + error, false, true);
                    }
                    
                    limpiarEstadoDrag();
                    recargarTodos();
                }
            });
        }
        
        // =====================================
        // FUNCIONES DE INTERFAZ
        // =====================================
        
        function mostrarInformacionEjecutivo(ejecutivo) {
            var plantel = planteles.find(p => p.id_pla == ejecutivo.id_pla);
            var padre = ejecutivos.find(e => e.id_eje == ejecutivo.id_padre);
            
            // Información del semáforo de sesión
            var semaforoInfo = '';
            if (ejecutivo.semaforo_sesion) {
                var semaforoClass = ejecutivo.semaforo_sesion;
                var semaforoTexto = '';
                
                switch(semaforoClass) {
                    case 'verde':
                        semaforoTexto = 'Sesión reciente (≤1 día)';
                        break;
                    case 'amarillo':
                        semaforoTexto = 'Sesión moderada (2-3 días)';
                        break;
                    case 'rojo':
                        semaforoTexto = 'Sesión antigua (≥4 días)';
                        break;
                    case 'sin_sesion':
                        semaforoTexto = 'Sin registro de sesión';
                        break;
                }
                
                var ultimaSesion = ejecutivo.ult_eje ? new Date(ejecutivo.ult_eje).toLocaleString('es-ES') : 'Nunca';
                var diasDesde = ejecutivo.dias_desde_ultima_sesion !== null ? ejecutivo.dias_desde_ultima_sesion + ' día(s)' : 'N/A';
                
                semaforoInfo = `
                    <div class="col-12 mt-3">
                        <h6><i class="fas fa-traffic-light"></i> Estado de Sesión</h6>
                        <div class="d-flex align-items-center mb-2">
                            <span class="semaforo-sesion ${semaforoClass} mr-2"></span>
                            <span>${semaforoTexto}</span>
                        </div>
                        <small class="text-muted">
                            <strong>Última sesión:</strong> ${ultimaSesion}<br>
                            <strong>Tiempo transcurrido:</strong> ${diasDesde}
                        </small>
                    </div>
                `;
            }
            
            var html = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre:</strong> ${ejecutivo.nom_eje}<br>
                        <strong>Teléfono:</strong> ${ejecutivo.tel_eje}<br>
                        <strong>Plantel:</strong> ${plantel ? plantel.nom_pla : 'Sin plantel'}
                    </div>
                    <div class="col-md-6">
                        <strong>Jefe:</strong> ${padre ? padre.nom_eje : 'Sin jefe (Raíz)'}<br>
                        <strong>Estado:</strong> ${ejecutivo.eli_eje == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>'
                        }
                    </div>
                    ${semaforoInfo}
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary" onclick="mostrarModalEditar()">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-${ejecutivo.eli_eje == 1 ? 'warning' : 'success'}" onclick="toggleEstado()">
                        <i class="fas fa-${ejecutivo.eli_eje == 1 ? 'eye-slash' : 'eye'}"></i> 
                        ${ejecutivo.eli_eje == 1 ? 'Ocultar' : 'Mostrar'}
                    </button>
                </div>
            `;
            
            $('#ejecutivo-info').html(html);
            $('#info-panel').show();
        }
        
        function actualizarEstadisticas() {
            var total = ejecutivos.length;
            var activos = ejecutivos.filter(e => e.eli_eje == 1).length;
            var ocultos = ejecutivos.filter(e => e.eli_eje == 0).length;
            
            // Estadísticas del semáforo P15
            var verde = ejecutivos.filter(e => e.semaforo_sesion === 'verde').length;
            var amarillo = ejecutivos.filter(e => e.semaforo_sesion === 'amarillo').length;
            var rojo = ejecutivos.filter(e => e.semaforo_sesion === 'rojo').length;
            var sinSesion = ejecutivos.filter(e => e.semaforo_sesion === 'sin_sesion').length;
            
            var html = `
                <div class="col-md-3 stat-item">
                    <div class="stat-number" id="total-ejecutivos">${total}</div>
                    <div class="stat-label">Total Ejecutivos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number text-success" id="ejecutivos-activos">${activos}</div>
                    <div class="stat-label">Activos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number text-danger" id="ejecutivos-ocultos">${ocultos}</div>
                    <div class="stat-label">Ocultos</div>
                </div>
                <div class="col-md-3 stat-item">
                    <div class="stat-number text-info">${planteles.length}</div>
                    <div class="stat-label">Planteles</div>
                </div>
            `;
            
            // Agregar estadísticas del semáforo
            html += `
                <div class="col-12 mt-3">
                    <h6 class="text-center mb-3">Estado de Sesiones</h6>
                    <div class="stats-semaforo justify-content-center">
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion verde"></span>
                            <span>${verde} Verde</span>
                        </div>
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion amarillo"></span>
                            <span>${amarillo} Amarillo</span>
                        </div>
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion rojo"></span>
                            <span>${rojo} Rojo</span>
                        </div>
                        <div class="stat-semaforo">
                            <span class="semaforo-sesion sin_sesion"></span>
                            <span>${sinSesion} Sin sesión</span>
                        </div>
                    </div>
                </div>
            `;
            
            $('#estadisticas').html(html);
        }
        
        function recargarTodos() {
            cargarEjecutivos().then(function() {
                cargarCitasPorPlantel(); // Actualizar conteo de citas por plantel
            });
        }
        
        // =====================================
        // FUNCIONES DE CRUD
        // =====================================
        
        function mostrarModalCrear() {
            modoEdicion = false;
            $('#modalTitulo').text('Crear Ejecutivo');
            $('#formEjecutivo')[0].reset();
            $('#ejecutivo_id').val('');
            $('#ejecutivo_activo').prop('checked', true);
            
            cargarSelectPadres();
            $('#modalEjecutivo').modal('show');
        }
        
        function mostrarModalEditar() {
            if(!nodoSeleccionado) {
                alert('Por favor selecciona un ejecutivo');
                return;
            }
            
            modoEdicion = true;
            $('#modalTitulo').text('Editar Ejecutivo');
            
            $('#ejecutivo_id').val(nodoSeleccionado.id_eje);
            $('#ejecutivo_nombre').val(nodoSeleccionado.nom_eje);
            $('#ejecutivo_telefono').val(nodoSeleccionado.tel_eje);
            $('#ejecutivo_plantel').val(nodoSeleccionado.id_pla);
            $('#ejecutivo_padre').val(nodoSeleccionado.id_padre);
            $('#ejecutivo_activo').prop('checked', nodoSeleccionado.eli_eje == 1);
            
            cargarSelectPadres(nodoSeleccionado.id_pla, nodoSeleccionado.id_eje);
            $('#modalEjecutivo').modal('show');
        }
        
        function cargarSelectPlanteles() {
            var html = '<option value="">Seleccione un plantel</option>';
            planteles.forEach(function(plantel) {
                html += `<option value="${plantel.id_pla}">${plantel.nom_pla}</option>`;
            });
            $('#ejecutivo_plantel').html(html);
        }
        
        function cargarSelectPadres(plantelSeleccionado = null, valorActual = null) {
            var html = '<option value="">Sin jefe (Raíz)</option>';
            
            var plantelId = plantelSeleccionado || $('#ejecutivo_plantel').val();
            
            if(plantelId) {
                var ejecutivosPlantel = ejecutivos.filter(e => e.id_pla == plantelId && e.eli_eje == 1);
                
                ejecutivosPlantel.forEach(function(ejecutivo) {
                    if(ejecutivo.id_eje != valorActual) {
                        html += `<option value="${ejecutivo.id_eje}">${ejecutivo.nom_eje}</option>`;
                    }
                });
            }
            
            $('#ejecutivo_padre').html(html);
        }
        
        // Evento para actualizar select de padres cuando cambia el plantel
        $('#ejecutivo_plantel').on('change', function() {
            cargarSelectPadres($(this).val(), $('#ejecutivo_id').val());
        });
        
        function guardarEjecutivo() {
            var formData = {
                action: modoEdicion ? 'actualizar_ejecutivo' : 'crear_ejecutivo',
                nom_eje: $('#ejecutivo_nombre').val(),
                tel_eje: $('#ejecutivo_telefono').val(),
                id_pla: $('#ejecutivo_plantel').val(),
                id_padre: $('#ejecutivo_padre').val() || null,
                eli_eje: $('#ejecutivo_activo').is(':checked') ? 1 : 0
            };
            
            if(modoEdicion) {
                formData.id_eje = $('#ejecutivo_id').val();
            }
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#modalEjecutivo').modal('hide');
                        recargarTodos();
                        
                        // Enviar mensaje WebSocket según el tipo de operación
                        if (modoEdicion) {
                            enviarMensajeWebSocket('ejecutivo_actualizado', {
                                id_eje: formData.id_eje,
                                datos: formData
                            });
                            mostrarBadgeWebSocket('success', 'Ejecutivo actualizado');
                        } else {
                            enviarMensajeWebSocket('ejecutivo_creado', {
                                id_eje: response.data.id_eje,
                                datos: formData
                            });
                            mostrarBadgeWebSocket('success', 'Ejecutivo creado');
                        }
                        
                        alert('Ejecutivo ' + (modoEdicion ? 'actualizado' : 'creado') + ' correctamente');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Error de conexión');
                }
            });
        }
        
        function toggleEstado() {
            if(!nodoSeleccionado) {
                alert('Por favor selecciona un ejecutivo');
                return;
            }
            
            var nuevoEstado = nodoSeleccionado.eli_eje == 1 ? 0 : 1;
            
            $.ajax({
                url: 'server/controlador_ejecutivos.php',
                type: 'POST',
                data: {
                    action: 'toggle_estado_ejecutivo',
                    id_eje: nodoSeleccionado.id_eje,
                    eli_eje: nuevoEstado
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        recargarTodos();
                        $('#info-panel').hide();
                        
                        // Enviar mensaje WebSocket de cambio de estado
                        enviarMensajeWebSocket('ejecutivo_estado_cambiado', {
                            id_eje: nodoSeleccionado.id_eje,
                            nuevo_estado: nuevoEstado,
                            nombre_ejecutivo: nodoSeleccionado.nom_eje
                        });
                        
                        var estadoTexto = nuevoEstado == 1 ? 'activado' : 'desactivado';
                        mostrarBadgeWebSocket('info', 'Ejecutivo ' + estadoTexto);
                        
                        nodoSeleccionado = null;
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Error de conexión');
                }
            });
        }
        
        // =====================================
        // FUNCIONES AUXILIARES
        // =====================================
        
        function limpiarEstadoDrag() {
            // Limpiar todas las clases de drop-zone
            $('.plantel-container').removeClass('drop-zone');
            
            // Resetear variables globales
            draggedNode = null;
            draggedFromPlantel = null;
            draggedExecutivo = null;
            
            // Ocultar mensaje de estado
            $('#drag-status').hide();
            
            console.log('Estado de drag completamente limpiado');
        }
        
        function mostrarMensajeDragDrop(mensaje, exito, error) {
            var $status = $('#drag-status');
            $status.removeClass('success error');
            
            if(exito) {
                $status.addClass('success');
            } else if(error) {
                $status.addClass('error');
            }
            
            $status.text(mensaje).show();
            
            if(exito || error) {
                setTimeout(function() {
                    $status.hide();
                }, 3000);
            }
        }
        
        // Variable para tracking de drag & drop entre planteles
        var draggedNode = null;
        var draggedFromPlantel = null;
        var draggedExecutivo = null;
        
        // Configurar eventos de drag & drop globales mejorados
        $(document).on('dnd_start.vakata', function(e, data) {
            console.log('=== INICIANDO DRAG ===');
            console.log('Evento completo:', e);
            console.log('Data completa:', data);
            
            // Obtener el nodo que se está arrastrando
            if(data.data && data.data.nodes && data.data.nodes.length > 0) {
                draggedNode = data.data.nodes[0];
                console.log('Nodo arrastrado:', draggedNode);
                
                // Buscar el ejecutivo en los datos
                draggedExecutivo = ejecutivos.find(ej => ej.id_eje == draggedNode);
                console.log('Ejecutivo encontrado:', draggedExecutivo);
                
                // Buscar el contenedor de plantel desde el cual se está arrastrando
                var sourceElement = $(data.element);
                var sourceTree = sourceElement.closest('.plantel-container');
                
                if(sourceTree.length > 0) {
                    draggedFromPlantel = sourceTree.data('plantel-id');
                    console.log('Arrastrando desde plantel:', draggedFromPlantel);
                    
                    // Resaltar zonas de drop (otros planteles)
                    $('.plantel-container').not('[data-plantel-id="' + draggedFromPlantel + '"]').addClass('drop-zone');
                    console.log('Zonas de drop resaltadas');
                    
                    // Mostrar mensaje de estado
                    mostrarMensajeDragDrop('Arrastrando ' + (draggedExecutivo ? draggedExecutivo.nom_eje : 'ejecutivo') + '...', false, false);
                } else {
                    console.warn('No se pudo encontrar el contenedor de plantel origen');
                }
            } else {
                console.error('No se pudo obtener el nodo arrastrado');
            }
        });
        
        $(document).on('dnd_stop.vakata', function(e, data) {
            console.log('=== FINALIZANDO DRAG ===');
            
            // Usar función de limpieza centralizada
            setTimeout(function() {
                limpiarEstadoDrag();
                console.log('Estado de drag limpiado desde dnd_stop');
            }, 100);
            
            // Resetear variables después de un pequeño delay para permitir que el drop se complete
            setTimeout(function() {
                draggedNode = null;
                draggedFromPlantel = null;
                draggedExecutivo = null;
                console.log('Variables de drag reseteadas');
            }, 100);
        });
        
        // Mejorar la detección de drop en contenedores de planteles
        // (Estos listeners adicionales ayudan a capturar el drop si fallan los eventos internos)
        $(document).on('dragover', '.plantel-container', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if(draggedNode && $(this).data('plantel-id') != draggedFromPlantel) {
                $(this).addClass('drop-zone');
            }
        });
        
        $(document).on('dragleave', '.plantel-container', function(e) {
            // Solo remover la clase si realmente salimos del contenedor
            var rect = this.getBoundingClientRect();
            var x = e.originalEvent.clientX;
            var y = e.originalEvent.clientY;
            
            if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
                $(this).removeClass('drop-zone');
            }
        });
        
        $(document).on('drop', '.plantel-container', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('=== DROP EVENT GLOBAL ===');
            
            var targetPlantel = $(this).data('plantel-id');
            console.log('Drop en plantel:', targetPlantel);
            console.log('Nodo arrastrado:', draggedNode);
            console.log('Plantel origen:', draggedFromPlantel);
            console.log('Ejecutivo arrastrado:', draggedExecutivo);
            
            // Limpiar TODAS las clases drop-zone inmediatamente
            $('.plantel-container').removeClass('drop-zone');
            
            if(draggedNode && draggedFromPlantel && targetPlantel != draggedFromPlantel) {
                var ejecutivoId = draggedNode;
                
                if(draggedExecutivo) {
                    console.log('Moviendo ejecutivo:', draggedExecutivo.nom_eje, 'ID:', ejecutivoId, 'del plantel:', draggedFromPlantel, 'al plantel:', targetPlantel);
                    
                    // Mover ejecutivo a nuevo plantel (sin padre - será raíz en el nuevo plantel)
                    moverEjecutivo(ejecutivoId, null, targetPlantel);
                } else {
                    console.error('No se encontró información del ejecutivo');
                    mostrarMensajeDragDrop('Error: No se encontró información del ejecutivo', false, true);
                }
            } else if(targetPlantel == draggedFromPlantel) {
                console.log('No se mueve porque es el mismo plantel');
            } else {
                console.log('Faltan datos para el movimiento:', {
                    draggedNode: draggedNode,
                    draggedFromPlantel: draggedFromPlantel,
                    targetPlantel: targetPlantel
                });
            }
            
            // Limpiar estado después del procesamiento
            setTimeout(function() {
                limpiarEstadoDrag();
            }, 100);
        });
        
        // =====================================
        // FUNCIONES DE WEBSOCKET
        // =====================================
        
        function verDetallesCitas(idEjecutivo, tipo) {
            // Navegar al apartado de citas con filtro por ejecutivo
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            var url = 'index.php?ejecutivo=' + idEjecutivo;
            
            if (fechaInicio) {
                url += '&fecha_inicio=' + fechaInicio;
            }
            if (fechaFin) {
                url += '&fecha_fin=' + fechaFin;
            }
            
            // Agregar parámetro para indicar el tipo de conteo
            url += '&tipo_conteo=' + tipo;
            url += '&origen=plantel';
            
            console.log('Navegando desde plantel a:', url);
            window.location.href = url;
        }
        
        function verDetallesCitasPlantel(idPlantel) {
            // Navegar al apartado de citas con filtro por plantel
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            
            var url = 'index.php?plantel=' + idPlantel;
            
            if (fechaInicio) {
                url += '&fecha_inicio=' + fechaInicio;
            }
            if (fechaFin) {
                url += '&fecha_fin=' + fechaFin;
            }
            
            url += '&tipo_conteo=plantel';
            url += '&origen=plantel';
            
            console.log('Navegando desde plantel completo a:', url);
            window.location.href = url;
        }
    </script>
</body>
</html>
