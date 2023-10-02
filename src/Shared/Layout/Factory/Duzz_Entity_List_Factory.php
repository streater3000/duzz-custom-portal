<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Layout\Factory as FactoryNamespace;
use Duzz\Shared\Actions as ActionsNamespace;
use Duzz\Core as CoreHelpers;
use \WP_Query;


class Duzz_Entity_List_Factory {

    private $list_args;
    private $post_id;
    private $user_id;
    private $list_name;
    private $items_per_page;
    private $feed_textarea;
    private $post_type;
    private $options_name;

    public function __construct($list_args, $post_id, $user_id, $items_per_page) {
        $this->list_args = $list_args;
        $this->post_id = $post_id;
        $this->user_id = $user_id;
        $this->items_per_page = $items_per_page;
        $this->post_type = $this->list_args['post_type'];
        $this->meta_query = $this->list_args['meta_query'];
        $this->list_name = $this->list_args['list_name'];
        $this->options_name = $this->list_args['options_name'];
        $this->field_name = $this->list_args['field_name'];
        $this->include_fields = $this->list_args['include_fields'];
        $this->container = new Duzz_Return_HTML('div', array('class' => 'post-table-list-container'));
        $this->call_list();

    }

    public function render() {
        return $this->container;
    }


    function call_list() {
        $this->format_table = new Duzz_Return_HTML('div', ['class' => 'format-list-table', 'id' => 'format_list_table']);
        $this->call_list = new Duzz_Return_HTML('div', ['class' => 'call-list-table', 'id' => 'call_list_table']);
        
        $args = $this->get_args();
        $this->post_list = $this->list_posts_table($args);

        $this->call_list->addChild($this->post_list);
        $this->format_table->addChild($this->call_list);

        $this->pagination = $this->add_pagination($args);
        $this->format_table->addChild($this->pagination);

        $this->container->addChild($this->format_table);
    }

    function add_pagination( $args = array() ){
        $this->add_pagination = new Duzz_Return_HTML('div', ['class' => 'add-pagination', 'id' => 'add_pagination']);
        $this->pagination_factory = new FactoryNamespace\Duzz_Pagination_Factory();
        $this->pagination = $this->pagination_factory->create_pagination($args, $this->items_per_page, $this->current);
        $this->add_pagination->addChild($this->pagination);
        $this->format_table->addChild($this->add_pagination);
    }


function list_posts_table( $args = array() ) {


$field_data = get_option($this->options_name, array());


// Retrieving field names separately
$regular_field_name = $this->field_name;
$data_title_field_name = $this->field_name . '_data_title';

$regular_field_data = isset($field_data[$regular_field_name]) ? $field_data[$regular_field_name] : array();

$data_title_field_data = isset($field_data[$data_title_field_name]) ? $field_data[$data_title_field_name] : array();

// Now merge these two arrays
$selected_columns = array_merge($regular_field_data, $data_title_field_data);

// Add the locked column
$lockedColumns = array(
    'status_update' => 'Auto Alert'
);
$all_columns = array_merge($selected_columns, $lockedColumns);


    $user = wp_get_current_user();
    $post_url = site_url( '/'.$this->post_type.'/' );

    $posts = get_posts( $args );
    $data = array();
    $rowClasses = array(); // Initialize rowClasses array

    $postUpdateInstance = new ActionsNamespace\Duzz_Project_Update(); // Create projectUpdate instance

    foreach ( $posts as $post ) {
        $post_id = $post->ID;


        $post_data = array();

        $post_data['id'] = $post_id; // add the post ID to the data array


        list($new_post, $new_post_title) = $postUpdateInstance->updateProject($post_id);

        foreach ( $all_columns as $column_name => $field_key ) {
            if ($column_name === 'status_update') {
                $post_data[$column_name] = $new_post_title;
            } else {
             $helpers = new CoreHelpers\Duzz_Helpers();

            $post_data[$column_name] = $helpers->duzz_get_field( $field_key, $post_id);
            }
        }

        $data[] = $post_data;
        $rowClasses[] = $new_post; // Add the $new_project classes to the rowClasses array
    }

        $fields_to_save = implode(", ", $regular_field_data);
        $data_title_string = isset($data_title_field_data[0]) ? $data_title_field_data[0] : "";
        $table = new Duzz_Table_Factory($this->post_type, $this->include_fields, $data_title_string, $fields_to_save);



    foreach ($all_columns as $column_name => $field_key) {
        $isLocked = isset($lockedColumns[$column_name]);
        if ($column_name === 'status_update') {
            $field_key = $column_name;
        }

        $table->addColumn($field_key, array('key' => $column_name, 'locked' => $isLocked));
    }

    if (empty($data)) {
        $empty_data = array();
        foreach ($all_columns as $column_name => $field_key) {
            $empty_data[$column_name] = '';
        }
        $data[] = $empty_data;
    }

    // Render the table with data, row classes, and project URL
    $table_html = $table->render($data, $rowClasses, $post_url);

       $table_div = new Duzz_Return_HTML('div', ['class' => 'entity-table-container', 'id' => 'entity-table-container']);
        $table_div->addChild('div', ['class' => 'entity-table-html'], $table_html);
        return $table_div;
}


public function get_args() {


$this->current = max(1, get_query_var('paged'));
$this->paged = get_query_var('paged') ? get_query_var('paged') : 1;

    // Create the $list_args array
    $list_args = array(
        'post_type' => $this->post_type,
        'post_id' => !empty($this->post_id) ? $this->post_id : null,
        'offset' => ($this->paged - 1) * $this->items_per_page,
        'posts_per_page' => $this->items_per_page,
    );



    // Add the meta_query from list_args
    $list_args['meta_query'] = $this->meta_query;

    $the_query = new WP_Query($list_args);

    $list_args['total'] = $the_query->found_posts;

    return $list_args;
}

}