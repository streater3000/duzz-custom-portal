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

    private function isPaymentCompleted($payment_id) {
        $payment_status = get_post_meta($payment_id, 'payment_confirm', true);
        return $payment_status === 'Completed';
    }

function addRow($table, $item, $units, $unitType, $price, $index = '', $isRemoveButton = false, $readonly = false) {

    $readonlyAttr = $readonly ? ['readonly' => 'readonly'] : [];

    $dataRow = $table->addChild('tr');

    $dataRow->addChild('td')->addChild('input', array_merge(['type' => 'text', 'name' => 'item[' . $index . ']', 'value' => $item, 'placeholder' => 'Item'], $readonlyAttr));
    $dataRow->addChild('td')->addChild('input', array_merge(['type' => 'number', 'name' => 'units[' . $index . ']', 'value' => $units, 'placeholder' => 'Units'], $readonlyAttr));

    $dropdownDisabledAttr = $readonly ? ['disabled' => 'disabled'] : [];

    $unitTypeSelect = $dataRow->addChild('td')->addChild('select', array_merge(['name' => 'unit_type[' . $index . ']'], $dropdownDisabledAttr));

    $hourAttributes = array('value' => 'hour');
        if ($unitType === 'hour') {
            $hourAttributes['selected'] = 'selected';
            }
    $unitTypeSelect->addChild('option', $hourAttributes, 'Hours');

    $unitAttributes = array('value' => 'unit');
        if ($unitType === 'unit') {
            $unitAttributes['selected'] = 'selected';
            }
        $unitTypeSelect->addChild('option', $unitAttributes, 'Quantity');


    $priceWrapper = $dataRow->addChild('td')->addChild('div', ['class' => 'input-wrapper']);
    $priceWrapper->addChild('span', ['class' => 'prepend-icon'], '$');
    $priceWrapper->addChild('input', array_merge(['type' => 'number', 'name' => 'price[' . $index . ']', 'class' => 'price-input', 'value' => $price, 'placeholder' => 'Price', 'step' => '0.01'], $readonlyAttr));

    if ($isRemoveButton) {
        $button = $dataRow->addChild('td')->addChild('button', ['type' => 'button', 'class' => 'add-line-item-button remove-line-item-button', 'onclick' => 'removeRow(this)']);
        $button->addChild('span', ['class' => 'dashicons dashicons-trash'], '');
    } else {
        $button = $dataRow->addChild('td')->addChild('button', ['type' => 'button', 'class' => 'add-line-item-button', 'onclick' => 'addRow(this)']);
        $button->addChild('span', ['class' => 'dashicons dashicons-plus-alt'], '');
    }
}

public function get_payment_table(){

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
        $payment_id = Duzz_Project_Number::generate();
    } else {
        $payment_id = $payment_id[0];
        $payment_data = get_post_meta($payment_id, 'line_items', true);
        $payment_data_sales_tax = get_post_meta($payment_id, 'sales_tax', true);
        $payment_data_invoice_name = get_post_meta($payment_id, 'invoice_name', true);
        $payment_data_invoice_type = get_post_meta($payment_id, 'invoice_type', true);
    }
}

     $isCompleted = $payment_id ? $this->isPaymentCompleted($payment_id) : false;
    $readonlyAttr = $isCompleted ? ['readonly' => 'readonly'] : [];


        // Create the form with the nonce
       $form = new HTMLNamespace\Duzz_Return_HTML('form', array(
        'action' => admin_url('admin-post.php'), // Added form action URL.
        'class' => 'payment-form', 
        'id' => 'invoice', 
        'method' => 'POST'
    ));

    // Hidden field for the action.
$form->addChild('input', array(
    'type' => 'hidden',
    'name' => 'action',
    'value' => 'send_invoice',
));

$form->addChild('input', array(
    'type' => 'hidden',
    'name' => '_wpnonce',
    'value' => wp_create_nonce('send_invoice_nonce'),
));
 
    $form->addChild('input', array(
        'type' => 'hidden',
        'name' => 'project_id',
        'value' => $project_id,
    ));

        $form->addChild('input', array(
        'type' => 'hidden',
        'name' => 'payment_id',
        'value' => $payment_id,
    ));


    $separateTable = $form->addChild('table', ['class' => 'invoice-info-wrapper']);

    $headerRow = $separateTable->addChild('tr');
    $headerRow->addChild('th', [], '');
    $headerRow->addChild('th', [], 'Invoice Name');
    $headerRow->addChild('th', [], 'Type');
    $headerRow->addChild('th', [], 'Sales Tax');



    $invoiceDataRow = $separateTable->addChild('tr');
        $invoiceEmptyRow = $invoiceDataRow->addChild('td', ['class' => 'view-mobile-green']);
        $payment_data_invoice_name = isset($payment_data_invoice_name) ? $payment_data_invoice_name : '';
             $invoiceNameAttributes = array_merge([
                'type' => 'text',
                'value' => $payment_data_invoice_name,
                'name' => 'invoice_name',
                'id' => 'invoice_name',
                'placeholder' => 'Invoice Name',
                'data-duzz-required' => ''   // Adding the attribute here
            ], $readonlyAttr);          

            $invoiceDataRow->addChild('td')->addChild('input', $invoiceNameAttributes);


            $dropdownDisabledAttr = $isCompleted ? ['disabled' => 'disabled'] : [];         

            $typeSelect = $invoiceDataRow->addChild('td')->addChild('select', array_merge([
                'name' => 'invoice_type',
                'id' => 'invoice_type'
            ], $dropdownDisabledAttr));         
            

                        $estimateAttributes = array('value' => 'Estimate');
            $payment_data_invoice_type = isset($payment_data_invoice_type) ? $payment_data_invoice_type : '';
            if ($payment_data_invoice_type === 'Estimate') {

                        $estimateAttributes['selected'] = 'selected';
                        }
                $typeSelect->addChild('option', $estimateAttributes, 'Estimate');

            $invoiceAttributes = array('value' => 'Invoice');
                if ($payment_data_invoice_type === 'Invoice') {
                        $invoiceAttributes['selected'] = 'selected';
                        }
                $typeSelect->addChild('option', $invoiceAttributes, 'Invoice');

    $salesTaxWrapper = $invoiceDataRow->addChild('td')->addChild('div', ['class' => 'input-wrapper']);
            $payment_data_sales_tax = isset($payment_data_sales_tax) ? $payment_data_sales_tax : '';
                    $salesTaxAttributes = array_merge([
                        'type' => 'number',
                        'value' => $payment_data_sales_tax,
                        'name' => 'sales_tax',
                        'id' => 'sales_tax',
                        'placeholder' => 'Sales Tax',
                        'data-duzz-required' => ''   // Adding the attribute here
                    ], $readonlyAttr);                  

                    $salesTaxWrapper->addChild('input', $salesTaxAttributes);

    $salesTaxWrapper->addChild('span', ['class' => 'append-icon'], '%');


        // Create a new table
    $div = $form->addChild('div', ['class' => 'invoice-estimate-table-container']);

    // Create a new table
    $table = $div->addChild('table', ['class' => 'invoice-fields-wrapper']);

        // Add a header row
        $headerRow = $table->addChild('tr');
        $headerRow->addChild('th', [], 'Item');
        $headerRow->addChild('th', [], 'Units');
        $headerRow->addChild('th', [], 'Unit Type');
        $headerRow->addChild('th', [], 'Price');
        $headerRow->addChild('th', [], 'Action');



if ($payment_id) {
    $this->addRow($table, '', '', '', '', '', false, $isCompleted);

    foreach ($payment_data as $index => $item_data) {
        $this->addRow($table, $item_data['item'], $item_data['units'], $item_data['unit_type'], $item_data['price'], $index, true, $isCompleted);
    }
} else {
    $this->addRow($table, $this->item, $this->units, '', $this->price, $this->rowID, false, $isCompleted);
}

    $totalRow = $table->addChild('tr');
    $totalRow->addChild('td');
    $totalRow->addChild('td');
    $totalRow->addChild('td', ['class' => 'invoice-total-border'], 'Total');
    $totalRow->addChild('td', ['class' => 'invoice-total-border-green', 'id' => 'total-value'], '');
    $totalRow->addChild('td');

    $taxCollectRow = $table->addChild('tr');
    $taxCollectRow->addChild('td');
    $taxCollectRow->addChild('td');
    $taxCollectRow->addChild('td', ['class' => 'invoice-total-border'], 'Tax');
    $taxCollectRow->addChild('td', ['class' => 'invoice-total-border-green', 'id' => 'tax-total-value'], '');
    $taxCollectRow->addChild('td');

    $totalTaxRow = $table->addChild('tr');
    $totalTaxRow->addChild('td');
    $totalTaxRow->addChild('td');
    $totalTaxRow->addChild('td', ['class' => 'invoice-total-border'], 'After Tax');
    $totalTaxRow->addChild('td', ['class' => 'invoice-total-border-green', 'id' => 'total-tax-value'], '');
    $totalTaxRow->addChild('td');

    if ($isCompleted) {
            $div->addChild('p', ['class' => 'payment-status-text'], 'Payment Completed');
        } else {
            $div->addChild('input', array(
                'class' => 'submit-payment-button',
                'type' => 'submit',
                'value' => 'Send',
            ));
        }

        return $form;
    }
}

