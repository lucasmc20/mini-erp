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
            'categoria' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'marca' => [
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
            'peso' => [
                'type' => 'DECIMAL',
                'constraint' => '8,3',
                'null' => TRUE
            ],
            'dimensoes' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'imagem_principal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'galeria_imagens' => [
                'type' => 'TEXT',
                'null' => TRUE
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
            'data_cadastro' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'data_atualizacao' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);

        $this->dbforge->add_key('sku');
        $this->dbforge->add_key('categoria');
        $this->dbforge->add_key('ativo');

        $this->dbforge->create_table('produtos');

        $this->db->query('ALTER TABLE produtos ADD UNIQUE KEY unique_sku (sku)');

        echo "Tabela 'produtos' criada com sucesso!\n";
    }

    public function down()
    {
        $this->dbforge->drop_table('produtos', TRUE);
        echo "Tabela 'produtos' removida com sucesso!\n";
    }
}
