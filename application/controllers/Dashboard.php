<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'admin_template', 'form']);

        if (!$this->session->userdata('usuario_logado')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Dados para o dashboard
        $data = [
            'estatisticas' => $this->obter_estatisticas(),
            'vendas_recentes' => $this->obter_vendas_recentes(),
            'produtos_baixo_estoque' => $this->obter_produtos_baixo_estoque()
        ];

        // Configuração do template
        $config = [
            'titulo' => 'Dashboard',
            'titulo_pagina' => 'Dashboard',
            'css_adicional' => ['assets/css/dashboard.css']
        ];

        carregar_template_admin('admin/dashboard/home', $data, $config);
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
                    'cep' => $this->input->post('cep'),
                    'numero' => $this->input->post('numero'),
                    'complemento' => $this->input->post('complemento'),
                    'bairro' => $this->input->post('bairro')
                ];

                if ($this->Usuario_model->atualizar_usuario($usuario_id, $dados)) {
                    $this->session->set_userdata('usuario_nome', $dados['nome']);
                    $this->session->set_flashdata('sucesso', 'Perfil atualizado com sucesso!');
                    redirect('dashboard/perfil');
                } else {
                    $data['erro'] = 'Erro ao atualizar perfil';
                }
            }
        }

        $data['usuario'] = $this->Usuario_model->buscar_por_id($usuario_id);

        // Configuração do template
        $config = [
            'titulo' => 'Perfil',
            'titulo_pagina' => 'Meu Perfil',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                'Perfil'
            ])
        ];

        carregar_template_admin('admin/dashboard/perfil', $data, $config);
    }

    public function alterar_senha()
    {
        // Verificar se é uma requisição POST
        if (!$this->input->post()) {
            redirect('dashboard/perfil');
        }

        $this->load->model('Usuario_model');
        $this->load->library('form_validation');

        $usuario_id = $this->session->userdata('usuario_id');

        // Configurar regras de validação
        $this->form_validation->set_rules('senha_atual', 'Senha Atual', 'required');
        $this->form_validation->set_rules('nova_senha', 'Nova Senha', 'required|min_length[6]');
        $this->form_validation->set_rules('confirmar_senha', 'Confirmação da Senha', 'required|matches[nova_senha]');

        // Configurar mensagens de erro em português
        $this->form_validation->set_message('required', 'O campo {field} é obrigatório.');
        $this->form_validation->set_message('min_length', 'O campo {field} deve ter pelo menos {param} caracteres.');
        $this->form_validation->set_message('matches', 'A confirmação da senha não confere.');

        if ($this->form_validation->run() == FALSE) {
            // Se a validação falhar, retorna os erros em JSON
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => [
                        'senha_atual' => form_error('senha_atual'),
                        'nova_senha' => form_error('nova_senha'),
                        'confirmar_senha' => form_error('confirmar_senha')
                    ]
                ]));
            return;
        }

        // Buscar usuário atual
        $usuario = $this->Usuario_model->buscar_por_id($usuario_id);

        if (!$usuario) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ]));
            return;
        }

        // Verificar se a senha atual está correta
        $senha_atual = $this->input->post('senha_atual');

        // Assumindo que as senhas são armazenadas com hash
        if (!password_verify($senha_atual, $usuario->senha)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Senha atual incorreta',
                    'errors' => [
                        'senha_atual' => 'A senha atual não confere'
                    ]
                ]));
            return;
        }

        // Criptografar a nova senha
        $nova_senha = $this->input->post('nova_senha');
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualizar a senha no banco de dados
        $dados_atualizacao = [
            'senha' => $senha_hash
        ];

        if ($this->Usuario_model->atualizar_usuario($usuario_id, $dados_atualizacao)) {

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Senha alterada com sucesso!'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Erro interno. Tente novamente.'
                ]));
        }
    }

    private function obter_estatisticas()
    {
        // Simular dados - substitua pela lógica real
        return [
            'total_produtos' => 150,
            'pedidos_mes' => 45,
            'vendas_mes' => 12500.00,
            'usuarios_ativos' => 23
        ];
    }

    private function obter_vendas_recentes()
    {
        // Simular dados - substitua pela lógica real
        return [
            [
                'id' => 1,
                'cliente' => 'João Silva',
                'valor' => 150.00,
                'data' => date('Y-m-d H:i:s'),
                'status' => 'concluido'
            ],
            [
                'id' => 2,
                'cliente' => 'Maria Santos',
                'valor' => 89.90,
                'data' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'status' => 'processando'
            ]
        ];
    }

    private function obter_produtos_baixo_estoque()
    {
        // Simular dados - substitua pela lógica real
        return [
            [
                'id' => 1,
                'nome' => 'Produto A',
                'estoque_atual' => 5,
                'estoque_minimo' => 10
            ],
            [
                'id' => 2,
                'nome' => 'Produto B',
                'estoque_atual' => 2,
                'estoque_minimo' => 15
            ]
        ];
    }
}