<!-- Cards de Estatísticas de Pedidos -->
<div class="row mb-4">
    <?php if (!empty($estatisticas)): ?>
        <?php foreach ($estatisticas as $stat): ?>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card shadow h-100 py-2 border-left-info">
                    <div class="card-body p-2 text-center">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            <?php echo htmlspecialchars($stat->status, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo (int)$stat->total; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Filtros -->
<form method="get" class="mb-3">
    <div class="row align-items-end">
        <div class="col-md-2 mb-2">
            <label for="numero_pedido" class="form-label">Número</label>
            <input type="text" name="numero_pedido" id="numero_pedido" class="form-control"
                value="<?php echo htmlspecialchars($filtros['p.numero_pedido'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-3 mb-2">
            <label for="usuario_nome" class="form-label">Cliente</label>
            <input type="text" name="usuario_nome" id="usuario_nome" class="form-control"
                value="<?php echo htmlspecialchars($filtros['u.nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 mb-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">Todos</option>
                <option value="pendente" <?php echo (isset($filtros['p.status']) && $filtros['p.status'] == 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                <option value="confirmado" <?php echo (isset($filtros['p.status']) && $filtros['p.status'] == 'confirmado') ? 'selected' : ''; ?>>Confirmado</option>
                <option value="processando" <?php echo (isset($filtros['p.status']) && $filtros['p.status'] == 'processando') ? 'selected' : ''; ?>>Processando</option>
                <option value="enviado" <?php echo (isset($filtros['p.status']) && $filtros['p.status'] == 'enviado') ? 'selected' : ''; ?>>Enviado</option>
                <option value="entregue" <?php echo (isset($filtros['p.status']) && $filtros['p.status'] == 'entregue') ? 'selected' : ''; ?>>Entregue</option>
                <option value="cancelado" <?php echo (isset($filtros['p.status']) && $filtros['p.status'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label for="cupom_id" class="form-label">Cupom</label>
            <input type="text" name="cupom_id" id="cupom_id" class="form-control"
                value="<?php echo htmlspecialchars($filtros['p.cupom_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 mb-2">
            <button class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filtrar</button>
        </div>
    </div>
</form>

<!-- Tabela de pedidos -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-shopping-cart me-2"></i>
            Pedidos
        </h6>
        <a href="<?php echo base_url('pedido/novo'); ?>" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Novo Pedido
        </a>
    </div>
    <div class="card-body">
        <?php if (!empty($pedidos)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>E-mail</th>
                            <th>Status</th>
                            <th>Valor Total</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo (int)$pedido->id; ?></td>
                                <td>
                                    <span class="badge bg-dark"><?php echo htmlspecialchars($pedido->numero_pedido, ENT_QUOTES, 'UTF-8'); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($pedido->usuario_nome, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($pedido->usuario_email, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php
                                    $status_badges = [
                                        'pendente' => 'secondary',
                                        'confirmado' => 'primary',
                                        'processando' => 'info',
                                        'enviado' => 'warning',
                                        'entregue' => 'success',
                                        'cancelado' => 'danger'
                                    ];
                                    $cor = $status_badges[$pedido->status] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $cor; ?>">
                                        <?php echo ucfirst($pedido->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo isset($pedido->total) ? 'R$ ' . number_format($pedido->total, 2, ',', '.') : '-'; ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y H:i', strtotime($pedido->data_pedido)); ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('pedido/visualizar/' . $pedido->id); ?>"
                                        class="btn btn-sm btn-outline-primary" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo base_url('pedido/editar/' . $pedido->id); ?>"
                                        class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="excluirPedido(<?php echo $pedido->id; ?>, '<?php echo addslashes(htmlspecialchars($pedido->numero_pedido, ENT_QUOTES, 'UTF-8')); ?>')"
                                        title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <p>Nenhum pedido encontrado</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Paginação (se tiver) -->
<?php if (!empty($paginacao) && $paginacao['total_paginas'] > 1): ?>
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $paginacao['total_paginas']; $p++): ?>
                <li class="page-item <?php echo $p == $paginacao['pagina_atual'] ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo base_url('pedidos?page=' . $p); ?>"><?php echo $p; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<style>
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .text-gray-800 {
        color: #3a3b45 !important;
    }

    .badge.bg-secondary {
        background: #6c757d !important;
        color: #fff;
    }

    .badge.bg-primary {
        background: #007bff !important;
        color: #fff;
    }

    .badge.bg-info {
        background: #17a2b8 !important;
        color: #fff;
    }

    .badge.bg-warning {
        background: #ffc107 !important;
        color: #212529;
    }

    .badge.bg-success {
        background: #28a745 !important;
        color: #fff;
    }

    .badge.bg-danger {
        background: #dc3545 !important;
        color: #fff;
    }

    .badge.bg-dark {
        background: #343a40 !important;
        color: #fff;
    }
</style>

<script>
function showAlert(msg, type = 'success') {
    // Exemplo com SweetAlert2, mas pode adaptar se usa outro.
    if (typeof Swal !== "undefined") {
        Swal.fire({
            icon: type,
            title: msg,
            showConfirmButton: false,
            timer: 1400
        });
    } else {
        alert(msg);
    }
}

function excluirPedido(id, numero) {
    if (confirm(`Tem certeza que deseja excluir o pedido "${numero}"?\n\nEsta ação não pode ser desfeita.`)) {
        fetch('<?php echo base_url('pedido/excluir/'); ?>' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Erro: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('Erro ao excluir pedido. Tente novamente.', 'error');
        });
    }
}
</script>
