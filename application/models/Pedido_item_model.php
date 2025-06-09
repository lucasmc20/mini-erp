<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_item_model extends CI_Model 
{
    protected $table = 'pedido_itens';
    protected $primary_key = 'id';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Buscar itens por pedido
     */
    public function get_by_pedido($pedido_id)
    {
        $this->db->select('pi.*, p.nome as produto_nome, p.sku, p.imagem_principal, p.categoria, p.marca');
        $this->db->from($this->table . ' pi');
        $this->db->join('produtos p', 'p.id = pi.produto_id', 'left');
        $this->db->where('pi.pedido_id', $pedido_id);
        $this->db->order_by('pi.data_adicionado', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Buscar item específico
     */
    public function get_by_id($id)
    {
        $this->db->select('pi.*, p.nome as produto_nome, p.sku');
        $this->db->from($this->table . ' pi');
        $this->db->join('produtos p', 'p.id = pi.produto_id', 'left');
        $this->db->where('pi.id', $id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Inserir item no pedido
     */
    public function insert($data)
    {
        $data['data_adicionado'] = date('Y-m-d H:i:s');
        $data['preco_total'] = $data['quantidade'] * $data['preco_unitario'];
        
        // Capturar dados do produto para histórico
        if (isset($data['produto_id'])) {
            $this->load->model('Produto_model');
            $produto = $this->Produto_model->get_by_id($data['produto_id']);
            
            if ($produto) {
                $data['dados_produto'] = json_encode([
                    'nome' => $produto->nome,
                    'sku' => $produto->sku,
                    'categoria' => $produto->categoria,
                    'marca' => $produto->marca,
                    'descricao' => $produto->descricao
                ]);
            }
        }
        
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }
    
    /**
     * Atualizar item do pedido
     */
    public function update($id, $data)
    {
        // Recalcular preço total se quantidade ou preço unitário foram alterados
        $item_atual = $this->get_by_id($id);
        if ($item_atual) {
            $quantidade = isset($data['quantidade']) ? $data['quantidade'] : $item_atual->quantidade;
            $preco_unitario = isset($data['preco_unitario']) ? $data['preco_unitario'] : $item_atual->preco_unitario;
            $data['preco_total'] = $quantidade * $preco_unitario;
        }
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Atualizar quantidade do item
     */
    public function update_quantidade($id, $quantidade)
    {
        $item = $this->get_by_id($id);
        if (!$item) {
            return false;
        }
        
        $data = [
            'quantidade' => $quantidade,
            'preco_total' => $quantidade * $item->preco_unitario
        ];
        
        return $this->update($id, $data);
    }
    
    /**
     * Deletar item do pedido
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    
    /**
     * Deletar todos os itens de um pedido
     */
    public function delete_by_pedido($pedido_id)
    {
        return $this->db->delete($this->table, ['pedido_id' => $pedido_id]);
    }
    
    /**
     * Verificar se produto já existe no pedido
     */
    public function produto_exists_in_pedido($pedido_id, $produto_id)
    {
        $where = ['pedido_id' => $pedido_id, 'produto_id' => $produto_id];
        return $this->db->get_where($this->table, $where)->row();
    }
    
    /**
     * Adicionar ou atualizar item no pedido
     */
    public function add_or_update_item($pedido_id, $produto_id, $quantidade, $preco_unitario)
    {
        $item_existente = $this->produto_exists_in_pedido($pedido_id, $produto_id);
        
        if ($item_existente) {
            // Atualizar quantidade do item existente
            $nova_quantidade = $item_existente->quantidade + $quantidade;
            return $this->update_quantidade($item_existente->id, $nova_quantidade);
        } else {
            // Inserir novo item
            $data = [
                'pedido_id' => $pedido_id,
                'produto_id' => $produto_id,
                'quantidade' => $quantidade,
                'preco_unitario' => $preco_unitario
            ];
            return $this->insert($data);
        }
    }
    
    /**
     * Calcular total do pedido
     */
    public function calculate_pedido_total($pedido_id)
    {
        $this->db->select_sum('preco_total');
        $this->db->where('pedido_id', $pedido_id);
        $result = $this->db->get($this->table)->row();
        
        return $result->preco_total ?? 0;
    }
    
    /**
     * Contar itens do pedido
     */
    public function count_by_pedido($pedido_id)
    {
        return $this->db->where('pedido_id', $pedido_id)->count_all_results($this->table);
    }
    
    /**
     * Calcular quantidade total de itens no pedido
     */
    public function get_total_quantidade($pedido_id)
    {
        $this->db->select_sum('quantidade');
        $this->db->where('pedido_id', $pedido_id);
        $result = $this->db->get($this->table)->row();
        
        return $result->quantidade ?? 0;
    }
    
    /**
     * Relatório - Produtos mais vendidos
     */
    public function get_produtos_mais_vendidos($limit = 10, $data_inicio = null, $data_fim = null)
    {
        $this->db->select('pi.produto_id, p.nome as produto_nome, p.sku, SUM(pi.quantidade) as total_vendido, SUM(pi.preco_total) as valor_total');
        $this->db->from($this->table . ' pi');
        $this->db->join('produtos p', 'p.id = pi.produto_id', 'left');
        $this->db->join('pedidos ped', 'ped.id = pi.pedido_id', 'left');
        $this->db->where_in('ped.status', ['confirmado', 'processando', 'enviado', 'entregue']);
        
        if ($data_inicio && $data_fim) {
            $this->db->where('ped.data_pedido >=', $data_inicio);
            $this->db->where('ped.data_pedido <=', $data_fim);
        }
        
        $this->db->group_by('pi.produto_id');
        $this->db->order_by('total_vendido', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Validar estoque antes de adicionar item
     */
    public function validate_estoque($produto_id, $quantidade)
    {
        $this->load->model('Estoque_model');
        $estoque = $this->Estoque_model->get_by_produto($produto_id);
        
        if (!$estoque) {
            return ['success' => false, 'message' => 'Produto não encontrado no estoque'];
        }
        
        $disponivel = $estoque->quantidade_atual - $estoque->quantidade_reservada;
        
        if ($disponivel < $quantidade) {
            return [
                'success' => false, 
                'message' => "Estoque insuficiente. Disponível: {$disponivel}, Solicitado: {$quantidade}"
            ];
        }
        
        return ['success' => true];
    }
}