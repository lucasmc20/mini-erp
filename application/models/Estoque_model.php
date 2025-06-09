<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estoque_model extends CI_Model 
{
    protected $table = 'estoque';
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
        
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->order_by('e.id', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar registro por ID
     */
    public function get_by_id($id)
    {
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->where('e.id', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Buscar por produto ID
     */
    public function get_by_produto($produto_id)
    {
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
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
            $nova_quantidade = $estoque->quantidade_atual + $quantidade;
            
            $update_data = [
                'quantidade_atual' => $nova_quantidade,
                'data_ultima_entrada' => date('Y-m-d H:i:s')
            ];
            
            // Adicionar dados extras se fornecidos
            if (!empty($dados_adicionais)) {
                $update_data = array_merge($update_data, $dados_adicionais);
            }
            
            return $this->update($estoque->id, $update_data);
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
            
            return $this->insert($insert_data);
        }
    }

    /**
     * Dar saída no estoque
     */
    public function saida_estoque($produto_id, $quantidade)
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
        
        $nova_quantidade = $estoque->quantidade_atual - $quantidade;
        
        $update_data = [
            'quantidade_atual' => $nova_quantidade,
            'data_ultima_saida' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->update($estoque->id, $update_data);
        
        return [
            'status' => $result,
            'message' => $result ? 'Saída registrada com sucesso' : 'Erro ao registrar saída'
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
     * Listar produtos com estoque baixo
     */
    public function get_estoque_baixo()
    {
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        $this->db->order_by('e.quantidade_atual', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Listar produtos próximos ao vencimento
     */
    public function get_proximos_vencimento($dias = 30)
    {
        $data_limite = date('Y-m-d', strtotime("+{$dias} days"));
        
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->where('e.data_validade <=', $data_limite);
        $this->db->where('e.data_validade IS NOT NULL');
        $this->db->order_by('e.data_validade', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Relatório de movimentação por período
     */
    public function get_movimentacao_periodo($data_inicio, $data_fim)
    {
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        $this->db->group_start();
        $this->db->where('e.data_ultima_entrada >=', $data_inicio);
        $this->db->where('e.data_ultima_entrada <=', $data_fim);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('e.data_ultima_saida >=', $data_inicio);
        $this->db->where('e.data_ultima_saida <=', $data_fim);
        $this->db->group_end();
        $this->db->order_by('e.data_atualizacao', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar com filtros
     */
    public function search($filters = [])
    {
        $this->db->select('e.*, p.nome as produto_nome, p.codigo as produto_codigo');
        $this->db->from($this->table . ' e');
        $this->db->join('produtos p', 'p.id = e.produto_id', 'left');
        
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
        
        if (isset($filters['estoque_baixo']) && $filters['estoque_baixo']) {
            $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        }
        
        $this->db->order_by('e.id', 'DESC');
        
        return $this->db->get()->result();
    }
}