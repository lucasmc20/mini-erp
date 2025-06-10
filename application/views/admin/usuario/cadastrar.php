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
<body class="cadastro-page">
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
            <div class="cadastro-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="cadastro-title mb-2">
                            <i class="fas fa-user-plus me-3"></i>
                            <?= $titulo ?>
                        </h1>
                        <p class="cadastro-subtitle mb-0">
                            Preencha os dados abaixo para criar um novo usuário
                        </p>
                    </div>
                    <a href="<?= site_url('usuario') ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Formulário -->
            <div class="cadastro-form">
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

                <?= form_open('usuario/cadastrar', ['id' => 'formCadastro', 'novalidate' => 'novalidate']) ?>
                    
                    <!-- Dados Pessoais -->
                    <div class="mb-4">
                        <h5 class="text-montink mb-3">
                            <i class="fas fa-user me-2"></i>
                            Dados Pessoais
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome Completo *</label>
                                    <input type="text" 
                                           class="form-control <?= form_error('nome') ? 'is-invalid' : '' ?>" 
                                           id="nome" 
                                           name="nome" 
                                           value="<?= set_value('nome') ?>"
                                           placeholder="Digite o nome completo"
                                           required>
                                    <div class="invalid-feedback">
                                        <?= form_error('nome') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="tel" 
                                           class="form-control <?= form_error('telefone') ? 'is-invalid' : '' ?>" 
                                           id="telefone" 
                                           name="telefone" 
                                           value="<?= set_value('telefone') ?>"
                                           placeholder="(00) 00000-0000">
                                    <div class="invalid-feedback">
                                        <?= form_error('telefone') ?>
                                    </div>
                                </div>
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
                            <input type="email" 
                                   class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?= set_value('email') ?>"
                                   placeholder="Digite o e-mail"
                                   required>
                            <div class="invalid-feedback">
                                <?= form_error('email') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Senha *</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control <?= form_error('senha') ? 'is-invalid' : '' ?>" 
                                               id="senha" 
                                               name="senha" 
                                               placeholder="Mínimo 6 caracteres"
                                               required>
                                        <button class="password-toggle" type="button" id="toggleSenha">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?= form_error('senha') ?>
                                    </div>
                                    <!-- Medidor de força da senha -->
                                    <div class="strength-meter mt-2">
                                        <div class="strength-bar" id="strengthBar"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">Digite uma senha para ver a força</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmar_senha" class="form-label">Confirmar Senha *</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control <?= form_error('confirmar_senha') ? 'is-invalid' : '' ?>" 
                                               id="confirmar_senha" 
                                               name="confirmar_senha" 
                                               placeholder="Repita a senha"
                                               required>
                                        <button class="password-toggle" type="button" id="toggleConfirmarSenha">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?= form_error('confirmar_senha') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?= site_url('usuario') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-montink" id="btnSubmit">
                            <i class="fas fa-save me-2"></i>
                            Cadastrar Usuário
                        </button>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle de visualização de senha
        function togglePassword(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const icon = button.querySelector('i');
            
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

        // Máscara para telefone
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

        // Medidor de força da senha
        document.getElementById('senha').addEventListener('input', function() {
            const senha = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let text = '';
            let className = '';
            
            if (senha.length === 0) {
                text = 'Digite uma senha para ver a força';
                className = '';
            } else if (senha.length < 6) {
                strength = 1;
                text = 'Muito fraca';
                className = 'strength-weak';
            } else {
                strength = 1;
                
                // Verificações de força
                if (senha.length >= 8) strength++;
                if (/[a-z]/.test(senha) && /[A-Z]/.test(senha)) strength++;
                if (/\d/.test(senha)) strength++;
                if (/[^a-zA-Z0-9]/.test(senha)) strength++;
                
                switch (strength) {
                    case 1:
                    case 2:
                        text = 'Fraca';
                        className = 'strength-weak';
                        break;
                    case 3:
                        text = '