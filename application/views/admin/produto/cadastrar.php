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
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text"
                                   class="form-control"
                                   id="categoria"
                                   name="categoria"
                                   value="<?php echo set_value('categoria'); ?>"
                                   placeholder="Ex: Eletrônicos, Roupas..."
                                   list="listaCategorias">
                            <datalist id="listaCategorias">
                                <?php foreach($categorias as $categoria): ?>
                                <option value="<?php echo $categoria; ?>">
                                    <?php endforeach; ?>
                            </datalist>
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
                        <div class="col-md-6 mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <input type="text"
                                   class="form-control"
                                   id="codigo_barras"
                                   name="codigo_barras"
                                   value="<?php echo set_value('codigo_barras'); ?>"
                                   placeholder="Código de barras do produto">
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
                        <div class="col-md-6 mb-3">
                            <label for="preco" class="form-label">Preço <span class="text-danger">*</span></label>
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
                        <div class="col-md-6 mb-3">
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
                    </div>

                    <div class="col-md-6 mb-3">
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
                            <small class="form-text text-muted">Formatos: JPG, PNG, GIF. Máximo: 2MB</small>
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
                            <small class="form-text text-muted">Selecione múltiplas imagens</small>
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
                            <small>Imagens de boa qualidade vendem mais</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Categorize corretamente seus produtos</small>
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

    @media (max-width: 768px) {
        .btn-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    }
</style>

<!-- JavaScript específico -->
<script>
    function previewImagem(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-image';
                container.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGaleria(input) {
        const container = document.getElementById('preview-galeria');
        container.innerHTML = '';
        container.className = 'galeria-preview';

        if (input.files) {
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'galeria-item';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn-remove-image';
                    btn.innerHTML = '×';
                    btn.onclick = function() {
                        div.remove();
                        // Remove file from input (requires recreating file list)
                    };

                    div.appendChild(img);
                    div.appendChild(btn);
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function gerarSKU() {
        const nome = document.getElementById('nome').value;
        const categoria = document.getElementById('categoria').value;

        if (!nome) {
            alert('Digite o nome do produto primeiro');
            return;
        }

        // Gerar SKU baseado no nome e categoria
        let sku = '';
        if (categoria) {
            sku += categoria.substring(0, 3).toUpperCase();
        }
        sku += nome.substring(0, 5).toUpperCase().replace(/[^A-Z0-9]/g, '');
        sku += Math.floor(Math.random() * 1000).toString().padStart(3, '0');

        document.getElementById('sku').value = sku;
    }

    function limparFormulario() {
        if (confirm('Tem certeza que deseja limpar todos os campos?')) {
            document.getElementById('formCadastrarProduto').reset();
            document.getElementById('preview-principal').innerHTML = '';
            document.getElementById('preview-galeria').innerHTML = '';
        }
    }

    function preencherExemplo() {
        document.getElementById('nome').value = 'Smartphone XYZ Pro 128GB';
        document.getElementById('sku').value = 'ELE-SMART-001';
        document.getElementById('descricao').value = 'Smartphone com tela de 6.1 polegadas, 128GB de armazenamento, câmera tripla de 48MP e bateria de 4000mAh.';
        document.getElementById('categoria').value = 'Eletrônicos';
        document.getElementById('marca').value = 'TechBrand';
        document.getElementById('preco').value = '1299.99';
        document.getElementById('preco_promocional').value = '1199.99';
        document.getElementById('peso').value = '0.185';
        document.getElementById('dimensoes').value = '14.7 x 7.1 x 0.8 cm';
        document.getElementById('ativo').checked = true;
        document.getElementById('destaque').checked = true;
    }

    // Validação em tempo real do formulário
    document.getElementById('formCadastrarProduto').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const sku = document.getElementById('sku').value.trim();
        const preco = document.getElementById('preco').value;

        if (!nome || !sku || !preco) {
            e.preventDefault();
            alert('Preencha todos os campos obrigatórios (Nome, SKU e Preço)');
            return false;
        }

        // Mostrar loading
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
        btn.disabled = true;
    });

    // Auto-preenchimento da categoria baseado em outras existentes
    document.getElementById('categoria').addEventListener('input', function() {
        // Implementar autocomplete se necessário
    });

    // Formatação automática de preços
    document.getElementById('preco').addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });

    document.getElementById('preco_promocional').addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
</script>