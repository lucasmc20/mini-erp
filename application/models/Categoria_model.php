<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends CI_Model
{
    protected $table = 'categorias';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Listar categorias com filtros e paginação
     */
    public function listar_categorias($filtros = [], $limite = null, $offset = null)
    {
        $this->db->select('c.*, cp.nome as categoria_pai_nome, 
                          (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id AND p.ativo = 1) as total_produtos');
        $this->db->from($this->table . ' c');
        $this->db->join($this->table . ' cp', 'cp.id = c.categoria_pai_id', 'left');

        // Aplicar filtros
        if (!empty($filtros['nome'])) {
            $this->db->like('c.nome', $filtros['nome']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $this->db->where('c.ativo', $filtros['ativo']);
        }

        if (isset($filtros['destaque']) && $filtros['destaque'] !== '') {
            $this->db->where('c.destaque', $filtros['destaque']);
        }

        if (isset($filtros['nivel']) && $filtros['nivel'] !== '') {
            $this->db->where('c.nivel', $filtros['nivel']);
        }

        if (!empty($filtros['categoria_pai_id'])) {
            $this->db->where('c.categoria_pai_id', $filtros['categoria_pai_id']);
        }

        // Paginação
        if ($limite) {
            $this->db->limit($limite, $offset);
        }

        $this->db->order_by('c.ordem', 'ASC');
        $this->db->order_by('c.nome', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Contar categorias com filtros
     */
    public function contar_categorias($filtros = [])
    {
        $this->db->from($this->table);

        if (!empty($filtros['nome'])) {
            $this->db->like('nome', $filtros['nome']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $this->db->where('ativo', $filtros['ativo']);
        }

        if (isset($filtros['destaque']) && $filtros['destaque'] !== '') {
            $this->db->where('destaque', $filtros['destaque']);
        }

        if (isset($filtros['nivel']) && $filtros['nivel'] !== '') {
            $this->db->where('nivel', $filtros['nivel']);
        }

        if (!empty($filtros['categoria_pai_id'])) {
            $this->db->where('categoria_pai_id', $filtros['categoria_pai_id']);
        }

        return $this->db->count_all_results();
    }

    /**
     * Buscar categoria por ID
     */
    public function buscar_por_id($id)
    {
        $this->db->select('c.*, cp.nome as categoria_pai_nome,
                          (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id) as total_produtos');
        $this->db->from($this->table . ' c');
        $this->db->join($this->table . ' cp', 'cp.id = c.categoria_pai_id', 'left');
        $this->db->where('c.id', $id);

        return $this->db->get()->row();
    }

    /**
     * Buscar categoria por slug
     */
    public function buscar_por_slug($slug)
    {
        $this->db->select('c.*, cp.nome as categoria_pai_nome');
        $this->db->from($this->table . ' c');
        $this->db->join($this->table . ' cp', 'cp.id = c.categoria_pai_id', 'left');
        $this->db->where('c.slug', $slug);
        $this->db->where('c.ativo', 1);

        return $this->db->get()->row();
    }

    /**
     * Cadastrar nova categoria
     */
    public function cadastrar_categoria($dados)
    {
        $dados['data_cadastro'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert($this->table, $dados)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Atualizar categoria
     */
    public function atualizar_categoria($id, $dados)
    {
        $dados['data_atualizacao'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $dados);
    }

    /**
     * Excluir categoria (verificar se não tem produtos ou subcategorias)
     */
    public function excluir_categoria($id)
    {
        // Verificar se categoria tem produtos
        $this->db->where('categoria_id', $id);
        $produtos_vinculados = $this->db->count_all_results('produtos');
        
        if ($produtos_vinculados > 0) {
            return [
                'status' => false,
                'message' => 'Não é possível excluir categoria que possui produtos vinculados (' . $produtos_vinculados . ' produtos)'
            ];
        }

        // Verificar se categoria tem subcategorias
        $this->db->where('categoria_pai_id', $id);
        $subcategorias = $this->db->count_all_results($this->table);
        
        if ($subcategorias > 0) {
            return [
                'status' => false,
                'message' => 'Não é possível excluir categoria que possui subcategorias (' . $subcategorias . ' subcategorias)'
            ];
        }
        
        $result = $this->db->delete($this->table, ['id' => $id]);
        
        return [
            'status' => $result,
            'message' => $result ? 'Categoria excluída com sucesso' : 'Erro ao excluir categoria'
        ];
    }

    /**
     * Verificar se nome da categoria já existe
     */
    public function verificar_nome_existe($nome, $excluir_id = null)
    {
        $this->db->where('nome', $nome);
        if ($excluir_id) {
            $this->db->where('id !=', $excluir_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Verificar se slug da categoria já existe
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
     * Listar categorias ativas para dropdowns
     */
    public function listar_ativas($incluir_subcategorias = true)
    {
        $this->db->select('id, nome, categoria_pai_id, nivel');
        $this->db->where('ativo', 1);
        
        if (!$incluir_subcategorias) {
            $this->db->where('categoria_pai_id IS NULL');
        }
        
        $this->db->order_by('ordem', 'ASC');
        $this->db->order_by('nome', 'ASC');
        
        return $this->db->get($this->table)->result();
    }

    /**
     * Listar categorias principais (sem pai)
     */
    public function listar_principais()
    {
        $this->db->select('c.*, 
                          (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id AND p.ativo = 1) as total_produtos,
                          (SELECT COUNT(*) FROM categorias sc WHERE sc.categoria_pai_id = c.id AND sc.ativo = 1) as total_subcategorias');
        $this->db->from($this->table . ' c');
        $this->db->where('c.categoria_pai_id IS NULL');
        $this->db->where('c.ativo', 1);
        $this->db->order_by('c.ordem', 'ASC');
        $this->db->order_by('c.nome', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Listar subcategorias de uma categoria pai
     */
    public function listar_subcategorias($categoria_pai_id)
    {
        $this->db->select('c.*, 
                          (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id AND p.ativo = 1) as total_produtos');
        $this->db->from($this->table . ' c');
        $this->db->where('c.categoria_pai_id', $categoria_pai_id);
        $this->db->where('c.ativo', 1);
        $this->db->order_by('c.ordem', 'ASC');
        $this->db->order_by('c.nome', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Listar categorias em destaque
     */
    public function listar_destaques($limite = null)
    {
        $this->db->select('c.*, 
                          (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id = c.id AND p.ativo = 1) as total_produtos');
        $this->db->from($this->table . ' c');
        $this->db->where('c.ativo', 1);
        $this->db->where('c.destaque', 1);
        $this->db->order_by('c.ordem', 'ASC');
        
        if ($limite) {
            $this->db->limit($limite);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Buscar árvore completa de categorias (hierárquica)
     */
    public function get_arvore_categorias()
    {
        // Buscar categorias principais
        $principais = $this->listar_principais();
        
        // Para cada categoria principal, buscar suas subcategorias
        foreach ($principais as $categoria) {
            $categoria->subcategorias = $this->listar_subcategorias($categoria->id);
        }
        
        return $principais;
    }

    /**
     * Buscar categorias para select hierárquico
     */
    public function get_categorias_select($categoria_excluir = null)
    {
        $categorias = [];
        
        // Buscar categorias principais
        $this->db->select('id, nome, nivel');
        $this->db->where('ativo', 1);
        $this->db->where('categoria_pai_id IS NULL');
        
        if ($categoria_excluir) {
            $this->db->where('id !=', $categoria_excluir);
        }
        
        $this->db->order_by('ordem', 'ASC');
        $this->db->order_by('nome', 'ASC');
        $principais = $this->db->get($this->table)->result();
        
        foreach ($principais as $principal) {
            $categorias[$principal->id] = $principal->nome;
            
            // Buscar subcategorias
            $this->db->select('id, nome, nivel');
            $this->db->where('categoria_pai_id', $principal->id);
            $this->db->where('ativo', 1);
            
            if ($categoria_excluir) {
                $this->db->where('id !=', $categoria_excluir);
            }
            
            $this->db->order_by('ordem', 'ASC');
            $this->db->order_by('nome', 'ASC');
            $subcategorias = $this->db->get($this->table)->result();
            
            foreach ($subcategorias as $sub) {
                $categorias[$sub->id] = '-- ' . $sub->nome;
            }
        }
        
        return $categorias;
    }

    /**
     * Contar produtos por categoria
     */
    public function contar_produtos_por_categoria()
    {
        $this->db->select('c.id, c.nome, c.cor_destaque, COUNT(p.id) as total_produtos');
        $this->db->from($this->table . ' c');
        $this->db->join('produtos p', 'p.categoria_id = c.id AND p.ativo = 1', 'left');
        $this->db->where('c.ativo', 1);
        $this->db->group_by('c.id, c.nome, c.cor_destaque');
        $this->db->order_by('total_produtos', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar categorias mais usadas
     */
    public function get_categorias_populares($limite = 5)
    {
        $this->db->select('c.id, c.nome, c.slug, c.cor_destaque, c.icone, COUNT(p.id) as total_produtos');
        $this->db->from($this->table . ' c');
        $this->db->join('produtos p', 'p.categoria_id = c.id AND p.ativo = 1');
        $this->db->where('c.ativo', 1);
        $this->db->group_by('c.id, c.nome, c.slug, c.cor_destaque, c.icone');
        $this->db->order_by('total_produtos', 'DESC');
        $this->db->limit($limite);
        
        return $this->db->get()->result();
    }

    /**
     * Buscar caminho da categoria (breadcrumb)
     */
    public function get_caminho_categoria($categoria_id)
    {
        $caminho = [];
        $categoria_atual = $this->buscar_por_id($categoria_id);
        
        while ($categoria_atual) {
            array_unshift($caminho, $categoria_atual);
            
            if ($categoria_atual->categoria_pai_id) {
                $categoria_atual = $this->buscar_por_id($categoria_atual->categoria_pai_id);
            } else {
                break;
            }
        }
        
        return $caminho;
    }

    /**
     * Reordenar categorias
     */
    public function reordenar_categorias($ordem_ids)
    {
        foreach ($ordem_ids as $posicao => $categoria_id) {
            $this->db->where('id', $categoria_id);
            $this->db->update($this->table, ['ordem' => $posicao + 1]);
        }
        
        return true;
    }

    /**
     * Buscar próxima ordem disponível
     */
    public function get_proxima_ordem($categoria_pai_id = null)
    {
        $this->db->select_max('ordem');
        
        if ($categoria_pai_id) {
            $this->db->where('categoria_pai_id', $categoria_pai_id);
        } else {
            $this->db->where('categoria_pai_id IS NULL');
        }
        
        $result = $this->db->get($this->table)->row();
        
        return ($result->ordem ?? 0) + 1;
    }

    /**
     * Buscar estatísticas das categorias
     */
    public function get_estatisticas()
    {
        // Total de categorias
        $total = $this->db->count_all($this->table);
        
        // Categorias ativas
        $ativas = $this->db->where('ativo', 1)->count_all_results($this->table);
        
        // Categorias principais
        $this->db->where('categoria_pai_id IS NULL');
        $principais = $this->db->count_all_results($this->table);
        
        // Subcategorias
        $this->db->where('categoria_pai_id IS NOT NULL');
        $subcategorias = $this->db->count_all_results($this->table);
        
        // Categorias em destaque
        $this->db->where('destaque', 1);
        $this->db->where('ativo', 1);
        $destaques = $this->db->count_all_results($this->table);
        
        // Categorias sem produtos
        $this->db->select('c.id');
        $this->db->from($this->table . ' c');
        $this->db->where('c.ativo', 1);
        $this->db->where('c.id NOT IN (SELECT DISTINCT categoria_id FROM produtos WHERE categoria_id IS NOT NULL)', null, false);
        $sem_produtos = $this->db->count_all_results();
        
        return [
            'total' => $total,
            'ativas' => $ativas,
            'inativas' => $total - $ativas,
            'principais' => $principais,
            'subcategorias' => $subcategorias,
            'destaques' => $destaques,
            'sem_produtos' => $sem_produtos
        ];
    }

    /**
     * Buscar categorias com produtos em estoque baixo
     */
    public function get_categorias_estoque_baixo()
    {
        $this->db->select('c.id, c.nome, COUNT(p.id) as produtos_estoque_baixo');
        $this->db->from($this->table . ' c');
        $this->db->join('produtos p', 'p.categoria_id = c.id AND p.ativo = 1');
        $this->db->join('estoque e', 'e.produto_id = p.id');
        $this->db->where('c.ativo', 1);
        $this->db->where('e.quantidade_atual <=', 'e.quantidade_minima', false);
        $this->db->group_by('c.id, c.nome');
        $this->db->having('produtos_estoque_baixo >', 0);
        $this->db->order_by('produtos_estoque_baixo', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar categoria por nome (busca aproximada)
     */
    public function buscar_por_nome($nome)
    {
        $this->db->select('id, nome, slug');
        $this->db->like('nome', $nome);
        $this->db->where('ativo', 1);
        $this->db->order_by('nome', 'ASC');
        $this->db->limit(10);
        
        return $this->db->get($this->table)->result();
    }

    /**
     * Validar se categoria pode ter subcategorias
     */
    public function pode_ter_subcategorias($categoria_id)
    {
        $categoria = $this->buscar_por_id($categoria_id);
        
        if (!$categoria) {
            return false;
        }
        
        // Limitar a 2 níveis (principais e subcategorias)
        return $categoria->nivel < 2;
    }

    /**
     * Atualizar contador de produtos da categoria
     */
    public function atualizar_contador_produtos($categoria_id)
    {
        $this->db->where('categoria_id', $categoria_id);
        $this->db->where('ativo', 1);
        $total_produtos = $this->db->count_all_results('produtos');
        
        // Aqui você pode adicionar um campo contador na tabela se desejar
        // Por enquanto, o contador é calculado dinamicamente nas consultas
        
        return $total_produtos;
    }

    // === MÉTODOS DE COMPATIBILIDADE ===

    /**
     * Buscar todas as categorias (alias para compatibilidade)
     */
    public function get_all($limit = null, $offset = null, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('ordem', 'ASC');
        $this->db->order_by('nome', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Buscar categoria por ID (alias para compatibilidade)
     */
    public function get_by_id($id)
    {
        return $this->buscar_por_id($id);
    }

    /**
     * Inserir nova categoria (alias para compatibilidade)
     */
    public function insert($data)
    {
        return $this->cadastrar_categoria($data);
    }

    /**
     * Atualizar categoria (alias para compatibilidade)
     */
    public function update($id, $data)
    {
        return $this->atualizar_categoria($id, $data);
    }

    /**
     * Deletar categoria (alias para compatibilidade)
     */
    public function delete($id)
    {
        $result = $this->excluir_categoria($id);
        return $result['status'];
    }

    /**
     * Contar total de categorias
     */
    public function count_all($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }
}