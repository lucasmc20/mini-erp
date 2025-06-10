<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model
{
    /** @var string Nome da tabela de usuários */
    protected $tabela = 'usuarios';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buscar usuário por ID
     *
     * @param int $id
     * @return object|null
     */
    public function buscar_por_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->tabela)
            ->row();
    }

    /**
     * Buscar usuário por e-mail
     *
     * @param string $email
     * @return object|null
     */
    public function buscar_por_email($email)
    {
        return $this->db
            ->where('email', trim(strtolower($email)))
            ->get($this->tabela)
            ->row();
    }

    /**
     * Buscar usuário por token de recuperação
     *
     * @param string $token_hash
     * @return object|null
     */
    public function buscar_por_token($token_hash)
    {
        return $this->db
            ->where('token_recuperacao', $token_hash)
            ->get($this->tabela)
            ->row();
    }

    /**
     * Criar novo usuário (cadastra e já hash da senha)
     *
     * @param array $dados Deve conter nome, email, senha (plain), etc.
     * @return bool
     */
    public function criar_usuario(array $dados)
    {
        // normalizações
        $dados['email']      = trim(strtolower($dados['email']));
        $dados['senha']      = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $dados['data_cadastro'] = date('Y-m-d H:i:s');

        return $this->db->insert($this->tabela, $dados);
    }

    /**
     * Atualizar dados do usuário
     *
     * @param int   $id
     * @param array $dados
     * @return bool
     */
    public function atualizar_usuario($id, array $dados)
    {
        $dados['data_atualizacao'] = date('Y-m-d H:i:s');

        return $this->db
            ->where('id', $id)
            ->update($this->tabela, $dados);
    }

    /**
     * Verificar se um e-mail já está cadastrado
     *
     * @param string $email
     * @return bool
     */
    public function email_existe($email)
    {
        return $this->db
                ->where('email', trim(strtolower($email)))
                ->count_all_results($this->tabela) > 0;
    }

    /**
     * Validar login (e-mail + senha)
     *
     * @param string $email
     * @param string $senha
     * @return object|false Retorna o objeto usuário (sem senha) ou false
     */
    public function validar_login($email, $senha)
    {
        $usuario = $this->buscar_por_email($email);
        if ($usuario && password_verify($senha, $usuario->senha)) {
            unset($usuario->senha);
            return $usuario;
        }
        return false;
    }

    /**
     * Verificar senha de um usuário
     *
     * @param int    $id
     * @param string $senha plain text
     * @return bool
     */
    public function verificar_senha($id, $senha)
    {
        $usuario = $this->buscar_por_id($id);
        return $usuario
            ? password_verify($senha, $usuario->senha)
            : false;
    }

    /**
     * Alterar senha de um usuário
     *
     * @param int    $id
     * @param string $nova_senha plain text
     * @return bool
     */
    public function alterar_senha($id, $nova_senha)
    {
        $dados = [
            'senha' => password_hash($nova_senha, PASSWORD_DEFAULT),
            'data_atualizacao' => date('Y-m-d H:i:s')
        ];
        return $this->db
            ->where('id', $id)
            ->update($this->tabela, $dados);
    }

    /**
     * Listar todos os usuários
     *
     * @return array
     */
    public function listar_todos()
    {
        return $this->db
            ->select('id, nome, email, telefone, ativo, data_cadastro')
            ->order_by('nome', 'ASC')
            ->get($this->tabela)
            ->result();
    }

    /**
     * Contar usuários ativos
     *
     * @return int
     */
    public function contar_ativos()
    {
        return $this->db
            ->where('ativo', 1)
            ->count_all_results($this->tabela);
    }
}
