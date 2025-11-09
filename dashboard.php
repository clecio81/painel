<?php
session_start();
require_once('./api/controles/db.php');
require_once('./api/controles/dashboard.php');
require_once("menu.php");

$dadosAtivos = Dashboard();
$dadosTestes = testes();
$conteudos = conteudos();
?>

<style type="text/css">
/* Dashboard Futuristic Styles */
.dashboard-container {
    background: linear-gradient(135deg, var(--futuristic-bg) 0%, #0f1419 100%);
    min-height: calc(100vh - var(--header-height));
    padding: 2rem;
}

.section-header {
    background: linear-gradient(135deg, #00aeff 0%, #0099d4 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px 12px 0 0;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(0, 174, 255, 0.3);
    position: relative;
    overflow: hidden;
}

.section-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.futuristic-card {
    background: linear-gradient(145deg, #1a1d24 0%, #232730 100%);
    border: 1px solid var(--futuristic-border);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
    backdrop-filter: blur(10px);
}

.futuristic-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 174, 255, 0.2);
    border-color: var(--futuristic-accent);
}

.futuristic-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--futuristic-accent), #00c6ff, var(--futuristic-accent));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.futuristic-card:hover::before {
    opacity: 1;
}

.card-content {
    padding: 1.5rem;
    color: var(--futuristic-text);
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1rem;
    position: relative;
    overflow: hidden;
}

.metric-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: inherit;
    opacity: 0.1;
    border-radius: inherit;
}

.metric-value {
    font-size: 2.2rem;
    font-weight: 700;
    color: white;
    margin: 0.5rem 0;
    text-shadow: 0 2px 10px rgba(0, 174, 255, 0.3);
}

.metric-label {
    font-size: 0.9rem;
    color: var(--futuristic-text-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.metric-footer {
    background: rgba(0, 0, 0, 0.2);
    padding: 0.75rem 1.5rem;
    border-top: 1px solid var(--futuristic-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
}

.metric-total {
    color: var(--futuristic-text-muted);
}

.metric-currency {
    font-weight: 600;
    color: var(--futuristic-accent);
}

.data-table-container {
    background: var(--futuristic-sidebar-bg);
    border: 1px solid var(--futuristic-border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.table-header {
    background: linear-gradient(135deg, #232730 0%, #2a2d36 100%);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--futuristic-border);
}

.table-title {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
}

.scrollable-table {
    max-height: 320px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--futuristic-accent) transparent;
}

.scrollable-table::-webkit-scrollbar {
    width: 6px;
}

.scrollable-table::-webkit-scrollbar-thumb {
    background: var(--futuristic-accent);
    border-radius: 3px;
}

.futuristic-table {
    margin: 0;
    color: var(--futuristic-text);
}

.futuristic-table th {
    background: rgba(0, 174, 255, 0.1);
    color: var(--futuristic-accent);
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border: none;
    position: sticky;
    top: 0;
    z-index: 10;
}

.futuristic-table td {
    padding: 1rem 0.75rem;
    border: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    vertical-align: middle;
}

.futuristic-table tbody tr:hover {
    background: rgba(0, 174, 255, 0.05);
}

.action-btn {
    background: linear-gradient(135deg, var(--futuristic-accent) 0%, #0099d4 100%);
    border: none;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-btn:hover {
    background: linear-gradient(135deg, #00c6ff 0%, #00aeff 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0, 174, 255, 0.4);
}

.table-summary {
    background: rgba(0, 0, 0, 0.3);
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--futuristic-border);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--futuristic-text);
    font-weight: 600;
}

.summary-label {
    color: var(--futuristic-text-muted);
    font-size: 0.9rem;
}

.summary-value {
    color: var(--futuristic-accent);
    font-weight: 700;
}

.online-users-card {
    text-align: center;
    position: relative;
}

.online-status {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 20px;
    color: #ffc107;
    font-weight: 600;
    font-size: 0.9rem;
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Color variants for different metrics */
.metric-primary { background: linear-gradient(135deg, rgba(0, 174, 255, 0.2), rgba(0, 174, 255, 0.1)); }
.metric-success { background: linear-gradient(135deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1)); }
.metric-warning { background: linear-gradient(135deg, rgba(255, 193, 7, 0.2), rgba(255, 193, 7, 0.1)); }
.metric-info { background: linear-gradient(135deg, rgba(23, 162, 184, 0.2), rgba(23, 162, 184, 0.1)); }
.metric-danger { background: linear-gradient(135deg, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.1)); }

.icon-primary { color: #007bff; }
.icon-success { color: #28a745; }
.icon-warning { color: #ffc107; }
.icon-info { color: #17a2b8; }
.icon-danger { color: #dc3545; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .metric-value {
        font-size: 1.8rem;
    }
    
    .futuristic-table th,
    .futuristic-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
}
</style>

<div class="dashboard-container">
    <?php if ($_SESSION['nivel_admin'] == 1): ?>
    <!-- Content Information Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="section-header">
                <i class="fas fa-chart-bar me-2"></i>
                Informações De Conteúdos
            </h4>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-primary">
                        <i class="fas fa-tv icon-primary"></i>
                    </div>
                    <div class="metric-value"><?php echo $conteudos['TotalLiveStreams']; ?></div>
                    <div class="metric-label">Canais</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-success">
                        <i class="fas fa-film icon-success"></i>
                    </div>
                    <div class="metric-value"><?php echo $conteudos['TotalMovieStreams']; ?></div>
                    <div class="metric-label">Filmes</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-warning">
                        <i class="fas fa-clapperboard icon-warning"></i>
                    </div>
                    <div class="metric-value"><?php echo $conteudos['TotalSeries']; ?></div>
                    <div class="metric-label">Séries</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-info">
                        <i class="fas fa-photo-film icon-info"></i>
                    </div>
                    <div class="metric-value"><?php echo $conteudos['TotalEpisodes']; ?></div>
                    <div class="metric-label">Episódios</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
    
    <!-- Financial Information Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="section-header">
                <i class="fas fa-chart-line me-2"></i>
                Informações Financeiras
            </h4>
        </div>
        
        <!-- Online Users Cards -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="futuristic-card h-100 online-users-card">
                <div class="card-content">
                    <div class="metric-icon metric-primary mx-auto">
                        <i class="fas fa-eye icon-primary"></i>
                    </div>
                    <div class="metric-label mb-3">Usuários Online</div>
                    <div class="online-status pulse-animation">
                        0
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-6 mb-4">
            <div class="futuristic-card h-100 online-users-card">
                <div class="card-content">
                    <div class="metric-icon metric-warning mx-auto">
                        <i class="fas fa-eye icon-warning"></i>
                    </div>
                    <div class="metric-label mb-3">Testes Online</div>
                    <div class="online-status pulse-animation">
                        0
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Financial Metrics -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-primary">
                        <i class="fas fa-users icon-primary"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['Totaldeclientes']; ?></div>
                    <div class="metric-label">Total de Usuários</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency">R$ <?php echo $dadosAtivos['Totaldeclientes_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency">R$ <?php echo $dadosAtivos['Totaldeclientes_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-success">
                        <i class="fas fa-user-check icon-success"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['clientesAtivos']; ?></div>
                    <div class="metric-label">Clientes Ativos</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #28a745;">R$ <?php echo $dadosAtivos['clientesAtivos_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #28a745;">R$ <?php echo $dadosAtivos['clientesAtivos_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-danger">
                        <i class="fas fa-users-slash icon-danger"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['clientesvencidostotal']; ?></div>
                    <div class="metric-label">Total Vencidos</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #dc3545;">R$ <?php echo $dadosAtivos['clientesvencidostotal_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #dc3545;">R$ <?php echo $dadosAtivos['clientesvencidostotal_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-success">
                        <i class="fas fa-sync-alt icon-success"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['clientesrenovados']; ?></div>
                    <div class="metric-label">Renovados</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #28a745;">R$ <?php echo $dadosAtivos['clientesrenovados_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #28a745;">R$ <?php echo $dadosAtivos['clientesrenovados_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-warning">
                        <i class="fas fa-clock icon-warning"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['clientesarenovar']; ?></div>
                    <div class="metric-label">A Renovar</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency">R$ <?php echo $dadosAtivos['clientesarenovar_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency">R$ <?php echo $dadosAtivos['clientesarenovar_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-info">
                        <i class="fas fa-user-plus icon-info"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['clientesnovos']; ?></div>
                    <div class="metric-label">Novos Usuários</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #17a2b8;">R$ <?php echo $dadosAtivos['clientesnovos_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #17a2b8;">R$ <?php echo $dadosAtivos['clientesnovos_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-danger">
                        <i class="fas fa-user-times icon-danger"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosAtivos['clientesvencidos_este_mes']; ?></div>
                    <div class="metric-label">Vencidos Este Mês</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #dc3545;">R$ <?php echo $dadosAtivos['clientesvencidos_este_mes_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #dc3545;">R$ <?php echo $dadosAtivos['clientesvencidos_este_mes_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-warning">
                        <i class="fas fa-vial icon-warning"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosTestes['Totaldetestes']; ?></div>
                    <div class="metric-label">Total de Testes</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency">R$ <?php echo $dadosTestes['Totaldetestes_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency">R$ <?php echo $dadosTestes['Totaldetestes_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-success">
                        <i class="fas fa-flask icon-success"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosTestes['TestesAtivos']; ?></div>
                    <div class="metric-label">Testes Ativos</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #28a745;">R$ <?php echo $dadosTestes['TestesAtivos_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #28a745;">R$ <?php echo $dadosTestes['TestesAtivos_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="futuristic-card h-100">
                <div class="card-content">
                    <div class="metric-icon metric-danger">
                        <i class="fas fa-ban icon-danger"></i>
                    </div>
                    <div class="metric-value"><?php echo $dadosTestes['Testesvencidostotal']; ?></div>
                    <div class="metric-label">Testes Vencidos</div>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">Total</span>
                    <span class="metric-currency" style="color: #dc3545;">R$ <?php echo $dadosTestes['Testesvencidostotal_valor']; ?></span>
                </div>
                <div class="metric-footer">
                    <span class="metric-total">P/unitário</span>
                    <span class="metric-currency" style="color: #dc3545;">R$ <?php echo $dadosTestes['Testesvencidostotal_valor_unidade']; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Tables Section -->
    <div class="row">
        <!-- Vencimento Hoje -->
        <div class="col-lg-6 mb-4">
            <div class="data-table-container">
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vencimento Hoje
                    </h5>
                </div>
                <div class="scrollable-table">
                    <table class="table futuristic-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Ações</th>
                                <th style="width: 10%;">ID</th>
                                <th>Usuário</th>
                                <th style="width: 20%;">Vencimento</th>
                                <th style="width: 15%;">Lucro</th>
                                <th style="width: 15%;">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dadosAtivos['clientesvencidos_amanha_lista'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Não há clientes com vencimento amanhã
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($dadosAtivos['clientesvencidos_amanha_lista'] as $cliente): ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="action-btn" 
                                                    onclick='modal_master("api/clientes.php", "renovar_cliente", "<?php echo $cliente['id']; ?>", "usuario", "<?php echo $cliente['usuario']; ?>")'>
                                                <i class="fas fa-sync-alt me-1"></i>Renovar
                                            </button>
                                        </td>
                                        <td><?php echo $cliente['id']; ?></td>
                                        <td><?php echo $cliente['usuario']; ?></td>
                                        <td><?php echo $cliente['data']; ?></td>
                                        <td>R$ <?php echo $cliente['lucro']; ?></td>
                                        <td>R$ <?php echo $cliente['total']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-summary">
                    <div class="summary-row">
                        <span class="summary-label">Total Lucro:</span>
                        <span class="summary-value">R$ <?php echo $dadosAtivos['clientesvencidos_amanha_lucro']; ?></span>
                    </div>
                    <div class="summary-row mt-2">
                        <span class="summary-label">Faturamento:</span>
                        <span class="summary-value">R$ <?php echo $dadosAtivos['clientesvencidos_amanha_valor_total']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Próximos 7 Dias -->
        <div class="col-lg-6 mb-4">
            <div class="data-table-container">
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="fas fa-calendar-week me-2"></i>
                        Próximos 7 Dias
                    </h5>
                </div>
                <div class="scrollable-table">
                    <table class="table futuristic-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Ações</th>
                                <th style="width: 10%;">ID</th>
                                <th>Usuário</th>
                                <th style="width: 20%;">Vencimento</th>
                                <th style="width: 15%;">Lucro</th>
                                <th style="width: 15%;">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dadosAtivos['clientesvencidos_proximos'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Não há clientes com vencimento nos próximos 7 dias
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($dadosAtivos['clientesvencidos_proximos'] as $cliente): ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="action-btn" 
                                                    onclick='modal_master("api/clientes.php", "renovar_cliente", "<?php echo $cliente['id']; ?>", "usuario", "<?php echo $cliente['usuario']; ?>")'>
                                                <i class="fas fa-sync-alt me-1"></i>Renovar
                                            </button>
                                        </td>
                                        <td><?php echo $cliente['id']; ?></td>
                                        <td><?php echo $cliente['usuario']; ?></td>
                                        <td><?php echo $cliente['data']; ?></td>
                                        <td>R$ <?php echo $cliente['lucro']; ?></td>
                                        <td>R$ <?php echo $cliente['total']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-summary">
                    <div class="summary-row">
                        <span class="summary-label">Total Lucro:</span>
                        <span class="summary-value">R$ <?php echo $dadosAtivos['clientesvencidos_proximos_lucro']; ?></span>
                    </div>
                    <div class="summary-row mt-2">
                        <span class="summary-label">Faturamento:</span>
                        <span class="summary-value">R$ <?php echo $dadosAtivos['clientesvencidos_proximos_valor_total']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vencidos Este Mês -->
        <div class="col-lg-6 mb-4">
            <div class="data-table-container">
                <div class="table-header">
                    <h5 class="table-title text-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        Vencidos Este Mês
                    </h5>
                </div>
                <div class="scrollable-table">
                    <table class="table futuristic-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Ações</th>
                                <th style="width: 10%;">ID</th>
                                <th>Usuário</th>
                                <th style="width: 20%;">Vencimento</th>
                                <th style="width: 15%;">Lucro</th>
                                <th style="width: 15%;">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dadosAtivos['clientesvencidos_este_mes_lista'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Não há clientes vencidos este mês
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($dadosAtivos['clientesvencidos_este_mes_lista'] as $cliente): ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="action-btn" 
                                                    onclick='modal_master("api/clientes.php", "renovar_cliente", "<?php echo $cliente['id']; ?>", "usuario", "<?php echo $cliente['usuario']; ?>")'>
                                                <i class="fas fa-sync-alt me-1"></i>Renovar
                                            </button>
                                        </td>
                                        <td><?php echo $cliente['id']; ?></td>
                                        <td><?php echo $cliente['usuario']; ?></td>
                                        <td><?php echo $cliente['data']; ?></td>
                                        <td>R$ <?php echo $cliente['lucro']; ?></td>
                                        <td>R$ <?php echo $cliente['total']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Renovados Este Mês -->
        <div class="col-lg-6 mb-4">
            <div class="data-table-container">
                <div class="table-header">
                    <h5 class="table-title text-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Renovados Este Mês
                    </h5>
                </div>
                <div class="scrollable-table">
                    <table class="table futuristic-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th>Usuário</th>
                                <th style="width: 20%;">Vencimento</th>
                                <th style="width: 20%;">Lucro</th>
                                <th style="width: 20%;">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dadosAtivos['clientesrenovados_lista'])): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Não há clientes renovados este mês
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($dadosAtivos['clientesrenovados_lista'] as $cliente): ?>
                                    <tr>
                                        <td><?php echo $cliente['id']; ?></td>
                                        <td><?php echo $cliente['usuario']; ?></td>
                                        <td><?php echo $cliente['data']; ?></td>
                                        <td>R$ <?php echo $cliente['lucro']; ?></td>
                                        <td>R$ <?php echo $cliente['total']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-summary">
                    <div class="summary-row">
                        <span class="summary-label">Total Lucro:</span>
                        <span class="summary-value">R$ <?php echo $dadosAtivos['clientesrenovados_lista_valor']; ?></span>
                    </div>
                    <div class="summary-row mt-2">
                        <span class="summary-label">Faturamento:</span>
                        <span class="summary-value">R$ <?php echo $dadosAtivos['clientesrenovados_lista_valor_total']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Master -->
<div class="modal fade" id="modal_master" tabindex="-1" aria-labelledby="modal_master" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background: var(--futuristic-sidebar-bg); border: 1px solid var(--futuristic-border); border-radius: 12px;">
            <div class="modal-header" id="modal_master-header" style="background: var(--futuristic-header-bg); border-bottom: 1px solid var(--futuristic-border);">
                <h5 class="modal-title" id="modal_master-titulo" style="color: var(--futuristic-text);"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="modal_master_form" onsubmit="event.preventDefault();" autocomplete="off">
                <div id="modal_master-body" class="modal-body overflow-auto" style="max-height: 421px; color: var(--futuristic-text);"></div>
                <div id="modal_master-footer" class="modal-footer" style="border-top: 1px solid var(--futuristic-border);"></div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="mt-5">
    <hr class="border-secondary mb-4">
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="col-md-12 text-center mb-2">
                <small style="color: var(--futuristic-text-muted);">
                    © <?php echo date("Y"); ?> Modificado por 
                    <span class="text-primary font-weight-bold">Cyber Player</span>
                </small>
            </div>
            <div class="col-md-12 text-center mb-3">
                <a class="btn btn-sm btn-outline-primary m-2" target="_blank" style="border-radius: 20px;">
                    <i class="fab fa-telegram-plane"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
// Add some futuristic interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.futuristic-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add click animation to action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    // Add loading animation for online status
    const onlineStatus = document.querySelectorAll('.online-status');
    onlineStatus.forEach(status => {
        status.addEventListener('click', function() {
            const originalText = this.textContent;
            this.textContent = 'Verificando...';
            this.style.opacity = '0.7';
            
            setTimeout(() => {
                this.textContent = originalText;
                this.style.opacity = '1';
            }, 2000);
        });
    });
    
    // Animate metric values on page load
    const metricValues = document.querySelectorAll('.metric-value');
    metricValues.forEach(value => {
        const finalValue = parseInt(value.textContent);
        if (!isNaN(finalValue)) {
            let currentValue = 0;
            const increment = finalValue / 50;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(timer);
                }
                value.textContent = Math.floor(currentValue);
            }, 30);
        }
    });
    
    // Add real-time clock to header if needed
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR');
        const clockElement = document.getElementById('real-time-clock');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }
    
    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Initial call
    
    // Add particles effect to background (optional)
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.position = 'fixed';
        particle.style.width = '2px';
        particle.style.height = '2px';
        particle.style.background = 'var(--futuristic-accent)';
        particle.style.borderRadius = '50%';
        particle.style.opacity = '0.3';
        particle.style.pointerEvents = 'none';
        particle.style.zIndex = '1';
        particle.style.left = Math.random() * window.innerWidth + 'px';
        particle.style.top = '-10px';
        document.body.appendChild(particle);
        
        let position = -10;
        const speed = Math.random() * 2 + 1;
        
        function animateParticle() {
            position += speed;
            particle.style.top = position + 'px';
            
            if (position > window.innerHeight) {
                document.body.removeChild(particle);
            } else {
                requestAnimationFrame(animateParticle);
            }
        }
        
        animateParticle();
    }
    
    // Create particles occasionally (uncomment to enable)
    // setInterval(createParticle, 5000);
});

// Function to refresh dashboard data via AJAX (optional)
function refreshDashboard() {
    // This can be used to refresh dashboard data without page reload
    const refreshBtn = document.getElementById('refresh-dashboard');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Atualizando...';
            this.disabled = true;
            
            // Simulate AJAX call
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-sync-alt"></i> Atualizar';
                this.disabled = false;
                // Here you would typically make an AJAX call to refresh data
                location.reload(); // For now, just reload the page
            }, 2000);
        });
    }
}

// Initialize refresh functionality
refreshDashboard();

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + R to refresh dashboard
    if (e.ctrlKey && e.key === 'r') {
        e.preventDefault();
        const refreshBtn = document.getElementById('refresh-dashboard');
        if (refreshBtn) {
            refreshBtn.click();
        }
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal.show');
        if (activeModal) {
            const modal = bootstrap.Modal.getInstance(activeModal);
            if (modal) {
                modal.hide();
            }
        }
    }
});

// Add smooth scrolling for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

</body>
</html>