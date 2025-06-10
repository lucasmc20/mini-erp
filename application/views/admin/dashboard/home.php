<!-- Cards de Estatísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Produtos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['total_produtos']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pedidos Este Mês
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['pedidos_mes']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Vendas Este Mês
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo formatar_moeda($estatisticas['vendas_mes']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Usuários Ativos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['usuarios_ativos']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="row">
    <!-- Vendas Recentes -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>
                    Vendas Recentes
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                       data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <div class="dropdown-header">Ações:</div>
                        <a class="dropdown-item" href="<?php echo base_url('pedidos'); ?>">Ver Todos</a>
                        <a class="dropdown-item" href="<?php echo base_url('relatorios/vendas'); ?>">Relatório</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($vendas_recentes)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($vendas_recentes as $venda): ?>
                                <tr>
                                    <td>#<?php echo $venda['id']; ?></td>
                                    <td><?php echo $venda['cliente']; ?></td>
                                    <td><?php echo formatar_moeda($venda['valor']); ?></td>
                                    <td><?php echo formatar_datetime_br($venda['data']); ?></td>
                                    <td><?php echo status_badge($venda['status']); ?></td>
                                    <td>
                                        <?php echo icone_acao(
                                            base_url('pedidos/ver/' . $venda['id']),
                                            'fas fa-eye',
                                            'Ver Detalhes'
                                        ); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <p>Nenhuma venda encontrada</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Produtos com Baixo Estoque -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Estoque Baixo
                </h6>
                <a href="<?php echo base_url('estoque'); ?>" class="btn btn-sm btn-outline-warning">
                    Ver Todos
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($produtos_baixo_estoque)): ?>
                    <?php foreach ($produtos_baixo_estoque as $produto): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Produto</div>
                                <div class="font-weight-bold"><?php echo $produto['nome']; ?></div>
                                <div class="small">
                                    <span class="text-danger">Atual: <?php echo $produto['estoque_atual']; ?></span>
                                    <span class="text-muted">/ Mín: <?php echo $produto['estoque_minimo']; ?></span>
                                </div>
                            </div>
                            <div>
                                <a href="<?php echo base_url('produtos/editar/' . $produto['id']); ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                        <p class="mb-0">Todos os produtos estão com estoque adequado!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Widget de Ações Rápidas -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>
                    Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <a href="<?php echo base_url('produtos/novo'); ?>" class="btn btn-montink btn-sm w-100">
                            <i class="fas fa-plus mb-1"></i><br>
                            <small>Novo Produto</small>
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?php echo base_url('pedidos/novo'); ?>" class="btn btn-outline-montink btn-sm w-100">
                            <i class="fas fa-shopping-cart mb-1"></i><br>
                            <small>Novo Pedido</small>
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?php echo base_url('cupons/novo'); ?>" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-tags mb-1"></i><br>
                            <small>Novo Cupom</small>
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?php echo base_url('usuarios/novo'); ?>" class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-user-plus mb-1"></i><br>
                            <small>Novo Usuário</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS específico para dashboard -->
<style>
    .border-left-primary {
        border-left: 4px solid var(--montink-primary) !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .text-xs {
        font-size: 0.7rem;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .text-gray-400 {
        color: #c8c9ca !important;
    }

    .text-gray-500 {
        color: #b7b9cc !important;
    }

    .text-gray-800 {
        color: #3a3b45 !important;
    }

    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }

    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }
</style>