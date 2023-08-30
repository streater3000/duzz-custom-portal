<?php

namespace Duzz\Base\Menu;


class Duzz_Menu_Edit extends \Walker_Nav_Menu_Edit{
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){
        $item_output = '';
        parent::start_el($item_output, $item, $depth, $args, $id);

        $visibility = get_post_meta( $item->ID, '_menu_item_visibility', true );
        if ( !$visibility ) $visibility = 'all';

        $new_fields = sprintf(
            '<p class="field-visibility description description-wide">
                <label for="edit-menu-item-visibility-%s">%s</label>
                <input type="radio" name="menu-item-visibility[%s]" value="all" %s /> %s
                <input type="radio" name="menu-item-visibility[%s]" value="logged_in" %s /> %s
                <input type="radio" name="menu-item-visibility[%s]" value="logged_out" %s /> %s
            </p>',
            esc_attr( $item->ID ),
            esc_html__( 'Visibility', 'menu-item-visibility' ),
            esc_attr( $item->ID ),
            checked( $visibility, 'all', false ),
            esc_html__( 'All Users', 'menu-item-visibility' ),
            esc_attr( $item->ID ),
            checked( $visibility, 'logged_in', false ),
            esc_html__( 'Logged In Users', 'menu-item-visibility' ),
            esc_attr( $item->ID ),
            checked( $visibility, 'logged_out', false ),
            esc_html__( 'Logged Out Users', 'menu-item-visibility' )
        );

        $output .= preg_replace( '/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output );
    }
}
