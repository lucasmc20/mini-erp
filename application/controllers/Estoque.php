<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Estoque extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'admin_template', 'form']);
        $this->load->model(['Estoque_model', 'Produto_model']);

        if (!$this->session->userdata('usuario_logado')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Parâmetros de paginação e filtros
        $limite = 20;
        $pagina = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($pagina - 1) * $limite;
        
        $filtros = [
            'produto_nome' => $this->input->get('produto_nome'),
            'lote' => $this->input->get('lote'),
            'fornecedor' => $this->input->get('fornecedor'),
            'localizacao' => $this->input->get('localizacao'),
            'estoque_baixo' => $this->input->get('estoque_baixo')
        ];

        // Buscar estoques com filtros
        if (array_filter($filtros)) {
            $estoques = $this->Estoque_model->search($filtros);
            $total_estoques = count($estoques);
            // Aplicar paginação manual para busca
            $estoques = array_slice($estoques, $offset, $limite);
        } else {
            $estoques = $this->Estoque_model->get_all($limite, $offset);
            $total_estoques = $this->Estoque_model->count_all();
        }

        // Estatísticas gerais
        $estatisticas = $this->obter_estatisticas_estoque();

        $data = [
            'estoques' => $estoques,
            'estatisticas' => $estatisticas,
            'filtros' => $filtros,
            'paginacao' => [
                'total' => $total_estoques,
                'por_pagina' => $limite,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total_estoques / $limite)
            ]
        ];

        // Configuração do template
        $config = [
            'titulo' => 'Controle de Estoque',
            'titulo_pagina' => 'Gerenciar Estoque',
            'css_adicional' => ['assets/css/estoque.css'],
            'js_adicional' => ['assets/js/estoque.js']
        ];

        carregar_template_admin('admin/estoque/listar', $data, $config);
    }

    public function entrada()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');

            // Regras de validação
            $this->form_validation->set_rules('produto_id', 'Produto', 'required|integer');
            $this->form_validation->set_rules('quantidade', 'Quantidade', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('custo_unitario', 'Custo Unitário', 'decimal');
            $this->form_validation->set_rules('lote', 'Lote', 'max_length[50]');
            $this->form_validation->set_rules('fornecedor', 'Fornecedor', 'max_length[150]');
            $this->form_validation->set_rules('localizacao', 'Localização', 'max_length[100]');
            $this->form_validation->set_rules('quantidade_minima', 'Quantidade Mínima', 'integer|greater_than_equal_to[0]');

            // Mensagens em português
            $this->form_validation->set_message('required', 'O campo {field} é obrigatório.');
            $this->form_validation->set_message('integer', 'O campo {field} deve ser um número inteiro.');
            $this->form_validation->set_message('greater_than', 'O campo {field} deve ser maior que {param}.');
            $this->form_validation->set_message('decimal', 'O campo {field} deve ser um número válido.');

            if ($this->form_validation->run()) {
                $produto_id = $this->input->post('produto_id');
                $quantidade = $this->input->post('quantidade');
                
                $dados_adicionais = [
                    'custo_unitario' => $this->input->post('custo_unitario') ?: null,
                    'lote' => $this->input->post('lote'),
                    'fornecedor' => $this->input->post('fornecedor'),
                    'localizacao' => $this->input->post('localizacao'),
                    'data_validade' => $this->input->post('data_validade') ?: null,
                    'observacoes' => $this->input->post('observacoes'),
                    'quantidade_minima' => $this->input->post('quantidade_minima') ?: 1
                ];

                if ($this->Estoque_model->entrada_estoque($produto_id, $quantidade, $dados_adicionais)) {
                    $this->session->set_flashdata('sucesso', 'Entrada registrada com sucesso!');
                    redirect('estoque');
                } else {
                    $data['erro'] = 'Erro ao registrar entrada no estoque';
                }
            }
        }

        // Buscar produtos para dropdown
        $data['produtos'] = $this->Produto_model->get_ativos();

        // Configuração do template
        $config = [
            'titulo' => 'Entrada no Estoque',
            'titulo_pagina' => 'Registrar Entrada',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Estoque', 'url' => base_url('estoque')],
                'Entrada'
            ]),
            'css_adicional' => ['assets/css/estoque-form.css'],
            'js_adicional' => ['assets/js/estoque-form.js']
        ];

        carregar_template_admin('admin/estoque/entrada', $data, $config);
    }

    public function saida()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');

            // Regras de validação
            $this->form_validation->set_rules('produto_id', 'Produto', 'required|integer');
            $this->form_validation->set_rules('quantidade', 'Quantidade', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('motivo', 'Motivo', 'required|max_length[200]');

            if ($this->form_validation->run()) {
                $produto_id = $this->input->post('produto_id');
                $quantidade = $this->input->post('quantidade');

                $resultado = $this->Estoque_model->saida_estoque($produto_id, $quantidade);

                if ($resultado['status']) {
                    // Registrar movimento de saída (se houver tabela de movimentações)
                    $this->registrar_movimentacao($produto_id, 'saida', $quantidade, $this->input->post('motivo'));
                    
                    $this->session->set_flashdata('sucesso', $resultado['message']);
                    redirect('estoque');
                } else {
                    $data['erro'] = $resultado['message'];
                }
            }
        }

        // Buscar produtos com estoque
        $data['produtos'] = $this->obter_produtos_com_estoque();

        // Configuração do template
        $config = [
            'titulo' => 'Saída do Estoque',
            'titulo_pagina' => 'Registrar Saída',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Estoque', 'url' => base_url('estoque')],
                'Saída'
            ]),
            'css_adicional' => ['assets/css/estoque-form.css'],
            'js_adicional' => ['assets/js/estoque-form.js']
        ];

        carregar_template_admin('admin/estoque/saida', $data, $config);
    }

    public function editar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $estoque = $this->Estoque_model->get_by_id($id);
        if (!$estoque) {
            show_404();
        }

        if ($this->input->post()) {
            $this->load->library('form_validation');

            // Regras de validação
            $this->form_validation->set_rules('quantidade_atual', 'Quantidade Atual', 'required|integer|greater_than_equal_to[0]');
            $this->form_validation->set_rules('quantidade_minima', 'Quantidade Mínima', 'required|integer|greater_than_equal_to[0]');
            $this->form_validation->set_rules('custo_unitario', 'Custo Unitário', 'decimal');

            if ($this->form_validation->run()) {
                $dados = [
                    'quantidade_atual' => $this->input->post('quantidade_atual'),
                    'quantidade_minima' => $this->input->post('quantidade_minima'),
                    'custo_unitario' => $this->input->post('custo_unitario') ?: null,
                    'lote' => $this->input->post('lote'),
                    'fornecedor' => $this->input->post('fornecedor'),
                    'localizacao' => $this->input->post('localizacao'),
                    'data_validade' => $this->input->post('data_validade') ?: null,
                    'observacoes' => $this->input->post('observacoes')
                ];

                if ($this->Estoque_model->update($id, $dados)) {
                    $this->session->set_flashdata('sucesso', 'Estoque atualizado com sucesso!');
                    redirect('estoque');
                } else {
                    $data['erro'] = 'Erro ao atualizar estoque';
                }
            }
        }

        $data['estoque'] = $estoque;

        // Configuração do template
        $config = [
            'titulo' => 'Editar Estoque',
            'titulo_pagina' => 'Editar Estoque: ' . $estoque->produto_nome,
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Estoque', 'url' => base_url('estoque')],
                'Editar'
            ]),
            'css_adicional' => ['assets/css/estoque-form.css'],
            'js_adicional' => ['assets/js/estoque-form.js']
        ];

        carregar_template_admin('admin/estoque/editar', $data, $config);
    }

    public function visualizar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $estoque = $this->Estoque_model->get_by_id($id);
        if (!$estoque) {
            show_404();
        }

        // Buscar histórico de movimentações (se existir tabela)
        $data['estoque'] = $estoque;
        $data['movimentacoes'] = $this->obter_movimentacoes_produto($estoque->produto_id);

        // Configuração do template
        $config = [
            'titulo' => 'Visualizar Estoque',
            'titulo_pagina' => 'Estoque: ' . $estoque->produto_nome,
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Estoque', 'url' => base_url('estoque')],
                'Visualizar'
            ]),
            'css_adicional' => ['assets/css/estoque-view.css']
        ];

        carregar_template_admin('admin/estoque/visualizar', $data, $config);
    }

    public function excluir($id = null)
    {
        if (!$id || !is_numeric($id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'ID inválido'
                ]));
            return;
        }

        $estoque = $this->Estoque_model->get_by_id($id);
        if (!$estoque) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Registro de estoque não encontrado'
                ]));
            return;
        }

        if ($this->Estoque_model->delete($id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Registro de estoque excluído com sucesso!'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Erro ao excluir registro de estoque'
                ]));
        }
    }

    public function alertas()
    {
        // Produtos com estoque baixo
        $estoque_baixo = $this->Estoque_model->get_estoque_baixo();
        
        // Produtos próximos ao vencimento
        $proximos_vencimento = $this->Estoque_model->get_proximos_vencimento(30);

        $data = [
            'estoque_baixo' => $estoque_baixo,
            'proximos_vencimento' => $proximos_vencimento
        ];

        // Configuração do template
        $config = [
            'titulo' => 'Alertas de Estoque',
            'titulo_pagina' => 'Alertas e Notificações',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Estoque', 'url' => base_url('estoque')],
                'Alertas'
            ]),
            'css_adicional' => ['assets/css/estoque-alertas.css']
        ];

        carregar_template_admin('admin/estoque/alertas', $data, $config);
    }

    public function relatorio()
    {
        $data_inicio = $this->input->get('data_inicio') ?: date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ?: date('Y-m-t');

        $movimentacoes = $this->Estoque_model->get_movimentacao_periodo($data_inicio, $data_fim);
        $estatisticas = $this->obter_estatisticas_periodo($data_inicio, $data_fim);

        $data = [
            'movimentacoes' => $movimentacoes,
            'estatisticas' => $estatisticas,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim
        ];

        // Configuração do template
        $config = [
            'titulo' => 'Relatório de Estoque',
            'titulo_pagina' => 'Relatório de Movimentação',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Estoque', 'url' => base_url('estoque')],
                'Relatório'
            ]),
            'css_adicional' => ['assets/css/estoque-relatorio.css'],
            'js_adicional' => ['assets/js/estoque-relatorio.js']
        ];

        carregar_template_admin('admin/estoque/relatorio', $data, $config);
    }

    public function ajustar_estoque()
    {
        if ($this->input->post()) {
            $produto_id = $this->input->post('produto_id');
            $quantidade_nova = $this->input->post('quantidade_nova');
            $motivo = $this->input->post('motivo');

            $estoque_atual = $this->Estoque_model->get_by_produto($produto_id);
            
            if ($estoque_atual) {
                $diferenca = $quantidade_nova - $estoque_atual->quantidade_atual;
                
                $dados = ['quantidade_atual' => $quantidade_nova];
                
                if ($this->Estoque_model->update($estoque_atual->id, $dados)) {
                    // Registrar ajuste
                    $this->registrar_movimentacao($produto_id, 'ajuste', $diferenca, $motivo);
                    
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'success' => true,
                            'message' => 'Estoque ajustado com sucesso!'
                        ]));
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'success' => false,
                            'message' => 'Erro ao ajustar estoque'
                        ]));
                }
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => false,
                        'message' => 'Produto não encontrado no estoque'
                    ]));
            }
        }
    }

    public function obter_estoque_produto($produto_id)
    {
        $estoque = $this->Estoque_model->get_by_produto($produto_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($estoque ?: (object)[]));
    }

    // Métodos privados auxiliares
    private function obter_estatisticas_estoque()
    {
        $estoque_baixo = count($this->Estoque_model->get_estoque_baixo());
        $proximos_vencimento = count($this->Estoque_model->get_proximos_vencimento(30));
        $total_produtos = $this->Estoque_model->count_all();
        
        // Valor total do estoque
        $query = $this->db->query("
            SELECT SUM(quantidade_atual * COALESCE(custo_unitario, 0)) as valor_total 
            FROM estoque 
            WHERE custo_unitario IS NOT NULL
        ");
        $valor_total = $query->row()->valor_total ?: 0;

        return [
            'total_produtos' => $total_produtos,
            'estoque_baixo' => $estoque_baixo,
            'proximos_vencimento' => $proximos_vencimento,
            'valor_total_estoque' => $valor_total
        ];
    }

    private function obter_produtos_com_estoque()
    {
        $this->db->select('p.id, p.nome, e.quantidade_atual, e.quantidade_reservada');
        $this->db->from('produtos p');
        $this->db->join('estoque e', 'e.produto_id = p.id');
        $this->db->where('p.ativo', 1);
        $this->db->where('e.quantidade_atual >', 0);
        
        return $this->db->get()->result();
    }

    private function registrar_movimentacao($produto_id, $tipo, $quantidade, $observacao = '')
    {
        // Implementar se houver tabela de movimentações
        // Este é um placeholder para futuras implementações
        return true;
    }

    private function obter_movimentacoes_produto($produto_id)
    {
        // Implementar se houver tabela de movimentações
        // Retorna array vazio por enquanto
        return [];
    }

    private function obter_estatisticas_periodo($data_inicio, $data_fim)
    {
        // Implementar estatísticas específicas do período
        return [
            'total_entradas' => 0,
            'total_saidas' => 0,
            'valor_entradas' => 0,
            'valor_saidas' => 0
        ];
    }
}