<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        // Parâmetros de paginação e filtros
        $limite = 20;
        $pagina = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($pagina - 1) * $limite;

        $filtros = [
            'nome' => $this->input->get('nome'),
            'email' => $this->input->get('email'),
            'ativo' => $this->input->get('ativo')
        ];

        $usuarios = $this->Usuario_model->listar_usuarios($filtros, $limite, $offset);
        $total_usuarios = $this->Usuario_model->contar_usuarios($filtros);

        $data = [
            'usuarios' => $usuarios,
            'filtros' => $filtros,
            'paginacao' => [
                'total' => $total_usuarios,
                'por_pagina' => $limite,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total_usuarios / $limite)
            ]
        ];

        // Configuração do template admin
        $config = [
            'titulo' => 'Usuários',
            'titulo_pagina' => 'Gerenciar Usuários',
            'css_adicional' => ['assets/css/usuarios.css'],
            'js_adicional' => ['assets/js/usuarios.js']
        ];

        carregar_template_admin('admin/usuario/listar', $data, $config);
    }

    public function cadastrar()
    {
        $data = [
            'titulo' => 'Cadastrar Usuário'
        ];

        if ($this->input->post()) {
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[usuarios.email]');
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');
            $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'required|matches[senha]');
            $this->form_validation->set_rules('telefone', 'Telefone', 'min_length[10]|max_length[15]');

            if ($this->form_validation->run()) {
                $dados_usuario = [
                    'nome' => $this->input->post('nome', true),
                    'email' => $this->input->post('email', true),
                    'senha' => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                    'telefone' => $this->input->post('telefone', true),
                    'ativo' => 1,
                    'data_cadastro' => date('Y-m-d H:i:s'),
                    'data_atualizacao' => date('Y-m-d H:i:s')
                ];

                if ($this->Usuario_model->criar_usuario($dados_usuario)) {
                    $this->session->set_flashdata('sucesso', 'Usuário cadastrado com sucesso!');
                    redirect('usuario');
                } else {
                    $this->session->set_flashdata('erro', 'Erro ao cadastrar usuário. Tente novamente.');
                }
            }
        }

        // Template admin
        $config = [
            'titulo' => 'Cadastrar Usuário',
            'titulo_pagina' => 'Cadastrar Usuário',
            'css_adicional' => ['assets/css/usuarios.css'],
            'js_adicional' => ['assets/js/usuarios.js']
        ];
        carregar_template_admin('admin/usuario/cadastrar', $data, $config);
    }

    public function editar($id = null)
    {
        if (!$id) show_404();

        $usuario = $this->Usuario_model->buscar_por_id($id);
        if (!$usuario) show_404();

        $data = [
            'titulo' => 'Editar Usuário',
            'usuario' => $usuario
        ];

        if ($this->input->post()) {
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|callback_email_unico[' . $id . ']');
            $this->form_validation->set_rules('telefone', 'Telefone', 'min_length[10]|max_length[15]');

            if ($this->input->post('senha')) {
                $this->form_validation->set_rules('senha', 'Senha', 'min_length[6]');
                $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'matches[senha]');
            }

            if ($this->form_validation->run()) {
                $dados_usuario = [
                    'nome'     => $this->input->post('nome', true),
                    'email'    => $this->input->post('email', true),
                    'telefone' => $this->input->post('telefone', true),
                    'data_atualizacao' => date('Y-m-d H:i:s')
                ];
                if ($this->input->post('senha')) {
                    $dados_usuario['senha'] = password_hash($this->input->post('senha'), PASSWORD_DEFAULT);
                }

                if ($this->Usuario_model->atualizar_usuario($id, $dados_usuario)) {
                    $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso!');
                    redirect('usuario');
                } else {
                    $this->session->set_flashdata('erro', 'Erro ao atualizar usuário. Tente novamente.');
                    $data['usuario'] = (object)array_merge((array)$usuario, $dados_usuario);
                }
            } else {
                $data['usuario'] = (object)array_merge((array)$usuario, $this->input->post());
            }
        }

        $config = [
            'titulo' => 'Editar Usuário',
            'titulo_pagina' => 'Editar Usuário',
            'css_adicional' => ['assets/css/usuarios.css'],
            'js_adicional' => ['assets/js/usuarios.js']
        ];
        carregar_template_admin('admin/usuario/editar', $data, $config);
    }

    public function visualizar($id = null)
    {
        if (!$id) show_404();

        $usuario = $this->Usuario_model->buscar_por_id($id);
        if (!$usuario) show_404();

        $data = [
            'titulo' => 'Visualizar Usuário',
            'usuario' => $usuario
        ];

        $config = [
            'titulo' => 'Visualizar Usuário',
            'titulo_pagina' => 'Visualizar Usuário',
            'css_adicional' => ['assets/css/usuarios.css'],
            'js_adicional' => ['assets/js/usuarios.js']
        ];
        carregar_template_admin('admin/usuario/visualizar', $data, $config);
    }

    public function excluir($id = null)
    {
        if (!$id) show_404();

        $usuario = $this->Usuario_model->buscar_por_id($id);
        if (!$usuario) show_404();

        if ($this->Usuario_model->atualizar_usuario($id, ['ativo' => 0, 'data_atualizacao' => date('Y-m-d H:i:s')])) {
            $this->session->set_flashdata('sucesso', 'Usuário removido com sucesso!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao remover usuário. Tente novamente.');
        }

        redirect('usuario');
    }

    // Callback de validação de e-mail único na edição
    public function email_unico($email, $id)
    {
        if ($this->Usuario_model->email_existe($email, $id)) {
            $this->form_validation->set_message('email_unico', 'Este e-mail já está sendo usado por outro usuário.');
            return false;
        }
        return true;
    }
}
