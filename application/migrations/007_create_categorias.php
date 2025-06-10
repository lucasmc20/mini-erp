<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_categorias extends CI_Migration 
{
    public function up()
    {
        // Criar tabela de categorias
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => TRUE
            ],
            'imagem' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'cor_destaque' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
                'null' => TRUE,
                'comment' => 'Cor em hexadecimal para destacar categoria'
            ],
            'icone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Classe do ícone (ex: fa-shopping-cart)'
            ],
            'ordem' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'default' => 0,
                'comment' => 'Ordem de exibição'
            ],
            'categoria_pai_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'Para subcategorias'
            ],
            'nivel' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'unsigned' => TRUE,
                'default' => 1,
                'comment' => 'Nível da categoria (1=principal, 2=sub, etc)'
            ],
            'ativo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => TRUE,
                'default' => 1
            ],
            'destaque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => TRUE,
                'default' => 0,
                'comment' => 'Categoria em destaque'
            ],
            'meta_title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => TRUE,
                'comment' => 'SEO - Título da página'
            ],
            'meta_description' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'SEO - Descrição da página'
            ],
            'meta_keywords' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'SEO - Palavras-chave'
            ],
            'data_cadastro' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'data_atualizacao' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'usuario_cadastro' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ],
            'usuario_atualizacao' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ]
        ]);

        // Definir chave primária
        $this->dbforge->add_key('id', TRUE);

        // Adicionar índices (removido o 'slug' para evitar duplicação)
        $this->dbforge->add_key('nome');
        $this->dbforge->add_key('categoria_pai_id');
        $this->dbforge->add_key('ativo');
        $this->dbforge->add_key('ordem');

        // Criar tabela
        $this->dbforge->create_table('categorias');

        // Adicionar índices únicos após criação da tabela
        $this->db->query('ALTER TABLE categorias ADD UNIQUE KEY unique_slug (slug)');

        // Adicionar foreign key para categoria pai (auto-relacionamento)
        $this->db->query('ALTER TABLE categorias ADD CONSTRAINT fk_categoria_pai FOREIGN KEY (categoria_pai_id) REFERENCES categorias(id) ON DELETE SET NULL ON UPDATE CASCADE');

        // Inserir categorias padrão
        $this->db->insert_batch('categorias', [
            [
                'nome' => 'Eletrônicos',
                'descricao' => 'Produtos eletrônicos em geral',
                'slug' => 'eletronicos',
                'cor_destaque' => '#007bff',
                'icone' => 'fa-laptop',
                'ordem' => 1,
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ],
            [
                'nome' => 'Roupas e Acessórios',
                'descricao' => 'Vestuário e acessórios de moda',
                'slug' => 'roupas-acessorios',
                'cor_destaque' => '#28a745',
                'icone' => 'fa-tshirt',
                'ordem' => 2,
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ],
            [
                'nome' => 'Casa e Jardim',
                'descricao' => 'Produtos para casa, decoração e jardim',
                'slug' => 'casa-jardim',
                'cor_destaque' => '#ffc107',
                'icone' => 'fa-home',
                'ordem' => 3,
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ],
            [
                'nome' => 'Esportes e Lazer',
                'descricao' => 'Artigos esportivos e de lazer',
                'slug' => 'esportes-lazer',
                'cor_destaque' => '#dc3545',
                'icone' => 'fa-futbol',
                'ordem' => 4,
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ],
            [
                'nome' => 'Livros e Mídia',
                'descricao' => 'Livros, CDs, DVDs e mídias em geral',
                'slug' => 'livros-midia',
                'cor_destaque' => '#6f42c1',
                'icone' => 'fa-book',
                'ordem' => 5,
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ],
            [
                'nome' => 'Alimentação',
                'descricao' => 'Alimentos e bebidas',
                'slug' => 'alimentacao',
                'cor_destaque' => '#fd7e14',
                'icone' => 'fa-utensils',
                'ordem' => 6,
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ]
        ]);

        echo "Tabela 'categorias' criada com sucesso!\n";
    }

    public function down()
    {
        // Remover foreign key primeiro
        $this->db->query('ALTER TABLE categorias DROP FOREIGN KEY IF EXISTS fk_categoria_pai');
        
        // Remover tabela
        $this->dbforge->drop_table('categorias');
        
        echo "Tabela 'categorias' removida com sucesso!\n";
    }
}