<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupom_model extends CI_Model 
{
    protected $table = 'cupons';
    protected $primary_key = 'id';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Buscar todos os cupons
     */
    public function get_all($limit = null, $offset = null, $where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('data_cadastro', 'DESC');
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Buscar cupom por ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    /**
     * Buscar cupom por código
     */
    public function get_by_codigo($codigo)
    {
        return $this->db->get_where($this->table, ['codigo' => $codigo])->row();
    }
    
    /**
     * Buscar cupons ativos
     */
    public function get_ativos($limit = null, $offset = null)
    {
        $where = [
            'ativo' => 1,
            'data_inicio <=' => date('Y-m-d H:i:s'),
            'data_fim >=' => date('Y-m-d H:i:s')
        ];
        
        return $this->get_all($limit, $offset, $where);
    }
    
    /**
     * Buscar cupons expirados
     */
    public function get_expirados($limit = null, $offset = null)
    {
        $where = ['data_fim <' => date('Y-m-d H:i:s')];
        return $this->get_all($limit, $offset, $where);
    }
    
    /**
     * Inserir novo cupom
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
     * Atualizar cupom
     */
    public function update($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Deletar cupom
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    
    /**
     * Verificar se código já existe
     */
    public function codigo_exists($codigo, $exclude_id = null)
    {
        $this->db->where('codigo', $codigo);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Validar cupom para uso
     */
    public function validate_cupom($codigo, $valor_pedido = 0, $usuario_id = null, $produtos = [])
    {
        $cupom = $this->get_by_codigo($codigo);
        
        if (!$cupom) {
            return ['valid' => false, 'message' => 'Cupom não encontrado'];
        }
        
        // Verificar se está ativo
        if (!$cupom->ativo) {
            return ['valid' => false, 'message' => 'Cupom inativo'];
        }
        
        // Verificar datas
        $agora = date('Y-m-d H:i:s');
        if ($cupom->data_inicio > $agora) {
            return ['valid' => false, 'message' => 'Cupom ainda não está válido'];
        }
        
        if ($cupom->data_fim < $agora) {
            return ['valid' => false, 'message' => 'Cupom expirado'];
        }
        
        // Verificar limite de uso total
        if ($cupom->limite_uso_total && $cupom->total_usado >= $cupom->limite_uso_total) {
            return ['valid' => false, 'message' => 'Cupom esgotado'];
        }
        
        // Verificar valor mínimo do pedido
        if ($cupom->valor_minimo_pedido && $valor_pedido < $cupom->valor_minimo_pedido) {
            return [
                'valid' => false, 
                'message' => "Valor mínimo do pedido: R$ " . number_format($cupom->valor_minimo_pedido, 2, ',', '.')
            ];
        }
        
        // Verificar uso por usuário
        if ($usuario_id && $cupom->limite_uso_usuario) {
            $usos_usuario = $this->count_usos_por_usuario($cupom->id, $usuario_id);
            if ($usos_usuario >= $cupom->limite_uso_usuario) {
                return ['valid' => false, 'message' => 'Limite de uso por usuário atingido'];
            }
        }
        
        // Verificar se é apenas para primeiro pedido
        if ($cupom->primeiro_pedido_apenas && $usuario_id) {
            if ($this->usuario_tem_pedidos_anteriores($usuario_id)) {
                return ['valid' => false, 'message' => 'Cupom válido apenas para primeiro pedido'];
            }
        }
        
        // Verificar produtos permitidos
        if ($cupom->produtos_permitidos && !empty($produtos)) {
            $produtos_permitidos = json_decode($cupom->produtos_permitidos, true);
            if ($produtos_permitidos && !$this->verificar_produtos_permitidos($produtos, $produtos_permitidos)) {
                return ['valid' => false, 'message' => 'Cupom não válido para os produtos selecionados'];
            }
        }
        
        // Verificar categorias permitidas
        if ($cupom->categorias_permitidas && !empty($produtos)) {
            $categorias_permitidas = json_decode($cupom->categorias_permitidas, true);
            if ($categorias_permitidas && !$this->verificar_categorias_permitidas($produtos, $categorias_permitidas)) {
                return ['valid' => false, 'message' => 'Cupom não válido para as categorias dos produtos'];
            }
        }
        
        return ['valid' => true, 'cupom' => $cupom];
    }
    
    /**
     * Calcular desconto do cupom
     */
    public function calcular_desconto($cupom, $valor_pedido)
    {
        if ($cupom->tipo_desconto == 'percentual') {
            $desconto = ($valor_pedido * $cupom->valor_desconto) / 100;
            
            // Verificar limite máximo de desconto
            if ($cupom->valor_maximo_desconto && $desconto > $cupom->valor_maximo_desconto) {
                $desconto = $cupom->valor_maximo_desconto;
            }
        } else {
            $desconto = $cupom->valor_desconto;
            
            // Desconto não pode ser maior que o valor do pedido
            if ($desconto > $valor_pedido) {
                $desconto = $valor_pedido;
            }
        }
        
        return $desconto;
    }
    
    /**
     * Registrar uso do cupom
     */
    public function registrar_uso($cupom_id)
    {
        $this->db->set('total_usado', 'total_usado + 1', false);
        $this->db->where('id', $cupom_id);
        return $this->db->update($this->table);
    }
    
    /**
     * Contar usos por usuário
     */
    private function count_usos_por_usuario($cupom_id, $usuario_id)
    {
        $this->db->where('cupom_id', $cupom_id);
        $this->db->where('usuario_id', $usuario_id);
        $this->db->where_not_in('status', ['cancelado']);
        return $this->db->count_all_results('pedidos');
    }
    
    /**
     * Verificar se usuário tem pedidos anteriores
     */
    private function usuario_tem_pedidos_anteriores($usuario_id)
    {
        $this->db->where('usuario_id', $usuario_id);
        $this->db->where_not_in('status', ['cancelado']);
        return $this->db->count_all_results('pedidos') > 0;
    }
    
    /**
     * Verificar produtos permitidos
     */
    private function verificar_produtos_permitidos($produtos, $produtos_permitidos)
    {
        foreach ($produtos as $produto) {
            if (!in_array($produto['id'], $produtos_permitidos)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Verificar categorias permitidas
     */
    private function verificar_categorias_permitidas($produtos, $categorias_permitidas)
    {
        foreach ($produtos as $produto) {
            if (!in_array($produto['categoria'], $categorias_permitidas)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Ativar/Desativar cupom
     */
    public function toggle_ativo($id)
    {
        $cupom = $this->get_by_id($id);
        if (!$cupom) {
            return false;
        }
        
        $novo_status = $cupom->ativo ? 0 : 1;
        return $this->update($id, ['ativo' => $novo_status]);
    }
    
    /**
     * Gerar código único para cupom
     */
    public function generate_codigo($prefixo = '', $tamanho = 8)
    {
        do {
            $codigo = $prefixo . strtoupper(substr(md5(uniqid(rand(), true)), 0, $tamanho));
        } while ($this->codigo_exists($codigo));
        
        return $codigo;
    }
    
    /**
     * Relatório de uso de cupons
     */
    public function get_relatorio_uso($data_inicio = null, $data_fim = null)
    {
        $this->db->select('c.codigo, c.nome, c.tipo_desconto, c.valor_desconto, c.total_usado, 
                          COUNT(p.id) as usos_periodo, SUM(p.valor_desconto) as valor_total_desconto');
        $this->db->from($this->table . ' c');
        $this->db->join('pedidos p', 'p.cupom_id = c.id', 'left');
        
        if ($data_inicio && $data_fim) {
            $this->db->where('p.data_pedido >=', $data_inicio);
            $this->db->where('p.data_pedido <=', $data_fim);
        }
        
        $this->db->group_by('c.id');
        $this->db->order_by('usos_periodo', 'DESC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Cupons mais utilizados
     */
    public function get_mais_utilizados($limit = 10)
    {
        $this->db->where('total_usado >', 0);
        $this->db->order_by('total_usado', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Contar cupons
     */
    public function count_all($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Estatísticas de cupons
     */
    public function get_estatisticas()
    {
        // Total de cupons
        $total_cupons = $this->db->count_all($this->table);
        
        // Cupons ativos
        $this->db->where('ativo', 1);
        $cupons_ativos = $this->db->count_all_results($this->table);
        
        // Cupons utilizados
        $this->db->where('total_usado >', 0);
        $cupons_utilizados = $this->db->count_all_results($this->table);
        
        // Total de desconto concedido
        $this->db->select_sum('valor_desconto');
        $this->db->where('cupom_id IS NOT NULL');
        $result = $this->db->get('pedidos')->row();
        $total_desconto = $result->valor_desconto ?? 0;
        
        return [
            'total_cupons' => $total_cupons,
            'cupons_ativos' => $cupons_ativos,
            'cupons_utilizados' => $cupons_utilizados,
            'total_desconto_concedido' => $total_desconto
        ];
    }
}