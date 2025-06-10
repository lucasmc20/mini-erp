<!-- Cards de Estatísticas dos Produtos -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total de Produtos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $paginacao['total']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Produtos Ativos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $ativos = 0;
                            foreach($produtos as $produto) {
                                if($produto->ativo == 1) $ativos++;
                            }
                            echo $ativos;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Em Destaque
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $destaques = 0;
                            foreach($produtos as $produto) {
                                if($produto->destaque == 1) $destaques++;
                            }
                            echo $destaques;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Categorias
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo count($categorias); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros e Ações -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i>
            Filtros
        </h6>
        <a href="<?php echo base_url('produto/cadastrar'); ?>" class="btn btn-montink btn-sm">
            <i class="fas fa-plus me-2"></i>
            Novo Produto
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('produto'); ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="nome" class="form-control"
                       placeholder="Digite o nome..."
                       value="<?php echo $filtros['nome']; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Categoria</label>
                <select name="categoria" class="form-select">
                    <option value="">Todas as categorias</option>
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?php echo $categoria; ?>"
                            <?php echo ($filtros['categoria'] == $categoria) ? 'selected' : ''; ?>>
                            <?php echo $categoria; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="ativo" class="form-select">
                    <option value="">Todos os status</option>
                    <option value="1" <?php echo ($filtros['ativo'] === '1') ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo ($filtros['ativo'] === '0') ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-montink me-2">
                    <i class="fas fa-search"></i>
                    Filtrar
                </button>
                <a href="<?php echo base_url('produto'); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Produtos -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-box me-2"></i>
            Lista de Produtos
        </h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
                <div class="dropdown-header">Ações:</div>
                <a class="dropdown-item" href="<?php echo base_url('produto/cadastrar'); ?>">
                    <i class="fas fa-plus me-2"></i>Novo Produto
                </a>
                <a class="dropdown-item" href="<?php echo base_url('relatorios/produtos'); ?>">
                    <i class="fas fa-chart-bar me-2"></i>Relatório
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="exportarProdutos()">
                    <i class="fas fa-download me-2"></i>Exportar CSV
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($produtos)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="60">Imagem</th>
                        <th>Nome</th>
                        <th>SKU</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th width="100">Status</th>
                        <th width="80">Destaque</th>
                        <th width="120">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td>
                                <?php if ($produto->imagem_principal): ?>
                                    <img src="<?php echo base_url('uploads/produtos/' . $produto->imagem_principal); ?>"
                                         alt="<?php echo $produto->nome; ?>"
                                         class="img-thumbnail produto-thumb">
                                <?php else: ?>
                                    <div class="produto-thumb-placeholder">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo $produto->nome; ?></div>
                                <?php if ($produto->marca): ?>
                                    <small class="text-muted"><?php echo $produto->marca; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code><?php echo $produto->sku; ?></code>
                            </td>
                            <td>
                                <?php if ($produto->categoria): ?>
                                    <span class="badge bg-secondary"><?php echo $produto->categoria; ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-success"><?php echo formatar_moeda($produto->preco); ?></div>
                                <?php if ($produto->preco_promocional): ?>
                                    <small class="text-danger">Promo: <?php echo formatar_moeda($produto->preco_promocional); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($produto->ativo): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($produto->destaque): ?>
                                    <i class="fas fa-star text-warning" title="Em destaque"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-muted" title="Sem destaque"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo base_url('produto/visualizar/' . $produto->id); ?>"
                                       class="btn btn-sm btn-outline-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo base_url('produto/editar/' . $produto->id); ?>"
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="excluirProduto(<?php echo $produto->id; ?>, '<?php echo addslashes($produto->nome); ?>')"
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($paginacao['total_paginas'] > 1): ?>
                <nav aria-label="Paginação dos produtos">
                    <ul class="pagination justify-content-center">
                        <!-- Primeira página -->
                        <?php if ($paginacao['pagina_atual'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo build_pagination_url(1, $filtros); ?>">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo build_pagination_url($paginacao['pagina_atual'] - 1, $filtros); ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Páginas numeradas -->
                        <?php
                        $inicio = max(1, $paginacao['pagina_atual'] - 2);
                        $fim = min($paginacao['total_paginas'], $paginacao['pagina_atual'] + 2);

                        for ($i = $inicio; $i <= $fim; $i++):
                            ?>
                            <li class="page-item <?php echo ($i == $paginacao['pagina_atual']) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo build_pagination_url($i, $filtros); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Última página -->
                        <?php if ($paginacao['pagina_atual'] < $paginacao['total_paginas']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo build_pagination_url($paginacao['pagina_atual'] + 1, $filtros); ?>">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo build_pagination_url($paginacao['total_paginas'], $filtros); ?>">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <!-- Info da paginação -->
                <div class="text-center text-muted">
                    Mostrando <?php echo (($paginacao['pagina_atual'] - 1) * $paginacao['por_pagina']) + 1; ?>
                    até <?php echo min($paginacao['pagina_atual'] * $paginacao['por_pagina'], $paginacao['total']); ?>
                    de <?php echo $paginacao['total']; ?> produtos
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <h5>Nenhum produto encontrado</h5>
                <p>Não foram encontrados produtos com os filtros selecionados.</p>
                <a href="<?php echo base_url('produto/cadastrar'); ?>" class="btn btn-montink">
                    <i class="fas fa-plus me-2"></i>
                    Cadastrar Primeiro Produto
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- CSS específico para produtos -->
<style>
    .border-left-primary {
        border-left: 4px solid var(--montink-primary) !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .text-xs {
        font-size: 0.7rem;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .text-gray-400 {
        color: #c8c9ca !important;
    }

    .text-gray-500 {
        color: #b7b9cc !important;
    }

    .text-gray-800 {
        color: #3a3b45 !important;
    }

    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }

    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }

    .produto-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .produto-thumb-placeholder {
        width: 50px;
        height: 50px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .pagination .page-link {
        color: var(--montink-primary);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--montink-primary);
        border-color: var(--montink-primary);
    }

    .badge {
        font-size: 0.75em;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            margin-bottom: 2px;
            margin-right: 0;
        }

        .table-responsive {
            font-size: 0.9em;
        }
    }
</style>

<!-- JavaScript específico para produtos -->
<script>
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
                        location.reload();
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

    function exportarProdutos() {
        // Construir URL com filtros atuais
        const params = new URLSearchParams();
        <?php if ($filtros['nome']): ?>
        params.append('nome', '<?php echo $filtros['nome']; ?>');
        <?php endif; ?>
        <?php if ($filtros['categoria']): ?>
        params.append('categoria', '<?php echo $filtros['categoria']; ?>');
        <?php endif; ?>
        <?php if ($filtros['ativo'] !== ''): ?>
        params.append('ativo', '<?php echo $filtros['ativo']; ?>');
        <?php endif; ?>

        window.open(`<?php echo base_url('produto/exportar'); ?>?${params.toString()}`, '_blank');
    }

    <?php if (!function_exists('build_pagination_url')): ?>
    <?php
    function build_pagination_url($page, $filtros) {
        $params = array_filter([
            'page' => $page,
            'nome' => $filtros['nome'],
            'categoria' => $filtros['categoria'],
            'ativo' => $filtros['ativo']
        ]);

        return base_url('produto?' . http_build_query($params));
    }
    ?>
    <?php endif; ?>
</script>