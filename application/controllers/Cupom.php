<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cupom extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'admin_template', 'form']);
        $this->load->model('Cupom_model');

        if (!$this->session->userdata('usuario_logado')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $limite = 20;
        $pagina = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($pagina - 1) * $limite;

        // Filtros
        $filtros = [
            'codigo' => $this->input->get('codigo'),
            'nome' => $this->input->get('nome'),
            'status' => $this->input->get('status'),
        ];

        // Buscar cupons (filtro simples, você pode melhorar a busca no model)
        if (array_filter($filtros)) {
            $cupons = $this->Cupom_model->get_all($limite, $offset, array_filter($filtros));
            $total_cupons = $this->Cupom_model->count_all(array_filter($filtros));
        } else {
            $cupons = $this->Cupom_model->get_all($limite, $offset);
            $total_cupons = $this->Cupom_model->count_all();
        }

        $estatisticas = $this->Cupom_model->get_estatisticas();

        $data = [
            'cupons' => $cupons,
            'estatisticas' => $estatisticas,
            'filtros' => $filtros,
            'paginacao' => [
                'total' => $total_cupons,
                'por_pagina' => $limite,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total_cupons / $limite)
            ]
        ];

        $config = [
            'titulo' => 'Gerenciar Cupons',
            'titulo_pagina' => 'Cupons de Desconto',
            'css_adicional' => ['assets/css/cupom.css'],
            'js_adicional' => ['assets/js/cupom.js']
        ];

        carregar_template_admin('admin/cupom/listar', $data, $config);
    }

    public function novo()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('codigo', 'Código', 'required|is_unique[cupons.codigo]');
            $this->form_validation->set_rules('nome', 'Nome', 'required');

            if ($this->form_validation->run()) {
                $dados = $this->input->post();
                if ($this->Cupom_model->insert($dados)) {
                    $this->session->set_flashdata('sucesso', 'Cupom cadastrado com sucesso!');
                    redirect('cupom');
                } else {
                    $data['erro'] = 'Erro ao cadastrar cupom';
                }
            }
        }

        $config = [
            'titulo' => 'Novo Cupom',
            'titulo_pagina' => 'Cadastrar Cupom',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Cupons', 'url' => base_url('cupons')],
                'Novo'
            ]),
            'css_adicional' => ['assets/css/cupom-form.css']
        ];

        carregar_template_admin('admin/cupom/novo', isset($data) ? $data : [], $config);
    }

    public function editar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $cupom = $this->Cupom_model->get_by_id($id);
        if (!$cupom) {
            show_404();
        }

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('nome', 'Nome', 'required');

            if ($this->form_validation->run()) {
                $dados = $this->input->post();
                // Se alterar código, verificar se já existe
                if (isset($dados['codigo']) && $this->Cupom_model->codigo_exists($dados['codigo'], $id)) {
                    $data['erro'] = 'Código já existe para outro cupom!';
                } else {
                    if ($this->Cupom_model->update($id, $dados)) {
                        $this->session->set_flashdata('sucesso', 'Cupom atualizado com sucesso!');
                        redirect('cupom');
                    } else {
                        $data['erro'] = 'Erro ao atualizar cupom';
                    }
                }
            }
        }

        $data['cupom'] = $cupom;

        $config = [
            'titulo' => 'Editar Cupom',
            'titulo_pagina' => 'Editar Cupom: ' . $cupom->nome,
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Cupons', 'url' => base_url('cupons')],
                'Editar'
            ]),
            'css_adicional' => ['assets/css/cupom-form.css']
        ];

        carregar_template_admin('admin/cupom/editar', isset($data) ? $data : [], $config);
    }

    public function excluir($id = null)
    {
        if (!$id || !is_numeric($id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'ID inválido']));
            return;
        }

        $cupom = $this->Cupom_model->get_by_id($id);
        if (!$cupom) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Cupom não encontrado']));
            return;
        }

        if ($this->Cupom_model->delete($id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'message' => 'Cupom excluído com sucesso!']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Erro ao excluir cupom']));
        }
    }

    public function toggle($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        if ($this->Cupom_model->toggle_ativo($id)) {
            $this->session->set_flashdata('sucesso', 'Status do cupom alterado!');
        } else {
            $this->session->set_flashdata('erro', 'Erro ao alterar status.');
        }
        redirect('cupom');
    }

    public function estatisticas()
    {
        $estatisticas = $this->Cupom_model->get_estatisticas();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($estatisticas));
    }

    public function relatorio_uso()
    {
        $data_inicio = $this->input->get('data_inicio');
        $data_fim = $this->input->get('data_fim');
        $relatorio = $this->Cupom_model->get_relatorio_uso($data_inicio, $data_fim);

        $config = [
            'titulo' => 'Relatório de Uso de Cupons',
            'titulo_pagina' => 'Relatório de Cupons',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Cupons', 'url' => base_url('cupons')],
                'Relatório'
            ]),
            'css_adicional' => ['assets/css/cupom-relatorio.css']
        ];

        $data = [
            'relatorio' => $relatorio,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim
        ];

        carregar_template_admin('admin/cupom/relatorio', $data, $config);
    }
}
