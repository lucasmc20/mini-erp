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
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text"
                                   class="form-control"
                                   id="categoria"
                                   name="categoria"
                                   value="<?php echo set_value('categoria', $produto->categoria); ?>"
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
                                   value="<?php echo set_value('marca', $produto->marca); ?>"
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
                                   value="<?php echo set_value('codigo_barras', $produto->codigo_barras); ?>"
                                   placeholder="Código de barras do produto">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dimensoes" class="form-label">Dimensões</label>
                            <input type="text"
                                   class="form-control"
                                   id="dimensoes"
                                   name="dimensoes"
                                   value="<?php echo set_value('dimensoes', $produto->dimensoes); ?>"
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
                        <div class="col-md-6 mb-3">
                            <label for="preco_promocional" class="form-label">Preço Promocional</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       class="form-control"
                                       id="preco_promocional"
                                       name="preco_promocional"
                                       value="<?php echo set_value('preco_promocional', $produto->preco_promocional); ?>"
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
                               value="<?php echo set_value('peso', $produto->peso); ?>"
                               step="0.001"
                               min="0"
                               placeholder="0,000">
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
                                Formatos: JPG, PNG, GIF. Máximo: 2MB
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
                            <td><small><?php echo formatar_datetime_br($produto->data_cadastro); ?></small></td>
                        </tr>
                        <?php if ($produto->data_atualizacao): ?>
                            <tr>
                                <td class="text-muted small">Última Atualização:</td>
                                <td><small><?php echo formatar_datetime_br($produto->data_atualizacao); ?></small></td>
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
                    </table>
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
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="duplicarProduto()">
                            <i class="fas fa-copy me-2"></i>
                            Duplicar Produto
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="visualizarProduto()">
                            <i class="fas fa-eye me-2"></i>
                            Visualizar
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetarFormulario()">
                            <i class="fas fa-undo me-2"></i>
                            Resetar Alterações
                        </button>
                    </div>
                </div>
            </div>

            <!-- Histórico de Alterações -->
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-history me-2"></i>
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
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Use "Resetar" para desfazer alterações</small>
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

<!-- CSS específico -->
<style>
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
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

    .table td {
        padding: 0.25rem 0.5rem;
        border: none;
    }

    @media (max-width: 768px) {
        .btn-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .btn-group .btn {
            margin-bottom: 5px;
        }
    }
</style>

<!-- JavaScript específico -->
<script>
    // Armazenar valores originais para reset
    const valoresOriginais = {
        nome: '<?php echo addslashes($produto->nome); ?>',
        sku: '<?php echo addslashes($produto->sku); ?>',
        descricao: '<?php echo addslashes($produto->descricao); ?>',
        categoria: '<?php echo addslashes($produto->categoria); ?>',
        marca: '<?php echo addslashes($produto->marca); ?>',
        codigo_barras: '<?php echo addslashes($produto->codigo_barras); ?>',
        dimensoes: '<?php echo addslashes($produto->dimensoes); ?>',
        preco: '<?php echo $produto->preco; ?>',
        preco_promocional: '<?php echo $produto->preco_promocional; ?>',
        peso: '<?php echo $produto->peso; ?>',
        ativo: <?php echo $produto->ativo ? 'true' : 'false'; ?>,
        destaque: <?php echo $produto->destaque ? 'true' : 'false'; ?>
    };

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
                    };

                    div.appendChild(img);
                    div.appendChild(btn);
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function removerImagemGaleria(nomeImagem, index) {
        if (confirm('Tem certeza que deseja remover esta imagem?')) {
            fetch('<?php echo base_url('produto/remover_imagem_galeria'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `produto_id=<?php echo $produto->id; ?>&imagem=${encodeURIComponent(nomeImagem)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`galeria-item-${index}`).remove();
                        alert('Imagem removida com sucesso!');
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao remover imagem. Tente novamente.');
                });
        }
    }

    function resetarFormulario() {
        if (confirm('Tem certeza que deseja resetar todas as alterações?')) {
            // Restaurar valores originais
            Object.keys(valoresOriginais).forEach(campo => {
                const elemento = document.getElementById(campo);
                if (elemento) {
                    if (elemento.type === 'checkbox') {
                        elemento.checked = valoresOriginais[campo];
                    } else {
                        elemento.value = valoresOriginais[campo] || '';
                    }
                }
            });

            // Limpar previews
            document.getElementById('preview-principal').innerHTML = '';
            document.getElementById('preview-galeria').innerHTML = '';

            // Resetar inputs de arquivo
            document.getElementById('imagem_principal').value = '';
            document.getElementById('galeria').value = '';

            alert('Formulário resetado para os valores originais.');
        }
    }

    function duplicarProduto() {
        if (confirm('Deseja duplicar este produto? Será criado um novo produto com os mesmos dados e SKU diferente.')) {
            window.open('<?php echo base_url('produto/duplicar/' . $produto->id); ?>', '_blank');
        }
    }

    function visualizarProduto() {
        window.open('<?php echo base_url('produto/visualizar/' . $produto->id); ?>', '_blank');
    }

    function excluirProduto(id, nome) {
        if (confirm(`Tem certeza que deseja excluir o produto "${nome}"?\n\nEsta ação não pode ser desfeita.`)) {
            fetch(`<?php echo base_url('produto/excluir/'); ?>${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '<?php echo base_url('produto'); ?>';
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao excluir produto. Tente novamente.');
                });
        }
    }

    // Validação em tempo real do formulário
    document.getElementById('formEditarProduto').addEventListener('submit', function(e) {
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
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
        btn.disabled = true;

        // Se não estiver marcado "continuar editando", redirecionar após salvar
        if (!document.getElementById('continuarEditando').checked) {
            setTimeout(() => {
                if (!this.querySelector('.is-invalid')) {
                    window.location.href = '<?php echo base_url('produto'); ?>';
                }
            }, 1000);
        }
    });

    // Detectar mudanças no formulário
    let formularioAlterado = false;

    document.getElementById('formEditarProduto').addEventListener('change', function() {
        formularioAlterado = true;
    });

    document.getElementById('formEditarProduto').addEventListener('input', function() {
        formularioAlterado = true;
    });

    // Avisar sobre mudanças não salvas
    window.addEventListener('beforeunload', function(e) {
        if (formularioAlterado) {
            e.preventDefault();
            e.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?';
            return 'Você tem alterações não salvas. Deseja realmente sair?';
        }
    });

    // Marcar como salvo quando o formulário for enviado
    document.getElementById('formEditarProduto').addEventListener('submit', function() {
        formularioAlterado = false;
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

    // Validação de preço promocional
    document.getElementById('preco_promocional').addEventListener('input', function() {
        const precoNormal = parseFloat(document.getElementById('preco').value) || 0;
        const precoPromocional = parseFloat(this.value) || 0;

        if (precoPromocional > 0 && precoPromocional >= precoNormal) {
            this.setCustomValidity('O preço promocional deve ser menor que o preço normal');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

    // Auto-save de rascunho (opcional)
    function autoSaveDraft() {
        const formData = new FormData(document.getElementById('formEditarProduto'));
        const dados = {};

        // Converter FormData para objeto
        for (let [key, value] of formData.entries()) {
            if (key !== 'imagem_principal' && key !== 'galeria[]') {
                dados[key] = value;
            }
        }

        // Salvar no localStorage
        localStorage.setItem(`produto_draft_${<?php echo $produto->id; ?>}`, JSON.stringify(dados));
    }

    // Auto-save a cada 30 segundos se houver alterações
    setInterval(() => {
        if (formularioAlterado) {
            autoSaveDraft();
            console.log('Rascunho salvo automaticamente');
        }
    }, 30000);

    // Restaurar rascunho se existir
    function restaurarRascunho() {
        const rascunho = localStorage.getItem(`produto_draft_${<?php echo $produto->id; ?>}`);

        if (rascunho) {
            if (confirm('Foi encontrado um rascunho não salvo deste produto. Deseja restaurá-lo?')) {
                const dados = JSON.parse(rascunho);

                Object.keys(dados).forEach(campo => {
                    const elemento = document.getElementById(campo);
                    if (elemento) {
                        if (elemento.type === 'checkbox') {
                            elemento.checked = dados[campo] === '1';
                        } else {
                            elemento.value = dados[campo] || '';
                        }
                    }
                });

                formularioAlterado = true;
            } else {
                // Limpar rascunho se o usuário não quiser restaurar
                localStorage.removeItem(`produto_draft_${<?php echo $produto->id; ?>}`);
            }
        }
    }

    // Limpar rascunho após salvar com sucesso
    function limparRascunho() {
        localStorage.removeItem(`produto_draft_${<?php echo $produto->id; ?>}`);
    }

    // Restaurar rascunho ao carregar a página
    document.addEventListener('DOMContentLoaded', restaurarRascunho);

    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl+S para salvar
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.getElementById('formEditarProduto').submit();
        }

        // Ctrl+Z para resetar
        if (e.ctrlKey && e.key === 'z' && e.shiftKey) {
            e.preventDefault();
            resetarFormulario();
        }

        // Esc para cancelar
        if (e.key === 'Escape') {
            if (confirm('Deseja cancelar a edição? Alterações não salvas serão perdidas.')) {
                history.back();
            }
        }
    });

    // Adicionar indicador visual de campo alterado
    document.querySelectorAll('input, textarea, select').forEach(campo => {
        const valorOriginal = campo.value;

        campo.addEventListener('input', function() {
            if (this.value !== valorOriginal) {
                this.classList.add('campo-alterado');
            } else {
                this.classList.remove('campo-alterado');
            }
        });
    });

    // CSS para campos alterados
    const style = document.createElement('style');
    style.textContent = `
    .campo-alterado {
        border-left: 3px solid #ffc107 !important;
        background-color: #fffbf0 !important;
    }

    .campo-alterado:focus {
        border-left: 3px solid #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }
`;
    document.head.appendChild(style);

    console.log('Sistema de edição carregado com sucesso!');
    console.log('Atalhos disponíveis:');
    console.log('- Ctrl+S: Salvar');
    console.log('- Ctrl+Shift+Z: Resetar');
    console.log('- Esc: Cancelar');
</script>