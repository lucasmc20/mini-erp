<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Mini ERP'; ?></title>
    
    <!-- Bootstrap CSS -->
    <?php echo load_bootstrap_css(); ?>
    
    <!-- Estilos customizados -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">Mini ERP</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Em desenvolvimento')">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Em desenvolvimento')">Vendas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Em desenvolvimento')">Relatórios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-4">