<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_estoque extends CI_Migration 
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
            'produto_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'quantidade_atual' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 0
            ],
            'quantidade_minima' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 1
            ],
            'quantidade_reservada' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 0
            ],
            'localizacao' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'lote' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE
            ],
            'data_validade' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'custo_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ],
            'fornecedor' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => TRUE
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'data_ultima_entrada' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'data_ultima_saida' => [
                'type' => 'DATETIME',
                'null' => TRUE
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
        
        $this->dbforge->add_key('produto_id');
        
        $this->dbforge->create_table('estoque');
        
        $this->db->query('ALTER TABLE estoque ADD CONSTRAINT fk_estoque_produto 
                         FOREIGN KEY (produto_id) REFERENCES produtos(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE estoque ADD UNIQUE KEY unique_produto (produto_id)');
        
        echo "Tabela 'estoque' criada com sucesso!\n";
    }

    public function down()
    {
        $this->db->query('ALTER TABLE estoque DROP FOREIGN KEY fk_estoque_produto');
        
        $this->dbforge->drop_table('estoque', TRUE);
        echo "Tabela 'estoque' removida com sucesso!\n";
    }
}