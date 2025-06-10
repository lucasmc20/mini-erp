<div class="row">
    <div class="col-xl-8 col-lg-9 mx-auto">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>
                    Informações do Perfil
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open('dashboard/perfil', ['class' => 'needs-validation', 'novalidate' => '']); ?>

                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="profile-image-container mb-3">
                            <div class="profile-image">
                                <i class="fas fa-user-circle fa-6x text-muted"></i>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-montink mt-2">
                                <i class="fas fa-camera me-1"></i>
                                Alterar Foto
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text"
                                       class="form-control <?php echo form_error('nome') ? 'is-invalid' : ''; ?>"
                                       id="nome"
                                       name="nome"
                                       value="<?php echo set_value('nome', isset($usuario->nome) ? $usuario->nome : ''); ?>"
                                       required>
                                <?php if (form_error('nome')): ?>
                                    <div class="invalid-feedback">
                                        <?php echo form_error('nome'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       value="<?php echo isset($usuario->email) ? $usuario->email : ''; ?>"
                                       readonly>
                                <div class="form-text">O e-mail não pode ser alterado</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel"
                                       class="form-control"
                                       id="telefone"
                                       name="telefone"
                                       value="<?php echo set_value('telefone', isset($usuario->telefone) ? $usuario->telefone : ''); ?>"
                                       placeholder="(00) 00000-0000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dados Pessoais -->
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-id-card me-2"></i>
                    Dados Pessoais
                </h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text"
                               class="form-control"
                               id="cpf"
                               name="cpf"
                               value="<?php echo set_value('cpf', isset($usuario->cpf) ? $usuario->cpf : ''); ?>"
                               placeholder="000.000.000-00">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date"
                               class="form-control"
                               id="data_nascimento"
                               name="data_nascimento"
                               value="<?php echo set_value('data_nascimento', isset($usuario->data_nascimento) ? $usuario->data_nascimento : ''); ?>">
                    </div>
                </div>

                <!-- Endereço -->
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Endereço
                </h6>

                <div class="row">
                    <!-- CEP em destaque -->
                    <div class="col-md-4 mb-3">
                        <label for="cep" class="form-label">
                            CEP *
                            <i class="fas fa-search text-muted ms-1" title="Busca automática"></i>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="cep"
                               name="cep"
                               value="<?php echo set_value('cep', isset($usuario->cep) ? $usuario->cep : ''); ?>"
                               placeholder="00000-000"
                               maxlength="9">
                    </div>

                    <!-- Logradouro -->
                    <div class="col-md-8 mb-3">
                        <label for="endereco" class="form-label">Logradouro</label>
                        <input type="text"
                               class="form-control"
                               id="endereco"
                               name="endereco"
                               value="<?php echo set_value('endereco', isset($usuario->endereco) ? $usuario->endereco : ''); ?>"
                               placeholder="Rua, Avenida, etc.">
                    </div>

                    <!-- Número e Complemento -->
                    <div class="col-md-3 mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text"
                               class="form-control"
                               id="numero"
                               name="numero"
                               value="<?php echo set_value('numero', isset($usuario->numero) ? $usuario->numero : ''); ?>"
                               placeholder="123">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text"
                               class="form-control"
                               id="complemento"
                               name="complemento"
                               value="<?php echo set_value('complemento', isset($usuario->complemento) ? $usuario->complemento : ''); ?>"
                               placeholder="Apto, Bloco, Casa, etc.">
                    </div>

                    <!-- Bairro -->
                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text"
                               class="form-control"
                               id="bairro"
                               name="bairro"
                               value="<?php echo set_value('bairro', isset($usuario->bairro) ? $usuario->bairro : ''); ?>"
                               placeholder="Nome do bairro">
                    </div>

                    <!-- Cidade -->
                    <div class="col-md-6 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text"
                               class="form-control"
                               id="cidade"
                               name="cidade"
                               value="<?php echo set_value('cidade', isset($usuario->cidade) ? $usuario->cidade : ''); ?>"
                               placeholder="Nome da cidade">
                    </div>

                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Selecione o estado...</option>
                            <option value="AC" <?php echo set_select('estado', 'AC', isset($usuario->estado) && $usuario->estado == 'AC'); ?>>Acre</option>
                            <option value="AL" <?php echo set_select('estado', 'AL', isset($usuario->estado) && $usuario->estado == 'AL'); ?>>Alagoas</option>
                            <option value="AP" <?php echo set_select('estado', 'AP', isset($usuario->estado) && $usuario->estado == 'AP'); ?>>Amapá</option>
                            <option value="AM" <?php echo set_select('estado', 'AM', isset($usuario->estado) && $usuario->estado == 'AM'); ?>>Amazonas</option>
                            <option value="BA" <?php echo set_select('estado', 'BA', isset($usuario->estado) && $usuario->estado == 'BA'); ?>>Bahia</option>
                            <option value="CE" <?php echo set_select('estado', 'CE', isset($usuario->estado) && $usuario->estado == 'CE'); ?>>Ceará</option>
                            <option value="DF" <?php echo set_select('estado', 'DF', isset($usuario->estado) && $usuario->estado == 'DF'); ?>>Distrito Federal</option>
                            <option value="ES" <?php echo set_select('estado', 'ES', isset($usuario->estado) && $usuario->estado == 'ES'); ?>>Espírito Santo</option>
                            <option value="GO" <?php echo set_select('estado', 'GO', isset($usuario->estado) && $usuario->estado == 'GO'); ?>>Goiás</option>
                            <option value="MA" <?php echo set_select('estado', 'MA', isset($usuario->estado) && $usuario->estado == 'MA'); ?>>Maranhão</option>
                            <option value="MT" <?php echo set_select('estado', 'MT', isset($usuario->estado) && $usuario->estado == 'MT'); ?>>Mato Grosso</option>
                            <option value="MS" <?php echo set_select('estado', 'MS', isset($usuario->estado) && $usuario->estado == 'MS'); ?>>Mato Grosso do Sul</option>
                            <option value="MG" <?php echo set_select('estado', 'MG', isset($usuario->estado) && $usuario->estado == 'MG'); ?>>Minas Gerais</option>
                            <option value="PA" <?php echo set_select('estado', 'PA', isset($usuario->estado) && $usuario->estado == 'PA'); ?>>Pará</option>
                            <option value="PB" <?php echo set_select('estado', 'PB', isset($usuario->estado) && $usuario->estado == 'PB'); ?>>Paraíba</option>
                            <option value="PR" <?php echo set_select('estado', 'PR', isset($usuario->estado) && $usuario->estado == 'PR'); ?>>Paraná</option>
                            <option value="PE" <?php echo set_select('estado', 'PE', isset($usuario->estado) && $usuario->estado == 'PE'); ?>>Pernambuco</option>
                            <option value="PI" <?php echo set_select('estado', 'PI', isset($usuario->estado) && $usuario->estado == 'PI'); ?>>Piauí</option>
                            <option value="RJ" <?php echo set_select('estado', 'RJ', isset($usuario->estado) && $usuario->estado == 'RJ'); ?>>Rio de Janeiro</option>
                            <option value="RN" <?php echo set_select('estado', 'RN', isset($usuario->estado) && $usuario->estado == 'RN'); ?>>Rio Grande do Norte</option>
                            <option value="RS" <?php echo set_select('estado', 'RS', isset($usuario->estado) && $usuario->estado == 'RS'); ?>>Rio Grande do Sul</option>
                            <option value="RO" <?php echo set_select('estado', 'RO', isset($usuario->estado) && $usuario->estado == 'RO'); ?>>Rondônia</option>
                            <option value="RR" <?php echo set_select('estado', 'RR', isset($usuario->estado) && $usuario->estado == 'RR'); ?>>Roraima</option>
                            <option value="SC" <?php echo set_select('estado', 'SC', isset($usuario->estado) && $usuario->estado == 'SC'); ?>>Santa Catarina</option>
                            <option value="SP" <?php echo set_select('estado', 'SP', isset($usuario->estado) && $usuario->estado == 'SP'); ?>>São Paulo</option>
                            <option value="SE" <?php echo set_select('estado', 'SE', isset($usuario->estado) && $usuario->estado == 'SE'); ?>>Sergipe</option>
                            <option value="TO" <?php echo set_select('estado', 'TO', isset($usuario->estado) && $usuario->estado == 'TO'); ?>>Tocantins</option>
                        </select>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar
                    </a>

                    <div>
                        <button type="button" class="btn btn-outline-warning me-2" data-bs-toggle="modal" data-bs-target="#modalAlterarSenha">
                            <i class="fas fa-key me-2"></i>
                            Alterar Senha
                        </button>

                        <button type="submit" class="btn btn-montink">
                            <i class="fas fa-save me-2"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar Senha -->
<div class="modal fade" id="modalAlterarSenha" tabindex="-1" aria-labelledby="modalAlterarSenhaLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAlterarSenhaLabel">
                    <i class="fas fa-key me-2"></i>
                    Alterar Senha
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formAlterarSenha" method="post" action="alterar_senha">
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Por segurança, você precisará informar sua senha atual.</small>
                    </div>

                    <div class="mb-3">
                        <label for="senha_atual" class="form-label">
                            <i class="fas fa-lock me-1"></i>
                            Senha Atual *
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="senha_atual"
                                   name="senha_atual"
                                   required
                                   placeholder="Digite sua senha atual">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('senha_atual')">
                                <i class="fas fa-eye" id="eye-senha_atual"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="error-senha_atual"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">
                            <i class="fas fa-key me-1"></i>
                            Nova Senha *
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="nova_senha"
                                   name="nova_senha"
                                   required
                                   minlength="6"
                                   placeholder="Digite a nova senha">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('nova_senha')">
                                <i class="fas fa-eye" id="eye-nova_senha"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-shield-alt me-1"></i>
                            Mínimo 6 caracteres. Use letras, números e símbolos.
                        </div>
                        <div class="invalid-feedback" id="error-nova_senha"></div>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">
                            <i class="fas fa-check-double me-1"></i>
                            Confirmar Nova Senha *
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="confirmar_senha"
                                   name="confirmar_senha"
                                   required
                                   placeholder="Confirme a nova senha">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmar_senha')">
                                <i class="fas fa-eye" id="eye-confirmar_senha"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="error-confirmar_senha"></div>
                    </div>

                    <!-- Indicador de força da senha -->
                    <div class="mb-3">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" id="password-strength" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted" id="strength-text">Digite uma senha para ver a força</small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-alterar-senha">
                        <i class="fas fa-save me-2"></i>
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS específico para perfil -->
<style>
    .profile-image-container {
        position: relative;
        display: inline-block;
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid var(--montink-light);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        margin: 0 auto;
    }

    .border-bottom {
        border-bottom: 1px solid #e3e6f0 !important;
    }

    @media (max-width: 768px) {
        .profile-image {
            width: 80px;
            height: 80px;
        }

        .profile-image i {
            font-size: 3rem !important;
        }
    }
    /* CSS específico para integração ViaCEP */

    /* Animação de loading no campo CEP */
    .form-control.loading {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    /* Container do CEP com posição relativa para o ícone de loading */
    .cep-container {
        position: relative;
    }

    /* Ícone de busca no label do CEP */
    .form-label i[title="Busca automática"] {
        font-size: 0.8em;
        cursor: help;
    }

    /* Toast personalizado para mensagens ViaCEP */
    .cep-toast {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        animation: slideInRight 0.3s ease-out;
    }

    /* Animação para o toast */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Estilo para campos preenchidos automaticamente */
    .auto-filled {
        background-color: #e8f5e8 !important;
        border-color: #28a745 !important;
        transition: all 0.3s ease;
    }

    /* Destaque no campo CEP */
    #cep:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Estilo do botão limpar */
    .btn-limpar-endereco {
        font-size: 0.8em;
        padding: 0.25rem 0.5rem;
    }

    /* Estilo do help text */
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }

    /* Indicador visual para campos obrigatórios */
    .form-label:has(+ input[required])::after,
    .form-label:has(+ select[required])::after {
        content: " *";
        color: #dc3545;
    }

    /* Estilo para o container do endereço */
    .endereco-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    /* Responsividade para dispositivos móveis */
    @media (max-width: 768px) {
        .cep-toast {
            right: 10px;
            left: 10px;
            min-width: auto;
        }

        .form-text {
            font-size: 0.8em;
        }

        .btn-limpar-endereco {
            font-size: 0.7em;
            padding: 0.2rem 0.4rem;
        }
    }

    /* Efeito hover nos campos de endereço */
    .form-control:hover:not(:disabled):not([readonly]) {
        border-color: #80bdff;
        transition: border-color 0.15s ease-in-out;
    }

    /* Estilo para campos com erro */
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    /* Loading spinner personalizado */
    .cep-loading-icon {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }


    /* Garantir z-index alto para o modal */
    .modal {
        z-index: 9999 !important;
    }

    .modal-backdrop {
        z-index: 9998 !important;
    }

    /* Melhorar aparência do modal */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .modal-header {
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        border-radius: 0 0 12px 12px;
        padding: 1rem 1.5rem;
    }

    /* Estilo para campos de senha */
    .input-group .btn {
        border-left: none;
    }

    .input-group .form-control:focus + .btn {
        border-color: #86b7fe;
    }

    /* Indicador de força da senha */
    .progress-bar {
        transition: all 0.3s ease;
    }

    .strength-weak {
        background-color: #dc3545 !important;
    }

    .strength-medium {
        background-color: #ffc107 !important;
    }

    .strength-strong {
        background-color: #28a745 !important;
    }

    /* Animação para mensagens de erro */
    .invalid-feedback {
        display: block;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .invalid-feedback.show {
        opacity: 1;
    }

    /* Responsividade */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 1rem;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1rem;
        }
    }

    /* Fix para garantir que o modal apareça sobre qualquer elemento */
    body.modal-open {
        overflow: hidden;
    }

    .modal.show {
        display: block !important;
    }

    /* Estilo para o backdrop */
    .modal-backdrop.show {
        opacity: 0.5;
        position: relative;
    }

</style>

<!-- JavaScript específico para perfil -->
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

    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para telefone
        const telefone = document.getElementById('telefone');
        if (telefone) {
            telefone.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 7) {
                    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else if (value.length >= 3) {
                    value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                }
                e.target.value = value;
            });
        }

        // Máscara para CPF
        const cpf = document.getElementById('cpf');
        if (cpf) {
            cpf.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                e.target.value = value;
            });
        }

        // Máscara para CEP
        const cep = document.getElementById('cep');
        if (cep) {
            cep.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                e.target.value = value;
            });

            // Buscar endereço pelo CEP
            cep.addEventListener('blur', function(e) {
                const cepValue = e.target.value.replace(/\D/g, '');
                if (cepValue.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cepValue}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('endereco').value = data.logradouro;
                                document.getElementById('cidade').value = data.localidade;
                                document.getElementById('estado').value = data.uf;
                            }
                        })
                        .catch(error => console.log('Erro ao buscar CEP:', error));
                }
            });
        }

        // Validação do formulário de alterar senha
        const formAlterarSenha = document.getElementById('formAlterarSenha');
        if (formAlterarSenha) {
            formAlterarSenha.addEventListener('submit', function(e) {
                const novaSenha = document.getElementById('nova_senha').value;
                const confirmarSenha = document.getElementById('confirmar_senha').value;

                if (novaSenha !== confirmarSenha) {
                    e.preventDefault();
                    //alert('As senhas não coincidem!');
                    return false;
                }

                if (novaSenha.length < 6) {
                    e.preventDefault();
                    //alert('A nova senha deve ter pelo menos 6 caracteres!');
                    return false;
                }
            });
        }
    });

    // JavaScript para integração completa com ViaCEP
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para telefone
        const telefone = document.getElementById('telefone');
        if (telefone) {
            telefone.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 7) {
                    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else if (value.length >= 3) {
                    value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                }
                e.target.value = value;
            });
        }

        // Máscara para CPF
        const cpf = document.getElementById('cpf');
        if (cpf) {
            cpf.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                e.target.value = value;
            });
        }

        // Máscara para CEP e integração com ViaCEP
        const cep = document.getElementById('cep');
        const endereco = document.getElementById('endereco');
        const bairro = document.getElementById('bairro');
        const cidade = document.getElementById('cidade');
        const estado = document.getElementById('estado');

        if (cep) {
            // Aplicar máscara no CEP
            cep.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 8) {
                    value = value.substring(0, 8);
                }
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                e.target.value = value;
            });

            // Buscar endereço pelo CEP quando o campo perder o foco
            cep.addEventListener('blur', function(e) {
                buscarCEP(e.target.value);
            });

            // Também buscar quando pressionar Enter
            cep.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    buscarCEP(e.target.value);
                }
            });
        }

        // Função para buscar CEP
        function buscarCEP(cepValue) {
            // Remove caracteres não numéricos
            const cepLimpo = cepValue.replace(/\D/g, '');

            // Verifica se o CEP tem 8 dígitos
            if (cepLimpo.length !== 8) {
                return;
            }

            // Adiciona indicador de carregamento
            adicionarIndicadorCarregamento();

            // Faz a requisição para a API do ViaCEP
            fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.erro) {
                        mostrarErro('CEP não encontrado');
                    } else {
                        preencherEndereco(data);
                        mostrarSucesso('Endereço encontrado!');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    mostrarErro('Erro ao buscar CEP. Verifique sua conexão.');
                })
                .finally(() => {
                    removerIndicadorCarregamento();
                });
        }

        // Função para preencher os campos de endereço
        function preencherEndereco(data) {
            if (endereco && data.logradouro) {
                endereco.value = data.logradouro;
                endereco.focus();
            }

            if (bairro && data.bairro) {
                bairro.value = data.bairro;
            }

            if (cidade && data.localidade) {
                cidade.value = data.localidade;
            }

            if (estado && data.uf) {
                estado.value = data.uf;
            }

            // Se o logradouro estiver vazio, foca no campo endereço para o usuário preencher
            if (!data.logradouro && endereco) {
                endereco.focus();
            }
        }

        // Função para adicionar indicador de carregamento
        function adicionarIndicadorCarregamento() {
            const cepField = document.getElementById('cep');
            if (cepField) {
                cepField.classList.add('loading');
                cepField.disabled = true;

                // Adiciona ícone de loading se não existir
                let loadingIcon = document.querySelector('.cep-loading-icon');
                if (!loadingIcon) {
                    loadingIcon = document.createElement('i');
                    loadingIcon.className = 'fas fa-spinner fa-spin cep-loading-icon';
                    loadingIcon.style.position = 'absolute';
                    loadingIcon.style.right = '10px';
                    loadingIcon.style.top = '50%';
                    loadingIcon.style.transform = 'translateY(-50%)';
                    loadingIcon.style.color = '#6c757d';

                    const cepContainer = cepField.parentElement;
                    cepContainer.style.position = 'relative';
                    cepContainer.appendChild(loadingIcon);
                }
            }
        }

        // Função para remover indicador de carregamento
        function removerIndicadorCarregamento() {
            const cepField = document.getElementById('cep');
            const loadingIcon = document.querySelector('.cep-loading-icon');

            if (cepField) {
                cepField.classList.remove('loading');
                cepField.disabled = false;
            }

            if (loadingIcon) {
                loadingIcon.remove();
            }
        }

        // Função para mostrar mensagem de sucesso
        function mostrarSucesso(mensagem) {
            mostrarToast(mensagem, 'success');
        }

        // Função para mostrar mensagem de erro
        function mostrarErro(mensagem) {
            mostrarToast(mensagem, 'error');
        }

        // Função para mostrar toast
        function mostrarToast(mensagem, tipo) {
            // Remove toast anterior se existir
            const toastAnterior = document.querySelector('.cep-toast');
            if (toastAnterior) {
                toastAnterior.remove();
            }

            // Cria o toast
            const toast = document.createElement('div');
            toast.className = `cep-toast alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '300px';

            toast.innerHTML = `
            <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(toast);

            // Remove automaticamente após 3 segundos
            setTimeout(() => {
                if (toast && toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        // Validação do formulário de alterar senha
        const formAlterarSenha = document.getElementById('formAlterarSenha');
        if (formAlterarSenha) {
            formAlterarSenha.addEventListener('submit', function(e) {
                const novaSenha = document.getElementById('nova_senha').value;
                const confirmarSenha = document.getElementById('confirmar_senha').value;

                if (novaSenha !== confirmarSenha) {
                    e.preventDefault();
                    mostrarErro('As senhas não coincidem!');
                    return false;
                }

                if (novaSenha.length < 6) {
                    e.preventDefault();
                    mostrarErro('A nova senha deve ter pelo menos 6 caracteres!');
                    return false;
                }
            });
        }

        // Função para limpar campos de endereço
        function limparEndereco() {
            if (endereco) endereco.value = '';
            if (bairro) bairro.value = '';
            if (cidade) cidade.value = '';
            if (estado) estado.value = '';
        }

        // Adiciona botão para limpar endereço
        const cepContainer = cep?.parentElement;
        if (cepContainer) {
            const btnLimpar = document.createElement('button');
            btnLimpar.type = 'button';
            btnLimpar.className = 'btn btn-outline-secondary btn-sm mt-1';
            btnLimpar.innerHTML = '<i class="fas fa-eraser me-1"></i> Limpar';
            btnLimpar.onclick = limparEndereco;

            const helpText = document.createElement('div');
            helpText.className = 'form-text d-flex justify-content-between align-items-center';
            helpText.innerHTML = `
            <span>Digite o CEP para buscar automaticamente</span>
        `;
            helpText.appendChild(btnLimpar);

            cepContainer.appendChild(helpText);
        }
    });
    // JavaScript para alterar senha via AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const formAlterarSenha = document.getElementById('formAlterarSenha');
        const btnAlterarSenha = document.getElementById('btn-alterar-senha');
        const modalAlterarSenha = document.getElementById('modalAlterarSenha');

        // Campos do formulário
        const senhaAtual = document.getElementById('senha_atual');
        const novaSenha = document.getElementById('nova_senha');
        const confirmarSenha = document.getElementById('confirmar_senha');

        // Elementos de feedback
        const strengthBar = document.getElementById('password-strength');
        const strengthText = document.getElementById('strength-text');

        // Função para toggle de visibilidade da senha
        window.togglePassword = function(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('eye-' + fieldId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        };

        // Função para calcular força da senha
        function calcularForcaSenha(senha) {
            let forca = 0;
            let texto = '';
            let classe = '';

            if (senha.length >= 6) forca += 1;
            if (senha.length >= 8) forca += 1;
            if (/[a-z]/.test(senha)) forca += 1;
            if (/[A-Z]/.test(senha)) forca += 1;
            if (/[0-9]/.test(senha)) forca += 1;
            if (/[^A-Za-z0-9]/.test(senha)) forca += 1;

            if (forca <= 2) {
                texto = 'Senha fraca';
                classe = 'strength-weak';
                forca = 33;
            } else if (forca <= 4) {
                texto = 'Senha média';
                classe = 'strength-medium';
                forca = 66;
            } else {
                texto = 'Senha forte';
                classe = 'strength-strong';
                forca = 100;
            }

            return { forca, texto, classe };
        }

        // Evento para monitorar força da senha
        if (novaSenha) {
            novaSenha.addEventListener('input', function() {
                const senha = this.value;

                if (senha === '') {
                    strengthBar.style.width = '0%';
                    strengthBar.className = 'progress-bar';
                    strengthText.textContent = 'Digite uma senha para ver a força';
                    return;
                }

                const resultado = calcularForcaSenha(senha);

                strengthBar.style.width = resultado.forca + '%';
                strengthBar.className = 'progress-bar ' + resultado.classe;
                strengthText.textContent = resultado.texto;
            });
        }

        // Função para limpar erros
        function limparErros() {
            document.querySelectorAll('.form-control').forEach(field => {
                field.classList.remove('is-invalid');
            });

            document.querySelectorAll('.invalid-feedback').forEach(error => {
                error.textContent = '';
                error.classList.remove('show');
            });
        }

        // Função para exibir erro em campo específico
        function exibirErro(campo, mensagem) {
            const field = document.getElementById(campo);
            const errorDiv = document.getElementById('error-' + campo);

            if (field && errorDiv) {
                field.classList.add('is-invalid');
                errorDiv.textContent = mensagem;
                errorDiv.classList.add('show');
            }
        }

        // Função para exibir toast
        function exibirToast(mensagem, tipo = 'success') {
            // Remove toast anterior se existir
            const toastAnterior = document.querySelector('.senha-toast');
            if (toastAnterior) {
                toastAnterior.remove();
            }

            const toast = document.createElement('div');
            toast.className = `senha-toast alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            min-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            animation: slideInRight 0.3s ease-out;
        `;

            toast.innerHTML = `
            <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(toast);

            // Remove automaticamente após 4 segundos
            setTimeout(() => {
                if (toast && toast.parentElement) {
                    const bsAlert = new bootstrap.Alert(toast);
                    bsAlert.close();
                }
            }, 4000);
        }

        // Função para resetar formulário
        function resetarFormulario() {
            formAlterarSenha.reset();
            limparErros();

            // Reset da barra de força
            strengthBar.style.width = '0%';
            strengthBar.className = 'progress-bar';
            strengthText.textContent = 'Digite uma senha para ver a força';

            // Reset dos tipos de input para password
            senhaAtual.type = 'password';
            novaSenha.type = 'password';
            confirmarSenha.type = 'password';

            // Reset dos ícones
            document.getElementById('eye-senha_atual').className = 'fas fa-eye';
            document.getElementById('eye-nova_senha').className = 'fas fa-eye';
            document.getElementById('eye-confirmar_senha').className = 'fas fa-eye';
        }

        // Validação client-side
        function validarFormulario() {
            let valido = true;

            limparErros();

            // Validar senha atual
            if (!senhaAtual.value.trim()) {
                exibirErro('senha_atual', 'A senha atual é obrigatória');
                valido = false;
            }

            // Validar nova senha
            if (!novaSenha.value.trim()) {
                exibirErro('nova_senha', 'A nova senha é obrigatória');
                valido = false;
            } else if (novaSenha.value.length < 6) {
                exibirErro('nova_senha', 'A nova senha deve ter pelo menos 6 caracteres');
                valido = false;
            }

            // Validar confirmação
            if (!confirmarSenha.value.trim()) {
                exibirErro('confirmar_senha', 'A confirmação da senha é obrigatória');
                valido = false;
            } else if (novaSenha.value !== confirmarSenha.value) {
                exibirErro('confirmar_senha', 'As senhas não coincidem');
                valido = false;
            }

            return valido;
        }

        // Evento de submit do formulário
        if (formAlterarSenha) {
            formAlterarSenha.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validar formulário
                if (!validarFormulario()) {
                    return;
                }

                // Desabilitar botão e mostrar loading
                btnAlterarSenha.disabled = true;
                const textoOriginal = btnAlterarSenha.innerHTML;
                btnAlterarSenha.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Alterando...';

                // Preparar dados
                const formData = new FormData(this);

                // Fazer requisição AJAX
                fetch('alterar_senha', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na requisição: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Sucesso
                            exibirToast(data.message, 'success');

                            // Fechar modal após pequeno delay
                            setTimeout(() => {
                                const modal = bootstrap.Modal.getInstance(modalAlterarSenha);
                                if (modal) {
                                    modal.hide();
                                }
                                resetarFormulario();
                            }, 1500);

                        } else {
                            // Erro - exibir mensagens específicas
                            if (data.errors) {
                                Object.keys(data.errors).forEach(campo => {
                                    if (data.errors[campo]) {
                                        exibirErro(campo, data.errors[campo].replace(/<\/?[^>]+(>|$)/g, ""));
                                    }
                                });
                            }

                            if (data.message) {
                                exibirToast(data.message, 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        exibirToast('Erro de conexão. Tente novamente.', 'error');
                    })
                    .finally(() => {
                        // Reabilitar botão
                        btnAlterarSenha.disabled = false;
                        btnAlterarSenha.innerHTML = textoOriginal;
                    });
            });
        }

        // Limpar formulário quando modal for fechado
        if (modalAlterarSenha) {
            modalAlterarSenha.addEventListener('hidden.bs.modal', function() {
                resetarFormulario();
            });
        }

        // Validação em tempo real para confirmação de senha
        if (confirmarSenha) {
            confirmarSenha.addEventListener('input', function() {
                const errorDiv = document.getElementById('error-confirmar_senha');

                if (this.value && novaSenha.value && this.value !== novaSenha.value) {
                    this.classList.add('is-invalid');
                    errorDiv.textContent = 'As senhas não coincidem';
                    errorDiv.classList.add('show');
                } else {
                    this.classList.remove('is-invalid');
                    errorDiv.textContent = '';
                    errorDiv.classList.remove('show');
                }
            });
        }
    });
</script>