<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'titulo' => 'Gerenciar Usuários',
            'usuarios' => $this->Usuario_model->listar_usuarios(),
            'total_usuarios' => count($this->Usuario_model->listar_usuarios())
        ];
        
        $this->load->view('usuario/listar', $data);
    }

    public function cadastrar()
    {
        $data['titulo'] = 'Cadastrar Usuário';

        if ($this->input->post()) {
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[usuarios.email]');
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');
            $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'required|matches[senha]');
            $this->form_validation->set_rules('telefone', 'Telefone', 'min_length[10]|max_length[15]');

            if ($this->form_validation->run()) {
                $dados_usuario = [
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'senha' => $this->input->post('senha'),
                    'telefone' => $this->input->post('telefone'),
                    'ativo' => 1
                ];

                if ($this->Usuario_model->criar_usuario($dados_usuario)) {
                    $this->session->set_flashdata('sucesso', 'Usuário cadastrado com sucesso!');
                    redirect('usuario');
                } else {
                    $this->session->set_flashdata('erro', 'Erro ao cadastrar usuário. Tente novamente.');
                }
            }
        }

        $this->load->view('usuario/cadastrar', $data);
    }

    public function editar($id = null)
    {
        if (!$id) {
            show_404();
        }

        $usuario = $this->Usuario_model->buscar_por_id($id);
        if (!$usuario) {
            show_404();
        }

        $data = [
            'titulo' => 'Editar Usuário',
            'usuario' => $usuario
        ];

        if ($this->input->post()) {
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
            $this->form_validation->set_rules('telefone', 'Telefone', 'min_length[10]|max_length[15]');
            
            // Validar senha apenas se foi preenchida
            if ($this->input->post('senha')) {
                $this->form_validation->set_rules('senha', 'Senha', 'min_length[6]');
                $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'matches[senha]');
            }

            // Verificar se o email já existe para outro usuário
            if ($this->Usuario_model->email_existe($this->input->post('email'), $id)) {
                $this->form_validation->set_rules('email', 'E-mail', 'callback_email_unico');
            }

            if ($this->form_validation->run()) {
                $dados_usuario = [
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone')
                ];

                // Incluir senha apenas se foi preenchida
                if ($this->input->post('senha')) {
                    $dados_usuario['senha'] = $this->input->post('senha');
                }

                if ($this->Usuario_model->atualizar_usuario($id, $dados_usuario)) {
                    $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso!');
                    redirect('usuario');
                } else {
                    $this->session->set_flashdata('erro', 'Erro ao atualizar usuário. Tente novamente.');
                }
            }
        }

        $this->load->view('usuario/editar', $data);
    }

    public function visualizar($id = null)
    {
        if (!$id) {
            show_404();
        }

        $usuario = $this->Usuario_model->buscar_por_id($id);
        if (!$usuario) {
            show_404();
        }

        $data = [
            'titulo' => 'Visualizar Usuário',
            'usuario' => $usuario
        ];

        $this->load->view('usuario/visualizar', $data);
    }

    public function excluir($id = null)
    {
        if (!$id) {
            show_404();
        }

        $usuario = $this->Usuario_model->buscar_por_id($id);
        if (!$usuario) {
            show_404();
        }

        // Soft delete - apenas marca como inativo
        if ($this->Usuario_model->atualizar_usuario($id, ['ativo' => 0])) {
            $this->session->set_flashdata('sucesso', 'Usuário removido com sucesso!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao remover usuário. Tente novamente.');
        }

        redirect('usuario');
    }

    // Validação customizada para email único
    public function email_unico($email)
    {
        if ($this->Usuario_model->email_existe($email, $this->uri->segment(3))) {
            $this->form_validation->set_message('email_unico', 'Este e-mail já está sendo usado por outro usuário.');
            return false;
        }
        return true;
    }
}