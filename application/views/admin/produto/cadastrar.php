<!-- Header da Página -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-primary me-2"></i>
            Cadastrar Produto
        </h1>
        <p class="mb-0 text-gray-600">Adicione um novo produto ao seu catálogo</p>
    </div>
    <div>
        <a href="<?php echo base_url('produto'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Voltar à Lista
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

<!-- Formulário de Cadastro -->
<form method="POST" enctype="multipart/form-data" id="formCadastrarProduto">
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
                                   value="<?php echo set_value('nome'); ?>"
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
                                   value="<?php echo set_value('sku'); ?>"
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
                                  placeholder="Descreva as características do produto"><?php echo set_value('descricao'); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <select class="form-select <?php echo form_error('categoria_id') ? 'is-invalid' : ''; ?>"
                                        id="categoria_id"
                                        name="categoria_id"
                                        required>
                                    <option value="">Selecione uma categoria</option>
                                    <?php if(!empty($categorias)): ?>
                                        <?php foreach($categorias as $categoria): ?>
                                            <option value="<?php echo $categoria->id; ?>"
                                                <?php echo set_select('categoria_id', $categoria->id); ?>>
                                                <?php echo str_repeat('-- ', $categoria->nivel - 1) . htmlspecialchars($categoria->nome); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <a href="<?php echo base_url('categoria/cadastrar'); ?>" 
                                   class="btn btn-outline-secondary" 
                                   target="_blank" 
                                   title="Cadastrar nova categoria">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('categoria_id'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text"
                                   class="form-control"
                                   id="marca"
                                   name="marca"
                                   value="<?php echo set_value('marca'); ?>"
                                   placeholder="Nome da marca">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text"
                                   class="form-control"
                                   id="modelo"
                                   name="modelo"
                                   value="<?php echo set_value('modelo'); ?>"
                                   placeholder="Modelo do produto">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <input type="text"
                                   class="form-control"
                                   id="codigo_barras"
                                   name="codigo_barras"
                                   value="<?php echo set_value('codigo_barras'); ?>"
                                   placeholder="Código de barras">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="number"
                                   class="form-control"
                                   id="peso"
                                   name="peso"
                                   value="<?php echo set_value('peso'); ?>"
                                   step="0.001"
                                   min="0"
                                   placeholder="0,000">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dimensoes" class="form-label">Dimensões</label>
                        <input type="text"
                               class="form-control"
                               id="dimensoes"
                               name="dimensoes"
                               value="<?php echo set_value('dimensoes'); ?>"
                               placeholder="Ex: 10x20x5 cm">
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
                            <label for="preco" class="form-label">Preço de Venda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       class="form-control <?php echo form_error('preco') ? 'is-invalid' : ''; ?>"
                                       id="preco"
                                       name="preco"
                                       value="<?php echo set_value('preco'); ?>"
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
                                       class="form-control"
                                       id="preco_promocional"
                                       name="preco_promocional"
                                       value="<?php echo set_value('preco_promocional'); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0,00">
                            </div>
                            <small class="form-text text-muted">Deixe vazio se não houver promoção</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="preco_custo" class="form-label">Preço de Custo</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       class="form-control"
                                       id="preco_custo"
                                       name="preco_custo"
                                       value="<?php echo set_value('preco_custo'); ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0,00">
                            </div>
                            <small class="form-text text-muted">Usado para cálculo de margem</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controle de Estoque -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-boxes me-2"></i>
                        Controle de Estoque
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="controlar_estoque"
                                   name="controlar_estoque"
                                   value="1"
                                   onchange="toggleEstoque()"
                                   <?php echo set_checkbox('controlar_estoque', '1', TRUE); ?>>
                            <label class="form-check-label" for="controlar_estoque">
                                <strong>Controlar Estoque</strong>
                            </label>
                            <div class="form-text">Ativar controle de estoque para este produto</div>
                        </div>
                    </div>

                    <div id="campos_estoque" style="display: none;">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="quantidade_inicial" class="form-label">Quantidade Inicial <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control"
                                       id="quantidade_inicial"
                                       name="quantidade_inicial"
                                       value="<?php echo set_value('quantidade_inicial', '0'); ?>"
                                       min="0"
                                       placeholder="0">
                                <small class="form-text text-muted">Quantidade inicial no estoque</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quantidade_minima" class="form-label">Quantidade Mínima <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control"
                                       id="quantidade_minima"
                                       name="quantidade_minima"
                                       value="<?php echo set_value('quantidade_minima', '1'); ?>"
                                       min="0"
                                       placeholder="1">
                                <small class="form-text text-muted">Alerta quando atingir este valor</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quantidade_maxima" class="form-label">Quantidade Máxima</label>
                                <input type="number"
                                       class="form-control"
                                       id="quantidade_maxima"
                                       name="quantidade_maxima"
                                       value="<?php echo set_value('quantidade_maxima'); ?>"
                                       min="0"
                                       placeholder="1000">
                                <small class="form-text text-muted">Limite máximo de estoque</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="localizacao" class="form-label">Localização no Estoque</label>
                                <input type="text"
                                       class="form-control"
                                       id="localizacao"
                                       name="localizacao"
                                       value="<?php echo set_value('localizacao'); ?>"
                                       placeholder="Ex: Prateleira A1, Setor 2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fornecedor" class="form-label">Fornecedor</label>
                                <input type="text"
                                       class="form-control"
                                       id="fornecedor"
                                       name="fornecedor"
                                       value="<?php echo set_value('fornecedor'); ?>"
                                       placeholder="Nome do fornecedor">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="lote" class="form-label">Lote</label>
                                <input type="text"
                                       class="form-control"
                                       id="lote"
                                       name="lote"
                                       value="<?php echo set_value('lote'); ?>"
                                       placeholder="Número do lote">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="data_fabricacao" class="form-label">Data de Fabricação</label>
                                <input type="date"
                                       class="form-control"
                                       id="data_fabricacao"
                                       name="data_fabricacao"
                                       value="<?php echo set_value('data_fabricacao'); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="data_validade" class="form-label">Data de Validade</label>
                                <input type="date"
                                       class="form-control"
                                       id="data_validade"
                                       name="data_validade"
                                       value="<?php echo set_value('data_validade'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes_estoque" class="form-label">Observações do Estoque</label>
                            <textarea class="form-control"
                                      id="observacoes_estoque"
                                      name="observacoes_estoque"
                                      rows="3"
                                      placeholder="Observações sobre o estoque deste produto"><?php echo set_value('observacoes_estoque'); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="permite_venda_sem_estoque"
                                       name="permite_venda_sem_estoque"
                                       value="1"
                                       <?php echo set_checkbox('permite_venda_sem_estoque', '1'); ?>>
                                <label class="form-check-label" for="permite_venda_sem_estoque">
                                    Permitir venda mesmo sem estoque
                                </label>
                                <div class="form-text">Permite vender o produto mesmo quando o estoque estiver zerado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagens -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-images me-2"></i>
                        Imagens do Produto
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="imagem_principal" class="form-label">Imagem Principal</label>
                            <input type="file"
                                   class="form-control"
                                   id="imagem_principal"
                                   name="imagem_principal"
                                   accept="image/*"
                                   onchange="previewImagem(this, 'preview-principal')">
                            <small class="form-text text-muted">Formatos: JPG, PNG, GIF, WebP. Máximo: 2MB</small>
                            <div id="preview-principal" class="mt-2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="galeria" class="form-label">Galeria de Imagens</label>
                            <input type="file"
                                   class="form-control"
                                   id="galeria"
                                   name="galeria[]"
                                   accept="image/*"
                                   multiple
                                   onchange="previewGaleria(this)">
                            <small class="form-text text-muted">Selecione múltiplas imagens para a galeria</small>
                            <div id="preview-galeria" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Lateral -->
        <div class="col-lg-4">
            <!-- Configurações -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
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
                                   <?php echo set_checkbox('ativo', '1', TRUE); ?>>
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
                                   <?php echo set_checkbox('destaque', '1'); ?>>
                            <label class="form-check-label" for="destaque">
                                <strong>Produto em Destaque</strong>
                            </label>
                            <div class="form-text">Aparece na página inicial</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-search me-2"></i>
                        SEO e Marketing
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text"
                               class="form-control"
                               id="meta_title"
                               name="meta_title"
                               value="<?php echo set_value('meta_title'); ?>"
                               maxlength="200"
                               placeholder="Título SEO (deixe vazio para usar o nome)">
                        <small class="form-text text-muted">Máximo 200 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control"
                                  id="meta_description"
                                  name="meta_description"
                                  rows="3"
                                  maxlength="300"
                                  placeholder="Descrição SEO para mecanismos de busca"><?php echo set_value('meta_description'); ?></textarea>
                        <small class="form-text text-muted">Máximo 300 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text"
                               class="form-control"
                               id="tags"
                               name="tags"
                               value="<?php echo set_value('tags'); ?>"
                               placeholder="tag1, tag2, tag3">
                        <small class="form-text text-muted">Separar por vírgulas</small>
                    </div>
                </div>
            </div>

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
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="gerarSKU()">
                            <i class="fas fa-barcode me-2"></i>
                            Gerar SKU Automático
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limparFormulario()">
                            <i class="fas fa-eraser me-2"></i>
                            Limpar Formulário
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="preencherExemplo()">
                            <i class="fas fa-lightbulb me-2"></i>
                            Exemplo de Produto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dicas -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        Dicas
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Use nomes descritivos e únicos</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>SKU deve ser único no sistema</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Ative o controle de estoque para rastreamento</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Categorize corretamente seus produtos</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Imagens de boa qualidade vendem mais</small>
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
                    <button type="submit" class="btn btn-montink btn-lg">
                        <i class="fas fa-save me-2"></i>
                        Cadastrar Produto
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg ms-2" onclick="history.back()">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="continuarCadastro">
                    <label class="form-check-label" for="continuarCadastro">
                        Continuar cadastrando após salvar
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- CSS específico -->
<style>
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .text-gray-600 {
        color: #6c757d !important;
    }

    .text-gray-800 {
        color: #3a3b45 !important;
    }

    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.25em;
    }

    .preview-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 5px;
    }

    .galeria-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .galeria-item {
        position: relative;
        display: inline-block;
    }

    .btn-remove-image {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }

    .card-header h6 {
        color: inherit;
    }

    #campos_estoque {
        border: 2px dashed #ffc107;
        border-radius: 8px;
        padding: 20px;
        background-color: #fffbf0;
    }

    .gap-2 {
        gap: 0.5rem !important;
    }

    @media (max-width: 768px) {
        .btn-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<script src="<?php echo base_url('assets/js/produto-form.js'); ?>"></script>
