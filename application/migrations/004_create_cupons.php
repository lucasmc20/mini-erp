<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_cupons extends CI_Migration 
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
            'codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => FALSE
            ],
            'tipo_desconto' => [
                'type' => 'ENUM',
                'constraint' => ['percentual', 'valor_fixo'],
                'default' => 'percentual',
                'null' => FALSE
            ],
            'valor_desconto' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ],
            'valor_minimo_pedido' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ],
            'valor_maximo_desconto' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ],
            'limite_uso_total' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            ],
            'limite_uso_usuario' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'null' => FALSE
            ],
            'total_usado' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => FALSE
            ],
            'data_inicio' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'data_fim' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'categorias_permitidas' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'produtos_permitidos' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'primeiro_pedido_apenas' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE
            ],
            'ativo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => FALSE
            ],
            'observacoes' => [
                'type' => 'TEXT',
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
        
        $this->dbforge->add_key('codigo');
        $this->dbforge->add_key('ativo');
        $this->dbforge->add_key(['data_inicio', 'data_fim']);
        
        $this->dbforge->create_table('cupons');
        
        $this->db->query('ALTER TABLE cupons ADD UNIQUE KEY unique_codigo (codigo)');
        
        echo "Tabela 'cupons' criada com sucesso!\n";
    }

    public function down()
    {
        $this->dbforge->drop_table('cupons', TRUE);
        echo "Tabela 'cupons' removida com sucesso!\n";
    }
}