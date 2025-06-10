<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit me-2"></i>
                    Editar Pedido #<?php echo htmlspecialchars($pedido->numero_pedido); ?>
                </h6>
                <a href="<?php echo base_url('pedido/visualizar/' . $pedido->id); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-eye"></i> Visualizar
                </a>
            </div>
            <div class="card-body">
                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
                <?php endif; ?>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="usuario_id" class="form-label">Cliente *</label>
                            <select name="usuario_id" id="usuario_id" class="form-control" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo $usuario->id; ?>"
                                        <?php echo set_select('usuario_id', $usuario->id, $pedido->usuario_id == $usuario->id); ?>>
                                        <?php echo htmlspecialchars($usuario->nome, ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($usuario->email, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pendente" <?php echo set_select('status', 'pendente', $pedido->status == 'pendente'); ?>>Pendente</option>
                                <option value="confirmado" <?php echo set_select('status', 'confirmado', $pedido->status == 'confirmado'); ?>>Confirmado</option>
                                <option value="processando" <?php echo set_select('status', 'processando', $pedido->status == 'processando'); ?>>Processando</option>
                                <option value="enviado" <?php echo set_select('status', 'enviado', $pedido->status == 'enviado'); ?>>Enviado</option>
                                <option value="entregue" <?php echo set_select('status', 'entregue', $pedido->status == 'entregue'); ?>>Entregue</option>
                                <option value="cancelado" <?php echo set_select('status', 'cancelado', $pedido->status == 'cancelado'); ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cupom_id" class="form-label">Cupom</label>
                            <select name="cupom_id" id="cupom_id" class="form-control">
                                <option value="">Nenhum</option>
                                <?php foreach ($cupons as $cupom): ?>
                                    <option value="<?php echo $cupom->id; ?>"
                                        <?php echo set_select('cupom_id', $cupom->id, $pedido->cupom_id == $cupom->id); ?>>
                                        <?php echo htmlspecialchars($cupom->codigo, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="total" class="form-label">Valor Total (R$)</label>
                            <input type="number" name="total" id="total" class="form-control" step="0.01" min="0"
                                   value="<?php echo set_value('total', $pedido->total); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status_pagamento" class="form-label">Status Pagamento</label>
                            <select name="status_pagamento" id="status_pagamento" class="form-control">
                                <option value="">Não definido</option>
                                <option value="pendente" <?php echo set_select('status_pagamento', 'pendente', $pedido->status_pagamento == 'pendente'); ?>>Pendente</option>
                                <option value="pago" <?php echo set_select('status_pagamento', 'pago', $pedido->status_pagamento == 'pago'); ?>>Pago</option>
                                <option value="cancelado" <?php echo set_select('status_pagamento', 'cancelado', $pedido->status_pagamento == 'cancelado'); ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="data_pedido" class="form-label">Data do Pedido</label>
                            <input type="datetime-local" name="data_pedido" id="data_pedido" class="form-control"
                                   value="<?php echo set_value('data_pedido', date('Y-m-d\TH:i', strtotime($pedido->data_pedido))); ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea name="observacoes" id="observacoes" class="form-control" rows="2"><?php echo set_value('observacoes', $pedido->observacoes); ?></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
                
                <!-- Itens do Pedido (Exibição simples) -->
                <?php if (!empty($itens)): ?>
                    <hr>
                    <h6 class="font-weight-bold text-secondary mb-2">
                        <i class="fas fa-list-ul me-1"></i>Itens do Pedido
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produto</th>
                                    <th>SKU</th>
                                    <th>Qtd</th>
                                    <th>Unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($itens as $idx => $item): ?>
                                <tr>
                                    <td><?php echo $idx + 1; ?></td>
                                    <td><?php echo htmlspecialchars($item->produto_nome); ?></td>
                                    <td><?php echo htmlspecialchars($item->sku); ?></td>
                                    <td><?php echo (int)$item->quantidade; ?></td>
                                    <td>R$ <?php echo number_format($item->preco_unitario, 2, ',', '.'); ?></td>
                                    <td>R$ <?php echo number_format($item->preco_total, 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<style>
    .form-label { font-weight: 500; }
    .card .alert { margin-top: 10px; }
</style>
