<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto_model extends CI_Model 
{
    protected $table = 'produtos';
    protected $primary_key = 'id';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Buscar todos os produtos
     */
    public function get_all($limit = null, $offset = null, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('nome', 'ASC');
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Buscar produto por ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    /**
     * Buscar produto por SKU
     */
    public function get_by_sku($sku)
    {
        return $this->db->get_where($this->table, ['sku' => $sku])->row();
    }
    
    /**
     * Buscar produtos ativos
     */
    public function get_ativos($limit = null, $offset = null)
    {
        return $this->get_all($limit, $offset, ['ativo' => 1]);
    }
    
    /**
     * Buscar produtos em destaque
     */
    public function get_destaques($limit = null)
    {
        $where = ['ativo' => 1, 'destaque' => 1];
        return $this->get_all($limit, null, $where);
    }
    
    /**
     * Buscar produtos por categoria
     */
    public function get_by_categoria($categoria, $limit = null, $offset = null)
    {
        $where = ['categoria' => $categoria, 'ativo' => 1];
        return $this->get_all($limit, $offset, $where);
    }
    
    /**
     * Inserir novo produto
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
     * Atualizar produto
     */
    public function update($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Deletar produto
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    
    /**
     * Verificar se SKU já existe
     */
    public function sku_exists($sku, $exclude_id = null)
    {
        $this->db->where('sku', $sku);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Buscar produtos por termo de pesquisa
     */
    public function search($term, $limit = null, $offset = null)
    {
        $this->db->like('nome', $term);
        $this->db->or_like('descricao', $term);
        $this->db->or_like('categoria', $term);
        $this->db->or_like('marca', $term);
        $this->db->where('ativo', 1);
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Contar total de produtos
     */
    public function count_all($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Buscar categorias únicas
     */
    public function get_categorias()
    {
        $this->db->select('categoria');
        $this->db->distinct();
        $this->db->where('categoria IS NOT NULL');
        $this->db->where('categoria !=', '');
        $this->db->where('ativo', 1);
        $this->db->order_by('categoria', 'ASC');
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Buscar marcas únicas
     */
    public function get_marcas()
    {
        $this->db->select('marca');
        $this->db->distinct();
        $this->db->where('marca IS NOT NULL');
        $this->db->where('marca !=', '');
        $this->db->where('ativo', 1);
        $this->db->order_by('marca', 'ASC');
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Verificar disponibilidade (integração com estoque)
     */
    public function check_disponibilidade($produto_id, $quantidade = 1)
    {
        $this->db->select('e.quantidade_atual, e.quantidade_reservada');
        $this->db->from('estoque e');
        $this->db->where('e.produto_id', $produto_id);
        $estoque = $this->db->get()->row();
        
        if (!$estoque) {
            return false;
        }
        
        $disponivel = $estoque->quantidade_atual - $estoque->quantidade_reservada;
        return $disponivel >= $quantidade;
    }
}