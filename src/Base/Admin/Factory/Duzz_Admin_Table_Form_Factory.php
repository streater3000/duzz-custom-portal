<?php

namespace Duzz\Base\Admin\Factory;

use Duzz\Shared\Layout\HTML as HTMLNamespace;

class Duzz_Admin_Table_Form_Factory
{
    protected $rows = [];
    protected $headerColumns = [];
    protected $tableFactory;
    protected $section_name;

    public function __construct($fieldType, $section_name = '')
    {
        $tableClass = ($fieldType === 'table') ? 'widefat' : 'form-table';
        $this->tableFactory = new HTMLNamespace\Duzz_Table('table', ['class' => $tableClass]);
        $this->fieldType = $fieldType;
        $this->section_name = $section_name;
    }

    public function duzz_addHeaderColumn($column) {
        $this->headerColumns[] = $column;
    }

    public function duzz_addRow($columns) {
        // Check if the header columns have already been added
        $addHeaderColumns = empty($this->headerColumns);

        $this->rows[] = $columns;

        // Check the number of columns in the row and add header columns if necessary
        if (count($columns) === 3 && $addHeaderColumns) {
            $this->duzz_addHeaderColumn('Field Label');
            $this->duzz_addHeaderColumn('Field Name');
            $this->duzz_addHeaderColumn('Field Value');
        }
    }

    public function duzz_render() {
        $this->duzz_renderTable();
    }

protected function duzz_renderTable() {
    // Step 1: Create the outer container with the appropriate class.
    $divClass = $this->fieldType === 'table' ? 'table-container' : 'postbox';
    $divcontainer = new HTMLNamespace\Duzz_HTML('div', ['class' => $divClass]);

    // Continue with your existing logic to render header, body, etc. for the table
    $this->duzz_renderHeaderRow();
    $this->duzz_renderBody();
    if ($this->fieldType !== 'table') {
        $this->duzz_renderTitle();
    }

    // Create another inner div container
    $section_name_class = str_replace('_', '-', $this->section_name);
    $divClasstwo = $this->fieldType === 'table' ? 'table-no-border' : 'duzz-menu-border-bottom';
    $div = new HTMLNamespace\Duzz_HTML('div', ['class' => $divClasstwo . ' ' . $section_name_class]);
    
    // Step 2: Add the table to the inner div container.
    $div->duzz_addChild($this->tableFactory);
    
    // Add the inner div to the main container.
    $divcontainer->duzz_addChild($div);

    // Step 3: Render the entire container.
    $divcontainer->duzz_render();
}



protected function duzz_renderTitle() {
    $section_name = ucwords(str_replace('_', ' ', $this->section_name));
    $title_attributes = ['class' => 'section-title'];
    $section_title = new HTMLNamespace\Duzz_HTML('h2', $title_attributes);
    $section_title->duzz_addChild($section_name);  // Use addChild instead of setContent

    $div = new HTMLNamespace\Duzz_HTML('div', ['class' => 'title-container']);
    $div->duzz_addChild($section_title);
    
    $this->tableFactory->duzz_addChild($div);
}

    protected function duzz_renderHeaderRow() {
        if (!empty($this->headerColumns)) {
            $thead = new HTMLNamespace\Duzz_Table('thead');
            $tr = new HTMLNamespace\Duzz_Table('tr');

            foreach ($this->headerColumns as $column) {
                $th = new HTMLNamespace\Duzz_Table('th');
                $th->duzz_addChild($column);
                $tr->duzz_addChild($th);
            }

            $thead->duzz_addChild($tr);
            $this->tableFactory->duzz_addChild($thead);
        }
    }

protected function duzz_renderBody() {
    $tbody = new HTMLNamespace\Duzz_Table('tbody');

    foreach ($this->rows as $columns) {
        $tr = new HTMLNamespace\Duzz_Table('tr');

        foreach ($columns as $column) {
            $td = new HTMLNamespace\Duzz_Table('td');

    if (is_object($column) && method_exists($column, 'duzz_render')) {
        $columnContent = $column->duzz_render();
        $td->duzz_setContent($columnContent);
        } else {
                $td->duzz_addChild($column);
                $tr->duzz_addChild($td);
            }
        }

        $tbody->duzz_addChild($tr);
    }

    $this->tableFactory->duzz_addChild($tbody);
}

}
