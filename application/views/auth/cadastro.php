<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Montink</title>
    <?php echo load_bootstrap_css(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        :root {
            --montink-primary: #1a1a1a;
            --montink-secondary: #00bcd4;
            --montink-accent: #4dd0e1;
            --montink-light: #f8f9fa;
            --montink-gradient: linear-gradient(135deg, #1a1a1a 0%, #333333 50%, #00bcd4 100%);
        }

        body {
            background: var(--montink-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }

        .cadastro-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(0, 188, 212, 0.1);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .cadastro-header {
            background: linear-gradient(45deg, var(--montink-primary), var(--montink-secondary));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .cadastro-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .logo {
            max-width: 100px;
            height: auto;
            filter: brightness(0) invert(1);
            position: relative;
            z-index: 2;
        }

        .cadastro-title {
            margin: 1rem 0 0.5rem;
            font-weight: 700;
            font-size: 1.8rem;
            position: relative;
            z-index: 2;
        }

        .cadastro-subtitle {
            opacity: 0.9;
            font-size: 1rem;
            position: relative;
            z-index: 2;
        }

        .cadastro-form {
            padding: 2.5rem;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--montink-primary);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control:focus {
            border-color: var(--montink-secondary);
            box-shadow: 0 0 0 0.25rem rgba(0, 188, 212, 0.15);
            background-color: white;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-valid {
            border-color: #28a745;
        }

        .input-group .form-control {
            border-radius: 12px 0 0 12px;
        }

        .password-toggle {
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 12px 12px 0;
            background-color: #fafafa;
            transition: all 0.3s ease;
            padding: 1rem;
        }

        .password-toggle:hover {
            background-color: var(--montink-secondary);
            border-color: var(--montink-secondary);
            color: white;
        }

        .btn-montink {
            background: linear-gradient(45deg, var(--montink-primary), var(--montink-secondary));
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-montink:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3);
            color: white;
        }

        .btn-montink::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-montink:hover::before {
            left: 100%;
        }

        .text-montink {
            color: var(--montink-secondary);
        }

        .text-montink:hover {
            color: var(--montink-primary);
            text-decoration: none;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(45deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(45deg, #fff3cd, #ffeaa7);
            color: #856404;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(0, 188, 212, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 10%;
            left: 20%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 40%;
            right: 20%;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .strength-meter {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 3px;
        }

        .strength-weak { background-color: #dc3545; width: 25%; }
        .strength-fair { background-color: #ffc107; width: 50%; }
        .strength-good { background-color: #fd7e14; width: 75%; }
        .strength-strong { background-color: #28a745; width: 100%; }

        .row {
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .col-md-6 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        @media (max-width: 768px) {
            .cadastro-form {
                padding: 1.5rem;
            }
            
            .cadastro-header {
                padding: 1.5rem;
            }

            .cadastro-container {
                margin: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="cadastro-container">
                    <div class="cadastro-header">
                        <img class="logo" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Montink Logo">
                        <h1 class="cadastro-title">Criar Conta</h1>
                        <p class="cadastro-subtitle">Junte-se à comunidade Montink</p>
                    </div>

                    <div class="cadastro-form">
                        <?php
                        // Exibir mensagens de erro
                        if (isset($erro)) {
                            echo bootstrap_alert($erro, 'danger');
                        }
                        
                        // Exibir mensagens de sucesso
                        if ($this->session->flashdata('sucesso')) {
                            echo bootstrap_alert($this->session->flashdata('sucesso'), 'success');
                        }
                        ?>

                        <?php echo form_open('auth/cadastro', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">
                                        <i class="bi bi-person me-2"></i>Nome Completo
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php echo form_error('nome') ? 'is-invalid' : ''; ?>" 
                                           id="nome" 
                                           name="nome" 
                                           placeholder="Digite seu nome completo"
                                           value="<?php echo set_value('nome'); ?>"
                                           required>
                                    <div class="invalid-feedback">
                                        <?php echo form_error('nome') ? form_error('nome', '', '') : 'Nome é obrigatório (mínimo 3 caracteres).'; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-2"></i>E-mail
                                    </label>
                                    <input type="email" 
                                           class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Digite seu e-mail"
                                           value="<?php echo set_value('email'); ?>"
                                           required>
                                    <div class="invalid-feedback">
                                        <?php echo form_error('email') ? form_error('email', '', '') : 'E-mail é obrigatório e deve ser válido.'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">
                                        <i class="bi bi-telephone me-2"></i>Telefone
                                    </label>
                                    <input type="tel" 
                                           class="form-control <?php echo form_error('telefone') ? 'is-invalid' : ''; ?>" 
                                           id="telefone" 
                                           name="telefone" 
                                           placeholder="(11) 99999-9999"
                                           value="<?php echo set_value('telefone'); ?>">
                                    <div class="invalid-feedback">
                                        <?php echo form_error('telefone'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">
                                        <i class="bi bi-card-text me-2"></i>CPF <small class="text-muted">(opcional)</small>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php echo form_error('cpf') ? 'is-invalid' : ''; ?>" 
                                           id="cpf" 
                                           name="cpf" 
                                           placeholder="000.000.000-00"
                                           value="<?php echo set_value('cpf'); ?>">
                                    <div class="invalid-feedback">
                                        <?php echo form_error('cpf'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="senha" class="form-label">
                                        <i class="bi bi-lock me-2"></i>Senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control <?php echo form_error('senha') ? 'is-invalid' : ''; ?>" 
                                               id="senha" 
                                               name="senha" 
                                               placeholder="Digite sua senha"
                                               required>
                                        <button class="btn password-toggle" type="button" onclick="togglePassword('senha', 'toggleIcon1')">
                                            <i class="bi bi-eye" id="toggleIcon1"></i>
                                        </button>
                                    </div>
                                    <div class="strength-meter">
                                        <div class="strength-bar" id="strengthBar"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">Força da senha</small>
                                    <div class="invalid-feedback">
                                        <?php echo form_error('senha') ? form_error('senha', '', '') : 'Senha deve ter pelo menos 6 caracteres.'; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmar_senha" class="form-label">
                                        <i class="bi bi-lock-fill me-2"></i>Confirmar Senha
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control <?php echo form_error('confirmar_senha') ? 'is-invalid' : ''; ?>" 
                                               id="confirmar_senha" 
                                               name="confirmar_senha" 
                                               placeholder="Confirme sua senha">
                                        <button class="btn password-toggle" type="button" onclick="togglePassword('confirmar_senha', 'toggleIcon2')">
                                            <i class="bi bi-eye" id="toggleIcon2"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?php echo form_error('confirmar_senha') ? form_error('confirmar_senha', '', '') : 'As senhas devem ser iguais.'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="termos" 
                                       name="termos" 
                                       value="1">
                                <label class="form-check-label" for="termos">
                                    Concordo com os 
                                    <a href="https://sou.montink.com/termos-e-condicoes" class="text-montink">Termos de Uso</a> 
                                    e 
                                    <a href="https://sou.montink.com/politica-de-privacidade" class="text-montink">Política de Privacidade</a>
                                </label>
                                <div class="invalid-feedback">
                                    Você deve concordar com os termos para continuar.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-montink w-100 mb-4">
                            <i class="bi bi-person-plus me-2"></i>
                            Criar Conta Montink
                        </button>
                        
                        <?php echo form_close(); ?>

                        <div class="text-center">
                            <p class="mb-0">Já tem uma conta?
                                <a href="<?php echo base_url('auth/login'); ?>" class="text-montink text-decoration-none fw-bold">
                                    Faça login aqui
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo load_bootstrap_js(); ?>
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Verificador de força da senha
        function checkPasswordStrength(password) {
            let strength = 0;
            let text = '';
            let className = '';

            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    text = 'Muito fraca';
                    className = 'strength-weak';
                    break;
                case 2:
                    text = 'Fraca';
                    className = 'strength-weak';
                    break;
                case 3:
                    text = 'Razoável';
                    className = 'strength-fair';
                    break;
                case 4:
                    text = 'Boa';
                    className = 'strength-good';
                    break;
                case 5:
                    text = 'Forte';
                    className = 'strength-strong';
                    break;
            }

            return { strength, text, className };
        }

        // Aplicar máscara no telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                e.target.value = value;
            }
        });

        // Aplicar máscara no CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                e.target.value = value;
            }
        });

        // Verificar força da senha em tempo real
        document.getElementById('senha').addEventListener('input', function(e) {
            const password = e.target.value;
            const result = checkPasswordStrength(password);
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            strengthBar.className = 'strength-bar ' + result.className;
            strengthText.textContent = result.text;
        });

        // Verificar se as senhas coincidem - sem mostrar erro imediatamente
        function checkPasswordMatch() {
            const senha = document.getElementById('senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;
            const confirmarSenhaInput = document.getElementById('confirmar_senha');
            const form = document.querySelector('.needs-validation');

            // Só verificar se o formulário já foi submetido
            if (form.classList.contains('was-validated')) {
                if (confirmarSenha && senha !== confirmarSenha) {
                    confirmarSenhaInput.setCustomValidity('As senhas não coincidem');
                } else if (confirmarSenha) {
                    confirmarSenhaInput.setCustomValidity('');
                }
            }
        }

        // Adicionar validação em tempo real apenas após primeira tentativa
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            let formSubmitted = false;

            // Verificar se há erros do servidor (significa que form foi enviado)
            if (document.querySelector('.is-invalid')) {
                formSubmitted = true;
                form.classList.add('was-validated');
            }

            // Adicionar listeners apenas após primeira submissão
            form.addEventListener('submit', function() {
                if (!formSubmitted) {
                    formSubmitted = true;
                    addRealTimeValidation();
                }
            });

            function addRealTimeValidation() {
                // Validação em tempo real para senhas
                document.getElementById('senha').addEventListener('input', checkPasswordMatch);
                document.getElementById('confirmar_senha').addEventListener('input', checkPasswordMatch);
                
                // Validação do checkbox de termos
                document.getElementById('termos').addEventListener('change', function() {
                    if (this.checked) {
                        this.setCustomValidity('');
                    } else {
                        this.setCustomValidity('Você deve aceitar os termos');
                    }
                });
            }

            // Se já foi submetido, ativar validação em tempo real
            if (formSubmitted) {
                addRealTimeValidation();
            }
        });

        // Validação do Bootstrap - melhorada
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        // Verificar validade personalizada
                        const senha = document.getElementById('senha').value;
                        const confirmarSenha = document.getElementById('confirmar_senha').value;
                        const termos = document.getElementById('termos').checked;
                        
                        let isValid = form.checkValidity();
                        
                        // Validação personalizada de senhas
                        if (senha !== confirmarSenha) {
                            document.getElementById('confirmar_senha').setCustomValidity('As senhas não coincidem');
                            isValid = false;
                        } else {
                            document.getElementById('confirmar_senha').setCustomValidity('');
                        }
                        
                        // Validação de termos
                        if (!termos) {
                            document.getElementById('termos').setCustomValidity('Você deve aceitar os termos');
                            isValid = false;
                        } else {
                            document.getElementById('termos').setCustomValidity('');
                        }
                        
                        if (!isValid) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Auto-dismiss alerts após 5 segundos
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert:not(.alert-danger)');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    alert.querySelector('.btn-close').click();
                }
            });
        }, 5000);

        // Focus no primeiro campo com erro
        document.addEventListener('DOMContentLoaded', function() {
            var firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.focus();
            } else {
                document.getElementById('nome').focus();
            }
        });

        // Animação de entrada suave
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.cadastro-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.6s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>

</html>