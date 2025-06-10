<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->library('form_validation');

        // Helper bootstrap já está carregado no MY_Controller
        // Definir mensagens de validação customizadas usando Bootstrap
        $this->form_validation->set_error_delimiters(
            '<div class="invalid-feedback d-block">',
            '</div>'
        );
    }

    public function index()
    {
        if ($this->session->userdata('usuario_logado')) {
            redirect('dashboard');
        }
        $this->login();
    }

    

    public function logout()
    {
        $nome_usuario = $this->session->userdata('usuario_nome');
        $this->session->sess_destroy();

        $this->session->set_flashdata('info', "Até logo, {$nome_usuario}! Volte sempre.");
        redirect('auth/login');
    }

    public function login()
    {
        if ($this->session->userdata('usuario_logado')) {
            redirect('dashboard');
        }

        $data = [];

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');

            if ($this->form_validation->run()) {
                $email = trim($this->input->post('email'));
                $senha = $this->input->post('senha');

                $usuario = $this->Usuario_model->validar_login($email, $senha);

                if ($usuario) {
                    // Verificar se usuário está ativo
                    if (isset($usuario->ativo) && !$usuario->ativo) {
                        $data['erro'] = 'Sua conta está desativada. Entre em contato com o administrador.';
                    } else {
                        $dados_sessao = [
                            'usuario_id' => $usuario->id,
                            'usuario_nome' => $usuario->nome,
                            'usuario_email' => $usuario->email,
                            'usuario_logado' => true,
                            'login_time' => time()
                        ];

                        $this->session->set_userdata($dados_sessao);

                        // Gerenciar cookie "lembrar-me"
                        $lembrar = $this->input->post('lembrar');
                        if ($lembrar) {
                            $this->input->set_cookie('email_lembrado', $email, 86400 * 30);
                        } else {
                            $this->input->set_cookie('email_lembrado', '', -1);
                        }

                        $this->session->set_flashdata('sucesso', 'Bem-vindo de volta, ' . $usuario->nome . '!');
                        redirect('dashboard');
                    }
                } else {
                    $data['erro'] = 'E-mail ou senha incorretos. Verifique seus dados e tente novamente.';
                    log_message('info', 'Tentativa de login inválida para: ' . $email . ' - IP: ' . $this->input->ip_address());
                }
            }
        }

        // Recuperar email lembrado
        $data['email_lembrado'] = $this->input->cookie('email_lembrado');

        $this->load->view('auth/login', $data);
    }

    public function cadastro()
    {
        if ($this->session->userdata('usuario_logado')) {
            redirect('dashboard');
        }

        $data = [];

        if ($this->input->post()) {
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|callback_email_unico');
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'required|matches[senha]');
            $this->form_validation->set_rules('telefone', 'Telefone', 'trim|min_length[14]|max_length[15]');
            $this->form_validation->set_rules('cpf', 'CPF', 'trim|callback_cpf_valido');

            if ($this->form_validation->run()) {
                $telefone = preg_replace('/[^0-9]/', '', $this->input->post('telefone'));
                $cpf = preg_replace('/[^0-9]/', '', $this->input->post('cpf'));

                $dados = [
                    'nome' => trim($this->input->post('nome')),
                    'email' => strtolower(trim($this->input->post('email'))),
                    'senha' => $this->input->post('senha'),
                    'telefone' => $telefone,
                    'cpf' => $cpf,
                    'ativo' => 1,
                    'data_cadastro' => date('Y-m-d H:i:s')
                ];

                if ($this->Usuario_model->criar_usuario($dados)) {
                    log_message('info', 'Novo usuário cadastrado: ' . $dados['email'] . ' - IP: ' . $this->input->ip_address());
                    $this->session->set_flashdata('sucesso', 'Parabéns! Sua conta foi criada com sucesso. Faça login para começar a usar a Montink.');
                    redirect('auth/login');
                } else {
                    $data['erro'] = 'Ops! Ocorreu um erro ao criar sua conta. Tente novamente em alguns instantes.';
                    log_message('error', 'Erro ao criar usuário: ' . $this->input->post('email'));
                }
            }
        }

        $this->load->view('auth/cadastro', $data);
    }


    public function email_unico($email)
    {
        if ($this->Usuario_model->email_existe($email)) {
            $this->form_validation->set_message('email_unico', 'Este e-mail já está em uso.');
            return false;
        }
        return true;
    }

    public function cpf_valido($cpf)
    {
        if (empty($cpf)) {
            return true; // CPF é opcional
        }

        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            $this->form_validation->set_message('cpf_valido', 'CPF deve ter 11 dígitos.');
            return false;
        }

        // Validação básica de CPF
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->form_validation->set_message('cpf_valido', 'CPF inválido.');
            return false;
        }

        return true;
    }

    public function esqueci_senha()
    {
        $data = [];

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');

            if ($this->form_validation->run()) {
                $email = $this->input->post('email');
                $usuario = $this->Usuario_model->buscar_por_email($email);

                if ($usuario) {
                    // Gerar token de recuperação ao invés de senha simples
                    $token = bin2hex(random_bytes(32));
                    $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    $dados_recuperacao = [
                        'token_recuperacao' => $token,
                        'token_expiracao' => $expiracao
                    ];

                    if ($this->Usuario_model->atualizar_usuario($usuario->id, $dados_recuperacao)) {
                        // Aqui você enviaria o email com o link de recuperação
                        // $this->enviar_email_recuperacao($email, $token);

                        $data['alert'] = alert_success('Instruções de recuperação foram enviadas para seu e-mail.');
                    } else {
                        $data['alert'] = alert_danger('Erro ao processar solicitação. Tente novamente.');
                    }
                } else {
                    $data['alert'] = alert_warning('E-mail não encontrado em nossa base de dados.');
                }
            } else {
                $data['alert'] = alert_warning('Por favor, informe um e-mail válido.');
            }
        }

        $this->load->view('auth/esqueci_senha', $data);
    }

    public function redefinir_senha($token = null)
    {
        if (!$token) {
            $this->session->set_flashdata('info', 'Token de recuperação não fornecido.');
            redirect('auth/esqueci_senha');
        }

        $token_hash = hash('sha256', $token);
        $usuario = $this->Usuario_model->buscar_por_token($token_hash);

        if (!$usuario || !$usuario->token_expiracao || strtotime($usuario->token_expiracao) < time()) {
            $this->session->set_flashdata('info', 'Token inválido ou expirado. Solicite uma nova recuperação de senha.');
            redirect('auth/esqueci_senha');
        }

        $data = ['token' => $token];

        if ($this->input->post()) {
            $this->form_validation->set_rules('senha', 'Nova Senha', 'required|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'required|matches[senha]');

            if ($this->form_validation->run()) {
                $nova_senha = $this->input->post('senha');

                $dados_atualizacao = [
                    'senha' => $nova_senha,
                    'token_recuperacao' => null,
                    'token_expiracao' => null
                ];

                if ($this->Usuario_model->atualizar_usuario($usuario->id, $dados_atualizacao)) {
                    $this->session->set_flashdata('sucesso', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
                    redirect('auth/login');
                } else {
                    $data['erro'] = 'Erro ao redefinir senha. Tente novamente.';
                }
            }
        }

        $this->load->view('auth/redefinir_senha', $data);
    }

    // Método auxiliar para envio de emails (implementar conforme sua necessidade)
    private function enviar_email_recuperacao($email, $token)
    {
        // Implementar envio de email com link de recuperação
        return true;
    }
}
