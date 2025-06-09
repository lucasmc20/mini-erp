<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        
        if (!$this->session->userdata('usuario_logado')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['usuario'] = [
            'nome' => $this->session->userdata('usuario_nome'),
            'email' => $this->session->userdata('usuario_email')
        ];
        
        $this->load->view('dashboard/home', $data);
    }

    public function perfil()
    {
        $this->load->model('Usuario_model');
        $usuario_id = $this->session->userdata('usuario_id');
        
        if ($this->input->post()) {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
            $this->form_validation->set_rules('telefone', 'Telefone', 'trim');
            
            if ($this->form_validation->run()) {
                $dados = [
                    'nome' => $this->input->post('nome'),
                    'telefone' => $this->input->post('telefone'),
                    'cpf' => $this->input->post('cpf'),
                    'data_nascimento' => $this->input->post('data_nascimento'),
                    'endereco' => $this->input->post('endereco'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'cep' => $this->input->post('cep')
                ];

                if ($this->Usuario_model->atualizar_usuario($usuario_id, $dados)) {
                    $this->session->set_userdata('usuario_nome', $dados['nome']);
                    $data['sucesso'] = 'Perfil atualizado com sucesso!';
                } else {
                    $data['erro'] = 'Erro ao atualizar perfil';
                }
            }
        }

        $data['usuario'] = $this->Usuario_model->buscar_por_id($usuario_id);
        $this->load->view('dashboard/perfil', $data);
    }
}