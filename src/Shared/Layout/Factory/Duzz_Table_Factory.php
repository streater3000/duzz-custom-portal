<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Shared\Actions as ActionsNamespace;
use Duzz\Shared\Layout\HTML\Duzz_Script;

class Duzz_Table_Factory {
    public $addRowClickHandler = true;
    public $columns = array();
    public $css = '';
    public $js = '';
    private $data = array(); 
    private $rowClasses = array();
    private $post_url = '';
    private $post_type;
    private $include_fields;

    // Update the constructor
    public function __construct($post_type = null, $include_fields = null, $data_title = null){
        $this->post_type = $post_type;
        $this->include_fields = $include_fields;
        $this->data_title = $data_title;
    }

public function duzz_addColumn($name, $options) {
    $column = array(
        'name' => $name,
        'options' => array_merge($options, array('name' => $name))
    );
    array_push($this->columns, $column);

}


    public function duzz_addRowClickHandlerScript() {
        $this->addRowClickHandler = apply_filters('duzz_add_row_click_handler', true);

        if ($this->addRowClickHandler) {
            $js = "
        document.addEventListener('DOMContentLoaded', function() {
            var rows = document.querySelectorAll('tr[data-href]');
            Array.prototype.slice.call(rows).forEach(function(row) {
                row.addEventListener('click', function() {
                    window.location.href = row.getAttribute('data-href');
                });
            });
        });
    ";

            $script = new Duzz_Script($js);
            $script->duzz_render();
        }
    }

    public function duzz_render($data, $rowClasses, $post_url) {
        // Set local properties based on passed parameters
        $this->data = $data;
        $this->rowClasses = $rowClasses;
        $this->post_url = $post_url;
        
        do_action('duzz_table_factory_before_render_scripts', $this);
        $this->duzz_addRowClickHandlerScript();
        add_action('table_factory_add_row_click_handler', array($this, 'duzz_addRowClickHandlerScript'));

        $class = '';
        $optionsName = $this->columns[0]['options']['name'];
        $class_constructor = str_replace('_', '-', $optionsName);

        if (!empty($this->columns)) {
            $class = 'custom-selected-table';
        }
        $tr_class = 'custom-selected-tr';


       if ($this->include_fields === true){

        // Create the form first
        $form = new Duzz_Return_HTML('form', array(
            'action' => $this->post_url,
            'class' => 'custom-post-add', 
            'method' => 'POST'
        ));

        // Add nonce fields to the form
        $form->duzz_addChild('input', array(
            'type' => 'hidden',
            'name' => 'action',
            'value' => 'custom_post_add',
        ));
        
        $form->duzz_addChild('input', array(
            'type' => 'hidden',
            'name' => '_wpnonce',
            'value' => wp_create_nonce('custom_post_add_nonce'),
        ));

        $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'post_type',
        'value' => $this->post_type,
        ));

        $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'data_title',
        'value' => $this->data_title,
        ));

        $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'company_id',
        'value' => '9909',
        ));


        $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'team_id',
        'value' => '9908',
        ));

        $staff_id = get_current_user_id();
        $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'staff_id',
        'value' => $staff_id,
        ));

        // Add the table to the form
        $table = $form->duzz_addChild('table', array('class' => $class));

        // Create a row for headers
        $header_row = $table->duzz_addChild('tr', array('class' => $tr_class));
        $header_row->duzz_addChild('th', ['class' => 'view-mobile-green']);

        foreach ($this->columns as $column) {
             $column_name = esc_attr($column['name']);
                
                $formatted_label = ActionsNamespace\Duzz_Format_Label::duzz_format($column_name);
                $column_class = esc_attr(str_replace('_', '-', $column_name));         

                if (isset($column['options']['locked']) && $column['options']['locked']) {
                    // Add submit button in place of column name for locked columns in the header
                    $submitButton = new Duzz_Return_HTML('input', array(
                        'type' => 'submit',
                        'class' => 'submit-button-class',
                        'value' => 'Add'
                    ), 'Add');
                    $header_row->duzz_addChild('th', array('class' => $column_class), $submitButton);
                } else {
                    // Create an input field inside other <th>
                    $input = new Duzz_Return_HTML('input', array(
                        'id' => $column_class,
                        'name' => $column_name,
                        'type' => 'text',
                        'placeholder' => $formatted_label,  
                        'class' => 'custom-post-field-class',
                        'data-duzz-required' => ''   
                    ));
                    $header_row->duzz_addChild('th', array('class' => $column_class), $input);
    }
}

        } elseif ($this->include_fields === false) {

            $table = new Duzz_Return_HTML('table', array('class' => $class));

            $header_row = $table->duzz_addChild('tr', array('class' => $tr_class));
            $header_row->duzz_addChild('th', ['class' => 'view-mobile-white']);
            foreach ($this->columns as $column) {
                $column_class = esc_attr($column['name']);
                $format_title = ActionsNamespace\Duzz_Format_Label::duzz_format($column['name']);
                $column_title = esc_html($format_title);
                $header_row->duzz_addChild('th', array('class' => $column_class), $column_title);
            }
        }

        // Create rows for data
        foreach ($this->data as $i => $row) {
    $href = isset($row['id']) && $this->post_url ? 'data-href="' . $this->post_url . $row['id'] . '"' : '';
    $attributes = array('class' => 'duzz-project-list-row');
    if ($href) {
        $attributes['data-href'] = ($this->post_url . $row['id'] . '/');
    }
    $data_row = $table->duzz_addChild('tr', $attributes);
    $data_row->duzz_addChild('td', ['class' => 'view-mobile-green']);
            
            foreach ($this->columns as $index => $column) {
                $value = isset($row[$column['options']['key']]) ? $row[$column['options']['key']] : '';
                $classes = isset($column['options']['classes']) ? $column['options']['classes'] : '';
                if (isset($column['options']['locked']) && $column['options']['locked']) {
                    $classes .= ' locked-column';
                    if (isset($this->rowClasses[$i])) {
                        $classes .= ' ' . $this->rowClasses[$i];
                    }
                }
                $label = ActionsNamespace\Duzz_Format_Label::duzz_format($column['name']);
                $innerHtml = '<span class="label-customer-mobile">' . $label . ':</span><span class="data-customer-mobile"> ' . $value . '</span>';
                $data_row->duzz_addChild('td', array('class' => $classes), $innerHtml);
            }
        }

        // Render the form which will include the table inside it
               if ($this->include_fields === true){
        $form_html = $form->duzz_render();
    } else {
        $form_html = $table->duzz_render();
    }
        $form_html .= $this->css;
        $form_html .= $this->js;

        // Make sure to return the generated HTML of the form
        return $form_html;
    }

}
