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
     * Listar produtos com filtros, paginação e informações de estoque
     */
    public function listar_produtos($filtros = [], $limite = null, $offset = null)
    {
        $this->db->select('p.*, c.nome as categoria_nome, c.cor_destaque as categoria_cor, 
                          e.quantidade_atual, e.quantidade_minima, e.quantidade_reservada, 
                          e.localizacao, e.status as status_estoque,
                          (e.quantidade_atual - e.quantidade_reservada) as quantidade_disponivel');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');

        // Aplicar filtros
        if (!empty($filtros['nome'])) {
            $this->db->like('p.nome', $filtros['nome']);
        }

        if (!empty($filtros['categoria_id'])) {
            $this->db->where('p.categoria_id', $filtros['categoria_id']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $this->db->where('p.ativo', $filtros['ativo']);
        }

        if (!empty($filtros['marca'])) {
            $this->db->like('p.marca', $filtros['marca']);
        }

        if (isset($filtros['estoque_baixo']) && $filtros['estoque_baixo']) {
            $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        }

        if (isset($filtros['sem_estoque']) && $filtros['sem_estoque']) {
            $this->db->where('e.quantidade_atual', 0);
        }

        if (isset($filtros['controlar_estoque']) && $filtros['controlar_estoque'] !== '') {
            $this->db->where('p.controlar_estoque', $filtros['controlar_estoque']);
        }

        // Paginação
        if ($limite) {
            $this->db->limit($limite, $offset);
        }

        $this->db->order_by('p.data_cadastro', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Contar produtos com filtros
     */
    public function contar_produtos($filtros = [])
    {
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');

        // Aplicar os mesmos filtros do listar_produtos
        if (!empty($filtros['nome'])) {
            $this->db->like('p.nome', $filtros['nome']);
        }

        if (!empty($filtros['categoria_id'])) {
            $this->db->where('p.categoria_id', $filtros['categoria_id']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $this->db->where('p.ativo', $filtros['ativo']);
        }

        if (!empty($filtros['marca'])) {
            $this->db->like('p.marca', $filtros['marca']);
        }

        if (isset($filtros['estoque_baixo']) && $filtros['estoque_baixo']) {
            $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        }

        if (isset($filtros['sem_estoque']) && $filtros['sem_estoque']) {
            $this->db->where('e.quantidade_atual', 0);
        }

        if (isset($filtros['controlar_estoque']) && $filtros['controlar_estoque'] !== '') {
            $this->db->where('p.controlar_estoque', $filtros['controlar_estoque']);
        }

        return $this->db->count_all_results();
    }

    /**
     * Buscar produto por ID (básico)
     */
    public function buscar_por_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Buscar produto por ID com informações completas (categoria e estoque)
     */
    public function buscar_por_id_completo($id)
    {
        $this->db->select('p.*, c.nome as categoria_nome, c.cor_destaque as categoria_cor,
                          e.quantidade_atual, e.quantidade_minima, e.quantidade_maxima, 
                          e.quantidade_reservada, e.localizacao, e.lote, e.data_validade,
                          e.data_fabricacao, e.custo_unitario, e.fornecedor, 
                          e.codigo_fornecedor, e.observacoes as observacoes_estoque,
                          e.status as status_estoque, e.data_ultima_entrada, e.data_ultima_saida,
                          (e.quantidade_atual - e.quantidade_reservada) as quantidade_disponivel');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');
        $this->db->where('p.id', $id);

        return $this->db->get()->row();
    }

    /**
     * Cadastrar novo produto
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
     */
    public function atualizar_produto($id, $dados)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $dados);
    }

    /**
     * Excluir produto
     */
    public function excluir_produto($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Verificar se SKU existe
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
     * Verificar se slug existe
     */
    public function verificar_slug_existe($slug, $excluir_id = null)
    {
        $this->db->where('slug', $slug);
        if ($excluir_id) {
            $this->db->where('id !=', $excluir_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Buscar produto por SKU
     */
    public function buscar_por_sku($sku)
    {
        return $this->db->get_where($this->table, ['sku' => $sku])->row();
    }

    /**
     * Buscar produto por slug
     */
    public function buscar_por_slug($slug)
    {
        $this->db->select('p.*, c.nome as categoria_nome');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.slug', $slug);
        $this->db->where('p.ativo', 1);

        return $this->db->get()->row();
    }

    /**
     * Buscar produtos ativos
     */
    public function get_ativos($limit = null, $offset = null)
    {
        $this->db->select('p.*, c.nome as categoria_nome');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('p.nome', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Buscar produtos em destaque
     */
    public function get_destaques($limit = null)
    {
        $this->db->select('p.*, c.nome as categoria_nome, c.cor_destaque as categoria_cor');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.destaque', 1);

        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->order_by('p.data_cadastro', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Buscar produtos por categoria
     */
    public function get_by_categoria($categoria_id, $limit = null, $offset = null)
    {
        $this->db->select('p.*, c.nome as categoria_nome, e.quantidade_atual');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');
        $this->db->where('p.categoria_id', $categoria_id);
        $this->db->where('p.ativo', 1);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('p.nome', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Buscar produtos relacionados (mesma categoria, exceto o produto atual)
     */
    public function get_relacionados($produto_id, $categoria_id, $limit = 6)
    {
        $this->db->select('p.*, c.nome as categoria_nome, e.quantidade_atual');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');
        $this->db->where('p.categoria_id', $categoria_id);
        $this->db->where('p.id !=', $produto_id);
        $this->db->where('p.ativo', 1);
        $this->db->limit($limit);
        $this->db->order_by('RAND()');

        return $this->db->get()->result();
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
     * Buscar produtos por termo de pesquisa
     */
    public function search($term, $limit = null, $offset = null, $filtros = [])
    {
        $this->db->select('p.*, c.nome as categoria_nome, e.quantidade_atual');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->join('estoque e', 'e.produto_id = p.id', 'left');
        
        // Busca por termo
        $this->db->group_start();
        $this->db->like('p.nome', $term);
        $this->db->or_like('p.descricao', $term);
        $this->db->or_like('c.nome', $term);
        $this->db->or_like('p.marca', $term);
        $this->db->or_like('p.tags', $term);
        $this->db->or_like('p.sku', $term);
        $this->db->group_end();

        $this->db->where('p.ativo', 1);

        // Aplicar filtros adicionais
        if (!empty($filtros['categoria_id'])) {
            $this->db->where('p.categoria_id', $filtros['categoria_id']);
        }

        if (!empty($filtros['marca'])) {
            $this->db->where('p.marca', $filtros['marca']);
        }

        if (!empty($filtros['preco_min'])) {
            $this->db->where('p.preco >=', $filtros['preco_min']);
        }

        if (!empty($filtros['preco_max'])) {
            $this->db->where('p.preco <=', $filtros['preco_max']);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('p.nome', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Contar resultados de busca
     */
    public function count_search($term, $filtros = [])
    {
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        
        // Busca por termo
        $this->db->group_start();
        $this->db->like('p.nome', $term);
        $this->db->or_like('p.descricao', $term);
        $this->db->or_like('c.nome', $term);
        $this->db->or_like('p.marca', $term);
        $this->db->or_like('p.tags', $term);
        $this->db->or_like('p.sku', $term);
        $this->db->group_end();

        $this->db->where('p.ativo', 1);

        // Aplicar filtros adicionais
        if (!empty($filtros['categoria_id'])) {
            $this->db->where('p.categoria_id', $filtros['categoria_id']);
        }

        if (!empty($filtros['marca'])) {
            $this->db->where('p.marca', $filtros['marca']);
        }

        if (!empty($filtros['preco_min'])) {
            $this->db->where('p.preco >=', $filtros['preco_min']);
        }

        if (!empty($filtros['preco_max'])) {
            $this->db->where('p.preco <=', $filtros['preco_max']);
        }

        return $this->db->count_all_results();
    }

    /**
     * Verificar disponibilidade em estoque
     */
    public function check_disponibilidade($produto_id, $quantidade = 1)
    {
        $this->db->select('e.quantidade_atual, e.quantidade_reservada, p.controlar_estoque, p.permite_venda_sem_estoque');
        $this->db->from('estoque e');
        $this->db->join($this->table . ' p', 'p.id = e.produto_id');
        $this->db->where('e.produto_id', $produto_id);
        $estoque = $this->db->get()->row();

        if (!$estoque) {
            return false;
        }

        // Se não controla estoque, sempre disponível
        if (!$estoque->controlar_estoque) {
            return true;
        }

        // Se permite venda sem estoque, sempre disponível
        if ($estoque->permite_venda_sem_estoque) {
            return true;
        }

        $disponivel = $estoque->quantidade_atual - $estoque->quantidade_reservada;
        return $disponivel >= $quantidade;
    }

    /**
     * Buscar produtos com baixo estoque
     */
    public function get_produtos_baixo_estoque($limite = 10)
    {
        $this->db->select('p.*, e.quantidade_atual, e.quantidade_minima, c.nome as categoria_nome');
        $this->db->from($this->table . ' p');
        $this->db->join('estoque e', 'e.produto_id = p.id');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.controlar_estoque', 1);
        $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        $this->db->order_by('e.quantidade_atual', 'ASC');
        
        if ($limite) {
            $this->db->limit($limite);
        }

        return $this->db->get()->result();
    }

    /**
     * Buscar produtos sem estoque
     */
    public function get_produtos_sem_estoque($limite = 10)
    {
        $this->db->select('p.*, c.nome as categoria_nome');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.controlar_estoque', 1);
        $this->db->where('p.id NOT IN (SELECT produto_id FROM estoque)', null, false);
        $this->db->order_by('p.data_cadastro', 'DESC');
        
        if ($limite) {
            $this->db->limit($limite);
        }

        return $this->db->get()->result();
    }

    /**
     * Contar produtos por status
     */
    public function contar_por_status()
    {
        return [
            'total' => $this->db->count_all($this->table),
            'ativos' => $this->count_all(['ativo' => 1]),
            'inativos' => $this->count_all(['ativo' => 0]),
            'destaques' => $this->count_all(['ativo' => 1, 'destaque' => 1]),
            'controla_estoque' => $this->count_all(['controlar_estoque' => 1])
        ];
    }

    /**
     * Contar total de produtos com condições
     */
    public function count_all($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Incrementar visualizações do produto
     */
    public function incrementar_views($produto_id)
    {
        $this->db->set('views', 'views + 1', FALSE);
        $this->db->where('id', $produto_id);
        return $this->db->update($this->table);
    }

    /**
     * Incrementar vendas do produto
     */
    public function incrementar_vendas($produto_id, $quantidade = 1)
    {
        $this->db->set('vendas_total', 'vendas_total + ' . (int)$quantidade, FALSE);
        $this->db->where('id', $produto_id);
        return $this->db->update($this->table);
    }

    /**
     * Buscar produtos mais vendidos
     */
    public function get_mais_vendidos($limit = 10)
    {
        $this->db->select('p.*, c.nome as categoria_nome');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.vendas_total >', 0);
        $this->db->order_by('p.vendas_total', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * Buscar produtos mais visualizados
     */
    public function get_mais_visualizados($limit = 10)
    {
        $this->db->select('p.*, c.nome as categoria_nome');
        $this->db->from($this->table . ' p');
        $this->db->join('categorias c', 'c.id = p.categoria_id', 'left');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.views >', 0);
        $this->db->order_by('p.views', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * Buscar estatísticas gerais dos produtos
     */
    public function get_estatisticas()
    {
        $stats = $this->contar_por_status();
        
        // Valor total do inventário
        $this->db->select('SUM(p.preco_custo * e.quantidade_atual) as valor_inventario');
        $this->db->from($this->table . ' p');
        $this->db->join('estoque e', 'e.produto_id = p.id');
        $this->db->where('p.ativo', 1);
        $this->db->where('p.preco_custo IS NOT NULL');
        $query = $this->db->get();
        $stats['valor_inventario'] = $query->row()->valor_inventario ?: 0;

        // Produtos em promoção
        $this->db->where('ativo', 1);
        $this->db->where('preco_promocional IS NOT NULL');
        $this->db->where('preco_promocional >', 0);
        $stats['em_promocao'] = $this->db->count_all_results($this->table);

        return $stats;
    }

    // === MÉTODOS DE COMPATIBILIDADE ===

    /**
     * Buscar todos os produtos (alias para compatibilidade)
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
     * Buscar produto por SKU (alias para compatibilidade)
     */
    public function get_by_sku($sku)
    {
        return $this->buscar_por_sku($sku);
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
     * Listar categorias únicas (método antigo - manter para compatibilidade)
     */
    public function listar_categorias()
    {
        // Este método agora usa a tabela categorias
        $this->load->model('Categoria_model');
        $categorias = $this->Categoria_model->listar_ativas();
        
        $nomes = [];
        foreach ($categorias as $categoria) {
            $nomes[] = $categoria->nome;
        }
        
        return $nomes;
    }

    /**
     * Buscar categorias únicas (alias para compatibilidade)
     */
    public function get_categorias()
    {
        return $this->listar_categorias();
    }
}