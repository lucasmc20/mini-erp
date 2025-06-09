<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_pedido_itens extends CI_Migration 
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
            'pedido_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'produto_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'quantidade' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 1
            ],
            'preco_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ],
            'preco_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ],
            'dados_produto' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'data_adicionado' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        
        $this->dbforge->add_key('pedido_id');
        $this->dbforge->add_key('produto_id');
        
        $this->dbforge->create_table('pedido_itens');
        
        $this->db->query('ALTER TABLE pedido_itens ADD CONSTRAINT fk_pedido_itens_pedido 
                         FOREIGN KEY (pedido_id) REFERENCES pedidos(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE pedido_itens ADD CONSTRAINT fk_pedido_itens_produto 
                         FOREIGN KEY (produto_id) REFERENCES produtos(id) 
                         ON DELETE RESTRICT ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE pedido_itens ADD UNIQUE KEY unique_pedido_produto (pedido_id, produto_id)');
        
        echo "Tabela 'pedido_itens' criada com sucesso!\n";
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pedido_itens DROP FOREIGN KEY fk_pedido_itens_pedido');
        $this->db->query('ALTER TABLE pedido_itens DROP FOREIGN KEY fk_pedido_itens_produto');
        
        $this->dbforge->drop_table('pedido_itens', TRUE);
        echo "Tabela 'pedido_itens' removida com sucesso!\n";
    }
}