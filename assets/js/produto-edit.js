/**
 * produto-editar.js
 * Sistema de edição de produtos
 * 
 * Funcionalidades:
 * - Validação em tempo real
 * - Preview de imagens
 * - Auto-save de rascunho
 * - Detecção de alterações
 * - Atalhos de teclado
 * - Alertas personalizados
 * - Controle de estoque dinâmico
 */

// ==================== VARIÁVEIS GLOBAIS ====================
let formularioAlterado = false;
let intervalAutoSave = null;
const AUTOSAVE_INTERVAL = 30000; // 30 segundos

// Valores originais para reset (serão preenchidos pelo PHP)
let valoresOriginais = {};

// URLs da aplicação (serão preenchidas pelo PHP)
let appUrls = {};

// Dados do produto (serão preenchidos pelo PHP)
let produtoData = {};

// ==================== INICIALIZAÇÃO ====================
document.addEventListener('DOMContentLoaded', function() {
    inicializarSistema();
});

function inicializarSistema() {
    console.log('🚀 Iniciando sistema de edição de produtos...');
    
    // Verificar se as variáveis globais foram definidas
    if (typeof window.produtoData !== 'undefined') {
        produtoData = window.produtoData;
        valoresOriginais = { ...produtoData };
    }
    
    if (typeof window.appUrls !== 'undefined') {
        appUrls = window.appUrls;
    }
    
    // Configurar eventos
    configurarEventos();
    
    // Configurar controle de estoque
    configurarEstoque();
    
    // Configurar auto-save
    iniciarAutoSave();
    
    // Restaurar rascunho se existir
    restaurarRascunho();
    
    // Configurar atalhos de teclado
    configurarAtalhos();
    
    // Configurar indicadores visuais
    configurarIndicadores();
    
    console.log('✅ Sistema carregado com sucesso!');
    console.log('📋 Atalhos disponíveis:');
    console.log('   - Ctrl+S: Salvar');
    console.log('   - Ctrl+Shift+Z: Resetar');
    console.log('   - Esc: Cancelar');
}

// ==================== CONFIGURAÇÃO DE EVENTOS ====================
function configurarEventos() {
    const form = document.getElementById('formEditarProduto');
    if (!form) return;
    
    // Detectar mudanças no formulário
    form.addEventListener('change', marcarComoAlterado);
    form.addEventListener('input', marcarComoAlterado);
    
    // Validação no submit
    form.addEventListener('submit', validarSubmit);
    
    // Formatação automática de preços
    ['preco', 'preco_promocional', 'preco_custo'].forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) {
            elemento.addEventListener('blur', formatarPreco);
        }
    });
    
    // Validação de preço promocional
    const precoPromocional = document.getElementById('preco_promocional');
    if (precoPromocional) {
        precoPromocional.addEventListener('input', validarPrecoPromocional);
    }
    
    // Configurar indicadores de alteração
    document.querySelectorAll('input, textarea, select').forEach(campo => {
        const valorOriginal = campo.value;
        
        campo.addEventListener('input', function() {
            if (this.value !== valorOriginal) {
                this.classList.add('campo-alterado');
            } else {
                this.classList.remove('campo-alterado');
            }
        });
    });
    
    // Avisar sobre mudanças não salvas
    window.addEventListener('beforeunload', function(e) {
        if (formularioAlterado) {
            e.preventDefault();
            e.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?';
            return 'Você tem alterações não salvas. Deseja realmente sair?';
        }
    });
}

// ==================== CONTROLE DE ESTOQUE ====================
function configurarEstoque() {
    const checkboxEstoque = document.getElementById('controlar_estoque');
    if (checkboxEstoque) {
        // Configurar estado inicial
        toggleEstoque();
        
        // Configurar evento de mudança
        checkboxEstoque.addEventListener('change', toggleEstoque);
    }
}

function toggleEstoque() {
    const checkbox = document.getElementById('controlar_estoque');
    const campos = document.getElementById('campos-estoque');
    
    if (!checkbox || !campos) return;
    
    if (checkbox.checked) {
        campos.style.display = 'block';
        campos.classList.add('fade-in');
        
        // Animar entrada dos campos
        setTimeout(() => {
            campos.style.opacity = '1';
            campos.style.transform = 'translateY(0)';
        }, 100);
    } else {
        campos.style.opacity = '0';
        campos.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            campos.style.display = 'none';
            campos.classList.remove('fade-in');
        }, 300);
    }
}

// ==================== PREVIEW DE IMAGENS ====================
function previewImagem(input, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.innerHTML = '';

    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validar arquivo
        if (!validarArquivoImagem(file)) {
            input.value = ''; // Limpar input se inválido
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-container';
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            img.alt = 'Preview da imagem';
            
            const info = document.createElement('small');
            info.className = 'text-muted d-block mt-1';
            info.textContent = `${file.name} (${formatarTamanhoArquivo(file.size)})`;
            
            div.appendChild(img);
            div.appendChild(info);
            container.appendChild(div);
            
            // Animação de entrada
            setTimeout(() => {
                img.style.opacity = '1';
                img.style.transform = 'scale(1)';
            }, 100);
        };
        reader.readAsDataURL(file);
    }
}

function previewGaleria(input) {
    const container = document.getElementById('preview-galeria');
    if (!container) return;
    
    container.innerHTML = '';
    container.className = 'galeria-preview mt-3';

    if (input.files && input.files.length > 0) {
        // Validar número máximo de arquivos
        if (input.files.length > 10) {
            showAlert('warning', 'Máximo de 10 imagens por vez permitido');
            input.value = '';
            return;
        }
        
        Array.from(input.files).forEach((file, index) => {
            // Validar cada arquivo
            if (!validarArquivoImagem(file)) {
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'galeria-item';
                div.style.animationDelay = `${index * 0.1}s`;

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-image';
                img.alt = `Preview ${index + 1}`;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn-remove-image';
                btn.innerHTML = '×';
                btn.title = 'Remover imagem';
                btn.onclick = function() {
                    div.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => div.remove(), 300);
                    atualizarFileInput(input, index);
                };
                
                const info = document.createElement('small');
                info.className = 'preview-info';
                info.textContent = `${file.name.substring(0, 15)}...`;

                div.appendChild(img);
                div.appendChild(btn);
                div.appendChild(info);
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function validarArquivoImagem(file) {
    const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    const tamanhoMaximo = 2 * 1024 * 1024; // 2MB
    
    if (!tiposPermitidos.includes(file.type)) {
        showAlert('error', 'Tipo de arquivo não permitido. Use: JPG, PNG, GIF ou WEBP');
        return false;
    }
    
    if (file.size > tamanhoMaximo) {
        showAlert('error', `Arquivo "${file.name}" muito grande. Máximo permitido: 2MB`);
        return false;
    }
    
    return true;
}

function atualizarFileInput(input, indexRemovido) {
    // Criar novo FileList sem o arquivo removido
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, index) => {
        if (index !== indexRemovido) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
}

function formatarTamanhoArquivo(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const tamanhos = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + tamanhos[i];
}

// ==================== REMOÇÃO DE IMAGENS ====================
function removerImagemGaleria(nomeImagem, index) {
    if (!confirm('Tem certeza que deseja remover esta imagem?')) {
        return;
    }
    
    mostrarLoading('Removendo imagem...');
    
    fetch(appUrls.removerImagem, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `produto_id=${produtoData.id}&imagem=${encodeURIComponent(nomeImagem)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        ocultarLoading();
        
        if (data.success) {
            const elemento = document.getElementById(`galeria-item-${index}`);
            if (elemento) {
                elemento.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => elemento.remove(), 300);
            }
            showAlert('success', 'Imagem removida com sucesso!');
            marcarComoAlterado();
        } else {
            showAlert('error', 'Erro: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        ocultarLoading();
        console.error('Erro:', error);
        showAlert('error', 'Erro ao remover imagem. Tente novamente.');
    });
}

// ==================== VALIDAÇÕES ====================
function validarSubmit(e) {
    const nome = document.getElementById('nome')?.value.trim();
    const sku = document.getElementById('sku')?.value.trim();
    const preco = document.getElementById('preco')?.value;
    const categoria_id = document.getElementById('categoria_id')?.value;

    // Validar campos obrigatórios
    if (!nome || !sku || !preco || !categoria_id) {
        e.preventDefault();
        showAlert('error', 'Preencha todos os campos obrigatórios (Nome, SKU, Preço e Categoria)');
        
        // Destacar campos vazios
        const campos = [
            {id: 'nome', valor: nome},
            {id: 'sku', valor: sku},
            {id: 'preco', valor: preco},
            {id: 'categoria_id', valor: categoria_id}
        ];
        
        let primeiroErro = null;
        campos.forEach(campo => {
            const elemento = document.getElementById(campo.id);
            if (!campo.valor && elemento) {
                elemento.classList.add('required-field', 'is-invalid');
                if (!primeiroErro) {
                    primeiroErro = elemento;
                }
            }
        });
        
        // Focar no primeiro campo com erro
        if (primeiroErro) {
            primeiroErro.focus();
            primeiroErro.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return false;
    }

    // Validar preço promocional
    const precoPromocional = parseFloat(document.getElementById('preco_promocional')?.value);
    const precoNormal = parseFloat(preco);
    
    if (precoPromocional > 0 && precoPromocional >= precoNormal) {
        e.preventDefault();
        showAlert('error', 'O preço promocional deve ser menor que o preço normal');
        document.getElementById('preco_promocional').focus();
        return false;
    }

    // Mostrar loading no botão de submit
    const btn = e.target.querySelector('button[type="submit"]');
    if (btn) {
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
        btn.disabled = true;
        
        // Salvar referência para restaurar se houver erro
        btn.dataset.textoOriginal = textoOriginal;
    }

    // Limpar rascunho se salvou com sucesso
    setTimeout(() => {
        if (!document.querySelector('.is-invalid')) {
            formularioAlterado = false;
            limparRascunho();
        }
    }, 1000);
}

function formatarPreco() {
    if (this.value && !isNaN(this.value)) {
        const valor = parseFloat(this.value);
        this.value = valor.toFixed(2);
    }
}

function validarPrecoPromocional() {
    const precoNormal = parseFloat(document.getElementById('preco')?.value) || 0;
    const precoPromocional = parseFloat(this.value) || 0;

    if (precoPromocional > 0 && precoPromocional >= precoNormal) {
        this.setCustomValidity('O preço promocional deve ser menor que o preço normal');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
}

// ==================== AÇÕES DO FORMULÁRIO ====================
function resetarFormulario() {
    if (!confirm('Tem certeza que deseja resetar todas as alterações?')) {
        return;
    }
    
    mostrarLoading('Resetando formulário...');
    
    // Restaurar valores originais
    Object.keys(valoresOriginais).forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) {
            if (elemento.type === 'checkbox') {
                elemento.checked = valoresOriginais[campo];
            } else {
                elemento.value = valoresOriginais[campo] || '';
            }
        }
    });

    // Limpar previews
    const previewPrincipal = document.getElementById('preview-principal');
    const previewGaleria = document.getElementById('preview-galeria');
    
    if (previewPrincipal) previewPrincipal.innerHTML = '';
    if (previewGaleria) previewGaleria.innerHTML = '';

    // Resetar inputs de arquivo
    const imagemPrincipal = document.getElementById('imagem_principal');
    const galeria = document.getElementById('galeria');
    
    if (imagemPrincipal) imagemPrincipal.value = '';
    if (galeria) galeria.value = '';

    // Atualizar visibilidade dos campos de estoque
    toggleEstoque();

    // Remover indicadores de alteração
    document.querySelectorAll('.campo-alterado').forEach(campo => {
        campo.classList.remove('campo-alterado');
    });

    // Remover classes de validação
    document.querySelectorAll('.is-invalid').forEach(campo => {
        campo.classList.remove('is-invalid', 'required-field');
    });

    formularioAlterado = false;
    
    setTimeout(() => {
        ocultarLoading();
        showAlert('info', 'Formulário resetado para os valores originais.');
    }, 500);
}

function duplicarProduto() {
    if (formularioAlterado) {
        if (!confirm('Você tem alterações não salvas. Deseja duplicar mesmo assim?')) {
            return;
        }
    }
    
    if (confirm('Deseja duplicar este produto? Será criado um novo produto com os mesmos dados e SKU diferente.')) {
        mostrarLoading('Duplicando produto...');
        window.open(appUrls.duplicar, '_blank');
        
        setTimeout(() => {
            ocultarLoading();
        }, 2000);
    }
}

function visualizarProduto() {
    window.open(appUrls.visualizar, '_blank');
}

function ajustarEstoque() {
    showAlert('info', 'Funcionalidade de ajuste de estoque em desenvolvimento. Em breve estará disponível!');
}

function excluirProduto(id, nome) {
    if (!confirm(`⚠️ ATENÇÃO!\n\nVocê está prestes a excluir o produto:\n"${nome}"\n\nEsta ação é IRREVERSÍVEL e removerá:\n- Todos os dados do produto\n- Todas as imagens\n- Histórico de vendas relacionado\n\nTem certeza que deseja continuar?`)) {
        return;
    }
    
    // Confirmação dupla para exclusão
    if (!confirm('Digite "EXCLUIR" para confirmar:')) {
        return;
    }
    
    const btn = event.target;
    const textoOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Excluindo...';
    btn.disabled = true;

    mostrarLoading('Excluindo produto...');

    fetch(`${appUrls.excluir}${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        ocultarLoading();
        
        if (data.success) {
            showAlert('success', data.message || 'Produto excluído com sucesso!');
            
            // Limpar rascunho
            limparRascunho();
            formularioAlterado = false;
            
            // Redirecionar após 2 segundos
            setTimeout(() => {
                window.location.href = appUrls.produto;
            }, 2000);
        } else {
            showAlert('error', 'Erro: ' + (data.message || 'Erro desconhecido'));
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        }
    })
    .catch(error => {
        ocultarLoading();
        console.error('Erro:', error);
        showAlert('error', 'Erro ao excluir produto. Tente novamente.');
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}

// ==================== AUTO-SAVE E RASCUNHO ====================
function iniciarAutoSave() {
    // Auto-save a cada 30 segundos se houver alterações
    intervalAutoSave = setInterval(() => {
        if (formularioAlterado) {
            autoSaveDraft();
        }
    }, AUTOSAVE_INTERVAL);
}

function autoSaveDraft() {
    const formData = new FormData(document.getElementById('formEditarProduto'));
    const dados = {};

    // Converter FormData para objeto (excluindo arquivos)
    for (let [key, value] of formData.entries()) {
        if (key !== 'imagem_principal' && key !== 'galeria[]') {
            dados[key] = value;
        }
    }

    // Adicionar timestamp
    dados.timestamp = new Date().toISOString();

    // Salvar no localStorage
    try {
        localStorage.setItem(`produto_draft_${produtoData.id}`, JSON.stringify(dados));
        mostrarIndicadorAutoSave();
        console.log('📄 Rascunho salvo automaticamente em', dados.timestamp);
    } catch (error) {
        console.error('Erro ao salvar rascunho:', error);
        showAlert('warning', 'Erro ao salvar rascunho automático');
    }
}

function limparRascunho() {
    try {
        localStorage.removeItem(`produto_draft_${produtoData.id}`);
        console.log('🗑️ Rascunho removido');
    } catch (error) {
        console.error('Erro ao limpar rascunho:', error);
    }
}

function restaurarRascunho() {
    try {
        const rascunho = localStorage.getItem(`produto_draft_${produtoData.id}`);

        if (rascunho) {
            const dados = JSON.parse(rascunho);
            const dataRascunho = new Date(dados.timestamp);
            const agora = new Date();
            const horasPassadas = (agora - dataRascunho) / (1000 * 60 * 60);
            
            // Só oferece restaurar se o rascunho for de menos de 24 horas
            if (horasPassadas < 24) {
                const dataFormatada = dataRascunho.toLocaleString('pt-BR');
                
                if (confirm(`Foi encontrado um rascunho não salvo deste produto.\n\nSalvo em: ${dataFormatada}\n\nDeseja restaurá-lo?`)) {
                    Object.keys(dados).forEach(campo => {
                        if (campo === 'timestamp') return;
                        
                        const elemento = document.getElementById(campo);
                        if (elemento) {
                            if (elemento.type === 'checkbox') {
                                elemento.checked = dados[campo] === '1';
                            } else {
                                elemento.value = dados[campo] || '';
                            }
                        }
                    });

                    formularioAlterado = true;
                    toggleEstoque(); // Atualizar visibilidade dos campos de estoque
                    showAlert('info', 'Rascunho restaurado com sucesso!');
                } else {
                    limparRascunho();
                }
            } else {
                // Rascunho muito antigo, remover
                limparRascunho();
            }
        }
    } catch (error) {
        console.error('Erro ao restaurar rascunho:', error);
        limparRascunho();
    }
}

function mostrarIndicadorAutoSave() {
    let indicador = document.querySelector('.autosave-indicator');
    
    if (!indicador) {
        indicador = document.createElement('div');
        indicador.className = 'autosave-indicator';
        indicador.innerHTML = '<i class="fas fa-save me-2"></i>Rascunho salvo';
        document.body.appendChild(indicador);
    }
    
    indicador.classList.add('show');
    
    setTimeout(() => {
        indicador.classList.remove('show');
    }, 2000);
}

// ==================== ATALHOS DE TECLADO ====================
function configurarAtalhos() {
    document.addEventListener('keydown', function(e) {
        // Ctrl+S para salvar
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            const form = document.getElementById('formEditarProduto');
            if (form) {
                form.submit();
            }
        }

        // Ctrl+Shift+Z para resetar
        if (e.ctrlKey && e.key === 'z' && e.shiftKey) {
            e.preventDefault();
            resetarFormulario();
        }

        // Esc para cancelar
        if (e.key === 'Escape') {
            const activeElement = document.activeElement;
            
            // Se está editando um campo, sair do campo
            if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {
                activeElement.blur();
                return;
            }
            
            // Senão, perguntar se quer cancelar
            if (confirm('Deseja cancelar a edição? Alterações não salvas serão perdidas.')) {
                history.back();
            }
        }

        // Ctrl+D para duplicar
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            duplicarProduto();
        }

        // Ctrl+E para visualizar
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            visualizarProduto();
        }
        
        // F1 para ajuda
        if (e.key === 'F1') {
            e.preventDefault();
            mostrarAjuda();
        }
    });
}

function mostrarAjuda() {
    const ajuda = `
🎯 SISTEMA DE EDIÇÃO DE PRODUTOS

⌨️ ATALHOS DISPONÍVEIS:
• Ctrl+S - Salvar produto
• Ctrl+Shift+Z - Resetar alterações
• Ctrl+D - Duplicar produto
• Ctrl+E - Visualizar produto
• Esc - Cancelar/Sair
• F1 - Esta ajuda

💡 DICAS:
• Alterações são salvas automaticamente como rascunho
• Campos alterados ficam destacados em amarelo
• SKU deve ser único no sistema
• Imagens são redimensionadas automaticamente
• Use Tab para navegar entre campos

📱 MOBILE:
• Interface responsiva
• Gestos de toque funcionam
• Zoom automático em campos
    `;
    
    showAlert('info', ajuda.trim(), false);
}

// ==================== INDICADORES VISUAIS ====================
function configurarIndicadores() {
    // Remover classes de validação quando o usuário começar a digitar
    document.querySelectorAll('input, textarea, select').forEach(campo => {
        campo.addEventListener('input', function() {
            this.classList.remove('is-invalid', 'required-field');
        });
        
        // Adicionar efeito de foco
        campo.addEventListener('focus', function() {
            this.closest('.form-group, .mb-3')?.classList.add('focused');
        });
        
        campo.addEventListener('blur', function() {
            this.closest('.form-group, .mb-3')?.classList.remove('focused');
        });
    });
    
    // Contador de caracteres para campos com limite
    configurarContadores();
}

function configurarContadores() {
    const campos = [
        {id: 'meta_title', max: 60},
        {id: 'meta_description', max: 160}
    ];
    
    campos.forEach(({id, max}) => {
        const campo = document.getElementById(id);
        if (campo) {
            const contador = document.createElement('small');
            contador.className = 'char-counter text-muted float-end';
            
            campo.parentNode.appendChild(contador);
            
            function atualizarContador() {
                const atual = campo.value.length;
                contador.textContent = `${atual}/${max}`;
                contador.className = `char-counter ${atual > max ? 'text-danger' : atual > max * 0.8 ? 'text-warning' : 'text-muted'} float-end`;
            }
            
            atualizarContador();
            campo.addEventListener('input', atualizarContador);
        }
    });
}

function marcarComoAlterado() {
    if (!formularioAlterado) {
        formularioAlterado = true;
        console.log('📝 Formulário marcado como alterado');
    }
}

// ==================== SISTEMA DE ALERTAS ====================
function showAlert(type, message, autoClose = true) {
    // Remover alertas existentes do mesmo tipo
    document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
        if (alert.classList.contains(`alert-${getAlertClass(type)}`)) {
            alert.remove();
        }
    });

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${getAlertClass(type)} alert-dismissible fade show alert-auto-dismiss`;
    
    const icon = getAlertIcon(type);
    alertDiv.innerHTML = `
        <i class="fas fa-${icon} me-2"></i>
        <span class="alert-message">${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Inserir no container de alertas ou no topo da página
    let container = document.querySelector('.alerts-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'alerts-container';
        container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1060; max-width: 400px;';
        document.body.appendChild(container);
    }
    
    container.appendChild(alertDiv);
    
    // Auto-remover após tempo especificado se definido
    if (autoClose) {
        const timeout = type === 'error' ? 8000 : 5000;
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, timeout);
    }
    
    // Configurar botão de fechar
    const closeBtn = alertDiv.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            alertDiv.style.animation = 'slideInRight 0.3s ease-out reverse';
            setTimeout(() => alertDiv.remove(), 300);
        });
    }
    
    return alertDiv;
}

function getAlertClass(type) {
    const classes = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };
    return classes[type] || 'info';
}

function getAlertIcon(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// ==================== LOADING ====================
function mostrarLoading(mensagem = 'Carregando...') {
    // Remover loading existente
    ocultarLoading();
    
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.id = 'loadingOverlay';
    
    overlay.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="loading-message">${mensagem}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    // Prevenir scroll do body
    document.body.style.overflow = 'hidden';
    
    return overlay;
}

function ocultarLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => {
            overlay.remove();
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

// ==================== UTILITÁRIOS ====================
function formatarNumero(numero, decimais = 2) {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: decimais,
        maximumFractionDigits: decimais
    }).format(numero);
}

function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function sanitizeHTML(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
}

function copiarParaClipboard(texto) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(texto).then(() => {
            showAlert('success', 'Copiado para a área de transferência!');
        });
    } else {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = texto;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showAlert('success', 'Copiado para a área de transferência!');
    }
}

// ==================== LIMPEZA ====================
window.addEventListener('beforeunload', function() {
    // Limpar intervalos
    if (intervalAutoSave) {
        clearInterval(intervalAutoSave);
    }
    
    // Restaurar overflow do body
    document.body.style.overflow = 'auto';
});

// ==================== EXPOSIÇÃO DE FUNÇÕES GLOBAIS ====================
// Funções que precisam ser acessíveis globalmente (chamadas pelo HTML)
window.toggleEstoque = toggleEstoque;
window.previewImagem = previewImagem;
window.previewGaleria = previewGaleria;
window.removerImagemGaleria = removerImagemGaleria;
window.resetarFormulario = resetarFormulario;
window.duplicarProduto = duplicarProduto;
window.visualizarProduto = visualizarProduto;
window.ajustarEstoque = ajustarEstoque;
window.excluirProduto = excluirProduto;
window.showAlert = showAlert;
window.mostrarLoading = mostrarLoading;
window.ocultarLoading = ocultarLoading;
window.copiarParaClipboard = copiarParaClipboard;

// ==================== DEBUGGING ====================
if (typeof console !== 'undefined' && console.log) {
    console.log('%c🎯 Sistema de Edição de Produtos', 'color: #28a745; font-weight: bold; font-size: 16px;');
    console.log('%cVersão: 2.0.0', 'color: #6c757d;');
    console.log('%cDesenvolvido com ❤️', 'color: #dc3545;');
    
    // Expor algumas funções para debug
    window.produtoEditarDebug = {
        valoresOriginais,
        produtoData,
        formularioAlterado,
        limparRascunho,
        autoSaveDraft,
        mostrarLoading,
        ocultarLoading,
        showAlert,
        toggleEstoque
    };
}