/**
 * JavaScript para formulário de cadastro/edição de produtos
 * Arquivo: assets/js/produto-form.js
 */

// Variáveis globais
let produtosCadastrados = parseInt(sessionStorage.getItem('produtos_cadastrados')) || 0;
let konami = [];
const konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑↑↓↓←→←→BA

// Inicialização quando DOM carrega
document.addEventListener('DOMContentLoaded', function() {
    inicializarFormulario();
    carregarRascunho();
    adicionarTooltips();
    inicializarTooltipsBootstrap();
    toggleEstoque(); // Estado inicial do estoque
    
    // Auto-salvar rascunho a cada 30 segundos
    setInterval(salvarRascunho, 30000);
    
    // Log para debugging (apenas em desenvolvimento)
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
        console.log('🚀 Formulário de cadastro de produto carregado');
        console.log('💡 Dicas: Ctrl+S para salvar, Ctrl+R para limpar');
        console.log('🎮 Easter egg: Digite o código Konami para exemplo automático');
    }
});

// ================================
// CONTROLE DE ESTOQUE
// ================================

function toggleEstoque() {
    const checkbox = document.getElementById('controlar_estoque');
    const campos = document.getElementById('campos_estoque');
    
    if (checkbox && campos) {
        if (checkbox.checked) {
            campos.style.display = 'block';
            // Tornar campos obrigatórios
            const qtdInicial = document.getElementById('quantidade_inicial');
            const qtdMinima = document.getElementById('quantidade_minima');
            if (qtdInicial) qtdInicial.required = true;
            if (qtdMinima) qtdMinima.required = true;
        } else {
            campos.style.display = 'none';
            // Remover obrigatoriedade
            const qtdInicial = document.getElementById('quantidade_inicial');
            const qtdMinima = document.getElementById('quantidade_minima');
            if (qtdInicial) qtdInicial.required = false;
            if (qtdMinima) qtdMinima.required = false;
        }
    }
}

// ================================
// PREVIEW DE IMAGENS
// ================================

function previewImagem(input, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            container.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGaleria(input) {
    const container = document.getElementById('preview-galeria');
    if (!container) return;
    
    container.innerHTML = '';
    container.className = 'galeria-preview';

    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'galeria-item';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-image';

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn-remove-image';
                btn.innerHTML = '×';
                btn.onclick = function() {
                    div.remove();
                };

                div.appendChild(img);
                div.appendChild(btn);
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

// ================================
// GERAÇÃO DE SKU
// ================================

function gerarSKU() {
    const nome = document.getElementById('nome');
    const categoriaSelect = document.getElementById('categoria_id');
    const skuField = document.getElementById('sku');
    
    if (!nome || !skuField) return;
    
    if (!nome.value.trim()) {
        alert('Digite o nome do produto primeiro');
        return;
    }

    // Gerar SKU baseado no nome e categoria
    let sku = '';
    
    if (categoriaSelect && categoriaSelect.selectedIndex > 0) {
        const categoria = categoriaSelect.options[categoriaSelect.selectedIndex].text;
        sku += categoria.replace(/^--\s*/, '').substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, '');
    }
    
    sku += nome.value.substring(0, 5).toUpperCase().replace(/[^A-Z0-9]/g, '');
    sku += Math.floor(Math.random() * 1000).toString().padStart(3, '0');

    skuField.value = sku;
}

// ================================
// AÇÕES DO FORMULÁRIO
// ================================

function limparFormulario() {
    if (confirm('Tem certeza que deseja limpar todos os campos?')) {
        const form = document.getElementById('formCadastrarProduto');
        if (form) {
            form.reset();
            
            // Limpar previews
            const previewPrincipal = document.getElementById('preview-principal');
            const previewGaleria = document.getElementById('preview-galeria');
            if (previewPrincipal) previewPrincipal.innerHTML = '';
            if (previewGaleria) previewGaleria.innerHTML = '';
            
            // Reajustar campos de estoque
            toggleEstoque();
            
            // Limpar rascunho
            localStorage.removeItem('rascunho_produto');
        }
    }
}

function preencherExemplo() {
    // Dados básicos
    setValue('nome', 'Smartphone XYZ Pro 128GB');
    setValue('sku', 'ELE-SMART-001');
    setValue('descricao', 'Smartphone com tela de 6.1 polegadas, 128GB de armazenamento, câmera tripla de 48MP e bateria de 4000mAh.');
    
    // Selecionar primeira categoria disponível
    const categoriaSelect = document.getElementById('categoria_id');
    if (categoriaSelect && categoriaSelect.options.length > 1) {
        categoriaSelect.selectedIndex = 1;
    }
    
    // Mais dados
    setValue('marca', 'TechBrand');
    setValue('modelo', 'XYZ Pro');
    setValue('preco', '1299.99');
    setValue('preco_promocional', '1199.99');
    setValue('preco_custo', '800.00');
    setValue('peso', '0.185');
    setValue('dimensoes', '14.7 x 7.1 x 0.8 cm');
    
    // Checkboxes
    setChecked('ativo', true);
    setChecked('destaque', true);
    setChecked('controlar_estoque', true);
    
    // Recarregar campos de estoque
    toggleEstoque();
    
    // Dados de estoque
    setValue('quantidade_inicial', '50');
    setValue('quantidade_minima', '5');
    setValue('quantidade_maxima', '200');
    setValue('localizacao', 'Prateleira A1');
    setValue('fornecedor', 'TechSupplier Ltda');
    
    // SEO
    setValue('meta_title', 'Smartphone XYZ Pro 128GB - Alta Performance');
    setValue('meta_description', 'Smartphone XYZ Pro com 128GB, câmera tripla 48MP, tela 6.1" e bateria 4000mAh. Oferta especial!');
    setValue('tags', 'smartphone, celular, 128gb, câmera, tecnologia');
}

// ================================
// CÁLCULOS FINANCEIROS
// ================================

function calcularMargem() {
    const precoVenda = parseFloat(getValue('preco')) || 0;
    const precoCusto = parseFloat(getValue('preco_custo')) || 0;
    
    if (precoVenda > 0 && precoCusto > 0) {
        const margem = ((precoVenda - precoCusto) / precoVenda * 100).toFixed(2);
        
        // Exibir margem se houver elemento para isso
        let margemElement = document.getElementById('margem_display');
        if (!margemElement) {
            margemElement = document.createElement('small');
            margemElement.id = 'margem_display';
            margemElement.className = 'form-text';
            margemElement.style.display = 'block';
            margemElement.style.marginTop = '5px';
            
            const precoCustoField = document.getElementById('preco_custo');
            if (precoCustoField) {
                precoCustoField.parentNode.appendChild(margemElement);
            }
        }
        
        margemElement.textContent = `Margem: ${margem}%`;
        margemElement.className = 'form-text ' + (margem > 20 ? 'text-success' : margem > 10 ? 'text-warning' : 'text-danger');
    }
}

function calcularPrecoSugerido(margemDesejada = 30) {
    const precoCusto = parseFloat(getValue('preco_custo')) || 0;
    if (precoCusto > 0) {
        const precoSugerido = precoCusto / (1 - margemDesejada / 100);
        setValue('preco', precoSugerido.toFixed(2));
        calcularMargem();
    }
}

function calcularVolume() {
    const dimensoes = getValue('dimensoes');
    const match = dimensoes.match(/(\d+(?:\.\d+)?)\s*x\s*(\d+(?:\.\d+)?)\s*x\s*(\d+(?:\.\d+)?)/);
    
    if (match) {
        const volume = (parseFloat(match[1]) * parseFloat(match[2]) * parseFloat(match[3])) / 1000000; // em m³
        
        // Exibir volume
        let volumeDisplay = document.getElementById('volume-display');
        if (!volumeDisplay) {
            volumeDisplay = document.createElement('small');
            volumeDisplay.id = 'volume-display';
            volumeDisplay.className = 'form-text text-info';
            const dimensoesField = document.getElementById('dimensoes');
            if (dimensoesField) {
                dimensoesField.parentNode.appendChild(volumeDisplay);
            }
        }
        volumeDisplay.textContent = `Volume: ${volume.toFixed(6)} m³`;
    }
}

// ================================
// VALIDAÇÕES
// ================================

function validarArquivo(input, maxSize = 2048) {
    if (input.files) {
        for (let file of input.files) {
            if (file.size > maxSize * 1024) {
                alert(`Arquivo ${file.name} é muito grande. Máximo permitido: ${maxSize}KB`);
                input.value = '';
                return false;
            }
            
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert(`Tipo de arquivo não permitido: ${file.type}`);
                input.value = '';
                return false;
            }
        }
    }
    return true;
}

function validarCodigoBarras(codigo) {
    if (codigo.length !== 13) return false;
    
    let soma = 0;
    for (let i = 0; i < 12; i++) {
        soma += parseInt(codigo[i]) * (i % 2 === 0 ? 1 : 3);
    }
    
    const digito = (10 - (soma % 10)) % 10;
    return digito === parseInt(codigo[12]);
}

function destacarCamposObrigatorios() {
    const campos = ['nome', 'sku', 'preco', 'categoria_id'];
    let camposVazios = [];
    
    campos.forEach(campo => {
        const element = document.getElementById(campo);
        if (element && !element.value.trim()) {
            element.classList.add('border-danger');
            const label = element.closest('.mb-3')?.querySelector('label');
            if (label) {
                camposVazios.push(label.textContent.replace(' *', ''));
            }
        } else if (element) {
            element.classList.remove('border-danger');
        }
    });
    
    // Verificar campos de estoque se controle estiver ativo
    const controlarEstoque = document.getElementById('controlar_estoque');
    if (controlarEstoque && controlarEstoque.checked) {
        ['quantidade_inicial', 'quantidade_minima'].forEach(campo => {
            const element = document.getElementById(campo);
            if (element && !element.value.trim()) {
                element.classList.add('border-danger');
                const label = element.closest('.mb-3')?.querySelector('label');
                if (label) {
                    camposVazios.push(label.textContent.replace(' *', ''));
                }
            } else if (element) {
                element.classList.remove('border-danger');
            }
        });
    }
    
    return camposVazios;
}

// ================================
// SISTEMA DE RASCUNHO
// ================================

function salvarRascunho() {
    const form = document.getElementById('formCadastrarProduto');
    if (!form) return;
    
    const formData = new FormData(form);
    const rascunho = {};
    
    for (let [key, value] of formData.entries()) {
        if (key !== 'imagem_principal' && !key.startsWith('galeria')) {
            rascunho[key] = value;
        }
    }
    
    localStorage.setItem('rascunho_produto', JSON.stringify(rascunho));
}

function carregarRascunho() {
    const rascunho = localStorage.getItem('rascunho_produto');
    if (rascunho && confirm('Há um rascunho salvo. Deseja carregá-lo?')) {
        const dados = JSON.parse(rascunho);
        
        for (let [key, value] of Object.entries(dados)) {
            const field = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = value === '1';
                } else {
                    field.value = value;
                }
            }
        }
        
        toggleEstoque();
        localStorage.removeItem('rascunho_produto');
    }
}

// ================================
// INTERFACE E UX
// ================================

function mostrarProgresso(mostrar) {
    const progressContainer = document.getElementById('progress-container');
    if (progressContainer) {
        progressContainer.style.display = mostrar ? 'flex' : 'none';
    }
}

function adicionarTooltips() {
    const tooltips = {
        'sku': 'Código único que identifica o produto (Stock Keeping Unit)',
        'preco_custo': 'Valor que você paga ao fornecedor pelo produto',
        'quantidade_minima': 'Quando o estoque atingir este valor, você receberá um alerta',
        'meta_title': 'Título que aparece nos resultados de busca do Google',
        'tags': 'Palavras-chave que ajudam na busca interna do site'
    };
    
    Object.entries(tooltips).forEach(([fieldId, tooltip]) => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.setAttribute('title', tooltip);
            field.setAttribute('data-bs-toggle', 'tooltip');
        }
    });
}

function inicializarTooltipsBootstrap() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

function adicionarContador(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    if (field) {
        const counter = document.createElement('small');
        counter.className = 'form-text text-muted text-end';
        counter.style.display = 'block';
        field.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - field.value.length;
            counter.textContent = `${field.value.length}/${maxLength} caracteres`;
            counter.className = remaining < 20 ? 'form-text text-danger text-end' : 'form-text text-muted text-end';
            counter.style.display = 'block';
        }
        
        field.addEventListener('input', updateCounter);
        updateCounter();
    }
}

// ================================
// EVENT LISTENERS
// ================================

function inicializarFormulario() {
    // Event listeners para controle de estoque
    const controlarEstoque = document.getElementById('controlar_estoque');
    if (controlarEstoque) {
        controlarEstoque.addEventListener('change', toggleEstoque);
    }

    // Event listeners para cálculos financeiros
    const preco = document.getElementById('preco');
    const precoCusto = document.getElementById('preco_custo');
    const precoPromocional = document.getElementById('preco_promocional');
    
    if (preco) {
        preco.addEventListener('input', calcularMargem);
        preco.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
    
    if (precoCusto) {
        precoCusto.addEventListener('input', calcularMargem);
        precoCusto.addEventListener('input', function() {
            if (this.value && !document.getElementById('btn-preco-sugerido')) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.id = 'btn-preco-sugerido';
                btn.className = 'btn btn-outline-success btn-sm mt-1';
                btn.innerHTML = '<i class="fas fa-calculator me-1"></i>Calcular preço (30% margem)';
                btn.onclick = () => calcularPrecoSugerido(30);
                
                this.parentNode.appendChild(btn);
            }
        });
        precoCusto.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
    
    if (precoPromocional) {
        precoPromocional.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
        precoPromocional.addEventListener('change', function() {
            const precoNormal = parseFloat(getValue('preco'));
            const precoPromocional = parseFloat(this.value);
            
            if (precoPromocional && precoNormal && precoPromocional >= precoNormal) {
                alert('O preço promocional deve ser menor que o preço normal');
                this.value = '';
            }
        });
    }

    // Event listeners para validações
    const imagemPrincipal = document.getElementById('imagem_principal');
    const galeria = document.getElementById('galeria');
    const codigoBarras = document.getElementById('codigo_barras');
    const dimensoes = document.getElementById('dimensoes');
    const nome = document.getElementById('nome');
    
    if (imagemPrincipal) {
        imagemPrincipal.addEventListener('change', function() {
            validarArquivo(this);
        });
    }
    
    if (galeria) {
        galeria.addEventListener('change', function() {
            validarArquivo(this);
        });
    }
    
    if (codigoBarras) {
        codigoBarras.addEventListener('blur', function() {
            const codigo = this.value.trim();
            if (codigo && codigo.length === 13) {
                if (!validarCodigoBarras(codigo)) {
                    this.classList.add('is-invalid');
                    if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = 'Código de barras inválido';
                        this.parentNode.appendChild(feedback);
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const feedback = this.parentNode.querySelector('.invalid-feedback');
                    if (feedback) feedback.remove();
                }
            }
        });
        
        codigoBarras.addEventListener('change', function() {
            if (this.value.length === 13) {
                buscarProdutoPorCodigoBarras(this.value);
            }
        });
    }
    
    if (dimensoes) {
        dimensoes.addEventListener('input', function() {
            let value = this.value.replace(/[^\d.,x\s]/g, '');
            this.value = value;
        });
        dimensoes.addEventListener('blur', calcularVolume);
    }
    
    if (nome) {
        nome.addEventListener('blur', function() {
            const metaTitle = document.getElementById('meta_title');
            if (metaTitle && !metaTitle.value && this.value) {
                metaTitle.value = this.value;
            }
        });
    }

    // Event listeners para validação em tempo real
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        field.addEventListener('blur', destacarCamposObrigatorios);
        field.addEventListener('input', destacarCamposObrigatorios);
    });

    // Event listener para submit do formulário
    const form = document.getElementById('formCadastrarProduto');
    if (form) {
        form.addEventListener('submit', function(e) {
            const camposVazios = destacarCamposObrigatorios();
            
            if (camposVazios.length > 0) {
                e.preventDefault();
                alert(`Preencha os seguintes campos obrigatórios:\n• ${camposVazios.join('\n• ')}`);
                return false;
            }

            // Mostrar indicador de progresso
            mostrarProgresso(true);
            
            // Incrementar contador
            produtosCadastrados++;
            sessionStorage.setItem('produtos_cadastrados', produtosCadastrados);
            
            // Desabilitar botão de submit
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
                btn.disabled = true;
                
                // Se houver erro, restaurar botão após 10 segundos
                setTimeout(() => {
                    mostrarProgresso(false);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 10000);
            }
            
            // Limpar rascunho após envio
            setTimeout(() => {
                localStorage.removeItem('rascunho_produto');
                
                // Mostrar estatística se mais de 1 produto
                if (produtosCadastrados > 1) {
                    setTimeout(() => {
                        alert(`Parabéns! Você já cadastrou ${produtosCadastrados} produtos nesta sessão.`);
                    }, 2000);
                }
            }, 1000);
        });
    }

    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl+S para salvar
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            const form = document.getElementById('formCadastrarProduto');
            if (form) form.submit();
        }
        
        // Ctrl+R para limpar (substitui o refresh padrão)
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            limparFormulario();
        }
        
        // Easter egg - Código Konami
        konami.push(e.keyCode);
        if (konami.length > konamiCode.length) {
            konami = konami.slice(-konamiCode.length);
        }
        
        if (konami.join(',') === konamiCode.join(',')) {
            preencherExemplo();
            alert('🎉 Código Konami ativado! Produto de exemplo preenchido!');
            konami = [];
        }
    });

    // Backup automático antes de sair
    window.addEventListener('beforeunload', function() {
        salvarRascunho();
    });

    // Adicionar contadores de caracteres
    setTimeout(() => {
        adicionarContador('meta_title', 200);
        adicionarContador('meta_description', 300);
    }, 100);
}

// ================================
// FUNÇÕES AUXILIARES
// ================================

function getValue(id) {
    const element = document.getElementById(id);
    return element ? element.value : '';
}

function setValue(id, value) {
    const element = document.getElementById(id);
    if (element) element.value = value;
}

function setChecked(id, checked) {
    const element = document.getElementById(id);
    if (element) element.checked = checked;
}

// Função para buscar produto por código de barras (preparada para API)
async function buscarProdutoPorCodigoBarras(codigo) {
    if (codigo.length === 13 && validarCodigoBarras(codigo)) {
        try {
            // Exemplo de integração com API de produtos
            // const response = await fetch(`https://api.exemplo.com/produto/${codigo}`);
            // const produto = await response.json();
            // if (produto.nome) {
            //     if (confirm('Produto encontrado! Preencher dados automaticamente?')) {
            //         setValue('nome', produto.nome);
            //         setValue('marca', produto.marca);
            //         // ... outros campos
            //     }
            // }
            
            console.log('Busca por código de barras:', codigo);
        } catch (error) {
            console.log('Erro ao buscar produto:', error);
        }
    }
}

// Função para gerar QR code (preparada para biblioteca)
function gerarQRCode() {
    const sku = getValue('sku');
    if (sku && typeof QRCode !== 'undefined') {
        console.log('Gerando QR code para SKU:', sku);
        // Implementar geração de QR code se biblioteca estiver carregada
    }
}

// Função para duplicar produto (útil para produtos similares)
function duplicarProduto() {
    const nome = getValue('nome');
    if (nome) {
        setValue('nome', nome + ' - Cópia');
        setValue('sku', '');
        gerarSKU();
    }
}