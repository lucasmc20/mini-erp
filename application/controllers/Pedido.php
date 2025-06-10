<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'admin_template', 'form']);
        $this->load->model(['Pedido_model', 'Pedido_item_model', 'Usuario_model', 'Produto_model', 'Cupom_model']);

        if (!$this->session->userdata('usuario_logado')) {
            redirect('auth/login');
        }
    }

    // Listar pedidos com paginação e filtros
    public function index()
    {
        $limite = 20;
        $pagina = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($pagina - 1) * $limite;

        $filtros = [
            'p.status' => $this->input->get('status'),
            'u.nome'   => $this->input->get('usuario_nome'),
            'p.numero_pedido' => $this->input->get('numero_pedido'),
            'p.cupom_id' => $this->input->get('cupom_id')
        ];
        $filtros = array_filter($filtros);

        if ($filtros) {
            $pedidos = $this->Pedido_model->get_all($limite, $offset, $filtros);
            $total_pedidos = $this->Pedido_model->count_all($filtros);
        } else {
            $pedidos = $this->Pedido_model->get_all($limite, $offset);
            $total_pedidos = $this->Pedido_model->count_all();
        }

        // Estatísticas rápidas (opcional, pode customizar)
        $estatisticas = $this->Pedido_model->get_estatisticas_status();

        $data = [
            'pedidos' => $pedidos,
            'estatisticas' => $estatisticas,
            'filtros' => $filtros,
            'paginacao' => [
                'total' => $total_pedidos,
                'por_pagina' => $limite,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total_pedidos / $limite)
            ]
        ];

        $config = [
            'titulo' => 'Gerenciar Pedidos',
            'titulo_pagina' => 'Pedidos',
            'css_adicional' => ['assets/css/pedidos.css'],
            'js_adicional' => ['assets/js/pedidos.js']
        ];

        carregar_template_admin('admin/pedido/listar', $data, $config);
    }

    // Visualizar detalhes do pedido e seus itens
    public function visualizar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $pedido = $this->Pedido_model->get_by_id($id);
        if (!$pedido) {
            show_404();
        }

        $itens = $this->Pedido_item_model->get_by_pedido($id);

        $data = [
            'pedido' => $pedido,
            'itens' => $itens
        ];

        $config = [
            'titulo' => 'Detalhes do Pedido',
            'titulo_pagina' => 'Pedido #' . htmlspecialchars($pedido->numero_pedido),
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Pedidos', 'url' => base_url('pedido')],
                'Visualizar'
            ])
        ];

        carregar_template_admin('admin/pedido/visualizar', $data, $config);
    }

    // Criar novo pedido manualmente
    public function novo()
    {
        $this->load->model('Usuario_model');
        $this->load->model('Cupom_model');
        $this->load->model('Produto_model');
        $this->load->model('Pedido_model');
        $this->load->model('Pedido_item_model');

        $data['usuarios'] = $this->Usuario_model->listar_todos();
        $data['cupons'] = $this->Cupom_model->get_ativos();
        $data['produtos'] = $this->Produto_model->get_all();

        if ($this->input->post()) {
            $pedido_data = $this->input->post();
            $produtos = $this->input->post('produtos');

            // Remova o campo 'produtos' do array de pedido antes de salvar o pedido
            unset($pedido_data['produtos']);

            // Salva pedido, retorna id
            $pedido_id = $this->Pedido_model->insert($pedido_data);

            if ($pedido_id) {
                // Agora salve os produtos do pedido
                foreach ($produtos as $produto_id => $produtoData) {
                    $produto = $this->Produto_model->get_by_id($produtoData['produto_id']);

                    if ($produto) {
                        $pedido_item = [
                            'pedido_id'      => $pedido_id,
                            'produto_id'     => $produtoData['produto_id'],
                            'quantidade'     => $produtoData['quantidade'],
                            'preco_unitario' => $produto->preco,
                            'preco_total'    => $produto->preco * $produtoData['quantidade'],
                        ];

                        $this->Pedido_item_model->insert($pedido_item);
                    } else {
                        log_message('error', 'Produto ID ' . $produtoData['produto_id'] . ' não encontrado ao tentar inserir pedido_item.');
                    }
                }

                $this->session->set_flashdata('sucesso', 'Pedido cadastrado com sucesso!');
                redirect('pedido');
            } else {
                $data['erro'] = 'Erro ao cadastrar pedido';
            }
        }

        $config = [
            'titulo' => 'Novo Pedido',
            'titulo_pagina' => 'Cadastrar Novo Pedido',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Pedidos', 'url' => base_url('pedido')],
                'Novo'
            ]),
            'css_adicional' => ['assets/css/pedidos-form.css'],
            'js_adicional' => ['assets/js/pedidos-form.js']
        ];

        carregar_template_admin('admin/pedido/novo', $data, $config);
    }


    // Editar pedido (detalhes gerais, não itens)
    public function editar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $pedido = $this->Pedido_model->get_by_id($id);
        if (!$pedido) {
            show_404();
        }

        $this->load->library('form_validation');

        if ($this->input->post()) {
            $this->form_validation->set_rules('status', 'Status', 'required');
            // Outros campos, se editar

            if ($this->form_validation->run()) {
                $dados = $this->input->post();

                if ($this->Pedido_model->update($id, $dados)) {
                    $this->session->set_flashdata('sucesso', 'Pedido atualizado!');
                    redirect('pedido/visualizar/' . $id);
                } else {
                    $data['erro'] = 'Erro ao atualizar pedido';
                }
            }
        }

        $data['pedido'] = $pedido;
        $data['usuarios'] = $this->Usuario_model->listar_todos(); // Se quiser permitir trocar usuário
        $data['cupons'] = $this->Cupom_model->get_ativos();

        $config = [
            'titulo' => 'Editar Pedido',
            'titulo_pagina' => 'Editar Pedido #' . htmlspecialchars($pedido->numero_pedido),
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Pedidos', 'url' => base_url('pedido')],
                'Editar'
            ])
        ];

        carregar_template_admin('admin/pedido/editar', $data, $config);
    }

    // Excluir pedido
    public function excluir($id)
{
    if ($this->input->is_ajax_request()) {
        $this->load->model('Pedido_item_model');
        $this->Pedido_item_model->delete_by_pedido($id);

        $this->load->model('Pedido_model');
        $result = $this->Pedido_model->delete($id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Pedido removido com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Não foi possível remover o pedido.']);
        }
        return;
    }
    // fallback
    redirect('pedido');
}


    // Atualizar status do pedido (ajax/post)
    public function atualizar_status($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $status = $this->input->post('status');
        $observacoes = $this->input->post('observacoes');

        if ($this->Pedido_model->update_status($id, $status, $observacoes)) {
            $this->session->set_flashdata('sucesso', 'Status do pedido atualizado!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao atualizar status.');
        }
        redirect('pedido/visualizar/' . $id);
    }

    // Relatório vendas por período
    public function relatorio()
    {
        $data_inicio = $this->input->get('data_inicio') ?: date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ?: date('Y-m-t');

        $vendas = $this->Pedido_model->get_vendas_periodo($data_inicio, $data_fim);

        $data = [
            'vendas' => $vendas,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim
        ];

        $config = [
            'titulo' => 'Relatório de Vendas',
            'titulo_pagina' => 'Relatório de Pedidos',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Pedidos', 'url' => base_url('pedido')],
                'Relatório'
            ])
        ];

        carregar_template_admin('admin/pedido/relatorio', $data, $config);
    }
}
