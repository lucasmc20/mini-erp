    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de Usuários</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $paginacao['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Usuários Ativos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_ativos ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Inativos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_inativos ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Novos no mês</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_novos ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
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
                <i class="fas fa-filter me-2"></i>Filtros
            </h6>
            <div class="d-flex gap-2">
                <a href="<?= base_url('usuario/cadastrar'); ?>" class="btn btn-montink btn-sm">
                    <i class="fas fa-plus me-2"></i>Novo Usuário
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('usuario'); ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control"
                        placeholder="Digite o nome..." value="<?= htmlspecialchars($filtros['nome'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="text" name="email" class="form-control"
                        placeholder="Digite o e-mail..." value="<?= htmlspecialchars($filtros['email'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="ativo" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" <?= ($filtros['ativo'] === '1') ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?= ($filtros['ativo'] === '0') ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-montink me-2">
                        <i class="fas fa-search"></i>Filtrar
                    </button>
                    <a href="<?= base_url('usuario'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>
                Lista de Usuários
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($usuarios)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th>Cadastro</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3"><?= strtoupper(substr($usuario->nome, 0, 2)) ?></div>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($usuario->nome) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($usuario->email) ?></td>
                                    <td><?= $usuario->telefone ? htmlspecialchars($usuario->telefone) : '<span class="text-muted">Não informado</span>' ?></td>
                                    <td>
                                        <?php if ($usuario->ativo): ?>
                                            <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inativo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($usuario->data_cadastro)) ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('usuario/visualizar/' . $usuario->id); ?>" class="btn btn-sm btn-outline-info" title="Visualizar"><i class="fas fa-eye"></i></a>
                                            <a href="<?= base_url('usuario/editar/' . $usuario->id); ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir"
                                                onclick="excluirUsuario(<?= $usuario->id ?>, '<?= addslashes($usuario->nome) ?>')">
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
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php
                            $inicio = max(1, $paginacao['pagina_atual'] - 2);
                            $fim = min($paginacao['total_paginas'], $paginacao['pagina_atual'] + 2);
                            if ($paginacao['pagina_atual'] > 1):
                            ?>
                                <li class="page-item"><a class="page-link" href="<?= build_pagination_url(1, $filtros) ?>"><i class="fas fa-angle-double-left"></i></a></li>
                                <li class="page-item"><a class="page-link" href="<?= build_pagination_url($paginacao['pagina_atual'] - 1, $filtros) ?>"><i class="fas fa-angle-left"></i></a></li>
                            <?php endif;
                            for ($i = $inicio; $i <= $fim; $i++): ?>
                                <li class="page-item <?= ($i == $paginacao['pagina_atual']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?= build_pagination_url($i, $filtros) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor;
                            if ($paginacao['pagina_atual'] < $paginacao['total_paginas']): ?>
                                <li class="page-item"><a class="page-link" href="<?= build_pagination_url($paginacao['pagina_atual'] + 1, $filtros) ?>"><i class="fas fa-angle-right"></i></a></li>
                                <li class="page-item"><a class="page-link" href="<?= build_pagination_url($paginacao['total_paginas'], $filtros) ?>"><i class="fas fa-angle-double-right"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <div class="text-center text-muted">
                        Mostrando <?= (($paginacao['pagina_atual'] - 1) * $paginacao['por_pagina']) + 1 ?>
                        até <?= min($paginacao['pagina_atual'] * $paginacao['por_pagina'], $paginacao['total']) ?>
                        de <?= $paginacao['total'] ?> usuários
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h5>Nenhum usuário encontrado</h5>
                    <p>Não foram encontrados usuários com os filtros selecionados.</p>
                    <a href="<?= base_url('usuario/cadastrar'); ?>" class="btn btn-montink">
                        <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Usuário
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function excluirUsuario(id, nome) {
            if (confirm(`Tem certeza que deseja excluir o usuário "${nome}"?\n\nEsta ação não pode ser desfeita.`)) {
                fetch(`<?= base_url('usuario/excluir/'); ?>${id}`, {
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
                        showAlert('Erro ao excluir usuário. Tente novamente.', 'error');
                    });
            }
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

            const container = document.querySelector('.container-fluid') || document.body;
            container.insertAdjacentHTML('afterbegin', alertHtml);

            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }
            }, 5000);
        }
    </script>

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

        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--montink-primary), var(--montink-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 188, 212, 0.05);
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
    </style>
    <?php
    // Função de paginação igual produto
    if (!function_exists('build_pagination_url')) {
        function build_pagination_url($page, $filtros)
        {
            $params = array_filter([
                'page' => $page,
                'nome' => $filtros['nome'] ?? '',
                'email' => $filtros['email'] ?? '',
                'ativo' => $filtros['ativo'] ?? ''
            ]);
            return base_url('usuario?' . http_build_query($params));
        }
    }
    ?>