<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> - Sistema Montink</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/montink-style.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Formas flutuantes decorativas -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container my-5">
        <div class="cadastro-container">
            <!-- Header -->
            <div class="cadastro-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="cadastro-title mb-2">
                            <i class="fas fa-users me-3"></i>
                            <?= $titulo ?>
                        </h1>
                        <p class="cadastro-subtitle mb-0">
                            Gerencie os usuários do sistema • Total: <?= $total_usuarios ?> usuários
                        </p>
                    </div>
                    <a href="<?= site_url('usuario/cadastrar') ?>" class="btn btn-montink">
                        <i class="fas fa-plus me-2"></i>
                        Novo Usuário
                    </a>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="cadastro-form">
                <!-- Alertas -->
                <?php if ($this->session->flashdata('sucesso')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $this->session->flashdata('sucesso') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('erro')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= $this->session->flashdata('erro') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Filtros e Busca -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar por nome ou e-mail...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-2"></i>
                                Filtros
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-filter="todos">Todos os usuários</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="ativos">Apenas ativos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" data-filter="recentes">Cadastrados recentemente</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Usuários -->
                <?php if (!empty($usuarios)): ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabelaUsuarios">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Nome</th>
                                <th><i class="fas fa-envelope me-2"></i>E-mail</th>
                                <th><i class="fas fa-phone me-2"></i>Telefone</th>
                                <th><i class="fas fa-calendar me-2"></i>Cadastro</th>
                                <th class="text-center"><i class="fas fa-cogs me-2"></i>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr data-usuario-id="<?= $usuario->id ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <?= strtoupper(substr($usuario->nome, 0, 2)) ?>
                                        </div>
                                        <strong><?= htmlspecialchars($usuario->nome) ?></strong>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($usuario->email) ?></td>
                                <td><?= !empty($usuario->telefone) ? htmlspecialchars($usuario->telefone) : '<span class="text-muted">Não informado</span>' ?></td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($usuario->data_cadastro)) ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('usuario/visualizar/' . $usuario->id) ?>" 
                                           class="btn btn-outline-info btn-sm" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('usuario/editar/' . $usuario->id) ?>" 
                                           class="btn btn-outline-primary btn-sm" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                title="Excluir"
                                                onclick="confirmarExclusao(<?= $usuario->id ?>, '<?= htmlspecialchars($usuario->nome) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <!-- Estado vazio -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-users fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Nenhum usuário encontrado</h4>
                    <p class="text-muted mb-4">Comece adicionando o primeiro usuário ao sistema.</p>
                    <a href="<?= site_url('usuario/cadastrar') ?>" class="btn btn-montink">
                        <i class="fas fa-plus me-2"></i>
                        Cadastrar Primeiro Usuário
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="modalExcluir" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir o usuário <strong id="nomeUsuario"></strong>?</p>
                    <p class="text-muted small">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" class="btn btn-danger" id="btnConfirmarExclusao">
                        <i class="fas fa-trash me-2"></i>
                        Excluir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Busca em tempo real
        document.getElementById('buscarUsuario').addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            const linhas = document.querySelectorAll('#tabelaUsuarios tbody tr');
            
            linhas.forEach(linha => {
                const nome = linha.cells[0].textContent.toLowerCase();
                const email = linha.cells[1].textContent.toLowerCase();
                
                if (nome.includes(termo) || email.includes(termo)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        });

        // Função para confirmar exclusão
        function confirmarExclusao(id, nome) {
            document.getElementById('nomeUsuario').textContent = nome;
            document.getElementById('btnConfirmarExclusao').href = '<?= site_url('usuario/excluir/') ?>' + id;
            new bootstrap.Modal(document.getElementById('modalExcluir')).show();
        }

        // Auto-dismiss de alertas
        setTimeout(() => {
            const alertas = document.querySelectorAll('.alert');
            alertas.forEach(alerta => {
                if (alerta.classList.contains('show')) {
                    bootstrap.Alert.getOrCreateInstance(alerta).close();
                }
            });
        }, 5000);
    </script>

    <style>
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
            margin: 0 2px;
        }
    </style>
</body>
</html>