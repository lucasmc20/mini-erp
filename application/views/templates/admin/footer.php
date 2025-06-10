</div>
</div>

<!-- Overlay para mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Floating Action Button -->
<div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">© <?php echo date('Y'); ?> Mini ERP. Todos os direitos reservados.</span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted">Versão 1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="<?php echo base_url('vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- Script personalizado -->
<script>
    // Toggle sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const toggleSidebar = document.getElementById('toggleSidebar');
        const toggleSidebarMobile = document.getElementById('toggleSidebarMobile');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        }

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.classList.add('sidebar-open');
        }

        if (toggleSidebar) {
            toggleSidebar.addEventListener('click', closeSidebar);
        }

        if (toggleSidebarMobile) {
            toggleSidebarMobile.addEventListener('click', openSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // Auto-close sidebar on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });

        // Animação de entrada
        const content = document.querySelector('.content-wrapper');
        if (content) {
            content.classList.add('fade-in');
            setTimeout(() => {
                content.classList.add('show');
            }, 100);
        }
    });

    // Confirmação de exclusão
    function confirmarExclusao(mensagem = 'Tem certeza que deseja excluir este item?') {
        return confirm(mensagem);
    }

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert && alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Popover initialization
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
</script>

<?php if (isset($js_adicional)): ?>
    <?php foreach ($js_adicional as $js): ?>
        <script src="<?php echo base_url($js); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($js_inline)): ?>
    <script>
        <?php echo $js_inline; ?>
    </script>
<?php endif; ?>

</body>
</html>