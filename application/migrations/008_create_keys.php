<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_keys extends CI_Migration 
{
    public function up()
    {
        // Adicionar foreign key de produtos para categorias
        $this->db->query('ALTER TABLE produtos ADD CONSTRAINT fk_produto_categoria 
                         FOREIGN KEY (categoria_id) REFERENCES categorias(id) 
                         ON DELETE SET NULL ON UPDATE CASCADE');

        // Adicionar foreign key de estoque_movimentacoes para usuarios (se tabela usuarios existir)
        if ($this->db->table_exists('usuarios')) {
            $this->db->query('ALTER TABLE estoque_movimentacoes ADD CONSTRAINT fk_movimentacao_usuario 
                             FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
        }

        // Adicionar foreign keys para audit trail em produtos (se tabela usuarios existir)
        if ($this->db->table_exists('usuarios')) {
            $this->db->query('ALTER TABLE produtos ADD CONSTRAINT fk_produto_usuario_cadastro 
                             FOREIGN KEY (usuario_cadastro) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
                             
            $this->db->query('ALTER TABLE produtos ADD CONSTRAINT fk_produto_usuario_atualizacao 
                             FOREIGN KEY (usuario_atualizacao) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
        }

        // Adicionar foreign keys para audit trail em estoque (se tabela usuarios existir)
        if ($this->db->table_exists('usuarios')) {
            $this->db->query('ALTER TABLE estoque ADD CONSTRAINT fk_estoque_usuario_cadastro 
                             FOREIGN KEY (usuario_cadastro) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
                             
            $this->db->query('ALTER TABLE estoque ADD CONSTRAINT fk_estoque_usuario_atualizacao 
                             FOREIGN KEY (usuario_atualizacao) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
        }

        // Adicionar foreign keys para audit trail em categorias (se tabela usuarios existir)
        if ($this->db->table_exists('usuarios')) {
            $this->db->query('ALTER TABLE categorias ADD CONSTRAINT fk_categoria_usuario_cadastro 
                             FOREIGN KEY (usuario_cadastro) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
                             
            $this->db->query('ALTER TABLE categorias ADD CONSTRAINT fk_categoria_usuario_atualizacao 
                             FOREIGN KEY (usuario_atualizacao) REFERENCES usuarios(id) 
                             ON DELETE SET NULL ON UPDATE CASCADE');
        }

        echo "Foreign keys adicionadas com sucesso!\n";
    }

    public function down()
    {
        // Remover foreign keys
        $this->db->query('ALTER TABLE produtos DROP FOREIGN KEY IF EXISTS fk_produto_categoria');
        $this->db->query('ALTER TABLE produtos DROP FOREIGN KEY IF EXISTS fk_produto_usuario_cadastro');
        $this->db->query('ALTER TABLE produtos DROP FOREIGN KEY IF EXISTS fk_produto_usuario_atualizacao');
        
        $this->db->query('ALTER TABLE estoque DROP FOREIGN KEY IF EXISTS fk_estoque_usuario_cadastro');
        $this->db->query('ALTER TABLE estoque DROP FOREIGN KEY IF EXISTS fk_estoque_usuario_atualizacao');
        
        $this->db->query('ALTER TABLE categorias DROP FOREIGN KEY IF EXISTS fk_categoria_usuario_cadastro');
        $this->db->query('ALTER TABLE categorias DROP FOREIGN KEY IF EXISTS fk_categoria_usuario_atualizacao');
        
        $this->db->query('ALTER TABLE estoque_movimentacoes DROP FOREIGN KEY IF EXISTS fk_movimentacao_usuario');

        echo "Foreign keys removidas com sucesso!\n";
    }
}