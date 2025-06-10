<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_usuarios extends CI_Migration
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
                'constraint' => 100,
                'null' => FALSE
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => FALSE
            ],
            'senha' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ],
            'telefone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 14,
                'null' => TRUE
            ],
            'data_nascimento' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'endereco' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'cidade' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'estado' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => TRUE
            ],
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE
            ],
            'numero' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
                'comment' => 'Número do endereço'
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'Complemento do endereço (apto, bloco, etc)'
            ],
            'bairro' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'Bairro do endereço'
            ],
            'ativo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->dbforge->add_key('email');
        $this->dbforge->add_key('cpf');

        $this->dbforge->create_table('usuarios');

        $this->db->query('ALTER TABLE usuarios ADD UNIQUE KEY unique_email (email)');

        echo "Tabela 'usuarios' criada com sucesso!\n";
    }

    public function down()
    {
        $this->dbforge->drop_table('usuarios', TRUE);
        echo "Tabela 'usuarios' removida com sucesso!\n";
    }
}
