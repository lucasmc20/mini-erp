<!-- Cards de Estatísticas do Estoque -->
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
                            <?php echo $estatisticas['total_produtos']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                            <?php echo $estatisticas['estoque_baixo']; ?>
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
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Próx. Vencimento
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $estatisticas['proximos_vencimento']; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
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
                            Valor Total
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo formatar_moeda($estatisticas['valor_total_estoque']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
        <div class="btn-group" role="group">
            <a href="<?php echo base_url('estoque/entrada'); ?>" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-2"></i>
                Entrada
            </a>
            <a href="<?php echo base_url('estoque/saida'); ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-minus me-2"></i>
                Saída
            </a>
            <a href="<?php echo base_url('estoque/alertas'); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-bell me-2"></i>
                Alertas
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('estoque'); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="produto_nome" class="form-control" 
                       placeholder="Digite o nome..." 
                       value="<?php echo $filtros['produto_nome']; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Lote</label>
                <input type="text" name="lote" class="form-control" 
                       placeholder="Lote..." 
                       value="<?php echo $filtros['lote']; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Fornecedor</label>
                <input type="text" name="fornecedor" class="form-control" 
                       placeholder="Fornecedor..." 
                       value="<?php echo $filtros['fornecedor']; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Localização</label>
                <input type="text" name="localizacao" class="form-control" 
                       placeholder="Local..." 
                       value="<?php echo $filtros['localizacao']; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Filtro Especial</label>
                <select name="estoque_baixo" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" <?php echo ($filtros['estoque_baixo'] === '1') ? 'selected' : ''; ?>>Estoque Baixo</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-montink me-2">
                    <i class="fas fa-search"></i>
                </button>
                <a href="<?php echo base_url('estoque'); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Estoque -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-warehouse me-2"></i>
            Controle de Estoque
        </h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
                <div class="dropdown-header">Ações:</div>
                <a class="dropdown-item" href="<?php echo base_url('estoque/entrada'); ?>">
                    <i class="fas fa-plus me-2"></i>Nova Entrada
                </a>
                <a class="dropdown-item" href="<?php echo base_url('estoque/saida'); ?>">
                    <i class="fas fa-minus me-2"></i>Nova Saída
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url('estoque/relatorio'); ?>">
                    <i class="fas fa-chart-bar me-2"></i>Relatório
                </a>
                <a class="dropdown-item" href="#" onclick="exportarEstoque()">
                    <i class="fas fa-download me-2"></i>Exportar CSV
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($estoques)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th>SKU</th>
                        <th width="100">Atual</th>
                        <th width="100">Mínimo</th>
                        <th width="100">Reservado</th>
                        <th width="100">Disponível</th>
                        <th>Localização</th>
                        <th>Lote</th>
                        <th width="120">Status</th>
                        <th width="120">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($estoques as $estoque): ?>
                        <?php
                        $disponivel = $estoque->quantidade_atual - $estoque->quantidade_reservada;
                        $status_estoque = '';
                        $classe_status = '';
                        
                        if ($estoque->quantidade_atual <= $estoque->quantidade_minima) {
                            $status_estoque = 'Baixo';
                            $classe_status = 'bg-danger';
                        } elseif ($estoque->quantidade_atual <= ($estoque->quantidade_minima * 1.5)) {
                            $status_estoque = 'Atenção';
                            $classe_status = 'bg-warning text-dark';
                        } else {
                            $status_estoque = 'Normal';
                            $classe_status = 'bg-success';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo $estoque->produto_nome; ?></div>
                                <?php if ($estoque->fornecedor): ?>
                                    <small class="text-muted">Fornecedor: <?php echo $estoque->fornecedor; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code><?php echo $estoque->produto_codigo; ?></code>
                            </td>
                            <td>
                                <span class="badge bg-primary fs-6"><?php echo $estoque->quantidade_atual; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary fs-6"><?php echo $estoque->quantidade_minima; ?></span>
                            </td>
                            <td>
                                <?php if ($estoque->quantidade_reservada > 0): ?>
                                    <span class="badge bg-info fs-6"><?php echo $estoque->quantidade_reservada; ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-dark fs-6"><?php echo $disponivel; ?></span>
                            </td>
                            <td>
                                <?php if ($estoque->localizacao): ?>
                                    <span class="badge bg-light text-dark"><?php echo $estoque->localizacao; ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($estoque->lote): ?>
                                    <small class="text-muted"><?php echo $estoque->lote; ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $classe_status; ?>"><?php echo $status_estoque; ?></span>
                                <?php if ($estoque->data_validade): ?>
                                    <?php
                                    $dias_vencimento = (strtotime($estoque->data_validade) - time()) / (60 * 60 * 24);
                                    if ($dias_vencimento <= 30 && $dias_vencimento > 0):
                                    ?>
                                        <br><small class="text-danger">
                                            <i class="fas fa-calendar-times"></i>
                                            <?php echo (int)$dias_vencimento; ?>d
                                        </small>
                                    <?php elseif ($dias_vencimento <= 0): ?>
                                        <br><small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Vencido
                                        </small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo base_url('estoque/visualizar/' . $estoque->id); ?>" 
                                       class="btn btn-sm btn-outline-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo base_url('estoque/editar/' . $estoque->id); ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            onclick="ajusteRapido(<?php echo $estoque->id; ?>, '<?php echo addslashes($estoque->produto_nome); ?>', <?php echo $estoque->quantidade_atual; ?>)"
                                            title="Ajuste Rápido">
                                        <i class="fas fa-tools"></i>
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
                <nav aria-label="Paginação do estoque">
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
                    de <?php echo $paginacao['total']; ?> produtos no estoque
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-warehouse fa-3x mb-3"></i>
                <h5>Nenhum produto no estoque</h5>
                <p>Não foram encontrados produtos no estoque com os filtros selecionados.</p>
                <div class="btn-group" role="group">
                    <a href="<?php echo base_url('estoque/entrada'); ?>" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        Primeira Entrada
                    </a>
                    <a href="<?php echo base_url('produto'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-box me-2"></i>
                        Ver Produtos
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Ajuste Rápido -->
<div class="modal fade" id="modalAjusteRapido" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tools me-2"></i>
                    Ajuste Rápido de Estoque
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAjusteRapido">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Produto:</label>
                        <div id="produtoAjuste" class="fw-bold"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantidadeAtual" class="form-label">Quantidade Atual:</label>
                            <input type="text" class="form-control" id="quantidadeAtual" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quantidadeNova" class="form-label">Nova Quantidade <span class="text-danger">*</span>:</label>
                            <input type="number" class="form-control" id="quantidadeNova" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="motivoAjuste" class="form-label">Motivo do Ajuste <span class="text-danger">*</span>:</label>
                        <select class="form-select" id="motivoAjuste" required>
                            <option value="">Selecione o motivo</option>
                            <option value="Inventário">Inventário</option>
                            <option value="Correção de sistema">Correção de sistema</option>
                            <option value="Perda/Avaria">Perda/Avaria</option>
                            <option value="Transferência">Transferência</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <div id="diferencaInfo" class="alert" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        Confirmar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS específico para estoque -->
<style>
    .border-left-primary {
        border-left: 4px solid var(--montink-primary) !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
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

    .badge.fs-6 {
        font-size: 0.9rem !important;
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

<!-- JavaScript específico para estoque -->
<script>
let estoqueAjusteId = null;

function ajusteRapido(id, nomeProduto, quantidadeAtual) {
    estoqueAjusteId = id;
    document.getElementById('produtoAjuste').textContent = nomeProduto;
    document.getElementById('quantidadeAtual').value = quantidadeAtual;
    document.getElementById('quantidadeNova').value = quantidadeAtual;
    document.getElementById('motivoAjuste').value = '';
    document.getElementById('diferencaInfo').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('modalAjusteRapido'));
    modal.show();
}

// Calcular diferença em tempo real
document.getElementById('quantidadeNova').addEventListener('input', function() {
    const atual = parseInt(document.getElementById('quantidadeAtual').value) || 0;
    const nova = parseInt(this.value) || 0;
    const diferenca = nova - atual;
    const infoDiv = document.getElementById('diferencaInfo');
    
    if (diferenca !== 0) {
        const tipo = diferenca > 0 ? 'Entrada' : 'Saída';
        const classe = diferenca > 0 ? 'alert-success' : 'alert-warning';
        
        infoDiv.className = `alert ${classe}`;
        infoDiv.innerHTML = `<strong>${tipo}:</strong> ${Math.abs(diferenca)} unidades`;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
});

// Processar ajuste
document.getElementById('formAjusteRapido').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const quantidadeNova = document.getElementById('quantidadeNova').value;
    const motivo = document.getElementById('motivoAjuste').value;
    
    if (!quantidadeNova || !motivo) {
        alert('Preencha todos os campos obrigatórios');
        return;
    }
    
    fetch('<?php echo base_url('estoque/ajustar_estoque'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `produto_id=${estoqueAjusteId}&quantidade_nova=${quantidadeNova}&motivo=${encodeURIComponent(motivo)}`
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
        alert('Erro ao processar ajuste. Tente novamente.');
    });
});

function exportarEstoque() {
    // Construir URL com filtros atuais
    const params = new URLSearchParams();
    <?php if ($filtros['produto_nome']): ?>
        params.append('produto_nome', '<?php echo $filtros['produto_nome']; ?>');
    <?php endif; ?>
    <?php if ($filtros['lote']): ?>
        params.append('lote', '<?php echo $filtros['lote']; ?>');
    <?php endif; ?>
    <?php if ($filtros['fornecedor']): ?>
        params.append('fornecedor', '<?php echo $filtros['fornecedor']; ?>');
    <?php endif; ?>
    <?php if ($filtros['localizacao']): ?>
        params.append('localizacao', '<?php echo $filtros['localizacao']; ?>');
    <?php endif; ?>
    <?php if ($filtros['estoque_baixo'] !== ''): ?>
        params.append('estoque_baixo', '<?php echo $filtros['estoque_baixo']; ?>');
    <?php endif; ?>
    
    window.open(`<?php echo base_url('estoque/exportar'); ?>?${params.toString()}`, '_blank');
}

<?php if (!function_exists('build_pagination_url')): ?>
<?php
function build_pagination_url($page, $filtros) {
    $params = array_filter([
        'page' => $page,
        'produto_nome' => $filtros['produto_nome'],
        'lote' => $filtros['lote'],
        'fornecedor' => $filtros['fornecedor'],
        'localizacao' => $filtros['localizacao'],
        'estoque_baixo' => $filtros['estoque_baixo']
    ]);
    
    return base_url('estoque?' . http_build_query($params));
}
?>
<?php endif; ?>
</script>