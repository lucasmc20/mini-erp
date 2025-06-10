<!-- Header da Página -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning me-2"></i>
            Editar Produto
        </h1>
        <p class="mb-0 text-gray-600">
            Editando: <strong><?php echo $produto->nome; ?></strong>
            <span class="badge bg-secondary ms-2">SKU: <?php echo $produto->sku; ?></span>
        </p>
    </div>
    <div class="btn-group" role="group">
        <a href="<?php echo base_url('produto'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Voltar à Lista
        </a>
        <a href="<?php echo base_url('produto/visualizar/' . $produto->id); ?>" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>
            Visualizar
        </a>
    </div>
</div>

<!-- Alertas -->
<?php if (isset($erro)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo $erro; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (validation_errors()): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Corrija os erros abaixo:</strong>
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Formulário de Edição -->
<form method="POST" enctype="multipart/form-data" id="formEditarProduto">
    <div class="row">
        <!-- Coluna Principal -->
        <div class="col-lg-8">
            <!-- Informações Básicas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações Básicas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nome" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?php echo form_error('nome') ? 'is-invalid' : ''; ?>"
                                   id="nome"
                                   name="nome"
                                   value="<?php echo set_value('nome', $produto->nome); ?>"
                                   placeholder="Digite o nome do produto"
                                   required>
                            <div class="invalid-feedback">
                                <?php echo form_error('nome'); ?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?php echo form_error('sku') ? 'is-invalid' : ''; ?>"
                                   id="sku"
                                   name="sku"
                                   value="<?php echo set_value('sku', $produto->sku); ?>"
                                   placeholder="Código único"
                                   required>
                            <div class="invalid-feedback">
                                <?php echo form_error('sku'); ?>
                            </div>
                            <small class="form-text text-muted">Código único de identificação</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control"
                                  id="descricao"
                                  name="descricao"
                                  rows="4"
                                  placeholder="Descreva as características do produto"><?php echo set_value('descricao', $produto->descricao); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                            <select class="form-control <?php echo form_error('categoria_id') ? 'is-invalid' : ''; ?>"
                                    id="categoria_id"
                                    name="categoria_id"
                                    required>
                                <option value="">Selecione uma categoria</option>
                                <?php if (!empty($categorias)): ?>
                                    <?php foreach($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria->id; ?>" 
                                                <?php echo set_select('categoria_id', $categoria->id, ($produto->categoria_id == $categoria->id)); ?>>
                                            <?php echo $categoria->nome; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo form_error('categoria_id'); ?>
                            </div>
                            <?php if (!empty($produto->categoria_nome)): ?>
                                <small class="form-text text-muted">
                                    Categoria atual: <strong><?php echo $produto->categoria_nome; ?></strong>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text"
                                   class="form-control <?php echo form_error('marca') ? 'is-invalid' : ''; ?>"
                                   id="marca"
                                   name="marca"
                                   value="<?php echo set_value('marca', $produto->marca); ?>"
                                   placeholder="Nome da marca">
                            <div class="invalid-feedback">
                                <?php echo form_error('marca'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text"
                                   class="form-control <?php echo form_error('modelo') ? 'is-invalid' : ''; ?>"
                                   id="modelo"
                                   name="modelo"
                                   value="<?php echo set_value('modelo', $produto->modelo); ?>"
                                   placeholder="Modelo do produto">
                            <div class="invalid-feedback">
                                <?php echo form_error('modelo'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <input type="text"
                                   class="form-control <?php echo form_error('codigo_barras') ? 'is-invalid' : ''; ?>"
                                   id="codigo_barras"
                                   name="codigo_barras"
                                   value="<?php echo set_value('codigo_barras', $produto->codigo_barras); ?>"
                                   placeholder="Código de barras do produto">
                            <div class="invalid-feedback">
                                <?php echo form_error('codigo_barras'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="number"
                                   class="form-control <?php echo form_error('peso') ? 'is-invalid' : ''; ?>"
                                   id="peso"
                                   name="peso"
                                   value="<?php echo set_value('peso', $produto->peso); ?>"
                                   step="0.001"
                                   min="0"
                                   placeholder="0,000">
                            <div class="invalid-feedback">
                                <?php echo form_error('peso'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dimensoes" class="form-label">Dimensões</label>
                            <input type="text"
                                   class="form-control <?php echo form_error('dimensoes') ? 'is-invalid' : ''; ?>"
                                   id="dimensoes"
                                   name="dimensoes"
                                   value="<?php echo set_value('dimensoes', $produto->dimensoes); ?>"
                                   placeholder="Ex: 10x20x5 cm">
                            <div class="invalid-feedback">
                                <?php echo form_error('dimensoes'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preços -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Preços
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="preco" class="form-label">Preço <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       class="form-control <?php echo form_error('preco') ? 'is-invalid' : ''; ?>"
                                       id="preco"
                                       name="preco"
                                       value="<?php echo set_value('preco', $produto->preco); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0,00"
                                       required>
                                <div class="invalid-feedback">
                                    <?php echo form_error('preco'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="preco_promocional" class="form-label">Preço Promocional</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       class="form-control <?php echo form_error('preco_promocional') ? 'is-invalid' : ''; ?>"
                                       id="preco_promocional"
                                       name="preco_promocional"
                                       value="<?php echo set_value('preco_promocional', $produto->preco_promocional); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0,00">
                                <div class="invalid-feedback">
                                    <?php echo form_error('preco_promocional'); ?>
                                </div>
                            </div>
                            <small class="form-text text-muted">Deixe vazio se não houver promoção</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="preco_custo" class="form-label">Preço de Custo</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       class="form-control <?php echo form_error('preco_custo') ? 'is-invalid' : ''; ?>"
                                       id="preco_custo"
                                       name="preco_custo"
                                       value="<?php echo set_value('preco_custo', $produto->preco_custo); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0,00">
                                <div class="invalid-feedback">
                                    <?php echo form_error('preco_custo'); ?>
                                </div>
                            </div>
                            <small class="form-text text-muted">Para controle interno</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controle de Estoque -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-boxes me-2"></i>
                        Controle de Estoque
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="controlar_estoque"
                                       name="controlar_estoque"
                                       value="1"
                                       <?php echo set_checkbox('controlar_estoque', '1', $produto->controlar_estoque); ?>
                                       onchange="toggleEstoque()">
                                <label class="form-check-label" for="controlar_estoque">
                                    <strong>Controlar Estoque</strong>
                                </label>
                                <div class="form-text">Ativar para controlar quantidades</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="permite_venda_sem_estoque"
                                       name="permite_venda_sem_estoque"
                                       value="1"
                                       <?php echo set_checkbox('permite_venda_sem_estoque', '1', $produto->permite_venda_sem_estoque); ?>>
                                <label class="form-check-label" for="permite_venda_sem_estoque">
                                    <strong>Permitir Venda Sem Estoque</strong>
                                </label>
                                <div class="form-text">Vender mesmo sem quantidade</div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos de Estoque (mostrar/ocultar baseado no checkbox) -->
                    <div id="campos-estoque" style="<?php echo $produto->controlar_estoque ? 'display: block;' : 'display: none;'; ?>">
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="quantidade_atual" class="form-label">Quantidade Atual</label>
                                <input type="number"
                                       class="form-control"
                                       id="quantidade_atual"
                                       value="<?php echo isset($produto->quantidade_atual) ? $produto->quantidade_atual : '0'; ?>"
                                       readonly>
                                <small class="form-text text-muted">Somente leitura</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="quantidade_minima" class="form-label">Quantidade Mínima</label>
                                <input type="number"
                                       class="form-control <?php echo form_error('quantidade_minima') ? 'is-invalid' : ''; ?>"
                                       id="quantidade_minima"
                                       name="quantidade_minima"
                                       value="<?php echo set_value('quantidade_minima', isset($produto->quantidade_minima) ? $produto->quantidade_minima : '1'); ?>"
                                       min="0"
                                       placeholder="1">
                                <div class="invalid-feedback">
                                    <?php echo form_error('quantidade_minima'); ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="quantidade_maxima" class="form-label">Quantidade Máxima</label>
                                <input type="number"
                                       class="form-control <?php echo form_error('quantidade_maxima') ? 'is-invalid' : ''; ?>"
                                       id="quantidade_maxima"
                                       name="quantidade_maxima"
                                       value="<?php echo set_value('quantidade_maxima', isset($produto->quantidade_maxima) ? $produto->quantidade_maxima : ''); ?>"
                                       min="0"
                                       placeholder="Opcional">
                                <div class="invalid-feedback">
                                    <?php echo form_error('quantidade_maxima'); ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text"
                                       class="form-control <?php echo form_error('localizacao') ? 'is-invalid' : ''; ?>"
                                       id="localizacao"
                                       name="localizacao"
                                       value="<?php echo set_value('localizacao', isset($produto->localizacao) ? $produto->localizacao : ''); ?>"
                                       placeholder="Ex: A1-B2">
                                <div class="invalid-feedback">
                                    <?php echo form_error('localizacao'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="lote" class="form-label">Lote</label>
                                <input type="text"
                                       class="form-control <?php echo form_error('lote') ? 'is-invalid' : ''; ?>"
                                       id="lote"
                                       name="lote"
                                       value="<?php echo set_value('lote', isset($produto->lote) ? $produto->lote : ''); ?>"
                                       placeholder="Código do lote">
                                <div class="invalid-feedback">
                                    <?php echo form_error('lote'); ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="data_fabricacao" class="form-label">Data de Fabricação</label>
                                <input type="date"
                                       class="form-control <?php echo form_error('data_fabricacao') ? 'is-invalid' : ''; ?>"
                                       id="data_fabricacao"
                                       name="data_fabricacao"
                                       value="<?php echo set_value('data_fabricacao', isset($produto->data_fabricacao) ? $produto->data_fabricacao : ''); ?>">
                                <div class="invalid-feedback">
                                    <?php echo form_error('data_fabricacao'); ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="data_validade" class="form-label">Data de Validade</label>
                                <input type="date"
                                       class="form-control <?php echo form_error('data_validade') ? 'is-invalid' : ''; ?>"
                                       id="data_validade"
                                       name="data_validade"
                                       value="<?php echo set_value('data_validade', isset($produto->data_validade) ? $produto->data_validade : ''); ?>">
                                <div class="invalid-feedback">
                                    <?php echo form_error('data_validade'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fornecedor" class="form-label">Fornecedor</label>
                                <input type="text"
                                       class="form-control <?php echo form_error('fornecedor') ? 'is-invalid' : ''; ?>"
                                       id="fornecedor"
                                       name="fornecedor"
                                       value="<?php echo set_value('fornecedor', isset($produto->fornecedor) ? $produto->fornecedor : ''); ?>"
                                       placeholder="Nome do fornecedor">
                                <div class="invalid-feedback">
                                    <?php echo form_error('fornecedor'); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="codigo_fornecedor" class="form-label">Código do Fornecedor</label>
                                <input type="text"
                                       class="form-control <?php echo form_error('codigo_fornecedor') ? 'is-invalid' : ''; ?>"
                                       id="codigo_fornecedor"
                                       name="codigo_fornecedor"
                                       value="<?php echo set_value('codigo_fornecedor', isset($produto->codigo_fornecedor) ? $produto->codigo_fornecedor : ''); ?>"
                                       placeholder="Código no fornecedor">
                                <div class="invalid-feedback">
                                    <?php echo form_error('codigo_fornecedor'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes_estoque" class="form-label">Observações do Estoque</label>
                            <textarea class="form-control <?php echo form_error('observacoes_estoque') ? 'is-invalid' : ''; ?>"
                                      id="observacoes_estoque"
                                      name="observacoes_estoque"
                                      rows="2"
                                      placeholder="Observações sobre o estoque..."><?php echo set_value('observacoes_estoque', isset($produto->observacoes_estoque) ? $produto->observacoes_estoque : ''); ?></textarea>
                            <div class="invalid-feedback">
                                <?php echo form_error('observacoes_estoque'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagens -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-images me-2"></i>
                        Imagens do Produto
                    </h6>
                    <small class="text-muted">Deixe vazio para manter as imagens atuais</small>
                </div>
                <div class="card-body">
                    <!-- Imagens Atuais -->
                    <?php
                    $galeria_atual = json_decode($produto->galeria_imagens, true) ?: [];
                    $tem_imagens = $produto->imagem_principal || !empty($galeria_atual);
                    ?>

                    <?php if ($tem_imagens): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Imagens Atuais:</h6>
                            <div class="row">
                                <?php if ($produto->imagem_principal): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="position-relative">
                                            <img src="<?php echo base_url('uploads/produtos/' . $produto->imagem_principal); ?>"
                                                 alt="Imagem Principal"
                                                 class="img-thumbnail w-100"
                                                 style="height: 120px; object-fit: cover;">
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-1">Principal</span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php foreach ($galeria_atual as $index => $imagem): ?>
                                    <div class="col-md-3 mb-3" id="galeria-item-<?php echo $index; ?>">
                                        <div class="position-relative">
                                            <img src="<?php echo base_url('uploads/produtos/' . $imagem); ?>"
                                                 alt="Galeria <?php echo $index + 1; ?>"
                                                 class="img-thumbnail w-100"
                                                 style="height: 120px; object-fit: cover;">
                                            <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                    onclick="removerImagemGaleria('<?php echo $imagem; ?>', <?php echo $index; ?>)"
                                                    title="Remover imagem">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?>

                    <!-- Upload de Novas Imagens -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="imagem_principal" class="form-label">Nova Imagem Principal</label>
                            <input type="file"
                                   class="form-control"
                                   id="imagem_principal"
                                   name="imagem_principal"
                                   accept="image/*"
                                   onchange="previewImagem(this, 'preview-principal')">
                            <small class="form-text text-muted">
                                Formatos: JPG, PNG, GIF, WEBP. Máximo: 2MB
                                <?php if (!$produto->imagem_principal): ?>
                                    <br><span class="text-warning">⚠️ Este produto não possui imagem principal</span>
                                <?php endif; ?>
                            </small>
                            <div id="preview-principal" class="mt-2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="galeria" class="form-label">Adicionar à Galeria</label>
                            <input type="file"
                                   class="form-control"
                                   id="galeria"
                                   name="galeria[]"
                                   accept="image/*"
                                   multiple
                                   onchange="previewGaleria(this)">
                            <small class="form-text text-muted">Selecione múltiplas imagens para adicionar</small>
                            <div id="preview-galeria" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO e Metadados -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-search me-2"></i>
                        SEO e Metadados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text"
                                   class="form-control <?php echo form_error('meta_title') ? 'is-invalid' : ''; ?>"
                                   id="meta_title"
                                   name="meta_title"
                                   value="<?php echo set_value('meta_title', $produto->meta_title); ?>"
                                   placeholder="Título para SEO"
                                   maxlength="60">
                            <div class="invalid-feedback">
                                <?php echo form_error('meta_title'); ?>
                            </div>
                            <small class="form-text text-muted">Máximo 60 caracteres</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text"
                                   class="form-control <?php echo form_error('tags') ? 'is-invalid' : ''; ?>"
                                   id="tags"
                                   name="tags"
                                   value="<?php echo set_value('tags', $produto->tags); ?>"
                                   placeholder="tag1, tag2, tag3">
                            <div class="invalid-feedback">
                                <?php echo form_error('tags'); ?>
                            </div>
                            <small class="form-text text-muted">Separadas por vírgula</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control <?php echo form_error('meta_description') ? 'is-invalid' : ''; ?>"
                                  id="meta_description"
                                  name="meta_description"
                                  rows="3"
                                  placeholder="Descrição para SEO"
                                  maxlength="160"><?php echo set_value('meta_description', $produto->meta_description); ?></textarea>
                        <div class="invalid-feedback">
                            <?php echo form_error('meta_description'); ?>
                        </div>
                        <small class="form-text text-muted">Máximo 160 caracteres</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Lateral -->
        <div class="col-lg-4">
            <!-- Configurações -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-cog me-2"></i>
                        Configurações
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="ativo"
                                   name="ativo"
                                   value="1"
                                <?php echo set_checkbox('ativo', '1', $produto->ativo); ?>>
                            <label class="form-check-label" for="ativo">
                                <strong>Produto Ativo</strong>
                            </label>
                            <div class="form-text">Produto visível na loja</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="destaque"
                                   name="destaque"
                                   value="1"
                                <?php echo set_checkbox('destaque', '1', $produto->destaque); ?>>
                            <label class="form-check-label" for="destaque">
                                <strong>Produto em Destaque</strong>
                            </label>
                            <div class="form-text">Aparece na página inicial</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Produto -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info me-2"></i>
                        Informações do Produto
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted small">ID:</td>
                            <td><code><?php echo $produto->id; ?></code></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Data de Cadastro:</td>
                            <td><small><?php echo date('d/m/Y H:i', strtotime($produto->data_cadastro)); ?></small></td>
                        </tr>
                        <?php if ($produto->data_atualizacao): ?>
                            <tr>
                                <td class="text-muted small">Última Atualização:</td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($produto->data_atualizacao)); ?></small></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-muted small">Status Atual:</td>
                            <td>
                                <?php if ($produto->ativo): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inativo</span>
                                <?php endif; ?>
                                <?php if ($produto->destaque): ?>
                                    <span class="badge bg-warning text-dark ms-1">Destaque</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if (isset($produto->quantidade_atual) && $produto->controlar_estoque): ?>
                            <tr>
                                <td class="text-muted small">Estoque Atual:</td>
                                <td>
                                    <span class="badge <?php echo ($produto->quantidade_atual <= ($produto->quantidade_minima ?? 0)) ? 'bg-danger' : 'bg-success'; ?>">
                                        <?php echo $produto->quantidade_atual; ?> unid.
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <?php if (isset($produto->views) || isset($produto->vendas_total)): ?>
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estatísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <?php if (isset($produto->views)): ?>
                        <div class="col-6">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Visualizações</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($produto->views); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($produto->vendas_total)): ?>
                        <div class="col-6">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Vendas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($produto->vendas_total); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ações Rápidas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-bolt me-2"></i>
                        Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="duplicarProduto()">
                            <i class="fas fa-copy me-2"></i>
                            Duplicar Produto
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="visualizarProduto()">
                            <i class="fas fa-eye me-2"></i>
                            Visualizar
                        </button>
                        <?php if ($produto->controlar_estoque): ?>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="ajustarEstoque()">
                            <i class="fas fa-boxes me-2"></i>
                            Ajustar Estoque
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetarFormulario()">
                            <i class="fas fa-undo me-2"></i>
                            Resetar Alterações
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dicas de Edição -->
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-lightbulb me-2"></i>
                        Dicas de Edição
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Altere apenas os campos necessários</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>SKU deve continuar único no sistema</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Novas imagens substituirão as atuais</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Use "Resetar" para desfazer alterações</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-exclamation text-warning me-2"></i>
                            <small>Salvamento automático a cada 30 segundos</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="card shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save me-2"></i>
                        Salvar Alterações
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg ms-2" onclick="history.back()">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-lg ms-2"
                            onclick="excluirProduto(<?php echo $produto->id; ?>, '<?php echo addslashes($produto->nome); ?>')">
                        <i class="fas fa-trash me-2"></i>
                        Excluir Produto
                    </button>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="continuarEditando">
                    <label class="form-check-label" for="continuarEditando">
                        Continuar editando após salvar
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="<?php echo base_url('assets/js/produto-edit.js'); ?>"></script>
<!-- Variables for JavaScript -->
<script>
    // Dados do produto para o JavaScript
    window.produtoData = {
        id: <?php echo $produto->id; ?>,
        nome: '<?php echo addslashes($produto->nome); ?>',
        sku: '<?php echo addslashes($produto->sku); ?>',
        descricao: '<?php echo addslashes($produto->descricao ?? ''); ?>',
        categoria_id: '<?php echo $produto->categoria_id; ?>',
        marca: '<?php echo addslashes($produto->marca ?? ''); ?>',
        modelo: '<?php echo addslashes($produto->modelo ?? ''); ?>',
        codigo_barras: '<?php echo addslashes($produto->codigo_barras ?? ''); ?>',
        dimensoes: '<?php echo addslashes($produto->dimensoes ?? ''); ?>',
        preco: '<?php echo $produto->preco; ?>',
        preco_promocional: '<?php echo $produto->preco_promocional ?? ''; ?>',
        preco_custo: '<?php echo $produto->preco_custo ?? ''; ?>',
        peso: '<?php echo $produto->peso ?? ''; ?>',
        ativo: <?php echo $produto->ativo ? 'true' : 'false'; ?>,
        destaque: <?php echo $produto->destaque ? 'true' : 'false'; ?>,
        controlar_estoque: <?php echo $produto->controlar_estoque ? 'true' : 'false'; ?>,
        permite_venda_sem_estoque: <?php echo $produto->permite_venda_sem_estoque ? 'true' : 'false'; ?>
    };

    // URLs para o JavaScript
    window.appUrls = {
        base: '<?php echo base_url(); ?>',
        produto: '<?php echo base_url('produto'); ?>',
        removerImagem: '<?php echo base_url('produto/remover_imagem_galeria'); ?>',
        excluir: '<?php echo base_url('produto/excluir/'); ?>',
        visualizar: '<?php echo base_url('produto/visualizar/' . $produto->id); ?>',
        duplicar: '<?php echo base_url('produto/duplicar/' . $produto->id); ?>'
    };
</script>