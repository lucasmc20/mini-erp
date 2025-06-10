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
     * Listar produtos com filtros, paginação
     * Método usado na controller index()
     */
    public function listar_produtos($filtros = [], $limite = null, $offset = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);

        // Aplicar filtros
        if (!empty($filtros['nome'])) {
            $this->db->like('nome', $filtros['nome']);
        }

        if (!empty($filtros['categoria'])) {
            $this->db->where('categoria', $filtros['categoria']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $this->db->where('ativo', $filtros['ativo']);
        }

        // Paginação
        if ($limite) {
            $this->db->limit($limite, $offset);
        }

        $this->db->order_by('data_cadastro', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Contar produtos com filtros
     * Método usado na controller index()
     */
    public function contar_produtos($filtros = [])
    {
        $this->db->from($this->table);

        // Aplicar os mesmos filtros do listar_produtos
        if (!empty($filtros['nome'])) {
            $this->db->like('nome', $filtros['nome']);
        }

        if (!empty($filtros['categoria'])) {
            $this->db->where('categoria', $filtros['categoria']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $this->db->where('ativo', $filtros['ativo']);
        }

        return $this->db->count_all_results();
    }

    /**
     * Buscar produto por ID
     * Método usado na controller editar(), visualizar(), excluir()
     */
    public function buscar_por_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Cadastrar novo produto
     * Método usado na controller cadastrar()
     */
    public function cadastrar_produto($dados)
    {
        if ($this->db->insert($this->table, $dados)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Atualizar produto
     * Método usado na controller editar()
     */
    public function atualizar_produto($id, $dados)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $dados);
    }

    /**
     * Excluir produto
     * Método usado na controller excluir()
     */
    public function excluir_produto($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Verificar se SKU existe (para cadastro)
     * Método usado na validação verificar_sku_unico()
     */
    public function verificar_sku_existe($sku, $excluir_id = null)
    {
        $this->db->where('sku', $sku);
        if ($excluir_id) {
            $this->db->where('id !=', $excluir_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Listar categorias únicas para filtros e formulários
     * Método usado na controller index() e cadastrar()
     */
    public function listar_categorias()
    {
        $this->db->select('categoria');
        $this->db->distinct();
        $this->db->where('categoria IS NOT NULL');
        $this->db->where('categoria !=', '');
        $this->db->order_by('categoria', 'ASC');

        $result = $this->db->get($this->table)->result();

        // Retornar apenas os valores das categorias em um array
        $categorias = [];
        foreach ($result as $row) {
            $categorias[] = $row->categoria;
        }

        return $categorias;
    }

    // === MÉTODOS ORIGINAIS MANTIDOS PARA COMPATIBILIDADE ===

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
     * Buscar produto por ID (alias para compatibilidade)
     */
    public function get_by_id($id)
    {
        return $this->buscar_por_id($id);
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
     * Inserir novo produto (alias para compatibilidade)
     */
    public function insert($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        return $this->cadastrar_produto($data);
    }

    /**
     * Atualizar produto (alias para compatibilidade)
     */
    public function update($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        return $this->atualizar_produto($id, $data);
    }

    /**
     * Deletar produto (alias para compatibilidade)
     */
    public function delete($id)
    {
        return $this->excluir_produto($id);
    }

    /**
     * Verificar se SKU já existe (alias para compatibilidade)
     */
    public function sku_exists($sku, $exclude_id = null)
    {
        return $this->verificar_sku_existe($sku, $exclude_id);
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
     * Buscar categorias únicas (alias para compatibilidade)
     */
    public function get_categorias()
    {
        return $this->listar_categorias();
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

        $result = $this->db->get($this->table)->result();

        // Retornar apenas os valores das marcas em um array
        $marcas = [];
        foreach ($result as $row) {
            $marcas[] = $row->marca;
        }

        return $marcas;
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

    /**
     * Buscar produtos com baixo estoque
     * Método auxiliar para dashboard
     */
    public function get_produtos_baixo_estoque($limite_minimo = 10)
    {
        $this->db->select('p.*, e.quantidade_atual, e.quantidade_minima');
        $this->db->from($this->table . ' p');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        $this->db->order_by('e.quantidade_atual', 'ASC');
        $this->db->limit(10);

        return $this->db->get()->result();
    }

    /**
     * Contar produtos por status
     * Método auxiliar para dashboard
     */
    public function contar_por_status()
    {
        return [
            'total' => $this->db->count_all($this->table),
            'ativos' => $this->count_all(['ativo' => 1]),
            'inativos' => $this->count_all(['ativo' => 0]),
            'destaques' => $this->count_all(['ativo' => 1, 'destaque' => 1])
        ];
    }
}