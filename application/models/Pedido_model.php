<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_model extends CI_Model
{
    protected $table = 'pedidos';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buscar todos os pedidos
     */
    public function get_all($limit = null, $offset = null, $where = [])
    {
        $this->db->select('p.*, u.nome as usuario_nome, u.email as usuario_email');
        $this->db->from($this->table . ' p');
        $this->db->join('usuarios u', 'u.id = p.usuario_id', 'left');

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('p.data_pedido', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Buscar pedido por ID
     */
    public function get_by_id($id)
    {
        $this->db->select('p.*, u.nome as usuario_nome, u.email as usuario_email, c.codigo as cupom_codigo');
        $this->db->from($this->table . ' p');
        $this->db->join('usuarios u', 'u.id = p.usuario_id', 'left');
        $this->db->join('cupons c', 'c.id = p.cupom_id', 'left');
        $this->db->where('p.id', $id);

        return $this->db->get()->row();
    }

    /**
     * Buscar pedido por número
     */
    public function get_by_numero($numero_pedido)
    {
        $this->db->select('p.*, u.nome as usuario_nome, u.email as usuario_email');
        $this->db->from($this->table . ' p');
        $this->db->join('usuarios u', 'u.id = p.usuario_id', 'left');
        $this->db->where('p.numero_pedido', $numero_pedido);

        return $this->db->get()->row();
    }

    /**
     * Buscar pedidos por usuário
     */
    public function get_by_usuario($usuario_id, $limit = null, $offset = null)
    {
        $where = ['p.usuario_id' => $usuario_id];
        return $this->get_all($limit, $offset, $where);
    }

    /**
     * Buscar pedidos por status
     */
    public function get_by_status($status, $limit = null, $offset = null)
    {
        $where = ['p.status' => $status];
        return $this->get_all($limit, $offset, $where);
    }

    /**
     * Inserir novo pedido
     */
    public function insert($data)
    {
        if (empty($data['cupom_id'])) {
            $data['cupom_id'] = null;
        }

        $data['data_pedido'] = date('Y-m-d H:i:s');
        $data['numero_pedido'] = $this->generate_numero_pedido();

        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Atualizar pedido
     */
    public function update($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Atualizar status do pedido
     */
    public function update_status($id, $status, $observacoes = null)
    {
        $data = [
            'status' => $status,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ];

        if ($observacoes) {
            $data['observacoes_internas'] = $observacoes;
        }

        // Atualizar campos de data específicos baseado no status
        switch ($status) {
            case 'confirmado':
                $data['data_confirmacao'] = date('Y-m-d H:i:s');
                break;
            case 'enviado':
                $data['data_envio'] = date('Y-m-d H:i:s');
                break;
            case 'entregue':
                $data['data_entrega'] = date('Y-m-d H:i:s');
                break;
            case 'cancelado':
                $data['data_cancelamento'] = date('Y-m-d H:i:s');
                break;
        }

        return $this->update($id, $data);
    }

    /**
     * Atualizar status do pagamento
     */
    public function update_status_pagamento($id, $status_pagamento)
    {
        $data = [
            'status_pagamento' => $status_pagamento,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ];

        return $this->update($id, $data);
    }

    /**
     * Gerar número do pedido único
     */
    private function generate_numero_pedido()
    {
        do {
            $numero = date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while ($this->numero_exists($numero));

        return $numero;
    }

    /**
     * Verificar se número do pedido já existe
     */
    private function numero_exists($numero)
    {
        return $this->db->get_where($this->table, ['numero_pedido' => $numero])->num_rows() > 0;
    }

    /**
     * Deletar pedido
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Contar pedidos
     */
    public function count_all($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Calcular totais do pedido
     */
    public function calculate_totals($pedido_id, $cupom_id = null)
    {
        // Buscar subtotal dos itens
        $this->db->select_sum('preco_total');
        $this->db->where('pedido_id', $pedido_id);
        $result = $this->db->get('pedido_itens')->row();
        $subtotal = $result->preco_total ?? 0;

        $valor_desconto = 0;

        // Aplicar desconto do cupom se houver
        if ($cupom_id) {
            $this->load->model('Cupom_model');
            $cupom = $this->Cupom_model->get_by_id($cupom_id);

            if ($cupom && $this->Cupom_model->validate_cupom($cupom_id, $subtotal)) {
                if ($cupom->tipo_desconto == 'percentual') {
                    $valor_desconto = ($subtotal * $cupom->valor_desconto) / 100;

                    // Verificar limite máximo de desconto
                    if ($cupom->valor_maximo_desconto && $valor_desconto > $cupom->valor_maximo_desconto) {
                        $valor_desconto = $cupom->valor_maximo_desconto;
                    }
                } else {
                    $valor_desconto = $cupom->valor_desconto;
                }
            }
        }

        return [
            'subtotal' => $subtotal,
            'valor_desconto' => $valor_desconto,
            'total' => $subtotal - $valor_desconto
        ];
    }

    /**
     * Relatórios - Vendas por período
     */
    public function get_vendas_periodo($data_inicio, $data_fim)
    {
        $this->db->select('DATE(data_pedido) as data, COUNT(*) as total_pedidos, SUM(total) as total_vendas');
        $this->db->where('data_pedido >=', $data_inicio);
        $this->db->where('data_pedido <=', $data_fim);
        $this->db->where_in('status', ['confirmado', 'processando', 'enviado', 'entregue']);
        $this->db->group_by('DATE(data_pedido)');
        $this->db->order_by('data_pedido', 'ASC');

        return $this->db->get($this->table)->result();
    }

    /**
     * Relatórios - Pedidos por status
     */
    public function get_estatisticas_status()
    {
        $this->db->select('status, COUNT(*) as total');
        $this->db->group_by('status');
        $this->db->order_by('total', 'DESC');

        return $this->db->get($this->table)->result();
    }

    /**
     * Buscar pedidos que precisam de atenção
     */
    public function get_pendentes_atencao()
    {
        $this->db->where_in('status', ['pendente', 'confirmado']);
        $this->db->where('data_pedido <', date('Y-m-d H:i:s', strtotime('-24 hours')));
        $this->db->order_by('data_pedido', 'ASC');

        return $this->db->get($this->table)->result();
    }
}
