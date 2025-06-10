<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_estoque extends CI_Migration 
{
    public function up()
    {
        // Criar tabela estoque
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
                'default' => 0,
                'comment' => 'Quantidade atual em estoque'
            ],
            'quantidade_minima' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 1,
                'comment' => 'Quantidade mínima para alerta'
            ],
            'quantidade_maxima' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'comment' => 'Quantidade máxima de estoque'
            ],
            'quantidade_reservada' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'default' => 0,
                'comment' => 'Quantidade reservada (pedidos pendentes)'
            ],
            'localizacao' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'Localização física no estoque'
            ],
            'lote' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Número do lote'
            ],
            'data_validade' => [
                'type' => 'DATE',
                'null' => TRUE,
                'comment' => 'Data de validade do produto'
            ],
            'data_fabricacao' => [
                'type' => 'DATE',
                'null' => TRUE,
                'comment' => 'Data de fabricação'
            ],
            'custo_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Custo unitário do produto'
            ],
            'preco_medio_custo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Preço médio de custo (média ponderada)'
            ],
            'fornecedor' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => TRUE,
                'comment' => 'Nome do fornecedor'
            ],
            'codigo_fornecedor' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Código do produto no fornecedor'
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Observações gerais'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['ativo', 'inativo', 'bloqueado'],
                'default' => 'ativo',
                'null' => FALSE,
                'comment' => 'Status do estoque'
            ],
            'data_ultima_entrada' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'Data da última entrada'
            ],
            'data_ultima_saida' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'Data da última saída'
            ],
            'data_ultima_contagem' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'Data da última contagem/inventário'
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
        $this->dbforge->add_key('produto_id');
        
        // Criar tabela
        $this->dbforge->create_table('estoque');
        
        // Adicionar foreign key e constraints
        $this->db->query('ALTER TABLE estoque ADD CONSTRAINT fk_estoque_produto 
                         FOREIGN KEY (produto_id) REFERENCES produtos(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE estoque ADD UNIQUE KEY unique_produto (produto_id)');
        
        // Índices adicionais
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_quantidade_atual (quantidade_atual)');
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_quantidade_minima (quantidade_minima)');
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_localizacao (localizacao)');
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_lote (lote)');
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_data_validade (data_validade)');
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_status (status)');
        $this->db->query('ALTER TABLE estoque ADD INDEX idx_fornecedor (fornecedor)');

        echo "Tabela 'estoque' criada com sucesso!\n";

        // Criar tabela de movimentações de estoque
        $this->criar_tabela_movimentacoes();
    }

    public function down()
    {
        // Remover foreign keys
        $this->db->query('ALTER TABLE estoque_movimentacoes DROP FOREIGN KEY IF EXISTS fk_movimentacao_produto');
        $this->db->query('ALTER TABLE estoque_movimentacoes DROP FOREIGN KEY IF EXISTS fk_movimentacao_usuario');
        $this->db->query('ALTER TABLE estoque DROP FOREIGN KEY IF EXISTS fk_estoque_produto');
        
        // Remover tabelas
        $this->dbforge->drop_table('estoque_movimentacoes', TRUE);
        $this->dbforge->drop_table('estoque', TRUE);
        
        echo "Tabelas 'estoque' e 'estoque_movimentacoes' removidas com sucesso!\n";
    }

    private function criar_tabela_movimentacoes()
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
            'tipo_movimentacao' => [
                'type' => 'ENUM',
                'constraint' => ['entrada', 'saida', 'ajuste', 'transferencia', 'inventario', 'perda', 'devolucao'],
                'null' => FALSE,
                'comment' => 'Tipo da movimentação'
            ],
            'quantidade' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'comment' => 'Quantidade movimentada (+ para entrada, - para saída)'
            ],
            'quantidade_anterior' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'comment' => 'Quantidade antes da movimentação'
            ],
            'quantidade_atual' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'comment' => 'Quantidade após a movimentação'
            ],
            'custo_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Custo unitário na movimentação'
            ],
            'valor_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Valor total da movimentação'
            ],
            'lote' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE
            ],
            'nota_fiscal' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Número da nota fiscal'
            ],
            'fornecedor' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => TRUE
            ],
            'motivo' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => TRUE,
                'comment' => 'Motivo da movimentação'
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'documento_referencia' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'Documento de referência (pedido, etc)'
            ],
            'data_movimentacao' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'Usuário que fez a movimentação'
            ],
            'ip_usuario' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => TRUE,
                'comment' => 'IP do usuário'
            ]
        ]);

        // Chave primária
        $this->dbforge->add_key('id', TRUE);
        
        // Índices
        $this->dbforge->add_key('produto_id');
        $this->dbforge->add_key('tipo_movimentacao');
        $this->dbforge->add_key('data_movimentacao');
        
        // Criar tabela
        $this->dbforge->create_table('estoque_movimentacoes');
        
        // Foreign keys
        $this->db->query('ALTER TABLE estoque_movimentacoes ADD CONSTRAINT fk_movimentacao_produto 
                         FOREIGN KEY (produto_id) REFERENCES produtos(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE');
        
        // Índices adicionais
        $this->db->query('ALTER TABLE estoque_movimentacoes ADD INDEX idx_lote (lote)');
        $this->db->query('ALTER TABLE estoque_movimentacoes ADD INDEX idx_nota_fiscal (nota_fiscal)');
        $this->db->query('ALTER TABLE estoque_movimentacoes ADD INDEX idx_usuario (usuario_id)');
        $this->db->query('ALTER TABLE estoque_movimentacoes ADD INDEX idx_data_tipo (data_movimentacao, tipo_movimentacao)');

        echo "Tabela 'estoque_movimentacoes' criada com sucesso!\n";
    }
}