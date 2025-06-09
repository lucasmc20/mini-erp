<div class="row">
    <div class="col-md-12">
        <!-- Alert de boas-vindas -->
        <?php echo bootstrap_alert('Bem-vindo ao Mini ERP! Sistema funcionando corretamente.', 'success'); ?>
        
        <!-- Card principal -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">🎉 Mini ERP - Sistema Funcionando!</h4>
            </div>
            <div class="card-body">
                <p class="lead">Seu ambiente está configurado e funcionando perfeitamente!</p>
                
                <h5>✅ Componentes ativos:</h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        CodeIgniter 3
                        <span class="badge bg-success rounded-pill">Funcionando</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Bootstrap 5
                        <span class="badge bg-success rounded-pill">Carregado</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Docker + Apache
                        <span class="badge bg-success rounded-pill">Rodando</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        MySQL Local
                        <span class="badge bg-info rounded-pill">Conectado</span>
                    </li>
                </ul>

                <div class="row">
                    <div class="col-md-4">
                        <?php echo bootstrap_button('📦 Produtos', '#', 'btn-primary'); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo bootstrap_button('💰 Vendas', '#', 'btn-success'); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo bootstrap_button('📊 Relatórios', '#', 'btn-info'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações técnicas -->
        <div class="mt-4">
            <h5>🔧 Informações do Sistema:</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title">PHP</h6>
                            <p class="card-text">Versão: <?php echo phpversion(); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="card-title">CodeIgniter</h6>
                            <p class="card-text">Versão: <?php echo CI_VERSION; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de próximos passos -->
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning">
                <h5 class="mb-0">🚀 Próximos Passos</h5>
            </div>
            <div class="card-body">
                <ol>
                    <li>Configure sua conexão com MySQL local</li>
                    <li>Crie seus controllers e models</li>
                    <li>Desenvolva as funcionalidades do ERP</li>
                    <li>Customize o layout conforme necessário</li>
                </ol>
            </div>
        </div>
    </div>
</div>