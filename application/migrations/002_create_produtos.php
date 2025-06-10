<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_produtos extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => FALSE
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'categoria_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'Referência para tabela categorias'
            ],
            'marca' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'modelo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'codigo_barras' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE
            ],
            'preco' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'default' => '0.00'
            ],
            'preco_promocional' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ],
            'preco_custo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Preço de custo do produto'
            ],
            'peso' => [
                'type' => 'DECIMAL',
                'constraint' => '8,3',
                'null' => TRUE,
                'comment' => 'Peso em quilogramas'
            ],
            'dimensoes' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'Dimensões (LxAxP em cm)'
            ],
            'imagem_principal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'galeria_imagens' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'JSON com galeria de imagens'
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 220,
                'null' => TRUE,
                'comment' => 'URL amigável'
            ],
            'ativo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => FALSE
            ],
            'destaque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE
            ],
            'controlar_estoque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => FALSE,
                'comment' => 'Se deve controlar estoque (1=sim, 0=não)'
            ],
            'permite_venda_sem_estoque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
                'comment' => 'Permite venda mesmo sem estoque'
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
            'tags' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Tags do produto separadas por vírgula'
            ],
            'views' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 0,
                'comment' => 'Número de visualizações'
            ],
            'vendas_total' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => 0,
                'comment' => 'Total de vendas do produto'
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

        // Chave primária
        $this->dbforge->add_key('id', TRUE);

        // Índices
        $this->dbforge->add_key('sku');
        $this->dbforge->add_key('categoria_id');
        $this->dbforge->add_key('ativo');
        $this->dbforge->add_key('destaque');
        $this->dbforge->add_key('slug');

        // Criar tabela
        $this->dbforge->create_table('produtos');

        // Adicionar índices únicos e compostos
        $this->db->query('ALTER TABLE produtos ADD UNIQUE KEY unique_sku (sku)');
        $this->db->query('ALTER TABLE produtos ADD UNIQUE KEY unique_slug (slug)');
        $this->db->query('ALTER TABLE produtos ADD INDEX idx_preco (preco)');
        $this->db->query('ALTER TABLE produtos ADD INDEX idx_marca (marca)');
        $this->db->query('ALTER TABLE produtos ADD INDEX idx_controlar_estoque (controlar_estoque)');
        $this->db->query('ALTER TABLE produtos ADD INDEX idx_data_cadastro (data_cadastro)');

        // Foreign key para categoria será adicionada após criar tabela categorias
        echo "Tabela 'produtos' criada com sucesso!\n";
    }

    public function down()
    {
        // Remover foreign keys se existirem
        $this->db->query('ALTER TABLE produtos DROP FOREIGN KEY IF EXISTS fk_produto_categoria');
        
        $this->dbforge->drop_table('produtos', TRUE);
        echo "Tabela 'produtos' removida com sucesso!\n";
    }
}