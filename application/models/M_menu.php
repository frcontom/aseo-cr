<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_menu extends CI_Model
{

    function generate($id = '')
    {
        // Estructura de salida
        $menus = [];

        // Crear índice temporal para organizar por ID
        $menu_by_id = [];

        // Consulta con orden por m_order
        $result = $this->general->query("SELECT * FROM (
            SELECT DISTINCT m.*
            FROM menu AS m
            LEFT JOIN permissions AS p ON m.m_id = p.p_menu
            WHERE p.p_profile = '".$id."' OR m.m_id IN (
                SELECT m_parent
                FROM menu
                WHERE m_id IN (
                    SELECT p_menu
                    FROM permissions
                    WHERE p_profile = '".$id."'
                )
            )
        ) AS subquery
        ORDER BY subquery.m_order ASC;");

        // Procesar el resultado y construir el índice por ID
        foreach ($result as $row) {
            $row['submenu'] = []; // Inicializar submenús como array vacío
            $menu_by_id[$row['m_id']] = $row;
        }

        // Construir estructura jerárquica respetando el orden
        foreach ($menu_by_id as $id => $menu) {
            if ($menu['m_parent'] == 0) {
                // Menús principales
                $menus[] = &$menu_by_id[$id];
            } elseif (!empty($menu['m_parent']) && isset($menu_by_id[$menu['m_parent']])) {
                // Submenús: Añadir al padre correspondiente
                $menu_by_id[$menu['m_parent']]['submenu'][] = &$menu_by_id[$id];
            }
        }

        // Ordenar submenús según m_order dentro de cada menú principal
        foreach ($menus as &$menu) {
            if (!empty($menu['submenu'])) {
                usort($menu['submenu'], function ($a, $b) {
                    return $a['m_order'] <=> $b['m_order'];
                });
            }
        }

        return $this->generate_html($menus);
    }

    function generate_html($menus = [])
    {
        $html = '';

        foreach ($menus as $me) {
            $title = $me['m_name'];
            $url = ($me['m_href'] != '') ? base_url($me['m_href']) : '';
            $icono = ($me['m_icon'] != '') ? $me['m_icon'] : '';

            // Validar si es un menú o link normal
            if ($me['m_type'] == 0 || (isset($me['submenu']) && count($me['submenu']) > 0)) {
                // Menú con submenú
                $html .= "<li><a class='has-arrow ai-icon' href='javascript:void(0);' aria-expanded='false'>";
                $html .= "<i class='flaticon-381-networking'></i>";
                $html .= "<span class='nav-text'>{$title}</span>";
                $html .= "</a>";
                $html .= "<ul aria-expanded='false'>";

                if (isset($me['submenu'])) {
                    foreach ($me['submenu'] as $sub) {
                        $titles = $sub['m_name'];
                        $urls = ($sub['m_href'] != '') ? base_url($sub['m_href']) : '';
                        $html .= "<li><a href='{$urls}'>{$titles}</a></li>";
                    }
                }

                $html .= "</ul></li>";
            } else {
                // Menú simple
                $html .= "<li><a href='{$url}' class='ai-icon' aria-expanded='false'>
                            <i class='flaticon-381-settings-2'></i>
                            <span class='nav-text'>{$title}</span>
                        </a></li>";
            }
        }

        return $html;
    }
}