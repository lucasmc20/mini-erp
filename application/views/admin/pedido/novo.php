<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Novo Pedido
                </h6>
                <a href="<?php echo base_url('pedidos'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="card-body">
                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
                <?php endif; ?>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="post" autocomplete="off" id="pedidoForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="usuario_id" class="form-label">Cliente *</label>
                            <select name="usuario_id" id="usuario_id" class="form-control" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo $usuario->id; ?>" <?php echo set_select('usuario_id', $usuario->id); ?>>
                                        <?php echo htmlspecialchars($usuario->nome, ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($usuario->email, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pendente" <?php echo set_select('status', 'pendente', true); ?>>Pendente</option>
                                <option value="confirmado" <?php echo set_select('status', 'confirmado'); ?>>Confirmado</option>
                                <option value="processando" <?php echo set_select('status', 'processando'); ?>>Processando</option>
                                <option value="enviado" <?php echo set_select('status', 'enviado'); ?>>Enviado</option>
                                <option value="entregue" <?php echo set_select('status', 'entregue'); ?>>Entregue</option>
                                <option value="cancelado" <?php echo set_select('status', 'cancelado'); ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cupom_id" class="form-label">Cupom</label>
                            <select name="cupom_id" id="cupom_id" class="form-control">
                                <option value="">Nenhum</option>
                                <?php foreach ($cupons as $cupom): ?>
                                    <option value="<?php echo $cupom->id; ?>" <?php echo set_select('cupom_id', $cupom->id); ?>>
                                        <?php echo htmlspecialchars($cupom->codigo, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Produtos do Pedido <span class="text-danger">*</span></label>
                            <div id="produtos-pedido">
                                <div class="row align-items-end mb-2 produto-item">
                                    <div class="col-md-6">
                                        <select name="produtos[0][produto_id]" class="form-control" required>
                                            <option value="">Selecione o produto</option>
                                            <?php foreach ($produtos as $produto): ?>
                                                <option value="<?php echo $produto->id; ?>">
                                                    <?php echo htmlspecialchars($produto->nome); ?> (<?php echo $produto->sku; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="produtos[0][quantidade]" class="form-control" min="1" placeholder="Qtd." value="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="produtos[0][preco_unitario]" class="form-control" min="0.01" step="0.01" placeholder="Valor Unitário" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-produto" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-produto">
                                <i class="fas fa-plus"></i> Adicionar Produto
                            </button>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="total" class="form-label">Valor Total (R$)</label>
                            <input type="number" name="total" id="total" class="form-control" step="0.01" min="0"
                                   value="<?php echo set_value('total'); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status_pagamento" class="form-label">Status Pagamento</label>
                            <select name="status_pagamento" id="status_pagamento" class="form-control">
                                <option value="">Não definido</option>
                                <option value="pendente" <?php echo set_select('status_pagamento', 'pendente'); ?>>Pendente</option>
                                <option value="pago" <?php echo set_select('status_pagamento', 'pago'); ?>>Pago</option>
                                <option value="cancelado" <?php echo set_select('status_pagamento', 'cancelado'); ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="data_pedido" class="form-label">Data do Pedido</label>
                            <input type="datetime-local" name="data_pedido" id="data_pedido" class="form-control"
                                   value="<?php echo set_value('data_pedido'); ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea name="observacoes" id="observacoes" class="form-control" rows="2"><?php echo set_value('observacoes'); ?></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> Salvar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let indexProduto = 1;
    document.getElementById('add-produto').onclick = function () {
        const wrapper = document.createElement('div');
        wrapper.className = 'row align-items-end mb-2 produto-item';
        wrapper.innerHTML = `
            <div class="col-md-6">
                <select name="produtos[${indexProduto}][produto_id]" class="form-control" required>
                    <option value="">Selecione o produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?php echo $produto->id; ?>">
                            <?php echo htmlspecialchars($produto->nome); ?> (<?php echo $produto->sku; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="produtos[${indexProduto}][quantidade]" class="form-control" min="1" placeholder="Qtd." value="1" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="produtos[${indexProduto}][preco_unitario]" class="form-control" min="0.01" step="0.01" placeholder="Valor Unitário" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm btn-remove-produto">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        document.getElementById('produtos-pedido').appendChild(wrapper);
        indexProduto++;
        atualizarBotoesRemover();
    };

    function atualizarBotoesRemover() {
        document.querySelectorAll('.btn-remove-produto').forEach(function(btn){
            btn.disabled = (document.querySelectorAll('.produto-item').length === 1);
            btn.onclick = function(){
                if (document.querySelectorAll('.produto-item').length > 1) {
                    btn.closest('.produto-item').remove();
                    atualizarBotoesRemover();
                }
            }
        });
    }
    atualizarBotoesRemover();
});
</script>

<style>
    .form-label { font-weight: 500; }
    .card .alert { margin-top: 10px; }
</style>
