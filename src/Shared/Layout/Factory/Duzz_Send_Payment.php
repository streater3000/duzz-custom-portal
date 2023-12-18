<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Actions\Duzz_Project_Number;

class Duzz_Send_Payment{
    public $payment_id;
    public $payment_data;
    public $payment_data_sales_tax;
    public $payment_data_invoice_name;
    public $payment_data_invoice_type;

    private $item;
    private $units;
    private $unit_type;
    private $price;
    private $rowID;

   public function __construct($item = '', $units = '', $unit_type = '', $price = '', $rowID = '')
    {
        $this->item = $item;
        $this->units = $units;
        $this->unit_type = $unit_type;
        $this->price = $price;
        $this->rowID = $rowID; 
        $this->project_id = absint(get_query_var('project_id', ''));
        $this->isCompleted = $this->payment_id ? $this->duzz_isPaymentCompleted($this->payment_id) : false;
        $this->readonlyAttr = $this->isCompleted ? ['readonly' => 'readonly'] : [];
        $this->duzz_get_data_for_table();
    }

    private function duzz_isPaymentCompleted($payment_id) {
        $payment_status = get_post_meta($payment_id, 'payment_confirm', true);
        return $payment_status === 'Completed';
    }

function duzz_addRow($table, $item, $units, $unitType, $price, $index = '', $isRemoveButton = false, $readonly = false) {

    $this->readonlyAttr = $readonly ? ['readonly' => 'readonly'] : [];

    $dataRow = $table->duzz_addChild('tr', ['class' => 'invoice-table-line-items-row']);

    $dataRow->duzz_addChild('td')->duzz_addChild('input', array_merge(['type' => 'text', 'name' => 'item[' . $index . ']', 'value' => $item, 'placeholder' => 'Item'], $this->readonlyAttr));
    $dataRow->duzz_addChild('td', ['class' => 'invoice-units-width'])->duzz_addChild('input', array_merge(['type' => 'number', 'name' => 'units[' . $index . ']', 'value' => $units, 'placeholder' => 'Units'], $this->readonlyAttr));

    $dropdownDisabledAttr = $readonly ? ['disabled' => 'disabled'] : [];

    $unitTypeSelect = $dataRow->duzz_addChild('td')->duzz_addChild('select', array_merge(['name' => 'unit_type[' . $index . ']'], $dropdownDisabledAttr));

    $hourAttributes = array('value' => 'hour');
        if ($unitType === 'hour') {
            $hourAttributes['selected'] = 'selected';
            }
    $unitTypeSelect->duzz_addChild('option', $hourAttributes, 'Hours');

    $unitAttributes = array('value' => 'unit');
        if ($unitType === 'unit') {
            $unitAttributes['selected'] = 'selected';
            }
        $unitTypeSelect->duzz_addChild('option', $unitAttributes, 'Quantity');


    $priceWrapper = $dataRow->duzz_addChild('td', ['class' => 'invoice-price-width'])->duzz_addChild('div', ['class' => 'input-wrapper']);
    $priceWrapper->duzz_addChild('span', ['class' => 'prepend-icon'], '$');
    $priceWrapper->duzz_addChild('input', array_merge(['type' => 'number', 'name' => 'price[' . $index . ']', 'class' => 'price-input', 'value' => $price, 'placeholder' => 'Price', 'step' => '0.01'], $this->readonlyAttr));

    if ($isRemoveButton) {
        $button = $dataRow->duzz_addChild('td')->duzz_addChild('button', ['type' => 'button', 'class' => 'add-line-item-button remove-line-item-button', 'onclick' => 'removeRow(this)']);
        $button->duzz_addChild('span', ['class' => 'dashicons dashicons-trash'], '');
    } else {
        $button = $dataRow->duzz_addChild('td')->duzz_addChild('button', ['type' => 'button', 'class' => 'add-line-item-button', 'onclick' => 'addRow(this)']);
        $button->duzz_addChild('span', ['class' => 'dashicons dashicons-plus-alt'], '');
    }
}

public function define_data_without_project_id(
    $payment_id = '',
    $payment_data = [],
    $payment_data_sales_tax = 0,
    $payment_data_invoice_name = '',
    $payment_data_invoice_type = ''
) {
    $this->payment_id = $payment_id;
    $this->payment_data = $payment_data;
    $this->payment_data_sales_tax = $payment_data_sales_tax;
    $this->payment_data_invoice_name = $payment_data_invoice_name;
    $this->payment_data_invoice_type = $payment_data_invoice_type;
}


    public function duzz_get_data_for_table() {
        
        $this->payment_id = null;

        if ($this->project_id) {
            // Get payment id that equals project_id
            $this->payment_id = get_posts([
                'post_type' => 'payment',
                'meta_key' => 'project_id',
                'meta_value' => $this->project_id,
                'posts_per_page' => 1,
                'fields' => 'ids',
            ]);

            // If $payment_id doesn't exist for the given $project_id, generate a new one.
            if (!$this->payment_id) {
                $this->payment_id = Duzz_Project_Number::duzz_generate();
            } else {
                $this->payment_id = $this->payment_id[0];
                $this->payment_data = get_post_meta($this->payment_id, 'line_items', true);
                $this->payment_data_sales_tax = get_post_meta($this->payment_id, 'sales_tax', true);
                $this->payment_data_invoice_name = get_post_meta($this->payment_id, 'invoice_name', true);
                $this->payment_data_invoice_type = get_post_meta($this->payment_id, 'invoice_type', true);
            }
        } else {
             $this->define_data_without_project_id();
        }
    }

public function duzz_create_form($form) {
    // Create the form with attributes
    $form = new HTMLNamespace\Duzz_Return_HTML('form', [
        'action' => admin_url('admin-post.php'),
        'class' => 'payment-form', 
        'id' => 'invoice', 
        'method' => 'POST'
    ]);

    // Hidden field for the action
    $form->duzz_addChild('input', [
        'type' => 'hidden',
        'name' => 'action',
        'value' => 'send_invoice',
    ]);

    // Hidden field for the nonce
    $form->duzz_addChild('input', [
        'type' => 'hidden',
        'name' => '_wpnonce',
        'value' => wp_create_nonce('send_invoice_nonce'),
    ]);

    // Hidden field for the project_id
    $form->duzz_addChild('input', [
        'type' => 'hidden',
        'name' => 'project_id',
        'value' => $this->project_id,
    ]);

    // Hidden field for the payment_id
    $form->duzz_addChild('input', [
        'type' => 'hidden',
        'name' => 'payment_id',
        'value' => $this->payment_id,
    ]);

    return $form;
}


public function duzz_create_invoice_info_wrapper($form) {

     // Start by calling the duzz_create_form method and get the initial form structure.
   $form = $this->duzz_create_form($form);

    $this->readonlyAttr = []; // This can be set based on your logic for making fields readonly.

    $separateTable = $form->duzz_addChild('table', ['class' => 'invoice-info-wrapper']);

    $headerRow = $separateTable->duzz_addChild('tr', ['class' => 'invoice-name-header-titles']);
    $headerRow->duzz_addChild('th', [], '');
    $headerRow->duzz_addChild('th', [], 'Invoice Name');
    $headerRow->duzz_addChild('th', [], 'Type');
    $headerRow->duzz_addChild('th', [], 'Sales Tax');

    $invoiceDataRow = $separateTable->duzz_addChild('tr', ['class' => 'invoice-name-row']);
    $invoiceDataRow->duzz_addChild('td', ['class' => 'view-mobile-green']);
        $this->payment_data_invoice_name = isset($this->payment_data_invoice_name) ? $this->payment_data_invoice_name : '';
    $invoiceNameAttributes = array_merge([
        'type' => 'text',
        'value' => $this->payment_data_invoice_name,
        'name' => 'invoice_name',
        'id' => 'invoice_name',
        'placeholder' => 'Invoice Name',
        'data-duzz-required' => ''
    ], $this->readonlyAttr);

    $invoiceDataRow->duzz_addChild('td')->duzz_addChild('input', $invoiceNameAttributes);

    $dropdownDisabledAttr = []; // Placeholder for your logic to disable the dropdown if needed.

    $typeSelect = $invoiceDataRow->duzz_addChild('td')->duzz_addChild('select', array_merge([
        'name' => 'invoice_type',
        'id' => 'invoice_type'
    ], $dropdownDisabledAttr));

    $estimateAttributes = ['value' => 'Estimate'];
    $this->payment_data_invoice_type = isset($this->payment_data_invoice_type) ? $this->payment_data_invoice_type : '';
    if ($this->payment_data_invoice_type === 'Estimate') {
        $estimateAttributes['selected'] = 'selected';
    }
    $typeSelect->duzz_addChild('option', $estimateAttributes, 'Estimate');

    $invoiceAttributes = ['value' => 'Invoice'];
    if ($this->payment_data_invoice_type === 'Invoice') {
        $invoiceAttributes['selected'] = 'selected';
    }
    $typeSelect->duzz_addChild('option', $invoiceAttributes, 'Invoice');

    $salesTaxWrapper = $invoiceDataRow->duzz_addChild('td')->duzz_addChild('div', ['class' => 'input-wrapper']);
        $this->payment_data_sales_tax = isset($this->payment_data_sales_tax) ? $this->payment_data_sales_tax : '';
    $salesTaxAttributes = array_merge([
        'type' => 'number',
        'value' => $this->payment_data_sales_tax,
        'name' => 'sales_tax',
        'id' => 'sales_tax',
        'placeholder' => 'Sales Tax',
        'data-duzz-required' => ''
    ], $this->readonlyAttr);

    $salesTaxWrapper->duzz_addChild('input', $salesTaxAttributes);
    $salesTaxWrapper->duzz_addChild('span', ['class' => 'append-icon'], '%');

    return $form;
}


public function duzz_create_invoice_estimate_container($form) {
    $form = $this->duzz_create_invoice_info_wrapper($form);
    list($table, $totals_table, $div) = $this->duzz_create_invoice_line_items($form);
    $this->duzz_create_invoice_totals($totals_table, $div);
    return $form;
}


public function duzz_create_invoice_line_items($form) {
    $this->isCompleted = $this->payment_id ? $this->duzz_isPaymentCompleted($this->payment_id) : false;
    $this->readonlyAttr = $this->isCompleted ? ['readonly' => 'readonly'] : [];
    
    // Container for the tables
    $div = $form->duzz_addChild('div', ['class' => 'invoice-estimate-table-container']);

    // Table for line items
    $table = $div->duzz_addChild('table', ['class' => 'invoice-fields-wrapper']);

    // Add header row to line items table
    $headerRow = $table->duzz_addChild('tr', ['class' => 'invoice-table-header-titles']);
    $headerRow->duzz_addChild('th', [], 'Item');
    $headerRow->duzz_addChild('th', [], 'Units');
    $headerRow->duzz_addChild('th', [], 'Unit Type');
    $headerRow->duzz_addChild('th', [], 'Price');
    $headerRow->duzz_addChild('th', [], 'Action');

    // Add line items rows to line items table
if ($this->payment_id) {
    $this->duzz_addRow($table, '', '', '', '', '', false, $this->isCompleted);

    if (is_array($this->payment_data) && !empty($this->payment_data)) {
        foreach ($this->payment_data as $item_data) {
            if (isset($item_data['id'])) {
                $index = $item_data['id']; // Retrieve the actual row ID
                $this->duzz_addRow($table, $item_data['item'], $item_data['units'], $item_data['unit_type'], $item_data['price'], $index, true, $this->isCompleted);
            }
        }
    }
} else {
        $this->duzz_addRow($table, $this->item, $this->units, '', $this->price, $this->rowID, false, $this->isCompleted);
    }

    // Separate table for totals
    $totals_table = $div->duzz_addChild('table', ['class' => 'invoice-totals-wrapper']);
    // Add totals rows to totals table
    // Example: $this->duzz_create_invoice_totals($totals_table);

    return [$table, $totals_table, $div];
}


public function duzz_create_invoice_totals($totals_table, $div) {
    $this->duzz_create_table_row($totals_table, 'Total', 'invoice-total-border-green', 'total-value');
    $this->duzz_create_table_row($totals_table, 'Tax', 'invoice-total-border-green', 'tax-total-value');
    $this->duzz_create_table_row($totals_table, 'After Tax', 'invoice-total-border-green', 'total-tax-value');


    if ($this->isCompleted) {
        $div->duzz_addChild('p', ['class' => 'payment-status-text'], 'Payment Completed');
    } else {
            if (!empty($this->project_id)) {
        $div->duzz_addChild('input', array(
            'class' => 'submit-payment-button',
            'type' => 'submit',
            'value' => 'Send',
        ));
            }
    }
}


private function duzz_create_table_row($table, $label, $tdClass = '', $tdId = '', $isLastCellEmpty = true) {
    $row = $table->duzz_addChild('tr', ['class' => 'invoice-table-totals-rows']);
    $row->duzz_addChild('td'); // First empty cell
    $row->duzz_addChild('td'); // Second empty cell
    $row->duzz_addChild('td', ['class' => 'invoice-total-border'], $label); // Label cell
    $row->duzz_addChild('td', ['class' => $tdClass, 'id' => $tdId], ''); // Value cell
    if ($isLastCellEmpty) {
        $row->duzz_addChild('td'); // Last empty cell if required
    }
}


  public function duzz_get_payment_table() {
    // Fetch necessary data

    // Add the table for estimates and calculations
    $form = $this->duzz_create_invoice_estimate_container($form);

    // At this point, $form should contain all the required structures for your payment table.
    // Depending on how you're using this function, you can now return the structured form.
    
     return $form;
}
}