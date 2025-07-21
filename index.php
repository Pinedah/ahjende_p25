<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 22 - Sistema de Citas</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Handsontable CSS -->
    <link rel="stylesheet" href="handsontable/handsontable.full.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Handsontable JS -->
    <script src="handsontable/handsontable.full.min.js"></script>
    
    <!-- Chart.js for pie charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .horario-column {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        .filter-section {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-section label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .filter-section .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .filter-section .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .filter-section .btn {
            border-radius: 4px;
            padding: 6px 12px;
            font-weight: 500;
        }
        .filter-section .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .filter-section .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .filter-section .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .filter-section .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .search-section {
            background-color: #f1f3f4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        /* Estilos para mejorar la visualización de grupos horarios */
        .ht_master .wtHolder .wtTable tbody tr:nth-child(4n+1) td {
            border-top: 2px solid #007bff !important;
        }
        .ht_master .wtHolder .wtTable tbody tr:nth-child(4n+2) td,
        .ht_master .wtHolder .wtTable tbody tr:nth-child(4n+3) td {
            background-color: #f8f9fa;
        }
        
        /* Estilos para el modal de historial */
        #modalHistorialCita .modal-dialog {
            max-width: 90%;
        }
        
        #tablaHistorialCita {
            font-size: 0.9em;
        }
        
        #tablaHistorialCita td {
            vertical-align: middle;
            word-wrap: break-word;
            max-width: 300px;
        }
        
        #infoHistorialCita {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        
        .badge {
            font-size: 0.8em;
            padding: 0.4em 0.6em;
        }
        
        /* Estilos para WebSocket */
        .websocket-changed {
            background-color: #ffeb3b !important;
            transition: background-color 0.3s ease;
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
        
        #websocket-status .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        #websocket-status .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        #websocket-status .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
        
        /* Estilos para comentarios */
        .celda-con-comentario {
            position: relative;
        }
        
        .celda-con-comentario::after {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid #ff6b35;
            border-top: 8px solid #ff6b35;
            border-bottom: 8px solid transparent;
            z-index: 10;
        }
        
        .comentario-tooltip {
            position: absolute;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 8px;
            font-size: 0.8em;
            max-width: 250px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            display: none;
        }
        
        .comentario-autor {
            font-weight: bold;
            color: #856404;
            font-size: 0.9em;
        }
        
        .comentario-fecha {
            color: #6c757d;
            font-size: 0.8em;
        }
        
        /* Estilos para el modal de comentarios */
        .comentario-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }
        
        .comentario-item .comentario-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .comentario-item .comentario-contenido {
            margin-bottom: 5px;
        }
        
        .comentario-item .comentario-meta {
            font-size: 0.8em;
            color: #6c757d;
        }
        
        .comentario-websocket-changed {
            background-color: #ffeb3b !important;
            transition: background-color 0.3s ease;
        }
        
        /* Estilos para colores de celdas */
        .celda-coloreada {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .color-websocket-changed {
            animation: colorChanged 1s ease-in-out;
        }
        
        @keyframes colorChanged {
            0% { 
                transform: scale(1); 
                box-shadow: 0 0 0 0 rgba(255, 235, 59, 0.7);
            }
            50% { 
                transform: scale(1.05); 
                box-shadow: 0 0 0 10px rgba(255, 235, 59, 0.3);
            }
            100% { 
                transform: scale(1); 
                box-shadow: 0 0 0 0 rgba(255, 235, 59, 0);
            }
        }
        
        .color-preset {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .color-preset:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .color-preset:active {
            transform: scale(0.95);
        }
        
        /* Estilos para el modal de colores */
        #modalColorCelda .form-control[type="color"] {
            height: 40px;
            border-radius: 5px;
        }
        
        #modalColorCelda .color-preset {
            text-align: center;
            font-size: 0.9em;
            border: 2px solid transparent;
        }
        
        #modalColorCelda .color-preset.selected {
            border-color: #007bff;
            transform: scale(1.1);
        }
        
        /* Estilos para estatus de citas - Práctica 22 */
        .celda-estatus {
            font-weight: bold !important;
            text-align: center !important;
        }
        
        /* Animación para cambios de estatus */
        .estatus-cambiado {
            animation: estatusChanged 1s ease-in-out;
        }
        
        @keyframes estatusChanged {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Estilos para badges de conteo */
        #estatus-badges .badge {
            border: 1px solid rgba(255,255,255,0.3);
            min-height: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
        }
        
        /* Estilos para el embudo de citas - Práctica 24 */
        .embudo-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 10px 15px;
        }
        
        .embudo-datos {
            flex: 2;
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 10px;
        }
        
        .embudo-etapa {
            text-align: center;
            padding: 8px;
            border-radius: 4px;
            min-width: 100px;
            transition: all 0.3s ease;
        }
        
        .embudo-etapa:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .embudo-etapa.total-citas {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        
        .embudo-etapa.citas-efectivas {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }
        
        .embudo-etapa.registros {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #333;
        }
        
        .embudo-numero {
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 1px;
            line-height: 1;
        }
        
        .embudo-porcentaje {
            font-size: 0.8em;
            margin-bottom: 1px;
            line-height: 1;
        }
        
        .embudo-label {
            font-size: 0.65em;
            opacity: 0.9;
            line-height: 1;
        }
        
        .embudo-flecha {
            font-size: 1em;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .embudo-chart-container {
            flex: 0 0 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .embudo-chart {
            width: 80px;
            height: 80px;
        }
        
        @media (max-width: 768px) {
            .embudo-container {
                flex-direction: column;
                gap: 10px;
                padding: 8px;
            }
            
            .embudo-datos {
                order: 2;
                flex-direction: column;
                gap: 8px;
            }
            
            .embudo-chart-container {
                order: 1;
                flex: 0 0 60px;
            }
            
            .embudo-chart {
                width: 60px;
                height: 60px;
            }
            
            .embudo-flecha {
                transform: rotate(90deg);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <h1 class="text-center mb-4">Práctica 22 - Sistema de Citas</h1>
        
        <div class="card">
            <div class="card-header">
                <h4>Sistema de Citas</h4>
            </div>
            <div class="card-body">
                
                <!-- Mensaje de navegación desde árbol de ejecutivos -->
                <div id="mensajeNavegacion"></div>
                
                <!-- Botón de regreso (solo visible cuando se navega desde un árbol) -->
                <div id="botonRegreso" style="display: none;" class="mb-3">
                    <button class="btn btn-outline-primary" onclick="regresarAOrigen()">
                        <i class="fas fa-arrow-left"></i> <span id="textoRegreso">Regresar</span>
                    </button>
                </div>
                
                <!-- Buscador de citas -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="buscador-citas"><strong>Buscar Citas:</strong></label>
                            <input type="text" id="buscador-citas" class="form-control" placeholder="Buscar por nombre, teléfono o ejecutivo...">
                        </div>
                        <div class="col-md-2">
                            <label for="mi-id-ejecutivo"><strong>Mi ID Ejecutivo:</strong></label>
                            <input type="number" id="mi-id-ejecutivo" class="form-control" value="1" min="1" max="999" style="width: 80px;">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-primary" onclick="buscarCitas()">Buscar</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-secondary" onclick="limpiarBusqueda()">Actualizar</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-success" onclick="mostrarModalNuevaColumna()">+ Columna</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-info" onclick="recargarEstructura()">🔄 Recargar</button>
                        </div>
                        <br>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-primary" onclick="window.location.href='arbol_ejecutivos.php'">
                                <i class="fas fa-sitemap"></i> Ejecutivos
                            </button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-success" onclick="window.location.href='arbol_planteles.php'">
                                <i class="fas fa-building"></i> Planteles
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Filtro de fecha -->
                <div class="filter-section">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="fecha-inicio-filtro"><strong>Fecha Inicio:</strong></label>
                            <input type="date" id="fecha-inicio-filtro" class="form-control" value="">
                        </div>
                        <div class="col-md-2">
                            <label for="fecha-fin-filtro"><strong>Fecha Fin:</strong></label>
                            <input type="date" id="fecha-fin-filtro" class="form-control" value="">
                        </div>
                        <div class="col-md-2">
                            <label for="ejecutivo-filtro"><strong>Ejecutivo:</strong></label>
                            <select id="ejecutivo-filtro" class="form-control">
                                <option value="">Todos los ejecutivos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="plantel-filtro"><strong>Plantel:</strong></label>
                            <select id="plantel-filtro" class="form-control">
                                <option value="">Todos los planteles</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="planteles-asociados-filtro"><strong>Incluir Planteles Asociados:</strong></label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="planteles-asociados-filtro" value="1">
                                <label class="form-check-label" for="planteles-asociados-filtro">
                                    🕋 Planteles Asociados
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-info mr-2" onclick="cargarCitas()">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <button class="btn btn-secondary" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                    <!-- Información del filtro activo -->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div id="info-filtro-activo" class="alert alert-info" style="display: none; margin-bottom: 0; padding: 8px 12px;">
                                <small><strong>Filtros activos:</strong> <span id="detalle-filtros"></span></small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtros rápidos -->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <small class="text-muted">
                                <strong>Filtros rápidos:</strong>
                                <button class="btn btn-sm btn-outline-secondary ml-1" onclick="aplicarFiltroRapido('hoy')">Hoy</button>
                                <button class="btn btn-sm btn-outline-secondary ml-1" onclick="aplicarFiltroRapido('semana')">Esta Semana</button>
                                <button class="btn btn-sm btn-outline-secondary ml-1" onclick="aplicarFiltroRapido('mes')">Este Mes</button>
                                <button class="btn btn-sm btn-outline-secondary ml-1" onclick="aplicarFiltroRapido('ultimos7')">Últimos 7 días</button>
                                <button class="btn btn-sm btn-outline-secondary ml-1" onclick="aplicarFiltroRapido('ultimos30')">Últimos 30 días</button>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Conteos de Estatus de Citas - Práctica 22 -->
                <div class="row mb-3" id="conteos-estatus" style="display: none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Conteos por Estatus de Cita:</h6>
                            </div>
                            <div class="card-body" style="padding: 10px;">
                                <div class="row" id="estatus-badges">
                                    <!-- Los badges se generarán dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conteos de Efectividad de Citas - Práctica 23 -->
                <div class="row mb-3" id="conteos-efectividad" style="display: none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Conteos por Efectividad de Cita</h6>
                            </div>
                            <div class="card-body" style="padding: 10px;">
                                <div class="row" id="efectividad-badges">
                                    <!-- Los badges se generarán dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Embudo de Citas - Práctica 24 -->
                <div class="row mb-3" id="embudo-citas" style="display: none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-funnel-dollar"></i> Embudo de Citas</h6>
                            </div>
                            <div class="card-body" style="padding: 10px;">
                                <div class="embudo-container">
                                    <!-- Datos del embudo -->
                                    <div class="embudo-datos">
                                        <!-- Total de Citas -->
                                        <div class="embudo-etapa total-citas">
                                            <div class="embudo-numero" id="total-numero">0</div>
                                            <div class="embudo-porcentaje" id="total-porcentaje">100%</div>
                                            <div class="embudo-label">TOTAL CITAS</div>
                                        </div>
                                        
                                        <!-- Flecha -->
                                        <div class="embudo-flecha">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        
                                        <!-- Citas Efectivas -->
                                        <div class="embudo-etapa citas-efectivas">
                                            <div class="embudo-numero" id="efectivas-numero">0</div>
                                            <div class="embudo-porcentaje" id="efectivas-porcentaje">0%</div>
                                            <div class="embudo-label">CITAS EFECTIVAS</div>
                                        </div>
                                        
                                        <!-- Flecha -->
                                        <div class="embudo-flecha">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        
                                        <!-- Registros -->
                                        <div class="embudo-etapa registros">
                                            <div class="embudo-numero" id="registros-numero">0</div>
                                            <div class="embudo-porcentaje" id="registros-porcentaje">0%</div>
                                            <div class="embudo-label">REGISTROS</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Gráfico unificado -->
                                    <div class="embudo-chart-container">
                                        <canvas class="embudo-chart" id="chart-embudo"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenedor Handsontable -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div id="websocket-status" class="alert alert-secondary" style="padding: 8px 12px; margin-bottom: 0;">
                            <small><strong>Estado WebSocket:</strong> <span id="websocket-status-text">Conectando...</span></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="websocket-log" class="alert alert-info" style="padding: 8px 12px; margin-bottom: 0; height: 45px; overflow-y: auto; font-size: 0.85em;">
                            <small id="websocket-log-text">Logs de WebSocket aparecerán aquí...</small>
                        </div>
                    </div>
                </div>
                
                <div id="tabla-citas" style="width: 100%; height: 600px;"></div>
                
            </div>
        </div>
    </div>

    <!-- Modal para agregar nueva columna -->
    <div class="modal fade" id="modalNuevaColumna" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nueva Columna</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formNuevaColumna">
                        <div class="form-group">
                            <label for="nombreColumna">Nombre de la Columna:</label>
                            <input type="text" class="form-control" id="nombreColumna" placeholder="ej: observaciones" required>
                            <small class="form-text text-muted">Solo letras, números y guiones bajos. No espacios.</small>
                        </div>
                        <div class="form-group">
                            <label for="tipoColumna">Tipo de Columna:</label>
                            <select class="form-control" id="tipoColumna">
                                <option value="VARCHAR(100)">Texto (VARCHAR)</option>
                                <option value="TEXT">Texto Largo (TEXT)</option>
                                <option value="INT">Número Entero (INT)</option>
                                <option value="DECIMAL(10,2)">Número Decimal</option>
                                <option value="DATE">Fecha (DATE)</option>
                                <option value="TIME">Hora (TIME)</option>
                                <option value="DATETIME">Fecha y Hora</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="crearNuevaColumna()">Crear Columna</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar historial de cita -->
    <div class="modal fade" id="modalHistorialCita" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Historial de Cita</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="infoHistorialCita" class="mb-3">
                        <strong>Cita:</strong> <span id="nombreCitaHistorial"></span><br>
                        <strong>ID:</strong> <span id="idCitaHistorial"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Responsable</th>
                                    <th>Movimiento</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaHistorialCita">
                                <!-- Contenido dinámico -->
                            </tbody>
                        </table>
                    </div>
                    <div id="sinHistorial" style="display: none;" class="text-center text-muted">
                        <p>No hay historial disponible para esta cita.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar comentario -->
    <div class="modal fade" id="modalComentario" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Comentario</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="infoComentario" class="mb-3">
                        <strong>Celda:</strong> <span id="celdaComentario"></span>
                    </div>
                    <div class="form-group">
                        <label for="contenidoComentario">Comentario:</label>
                        <textarea class="form-control" id="contenidoComentario" rows="3" placeholder="Escribe tu comentario aquí..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarComentario()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver comentarios -->
    <div class="modal fade" id="modalVerComentarios" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comentarios de la Cita</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="infoComentariosCita" class="mb-3">
                        <!-- Información de la cita -->
                    </div>
                    <div id="listaComentarios">
                        <!-- Comentarios aparecerán aquí -->
                    </div>
                    <div id="sinComentarios" style="display: none;" class="text-center text-muted">
                        <p>No hay comentarios para esta cita.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar color de celda -->
    <div class="modal fade" id="modalColorCelda" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Color de Celda</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="celdaColor">Celda seleccionada:</label>
                        <div id="celdaColor" class="alert alert-info"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="colorFondo">Color de fondo:</label>
                        <input type="color" class="form-control" id="colorFondo" value="#ffffff">
                    </div>
                    
                    <div class="form-group">
                        <label for="colorTexto">Color de texto:</label>
                        <input type="color" class="form-control" id="colorTexto" value="#000000">
                    </div>
                    
                    <div class="form-group">
                        <label>Colores predefinidos:</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="color-preset" style="background-color: #ffeb3b; color: #000000; padding: 10px; margin: 5px; cursor: pointer; border-radius: 3px;" data-fondo="#ffeb3b" data-texto="#000000">
                                    <i class="fas fa-star"></i> Importante
                                </div>
                                <div class="color-preset" style="background-color: #4caf50; color: #ffffff; padding: 10px; margin: 5px; cursor: pointer; border-radius: 3px;" data-fondo="#4caf50" data-texto="#ffffff">
                                    <i class="fas fa-check"></i> Completado
                                </div>
                                <div class="color-preset" style="background-color: #f44336; color: #ffffff; padding: 10px; margin: 5px; cursor: pointer; border-radius: 3px;" data-fondo="#f44336" data-texto="#ffffff">
                                    <i class="fas fa-exclamation"></i> Urgente
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="color-preset" style="background-color: #2196f3; color: #ffffff; padding: 10px; margin: 5px; cursor: pointer; border-radius: 3px;" data-fondo="#2196f3" data-texto="#ffffff">
                                    <i class="fas fa-info"></i> Información
                                </div>
                                <div class="color-preset" style="background-color: #ff9800; color: #ffffff; padding: 10px; margin: 5px; cursor: pointer; border-radius: 3px;" data-fondo="#ff9800" data-texto="#ffffff">
                                    <i class="fas fa-clock"></i> Pendiente
                                </div>
                                <div class="color-preset" style="background-color: #9c27b0; color: #ffffff; padding: 10px; margin: 5px; cursor: pointer; border-radius: 3px;" data-fondo="#9c27b0" data-texto="#ffffff">
                                    <i class="fas fa-bookmark"></i> Especial
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarColor()">Guardar Color</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // =====================================
        // CONFIGURACIÓN DE COLUMNAS
        // =====================================
        
        // Configuración dinámica de columnas (se carga desde el servidor)
        var columnasConfig = [];
        
        // Variables globales
        var hot = null;
        var ejecutivos = [];
        var ejecutivosDropdown = [];
        var modoFiltroFecha = true; // true = filtro por fecha, false = búsqueda
        var citasPorRango = 4; // Número de citas por rango horario (2 en blanco + 2 para citas)
        var filaEditandose = null; // Fila que se está editando actualmente
        var datosPendientes = {}; // Datos pendientes de guardar para la fila actual
        
        // Variables WebSocket
        var socket = null;
        var websocketUrl = 'wss://socket.ahjende.com/wss/?encoding=text';
        var miIdEjecutivo = 1; // ID del ejecutivo actual
        var reconectarIntento = 0;
        var maxReconectarIntentos = 5;
        
        // Variables para colores de celdas
        var coloresCeldas = {}; // Almacenar colores por fila,columna {"fila,columna": {fondo: "#color", texto: "#color"}}
        var colorSeleccionado = null; // Color seleccionado para cambiar
        
        // Función para cargar colores desde localStorage
        function cargarColoresDesdeCache() {
            try {
                var coloresCache = localStorage.getItem('coloresCeldas_' + window.location.pathname);
                if (coloresCache) {
                    coloresCeldas = JSON.parse(coloresCache);
                    console.log('📱 Colores cargados desde cache:', Object.keys(coloresCeldas).length);
                }
            } catch (e) {
                console.error('❌ Error al cargar colores desde cache:', e);
            }
        }
        
        // Función para guardar colores en localStorage
        function guardarColoresEnCache() {
            try {
                localStorage.setItem('coloresCeldas_' + window.location.pathname, JSON.stringify(coloresCeldas));
            } catch (e) {
                console.error('❌ Error al guardar colores en cache:', e);
            }
        }
        
        // Función para limpiar cache de colores
        function limpiarCacheColores() {
            try {
                localStorage.removeItem('coloresCeldas_' + window.location.pathname);
                coloresCeldas = {};
                console.log('🗑️ Cache de colores limpiado');
            } catch (e) {
                console.error('❌ Error al limpiar cache de colores:', e);
            }
        }
        
        // =====================================
        // INICIALIZACIÓN
        // =====================================
        
        $(document).ready(function() {
            // NO cargar colores desde cache al iniciar, ahora se cargan desde BD
            // cargarColoresDesdeCache();
            
            // Configurar fechas por defecto (última semana)
            var fechaHoy = new Date();
            var fechaFin = fechaHoy.toISOString().split('T')[0];
            var fechaInicioDate = new Date(fechaHoy);
            fechaInicioDate.setDate(fechaInicioDate.getDate() - 7); // Una semana atrás
            var fechaInicio = fechaInicioDate.toISOString().split('T')[0];
            
            $('#fecha-inicio-filtro').val(fechaInicio);
            $('#fecha-fin-filtro').val(fechaFin);
            
            // Agregar validación de fechas
            $('#fecha-inicio-filtro, #fecha-fin-filtro').on('change', function() {
                validarRangoFechas();
            });
            
            // Configurar evento para cambio de ID ejecutivo
            $('#mi-id-ejecutivo').on('change', function() {
                miIdEjecutivo = parseInt($(this).val()) || 1;
                log('ID ejecutivo cambiado a: ' + miIdEjecutivo);
            });
            
            // Inicializar WebSocket
            inicializarWebSocket();
            
            // Cargar estructura de columnas primero
            cargarEstructuraTabla().then(function() {
                return cargarEjecutivos();
            }).then(function() {
                console.log('Inicializando tabla después de cargar estructura y ejecutivos...');
                console.log('ejecutivosDropdown:', ejecutivosDropdown);
                console.log('columnasConfig después de cargar ejecutivos:', columnasConfig);
                
                inicializarTabla();
                cargarCitas();
                // Aplicar filtros desde URL después de cargar todo
                aplicarFiltrosDesdeURL();
                
                // NO aplicar colores del cache, ahora se cargan desde BD automáticamente
            }).catch(function(error) {
                console.error('Error en inicialización:', error);
                alert('Error al inicializar la aplicación: ' + error);
            });
        });
        
        // Función para validar rango de fechas
        function validarRangoFechas() {
            var fechaInicio = $('#fecha-inicio-filtro').val();
            var fechaFin = $('#fecha-fin-filtro').val();
            
            if (fechaInicio && fechaFin) {
                var inicio = new Date(fechaInicio);
                var fin = new Date(fechaFin);
                
                if (inicio > fin) {
                    alert('La fecha de inicio no puede ser mayor que la fecha de fin');
                    $('#fecha-inicio-filtro').val('');
                    return false;
                }
            }
            return true;
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
                
                // Procesar actualizaciones de citas
                if (mensaje.tipo === 'cita_actualizada') {
                    procesarActualizacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'cita_creada') {
                    procesarCreacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'cita_eliminada') {
                    procesarEliminacionWebSocket(mensaje);
                } else if (mensaje.tipo === 'comentario_agregado') {
                    procesarComentarioWebSocket(mensaje);
                } else if (mensaje.tipo === 'color_cambiado' || mensaje.tipo === 'color_eliminado') {
                    procesarColorWebSocket(mensaje);
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
            if (!hot || !mensaje.id_cit) {
                return;
            }
            
            log('🔄 Procesando actualización de cita ID: ' + mensaje.id_cit);
            
            // Buscar la fila correspondiente
            var filas = hot.getData();
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            for (var i = 0; i < filas.length; i++) {
                if (filas[i][idCitIndex] == mensaje.id_cit) {
                    var columnaIndex = obtenerIndiceColumna(mensaje.campo);
                    if (columnaIndex !== -1) {
                        log('🔄 Actualizando fila ' + i + ', columna ' + columnaIndex + ' con "' + mensaje.valor + '"');
                        
                        // Actualizar con source 'websocket' para evitar bucles
                        hot.setDataAtCell(i, columnaIndex, mensaje.valor, 'websocket');
                        
                        // Aplicar color automáticamente si es estatus (Práctica 22)
                        if (mensaje.campo === 'est_cit' && mensaje.valor && mensaje.valor !== '') {
                            setTimeout(function() {
                                aplicarColorEstatus(i, columnaIndex, mensaje.valor);
                                // Actualizar conteos también via WebSocket
                                mostrarConteosEstatus();
                            }, 100);
                        }
                        
                        // Aplicar color automáticamente si es efectividad (Práctica 23)
                        if (mensaje.campo === 'efe_cit' && mensaje.valor && mensaje.valor !== '') {
                            setTimeout(function() {
                                aplicarColorEfectividad(i, columnaIndex, mensaje.valor);
                                // Actualizar conteos también via WebSocket
                                mostrarConteosEfectividad();
                            }, 100);
                        }
                        
                        // Aplicar feedback visual
                        aplicarFeedbackVisual(i, columnaIndex, mensaje.campo);
                        
                        // Mostrar badge de actualización
                        mostrarBadgeWebSocket('info', 'Campo ' + mensaje.campo + ' actualizado');
                    }
                    break;
                }
            }
        }
        
        function procesarCreacionWebSocket(mensaje) {
            if (!hot || !mensaje.id_cit) {
                return;
            }
            
            log('➕ Procesando creación de cita ID: ' + mensaje.id_cit);
            
            // Recargar la tabla para mostrar la nueva cita
            if (modoFiltroFecha) {
                cargarCitas();
            } else {
                buscarCitas();
            }
            
            // Mostrar badge de nueva cita
            mostrarBadgeWebSocket('success', 'Nueva cita creada por otro usuario');
        }
        
        function procesarEliminacionWebSocket(mensaje) {
            if (!hot || !mensaje.id_cit) {
                return;
            }
            
            log('🗑️ Procesando eliminación de cita ID: ' + mensaje.id_cit);
            
            // Recargar la tabla para remover la cita eliminada
            if (modoFiltroFecha) {
                cargarCitas();
            } else {
                buscarCitas();
            }
            
            // Mostrar badge de eliminación
            mostrarBadgeWebSocket('warning', 'Cita eliminada por otro usuario');
        }
        
        function aplicarFeedbackVisual(row, column, campo) {
            // Resaltar celda con animación
            var celda = hot.getCell(row, column);
            if (celda) {
                celda.classList.add('websocket-changed');
                setTimeout(function() {
                    celda.classList.remove('websocket-changed');
                }, 2000);
            }
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
            
            statusElement.removeClass('alert-secondary alert-success alert-danger alert-warning');
            statusElement.addClass('alert-' + tipo);
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
        // FUNCIONES DE CONFIGURACIÓN
        // =====================================
        
        function cargarEstructuraTabla() {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: { action: 'obtener_estructura_tabla' },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            columnasConfig = response.data;
                            console.log('Estructura de tabla cargada:', columnasConfig);
                            resolve();
                        } else {
                            reject('Error al cargar estructura de tabla: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        reject('Error de conexión al cargar estructura: ' + error);
                    }
                });
            });
        }
        
        function generarHeaders() {
            return columnasConfig.map(function(col) { return col.header; });
        }
        
        function generarColumnas() {
            console.log('Generando columnas...');
            console.log('ejecutivosDropdown en generarColumnas:', ejecutivosDropdown);
            
            return columnasConfig.map(function(col) {
                var columna = {
                    type: col.type,
                    width: col.width || 120
                };
                
                if (col.readOnly) columna.readOnly = col.readOnly;
                if (col.className) columna.className = col.className;
                if (col.dateFormat) columna.dateFormat = col.dateFormat;
                if (col.timeFormat) columna.timeFormat = col.timeFormat;
                
                // Configuración específica para dropdowns
                if (col.type === 'dropdown') {
                    if (col.key === 'id_eje2') {
                        // Dropdown de ejecutivos - siempre usar la lista más actualizada
                        columna.source = ejecutivosDropdown.length > 0 ? ejecutivosDropdown : ['No hay ejecutivos'];
                        columna.strict = false;
                        console.log('Configurando dropdown ejecutivos:', columna.source);
                    } else if (col.key === 'est_cit') {
                        // Dropdown de estatus (usar la fuente del servidor)
                        columna.source = col.source || [
                            'CITA AGENDADA',
                            'INVASIÓN DE CICLO', 
                            'CITA REAGENDADA',
                            'CITA NO ATENDIDA',
                            'PAGO ESPERADO',
                            'PERDIDO POR PRECIO',
                            'PERDIDO POR HORARIO',
                            'REGISTRO',
                            'NO LE INTERESA',
                            'ASESORÍA REALIZADA',
                            'CITA CONFIRMADA'
                        ];
                        columna.strict = false;
                        console.log('Configurando dropdown estatus:', columna.source);
                    } else {
                        // Otros dropdowns usando la configuración del servidor
                        columna.source = col.source || [];
                        columna.strict = false;
                    }
                }
                
                return columna;
            });
        }
        
        function obtenerCampo(columnIndex) {
            return columnasConfig[columnIndex] ? columnasConfig[columnIndex].key : null;
        }
        
        function obtenerIndiceColumna(campo) {
            for (var i = 0; i < columnasConfig.length; i++) {
                if (columnasConfig[i].key === campo) return i;
            }
            return -1;
        }
        
        // =====================================
        // FUNCIONES DE EJECUTIVOS
        // =====================================
        
        function cargarEjecutivos() {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: { action: 'obtener_ejecutivos' },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            ejecutivos = response.data;
                            ejecutivosDropdown = response.data.map(function(eje) {
                                return eje.nom_eje;
                            });
                            
                            // Actualizar configuración de columna dropdown
                            var colEjecutivo = columnasConfig.find(function(col) { return col.key === 'id_eje2'; });
                            if (colEjecutivo) {
                                colEjecutivo.source = ejecutivosDropdown;
                            }
                            
                            // Poblar el dropdown del filtro de ejecutivos
                            var selectEjecutivo = $('#ejecutivo-filtro');
                            selectEjecutivo.empty();
                            selectEjecutivo.append('<option value="">Todos los ejecutivos</option>');
                            
                            ejecutivos.forEach(function(ejecutivo) {
                                selectEjecutivo.append('<option value="' + ejecutivo.id_eje + '">' + 
                                    ejecutivo.nom_eje + '</option>');
                            });
                            
                            console.log('Ejecutivos cargados:', ejecutivos);
                            
                            // Cargar planteles para el filtro
                            cargarPlanteles().then(function() {
                                resolve();
                            }).catch(function(error) {
                                reject(error);
                            });
                        } else {
                            reject('Error al cargar ejecutivos');
                        }
                    },
                    error: function() {
                        reject('Error de conexión');
                    }
                });
            });
        }
        
        function cargarPlanteles() {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: 'server/controlador_ejecutivos.php',
                    type: 'POST',
                    data: { action: 'obtener_planteles' },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            var planteles = response.data;
                            
                            // Poblar el dropdown del filtro de planteles
                            var selectPlantel = $('#plantel-filtro');
                            selectPlantel.empty();
                            selectPlantel.append('<option value="">Todos los planteles</option>');
                            
                            planteles.forEach(function(plantel) {
                                selectPlantel.append('<option value="' + plantel.id_pla + '">' + 
                                    plantel.nom_pla + '</option>');
                            });
                            
                            console.log('Planteles cargados:', planteles);
                            resolve();
                        } else {
                            reject('Error al cargar planteles');
                        }
                    },
                    error: function() {
                        reject('Error de conexión al cargar planteles');
                    }
                });
            });
        }
        
        function obtenerIdEjecutivo(nombreEjecutivo) {
            var ejecutivo = ejecutivos.find(function(eje) {
                return eje.nom_eje === nombreEjecutivo;
            });
            return ejecutivo ? ejecutivo.id_eje : null;
        }
        
        function obtenerNombreEjecutivo(idEjecutivo) {
            var ejecutivo = ejecutivos.find(function(eje) {
                return eje.id_eje == idEjecutivo;
            });
            return ejecutivo ? ejecutivo.nom_eje : '';
        }
        
        // =====================================
        // FUNCIONES PARA ESTATUS DE CITA Y EFECTIVIDAD DE CITA (PRÁCTICA 22 y 23)
        // =====================================
        
        function obtenerColorEstatus(estatus) {
            var catalogoEstatus = {
                'CITA AGENDADA': '#FF9800',
                'INVASIÓN DE CICLO': '#FFFF00',
                'CITA REAGENDADA': '#9C27B0',
                'CITA NO ATENDIDA': '#FF6666',
                'PAGO ESPERADO': '#FF00FF',
                'PERDIDO POR PRECIO': '#AABBCC',
                'PERDIDO POR HORARIO': '#336699',
                'REGISTRO': '#00FFFF',
                'NO LE INTERESA': '#CC0000',
                'ASESORÍA REALIZADA': '#00FF00',
                'CITA CONFIRMADA': '#FFFF00'
            };
            
            return catalogoEstatus[estatus] || null;
        }
        
        function obtenerColorEfectividad(efectividad) {
            switch(efectividad) {
                case 'CITA EFECTIVA':
                    return {
                        fondo: '#FFC0CB',  // rosa
                        texto: '#FF0000'   // rojo
                    };
                case 'CITA NO EFECTIVA':
                    return {
                        fondo: '#FF6666',  // rojo claro
                        texto: '#FFFFFF'   // blanco
                    };
                default:
                    return null;
            }
        }

        // Función para calcular conteos de estatus
        function calcularConteosEstatus() {
            var conteos = {};
            var totalCitas = 0;
            
            // Inicializar conteos
            var catalogoEstatus = {
                'CITA AGENDADA': '#FF9800',
                'INVASIÓN DE CICLO': '#FFFF00',
                'CITA REAGENDADA': '#9C27B0',
                'CITA NO ATENDIDA': '#FF6666',
                'PAGO ESPERADO': '#FF00FF',
                'PERDIDO POR PRECIO': '#AABBCC',
                'PERDIDO POR HORARIO': '#336699',
                'REGISTRO': '#00FFFF',
                'NO LE INTERESA': '#CC0000',
                'ASESORÍA REALIZADA': '#00FF00',
                'CITA CONFIRMADA': '#FFFF00'
            };
            
            Object.keys(catalogoEstatus).forEach(function(estatus) {
                conteos[estatus] = { cantidad: 0, color: catalogoEstatus[estatus] };
            });
            
            if (!hot) return conteos;
            
            var indexEstatus = obtenerIndiceColumna('est_cit');
            var indexIdCit = obtenerIndiceColumna('id_cit');
            
            if (indexEstatus === -1 || indexIdCit === -1) return conteos;
            
            // Recorrer todas las filas de la tabla
            var datos = hot.getData();
            datos.forEach(function(fila) {
                var id_cit = fila[indexIdCit];
                var estatus = fila[indexEstatus];
                
                // Solo contar filas que tienen ID de cita (citas reales)
                if (id_cit && id_cit !== '' && estatus && conteos[estatus]) {
                    conteos[estatus].cantidad++;
                    totalCitas++;
                }
            });
            
            return { conteos: conteos, total: totalCitas };
        }
        
        // Función para mostrar conteos de estatus
        function mostrarConteosEstatus() {
            var resultado = calcularConteosEstatus();
            var conteos = resultado.conteos;
            var total = resultado.total;
            
            var contenedor = $('#estatus-badges');
            contenedor.empty();
            
            if (total === 0) {
                $('#conteos-estatus').hide();
                return;
            }
            
            $('#conteos-estatus').show();
            
            Object.keys(conteos).forEach(function(estatus) {
                var datos = conteos[estatus];
                if (datos.cantidad > 0) {
                    var porcentaje = ((datos.cantidad / total) * 100).toFixed(1);
                    var badge = `
                        <div class="col-md-2 mb-2">
                            <div class="badge p-2 d-block text-center" style="background-color: ${datos.color}; color: ${getContrastColor(datos.color)}; font-size: 0.85em;">
                                <div style="font-weight: bold;">${estatus}</div>
                                <div>${datos.cantidad} (${porcentaje}%)</div>
                            </div>
                        </div>
                    `;
                    contenedor.append(badge);
                }
            });
            
            // Actualizar embudo de citas cuando cambian los estatus
            mostrarEmbudoCitas();
        }
        
        // Función para obtener color de contraste (texto blanco o negro)
        function getContrastColor(hexColor) {
            // Convertir hex a RGB
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hexColor);
            if (!result) return '#000000';
            
            var r = parseInt(result[1], 16);
            var g = parseInt(result[2], 16);
            var b = parseInt(result[3], 16);
            
            // Calcular luminancia
            var luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
            
            return luminance > 0.5 ? '#000000' : '#ffffff';
        }
        
        // Función para aplicar color automáticamente al cambiar estatus
        function aplicarColorEstatus(row, column, estatus) {
            if (!hot || !estatus) return;
            
            // Simplemente hacer render ya que el renderer personalizado se encargará de los colores
            hot.render();
            
            // Actualizar conteos
            setTimeout(mostrarConteosEstatus, 100);
        }

        // Función para calcular conteos de efectividad
        function calcularConteosEfectividad() {
            var conteos = {};
            var totalCitas = 0;
            
            // Inicializar conteos para efectividad
            var catalogoEfectividad = {
                'CITA EFECTIVA': { fondo: '#FFC0CB', texto: '#FF0000' },
                'CITA NO EFECTIVA': { fondo: '#FF6666', texto: '#FFFFFF' }
            };
            
            Object.keys(catalogoEfectividad).forEach(function(efectividad) {
                conteos[efectividad] = { cantidad: 0, color: catalogoEfectividad[efectividad].fondo };
            });
            
            if (!hot) return conteos;
            
            var indexEfectividad = obtenerIndiceColumna('efe_cit');
            var indexIdCit = obtenerIndiceColumna('id_cit');
            
            if (indexEfectividad === -1 || indexIdCit === -1) return conteos;
            
            // Recorrer todas las filas de la tabla
            var datos = hot.getData();
            datos.forEach(function(fila) {
                var id_cit = fila[indexIdCit];
                var efectividad = fila[indexEfectividad];
                
                // Solo contar filas que tienen ID de cita (citas reales)
                if (id_cit && id_cit !== '' && efectividad && conteos[efectividad]) {
                    conteos[efectividad].cantidad++;
                    totalCitas++;
                }
            });
            
            return { conteos: conteos, total: totalCitas };
        }
        
        // Función para mostrar conteos de efectividad
        function mostrarConteosEfectividad() {
            var resultado = calcularConteosEfectividad();
            var conteos = resultado.conteos;
            var total = resultado.total;
            
            var contenedor = $('#efectividad-badges');
            contenedor.empty();
            
            if (total === 0) {
                $('#conteos-efectividad').hide();
                // Ocultar embudo también si no hay datos
                $('#embudo-citas').hide();
                return;
            }
            
            $('#conteos-efectividad').show();
            
            Object.keys(conteos).forEach(function(efectividad) {
                var datos = conteos[efectividad];
                if (datos.cantidad > 0) {
                    var porcentaje = ((datos.cantidad / total) * 100).toFixed(1);
                    var badge = `
                        <div class="col-md-3 mb-2">
                            <div class="badge p-2 d-block text-center" style="background-color: ${datos.color}; color: ${getContrastColor(datos.color)}; font-size: 0.85em;">
                                <div style="font-weight: bold;">${efectividad}</div>
                                <div>${datos.cantidad} (${porcentaje}%)</div>
                            </div>
                        </div>
                    `;
                    contenedor.append(badge);
                }
            });
            
            // Mostrar embudo de citas (Práctica 24)
            mostrarEmbudoCitas();
        }
        
        // Función para aplicar color automáticamente al cambiar efectividad
        function aplicarColorEfectividad(row, column, efectividad) {
            if (!hot || !efectividad) return;
            
            // Simplemente hacer render ya que el renderer personalizado se encargará de los colores
            hot.render();
            
            // Actualizar conteos
            setTimeout(mostrarConteosEfectividad, 100);
        }

        // =====================================
        // FUNCIONES DEL EMBUDO DE CITAS - PRÁCTICA 24
        // =====================================
        
        var chartInstances = {}; // Para almacenar las instancias de los gráficos
        var embudoTimeout = null; // Para evitar múltiples llamadas consecutivas
        
        // Función para calcular datos del embudo (consulta global a la base de datos)
        function calcularDatosEmbudo() {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: {
                        action: 'obtener_estadisticas_embudo'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            resolve({
                                totalCitas: parseInt(response.data.total_citas) || 0,
                                citasEfectivas: parseInt(response.data.citas_efectivas) || 0,
                                registros: parseInt(response.data.registros) || 0
                            });
                        } else {
                            console.error('Error al obtener estadísticas del embudo:', response.message);
                            resolve({ totalCitas: 0, citasEfectivas: 0, registros: 0 });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX al obtener estadísticas del embudo:', error);
                        resolve({ totalCitas: 0, citasEfectivas: 0, registros: 0 });
                    }
                });
            });
        }
        
        // Función para crear gráfico unificado del embudo
        function crearGraficoEmbudoUnificado(totalCitas, citasEfectivas, registros) {
            var ctx = document.getElementById('chart-embudo');
            if (!ctx) return;
            
            // Destruir gráfico existente si existe
            if (chartInstances['chart-embudo']) {
                chartInstances['chart-embudo'].destroy();
            }
            
            // Forzar el tamaño del canvas para evitar crecimiento automático
            ctx.style.width = '80px';
            ctx.style.height = '80px';
            ctx.width = 80;
            ctx.height = 80;
            
            // Calcular datos para el gráfico
            var noEfectivas = totalCitas - citasEfectivas;
            var efectivasNoRegistro = citasEfectivas - registros;
            
            chartInstances['chart-embudo'] = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Registros', 'Efectivas sin Registro', 'No Efectivas'],
                    datasets: [{
                        data: [registros, efectivasNoRegistro, noEfectivas],
                        backgroundColor: ['#ffc107', '#28a745', '#e9ecef'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 8
                                },
                                padding: 4,
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        },
                        tooltip: {
                            enabled: true,
                            titleFont: {
                                size: 10
                            },
                            bodyFont: {
                                size: 9
                            },
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '50%'
                }
            });
        }
        
        // Función para mostrar el embudo de citas
        function mostrarEmbudoCitas() {
            // Evitar múltiples llamadas consecutivas con debounce
            if (embudoTimeout) {
                clearTimeout(embudoTimeout);
            }
            
            embudoTimeout = setTimeout(function() {
                calcularDatosEmbudo().then(function(datos) {
                    var totalCitas = datos.totalCitas;
                    var citasEfectivas = datos.citasEfectivas;
                    var registros = datos.registros;
                    
                    if (totalCitas === 0) {
                        $('#embudo-citas').hide();
                        return;
                    }
                    
                    $('#embudo-citas').show();
                    
                    // Calcular porcentajes
                    var porcentajeEfectivas = totalCitas > 0 ? ((citasEfectivas / totalCitas) * 100).toFixed(1) : 0;
                    var porcentajeRegistros = totalCitas > 0 ? ((registros / totalCitas) * 100).toFixed(1) : 0;
                    
                    // Actualizar números y porcentajes
                    $('#total-numero').text(totalCitas);
                    $('#total-porcentaje').text('100%');
                    
                    $('#efectivas-numero').text(citasEfectivas);
                    $('#efectivas-porcentaje').text(porcentajeEfectivas + '%');
                    
                    $('#registros-numero').text(registros);
                    $('#registros-porcentaje').text(porcentajeRegistros + '%');
                    
                    // Crear gráfico unificado con un delay para asegurar que el DOM esté listo
                    setTimeout(function() {
                        crearGraficoEmbudoUnificado(totalCitas, citasEfectivas, registros);
                    }, 100);
                }).catch(function(error) {
                    console.error('Error al mostrar embudo de citas:', error);
                    $('#embudo-citas').hide();
                });
                
                embudoTimeout = null;
            }, 50);
        }

        // =====================================
        // TABLA DINÁMICA
        // =====================================
        
        function inicializarTabla() {
            console.log('Inicializando tabla Handsontable...');
            console.log('Configuración de columnas:', columnasConfig);
            
            var container = document.getElementById('tabla-citas');
            var datosBase = generarHorariosFijos();
            
            hot = new Handsontable(container, {
                data: datosBase,
                colHeaders: generarHeaders(),
                columns: generarColumnas(),
                rowHeaders: true,
                height: 600,
                licenseKey: 'non-commercial-and-evaluation',
                cells: function(row, col) {
                    var cellProperties = {};
                    var campo = obtenerCampo(col);
                    
                    // Aplicar renderer personalizado para estatus
                    if (campo === 'est_cit') {
                        cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
                            Handsontable.renderers.DropdownRenderer.apply(this, arguments);
                            
                            if (value && value !== '') {
                                var color = obtenerColorEstatus(value);
                                if (color) {
                                    var colorTexto = getContrastColor(color);
                                    td.style.backgroundColor = color + ' !important';
                                    td.style.color = colorTexto + ' !important';
                                    td.style.fontWeight = 'bold';
                                    td.style.textAlign = 'center';
                                }
                            }
                        };
                    }
                    
                    return cellProperties;
                },
                licenseKey: 'non-commercial-and-evaluation',
                contextMenu: {
                    items: {
                        'row_above': {
                            name: 'Insertar fila arriba'
                        },
                        'row_below': {
                            name: 'Insertar fila abajo'
                        },
                        'sep1': '---------',
                        'ver_historial': {
                            name: 'Ver historial',
                            callback: function(key, selection, clickEvent) {
                                verHistorialCita(selection);
                            },
                            disabled: function() {
                                // Habilitar solo si hay una cita en la fila seleccionada
                                var selected = hot.getSelected();
                                if (selected && selected.length > 0) {
                                    var row = selected[0][0];
                                    var data = hot.getDataAtRow(row);
                                    var idCitIndex = obtenerIndiceColumna('id_cit');
                                    return !data || !data[idCitIndex] || data[idCitIndex] === '';
                                }
                                return true;
                            }
                        },
                        'eliminar_cita': {
                            name: 'Eliminar cita',
                            callback: function(key, selection, clickEvent) {
                                eliminarCitaSeleccionada(selection);
                            },
                            disabled: function() {
                                // Habilitar solo si hay una cita en la fila seleccionada
                                var selected = hot.getSelected();
                                if (selected && selected.length > 0) {
                                    var row = selected[0][0];
                                    var data = hot.getDataAtRow(row);
                                    var idCitIndex = obtenerIndiceColumna('id_cit');
                                    return !data || !data[idCitIndex] || data[idCitIndex] === '';
                                }
                                return true;
                            }
                        },
                        'sep2': '---------',
                        'agregar_comentario': {
                            name: 'Agregar comentario',
                            callback: function(key, selection, clickEvent) {
                                agregarComentario(selection);
                            },
                            disabled: function() {
                                // Habilitar solo si hay una cita en la fila seleccionada
                                var selected = hot.getSelected();
                                if (selected && selected.length > 0) {
                                    var row = selected[0][0];
                                    var data = hot.getDataAtRow(row);
                                    var idCitIndex = obtenerIndiceColumna('id_cit');
                                    return !data || !data[idCitIndex] || data[idCitIndex] === '';
                                }
                                return true;
                            }
                        },
                        'ver_comentarios': {
                            name: 'Ver comentarios',
                            callback: function(key, selection, clickEvent) {
                                verComentarios(selection);
                            },
                            disabled: function() {
                                // Habilitar solo si hay una cita en la fila seleccionada
                                var selected = hot.getSelected();
                                if (selected && selected.length > 0) {
                                    var row = selected[0][0];
                                    var data = hot.getDataAtRow(row);
                                    var idCitIndex = obtenerIndiceColumna('id_cit');
                                    return !data || !data[idCitIndex] || data[idCitIndex] === '';
                                }
                                return true;
                            }
                        },
                        'sep3': '---------',
                        'cambiar_color': {
                            name: 'Cambiar color de celda',
                            callback: function(key, selection, clickEvent) {
                                cambiarColorCelda(selection);
                            },
                            disabled: function() {
                                // Habilitar solo si hay una cita en la fila seleccionada
                                var selected = hot.getSelected();
                                if (selected && selected.length > 0) {
                                    var row = selected[0][0];
                                    var data = hot.getDataAtRow(row);
                                    var idCitIndex = obtenerIndiceColumna('id_cit');
                                    return !data || !data[idCitIndex] || data[idCitIndex] === '';
                                }
                                return true;
                            }
                        },
                        'quitar_color': {
                            name: 'Quitar color de celda',
                            callback: function(key, selection, clickEvent) {
                                quitarColorCelda(selection);
                            },
                            disabled: function() {
                                // Habilitar solo si hay una cita en la fila seleccionada
                                var selected = hot.getSelected();
                                if (selected && selected.length > 0) {
                                    var row = selected[0][0];
                                    var data = hot.getDataAtRow(row);
                                    var idCitIndex = obtenerIndiceColumna('id_cit');
                                    return !data || !data[idCitIndex] || data[idCitIndex] === '';
                                }
                                return true;
                            }
                        },
                        'sep4': '---------',
                        'undo': {
                            name: 'Deshacer'
                        },
                        'redo': {
                            name: 'Rehacer'
                        }
                    }
                },
                stretchH: 'all',
                
                // Evento para manejar cambios
                afterChange: function(changes, source) {
                    if (changes && source !== 'loadData' && source !== 'websocket') {
                        changes.forEach(function([row, prop, oldValue, newValue]) {
                            if (newValue !== oldValue && prop > 0) {
                                manejarCambioEnFila(row, prop, newValue, oldValue);
                                
                                // Aplicar colores automáticamente para cambios de estatus o efectividad
                                var campo = obtenerCampo(prop);
                                if (campo === 'est_cit' && newValue && newValue !== '') {
                                    setTimeout(function() {
                                        // Forzar re-render de la celda específica
                                        hot.render();
                                        mostrarConteosEstatus();
                                    }, 100);
                                } else if (campo === 'efe_cit' && newValue && newValue !== '') {
                                    setTimeout(function() {
                                        // Forzar re-render de la celda específica
                                        hot.render();
                                        mostrarConteosEfectividad();
                                    }, 100);
                                }
                            }
                        });
                    }
                },
                
                // Evento cuando se selecciona una celda diferente
                afterSelection: function(row, column, row2, column2, preventScrolling, selectionLayerLevel) {
                    if (filaEditandose !== null && filaEditandose !== row) {
                        // El usuario cambió de fila, guardar cambios pendientes
                        guardarCambiosPendientes();
                    }
                },
                
                // Evento antes de perder el foco
                beforeOnCellMouseDown: function(event, coords, TD) {
                    if (filaEditandose !== null && filaEditandose !== coords.row) {
                        // El usuario hizo clic en otra fila, guardar cambios pendientes
                        guardarCambiosPendientes();
                    }
                },
                
                // Evento antes de validación
                beforeChange: function(changes, source) {
                    if (source === 'edit') {
                        changes.forEach(function([row, prop, oldValue, newValue]) {
                            var campo = obtenerCampo(prop);
                            if (campo === 'id_eje2' && newValue) {
                                var idEjecutivo = obtenerIdEjecutivo(newValue);
                                if (idEjecutivo) {
                                    changes[0][3] = idEjecutivo;
                                }
                            }
                        });
                    }
                },
                
                // Evento antes de eliminar fila - capturar IDs de citas
                beforeRemoveRow: function(index, amount, physicalRows, source) {
                    if (source !== 'loadData') {
                        var idCitIndex = obtenerIndiceColumna('id_cit');
                        var citasAEliminar = [];
                        
                        // Capturar los IDs de las citas antes de que se eliminen
                        physicalRows.forEach(function(rowIndex) {
                            var rowData = hot.getSourceDataAtRow(rowIndex);
                            if (rowData && rowData[idCitIndex]) {
                                citasAEliminar.push(rowData[idCitIndex]);
                                console.log('BEFORE REMOVE ROW: Capturando cita con ID:', rowData[idCitIndex]);
                            }
                        });
                        
                        // Guardar los IDs para procesarlos después
                        hot._citasAEliminar = citasAEliminar;
                    }
                },
                
                // Evento después de eliminar fila - eliminar de base de datos
                afterRemoveRow: function(index, amount, physicalRows, source) {
                    if (source !== 'loadData' && hot._citasAEliminar) {
                        // Procesar las citas capturadas en beforeRemoveRow
                        hot._citasAEliminar.forEach(function(id_cit) {
                            console.log('AFTER REMOVE ROW: Eliminando cita con ID:', id_cit);
                            eliminarCitaBaseDatos(id_cit);
                        });
                        
                        // Limpiar la variable temporal
                        delete hot._citasAEliminar;
                    }
                },
                
                // Renderer personalizado para grupos de horarios
                afterRenderer: function(TD, row, col, prop, value, cellProperties) {
                    var campo = obtenerCampo(col);
                    
                    // Renderer para ejecutivos
                    if (campo === 'id_eje2' && value) {
                        var nombreEjecutivo = obtenerNombreEjecutivo(value);
                        if (nombreEjecutivo) {
                            TD.innerHTML = nombreEjecutivo;
                        }
                    }
                    
                    // Renderer para estatus de cita (Práctica 22)
                    if (campo === 'est_cit' && value) {
                        var colorEstatus = obtenerColorEstatus(value);
                        if (colorEstatus) {
                            TD.style.backgroundColor = colorEstatus;
                            TD.style.color = '#000000'; // Texto negro por defecto
                            TD.style.fontWeight = 'bold';
                        }
                    }
                    
                    // Renderer para efectividad de cita (Práctica 23)
                    if (campo === 'efe_cit' && value) {
                        var colorEfectividad = obtenerColorEfectividad(value);
                        if (colorEfectividad) {
                            console.log('🎨 Aplicando color efectividad:', value, colorEfectividad);
                            TD.style.backgroundColor = colorEfectividad.fondo;
                            TD.style.color = colorEfectividad.texto;
                            TD.style.fontWeight = 'bold';
                            TD.style.textAlign = 'center';
                        }
                    }
                    
                    // Aplicar colores personalizados (tienen prioridad sobre colores automáticos)
                    var claveCelda = row + ',' + col;
                    if (coloresCeldas[claveCelda]) {
                        console.log('🎨 Aplicando color personalizado en:', claveCelda, coloresCeldas[claveCelda]);
                        TD.style.backgroundColor = coloresCeldas[claveCelda].fondo;
                        TD.style.color = coloresCeldas[claveCelda].texto;
                        TD.classList.add('celda-coloreada');
                    } else if (campo !== 'est_cit' && campo !== 'efe_cit') {
                        // Solo limpiar estilos si no es columna de estatus o efectividad
                        TD.style.backgroundColor = '';
                        TD.style.color = '';
                        TD.classList.remove('celda-coloreada');
                    }
                    
                    // Estilo para filas de grupo horario
                    var esInicioGrupo = row % citasPorRango === 0;
                    if (esInicioGrupo && col === 0) {
                        TD.style.borderTop = '3px solid #007bff';
                        TD.style.fontWeight = 'bold';
                    }
                    
                    // Resaltar celdas vacías reservadas
                    var posicionEnGrupo = row % citasPorRango;
                    if (posicionEnGrupo >= 2 && !value && col > 0) {
                        // Solo aplicar si no hay color personalizado
                        if (!coloresCeldas[claveCelda]) {
                            TD.style.backgroundColor = '#ffffff';
                            TD.style.border = '1px dashed #cccccc';
                        }
                    }
                }
            });
        }
        

        function agregarNuevaColumna(){
            // Esta función ahora se llama desde el modal
            mostrarModalNuevaColumna();
        }
        
        function mostrarModalNuevaColumna() {
            $('#modalNuevaColumna').modal('show');
        }
        
        function crearNuevaColumna() {
            var nombreColumna = $('#nombreColumna').val().trim();
            var tipoColumna = $('#tipoColumna').val();
            
            if (!nombreColumna) {
                alert('Por favor ingrese un nombre para la columna');
                return;
            }
            
            // Validar formato del nombre
            if (!/^[a-zA-Z][a-zA-Z0-9_]*$/.test(nombreColumna)) {
                alert('El nombre de la columna debe comenzar con una letra y contener solo letras, números y guiones bajos');
                return;
            }
            
            // Verificar que no exista ya
            var existeColumna = columnasConfig.some(function(col) {
                return col.key === nombreColumna;
            });
            
            if (existeColumna) {
                alert('Ya existe una columna con ese nombre');
                return;
            }
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: {
                    action: 'crear_nueva_columna',
                    nombre_columna: nombreColumna,
                    tipo_columna: tipoColumna
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        alert('Columna creada correctamente: ' + response.data.nombre_columna);
                        $('#modalNuevaColumna').modal('hide');
                        $('#nombreColumna').val('');
                        $('#tipoColumna').val('VARCHAR(100)');
                        
                        // Recargar estructura y tabla
                        recargarEstructura();
                    } else {
                        alert('Error al crear columna: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error de conexión al crear columna');
                }
            });
        }
        
        function recargarEstructura() {
            console.log('Recargando estructura de tabla...');
            cargarEstructuraTabla().then(function() {
                console.log('Estructura recargada, reinicializando tabla...');
                // Reinicializar tabla con nueva estructura
                if (hot) {
                    hot.destroy();
                }
                inicializarTabla();
                cargarCitas();
            }).catch(function(error) {
                console.error('Error al recargar estructura:', error);
                alert('Error al recargar estructura: ' + error);
            });
        }
        
        function actualizarConfiguracionTabla() {
            // Actualizar headers
            hot.updateSettings({
                colHeaders: generarHeaders(),
                columns: generarColumnas()
            });
        }
        
        function generarHorariosFijos() {
            var horarios = [];
            for (var h = 8; h <= 20; h++) {
                var inicio = h < 10 ? '0' + h + ':00' : h + ':00';
                var fin = (h + 1) < 10 ? '0' + (h + 1) + ':00' : (h + 1) + ':00';
                var rango = inicio + ' - ' + fin;
                
                // Crear múltiples filas para cada rango horario
                for (var i = 0; i < citasPorRango; i++) {
                    var fila = new Array(columnasConfig.length).fill('');
                    // Solo mostrar el rango en la primera fila de cada grupo
                    fila[0] = i === 0 ? rango : '';
                    horarios.push(fila);
                }
            }
            return horarios;
        }
        
        function manejarCambioEnFila(row, column, newValue, oldValue) {
            var campo = obtenerCampo(column);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            var id_cit = hot.getDataAtCell(row, idCitIndex);
            
            // Establecer la fila que se está editando
            if (filaEditandose === null || filaEditandose !== row) {
                filaEditandose = row;
                datosPendientes = {}; // Limpiar datos pendientes al cambiar de fila
            }
            
            // Guardar el cambio en los datos pendientes
            datosPendientes[campo] = newValue;
            
            console.log('Cambio detectado en fila', row, '- Campo:', campo, '- Valor:', newValue);
            console.log('Datos pendientes para fila', row, ':', datosPendientes);
            
            // Aplicar color automáticamente si el campo es estatus (Práctica 22)
            if (campo === 'est_cit' && newValue && newValue !== '') {
                setTimeout(function() {
                    aplicarColorEstatus(row, column, newValue);
                    // Los conteos se actualizan dentro de aplicarColorEstatus()
                }, 50);
                
                // Guardar inmediatamente en la base de datos si hay ID de cita
                if (id_cit && id_cit !== '') {
                    console.log('🔄 Guardando estatus inmediatamente:', campo, newValue);
                    $.ajax({
                        url: 'server/controlador_citas.php',
                        type: 'POST',
                        data: {
                            action: 'actualizar_cita',
                            id_cit: id_cit,
                            campo: campo,
                            valor: newValue
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                console.log('✅ Estatus guardado correctamente');
                            } else {
                                console.error('❌ Error al guardar estatus:', response.message);
                                alert('Error al guardar estatus: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('❌ Error de conexión al guardar estatus:', error);
                            alert('Error de conexión al guardar estatus');
                        }
                    });
                }
            }
            
            // Aplicar color automáticamente si el campo es efectividad (Práctica 23)
            if (campo === 'efe_cit' && newValue && newValue !== '') {
                console.log('🎯 Procesando cambio de efectividad:', campo, '=', newValue, 'en fila', row);
                setTimeout(function() {
                    aplicarColorEfectividad(row, column, newValue);
                    // Los conteos se actualizan dentro de aplicarColorEfectividad()
                }, 50);
                
                // Guardar inmediatamente en la base de datos si hay ID de cita
                if (id_cit && id_cit !== '') {
                    console.log('🔄 Guardando efectividad inmediatamente:', campo, newValue, 'para cita ID:', id_cit);
                    $.ajax({
                        url: 'server/controlador_citas.php',
                        type: 'POST',
                        data: {
                            action: 'actualizar_cita',
                            id_cit: id_cit,
                            campo: campo,
                            valor: newValue
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('📊 Respuesta del servidor para efectividad:', response);
                            if (response.success) {
                                console.log('✅ Efectividad guardada correctamente en BD');
                                mostrarBadgeWebSocket('success', 'Efectividad actualizada: ' + newValue);
                            } else {
                                console.error('❌ Error al guardar efectividad:', response.message);
                                alert('Error al guardar efectividad: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('❌ Error de conexión al guardar efectividad:', error);
                            console.error('Detalles del error:', xhr.responseText);
                            alert('Error de conexión al guardar efectividad: ' + error);
                        }
                    });
                } else {
                    console.warn('⚠️ No se puede guardar efectividad: no hay ID de cita válido');
                }
            }
            
            // Enviar mensaje WebSocket si existe una cita (ID no vacío)
            if (id_cit && id_cit !== '') {
                enviarMensajeWebSocket('cita_actualizada', {
                    id_cit: id_cit,
                    campo: campo,
                    valor: newValue
                });
            }
        }
        
        function guardarCambiosPendientes() {
            if (filaEditandose === null || Object.keys(datosPendientes).length === 0) {
                return;
            }
            
            var idCitIndex = obtenerIndiceColumna('id_cit');
            var id_cit = hot.getDataAtCell(filaEditandose, idCitIndex);
            
            console.log('Guardando cambios pendientes para fila', filaEditandose, '- ID:', id_cit);
            console.log('Datos a guardar:', datosPendientes);
            
            if (!id_cit) {
                // Nueva cita - crear con todos los datos pendientes
                crearNuevaCitaCompleta(filaEditandose, datosPendientes);
            } else {
                // Cita existente - actualizar campos modificados
                actualizarCitaCompleta(filaEditandose, id_cit, datosPendientes);
            }
            
            // Limpiar estado
            filaEditandose = null;
            datosPendientes = {};
        }
        
        function obtenerRangoHorario(fila) {
            // Calcular el rango horario basado en la fila
            var grupoHorario = Math.floor(fila / citasPorRango);
            var hora = grupoHorario + 8;
            
            if (hora >= 8 && hora <= 20) {
                var inicio = hora < 10 ? '0' + hora + ':00' : hora + ':00';
                var fin = (hora + 1) < 10 ? '0' + (hora + 1) + ':00' : (hora + 1) + ':00';
                return inicio + ' - ' + fin;
            }
            return '';
        }
        
        function crearNuevaCitaCompleta(row, datosPendientes) {
            // Recopilar datos de la fila para crear nueva cita
            var rowData = hot.getDataAtRow(row);
            
            // Usar fecha del filtro como valor por defecto si no hay fecha especificada
            var fechaIndex = obtenerIndiceColumna('cit_cit');
            var fecha = datosPendientes['cit_cit'] || rowData[fechaIndex] || $('#fecha-filtro').val() || new Date().toISOString().split('T')[0];
            
            // Generar hora basada en el rango horario si no se especifica
            var horaIndex = obtenerIndiceColumna('hor_cit');
            var hora = datosPendientes['hor_cit'] || rowData[horaIndex];
            if (!hora) {
                var rangoHorario = obtenerRangoHorario(row);
                if (rangoHorario) {
                    hora = rangoHorario.split(' - ')[0] + ':00';
                } else {
                    hora = '09:00:00';
                }
            }
            
            // Asegurar que la hora tenga el formato correcto
            if (hora && hora.length <= 5) {
                hora = hora + ':00';
            }
            
            // Preparar datos dinámicamente basado en la configuración de columnas
            var datos = { action: 'guardar_cita' };
            
            // Combinar datos pendientes con datos de la fila
            columnasConfig.forEach(function(col, index) {
                if (col.key !== 'horario' && col.key !== 'nom_eje') { // Excluir columnas virtuales
                    var valor = datosPendientes[col.key] || rowData[index] || '';
                    
                    // Solo agregar valores no vacíos para permitir NULL en la BD
                    if (valor !== '') {
                        datos[col.key] = valor;
                    }
                }
            });
            
            // Siempre incluir fecha y hora por defecto para evitar problemas
            datos['cit_cit'] = fecha;
            datos['hor_cit'] = hora;
            
            // Validar que al menos haya algún dato significativo para crear la cita
            if (!datos.nom_cit && !datos.tel_cit && !datos.id_eje2) {
                console.log('No hay datos suficientes para crear la cita');
                return;
            }
            
            console.log('Enviando datos para nueva cita completa:', datos);
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: datos,
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    if(response.success) {
                        // Actualizar el ID en la tabla
                        var idCitIndex = obtenerIndiceColumna('id_cit');
                        hot.setDataAtCell(row, idCitIndex, response.data.id);
                        console.log('Nueva cita creada con ID:', response.data.id);
                        
                        // Enviar mensaje WebSocket de nueva cita
                        enviarMensajeWebSocket('cita_creada', {
                            id_cit: response.data.id,
                            datos: datos
                        });
                        
                        // Mostrar badge de éxito
                        mostrarBadgeWebSocket('success', 'Nueva cita creada');
                    } else {
                        alert('Error al crear cita: ' + response.message);
                        cargarCitas();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    alert('Error de conexión al crear cita. Revise la consola para más detalles.');
                    cargarCitas();
                }
            });
        }
        
        function actualizarCitaCompleta(row, id_cit, datosPendientes) {
            // Actualizar todos los campos modificados de una vez
            var actualizaciones = [];
            
            Object.keys(datosPendientes).forEach(function(campo) {
                var valor = datosPendientes[campo];
                actualizaciones.push({
                    campo: campo,
                    valor: valor
                });
            });
            
            if (actualizaciones.length === 0) {
                return;
            }
            
            console.log('Actualizando cita', id_cit, 'con cambios:', actualizaciones);
            
            // Procesar actualizaciones una por una
            var procesarActualizacion = function(index) {
                if (index >= actualizaciones.length) {
                    console.log('Todas las actualizaciones completadas');
                    return;
                }
                
                var update = actualizaciones[index];
                
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: {
                        action: 'actualizar_cita',
                        campo: update.campo,
                        valor: update.valor,
                        id_cit: id_cit
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            console.log('Campo', update.campo, 'actualizado correctamente');
                            // Procesar siguiente actualización
                            procesarActualizacion(index + 1);
                        } else {
                            alert('Error al actualizar campo ' + update.campo + ': ' + response.message);
                            cargarCitas();
                        }
                    },
                    error: function() {
                        alert('Error de conexión al actualizar campo ' + update.campo);
                        cargarCitas();
                    }
                });
            };
            
            // Iniciar proceso de actualizaciones
            procesarActualizacion(0);
        }
        
        function cargarConfiguracionColumnas() {
            return new Promise(function(resolve, reject) {
                // Cargar columnas dinámicas desde la base de datos
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: { action: 'obtener_columnas_dinamicas' },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success && response.data) {
                            response.data.forEach(function(columna) {
                                var nuevaColumna = {
                                    key: columna.nombre,
                                    header: columna.nombre.replace('col_dinamica_', 'Columna ').replace(/_\d+_\d+$/, ''),
                                    type: 'text',
                                    width: 150
                                };
                                columnasConfig.push(nuevaColumna);
                            });
                            console.log('Columnas dinámicas cargadas desde BD:', response.data);
                        }
                        resolve();
                    },
                    error: function() {
                        console.error('Error al cargar columnas dinámicas desde BD');
                        resolve(); // No fallar la inicialización
                    }
                });
            });
        }
        
        function eliminarCitaBaseDatos(id_cit) {
            if (!id_cit) {
                console.log('No hay ID de cita para eliminar');
                return;
            }
            
            console.log('Eliminando cita con ID:', id_cit);
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: {
                    action: 'eliminar_cita',
                    id_cit: id_cit
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    if(response.success) {
                        console.log('Cita eliminada correctamente de la base de datos');
                        
                        // Enviar mensaje WebSocket de eliminación
                        enviarMensajeWebSocket('cita_eliminada', {
                            id_cit: id_cit
                        });
                        
                        // Mostrar badge de éxito
                        mostrarBadgeWebSocket('warning', 'Cita eliminada');
                        
                        // Recargar inmediatamente para actualizar la vista
                        if (modoFiltroFecha) {
                            cargarCitas();
                        } else {
                            buscarCitas();
                        }
                    } else {
                        console.error('Error al eliminar cita:', response.message);
                        alert('Error al eliminar cita: ' + response.message);
                        cargarCitas();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    alert('Error de conexión al eliminar cita');
                    cargarCitas();
                }
            });
        }
        
        // =====================================
        // CARGA Y BÚSQUEDA DE DATOS
        // =====================================
        
        function cargarCitas() {
            // Validar rango de fechas antes de continuar
            if (!validarRangoFechas()) {
                return;
            }
            
            modoFiltroFecha = true;
            var fechaInicio = $('#fecha-inicio-filtro').val();
            var fechaFin = $('#fecha-fin-filtro').val();
            var idEjecutivo = $('#ejecutivo-filtro').val();
            var idPlantel = $('#plantel-filtro').val();
            var incluirPlanteles = $('#planteles-asociados-filtro').is(':checked');
            
            var datos = { 
                action: 'obtener_citas'
            };
            
            // Agregar filtros de fecha si están presentes
            if (fechaInicio) {
                datos.fecha_inicio = fechaInicio;
            }
            if (fechaFin) {
                datos.fecha_fin = fechaFin;
            }
            
            if (idEjecutivo) {
                datos.id_ejecutivo = idEjecutivo;
                datos.incluir_planteles_asociados = incluirPlanteles ? 'true' : 'false';
            }
            
            if (idPlantel) {
                datos.id_plantel = idPlantel;
            }
            
            console.log('=== DEBUG CARGAR CITAS ===');
            console.log('Fecha inicio:', fechaInicio);
            console.log('Fecha fin:', fechaFin);
            console.log('ID Ejecutivo:', idEjecutivo);
            console.log('ID Plantel:', idPlantel);
            console.log('Incluir planteles:', incluirPlanteles);
            console.log('Datos enviados:', datos);
            console.log('========================');
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: datos,
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        console.log('Citas cargadas:', response.data.length, 'registros');
                        // Mostrar algunas citas para debug
                        mostrarCitasEnTabla(response.data, true);
                        actualizarInfoFiltroActivo();
                        
                        // Cargar indicadores de comentarios después de mostrar las citas
                        setTimeout(function() {
                            cargarIndicadoresComentarios();
                        }, 500);
                        
                        // Mostrar conteos de estatus (Práctica 22)
                        setTimeout(function() {
                            mostrarConteosEstatus();
                        }, 600);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar citas:', error);
                    alert('Error de conexión al servidor: ' + error);
                }
            });
        }
        
        function actualizarInfoFiltroActivo() {
            var filtros = [];
            var fechaInicio = $('#fecha-inicio-filtro').val();
            var fechaFin = $('#fecha-fin-filtro').val();
            var idEjecutivo = $('#ejecutivo-filtro').val();
            var idPlantel = $('#plantel-filtro').val();
            var incluirPlanteles = $('#planteles-asociados-filtro').is(':checked');
            
            if (fechaInicio && fechaFin) {
                filtros.push('📅 Fechas: ' + fechaInicio + ' a ' + fechaFin);
            } else if (fechaInicio) {
                filtros.push('📅 Desde: ' + fechaInicio);
            } else if (fechaFin) {
                filtros.push('📅 Hasta: ' + fechaFin);
            }
            
            if (idEjecutivo) {
                var nombreEjecutivo = $('#ejecutivo-filtro option:selected').text();
                filtros.push('👤 Ejecutivo: ' + nombreEjecutivo);
                
                if (incluirPlanteles) {
                    filtros.push('🕋 Incluyendo planteles asociados');
                }
            }
            
            if (idPlantel) {
                var nombrePlantel = $('#plantel-filtro option:selected').text();
                filtros.push('🏢 Plantel: ' + nombrePlantel);
            }
            
            if (filtros.length > 0) {
                $('#detalle-filtros').text(filtros.join(' | '));
                $('#info-filtro-activo').show();
            } else {
                $('#info-filtro-activo').hide();
            }
        }
        
        function aplicarFiltroRapido(tipo) {
            var fechaHoy = new Date();
            var fechaInicio, fechaFin;
            
            switch(tipo) {
                case 'hoy':
                    fechaInicio = fechaFin = fechaHoy.toISOString().split('T')[0];
                    break;
                    
                case 'semana':
                    // Inicio de la semana (lunes)
                    var inicioSemana = new Date(fechaHoy);
                    inicioSemana.setDate(fechaHoy.getDate() - fechaHoy.getDay() + 1);
                    fechaInicio = inicioSemana.toISOString().split('T')[0];
                    fechaFin = fechaHoy.toISOString().split('T')[0];
                    break;
                    
                case 'mes':
                    // Inicio del mes actual
                    var inicioMes = new Date(fechaHoy.getFullYear(), fechaHoy.getMonth(), 1);
                    fechaInicio = inicioMes.toISOString().split('T')[0];
                    fechaFin = fechaHoy.toISOString().split('T')[0];
                    break;
                    
                case 'ultimos7':
                    var hace7Dias = new Date(fechaHoy);
                    hace7Dias.setDate(fechaHoy.getDate() - 7);
                    fechaInicio = hace7Dias.toISOString().split('T')[0];
                    fechaFin = fechaHoy.toISOString().split('T')[0];
                    break;
                    
                case 'ultimos30':
                    var hace30Dias = new Date(fechaHoy);
                    hace30Dias.setDate(fechaHoy.getDate() - 30);
                    fechaInicio = hace30Dias.toISOString().split('T')[0];
                    fechaFin = fechaHoy.toISOString().split('T')[0];
                    break;
            }
            
            $('#fecha-inicio-filtro').val(fechaInicio);
            $('#fecha-fin-filtro').val(fechaFin);
            cargarCitas();
        }
        
        function buscarCitas() {
            var termino = $('#buscador-citas').val().trim();
            if (!termino) {
                //alert('Ingrese un término de búsqueda');
                limpiarBusqueda();
                return;
            }
            
            modoFiltroFecha = false;
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: { 
                    action: 'obtener_citas'
                    // Sin fecha_filtro para obtener todas las citas
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        // Filtrar localmente por el término de búsqueda
                        var citasFiltradas = response.data.filter(function(cita) {
                            return (cita.nom_cit && cita.nom_cit.toLowerCase().includes(termino.toLowerCase())) ||
                                   (cita.tel_cit && cita.tel_cit.includes(termino)) ||
                                   (cita.nom_eje && cita.nom_eje.toLowerCase().includes(termino.toLowerCase()));
                        });
                        
                        mostrarCitasEnTabla(citasFiltradas, false);
                        
                        // Cargar indicadores de comentarios después de mostrar las citas
                        setTimeout(function() {
                            cargarIndicadoresComentarios();
                        }, 500);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error de conexión al servidor');
                }
            });
        }
        
        function limpiarBusqueda() {
            $('#buscador-citas').val('');
            cargarCitas();
        }
        
        function limpiarFiltros() {
            // Configurar fechas por defecto (última semana)
            var fechaHoy = new Date();
            var fechaFin = fechaHoy.toISOString().split('T')[0];
            var fechaInicioDate = new Date(fechaHoy);
            fechaInicioDate.setDate(fechaInicioDate.getDate() - 7); // Una semana atrás
            var fechaInicio = fechaInicioDate.toISOString().split('T')[0];
            
            $('#fecha-inicio-filtro').val(fechaInicio);
            $('#fecha-fin-filtro').val(fechaFin);
            $('#ejecutivo-filtro').val('');
            $('#plantel-filtro').val('');
            $('#planteles-asociados-filtro').prop('checked', false);
            
            // Limpiar mensaje de navegación
            $('#mensajeNavegacion').html('');
            
            // Ocultar información del filtro activo
            $('#info-filtro-activo').hide();
            
            cargarCitas();
        }
        
        function mostrarCitasEnTabla(citas, usarHorariosFijos) {
            var datos;
            
            if (usarHorariosFijos) {
                // Modo normal con horarios fijos expandidos
                datos = generarHorariosFijos();
                
                // Agrupar citas por hora para distribución
                var citasPorHora = {};
                citas.forEach(function(cita) {
                    var hora = parseInt(cita.hor_cit.split(':')[0]);
                    if (!citasPorHora[hora]) {
                        citasPorHora[hora] = [];
                    }
                    citasPorHora[hora].push(cita);
                });
                
                // Distribuir citas en las filas correspondientes
                Object.keys(citasPorHora).forEach(function(hora) {
                    var horaNum = parseInt(hora);
                    var indiceGrupoInicio = (horaNum - 8) * citasPorRango;
                    
                    if (indiceGrupoInicio >= 0 && indiceGrupoInicio < datos.length) {
                        citasPorHora[hora].forEach(function(cita, index) {
                            var indiceFila = indiceGrupoInicio + index;
                            if (indiceFila < datos.length) {
                                // Mantener el rango de horario solo en la primera fila del grupo
                                var rangoHorario = datos[indiceFila][0];
                                datos[indiceFila] = mapearCitaAFila(cita, rangoHorario);
                            }
                        });
                    }
                });
            } else {
                // Modo búsqueda - mostrar solo resultados
                datos = citas.map(function(cita) {
                    return mapearCitaAFila(cita, '');
                });
            }
            
            hot.loadData(datos);
            
            // Aplicar colores de estatus después de cargar los datos
            setTimeout(function() {
                aplicarColoresEstatus();
                cargarColoresCeldas(); // Cargar colores desde la base de datos
                // Mostrar conteos después de aplicar colores
                setTimeout(function() {
                    mostrarConteosEstatus();
                    mostrarConteosEfectividad();
                }, 200);
            }, 300);
        }
        
        // Función para aplicar colores de estatus a todas las celdas de estatus
        function aplicarColoresEstatus() {
            if (!hot) return;
            
            // Simplemente hacer render ya que el renderer personalizado se encargará de los colores
            hot.render();
        }
        
        function mapearCitaAFila(cita, horario) {
            var fila = new Array(columnasConfig.length).fill('');
            
            columnasConfig.forEach(function(col, index) {
                if (col.key === 'horario') {
                    fila[index] = horario;
                } else if (cita.hasOwnProperty(col.key)) {
                    // Mapear directamente desde los datos de la cita
                    fila[index] = cita[col.key] || '';
                }
            });
            
            return fila;
        }
        
        // =====================================
        // FUNCIONES DE HISTORIAL
        // =====================================
        
        function verHistorialCita(selection) {
            if (!selection || selection.length === 0) {
                alert('Por favor selecciona una fila para ver el historial');
                return;
            }
            
            var row = selection[0].start.row;
            var data = hot.getDataAtRow(row);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            var nombreIndex = obtenerIndiceColumna('nom_cit');
            
            if (!data || !data[idCitIndex] || data[idCitIndex] === '') {
                alert('No hay una cita en esta fila para ver el historial');
                return;
            }
            
            var idCit = data[idCitIndex];
            var nombreCita = data[nombreIndex] || 'Sin nombre';
            
            // Mostrar información de la cita
            $('#idCitaHistorial').text(idCit);
            $('#nombreCitaHistorial').text(nombreCita);
            
            // Cargar historial
            cargarHistorialCita(idCit);
            
            // Mostrar modal
            $('#modalHistorialCita').modal('show');
        }
        
        function cargarHistorialCita(idCit) {
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: {
                    action: 'obtener_historial_cita',
                    id_cit: idCit
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        mostrarHistorialEnTabla(response.data);
                    } else {
                        alert('Error al cargar historial: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error de conexión al cargar historial');
                }
            });
        }
        
        function mostrarHistorialEnTabla(historial) {
            var tbody = $('#tablaHistorialCita');
            tbody.empty();
            
            if (!historial || historial.length === 0) {
                $('#sinHistorial').show();
                return;
            }
            
            $('#sinHistorial').hide();
            
            historial.forEach(function(registro) {
                var fecha = new Date(registro.fec_his_cit);
                var fechaFormateada = fecha.toLocaleString('es-ES', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                
                var movimientoClass = '';
                var movimientoIcon = '';
                
                switch(registro.mov_his_cit) {
                    case 'alta':
                        movimientoClass = 'badge-success';
                        movimientoIcon = '➕';
                        break;
                    case 'cambio':
                        movimientoClass = 'badge-warning';
                        movimientoIcon = '✏️';
                        break;
                    case 'baja':
                        movimientoClass = 'badge-danger';
                        movimientoIcon = '🗑️';
                        break;
                }
                
                var fila = `
                    <tr>
                        <td style="white-space: nowrap;">${fechaFormateada}</td>
                        <td>${registro.res_his_cit}</td>
                        <td>
                            <span class="badge ${movimientoClass}">
                                ${movimientoIcon} ${registro.mov_his_cit.toUpperCase()}
                            </span>
                        </td>
                        <td>${registro.des_his_cit}</td>
                    </tr>
                `;
                
                tbody.append(fila);
            });
        }
        
        // =====================================
        // FUNCIONES DE ELIMINACIÓN
        // =====================================
        
        function eliminarCitaSeleccionada(selection) {
            if (!selection || selection.length === 0) {
                alert('Por favor selecciona una fila para eliminar');
                return;
            }
            
            var row = selection[0].start.row;
            var data = hot.getDataAtRow(row);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            if (!data || !data[idCitIndex] || data[idCitIndex] === '') {
                alert('No hay una cita en esta fila para eliminar');
                return;
            }
            
            var idCit = data[idCitIndex];
            var nombreIndex = obtenerIndiceColumna('nom_cit');
            var nombreCita = data[nombreIndex] || 'Sin nombre';
            
            console.log('Eliminando cita:', {id: idCit, nombre: nombreCita});
            
            // Eliminar directamente sin confirmación
            eliminarCitaBaseDatos(idCit);
            // Remover la fila visualmente después de la eliminación exitosa
            setTimeout(function() {
                cargarCitas(); // Recargar datos para actualizar la vista
            }, 500);
        }
        
        // =====================================
        // FUNCIONES DE UTILIDAD
        // =====================================
        
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
        
        function regresarAOrigen() {
            var params = obtenerParametrosURL();
            var url = '';
            
            if (params.origen === 'ejecutivos') {
                url = 'arbol_ejecutivos.php';
            } else if (params.origen === 'plantel') {
                url = 'arbol_planteles.php';
            } else {
                // Fallback al árbol de ejecutivos
                url = 'arbol_ejecutivos.php';
            }
            
            // Conservar los filtros de fecha para mantener la consistencia
            var queryString = '';
            if (params.fecha_inicio) {
                queryString += (queryString ? '&' : '') + 'fechaInicio=' + params.fecha_inicio;
            }
            if (params.fecha_fin) {
                queryString += (queryString ? '&' : '') + 'fechaFin=' + params.fecha_fin;
            }
            
            if (queryString) {
                url += '?' + queryString;
            }
            
            window.location.href = url;
        }
        
        function aplicarFiltrosDesdeURL() {
            var params = obtenerParametrosURL();
            
            console.log('=== DEBUG APLICAR FILTROS URL ===');
            console.log('Parámetros URL:', params);
            
            // Aplicar filtro de ejecutivo si existe
            if (params.ejecutivo) {
                $('#ejecutivo-filtro').val(params.ejecutivo);
                console.log('Ejecutivo seleccionado:', params.ejecutivo);
            }
            
            // Aplicar filtro de plantel si existe
            if (params.plantel) {
                $('#plantel-filtro').val(params.plantel);
                console.log('Plantel seleccionado:', params.plantel);
            }
            
            // Aplicar filtros de fecha si existen
            if (params.fecha_inicio) {
                $('#fecha-inicio-filtro').val(params.fecha_inicio);
                console.log('Fecha inicio:', params.fecha_inicio);
            }
            
            if (params.fecha_fin) {
                $('#fecha-fin-filtro').val(params.fecha_fin);
                console.log('Fecha fin:', params.fecha_fin);
            }
            
            // Manejar checkbox de planteles asociados según el tipo de conteo
            if (params.tipo_conteo === 'propias') {
                // Para citas propias, nunca incluir planteles asociados
                $('#planteles-asociados-filtro').prop('checked', false);
                console.log('Planteles asociados desactivado para citas propias');
            } else if (params.tipo_conteo === 'recursivas') {
                // Para citas recursivas, sí incluir planteles asociados
                $('#planteles-asociados-filtro').prop('checked', true);
                console.log('Planteles asociados activado para citas recursivas');
            } else if (params.incluir_planteles === 'true') {
                $('#planteles-asociados-filtro').prop('checked', true);
                console.log('Planteles asociados activado');
            } else {
                $('#planteles-asociados-filtro').prop('checked', false);
            }
            
            // Mostrar mensaje específico según el origen y tipo
            if (params.origen === 'plantel' || params.origen === 'ejecutivos') {
                var mensaje = '';
                var fechaTexto = '';
                
                // Generar texto de fechas si existen
                if (params.fecha_inicio && params.fecha_fin) {
                    fechaTexto = ' del ' + params.fecha_inicio + ' al ' + params.fecha_fin;
                } else if (params.fecha_inicio) {
                    fechaTexto = ' desde ' + params.fecha_inicio;
                } else if (params.fecha_fin) {
                    fechaTexto = ' hasta ' + params.fecha_fin;
                }
                
                if (params.ejecutivo) {
                    if (params.tipo_conteo === 'propias') {
                        mensaje = '<div class="alert alert-info alert-dismissible fade show" role="alert">' +
                                '<i class="fas fa-user"></i> <strong>Navegación desde Árbol de Ejecutivos:</strong> ' +
                                'Mostrando <strong>citas propias</strong> del ejecutivo seleccionado' + fechaTexto + '.' +
                                '<button type="button" class="close" data-dismiss="alert">' +
                                '<span aria-hidden="true">&times;</span></button></div>';
                    } else if (params.tipo_conteo === 'recursivas') {
                        mensaje = '<div class="alert alert-info alert-dismissible fade show" role="alert">' +
                                '<i class="fas fa-users"></i> <strong>Navegación desde Árbol de Ejecutivos:</strong> ' +
                                'Mostrando <strong>citas recursivas</strong> (incluye descendientes) del ejecutivo seleccionado' + fechaTexto + '.' +
                                '<button type="button" class="close" data-dismiss="alert">' +
                                '<span aria-hidden="true">&times;</span></button></div>';
                    } else {
                        mensaje = '<div class="alert alert-info alert-dismissible fade show" role="alert">' +
                                '<i class="fas fa-user"></i> <strong>Navegación desde Árbol de Ejecutivos:</strong> ' +
                                'Mostrando todas las citas del ejecutivo seleccionado' + fechaTexto + '.' +
                                '<button type="button" class="close" data-dismiss="alert">' +
                                '<span aria-hidden="true">&times;</span></button></div>';
                    }
                }
                
                if (params.plantel) {
                    mensaje = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<i class="fas fa-building"></i> <strong>Navegación desde Árbol de Planteles:</strong> ' +
                            'Mostrando <strong>citas totales</strong> del plantel seleccionado' + fechaTexto + '.' +
                            '<button type="button" class="close" data-dismiss="alert">' +
                            '<span aria-hidden="true">&times;</span></button></div>';
                }
                
                if (mensaje) {
                    $('#mensajeNavegacion').html(mensaje);
                }
                
                // Mostrar botón de regreso
                if (params.origen === 'ejecutivos') {
                    $('#textoRegreso').text('Regresar al Árbol de Ejecutivos');
                    $('#botonRegreso').show();
                } else if (params.origen === 'plantel') {
                    $('#textoRegreso').text('Regresar al Árbol de Planteles');
                    $('#botonRegreso').show();
                }
            }
            
            // Aplicar filtros automáticamente si se recibieron parámetros
            if (params.ejecutivo || params.plantel || params.fecha_inicio) {
                console.log('Aplicando filtros automáticamente...');
                cargarCitas();
                actualizarInfoFiltroActivo();
            }
        }
        
        // =====================================
        // FUNCIONES DE COMENTARIOS
        // =====================================
        
        var comentarioSeleccionado = null;
        
        // Función de debug para verificar el estado de la tabla
        function debugTabla() {
            console.log('=== DEBUG TABLA ===');
            console.log('hot:', hot);
            console.log('columnasConfig:', columnasConfig);
            console.log('selected:', hot ? hot.getSelected() : 'No hot');
            console.log('data sample:', hot ? hot.getData().slice(0, 3) : 'No hot');
            console.log('==================');
        }
        
        function agregarComentario(selection) {
            console.log('=== AGREGAR COMENTARIO ===');
            console.log('Selection recibida:', selection);
            debugTabla();
            
            // Obtener la selección actual de Handsontable
            var selected = hot.getSelected();
            console.log('Selección actual:', selected);
            
            if (!selected || selected.length === 0) {
                alert('Por favor selecciona una celda para agregar un comentario.');
                return;
            }
            
            var row = selected[0][0];
            var col = selected[0][1];
            var data = hot.getDataAtRow(row);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            console.log('Row:', row, 'Col:', col);
            console.log('Data completa:', data);
            console.log('ID Index:', idCitIndex);
            console.log('columnasConfig:', columnasConfig);
            
            // Verificar si la columna id_cit existe
            if (idCitIndex === -1) {
                alert('Error: No se pudo encontrar la columna id_cit en la configuración de la tabla.');
                console.error('columnasConfig no contiene id_cit:', columnasConfig);
                return;
            }
            
            // Verificar si hay datos en la fila
            if (!data || data.length === 0) {
                alert('Error: No hay datos en la fila seleccionada.');
                console.error('Datos de fila vacíos:', data);
                return;
            }
            
            // Verificar si hay un ID de cita válido
            var idCit = data[idCitIndex];
            console.log('ID Cita extraído:', idCit, 'Tipo:', typeof idCit);
            
            if (!idCit || idCit === '' || idCit === null || idCit === undefined) {
                alert('Esta fila no contiene una cita válida. Por favor selecciona una fila que contenga datos de una cita.\n\nDebug: Fila ' + (row + 1) + ', ID index: ' + idCitIndex + ', Valor: ' + idCit);
                console.error('ID de cita no válido:', {
                    row: row,
                    col: col,
                    idCitIndex: idCitIndex,
                    idCit: idCit,
                    data: data,
                    columnasConfig: columnasConfig
                });
                return;
            }
            
            // Guardar información de la celda seleccionada
            comentarioSeleccionado = {
                id_cit: idCit,
                fila: row,
                columna: col,
                columnaNombre: obtenerNombreColumna(col)
            };
            
            console.log('Comentario seleccionado:', comentarioSeleccionado);
            
            // Actualizar información en el modal
            $('#celdaComentario').text(`Fila ${row + 1}, Columna: ${comentarioSeleccionado.columnaNombre}`);
            $('#contenidoComentario').val('');
            
            // Mostrar modal
            $('#modalComentario').modal('show');
            
            console.log('=== FIN AGREGAR COMENTARIO ===');
        }
        
        function guardarComentario() {
            if (!comentarioSeleccionado) {
                alert('Error: No hay celda seleccionada.');
                return;
            }
            
            var contenido = $('#contenidoComentario').val().trim();
            if (!contenido) {
                alert('Por favor escribe un comentario.');
                return;
            }
            
            var datos = {
                action: 'guardar_comentario',
                id_cit: comentarioSeleccionado.id_cit,
                fila: comentarioSeleccionado.fila,
                columna: comentarioSeleccionado.columna,
                contenido: contenido,
                id_ejecutivo: miIdEjecutivo
            };
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: datos,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Cerrar modal
                        $('#modalComentario').modal('hide');
                        
                        // Aplicar indicador visual de comentario
                        aplicarIndicadorComentario(comentarioSeleccionado.fila, comentarioSeleccionado.columna);
                        
                        // Enviar mensaje WebSocket
                        enviarMensajeWebSocket('comentario_agregado', {
                            id_cit: comentarioSeleccionado.id_cit,
                            fila: comentarioSeleccionado.fila,
                            columna: comentarioSeleccionado.columna,
                            contenido: contenido,
                            id_comentario: response.data.id_comentario
                        });
                        
                        // Mostrar mensaje de éxito
                        mostrarBadgeWebSocket('success', 'Comentario agregado');
                        
                        // Limpiar selección
                        comentarioSeleccionado = null;
                    } else {
                        alert('Error al guardar comentario: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error de conexión al guardar comentario.');
                    console.error('Error:', error);
                }
            });
        }
        
        function verComentarios(selection) {
            console.log('=== VER COMENTARIOS ===');
            console.log('Selection recibida:', selection);
            
            // Obtener la selección actual de Handsontable
            var selected = hot.getSelected();
            console.log('Selección actual:', selected);
            
            if (!selected || selected.length === 0) {
                alert('Por favor selecciona una celda para ver los comentarios.');
                return;
            }
            
            var row = selected[0][0];
            var data = hot.getDataAtRow(row);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            console.log('Row:', row, 'Data:', data, 'ID Index:', idCitIndex);
            
            // Verificar si la columna id_cit existe
            if (idCitIndex === -1) {
                alert('Error: No se pudo encontrar la columna id_cit en la configuración de la tabla.');
                console.error('columnasConfig no contiene id_cit:', columnasConfig);
                return;
            }
            
            // Verificar si hay datos en la fila
            if (!data || data.length === 0) {
                alert('Error: No hay datos en la fila seleccionada.');
                console.error('Datos de fila vacíos:', data);
                return;
            }
            
            // Verificar si hay un ID de cita válido
            var idCit = data[idCitIndex];
            console.log('ID Cita extraído:', idCit, 'Tipo:', typeof idCit);
            
            if (!idCit || idCit === '' || idCit === null || idCit === undefined) {
                alert('Esta fila no contiene una cita válida. Por favor selecciona una fila que contenga datos de una cita.\n\nDebug: Fila ' + (row + 1) + ', ID index: ' + idCitIndex + ', Valor: ' + idCit);
                console.error('ID de cita no válido:', {
                    row: row,
                    idCitIndex: idCitIndex,
                    idCit: idCit,
                    data: data,
                    columnasConfig: columnasConfig
                });
                return;
            }
            
            var id_cit = idCit;
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: {
                    action: 'obtener_comentarios',
                    id_cit: id_cit
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        mostrarListaComentarios(response.data, data);
                    } else {
                        alert('Error al obtener comentarios: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error de conexión al obtener comentarios.');
                    console.error('Error:', error);
                }
            });
            
            console.log('=== FIN VER COMENTARIOS ===');
        }
        
        function mostrarListaComentarios(comentarios, dataCita) {
            var nomCitIndex = obtenerIndiceColumna('nom_cit');
            var nombreCita = dataCita[nomCitIndex] || 'Sin nombre';
            
            // Actualizar información de la cita
            $('#infoComentariosCita').html(`
                <div class="alert alert-info">
                    <strong>Cita:</strong> ${nombreCita}<br>
                    <strong>ID:</strong> ${dataCita[obtenerIndiceColumna('id_cit')]}
                </div>
            `);
            
            // Mostrar comentarios
            if (comentarios && comentarios.length > 0) {
                var html = '';
                comentarios.forEach(function(comentario) {
                    var fechaFormateada = new Date(comentario.fecha_com).toLocaleString();
                    var fechaEditada = comentario.fecha_edit_com ? 
                        ' (Editado: ' + new Date(comentario.fecha_edit_com).toLocaleString() + ')' : '';
                    
                    html += `
                        <div class="comentario-item">
                            <div class="comentario-header">
                                <div class="comentario-autor">${comentario.nom_eje}</div>
                                <div class="comentario-fecha">${fechaFormateada}${fechaEditada}</div>
                            </div>
                            <div class="comentario-contenido">${comentario.contenido_com}</div>
                            <div class="comentario-meta">
                                <small>Celda: Fila ${parseInt(comentario.fila_com) + 1}, Columna ${parseInt(comentario.columna_com) + 1}</small>
                            </div>
                        </div>
                    `;
                });
                
                $('#listaComentarios').html(html);
                $('#sinComentarios').hide();
            } else {
                $('#listaComentarios').html('');
                $('#sinComentarios').show();
            }
            
            // Mostrar modal
            $('#modalVerComentarios').modal('show');
        }
        
        function aplicarIndicadorComentario(fila, columna) {
            var celda = hot.getCell(fila, columna);
            if (celda && !celda.classList.contains('celda-con-comentario')) {
                celda.classList.add('celda-con-comentario');
            }
        }
        
        function obtenerNombreColumna(indiceColumna) {
            if (columnasConfig && columnasConfig[indiceColumna]) {
                return columnasConfig[indiceColumna].header || `Columna ${indiceColumna + 1}`;
            }
            return `Columna ${indiceColumna + 1}`;
        }
        
        function cargarIndicadoresComentarios() {
            // Cargar comentarios para todas las citas visibles y aplicar indicadores
            var filas = hot.getData();
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            filas.forEach(function(fila, indiceFila) {
                if (fila[idCitIndex]) {
                    $.ajax({
                        url: 'server/controlador_citas.php',
                        type: 'POST',
                        data: {
                            action: 'obtener_comentarios',
                            id_cit: fila[idCitIndex]
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data.length > 0) {
                                response.data.forEach(function(comentario) {
                                    aplicarIndicadorComentario(parseInt(comentario.fila_com), parseInt(comentario.columna_com));
                                });
                            }
                        }
                    });
                }
            });
        }
        
        function procesarComentarioWebSocket(mensaje) {
            if (!mensaje.id_cit || !mensaje.fila || !mensaje.columna) {
                return;
            }
            
            log('💬 Procesando comentario WebSocket: ' + JSON.stringify(mensaje));
            
            // Aplicar indicador visual
            aplicarIndicadorComentario(mensaje.fila, mensaje.columna);
            
            // Aplicar feedback visual
            var celda = hot.getCell(mensaje.fila, mensaje.columna);
            if (celda) {
                celda.classList.add('comentario-websocket-changed');
                setTimeout(function() {
                    celda.classList.remove('comentario-websocket-changed');
                }, 3000);
            }
            
            // Mostrar badge
            mostrarBadgeWebSocket('info', 'Comentario agregado por otro usuario');
        }
        
        // =====================================
        // FUNCIONES DE COLORES DE CELDAS
        // =====================================
        
        var colorSeleccionado = null;
        
        function cambiarColorCelda(selection) {
            console.log('=== CAMBIAR COLOR CELDA ===');
            console.log('Selection recibida:', selection);
            
            // Obtener la selección actual de Handsontable
            var selected = hot.getSelected();
            console.log('Selección actual:', selected);
            
            if (!selected || selected.length === 0) {
                alert('Por favor selecciona una celda para cambiar su color.');
                return;
            }
            
            var row = selected[0][0];
            var col = selected[0][1];
            var data = hot.getDataAtRow(row);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            console.log('Row:', row, 'Col:', col, 'Data:', data, 'ID Index:', idCitIndex);
            
            // Verificar si la columna id_cit existe
            if (idCitIndex === -1) {
                alert('Error: No se pudo encontrar la columna id_cit en la configuración de la tabla.');
                console.error('columnasConfig no contiene id_cit:', columnasConfig);
                return;
            }
            
            // Verificar si hay datos en la fila
            if (!data || data.length === 0) {
                alert('Error: No hay datos en la fila seleccionada.');
                console.error('Datos de fila vacíos:', data);
                return;
            }
            
            // Verificar si hay un ID de cita válido
            var idCit = data[idCitIndex];
            console.log('ID Cita extraído:', idCit, 'Tipo:', typeof idCit);
            
            if (!idCit || idCit === '' || idCit === null || idCit === undefined) {
                alert('Esta fila no contiene una cita válida. Por favor selecciona una fila que contenga datos de una cita.\n\nDebug: Fila ' + (row + 1) + ', ID index: ' + idCitIndex + ', Valor: ' + idCit);
                console.error('ID de cita no válido:', {
                    row: row,
                    col: col,
                    idCitIndex: idCitIndex,
                    idCit: idCit,
                    data: data,
                    columnasConfig: columnasConfig
                });
                return;
            }
            
            // Guardar información de la celda seleccionada
            colorSeleccionado = {
                id_cit: idCit,
                fila: row,
                columna: col,
                columnaNombre: obtenerNombreColumna(col)
            };
            
            console.log('Color seleccionado:', colorSeleccionado);
            
            // Obtener color actual de la celda
            var celdaActual = hot.getCell(row, col);
            var colorActual = {
                fondo: '#ffffff',
                texto: '#000000'
            };
            
            if (celdaActual) {
                var styles = window.getComputedStyle(celdaActual);
                colorActual.fondo = rgbToHex(styles.backgroundColor) || '#ffffff';
                colorActual.texto = rgbToHex(styles.color) || '#000000';
            }
            
            // Actualizar información en el modal
            $('#celdaColor').text(`Fila ${row + 1}, Columna: ${colorSeleccionado.columnaNombre}`);
            $('#colorFondo').val(colorActual.fondo);
            $('#colorTexto').val(colorActual.texto);
            
            // Configurar eventos para colores predefinidos
            $('.color-preset').off('click').on('click', function() {
                var fondo = $(this).data('fondo');
                var texto = $(this).data('texto');
                $('#colorFondo').val(fondo);
                $('#colorTexto').val(texto);
                
                // Marcar como seleccionado
                $('.color-preset').removeClass('selected');
                $(this).addClass('selected');
            });
            
            // Mostrar modal
            $('#modalColorCelda').modal('show');
            
            console.log('=== FIN CAMBIAR COLOR CELDA ===');
        }
        
        function quitarColorCelda(selection) {
            console.log('=== QUITAR COLOR CELDA ===');
            console.log('Selection recibida:', selection);
            
            // Obtener la selección actual de Handsontable
            var selected = hot.getSelected();
            console.log('Selección actual:', selected);
            
            if (!selected || selected.length === 0) {
                alert('Por favor selecciona una celda para quitar su color.');
                return;
            }
            
            var row = selected[0][0];
            var col = selected[0][1];
            var data = hot.getDataAtRow(row);
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            // Verificar si hay un ID de cita válido
            var idCit = data[idCitIndex];
            
            if (!idCit || idCit === '' || idCit === null || idCit === undefined) {
                alert('Esta fila no contiene una cita válida.');
                return;
            }
            
            // Confirmar eliminación
            if (confirm('¿Está seguro de que desea quitar el color de esta celda?')) {
                // Buscar y eliminar el color de la celda
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: {
                        action: 'obtener_colores',
                        id_cit: idCit
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Buscar el color de esta celda específica
                            var colorCelda = response.data.find(function(color) {
                                return color.fila_color == row && color.columna_color == col;
                            });
                            
                            if (colorCelda) {
                                // Eliminar el color
                                $.ajax({
                                    url: 'server/controlador_citas.php',
                                    type: 'POST',
                                    data: {
                                        action: 'eliminar_color',
                                        id_color: colorCelda.id_color
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.success) {
                                            // Quitar el color de la celda del almacenamiento
                                            var claveCelda = row + ',' + col;
                                            delete coloresCeldas[claveCelda];
                                            
                                            // Forzar re-renderizado de la celda
                                            hot.render();
                                            
                                            // Enviar por WebSocket
                                            enviarMensajeWebSocket('color_eliminado', {
                                                id_cit: idCit,
                                                columna: col,
                                                id_ejecutivo: miIdEjecutivo
                                            });
                                            
                                            mostrarBadgeWebSocket('success', 'Color eliminado correctamente');
                                        } else {
                                            alert('Error al eliminar color: ' + response.message);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        alert('Error de conexión al eliminar color: ' + error);
                                    }
                                });
                            } else {
                                alert('No se encontró un color personalizado para esta celda.');
                            }
                        } else {
                            alert('Error al obtener colores: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error de conexión al obtener colores: ' + error);
                    }
                });
            }
            
            console.log('=== FIN QUITAR COLOR CELDA ===');
        }
        
        function guardarColor() {
            if (!colorSeleccionado) {
                alert('No hay celda seleccionada para colorear.');
                return;
            }
            
            var colorFondo = $('#colorFondo').val();
            var colorTexto = $('#colorTexto').val();
            
            if (!colorFondo || !colorTexto) {
                alert('Por favor selecciona ambos colores (fondo y texto).');
                return;
            }
            
            console.log('Guardando color:', {
                celda: colorSeleccionado,
                fondo: colorFondo,
                texto: colorTexto
            });
            
            $.ajax({
                url: 'server/controlador_citas.php',
                type: 'POST',
                data: {
                    action: 'guardar_color',
                    id_cit: colorSeleccionado.id_cit,
                    fila: colorSeleccionado.fila,
                    columna: colorSeleccionado.columna,
                    color_fondo: colorFondo,
                    color_texto: colorTexto,
                    id_ejecutivo: miIdEjecutivo
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Aplicar el color a la celda
                        aplicarColorCelda(colorSeleccionado.fila, colorSeleccionado.columna, colorFondo, colorTexto);
                        
                        // Enviar por WebSocket
                        enviarMensajeWebSocket('color_cambiado', {
                            id_cit: colorSeleccionado.id_cit,
                            fila: colorSeleccionado.fila,
                            columna: colorSeleccionado.columna,
                            color_fondo: colorFondo,
                            color_texto: colorTexto,
                            id_ejecutivo: miIdEjecutivo
                        });
                        
                        $('#modalColorCelda').modal('hide');
                        mostrarBadgeWebSocket('success', 'Color guardado correctamente');
                    } else {
                        alert('Error al guardar color: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error de conexión al guardar color: ' + error);
                }
            });
        }
        
        function aplicarColorCelda(fila, columna, colorFondo, colorTexto) {
            var claveCelda = fila + ',' + columna;
            
            // Almacenar el color en memoria para el renderizado actual
            coloresCeldas[claveCelda] = {
                fondo: colorFondo,
                texto: colorTexto
            };
            
            // NO guardar en localStorage ya que ahora depende de la base de datos
            // guardarColoresEnCache();
            
            console.log('🎨 Color aplicado en memoria:', claveCelda, colorFondo, colorTexto);
            
            // Forzar re-renderizado de la celda específica
            setTimeout(function() {
                hot.render();
            }, 10);
        }
        
        function cargarColoresCeldas() {
            console.log('🎨 Cargando colores de celdas desde base de datos...');
            
            // Limpiar colores de celdas previos para recargar correctamente
            coloresCeldas = {};
            
            // Cargar colores para todas las citas visibles
            var data = hot.getData();
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            if (idCitIndex === -1) {
                console.log('⚠️ No se encontró columna id_cit');
                return;
            }
            
            var citasConColores = [];
            
            data.forEach(function(fila, filaIndex) {
                var idCit = fila[idCitIndex];
                if (idCit && idCit !== '' && citasConColores.indexOf(idCit) === -1) {
                    citasConColores.push(idCit);
                }
            });
            
            console.log('📋 Citas con posibles colores:', citasConColores);
            
            if (citasConColores.length === 0) {
                console.log('ℹ️ No hay citas para cargar colores');
                hot.render();
                return;
            }
            
            var coloresCargados = 0;
            var totalCitas = citasConColores.length;
            
            // Cargar colores para cada cita
            citasConColores.forEach(function(idCit) {
                $.ajax({
                    url: 'server/controlador_citas.php',
                    type: 'POST',
                    data: {
                        action: 'obtener_colores',
                        id_cit: idCit
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            response.data.forEach(function(color) {
                                // Encontrar la fila actual de esta cita en la tabla
                                var filaActual = encontrarFilaPorCita(idCit);
                                if (filaActual !== -1) {
                                    aplicarColorCelda(
                                        filaActual, 
                                        parseInt(color.columna_color), 
                                        color.color_fondo, 
                                        color.color_texto
                                    );
                                    console.log('✅ Color aplicado:', idCit, 'fila actual:', filaActual, 'columna:', color.columna_color, color.color_fondo);
                                }
                            });
                        }
                        
                        coloresCargados++;
                        if (coloresCargados === totalCitas) {
                            console.log('🎉 Todos los colores cargados exitosamente, forzando re-renderizado');
                            hot.render();
                            mostrarBadgeWebSocket('success', 'Colores de celdas restaurados desde base de datos');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Error al cargar colores para cita', idCit, ':', error);
                        coloresCargados++;
                        if (coloresCargados === totalCitas) {
                            console.log('🎉 Carga completada (con errores), forzando re-renderizado');
                            hot.render();
                        }
                    }
                });
            });
        }
        
        // Función auxiliar para encontrar la fila actual de una cita específica
        function encontrarFilaPorCita(idCit) {
            var data = hot.getData();
            var idCitIndex = obtenerIndiceColumna('id_cit');
            
            for (var i = 0; i < data.length; i++) {
                if (data[i][idCitIndex] == idCit) {
                    return i;
                }
            }
            return -1; // No encontrado
        }
        
        function procesarColorWebSocket(mensaje) {
            if (!mensaje.id_cit || !mensaje.columna) {
                return;
            }
            
            log('🎨 Procesando color WebSocket: ' + JSON.stringify(mensaje));
            
            if (mensaje.tipo === 'color_cambiado') {
                // Encontrar la fila actual de esta cita
                var filaActual = encontrarFilaPorCita(mensaje.id_cit);
                if (filaActual !== -1) {
                    // Aplicar el nuevo color en la posición actual
                    aplicarColorCelda(filaActual, mensaje.columna, mensaje.color_fondo, mensaje.color_texto);
                    
                    // Aplicar feedback visual
                    var celda = hot.getCell(filaActual, mensaje.columna);
                    if (celda) {
                        celda.classList.add('color-websocket-changed');
                        setTimeout(function() {
                            celda.classList.remove('color-websocket-changed');
                        }, 1000);
                    }
                    
                    mostrarBadgeWebSocket('info', 'Color cambiado por otro usuario');
                }
            } else if (mensaje.tipo === 'color_eliminado') {
                // Encontrar la fila actual de esta cita
                var filaActual = encontrarFilaPorCita(mensaje.id_cit);
                if (filaActual !== -1) {
                    // Quitar el color del almacenamiento
                    var claveCelda = filaActual + ',' + mensaje.columna;
                    delete coloresCeldas[claveCelda];
                    
                    // Forzar re-renderizado de la celda
                    hot.render();
                    
                    // Aplicar feedback visual
                    var celda = hot.getCell(filaActual, mensaje.columna);
                    if (celda) {
                        celda.classList.add('color-websocket-changed');
                        setTimeout(function() {
                            celda.classList.remove('color-websocket-changed');
                        }, 1000);
                    }
                    
                    mostrarBadgeWebSocket('info', 'Color eliminado por otro usuario');
                }
            }
        }
        
        function rgbToHex(rgb) {
            if (!rgb || rgb === 'transparent' || rgb === 'rgba(0, 0, 0, 0)') return '#ffffff';
            
            var result = rgb.match(/\d+/g);
            if (!result || result.length < 3) return '#ffffff';
            
            return '#' + 
                ('0' + parseInt(result[0]).toString(16)).slice(-2) + 
                ('0' + parseInt(result[1]).toString(16)).slice(-2) + 
                ('0' + parseInt(result[2]).toString(16)).slice(-2);
        }
    </script>
</body>
</html>
