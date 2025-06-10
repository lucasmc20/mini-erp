<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estoque_model extends CI_Model 
{
    protected $table = 'estoque';
    protected $table_movimentacoes = 'estoque_movimentacoes';
    protected $primary_key = 'id';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buscar todos os registros de estoque
     */
    public function get_all($limit = null, $offset = null)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->select('e.*, p.nome as produto_nome, p.sku as produto_codigo, c.nome as categoria_nome');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->order_by('e.id', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar registro por ID
     */
    public function get_by_id($id)
    {
        $this->db->select('e.*, p.nome as produto_nome, p.sku as produto_codigo, c.nome as categoria_nome');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('e.id', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Buscar por produto ID
     */
    public function get_by_produto($produto_id)
    {
        $this->db->select('e.*, p.nome as produto_nome, p.sku as produto_codigo, c.nome as categoria_nome');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('e.produto_id', $produto_id);
        
        return $this->db->get()->row();
    }

    /**
     * Inserir novo registro
     */
    public function insert($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Atualizar registro
     */
    public function update($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar registro
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Contar total de registros
     */
    public function count_all()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * Dar entrada no estoque
     */
    public function entrada_estoque($produto_id, $quantidade, $dados_adicionais = [])
    {
        $estoque = $this->get_by_produto($produto_id);
        
        if ($estoque) {
            // Atualizar estoque existente
            $quantidade_anterior = $estoque->quantidade_atual;
            $nova_quantidade = $estoque->quantidade_atual + $quantidade;
            
            $update_data = [
                'quantidade_atual' => $nova_quantidade,
                'data_ultima_entrada' => date('Y-m-d H:i:s')
            ];
            
            // Adicionar dados extras se fornecidos
            if (!empty($dados_adicionais)) {
                $update_data = array_merge($update_data, $dados_adicionais);
            }
            
            $result = $this->update($estoque->id, $update_data);
            
            // Registrar movimentação
            if ($result) {
                $this->registrar_movimentacao(
                    $produto_id,
                    'entrada',
                    $quantidade,
                    $quantidade_anterior,
                    $nova_quantidade,
                    $dados_adicionais['custo_unitario'] ?? null,
                    'Entrada manual no estoque',
                    $dados_adicionais
                );
            }
            
            return $result;
        } else {
            // Criar novo registro de estoque
            $insert_data = [
                'produto_id' => $produto_id,
                'quantidade_atual' => $quantidade,
                'data_ultima_entrada' => date('Y-m-d H:i:s')
            ];
            
            if (!empty($dados_adicionais)) {
                $insert_data = array_merge($insert_data, $dados_adicionais);
            }
            
            $result = $this->insert($insert_data);
            
            // Registrar movimentação
            if ($result) {
                $this->registrar_movimentacao(
                    $produto_id,
                    'entrada',
                    $quantidade,
                    0,
                    $quantidade,
                    $dados_adicionais['custo_unitario'] ?? null,
                    'Primeira entrada no estoque',
                    $dados_adicionais
                );
            }
            
            return $result;
        }
    }

    /**
     * Dar saída no estoque
     */
    public function saida_estoque($produto_id, $quantidade, $motivo = 'Saída manual')
    {
        $estoque = $this->get_by_produto($produto_id);
        
        if (!$estoque) {
            return ['status' => false, 'message' => 'Produto não encontrado no estoque'];
        }
        
        $quantidade_disponivel = $estoque->quantidade_atual - $estoque->quantidade_reservada;
        
        if ($quantidade > $quantidade_disponivel) {
            return [
                'status' => false, 
                'message' => 'Quantidade insuficiente em estoque. Disponível: ' . $quantidade_disponivel
            ];
        }
        
        $quantidade_anterior = $estoque->quantidade_atual;
        $nova_quantidade = $estoque->quantidade_atual - $quantidade;
        
        $update_data = [
            'quantidade_atual' => $nova_quantidade,
            'data_ultima_saida' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->update($estoque->id, $update_data);
        
        // Registrar movimentação
        if ($result) {
            $this->registrar_movimentacao(
                $produto_id,
                'saida',
                -$quantidade,
                $quantidade_anterior,
                $nova_quantidade,
                null,
                $motivo
            );
        }
        
        return [
            'status' => $result,
            'message' => $result ? 'Saída registrada com sucesso' : 'Erro ao registrar saída'
        ];
    }

    /**
     * Ajustar estoque (para correções)
     */
    public function ajustar_estoque($produto_id, $quantidade_nova, $motivo = 'Ajuste de estoque')
    {
        $estoque = $this->get_by_produto($produto_id);
        
        if (!$estoque) {
            return ['status' => false, 'message' => 'Produto não encontrado no estoque'];
        }
        
        $quantidade_anterior = $estoque->quantidade_atual;
        $diferenca = $quantidade_nova - $quantidade_anterior;
        
        $update_data = [
            'quantidade_atual' => $quantidade_nova,
            'data_ultima_contagem' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->update($estoque->id, $update_data);
        
        // Registrar movimentação
        if ($result) {
            $this->registrar_movimentacao(
                $produto_id,
                'ajuste',
                $diferenca,
                $quantidade_anterior,
                $quantidade_nova,
                null,
                $motivo
            );
        }
        
        return [
            'status' => $result,
            'message' => $result ? 'Estoque ajustado com sucesso' : 'Erro ao ajustar estoque'
        ];
    }

    /**
     * Reservar quantidade no estoque
     */
    public function reservar_estoque($produto_id, $quantidade)
    {
        $estoque = $this->get_by_produto($produto_id);
        
        if (!$estoque) {
            return ['status' => false, 'message' => 'Produto não encontrado no estoque'];
        }
        
        $quantidade_disponivel = $estoque->quantidade_atual - $estoque->quantidade_reservada;
        
        if ($quantidade > $quantidade_disponivel) {
            return [
                'status' => false,
                'message' => 'Quantidade insuficiente para reserva. Disponível: ' . $quantidade_disponivel
            ];
        }
        
        $nova_quantidade_reservada = $estoque->quantidade_reservada + $quantidade;
        
        $result = $this->update($estoque->id, ['quantidade_reservada' => $nova_quantidade_reservada]);
        
        return [
            'status' => $result,
            'message' => $result ? 'Quantidade reservada com sucesso' : 'Erro ao reservar quantidade'
        ];
    }

    /**
     * Liberar reserva do estoque
     */
    public function liberar_reserva($produto_id, $quantidade)
    {
        $estoque = $this->get_by_produto($produto_id);
        
        if (!$estoque) {
            return ['status' => false, 'message' => 'Produto não encontrado no estoque'];
        }
        
        if ($quantidade > $estoque->quantidade_reservada) {
            return [
                'status' => false,
                'message' => 'Quantidade a liberar maior que a reservada'
            ];
        }
        
        $nova_quantidade_reservada = $estoque->quantidade_reservada - $quantidade;
        
        $result = $this->update($estoque->id, ['quantidade_reservada' => $nova_quantidade_reservada]);
        
        return [
            'status' => $result,
            'message' => $result ? 'Reserva liberada com sucesso' : 'Erro ao liberar reserva'
        ];
    }

    /**
     * Registrar movimentação de estoque
     */
    public function registrar_movimentacao($produto_id, $tipo, $quantidade, $quantidade_anterior, $quantidade_atual, $custo_unitario = null, $motivo = '', $dados_extras = [])
    {
        $dados_movimentacao = [
            'produto_id' => $produto_id,
            'tipo_movimentacao' => $tipo,
            'quantidade' => $quantidade,
            'quantidade_anterior' => $quantidade_anterior,
            'quantidade_atual' => $quantidade_atual,
            'custo_unitario' => $custo_unitario,
            'valor_total' => $custo_unitario ? ($custo_unitario * abs($quantidade)) : null,
            'motivo' => $motivo,
            'data_movimentacao' => date('Y-m-d H:i:s'),
            'usuario_id' => $this->session->userdata('usuario_id'),
            'ip_usuario' => $this->input->ip_address()
        ];

        // Adicionar dados extras se fornecidos
        if (!empty($dados_extras)) {
            if (isset($dados_extras['lote'])) $dados_movimentacao['lote'] = $dados_extras['lote'];
            if (isset($dados_extras['nota_fiscal'])) $dados_movimentacao['nota_fiscal'] = $dados_extras['nota_fiscal'];
            if (isset($dados_extras['fornecedor'])) $dados_movimentacao['fornecedor'] = $dados_extras['fornecedor'];
            if (isset($dados_extras['observacoes'])) $dados_movimentacao['observacoes'] = $dados_extras['observacoes'];
            if (isset($dados_extras['documento_referencia'])) $dados_movimentacao['documento_referencia'] = $dados_extras['documento_referencia'];
        }

        return $this->db->insert($this->table_movimentacoes, $dados_movimentacao);
    }

    /**
     * Buscar movimentações de um produto
     */
    public function get_movimentacoes_produto($produto_id, $limit = null)
    {
        $this->db->select('m.*, p.nome as produto_nome, p.sku');
        $this->db->from($this->table_movimentacoes . ' m');
        $this->db->join('produtos p', 'p.id = m.produto_id');
        $this->db->where('m.produto_id', $produto_id);
        $this->db->order_by('m.data_movimentacao', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Listar produtos com estoque baixo
     */
    public function get_estoque_baixo()
    {
        $this->db->select('e.*, p.nome as produto_nome, p.sku as produto_codigo, c.nome as categoria_nome');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        $this->db->where('p.ativo', 1);
        $this->db->order_by('e.quantidade_atual', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Listar produtos próximos ao vencimento
     */
    public function get_proximos_vencimento($dias = 30)
    {
        $data_limite = date('Y-m-d', strtotime("+{$dias} days"));
        
        $this->db->select('e.*, p.nome as produto_nome, p.sku as produto_codigo, c.nome as categoria_nome');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('e.data_validade <=', $data_limite);
        $this->db->where('e.data_validade IS NOT NULL');
        $this->db->where('p.ativo', 1);
        $this->db->order_by('e.data_validade', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Relatório de movimentação por período
     */
    public function get_movimentacao_periodo($data_inicio, $data_fim)
    {
        $this->db->select('m.*, p.nome as produto_nome, p.sku, c.nome as categoria_nome');
        $this->db->from($this->table_movimentacoes . ' m');
        $this->db->join('produtos p', 'p.id = m.produto_id');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('m.data_movimentacao >=', $data_inicio . ' 00:00:00');
        $this->db->where('m.data_movimentacao <=', $data_fim . ' 23:59:59');
        $this->db->order_by('m.data_movimentacao', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar com filtros
     */
    public function search($filters = [])
    {
        $this->db->select('e.*, p.nome as produto_nome, p.sku as produto_codigo, c.nome as categoria_nome');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        
        if (!empty($filters['produto_nome'])) {
            $this->db->like('p.nome', $filters['produto_nome']);
        }
        
        if (!empty($filters['lote'])) {
            $this->db->like('e.lote', $filters['lote']);
        }
        
        if (!empty($filters['fornecedor'])) {
            $this->db->like('e.fornecedor', $filters['fornecedor']);
        }
        
        if (!empty($filters['localizacao'])) {
            $this->db->like('e.localizacao', $filters['localizacao']);
        }

        if (!empty($filters['categoria_id'])) {
            $this->db->where('p.categoria_id', $filters['categoria_id']);
        }
        
        if (isset($filters['estoque_baixo']) && $filters['estoque_baixo']) {
            $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->db->where('e.status', $filters['status']);
        }
        
        $this->db->order_by('e.id', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Verificar se produto tem movimentações
     */
    public function produto_tem_movimentacoes($produto_id)
    {
        $this->db->where('produto_id', $produto_id);
        return $this->db->count_all_results($this->table_movimentacoes) > 0;
    }

    /**
     * Obter estatísticas gerais do estoque
     */
    public function get_estatisticas()
    {
        // Total de produtos no estoque
        $total_produtos = $this->count_all();
        
        // Produtos com estoque baixo
        $estoque_baixo = count($this->get_estoque_baixo());
        
        // Produtos próximos ao vencimento
        $proximos_vencimento = count($this->get_proximos_vencimento(30));
        
        // Valor total do estoque
        $this->db->select('SUM(quantidade_atual * COALESCE(custo_unitario, 0)) as valor_total');
        $this->db->from($this->table);
        $this->db->where('custo_unitario IS NOT NULL');
        $query = $this->db->get();
        $valor_total = $query->row()->valor_total ?: 0;
        
        // Total de itens em estoque
        $this->db->select('SUM(quantidade_atual) as total_itens');
        $this->db->from($this->table);
        $query_itens = $this->db->get();
        $total_itens = $query_itens->row()->total_itens ?: 0;

        // Total de itens reservados
        $this->db->select('SUM(quantidade_reservada) as total_reservado');
        $this->db->from($this->table);
        $query_reservado = $this->db->get();
        $total_reservado = $query_reservado->row()->total_reservado ?: 0;

        // Produtos sem estoque
        $this->db->where('quantidade_atual', 0);
        $sem_estoque = $this->db->count_all_results($this->table);

        return [
            'total_produtos' => $total_produtos,
            'estoque_baixo' => $estoque_baixo,
            'proximos_vencimento' => $proximos_vencimento,
            'valor_total_estoque' => $valor_total,
            'total_itens' => $total_itens,
            'total_reservado' => $total_reservado,
            'sem_estoque' => $sem_estoque
        ];
    }

    /**
     * Verificar se produto já tem estoque cadastrado
     */
    public function produto_tem_estoque($produto_id)
    {
        $this->db->where('produto_id', $produto_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Buscar produtos sem estoque cadastrado
     */
    public function get_produtos_sem_estoque()
    {
        $this->db->select('p.id, p.nome, p.sku, c.nome as categoria_nome');
        $this->db->from('produtos p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.controlar_estoque', 1);
        $this->db->where('p.id NOT IN (SELECT produto_id FROM ' . $this->table . ')', null, false);
        $this->db->order_by('p.nome', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Criar estoque inicial para produto
     */
    public function criar_estoque_inicial($produto_id, $dados = [])
    {
        $dados_padrao = [
            'produto_id' => $produto_id,
            'quantidade_atual' => 0,
            'quantidade_minima' => 1,
            'quantidade_reservada' => 0,
            'status' => 'ativo'
        ];
        
        $dados_finais = array_merge($dados_padrao, $dados);
        
        return $this->insert($dados_finais);
    }

    /**
     * Buscar produtos para entrada (produtos ativos que controlam estoque)
     */
    public function get_produtos_para_entrada()
    {
        $this->db->select('p.id, p.nome, p.sku, c.nome as categoria_nome');
        $this->db->from('produtos p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.controlar_estoque', 1);
        $this->db->order_by('p.nome', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar produtos com estoque para saída
     */
    public function get_produtos_com_estoque()
    {
        $this->db->select('p.id, p.nome, p.sku, e.quantidade_atual, e.quantidade_reservada, c.nome as categoria_nome');
        $this->db->from('produtos p');
        $this->db->join($this->table . ' e', 'e.produto_id = p.id');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.controlar_estoque', 1);
        $this->db->where('e.quantidade_atual >', 0);
        $this->db->order_by('p.nome', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Métodos de compatibilidade para a controller
     */

    /**
     * Listar produtos com filtros, paginação (similar ao Produto_model)
     */
    public function listar_estoque($filtros = [], $limite = null, $offset = null)
    {
        $resultados = $this->search($filtros);
        
        if ($limite) {
            return array_slice($resultados, $offset ?: 0, $limite);
        }
        
        return $resultados;
    }

    /**
     * Contar estoque com filtros
     */
    public function contar_estoque($filtros = [])
    {
        $resultados = $this->search($filtros);
        return count($resultados);
    }
}