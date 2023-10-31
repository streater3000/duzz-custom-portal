<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Actions\Duzz_Project_Number;

class Duzz_Send_Payment{
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
    }

    private function duzz_isPaymentCompleted($payment_id) {
        $payment_status = get_post_meta($payment_id, 'payment_confirm', true);
        return $payment_status === 'Completed';
    }

function duzz_addRow($table, $item, $units, $unitType, $price, $index = '', $isRemoveButton = false, $readonly = false) {

    $readonlyAttr = $readonly ? ['readonly' => 'readonly'] : [];

    $dataRow = $table->duzz_addChild('tr');

    $dataRow->duzz_addChild('td')->duzz_addChild('input', array_merge(['type' => 'text', 'name' => 'item[' . $index . ']', 'value' => $item, 'placeholder' => 'Item'], $readonlyAttr));
    $dataRow->duzz_addChild('td')->duzz_addChild('input', array_merge(['type' => 'number', 'name' => 'units[' . $index . ']', 'value' => $units, 'placeholder' => 'Units'], $readonlyAttr));

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


    $priceWrapper = $dataRow->duzz_addChild('td')->duzz_addChild('div', ['class' => 'input-wrapper']);
    $priceWrapper->duzz_addChild('span', ['class' => 'prepend-icon'], '$');
    $priceWrapper->duzz_addChild('input', array_merge(['type' => 'number', 'name' => 'price[' . $index . ']', 'class' => 'price-input', 'value' => $price, 'placeholder' => 'Price', 'step' => '0.01'], $readonlyAttr));

    if ($isRemoveButton) {
        $button = $dataRow->duzz_addChild('td')->duzz_addChild('button', ['type' => 'button', 'class' => 'add-line-item-button remove-line-item-button', 'onclick' => 'removeRow(this)']);
        $button->duzz_addChild('span', ['class' => 'dashicons dashicons-trash'], '');
    } else {
        $button = $dataRow->duzz_addChild('td')->duzz_addChild('button', ['type' => 'button', 'class' => 'add-line-item-button', 'onclick' => 'addRow(this)']);
        $button->duzz_addChild('span', ['class' => 'dashicons dashicons-plus-alt'], '');
    }
}

public function duzz_get_payment_table(){

    // Replacing $_GET with get_query_var for better WordPress compatibility
    $project_id = absint(get_query_var('project_id', '')); 

    $payment_data = [];
    $payment_id = null;

if ($project_id) {
    // Get payment id that equals project_id
    $payment_id = get_posts([
        'post_type' => 'payment',
        'meta_key' => 'project_id',
        'meta_value' => $project_id,
        'posts_per_page' => 1,
        'fields' => 'ids',
    ]);

    // If $payment_id doesn't exist for the given $project_id, generate a new one.
    if (!$payment_id) {
        $payment_id = Duzz_Project_Number::duzz_generate();
    } else {
        $payment_id = $payment_id[0];
        $payment_data = get_post_meta($payment_id, 'line_items', true);
        $payment_data_sales_tax = get_post_meta($payment_id, 'sales_tax', true);
        $payment_data_invoice_name = get_post_meta($payment_id, 'invoice_name', true);
        $payment_data_invoice_type = get_post_meta($payment_id, 'invoice_type', true);
    }
}

     $isCompleted = $payment_id ? $this->duzz_isPaymentCompleted($payment_id) : false;
    $readonlyAttr = $isCompleted ? ['readonly' => 'readonly'] : [];


        // Create the form with the nonce
       $form = new HTMLNamespace\Duzz_Return_HTML('form', array(
        'action' => admin_url('admin-post.php'), // Added form action URL.
        'class' => 'payment-form', 
        'id' => 'invoice', 
        'method' => 'POST'
    ));

    // Hidden field for the action.
$form->duzz_addChild('input', array(
    'type' => 'hidden',
    'name' => 'action',
    'value' => 'send_invoice',
));

$form->duzz_addChild('input', array(
    'type' => 'hidden',
    'name' => '_wpnonce',
    'value' => wp_create_nonce('send_invoice_nonce'),
));
 
    $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'project_id',
        'value' => $project_id,
    ));

        $form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'payment_id',
        'value' => $payment_id,
    ));


    $separateTable = $form->duzz_addChild('table', ['class' => 'invoice-info-wrapper']);

    $headerRow = $separateTable->duzz_addChild('tr');
    $headerRow->duzz_addChild('th', [], '');
    $headerRow->duzz_addChild('th', [], 'Invoice Name');
    $headerRow->duzz_addChild('th', [], 'Type');
    $headerRow->duzz_addChild('th', [], 'Sales Tax');



    $invoiceDataRow = $separateTable->duzz_addChild('tr');
        $invoiceEmptyRow = $invoiceDataRow->duzz_addChild('td', ['class' => 'view-mobile-green']);
        $payment_data_invoice_name = isset($payment_data_invoice_name) ? $payment_data_invoice_name : '';
             $invoiceNameAttributes = array_merge([
                'type' => 'text',
                'value' => $payment_data_invoice_name,
                'name' => 'invoice_name',
                'id' => 'invoice_name',
                'placeholder' => 'Invoice Name',
                'data-duzz-required' => ''   // Adding the attribute here
            ], $readonlyAttr);          

            $invoiceDataRow->duzz_addChild('td')->duzz_addChild('input', $invoiceNameAttributes);


            $dropdownDisabledAttr = $isCompleted ? ['disabled' => 'disabled'] : [];         

            $typeSelect = $invoiceDataRow->duzz_addChild('td')->duzz_addChild('select', array_merge([
                'name' => 'invoice_type',
                'id' => 'invoice_type'
            ], $dropdownDisabledAttr));         
            

                        $estimateAttributes = array('value' => 'Estimate');
            $payment_data_invoice_type = isset($payment_data_invoice_type) ? $payment_data_invoice_type : '';
            if ($payment_data_invoice_type === 'Estimate') {

                        $estimateAttributes['selected'] = 'selected';
                        }
                $typeSelect->duzz_addChild('option', $estimateAttributes, 'Estimate');

            $invoiceAttributes = array('value' => 'Invoice');
                if ($payment_data_invoice_type === 'Invoice') {
                        $invoiceAttributes['selected'] = 'selected';
                        }
                $typeSelect->duzz_addChild('option', $invoiceAttributes, 'Invoice');

    $salesTaxWrapper = $invoiceDataRow->duzz_addChild('td')->duzz_addChild('div', ['class' => 'input-wrapper']);
            $payment_data_sales_tax = isset($payment_data_sales_tax) ? $payment_data_sales_tax : '';
                    $salesTaxAttributes = array_merge([
                        'type' => 'number',
                        'value' => $payment_data_sales_tax,
                        'name' => 'sales_tax',
                        'id' => 'sales_tax',
                        'placeholder' => 'Sales Tax',
                        'data-duzz-required' => ''   // Adding the attribute here
                    ], $readonlyAttr);                  

                    $salesTaxWrapper->duzz_addChild('input', $salesTaxAttributes);

    $salesTaxWrapper->duzz_addChild('span', ['class' => 'append-icon'], '%');


        // Create a new table
    $div = $form->duzz_addChild('div', ['class' => 'invoice-estimate-table-container']);

    // Create a new table
    $table = $div->duzz_addChild('table', ['class' => 'invoice-fields-wrapper']);

        // Add a header row
        $headerRow = $table->duzz_addChild('tr');
        $headerRow->duzz_addChild('th', [], 'Item');
        $headerRow->duzz_addChild('th', [], 'Units');
        $headerRow->duzz_addChild('th', [], 'Unit Type');
        $headerRow->duzz_addChild('th', [], 'Price');
        $headerRow->duzz_addChild('th', [], 'Action');



if ($payment_id) {
    $this->duzz_addRow($table, '', '', '', '', '', false, $isCompleted);

    foreach ($payment_data as $index => $item_data) {
        $this->duzz_addRow($table, $item_data['item'], $item_data['units'], $item_data['unit_type'], $item_data['price'], $index, true, $isCompleted);
    }
} else {
    $this->duzz_addRow($table, $this->item, $this->units, '', $this->price, $this->rowID, false, $isCompleted);
}

    $totalRow = $table->duzz_addChild('tr');
    $totalRow->duzz_addChild('td');
    $totalRow->duzz_addChild('td');
    $totalRow->duzz_addChild('td', ['class' => 'invoice-total-border'], 'Total');
    $totalRow->duzz_addChild('td', ['class' => 'invoice-total-border-green', 'id' => 'total-value'], '');
    $totalRow->duzz_addChild('td');

    $taxCollectRow = $table->duzz_addChild('tr');
    $taxCollectRow->duzz_addChild('td');
    $taxCollectRow->duzz_addChild('td');
    $taxCollectRow->duzz_addChild('td', ['class' => 'invoice-total-border'], 'Tax');
    $taxCollectRow->duzz_addChild('td', ['class' => 'invoice-total-border-green', 'id' => 'tax-total-value'], '');
    $taxCollectRow->duzz_addChild('td');

    $totalTaxRow = $table->duzz_addChild('tr');
    $totalTaxRow->duzz_addChild('td');
    $totalTaxRow->duzz_addChild('td');
    $totalTaxRow->duzz_addChild('td', ['class' => 'invoice-total-border'], 'After Tax');
    $totalTaxRow->duzz_addChild('td', ['class' => 'invoice-total-border-green', 'id' => 'total-tax-value'], '');
    $totalTaxRow->duzz_addChild('td');

    if ($isCompleted) {
            $div->duzz_addChild('p', ['class' => 'payment-status-text'], 'Payment Completed');
        } else {
            $div->duzz_addChild('input', array(
                'class' => 'submit-payment-button',
                'type' => 'submit',
                'value' => 'Send',
            ));
        }

        return $form;
    }
}
