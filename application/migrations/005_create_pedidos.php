<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_pedidos extends CI_Migration 
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
            'numero_pedido' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => FALSE
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ],
            'cupom_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pendente', 'confirmado', 'processando', 'enviado', 'entregue', 'cancelado', 'devolvido'],
                'default' => 'pendente',
                'null' => FALSE
            ],
            'forma_pagamento' => [
                'type' => 'ENUM',
                'constraint' => ['cartao_credito', 'cartao_debito', 'boleto', 'pix', 'transferencia'],
                'null' => TRUE
            ],
            'status_pagamento' => [
                'type' => 'ENUM',
                'constraint' => ['pendente', 'aprovado', 'rejeitado', 'estornado'],
                'default' => 'pendente',
                'null' => FALSE
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'default' => '0.00'
            ],
            'valor_desconto' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'default' => '0.00'
            ],
            'valor_frete' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'default' => '0.00'
            ],
            'total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'default' => '0.00'
            ],
            'endereco_entrega' => [
                'type' => 'TEXT',
                'null' => FALSE
            ],
            'endereco_cobranca' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'dados_frete' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'codigo_rastreamento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'observacoes_internas' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'data_pedido' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'data_confirmacao' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'data_envio' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'data_entrega' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'data_cancelamento' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'data_atualizacao' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        
        $this->dbforge->add_key('numero_pedido');
        $this->dbforge->add_key('usuario_id');
        $this->dbforge->add_key('cupom_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('data_pedido');
        
        $this->dbforge->create_table('pedidos');
        
        $this->db->query('ALTER TABLE pedidos ADD CONSTRAINT fk_pedidos_usuario 
                         FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
                         ON DELETE RESTRICT ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE pedidos ADD CONSTRAINT fk_pedidos_cupom 
                         FOREIGN KEY (cupom_id) REFERENCES cupons(id) 
                         ON DELETE SET NULL ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE pedidos ADD UNIQUE KEY unique_numero_pedido (numero_pedido)');
        
        echo "Tabela 'pedidos' criada com sucesso!\n";
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pedidos DROP FOREIGN KEY fk_pedidos_usuario');
        $this->db->query('ALTER TABLE pedidos DROP FOREIGN KEY fk_pedidos_cupom');
        
        $this->dbforge->drop_table('pedidos', TRUE);
        echo "Tabela 'pedidos' removida com sucesso!\n";
    }
}