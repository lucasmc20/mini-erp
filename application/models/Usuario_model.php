<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model
{
    private $tabela = 'usuarios';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function verificar_login($email, $senha)
    {
        $this->db->where('email', $email);
        $this->db->where('ativo', 1);
        $usuario = $this->db->get($this->tabela)->row();

        if ($usuario && password_verify($senha, $usuario->senha)) {
            return $usuario;
        }
        return false;
    }

    public function buscar_por_id($id)
    {
        $this->db->where('id', $id);
        $this->db->where('ativo', 1);
        return $this->db->get($this->tabela)->row();
    }

    public function buscar_por_email($email)
    {
        $this->db->where('email', $email);
        return $this->db->get($this->tabela)->row();
    }

    public function criar_usuario($dados)
    {
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $dados['data_cadastro'] = date('Y-m-d H:i:s');
        
        return $this->db->insert($this->tabela, $dados);
    }

    public function atualizar_usuario($id, $dados)
    {
        $dados['data_atualizacao'] = date('Y-m-d H:i:s');
        
        if (isset($dados['senha'])) {
            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->tabela, $dados);
    }

    public function email_existe($email, $id_excluir = null)
    {
        $this->db->where('email', $email);
        if ($id_excluir) {
            $this->db->where('id !=', $id_excluir);
        }
        return $this->db->get($this->tabela)->num_rows() > 0;
    }

    public function listar_usuarios($limite = null, $offset = 0)
    {
        $this->db->where('ativo', 1);
        $this->db->order_by('nome', 'ASC');
        
        if ($limite) {
            $this->db->limit($limite, $offset);
        }
        
        return $this->db->get($this->tabela)->result();
    }
}