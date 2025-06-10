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
                            foreach ($produtos as $produto) {
                                if ($produto->ativo == 1) $ativos++;
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
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Estoque Baixo
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $estoque_baixo = 0;
                            foreach ($produtos as $produto) {
                                if (isset($produto->quantidade_atual) && isset($produto->quantidade_minima) && $produto->quantidade_atual <= $produto->quantidade_minima) {
                                    $estoque_baixo++;
                                }
                            }
                            echo $estoque_baixo;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('categoria'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-tags me-2"></i>
                Categorias
            </a>
            <a href="<?php echo base_url('produto/cadastrar'); ?>" class="btn btn-montink btn-sm">
                <i class="fas fa-plus me-2"></i>
                Novo Produto
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('produto'); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="nome" class="form-control"
                    placeholder="Digite o nome..."
                    value="<?php echo htmlspecialchars($filtros['nome'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Categoria</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Todas as categorias</option>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria->id; ?>"
                                <?php echo (($filtros['categoria_id'] ?? '') == $categoria->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria->nome); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Marca</label>
                <select name="marca" class="form-select">
                    <option value="">Todas as marcas</option>
                    <?php if (!empty($marcas)): ?>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo htmlspecialchars($marca); ?>"
                                <?php echo (($filtros['marca'] ?? '') == $marca) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($marca); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="ativo" class="form-select">
                    <option value="">Todos os status</option>
                    <option value="1" <?php echo ($filtros['ativo'] === '1') ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo ($filtros['ativo'] === '0') ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Estoque</label>
                <select name="estoque_baixo" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" <?php echo ($filtros['estoque_baixo'] === '1') ? 'selected' : ''; ?>>Estoque Baixo</option>
                    <option value="0" <?php echo ($filtros['sem_estoque'] === '1') ? 'selected' : ''; ?>>Sem Estoque</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
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
                <a class="dropdown-item" href="<?php echo base_url('estoque'); ?>">
                    <i class="fas fa-boxes me-2"></i>Gerenciar Estoque
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
                            <th>Produto</th>
                            <th>SKU</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th width="100">Estoque</th>
                            <th width="80">Status</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td>
                                    <?php if ($produto->imagem_principal): ?>
                                        <img src="<?php echo base_url('uploads/produtos/' . $produto->imagem_principal); ?>"
                                            alt="<?php echo htmlspecialchars($produto->nome); ?>"
                                            class="img-thumbnail produto-thumb">
                                    <?php else: ?>
                                        <div class="produto-thumb-placeholder">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($produto->nome); ?></div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <?php if ($produto->marca): ?>
                                            <small class="text-muted"><?php echo htmlspecialchars($produto->marca); ?></small>
                                        <?php endif; ?>
                                        <?php if ($produto->destaque): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star"></i> Destaque
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <code><?php echo htmlspecialchars($produto->sku); ?></code>
                                </td>
                                <td>
                                    <?php if ($produto->categoria_nome): ?>
                                        <span class="badge rounded-pill"
                                            style="background-color: <?php echo $produto->categoria_cor ?: '#6c757d'; ?>">
                                            <?php echo htmlspecialchars($produto->categoria_nome); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Sem categoria</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-success"><?php echo formatar_moeda($produto->preco); ?></div>
                                    <?php if ($produto->preco_promocional && $produto->preco_promocional > 0): ?>
                                        <small class="text-danger">
                                            <del><?php echo formatar_moeda($produto->preco); ?></del>
                                            <?php echo formatar_moeda($produto->preco_promocional); ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($produto->controlar_estoque): ?>
                                        <?php if (isset($produto->quantidade_atual)): ?>
                                            <?php
                                            $disponivel = $produto->quantidade_atual - ($produto->quantidade_reservada ?: 0);
                                            $classe_estoque = '';
                                            if ($produto->quantidade_atual <= $produto->quantidade_minima) {
                                                $classe_estoque = 'text-danger';
                                            } elseif ($produto->quantidade_atual <= ($produto->quantidade_minima * 2)) {
                                                $classe_estoque = 'text-warning';
                                            } else {
                                                $classe_estoque = 'text-success';
                                            }
                                            ?>
                                            <div class="<?php echo $classe_estoque; ?>">
                                                <strong><?php echo $produto->quantidade_atual; ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                Disp: <?php echo $disponivel; ?>
                                                <?php if ($produto->quantidade_reservada > 0): ?>
                                                    | Res: <?php echo $produto->quantidade_reservada; ?>
                                                <?php endif; ?>
                                            </small>
                                            <?php if ($produto->quantidade_atual <= $produto->quantidade_minima): ?>
                                                <div>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> Baixo
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Não cadastrado</span>
                                            <div>
                                                <a href="<?php echo base_url('estoque/entrada'); ?>?produto=<?php echo $produto->id; ?>"
                                                    class="badge bg-primary text-decoration-none">
                                                    <i class="fas fa-plus"></i> Cadastrar
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Não controla</span>
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
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo base_url('produto/visualizar/' . $produto->id); ?>"
                                            class="btn btn-sm btn-outline-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('produto/editar/' . $produto->id); ?>"
                                            class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($produto->controlar_estoque): ?>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-warning"
                                                onclick="ajustarEstoque(<?php echo $produto->id; ?>, '<?php echo addslashes($produto->nome); ?>', <?php echo $produto->quantidade_atual ?: 0; ?>)"
                                                title="Ajustar Estoque">
                                                <i class="fas fa-boxes"></i>
                                            </button>
                                        <?php endif; ?>
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
                <div class="d-flex gap-2 justify-content-center">
                    <a href="<?php echo base_url('produto/cadastrar'); ?>" class="btn btn-montink">
                        <i class="fas fa-plus me-2"></i>
                        Cadastrar Primeiro Produto
                    </a>
                    <a href="<?php echo base_url('categoria/cadastrar'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-tags me-2"></i>
                        Cadastrar Categoria
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Ajuste de Estoque -->
<div class="modal fade" id="modalAjusteEstoque" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajustar Estoque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAjusteEstoque">
                <div class="modal-body">
                    <input type="hidden" id="produtoIdAjuste">
                    <div class="mb-3">
                        <label class="form-label">Produto:</label>
                        <div id="produtoNomeAjuste" class="fw-bold"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantidade Atual:</label>
                        <div id="quantidadeAtualAjuste" class="fw-bold text-primary"></div>
                    </div>
                    <div class="mb-3">
                        <label for="quantidadeNovaAjuste" class="form-label">Nova Quantidade:</label>
                        <input type="number" class="form-control" id="quantidadeNovaAjuste" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="motivoAjuste" class="form-label">Motivo do Ajuste:</label>
                        <textarea class="form-control" id="motivoAjuste" rows="3" placeholder="Descreva o motivo do ajuste..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Ajustar Estoque</button>
                </div>
            </form>
        </div>
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

    .no-gutters>.col,
    .no-gutters>[class*="col-"] {
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

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .gap-2 {
        gap: 0.5rem !important;
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

        .d-flex.gap-2 {
            flex-direction: column;
        }
    }

    .modal-backdrop {
        position: relative;
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
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('Erro: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showAlert('Erro ao excluir produto. Tente novamente.', 'error');
                });
        }
    }

    const modalElement = document.getElementById('modalAjusteEstoque');
    let modalInstance = null;

    function ajustarEstoque(id, nome, quantidadeAtual) {
        document.getElementById('produtoIdAjuste').value = id;
        document.getElementById('produtoNomeAjuste').textContent = nome;
        document.getElementById('quantidadeAtualAjuste').textContent = quantidadeAtual;
        document.getElementById('quantidadeNovaAjuste').value = quantidadeAtual;
        document.getElementById('motivoAjuste').value = '';

        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
        }
        modalInstance.show();
    }


    document.getElementById('formAjusteEstoque').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('produtoIdAjuste').value;
        const quantidade = document.getElementById('quantidadeNovaAjuste').value;
        const motivo = document.getElementById('motivoAjuste').value;

        const formData = new FormData();
        formData.append('quantidade_nova', quantidade);
        formData.append('motivo', motivo);

        fetch(`<?php echo base_url('produto/ajustar_estoque/'); ?>${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    showAlert(data.message, 'success');
                    if (modalInstance) modalInstance.hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('Erro: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('Erro ao ajustar estoque. Tente novamente.', 'error');
            });
    });



    function exportarProdutos() {
        // Construir URL com filtros atuais
        const params = new URLSearchParams();
        <?php if ($filtros['nome']): ?>
            params.append('nome', '<?php echo addslashes($filtros['nome']); ?>');
        <?php endif; ?>
        <?php if ($filtros['categoria_id']): ?>
            params.append('categoria_id', '<?php echo $filtros['categoria_id']; ?>');
        <?php endif; ?>
        <?php if ($filtros['ativo'] !== ''): ?>
            params.append('ativo', '<?php echo $filtros['ativo']; ?>');
        <?php endif; ?>
        <?php if ($filtros['marca']): ?>
            params.append('marca', '<?php echo addslashes($filtros['marca']); ?>');
        <?php endif; ?>

        window.open(`<?php echo base_url('produto/exportar'); ?>?${params.toString()}`, '_blank');
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Inserir no topo da página
        const container = document.querySelector('.container-fluid') || document.body;
        container.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto-remover após 5 segundos
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    }

    <?php if (!function_exists('build_pagination_url')): ?>
        <?php
        function build_pagination_url($page, $filtros)
        {
            $params = array_filter([
                'page' => $page,
                'nome' => $filtros['nome'],
                'categoria_id' => $filtros['categoria_id'],
                'marca' => $filtros['marca'],
                'ativo' => $filtros['ativo'],
                'estoque_baixo' => $filtros['estoque_baixo']
            ]);

            return base_url('produto?' . http_build_query($params));
        }
        ?>
    <?php endif; ?>

    <?php if (!function_exists('formatar_moeda')): ?>
        <?php
        function formatar_moeda($valor)
        {
            return 'R$ ' . number_format($valor, 2, ',', '.');
        }
        ?>
    <?php endif; ?>
</script>