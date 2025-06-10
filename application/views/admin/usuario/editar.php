<!-- Formas flutuantes decorativas -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container my-4">
        <div class="cadastro-container">
            <!-- Header -->
            <div class="cadastro-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="cadastro-title mb-2">
                        <i class="fas fa-user-edit me-3"></i>
                        <?= $titulo ?>
                    </h1>
                    <p class="cadastro-subtitle mb-0">
                        Editando usuário: <strong><?= htmlspecialchars($usuario->nome) ?></strong>
                    </p>
                </div>
                <a href="<?= site_url('usuario') ?>" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>

            <div class="cadastro-form">
                <!-- Alertas -->
                <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erro de validação:</strong>
                    <?= validation_errors() ?>
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

                <?= form_open('usuario/editar/' . $usuario->id, ['id' => 'formEditar', 'novalidate' => 'novalidate']) ?>

                <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="text-montink mb-3">
                        <i class="fas fa-user me-2"></i>
                        Dados Pessoais
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control <?= form_error('nome') ? 'is-invalid' : '' ?>"
                                   id="nome" name="nome" value="<?= set_value('nome', $usuario->nome) ?>"
                                   placeholder="Digite o nome completo" required>
                            <div class="invalid-feedback"><?= form_error('nome') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control <?= form_error('telefone') ? 'is-invalid' : '' ?>"
                                   id="telefone" name="telefone" value="<?= set_value('telefone', $usuario->telefone) ?>"
                                   placeholder="(00) 00000-0000">
                            <div class="invalid-feedback"><?= form_error('telefone') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Dados de Acesso -->
                <div class="mb-4">
                    <h5 class="text-montink mb-3">
                        <i class="fas fa-key me-2"></i>
                        Dados de Acesso
                    </h5>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail *</label>
                        <input type="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>"
                               id="email" name="email" value="<?= set_value('email', $usuario->email) ?>"
                               placeholder="Digite o e-mail" required>
                        <div class="invalid-feedback"><?= form_error('email') ?></div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Alterar Senha:</strong> Deixe os campos em branco se não quiser alterar a senha atual.
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?= form_error('senha') ? 'is-invalid' : '' ?>"
                                       id="senha" name="senha" placeholder="Mínimo 6 caracteres">
                                <button class="password-toggle" type="button" id="toggleSenha">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"><?= form_error('senha') ?></div>
                            <div class="strength-meter mt-2" style="display:none;" id="strengthMeter">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <small class="text-muted" id="strengthText" style="display:none;">Digite uma senha para ver a força</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?= form_error('confirmar_senha') ? 'is-invalid' : '' ?>"
                                       id="confirmar_senha" name="confirmar_senha" placeholder="Repita a nova senha">
                                <button class="password-toggle" type="button" id="toggleConfirmarSenha">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"><?= form_error('confirmar_senha') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="mb-4">
                    <h5 class="text-montink mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informações do Sistema
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data de Cadastro</label>
                            <input type="text" class="form-control"
                                   value="<?= date('d/m/Y H:i', strtotime($usuario->data_cadastro)) ?>"
                                   readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Última Atualização</label>
                            <input type="text" class="form-control"
                                   value="<?= $usuario->data_atualizacao ? date('d/m/Y H:i', strtotime($usuario->data_atualizacao)) : 'Nunca atualizado' ?>"
                                   readonly>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex gap-3 justify-content-between">
                    <div>
                        <a href="<?= site_url('usuario/visualizar/' . $usuario->id) ?>" class="btn btn-outline-info">
                            <i class="fas fa-eye me-2"></i>Visualizar
                        </a>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="<?= site_url('usuario') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-montink" id="btnSubmit">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle de senha
    function togglePassword(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(buttonId).querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    document.getElementById('toggleSenha').addEventListener('click', () => {
        togglePassword('senha', 'toggleSenha');
    });
    document.getElementById('toggleConfirmarSenha').addEventListener('click', () => {
        togglePassword('confirmar_senha', 'toggleConfirmarSenha');
    });

    // Máscara telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            value = value.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
            value = value.replace(/^(\d{2})(\d{1,5})/, '($1) $2');
            value = value.replace(/^(\d{2})/, '($1');
        }
        e.target.value = value;
    });

    // Força senha
    const senhaInput = document.getElementById('senha');
    const strengthMeter = document.getElementById('strengthMeter');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    senhaInput.addEventListener('input', function() {
        const senha = this.value;
        if (!senha) { strengthMeter.style.display = 'none'; strengthText.style.display = 'none'; return;}
        strengthMeter.style.display = 'block'; strengthText.style.display = 'block';
        let strength = 0;
        if (senha.length < 6) { strength = 1;}
        else {
            strength = 1;
            if (senha.length >= 8) strength++;
            if (/[a-z]/.test(senha) && /[A-Z]/.test(senha)) strength++;
            if (/\d/.test(senha)) strength++;
            if (/[^a-zA-Z0-9]/.test(senha)) strength++;
        }
        let text = '', className = '';
        switch (strength) {
            case 1: case 2: text='Fraca'; className='strength-weak'; break;
            case 3: text='Razoável'; className='strength-fair'; break;
            case 4: text='Boa'; className='strength-good'; break;
            case 5: text='Forte'; className='strength-strong'; break;
        }
        strengthBar.className = 'strength-bar '+className;
        strengthText.textContent = text;
    });

    // Confirmação senha
    document.getElementById('confirmar_senha').addEventListener('input', function() {
        const senha = document.getElementById('senha').value;
        const confirmar = this.value;
        if (confirmar && senha !== confirmar) {
            this.classList.add('is-invalid'); this.classList.remove('is-valid');
        } else if (confirmar) {
            this.classList.remove('is-invalid'); this.classList.add('is-valid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    // Email validação visual
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (email) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Loading no submit
    document.getElementById('formEditar').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.classList.add('loading'); btn.disabled = true;
    });

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
