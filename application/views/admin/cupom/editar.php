<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-tags me-2"></i>
                    Editar Cupom de Desconto
                </h6>
                <a href="<?php echo base_url('cupom'); ?>" class="btn btn-outline-secondary btn-sm">
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
                <form method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código *</label>
                            <input type="text" name="codigo" id="codigo" class="form-control" maxlength="30" required
                                   value="<?php echo set_value('codigo', htmlspecialchars($cupom->codigo, ENT_QUOTES, 'UTF-8')); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome do Cupom *</label>
                            <input type="text" name="nome" id="nome" class="form-control" maxlength="100" required
                                   value="<?php echo set_value('nome', htmlspecialchars($cupom->nome, ENT_QUOTES, 'UTF-8')); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tipo_desconto" class="form-label">Tipo de Desconto *</label>
                            <select name="tipo_desconto" id="tipo_desconto" class="form-control" required>
                                <option value="percentual" <?php echo set_select('tipo_desconto', 'percentual', $cupom->tipo_desconto == 'percentual'); ?>>Percentual (%)</option>
                                <option value="valor" <?php echo set_select('tipo_desconto', 'valor', $cupom->tipo_desconto == 'valor'); ?>>Valor Fixo (R$)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="valor_desconto" class="form-label">Valor do Desconto *</label>
                            <input type="number" name="valor_desconto" id="valor_desconto" class="form-control" min="0" step="0.01" required
                                   value="<?php echo set_value('valor_desconto', $cupom->valor_desconto); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="valor_maximo_desconto" class="form-label">Máx. Desconto (opcional)</label>
                            <input type="number" name="valor_maximo_desconto" id="valor_maximo_desconto" class="form-control" min="0" step="0.01"
                                   value="<?php echo set_value('valor_maximo_desconto', $cupom->valor_maximo_desconto); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="valor_minimo_pedido" class="form-label">Valor Mínimo do Pedido (opcional)</label>
                            <input type="number" name="valor_minimo_pedido" id="valor_minimo_pedido" class="form-control" min="0" step="0.01"
                                   value="<?php echo set_value('valor_minimo_pedido', $cupom->valor_minimo_pedido); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="limite_uso_total" class="form-label">Limite Total de Uso</label>
                            <input type="number" name="limite_uso_total" id="limite_uso_total" class="form-control" min="0"
                                   value="<?php echo set_value('limite_uso_total', $cupom->limite_uso_total); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="limite_uso_usuario" class="form-label">Limite por Usuário</label>
                            <input type="number" name="limite_uso_usuario" id="limite_uso_usuario" class="form-control" min="0"
                                   value="<?php echo set_value('limite_uso_usuario', $cupom->limite_uso_usuario); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio" class="form-label">Data de Início *</label>
                            <input type="datetime-local" name="data_inicio" id="data_inicio" class="form-control" required
                                   value="<?php echo set_value('data_inicio', date('Y-m-d\TH:i', strtotime($cupom->data_inicio))); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim" class="form-label">Data de Fim *</label>
                            <input type="datetime-local" name="data_fim" id="data_fim" class="form-control" required
                                   value="<?php echo set_value('data_fim', date('Y-m-d\TH:i', strtotime($cupom->data_fim))); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="ativo" class="form-label">Status</label>
                            <select name="ativo" id="ativo" class="form-control">
                                <option value="1" <?php echo set_select('ativo', '1', $cupom->ativo == 1); ?>>Ativo</option>
                                <option value="0" <?php echo set_select('ativo', '0', $cupom->ativo == 0); ?>>Inativo</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="primeiro_pedido_apenas" class="form-label">Só Primeiro Pedido?</label>
                            <select name="primeiro_pedido_apenas" id="primeiro_pedido_apenas" class="form-control">
                                <option value="0" <?php echo set_select('primeiro_pedido_apenas', '0', $cupom->primeiro_pedido_apenas == 0); ?>>Não</option>
                                <option value="1" <?php echo set_select('primeiro_pedido_apenas', '1', $cupom->primeiro_pedido_apenas == 1); ?>>Sim</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="produtos_permitidos" class="form-label">Produtos Permitidos (IDs, separados por vírgula)</label>
                            <input type="text" name="produtos_permitidos" id="produtos_permitidos" class="form-control" placeholder="Ex: 1,2,3"
                                   value="<?php echo set_value('produtos_permitidos', $cupom->produtos_permitidos); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categorias_permitidas" class="form-label">Categorias Permitidas (IDs, separados por vírgula)</label>
                            <input type="text" name="categorias_permitidas" id="categorias_permitidas" class="form-control" placeholder="Ex: 5,7"
                                   value="<?php echo set_value('categorias_permitidas', $cupom->categorias_permitidas); ?>">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label { font-weight: 500; }
    .card .alert { margin-top: 10px; }
</style>
