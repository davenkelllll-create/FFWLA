<?php
/**
 * Bootstrap 5 Nav Walker
 * Wandelt WordPress-Menüs in Bootstrap-5-kompatibles HTML um.
 */
class FW_Bootstrap_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null): void {
        $output .= '<ul class="dropdown-menu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null): void {
        $output .= '</ul>';
    }

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $id = 0): void {
        $item     = $data_object;
        $indent   = str_repeat("\t", $depth);
        $classes  = empty($item->classes) ? [] : (array)$item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $is_active    = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes);

        if ($depth === 0) {
            $li_class = 'nav-item' . ($has_children ? ' dropdown' : '');
            $output  .= $indent . '<li class="' . esc_attr($li_class) . '">';

            if ($has_children) {
                $link_class = 'nav-link dropdown-toggle' . ($is_active ? ' active' : '');
                $output .= '<a class="' . esc_attr($link_class) . '" href="#"'
                    . ($is_active ? ' aria-current="page"' : '')
                    . ' role="button" data-bs-toggle="dropdown" aria-expanded="false">'
                    . esc_html($item->title) . '</a>';
            } else {
                $link_class = 'nav-link' . ($is_active ? ' active' : '');
                $output .= '<a class="' . esc_attr($link_class) . '" href="' . esc_url($item->url) . '"'
                    . ($is_active ? ' aria-current="page"' : '') . '>'
                    . esc_html($item->title) . '</a>';
            }
        } else {
            $item_class = 'dropdown-item' . ($is_active ? ' active' : '');
            $output .= $indent . '<li><a class="' . esc_attr($item_class) . '" href="' . esc_url($item->url) . '">'
                . esc_html($item->title) . '</a></li>';
        }
    }

    public function end_el(&$output, $data_object, $depth = 0, $args = null): void {
        if ($depth === 0) $output .= '</li>';
    }
}
