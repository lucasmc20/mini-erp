<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Montink</title>
    <?php echo load_bootstrap_css(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">
</head>

<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-9">
                <div class="login-container">
                    <div class="login-header">
                        <img class="logo" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Montink Logo">
                        <h1 class="login-title">Bem-vindo!</h1>
                        <p class="login-subtitle">Acesse sua conta Montink</p>
                    </div>

                    <div class="login-form">
                        <?php
                        // Exibir mensagens de erro
                        if (isset($erro)) {
                            echo bootstrap_alert($erro, 'danger');
                        }
                        
                        // Exibir mensagens de sucesso
                        if ($this->session->flashdata('sucesso')) {
                            echo bootstrap_alert($this->session->flashdata('sucesso'), 'success');
                        }
                        
                        // Exibir mensagens informativas
                        if ($this->session->flashdata('info')) {
                            echo bootstrap_alert($this->session->flashdata('info'), 'info');
                        }
                        ?>

                        <?php echo form_open('auth/login', ['class' => 'needs-validation', 'novalidate' => '', 'id' => 'loginForm']); ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-2"></i>E-mail
                            </label>
                            <input type="email" 
                                   class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Digite seu e-mail"
                                   value="<?php echo isset($email_lembrado) ? $email_lembrado : set_value('email'); ?>">
                            <div class="invalid-feedback" style="display: none;">
                                Por favor, insira um e-mail válido.
                            </div>
                            <?php if (form_error('email')): ?>
                                <small class="text-danger d-block"><?php echo form_error('email', '', ''); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">
                                <i class="bi bi-lock me-2"></i>Senha
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control <?php echo form_error('senha') ? 'is-invalid' : ''; ?>" 
                                       id="senha" 
                                       name="senha" 
                                       placeholder="Digite sua senha">
                                <button class="btn password-toggle" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" style="display: none;">
                                A senha deve ter pelo menos 6 caracteres.
                            </div>
                            <?php if (form_error('senha')): ?>
                                <small class="text-danger d-block"><?php echo form_error('senha', '', ''); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="row mb-4">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="lembrar" 
                                           name="lembrar" 
                                           value="1"
                                           <?php echo !empty($email_lembrado) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="lembrar">
                                        Lembrar-me
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="col text-end">
                                <a href="<?php echo base_url('auth/esqueci_senha'); ?>" class="text-montink text-decoration-none">
                                    Esqueci minha senha
                                </a>
                            </div> -->
                        </div>

                        <button type="submit" class="btn btn-montink w-100 mb-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Entrar na Montink
                        </button>
                        
                        <?php echo form_close(); ?>

                        <div class="text-center">
                            <p class="mb-0">Não tem uma conta?
                                <a href="<?php echo base_url('auth/cadastro'); ?>" class="text-montink text-decoration-none fw-bold">
                                    Cadastre-se aqui
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
        function togglePassword() {
            const senha = document.getElementById('senha');
            const icon = document.getElementById('toggleIcon');

            if (senha.type === 'password') {
                senha.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                senha.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Validação personalizada do login
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            let formSubmitted = false;

            // Verificar se há erros do servidor
            const hasServerErrors = document.querySelector('.text-danger');
            if (hasServerErrors) {
                formSubmitted = true;
                addRealTimeValidation();
            }

            // Interceptar envio do formulário
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                let isValid = true;
                
                // Validar email
                const email = emailInput.value.trim();
                if (!email) {
                    showFieldError(emailInput, 'E-mail é obrigatório.');
                    isValid = false;
                } else if (!isValidEmail(email)) {
                    showFieldError(emailInput, 'Por favor, insira um e-mail válido.');
                    isValid = false;
                } else {
                    hideFieldError(emailInput);
                }
                
                // Validar senha
                const senha = senhaInput.value;
                if (!senha) {
                    showFieldError(senhaInput, 'Senha é obrigatória.');
                    isValid = false;
                } else if (senha.length < 6) {
                    showFieldError(senhaInput, 'A senha deve ter pelo menos 6 caracteres.');
                    isValid = false;
                } else {
                    hideFieldError(senhaInput);
                }
                
                if (isValid) {
                    // Remove interceptação e envia o formulário
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                } else {
                    if (!formSubmitted) {
                        formSubmitted = true;
                        addRealTimeValidation();
                    }
                }
            });

            function addRealTimeValidation() {
                emailInput.addEventListener('input', validateEmail);
                emailInput.addEventListener('blur', validateEmail);
                senhaInput.addEventListener('input', validateSenha);
                senhaInput.addEventListener('blur', validateSenha);
            }

            function validateEmail() {
                const email = emailInput.value.trim();
                if (formSubmitted) {
                    if (!email) {
                        showFieldError(emailInput, 'E-mail é obrigatório.');
                    } else if (!isValidEmail(email)) {
                        showFieldError(emailInput, 'Por favor, insira um e-mail válido.');
                    } else {
                        hideFieldError(emailInput);
                    }
                }
            }

            function validateSenha() {
                const senha = senhaInput.value;
                if (formSubmitted) {
                    if (!senha) {
                        showFieldError(senhaInput, 'Senha é obrigatória.');
                    } else if (senha.length < 6) {
                        showFieldError(senhaInput, 'A senha deve ter pelo menos 6 caracteres.');
                    } else {
                        hideFieldError(senhaInput);
                    }
                }
            }

            function showFieldError(field, message) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                const feedback = field.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.innerHTML = message;
                    feedback.style.display = 'block';
                }
            }

            function hideFieldError(field) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                const feedback = field.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.style.display = 'none';
                }
            }

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Focus inicial
            emailInput.focus();
        });

        // Auto-dismiss alerts após 5 segundos
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert:not(.alert-danger)');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    alert.querySelector('.btn-close').click();
                }
            });
        }, 5000);

        // Animação de entrada suave
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.login-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(function() {
                container.style.transition = 'all 0.6s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>

</html>