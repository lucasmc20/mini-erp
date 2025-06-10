<!-- Cards de Estatísticas de Cupons -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Cupons
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['total_cupons'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
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
                            Cupons Ativos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['cupons_ativos'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Cupons Utilizados
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['cupons_utilizados'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
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
                            Desconto Concedido
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo formatar_moeda($estatisticas['total_desconto_concedido'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-coins fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="row">
    <!-- Lista de Cupons -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-tags me-2"></i>
                    Cupons de Desconto
                </h6>
                <a href="<?php echo base_url('cupom/novo'); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Novo Cupom
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($cupons)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Desconto</th>
                                    <th>Validade</th>
                                    <th>Status</th>
                                    <th>Usos</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cupons as $cupom): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cupom->codigo, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($cupom->nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <?php echo $cupom->tipo_desconto == 'percentual' ? 'Percentual' : 'Valor Fixo'; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($cupom->tipo_desconto == 'percentual') {
                                                echo $cupom->valor_desconto . '%';
                                            } else {
                                                echo formatar_moeda($cupom->valor_desconto);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($cupom->data_inicio)); ?>
                                            -
                                            <?php echo date('d/m/Y', strtotime($cupom->data_fim)); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($cupom->ativo) {
                                                echo '<span class="badge bg-success">Ativo</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Inativo</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo (int)$cupom->total_usado; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('cupom/editar/' . $cupom->id); ?>"
                                                class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo base_url('cupom/excluir/' . $cupom->id); ?>"
                                                class="btn btn-sm btn-outline-danger" title="Excluir"
                                                onclick="return confirm('Deseja realmente excluir este cupom?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <a href="<?php echo base_url('cupom/toggle/' . $cupom->id); ?>"
                                                class="btn btn-sm btn-outline-warning" title="Ativar/Inativar">
                                                <i class="fas fa-power-off"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-tags fa-3x mb-3"></i>
                        <p>Nenhum cupom cadastrado</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas e Widget -->
    <div class="col-xl-4 col-lg-5">
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
                        <a href="<?php echo base_url('cupom/novo'); ?>" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-plus mb-1"></i><br>
                            <small>Novo Cupom</small>
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?php echo base_url('cupom'); ?>" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-tags mb-1"></i><br>
                            <small>Todos Cupons</small>
                        </a>
                    </div>
                    
                    <div class="col-6 mb-3">
                        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-outline-dark btn-sm w-100">
                            <i class="fas fa-arrow-left mb-1"></i><br>
                            <small>Voltar</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS igual ao do dashboard (mantendo o padrão visual) -->
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

    .text-xs {
        font-size: 0.7rem;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .text-gray-800 {
        color: #3a3b45 !important;
    }

    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }

    .no-gutters>.col,
    .no-gutters>[class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }

    .badge.bg-success {
        background: #28a745 !important;
        color: #fff;
    }

    .badge.bg-secondary {
        background: #6c757d !important;
        color: #fff;
    }
</style>