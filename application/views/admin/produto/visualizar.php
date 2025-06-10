<!-- Header da Página -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info me-2"></i>
            <?php echo $produto->nome; ?>
        </h1>
        <p class="mb-0 text-gray-600">
            <span class="badge bg-secondary me-2">SKU: <?php echo $produto->sku; ?></span>
            <?php if ($produto->categoria): ?>
                <span class="badge bg-primary me-2"><?php echo $produto->categoria; ?></span>
            <?php endif; ?>
            <?php if ($produto->ativo): ?>
                <span class="badge bg-success">Ativo</span>
            <?php else: ?>
                <span class="badge bg-danger">Inativo</span>
            <?php endif; ?>
            <?php if ($produto->destaque): ?>
                <span class="badge bg-warning text-dark ms-1">
                    <i class="fas fa-star me-1"></i>Destaque
                </span>
            <?php endif; ?>
        </p>
    </div>
    <div class="btn-group" role="group">
        <a href="<?php echo base_url('produto'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Voltar à Lista
        </a>
        <a href="<?php echo base_url('produto/editar/' . $produto->id); ?>" class="btn btn-montink">
            <i class="fas fa-edit me-2"></i>
            Editar
        </a>
        <button type="button"
                class="btn btn-outline-danger"
                onclick="excluirProduto(<?php echo $produto->id; ?>, '<?php echo addslashes($produto->nome); ?>')">
            <i class="fas fa-trash me-2"></i>
            Excluir
        </button>
    </div>
</div>

<div class="row">
    <!-- Coluna Principal -->
    <div class="col-lg-8">
        <!-- Galeria de Imagens -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-images me-2"></i>
                    Galeria de Imagens
                </h6>
            </div>
            <div class="card-body">
                <?php
                $galeria = json_decode($produto->galeria_imagens, true) ?: [];
                $todasImagens = [];

                // Adicionar imagem principal primeiro
                if ($produto->imagem_principal) {
                    $todasImagens[] = $produto->imagem_principal;
                }

                // Adicionar imagens da galeria
                $todasImagens = array_merge($todasImagens, $galeria);
                ?>

                <?php if (!empty($todasImagens)): ?>
                    <!-- Imagem Principal -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="main-image-container mb-3">
                                <img src="<?php echo base_url('uploads/produtos/' . $todasImagens[0]); ?>"
                                     alt="<?php echo $produto->nome; ?>"
                                     class="img-fluid main-product-image"
                                     id="imagemPrincipal">
                            </div>
                        </div>

                        <!-- Miniaturas -->
                        <?php if (count($todasImagens) > 1): ?>
                            <div class="col-md-4">
                                <div class="thumbnails-container">
                                    <?php foreach ($todasImagens as $index => $imagem): ?>
                                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                             onclick="trocarImagem('<?php echo base_url('uploads/produtos/' . $imagem); ?>', this)">
                                            <img src="<?php echo base_url('uploads/produtos/' . $imagem); ?>"
                                                 alt="Miniatura <?php echo $index + 1; ?>"
                                                 class="img-thumbnail">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Controles da Galeria -->
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="ampliarImagem()">
                            <i class="fas fa-search-plus me-1"></i>
                            Ampliar
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="baixarImagem()">
                            <i class="fas fa-download me-1"></i>
                            Baixar
                        </button>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-image fa-3x mb-3"></i>
                        <p>Nenhuma imagem cadastrada para este produto</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informações Detalhadas -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-info-circle me-2"></i>
                    Informações Detalhadas
                </h6>
            </div>
            <div class="card-body">
                <?php if ($produto->descricao): ?>
                    <div class="mb-4">
                        <h6 class="text-primary">Descrição:</h6>
                        <p class="text-justify"><?php echo nl2br($produto->descricao); ?></p>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">SKU:</td>
                                <td><code><?php echo $produto->sku; ?></code></td>
                            </tr>
                            <?php if ($produto->categoria): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Categoria:</td>
                                    <td><span class="badge bg-secondary"><?php echo $produto->categoria; ?></span></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($produto->marca): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Marca:</td>
                                    <td><?php echo $produto->marca; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($produto->codigo_barras): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Código de Barras:</td>
                                    <td><code><?php echo $produto->codigo_barras; ?></code></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <?php if ($produto->peso): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Peso:</td>
                                    <td><?php echo $produto->peso; ?> kg</td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($produto->dimensoes): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Dimensões:</td>
                                    <td><?php echo $produto->dimensoes; ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="fw-bold text-muted">Data de Cadastro:</td>
                                <td><?php echo formatar_datetime_br($produto->data_cadastro); ?></td>
                            </tr>
                            <?php if ($produto->data_atualizacao): ?>
                                <tr>
                                    <td class="fw-bold text-muted">Última Atualização:</td>
                                    <td><?php echo formatar_datetime_br($produto->data_atualizacao); ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coluna Lateral -->
    <div class="col-lg-4">
        <!-- Preços -->
        <div class="card shadow mb-4 border-left-success">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-dollar-sign me-2"></i>
                    Preços
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <label class="form-label text-muted small">PREÇO NORMAL</label>
                    <h3 class="text-success mb-0"><?php echo formatar_moeda($produto->preco); ?></h3>
                </div>

                <?php if ($produto->preco_promocional): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">PREÇO PROMOCIONAL</label>
                        <h4 class="text-danger mb-0"><?php echo formatar_moeda($produto->preco_promocional); ?></h4>
                        <small class="text-muted">
                            Economia: <?php echo formatar_moeda($produto->preco - $produto->preco_promocional); ?>
                            (<?php echo number_format((($produto->preco - $produto->preco_promocional) / $produto->preco) * 100, 1); ?>% OFF)
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Status e Configurações -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-cog me-2"></i>
                    Status e Configurações
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Status do Produto:</span>
                    <?php if ($produto->ativo): ?>
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>Ativo
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger">
                            <i class="fas fa-times me-1"></i>Inativo
                        </span>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Produto em Destaque:</span>
                    <?php if ($produto->destaque): ?>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Sim
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">
                            <i class="fas fa-minus me-1"></i>Não
                        </span>
                    <?php endif; ?>
                </div>

                <hr>

                <!-- Ações Rápidas -->
                <div class="d-grid gap-2">
                    <?php if (!$produto->ativo): ?>
                        <button class="btn btn-success btn-sm" onclick="alterarStatus(<?php echo $produto->id; ?>, 1)">
                            <i class="fas fa-toggle-on me-2"></i>Ativar Produto
                        </button>
                    <?php else: ?>
                        <button class="btn btn-warning btn-sm" onclick="alterarStatus(<?php echo $produto->id; ?>, 0)">
                            <i class="fas fa-toggle-off me-2"></i>Desativar Produto
                        </button>
                    <?php endif; ?>

                    <?php if (!$produto->destaque): ?>
                        <button class="btn btn-outline-warning btn-sm" onclick="alterarDestaque(<?php echo $produto->id; ?>, 1)">
                            <i class="fas fa-star me-2"></i>Colocar em Destaque
                        </button>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm" onclick="alterarDestaque(<?php echo $produto->id; ?>, 0)">
                            <i class="fas fa-star-half-alt me-2"></i>Remover Destaque
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="card shadow mb-4 border-left-info">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-chart-bar me-2"></i>
                    Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-primary mb-1">0</h5>
                            <small class="text-muted">Vendas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-info mb-1">0</h5>
                        <small class="text-muted">Visualizações</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Cadastrado em <?php echo formatar_data_br($produto->data_cadastro); ?>
                    </small>
                </div>
            </div>
        </div>

        <!-- Links Relacionados -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">
                    <i class="fas fa-link me-2"></i>
                    Links Relacionados
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('estoque/produto/' . $produto->id); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-boxes me-2"></i>Gerenciar Estoque
                    </a>
                    <a href="<?php echo base_url('relatorios/produto/' . $produto->id); ?>" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-chart-line me-2"></i>Relatório de Vendas
                    </a>
                    <a href="<?php echo base_url('produto/duplicar/' . $produto->id); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-copy me-2"></i>Duplicar Produto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ampliar Imagem -->
<div class="modal fade" id="modalAmpliarImagem" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search-plus me-2"></i>
                    <?php echo $produto->nome; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" alt="Imagem ampliada" class="img-fluid w-100" id="imagemAmpliada">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="baixarImagem()">
                    <i class="fas fa-download me-2"></i>Baixar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CSS específico -->
<style>
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .text-gray-600 {
        color: #6c757d !important;
    }

    .text-gray-800 {
        color: #3a3b45 !important;
    }

    .main-product-image {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .main-product-image:hover {
        transform: scale(1.02);
    }

    .thumbnails-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .thumbnail-item {
        margin-bottom: 10px;
        cursor: pointer;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .thumbnail-item:hover {
        transform: scale(1.05);
    }

    .thumbnail-item.active {
        box-shadow: 0 0 0 3px var(--montink-primary);
    }

    .thumbnail-item img {
        width: 100%;
        height: 80px;
        object-fit: cover;
    }

    .table td {
        padding: 0.5rem 0.75rem;
        border: none;
    }

    .badge {
        font-size: 0.8em;
    }

    .btn-group .btn {
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }

    .text-justify {
        text-align: justify;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin-bottom: 5px;
        }

        .thumbnails-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .thumbnail-item {
            flex: 0 0 auto;
            width: 80px;
            margin-right: 10px;
            margin-bottom: 0;
        }
    }
</style>

<!-- JavaScript específico -->
<script>
    function trocarImagem(novoSrc, thumbnailElement) {
        // Atualizar imagem principal
        document.getElementById('imagemPrincipal').src = novoSrc;

        // Remover classe active de todas as miniaturas
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });

        // Adicionar classe active na miniatura clicada
        thumbnailElement.classList.add('active');
    }

    function ampliarImagem() {
        const imagemPrincipal = document.getElementById('imagemPrincipal');
        const imagemAmpliada = document.getElementById('imagemAmpliada');

        imagemAmpliada.src = imagemPrincipal.src;

        const modal = new bootstrap.Modal(document.getElementById('modalAmpliarImagem'));
        modal.show();
    }

    function baixarImagem() {
        const imagemSrc = document.getElementById('imagemPrincipal').src;
        const link = document.createElement('a');
        link.href = imagemSrc;
        link.download = '<?php echo $produto->nome; ?>_imagem.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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

    function alterarStatus(id, novoStatus) {
        const acao = novoStatus ? 'ativar' : 'desativar';

        if (confirm(`Tem certeza que deseja ${acao} este produto?`)) {
            fetch(`<?php echo base_url('produto/alterar_status'); ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    produto_id: id,
                    ativo: novoStatus
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao alterar status. Tente novamente.');
                });
        }
    }

    function alterarDestaque(id, novoDestaque) {
        const acao = novoDestaque ? 'colocar em destaque' : 'remover do destaque';

        if (confirm(`Tem certeza que deseja ${acao} este produto?`)) {
            fetch(`<?php echo base_url('produto/alterar_destaque'); ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    produto_id: id,
                    destaque: novoDestaque
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao alterar destaque. Tente novamente.');
                });
        }
    }

    // Permitir ampliar clicando na imagem principal
    document.getElementById('imagemPrincipal').addEventListener('click', ampliarImagem);
</script>