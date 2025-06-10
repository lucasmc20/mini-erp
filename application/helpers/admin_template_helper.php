<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Helper para facilitar o uso do template administrativo
 */

if (!function_exists('carregar_template_admin')) {
    /**
     * Carrega o template administrativo completo
     *
     * @param string $view Nome da view a ser carregada
     * @param array $data Dados a serem passados para a view
     * @param array $config Configurações do template
     */
    function carregar_template_admin($view, $data = [], $config = [])
    {
        $CI = &get_instance();

        // Configurações padrão
        $template_config = [
            'titulo' => 'Admin',
            'titulo_pagina' => '',
            'breadcrumb' => [],
            'css_adicional' => [],
            'js_adicional' => [],
            'js_inline' => '',
            'botao_acao' => null
        ];

        // Mescla configurações
        $template_config = array_merge($template_config, $config);

        // Mescla dados
        $template_data = array_merge($data, $template_config);

        // Carrega header
        $CI->load->view('templates/admin/header', $template_data);

        // Carrega conteúdo
        $CI->load->view($view, $data);

        // Carrega footer
        $CI->load->view('templates/admin/footer', $template_data);
    }
}

if (!function_exists('gerar_breadcrumb')) {
    /**
     * Gera array de breadcrumb
     *
     * @param array $itens Array com os itens do breadcrumb
     * @return array
     */
    function gerar_breadcrumb($itens = [])
    {
        $breadcrumb = [];

        foreach ($itens as $item) {
            if (is_string($item)) {
                $breadcrumb[] = ['titulo' => $item];
            } else {
                $breadcrumb[] = $item;
            }
        }

        return $breadcrumb;
    }
}

if (!function_exists('botao_acao')) {
    /**
     * Gera configuração para botão de ação
     *
     * @param string $texto Texto do botão
     * @param string $url URL do botão
     * @param string $icone Classe do ícone (opcional)
     * @return array
     */
    function botao_acao($texto, $url, $icone = null)
    {
        return [
            'texto' => $texto,
            'url' => $url,
            'icone' => $icone
        ];
    }
}

if (!function_exists('formatar_data_br')) {
    /**
     * Formata data para padrão brasileiro
     *
     * @param string $data Data no formato Y-m-d
     * @return string Data no formato d/m/Y
     */
    function formatar_data_br($data)
    {
        if (empty($data) || $data == '0000-00-00') {
            return '-';
        }

        return date('d/m/Y', strtotime($data));
    }
}

if (!function_exists('formatar_datetime_br')) {
    /**
     * Formata data e hora para padrão brasileiro
     *
     * @param string $datetime Data e hora no formato Y-m-d H:i:s
     * @return string Data e hora no formato d/m/Y H:i
     */
    function formatar_datetime_br($datetime)
    {
        if (empty($datetime) || $datetime == '0000-00-00 00:00:00') {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($datetime));
    }
}

if (!function_exists('formatar_moeda')) {
    /**
     * Formatar valor para moeda brasileira
     *
     * @param float $valor Valor a ser formatado
     * @return string Valor formatado
     */
    function formatar_moeda($valor)
    {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('status_badge')) {
    /**
     * Gera badge de status
     *
     * @param string $status Status
     * @param array $config Configurações personalizadas
     * @return string HTML do badge
     */
    function status_badge($status, $config = [])
    {
        $classes = [
            'ativo' => 'bg-success',
            'inativo' => 'bg-danger',
            'pendente' => 'bg-warning text-dark',
            'processando' => 'bg-info',
            'cancelado' => 'bg-secondary',
            'concluido' => 'bg-success',
            'em_andamento' => 'bg-primary'
        ];

        $textos = [
            'ativo' => 'Ativo',
            'inativo' => 'Inativo',
            'pendente' => 'Pendente',
            'processando' => 'Processando',
            'cancelado' => 'Cancelado',
            'concluido' => 'Concluído',
            'em_andamento' => 'Em Andamento'
        ];

        $classe = isset($classes[$status]) ? $classes[$status] : 'bg-secondary';
        $texto = isset($textos[$status]) ? $textos[$status] : ucfirst($status);

        if (isset($config['classe'])) {
            $classe = $config['classe'];
        }

        if (isset($config['texto'])) {
            $texto = $config['texto'];
        }

        return "<span class=\"badge {$classe}\">{$texto}</span>";
    }
}

if (!function_exists('confirmar_exclusao')) {
    /**
     * Gera atributo onclick para confirmação de exclusão
     *
     * @param string $mensagem Mensagem personalizada (opcional)
     * @return string
     */
    function confirmar_exclusao($mensagem = null)
    {
        if (!$mensagem) {
            $mensagem = 'Tem certeza que deseja excluir este item?';
        }

        return "onclick=\"return confirmarExclusao('{$mensagem}')\"";
    }
}

if (!function_exists('gerar_paginacao')) {
    /**
     * Gera HTML para paginação
     *
     * @param int $total_registros Total de registros
     * @param int $por_pagina Registros por página
     * @param int $pagina_atual Página atual
     * @param string $url_base URL base para paginação
     * @return string HTML da paginação
     */
    function gerar_paginacao($total_registros, $por_pagina, $pagina_atual, $url_base)
    {
        if ($total_registros <= $por_pagina) {
            return '';
        }

        $total_paginas = ceil($total_registros / $por_pagina);
        $html = '<nav aria-label="Paginação"><ul class="pagination justify-content-center">';

        // Botão anterior
        if ($pagina_atual > 1) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . ($pagina_atual - 1) . '">';
            $html .= '<i class="fas fa-chevron-left"></i></a></li>';
        }

        // Páginas
        $inicio = max(1, $pagina_atual - 2);
        $fim = min($total_paginas, $pagina_atual + 2);

        if ($inicio > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $url_base . '?pagina=1">1</a></li>';
            if ($inicio > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $inicio; $i <= $fim; $i++) {
            $ativo = ($i == $pagina_atual) ? ' active' : '';
            $html .= '<li class="page-item' . $ativo . '">';
            $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . $i . '">' . $i . '</a></li>';
        }

        if ($fim < $total_paginas) {
            if ($fim < $total_paginas - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link" href="' . $url_base . '?pagina=' . $total_paginas . '">' . $total_paginas . '</a></li>';
        }

        // Botão próximo
        if ($pagina_atual < $total_paginas) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . ($pagina_atual + 1) . '">';
            $html .= '<i class="fas fa-chevron-right"></i></a></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }
}

if (!function_exists('icone_acao')) {
    /**
     * Gera botão com ícone para ações
     *
     * @param string $url URL da ação
     * @param string $icone Classe do ícone
     * @param string $titulo Título do botão
     * @param string $classe Classes CSS adicionais
     * @param array $atributos Atributos HTML adicionais
     * @return string HTML do botão
     */
    function icone_acao($url, $icone, $titulo, $classe = 'btn-sm btn-outline-secondary', $atributos = [])
    {
        $attrs = '';
        foreach ($atributos as $attr => $valor) {
            $attrs .= " {$attr}=\"{$valor}\"";
        }

        return "<a href=\"{$url}\" class=\"btn {$classe}\" title=\"{$titulo}\" data-bs-toggle=\"tooltip\"{$attrs}>
                    <i class=\"{$icone}\"></i>
                </a>";
    }
}

if (!function_exists('tabela_vazia')) {
    /**
     * Gera HTML para tabela vazia
     *
     * @param string $mensagem Mensagem a ser exibida
     * @param int $colunas Número de colunas da tabela
     * @return string HTML
     */
    function tabela_vazia($mensagem = 'Nenhum registro encontrado', $colunas = 5)
    {
        return "<tr><td colspan=\"{$colunas}\" class=\"text-center text-muted py-4\">
                    <i class=\"fas fa-inbox fa-2x mb-2\"></i><br>
                    {$mensagem}
                </td></tr>";
    }
}