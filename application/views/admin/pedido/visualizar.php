<?php
$subtotal = 0;
$total = 0;
?>

<div class="row mb-4">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Pedido #<?php echo htmlspecialchars($pedido->numero_pedido); ?>
                </h6>
                <a href="<?php echo base_url('pedido'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="card-body">

                <!-- Detalhes principais -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <div><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido->usuario_nome); ?></div>
                        <div><strong>Email:</strong> <?php echo htmlspecialchars($pedido->usuario_email); ?></div>
                        <div><strong>Status:</strong>
                            <?php
                            $badges = [
                                'pendente' => 'secondary',
                                'confirmado' => 'primary',
                                'processando' => 'info',
                                'enviado' => 'warning',
                                'entregue' => 'success',
                                'cancelado' => 'danger'
                            ];
                            $cor = $badges[$pedido->status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo $cor; ?>"><?php echo ucfirst($pedido->status); ?></span>
                        </div>
                        <div>
                            <strong>Pagamento:</strong>
                            <span class="badge bg-<?php echo ($pedido->status_pagamento == 'pago' ? 'success' : ($pedido->status_pagamento == 'cancelado' ? 'danger' : 'warning')); ?>">
                                <?php echo ucfirst($pedido->status_pagamento ?: 'Indefinido'); ?>
                            </span>
                        </div>
                        <?php if (!empty($pedido->cupom_codigo)): ?>
                            <div>
                                <strong>Cupom:</strong>
                                <span class="badge bg-dark"><?php echo htmlspecialchars($pedido->cupom_codigo); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($pedido->observacoes)): ?>
                            <div>
                                <strong>Observações:</strong>
                                <div class="text-muted"><?php echo nl2br(htmlspecialchars($pedido->observacoes)); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div><strong>Data do pedido:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido->data_pedido)); ?></div>
                        <?php if ($pedido->data_confirmacao): ?>
                            <div><strong>Confirmação:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido->data_confirmacao)); ?></div>
                        <?php endif; ?>
                        <?php if ($pedido->data_envio): ?>
                            <div><strong>Envio:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido->data_envio)); ?></div>
                        <?php endif; ?>
                        <?php if ($pedido->data_entrega): ?>
                            <div><strong>Entrega:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido->data_entrega)); ?></div>
                        <?php endif; ?>
                        <?php if ($pedido->data_cancelamento): ?>
                            <div><strong>Cancelamento:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido->data_cancelamento)); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>

                <!-- Itens do pedido -->
                <h6 class="font-weight-bold text-secondary mb-3">
                    <i class="fas fa-list-ul me-1"></i>Itens do Pedido
                </h6>
                <?php if (!empty($itens)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produto</th>
                                    <th>SKU</th>
                                    <th>Categoria</th>
                                    <th>Marca</th>
                                    <th>Qtd</th>
                                    <th>Unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($itens)):
                                    foreach ($itens as $idx => $item):
                                        $subtotal += $item->preco_total;
                                ?>
                                        <tr>
                                            <td><?php echo $idx + 1; ?></td>
                                            <td>
                                                <?php if (!empty($item->imagem_principal)): ?>
                                                    <img src="<?php echo base_url($item->imagem_principal); ?>" alt=""
                                                        style="max-height:32px;max-width:32px;object-fit:contain;"
                                                        class="me-2 rounded shadow-sm border" />
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($item->produto_nome); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($item->sku); ?></td>
                                            <td><?php echo isset($item->categoria_nome) ? htmlspecialchars($item->categoria_nome) : '-'; ?></td>
                                            <td><?php echo htmlspecialchars($item->marca); ?></td>
                                            <td><?php echo (int)$item->quantidade; ?></td>
                                            <td>R$ <?php echo number_format($item->preco_unitario, 2, ',', '.'); ?></td>
                                            <td>R$ <?php echo number_format($item->preco_total, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Nenhum item no pedido.</div>
                <?php endif; ?>

                <!-- Resumo financeiro -->
                <div class="row justify-content-end mt-4">
                    <div class="col-md-6">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Subtotal:</strong>
                                        <span>R$ <?php echo number_format($subtotal ?? 0, 2, ',', '.'); ?></span>
                                    </li>
                                    <?php if (!empty($pedido->valor_desconto) && $pedido->valor_desconto > 0): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Desconto:</strong>
                                            <span class="text-danger">- R$ <?php echo number_format($pedido->valor_desconto, 2, ',', '.'); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Total:</strong>
                                        <span class="fs-5 text-success">
                                            R$ <?php echo number_format(($subtotal - ($pedido->valor_desconto ?? 0)), 2, ',', '.'); ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        <a href="<?php echo base_url('pedido/editar/' . $pedido->id); ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Editar Pedido
                        </a>
                        <a href="<?php echo base_url('pedido/excluir/' . $pedido->id); ?>"
                            class="btn btn-outline-danger"
                            onclick="return confirm('Deseja realmente excluir este pedido?');">
                            <i class="fas fa-trash me-1"></i> Excluir Pedido
                        </a>
                    </div>
                    <div>
                        <!-- Implemente botão para impressão se quiser -->
                        <!-- <a href="#" class="btn btn-outline-secondary"><i class="fas fa-print me-1"></i> Imprimir</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card .list-group-item {
        background: none;
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