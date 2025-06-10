<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'admin_template', 'form']);
        $this->load->model(['Produto_model', 'Categoria_model', 'Estoque_model']);
        $this->load->library('upload');

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
            'nome' => $this->input->get('nome'),
            'categoria_id' => $this->input->get('categoria_id'),
            'ativo' => $this->input->get('ativo'),
            'estoque_baixo' => $this->input->get('estoque_baixo'),
            'marca' => $this->input->get('marca')
        ];

        // Buscar produtos com filtros
        $produtos = $this->Produto_model->listar_produtos($filtros, $limite, $offset);
        $total_produtos = $this->Produto_model->contar_produtos($filtros);

        // Buscar categorias para filtro
        $categorias = $this->Categoria_model->listar_ativas();

        // Buscar marcas para filtro
        $marcas = $this->Produto_model->get_marcas();

        $data = [
            'produtos' => $produtos,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'filtros' => $filtros,
            'paginacao' => [
                'total' => $total_produtos,
                'por_pagina' => $limite,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total_produtos / $limite)
            ]
        ];

        // Configuração do template
        $config = [
            'titulo' => 'Produtos',
            'titulo_pagina' => 'Gerenciar Produtos',
            'css_adicional' => ['assets/css/produtos.css'],
            'js_adicional' => ['assets/js/produtos.js']
        ];

        carregar_template_admin('admin/produto/listar', $data, $config);
    }

    public function cadastrar()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');

            // Regras de validação do produto
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|max_length[200]');
            $this->form_validation->set_rules('sku', 'SKU', 'required|max_length[50]|callback_verificar_sku_unico');
            $this->form_validation->set_rules('preco', 'Preço', 'required|decimal|greater_than[0]');
            $this->form_validation->set_rules('categoria_id', 'Categoria', 'required|integer');
            $this->form_validation->set_rules('marca', 'Marca', 'max_length[100]');
            $this->form_validation->set_rules('modelo', 'Modelo', 'max_length[100]');
            $this->form_validation->set_rules('codigo_barras', 'Código de Barras', 'max_length[50]');
            $this->form_validation->set_rules('peso', 'Peso', 'decimal');
            $this->form_validation->set_rules('dimensoes', 'Dimensões', 'max_length[100]');
            $this->form_validation->set_rules('preco_custo', 'Preço de Custo', 'decimal');

            // Regras de validação do estoque (se controlar estoque estiver ativo)
            if ($this->input->post('controlar_estoque')) {
                $this->form_validation->set_rules('quantidade_inicial', 'Quantidade Inicial', 'required|integer|greater_than_equal_to[0]');
                $this->form_validation->set_rules('quantidade_minima', 'Quantidade Mínima', 'required|integer|greater_than_equal_to[0]');
                $this->form_validation->set_rules('localizacao', 'Localização', 'max_length[100]');
                $this->form_validation->set_rules('fornecedor', 'Fornecedor', 'max_length[150]');
            }

            // Mensagens em português
            $this->form_validation->set_message('required', 'O campo {field} é obrigatório.');
            $this->form_validation->set_message('min_length', 'O campo {field} deve ter pelo menos {param} caracteres.');
            $this->form_validation->set_message('max_length', 'O campo {field} deve ter no máximo {param} caracteres.');
            $this->form_validation->set_message('greater_than', 'O campo {field} deve ser maior que {param}.');
            $this->form_validation->set_message('greater_than_equal_to', 'O campo {field} deve ser maior ou igual a {param}.');
            $this->form_validation->set_message('decimal', 'O campo {field} deve ser um número válido.');
            $this->form_validation->set_message('integer', 'O campo {field} deve ser um número inteiro.');

            if ($this->form_validation->run()) {
                // Iniciar transação
                $this->db->trans_start();

                try {
                    // Fazer upload da imagem principal se fornecida
                    $imagem_principal = $this->fazer_upload_imagem('imagem_principal');

                    // Fazer upload das imagens da galeria se fornecidas
                    $galeria_imagens = $this->fazer_upload_galeria();

                    // Gerar slug automaticamente
                    $slug = $this->gerar_slug($this->input->post('nome'));

                    // Dados do produto
                    $dados_produto = [
                        'nome' => $this->input->post('nome'),
                        'descricao' => $this->input->post('descricao'),
                        'categoria_id' => $this->input->post('categoria_id'),
                        'marca' => $this->input->post('marca'),
                        'modelo' => $this->input->post('modelo'),
                        'codigo_barras' => $this->input->post('codigo_barras'),
                        'sku' => $this->input->post('sku'),
                        'preco' => $this->input->post('preco'),
                        'preco_promocional' => $this->input->post('preco_promocional') ?: null,
                        'preco_custo' => $this->input->post('preco_custo') ?: null,
                        'peso' => $this->input->post('peso') ?: null,
                        'dimensoes' => $this->input->post('dimensoes'),
                        'imagem_principal' => $imagem_principal,
                        'galeria_imagens' => json_encode($galeria_imagens),
                        'slug' => $slug,
                        'ativo' => $this->input->post('ativo') ? 1 : 0,
                        'destaque' => $this->input->post('destaque') ? 1 : 0,
                        'controlar_estoque' => $this->input->post('controlar_estoque') ? 1 : 0,
                        'permite_venda_sem_estoque' => $this->input->post('permite_venda_sem_estoque') ? 1 : 0,
                        'meta_title' => $this->input->post('meta_title'),
                        'meta_description' => $this->input->post('meta_description'),
                        'tags' => $this->input->post('tags'),
                        'data_cadastro' => date('Y-m-d H:i:s'),
                        'usuario_cadastro' => $this->session->userdata('usuario_id')
                    ];

                    // Cadastrar produto
                    $produto_id = $this->Produto_model->cadastrar_produto($dados_produto);

                    if (!$produto_id) {
                        throw new Exception('Erro ao cadastrar produto');
                    }

                    // Se controlar estoque, criar registro de estoque
                    if ($this->input->post('controlar_estoque')) {
                        $dados_estoque = [
                            'produto_id' => $produto_id,
                            'quantidade_atual' => $this->input->post('quantidade_inicial') ?: 0,
                            'quantidade_minima' => $this->input->post('quantidade_minima') ?: 1,
                            'quantidade_maxima' => $this->input->post('quantidade_maxima') ?: null,
                            'localizacao' => $this->input->post('localizacao'),
                            'lote' => $this->input->post('lote'),
                            'data_validade' => $this->input->post('data_validade') ?: null,
                            'data_fabricacao' => $this->input->post('data_fabricacao') ?: null,
                            'custo_unitario' => $this->input->post('preco_custo') ?: null,
                            'fornecedor' => $this->input->post('fornecedor'),
                            'codigo_fornecedor' => $this->input->post('codigo_fornecedor'),
                            'observacoes' => $this->input->post('observacoes_estoque'),
                            'status' => 'ativo',
                            'data_cadastro' => date('Y-m-d H:i:s'),
                            'usuario_cadastro' => $this->session->userdata('usuario_id')
                        ];

                        $estoque_id = $this->Estoque_model->insert($dados_estoque);

                        if (!$estoque_id) {
                            throw new Exception('Erro ao criar registro de estoque');
                        }

                        // Se quantidade inicial > 0, registrar movimentação de entrada
                        $quantidade_inicial = $this->input->post('quantidade_inicial');
                        if ($quantidade_inicial > 0) {
                            $this->Estoque_model->registrar_movimentacao(
                                $produto_id,
                                'entrada',
                                $quantidade_inicial,
                                0,
                                $quantidade_inicial,
                                $this->input->post('preco_custo'),
                                'Estoque inicial do produto',
                                [
                                    'lote' => $this->input->post('lote'),
                                    'fornecedor' => $this->input->post('fornecedor'),
                                    'motivo' => 'Cadastro inicial do produto'
                                ]
                            );
                        }
                    }

                    // Finalizar transação
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Erro na transação do banco de dados');
                    }

                    $this->session->set_flashdata('sucesso', 'Produto cadastrado com sucesso!');
                    redirect('produto');

                } catch (Exception $e) {
                    $this->db->trans_rollback();
                    $data['erro'] = $e->getMessage();
                }
            }
        }

        // Buscar categorias para dropdown
        $data['categorias'] = $this->Categoria_model->listar_ativas();

        // Configuração do template
        $config = [
            'titulo' => 'Cadastrar Produto',
            'titulo_pagina' => 'Cadastrar Novo Produto',
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Produtos', 'url' => base_url('produto')],
                'Cadastrar'
            ]),
            'css_adicional' => ['assets/css/produtos-form.css'],
            'js_adicional' => ['assets/js/produtos-form.js']
        ];

        carregar_template_admin('admin/produto/cadastrar', $data, $config);
    }

    public function editar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $produto = $this->Produto_model->buscar_por_id_completo($id);
        if (!$produto) {
            show_404();
        }

        if ($this->input->post()) {
            $this->load->library('form_validation');

            // Regras de validação
            $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|max_length[200]');
            $this->form_validation->set_rules('sku', 'SKU', 'required|max_length[50]|callback_verificar_sku_edicao[' . $id . ']');
            $this->form_validation->set_rules('preco', 'Preço', 'required|decimal|greater_than[0]');
            $this->form_validation->set_rules('categoria_id', 'Categoria', 'required|integer');
            $this->form_validation->set_rules('marca', 'Marca', 'max_length[100]');
            $this->form_validation->set_rules('modelo', 'Modelo', 'max_length[100]');
            $this->form_validation->set_rules('codigo_barras', 'Código de Barras', 'max_length[50]');
            $this->form_validation->set_rules('peso', 'Peso', 'decimal');
            $this->form_validation->set_rules('dimensoes', 'Dimensões', 'max_length[100]');

            if ($this->form_validation->run()) {
                // Iniciar transação
                $this->db->trans_start();

                try {
                    // Verificar se nova imagem principal foi enviada
                    $imagem_principal = $produto->imagem_principal;
                    $nova_imagem = $this->fazer_upload_imagem('imagem_principal');
                    if ($nova_imagem) {
                        // Remover imagem antiga se existir
                        if ($imagem_principal && file_exists('./uploads/produtos/' . $imagem_principal)) {
                            unlink('./uploads/produtos/' . $imagem_principal);
                        }
                        $imagem_principal = $nova_imagem;
                    }

                    // Verificar se novas imagens da galeria foram enviadas
                    $galeria_atual = json_decode($produto->galeria_imagens, true) ?: [];
                    $novas_imagens = $this->fazer_upload_galeria();
                    $galeria_imagens = array_merge($galeria_atual, $novas_imagens);

                    // Atualizar slug se nome mudou
                    $slug = $produto->slug;
                    if ($this->input->post('nome') !== $produto->nome) {
                        $slug = $this->gerar_slug($this->input->post('nome'), $id);
                    }

                    $dados_produto = [
                        'nome' => $this->input->post('nome'),
                        'descricao' => $this->input->post('descricao'),
                        'categoria_id' => $this->input->post('categoria_id'),
                        'marca' => $this->input->post('marca'),
                        'modelo' => $this->input->post('modelo'),
                        'codigo_barras' => $this->input->post('codigo_barras'),
                        'sku' => $this->input->post('sku'),
                        'preco' => $this->input->post('preco'),
                        'preco_promocional' => $this->input->post('preco_promocional') ?: null,
                        'preco_custo' => $this->input->post('preco_custo') ?: null,
                        'peso' => $this->input->post('peso') ?: null,
                        'dimensoes' => $this->input->post('dimensoes'),
                        'imagem_principal' => $imagem_principal,
                        'galeria_imagens' => json_encode($galeria_imagens),
                        'slug' => $slug,
                        'ativo' => $this->input->post('ativo') ? 1 : 0,
                        'destaque' => $this->input->post('destaque') ? 1 : 0,
                        'controlar_estoque' => $this->input->post('controlar_estoque') ? 1 : 0,
                        'permite_venda_sem_estoque' => $this->input->post('permite_venda_sem_estoque') ? 1 : 0,
                        'meta_title' => $this->input->post('meta_title'),
                        'meta_description' => $this->input->post('meta_description'),
                        'tags' => $this->input->post('tags'),
                        'data_atualizacao' => date('Y-m-d H:i:s'),
                        'usuario_atualizacao' => $this->session->userdata('usuario_id')
                    ];

                    if (!$this->Produto_model->atualizar_produto($id, $dados_produto)) {
                        throw new Exception('Erro ao atualizar produto');
                    }

                    // Atualizar estoque se existe e se controla estoque
                    if ($this->input->post('controlar_estoque')) {
                        $estoque = $this->Estoque_model->get_by_produto($id);
                        
                        $dados_estoque = [
                            'quantidade_minima' => $this->input->post('quantidade_minima') ?: 1,
                            'quantidade_maxima' => $this->input->post('quantidade_maxima') ?: null,
                            'localizacao' => $this->input->post('localizacao'),
                            'lote' => $this->input->post('lote'),
                            'data_validade' => $this->input->post('data_validade') ?: null,
                            'data_fabricacao' => $this->input->post('data_fabricacao') ?: null,
                            'custo_unitario' => $this->input->post('preco_custo') ?: null,
                            'fornecedor' => $this->input->post('fornecedor'),
                            'codigo_fornecedor' => $this->input->post('codigo_fornecedor'),
                            'observacoes' => $this->input->post('observacoes_estoque'),
                            'data_atualizacao' => date('Y-m-d H:i:s'),
                            'usuario_atualizacao' => $this->session->userdata('usuario_id')
                        ];

                        if ($estoque) {
                            $this->Estoque_model->update($estoque->id, $dados_estoque);
                        } else {
                            // Criar estoque se não existe
                            $dados_estoque['produto_id'] = $id;
                            $dados_estoque['quantidade_atual'] = 0;
                            $dados_estoque['status'] = 'ativo';
                            $dados_estoque['data_cadastro'] = date('Y-m-d H:i:s');
                            $dados_estoque['usuario_cadastro'] = $this->session->userdata('usuario_id');
                            $this->Estoque_model->insert($dados_estoque);
                        }
                    }

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Erro na transação do banco de dados');
                    }

                    $this->session->set_flashdata('sucesso', 'Produto atualizado com sucesso!');
                    redirect('produto');

                } catch (Exception $e) {
                    $this->db->trans_rollback();
                    $data['erro'] = $e->getMessage();
                }
            }
        }

        $data['produto'] = $produto;
        $data['categorias'] = $this->Categoria_model->listar_ativas();

        // Configuração do template
        $config = [
            'titulo' => 'Editar Produto',
            'titulo_pagina' => 'Editar Produto: ' . $produto->nome,
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Produtos', 'url' => base_url('produto')],
                'Editar'
            ]),
            'css_adicional' => ['assets/css/produtos-form.css'],
            'js_adicional' => ['assets/js/produtos-form.js']
        ];

        carregar_template_admin('admin/produto/editar', $data, $config);
    }

    public function visualizar($id = null)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $produto = $this->Produto_model->buscar_por_id_completo($id);
        if (!$produto) {
            show_404();
        }

        // Buscar movimentações de estoque
        $movimentacoes = $this->Estoque_model->get_movimentacoes_produto($id, 10);

        $data['produto'] = $produto;
        $data['movimentacoes'] = $movimentacoes;

        // Configuração do template
        $config = [
            'titulo' => 'Visualizar Produto',
            'titulo_pagina' => 'Produto: ' . $produto->nome,
            'breadcrumb' => gerar_breadcrumb([
                ['titulo' => 'Dashboard', 'url' => base_url('dashboard')],
                ['titulo' => 'Produtos', 'url' => base_url('produto')],
                'Visualizar'
            ]),
            'css_adicional' => ['assets/css/produtos-view.css']
        ];

        carregar_template_admin('admin/produto/visualizar', $data, $config);
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

        $produto = $this->Produto_model->buscar_por_id($id);
        if (!$produto) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Produto não encontrado'
                ]));
            return;
        }

        // Verificar se produto tem movimentações de estoque
        $tem_movimentacoes = $this->Estoque_model->produto_tem_movimentacoes($id);
        if ($tem_movimentacoes) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Não é possível excluir produto que possui movimentações de estoque'
                ]));
            return;
        }

        if ($this->Produto_model->excluir_produto($id)) {
            // Remover imagens associadas
            if ($produto->imagem_principal && file_exists('./uploads/produtos/' . $produto->imagem_principal)) {
                unlink('./uploads/produtos/' . $produto->imagem_principal);
            }

            $galeria = json_decode($produto->galeria_imagens, true);
            if ($galeria) {
                foreach ($galeria as $imagem) {
                    if (file_exists('./uploads/produtos/' . $imagem)) {
                        unlink('./uploads/produtos/' . $imagem);
                    }
                }
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Produto excluído com sucesso!'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Erro ao excluir produto'
                ]));
        }
    }

    public function ajustar_estoque($id = null)
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

        if ($this->input->post()) {
            $quantidade_nova = $this->input->post('quantidade_nova');
            $motivo = $this->input->post('motivo');

            $result = $this->Estoque_model->ajustar_estoque($id, $quantidade_nova, $motivo);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
        }
    }

    // Validação personalizada para SKU único no cadastro
    public function verificar_sku_unico($sku)
    {
        if ($this->Produto_model->verificar_sku_existe($sku)) {
            $this->form_validation->set_message('verificar_sku_unico', 'Este SKU já está sendo usado por outro produto.');
            return FALSE;
        }
        return TRUE;
    }

    // Validação personalizada para SKU único na edição
    public function verificar_sku_edicao($sku, $id)
    {
        if ($this->Produto_model->verificar_sku_existe($sku, $id)) {
            $this->form_validation->set_message('verificar_sku_edicao', 'Este SKU já está sendo usado por outro produto.');
            return FALSE;
        }
        return TRUE;
    }

    private function gerar_slug($nome, $excluir_id = null)
    {
        $slug_base = url_title($nome, 'dash', TRUE);
        $slug = $slug_base;
        $contador = 1;

        // Verificar se slug já existe
        while ($this->Produto_model->verificar_slug_existe($slug, $excluir_id)) {
            $slug = $slug_base . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    private function fazer_upload_imagem($campo)
    {
        if (empty($_FILES[$campo]['name'])) {
            return null;
        }

        $config['upload_path'] = './uploads/produtos/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        // Criar diretório se não existir
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload($campo)) {
            $upload_data = $this->upload->data();
            return $upload_data['file_name'];
        }

        return null;
    }

    private function fazer_upload_galeria()
    {
        $imagens = [];

        if (empty($_FILES['galeria']['name'][0])) {
            return $imagens;
        }

        $config['upload_path'] = './uploads/produtos/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        // Criar diretório se não existir
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $files = $_FILES['galeria'];

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] == 0) {
                $_FILES['galeria_upload'] = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('galeria_upload')) {
                    $upload_data = $this->upload->data();
                    $imagens[] = $upload_data['file_name'];
                }
            }
        }

        return $imagens;
    }

    public function remover_imagem_galeria()
    {
        $produto_id = $this->input->post('produto_id');
        $imagem = $this->input->post('imagem');

        if (!$produto_id || !$imagem) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Dados inválidos'
                ]));
            return;
        }

        $produto = $this->Produto_model->buscar_por_id($produto_id);
        if (!$produto) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Produto não encontrado'
                ]));
            return;
        }

        $galeria = json_decode($produto->galeria_imagens, true) ?: [];
        $nova_galeria = array_filter($galeria, function($img) use ($imagem) {
            return $img !== $imagem;
        });

        $dados = [
            'galeria_imagens' => json_encode(array_values($nova_galeria)),
            'data_atualizacao' => date('Y-m-d H:i:s')
        ];

        if ($this->Produto_model->atualizar_produto($produto_id, $dados)) {
            // Remover arquivo físico
            if (file_exists('./uploads/produtos/' . $imagem)) {
                unlink('./uploads/produtos/' . $imagem);
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Imagem removida com sucesso!'
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Erro ao remover imagem'
                ]));
        }
    }
}