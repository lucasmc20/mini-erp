<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? $titulo . ' - ' : ''; ?>Admin - Mini ERP</title>

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url('vendor/twbs/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Font Awesome para ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- CSS Personalizado -->
    <link href="<?php echo base_url('assets/css/admin.css'); ?>" rel="stylesheet">

    <?php if (isset($css_adicional)): ?>
        <?php foreach ($css_adicional as $css): ?>
            <link href="<?php echo base_url($css); ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4 class="sidebar-title">
            <i class="fas fa-cubes me-2"></i>
            Mini ERP
        </h4>
        <button class="btn-toggle-sidebar d-lg-none" id="toggleSidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>"
                   href="<?php echo base_url('dashboard'); ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(1) == 'produto') ? 'active' : ''; ?>"
                   href="<?php echo base_url('produto'); ?>">
                    <i class="fas fa-box"></i>
                    <span>Produtos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(1) == 'estoque') ? 'active' : ''; ?>"
                   href="<?php echo base_url('estoque'); ?>">
                    <i class="fas fa-warehouse"></i>
                    <span>Estoque</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(1) == 'cupom') ? 'active' : ''; ?>"
                   href="<?php echo base_url('cupom'); ?>">
                    <i class="fas fa-tags"></i>
                    <span>Cupons</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(1) == 'pedidos') ? 'active' : ''; ?>"
                   href="<?php echo base_url('pedido'); ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pedidos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(1) == 'usuarios') ? 'active' : ''; ?>"
                   href="<?php echo base_url('usuario'); ?>">
                    <i class="fas fa-users"></i>
                    <span>Usuários</span>
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($this->uri->segment(2) == 'perfil') ? 'active' : ''; ?>"
                   href="<?php echo base_url('dashboard/perfil'); ?>">
                    <i class="fas fa-user-cog"></i>
                    <span>Perfil</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="<?php echo base_url('auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-outline-secondary d-lg-none me-3" id="toggleSidebarMobile">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                       id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <span class="d-none d-md-inline">
                                <?php echo $this->session->userdata('usuario_nome'); ?>
                            </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url('dashboard/perfil'); ?>">
                                <i class="fas fa-user-cog me-2"></i>
                                Perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?php echo base_url('auth/logout'); ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content-wrapper">
        <?php if (isset($breadcrumb)): ?>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url('dashboard'); ?>">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <?php foreach ($breadcrumb as $item): ?>
                        <?php if (isset($item['url'])): ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo $item['url']; ?>"><?php echo $item['titulo']; ?></a>
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item active"><?php echo $item['titulo']; ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>

        <?php if (isset($titulo_pagina)): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?php echo $titulo_pagina; ?></h1>
                <?php if (isset($botao_acao)): ?>
                    <a href="<?php echo $botao_acao['url']; ?>" class="btn btn-montink">
                        <?php if (isset($botao_acao['icone'])): ?>
                            <i class="<?php echo $botao_acao['icone']; ?> me-2"></i>
                        <?php endif; ?>
                        <?php echo $botao_acao['texto']; ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Mensagens de Flash -->
        <?php if ($this->session->flashdata('sucesso')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $this->session->flashdata('sucesso'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('erro')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $this->session->flashdata('erro'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('aviso')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $this->session->flashdata('aviso'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $sucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $erro; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
<?php endif; ?>