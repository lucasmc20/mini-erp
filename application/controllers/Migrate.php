<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migrate extends CI_Controller 
{ 
    // Declarar propriedades para evitar warnings do PHP 8.2+
    public $dbforge;
    public $migration;
    public $db;
    public $session;
    public $log;
    public $benchmark;
    public $hooks;
    public $config;
    public $utf8;
    public $uri;
    public $exceptions;
    public $router;
    public $output;
    public $security;
    public $input;
    public $lang;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar se está em ambiente de desenvolvimento
        if (ENVIRONMENT !== 'development') {
            show_404();
        }
    }

    public function index() 
    { 
        // Carregar bibliotecas necessárias (nome correto em minúsculo)
        $this->load->library('migration');
        $this->load->dbutil(); // Para operações de banco
        
        echo "<h2>Executando Migrations...</h2>\n";
        
        // Executar migrations
        if ($this->migration->current() === FALSE) {
            echo "<p style='color: red;'>ERRO: " . $this->migration->error_string() . "</p>";
        } else {
            echo "<p style='color: green;'>✅ Migrations executadas com sucesso!</p>";
        }
        
        // Mostrar versão atual
        echo "<p>Versão atual do banco: " . $this->migration->get_version() . "</p>";
    }

    /**
     * Executar migration específica
     */
    public function to($version = null)
    {
        if (ENVIRONMENT !== 'development') {
            show_404();
        }

        $this->load->library('migration');
        
        if ($version === null) {
            echo "<p style='color: red;'>Por favor, especifique uma versão.</p>";
            return;
        }

        echo "<h2>Migrando para versão: {$version}</h2>\n";
        
        if ($this->migration->version($version) === FALSE) {
            echo "<p style='color: red;'>ERRO: " . $this->migration->error_string() . "</p>";
        } else {
            echo "<p style='color: green;'>✅ Migration para versão {$version} executada com sucesso!</p>";
        }
    }

    /**
     * Fazer rollback da última migration
     */
    public function rollback()
    {
        if (ENVIRONMENT !== 'development') {
            show_404();
        }

        $this->load->library('migration');
        
        $current_version = $this->migration->get_version();
        $target_version = max(0, $current_version - 1);
        
        echo "<h2>Fazendo rollback da versão {$current_version} para {$target_version}</h2>\n";
        
        if ($this->migration->version($target_version) === FALSE) {
            echo "<p style='color: red;'>ERRO: " . $this->migration->error_string() . "</p>";
        } else {
            echo "<p style='color: green;'>✅ Rollback executado com sucesso!</p>";
        }
    }

    /**
     * Mostrar status das migrations
     */
    public function status()
    {
        if (ENVIRONMENT !== 'development') {
            show_404();
        }

        $this->load->library('migration');
        
        echo "<h2>Status das Migrations</h2>\n";
        echo "<p>Versão atual: " . $this->migration->get_version() . "</p>";
        
        // Listar arquivos de migration disponíveis
        $migration_path = APPPATH . 'migrations/';
        if (is_dir($migration_path)) {
            $files = scandir($migration_path);
            echo "<h3>Migrations disponíveis:</h3>";
            echo "<ul>";
            foreach ($files as $file) {
                if (substr($file, -4) === '.php' && $file !== 'index.html') {
                    echo "<li>{$file}</li>";
                }
            }
            echo "</ul>";
        }
    }

    /**
     * Reset completo - CUIDADO!
     */
    public function reset()
    {
        if (ENVIRONMENT !== 'development') {
            show_404();
        }

        $this->load->library('migration');
        
        echo "<h2>⚠️  RESET COMPLETO - Todas as tabelas serão removidas!</h2>\n";
        
        if ($this->migration->version(0) === FALSE) {
            echo "<p style='color: red;'>ERRO: " . $this->migration->error_string() . "</p>";
        } else {
            echo "<p style='color: orange;'>✅ Reset executado - Banco zerado!</p>";
        }
    }
}