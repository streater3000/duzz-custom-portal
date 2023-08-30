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

    public function addHeaderColumn($column) {
        $this->headerColumns[] = $column;
    }

    public function addRow($columns) {
        // Check if the header columns have already been added
        $addHeaderColumns = empty($this->headerColumns);

        $this->rows[] = $columns;

        // Check the number of columns in the row and add header columns if necessary
        if (count($columns) === 3 && $addHeaderColumns) {
            $this->addHeaderColumn('Field Label');
            $this->addHeaderColumn('Field Name');
            $this->addHeaderColumn('Field Value');
        }
    }

    public function render() {
        $this->renderTable();
    }

protected function renderTable() {
    // Step 1: Create the outer container with the appropriate class.
    $divClass = $this->fieldType === 'table' ? 'table-container' : 'postbox';
    $divcontainer = new HTMLNamespace\Duzz_HTML('div', ['class' => $divClass]);

    // Continue with your existing logic to render header, body, etc. for the table
    $this->renderHeaderRow();
    $this->renderBody();
    if ($this->fieldType !== 'table') {
        $this->renderTitle();
    }

    // Create another inner div container
    $section_name_class = str_replace('_', '-', $this->section_name);
    $divClasstwo = $this->fieldType === 'table' ? 'table-no-border' : 'duzz-menu-border-bottom';
    $div = new HTMLNamespace\Duzz_HTML('div', ['class' => $divClasstwo . ' ' . $section_name_class]);
    
    // Step 2: Add the table to the inner div container.
    $div->addChild($this->tableFactory);
    
    // Add the inner div to the main container.
    $divcontainer->addChild($div);

    // Step 3: Render the entire container.
    $divcontainer->render();
}



protected function renderTitle() {
    $section_name = ucwords(str_replace('_', ' ', $this->section_name));
    $title_attributes = ['class' => 'section-title'];
    $section_title = new HTMLNamespace\Duzz_HTML('h2', $title_attributes);
    $section_title->addChild($section_name);  // Use addChild instead of setContent

    $div = new HTMLNamespace\Duzz_HTML('div', ['class' => 'title-container']);
    $div->addChild($section_title);
    
    $this->tableFactory->addChild($div);
}

    protected function renderHeaderRow() {
        if (!empty($this->headerColumns)) {
            $thead = new HTMLNamespace\Duzz_Table('thead');
            $tr = new HTMLNamespace\Duzz_Table('tr');

            foreach ($this->headerColumns as $column) {
                $th = new HTMLNamespace\Duzz_Table('th');
                $th->addChild($column);
                $tr->addChild($th);
            }

            $thead->addChild($tr);
            $this->tableFactory->addChild($thead);
        }
    }

protected function renderBody() {
    $tbody = new HTMLNamespace\Duzz_Table('tbody');

    foreach ($this->rows as $columns) {
        $tr = new HTMLNamespace\Duzz_Table('tr');

        foreach ($columns as $column) {
            $td = new HTMLNamespace\Duzz_Table('td');

    if (is_object($column) && method_exists($column, 'render')) {
        $columnContent = $column->render();
        $td->setContent($columnContent);
        } else {
                $td->addChild($column);
                $tr->addChild($td);
            }
        }

        $tbody->addChild($tr);
    }

    $this->tableFactory->addChild($tbody);
}

}
