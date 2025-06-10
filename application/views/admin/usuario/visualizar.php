<div class="container-fluid px-0">
    <!-- Card de informações -->
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-header bg-montink text-white d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-user me-2"></i><?= $titulo ?></h4>
                        <small>Visualizando cadastro do usuário</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('usuario/editar/' . $usuario->id) ?>" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <a href="<?= site_url('usuario') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="avatar-circle big">
                                <?= strtoupper(substr($usuario->nome, 0, 2)) ?>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-1 fw-bold"><?= htmlspecialchars($usuario->nome) ?></h5>
                            <span class="badge <?= $usuario->ativo ? 'bg-success' : 'bg-danger' ?>">
                                <?= $usuario->ativo ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-envelope me-2 text-gray-400"></i>
                                <strong>E-mail:</strong><br>
                                <?= htmlspecialchars($usuario->email) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-phone me-2 text-gray-400"></i>
                                <strong>Telefone:</strong><br>
                                <?= $usuario->telefone ? htmlspecialchars($usuario->telefone) : '<span class="text-muted">Não informado</span>' ?>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-calendar-plus me-2 text-gray-400"></i>
                                <strong>Data de Cadastro:</strong><br>
                                <?= date('d/m/Y H:i', strtotime($usuario->data_cadastro)) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fas fa-clock me-2 text-gray-400"></i>
                                <strong>Última Atualização:</strong><br>
                                <?= $usuario->data_atualizacao ? date('d/m/Y H:i', strtotime($usuario->data_atualizacao)) : 'Nunca atualizado' ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="<?= site_url('usuario') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                    <div>
                        <a href="<?= site_url('usuario/editar/' . $usuario->id) ?>" class="btn btn-outline-montink me-2">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <?php if ($usuario->ativo): ?>
                        <button type="button"
                                class="btn btn-outline-danger"
                                onclick="confirmarExclusaoUsuario('<?= $usuario->id ?>','<?= htmlspecialchars($usuario->nome) ?>')">
                            <i class="fas fa-trash me-1"></i> Remover
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modalExcluirUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Remoção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover o usuário <strong id="nomeExcluirUsuario"></strong>?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" class="btn btn-danger" id="btnConfirmarExcluirUsuario">
                    <i class="fas fa-trash me-2"></i>Remover
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle.big {
        width: 70px;
        height: 70px;
        font-size: 2rem;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--montink-primary), var(--montink-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        margin-right: 10px;
    }
    .text-gray-400 { color: #c8c9ca !important; }
    .gap-2 { gap: .5rem !important; }
</style>
<script>
    // Exibir modal de confirmação de exclusão
    function confirmarExclusaoUsuario(id, nome) {
        document.getElementById('nomeExcluirUsuario').textContent = nome;
        document.getElementById('btnConfirmarExcluirUsuario').href = '<?= site_url('usuario/excluir/') ?>' + id;
        new bootstrap.Modal(document.getElementById('modalExcluirUsuario')).show();
    }
</script>
