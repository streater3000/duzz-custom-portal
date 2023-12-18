<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_Invoice_Table {
    protected $table;
    protected $totalSum = 0;
    protected $salesTax = 0;
    protected $tbody; // Store reference to tbody for direct access
    protected $headerContainer; // Store reference to the header container for direct access

    public function __construct() {
        // Create the main invoice table container
        $this->table = new Duzz_Return_HTML('div', ['class' => 'invoice-pricing-container']);

        // Initialize the header container
        $this->headerContainer = $this->table->duzz_addChild('div', ['class' => 'header-pricing-container']);
        
        // Initialize and store the tbody reference
        $this->tbody = $this->table->duzz_addChild('table')->duzz_addChild('tbody'); 
    }

    public function duzz_addHeader($invoice_type, $invoice_name, $sales_tax) {
        $this->salesTax = $sales_tax;

        // Set content for the header container
        $this->headerContainer->duzz_addChild('div', ['class' => 'invoice-feed-title'], sanitize_text_field($invoice_type));
        $this->headerContainer->duzz_addChild('div', ['class' => 'progress-sub-title'], sanitize_text_field($invoice_name));
    }

    public function duzz_addRow($item, $units, $unit_type, $price) {
        $total = $units * $price;
        $this->totalSum += $total;

        // Use the stored tbody reference
        $rowMain = $this->tbody->duzz_addChild('tr', ['class' => 'pricing-invoice-header']);
        $rowMain->duzz_addChild('td', [], sanitize_text_field($item));
        $rowMain->duzz_addChild('td', ['class' => 'invoice-align-right'], '$' . number_format($total, 2));

        // Adding the sub row
        $rowSub = $this->tbody->duzz_addChild('tr', ['class' => 'pricing-invoice-sub']);
        $rowSub->duzz_addChild('td', [], $units . ' ' . sanitize_text_field($unit_type) . 's');
        $rowSub->duzz_addChild('td', ['class' => 'invoice-align-right'], '$' . number_format($price, 2) . ' per ' . sanitize_text_field($unit_type));
    }

public function duzz_generatePayNowButton($clientSecret, $amount, $project_id) {
    $button = new Duzz_Return_HTML('button', [
        'type' => 'button',
        'class' => 'featherlight-stipe-trigger',
        'data-featherlight' => '#stripe-popup',
        'data-secret' => $clientSecret,
        'data-amount' => $amount,
        'data-project-id' => $project_id
    ]);
    $button->duzz_setContent('Pay Now');
    return $button;
}

    public function duzz_getTotalAfterTax() {
        $tax_total = $this->totalSum * ($this->salesTax / 100);
        $total_aftertax_sum = $this->totalSum + $tax_total;

        $totalContainer = new Duzz_Return_HTML('div', ['class' => 'total-invoice-pricing-container']);
        $totalTable = $totalContainer->duzz_addChild('table')->duzz_addChild('tbody');

        $rowTotal = $totalTable->duzz_addChild('tr', ['class' => 'pricing-border-top']);
        $rowTotal->duzz_addChild('td', [], 'Total');
        $rowTotal->duzz_addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . number_format($this->totalSum, 2));

        $rowTax = $totalTable->duzz_addChild('tr', ['class' => 'pricing-border-top']);
        $rowTax->duzz_addChild('td', [], 'Tax (' . sanitize_text_field($this->salesTax) . '%)');
        $rowTax->duzz_addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . number_format($tax_total, 2));

        $rowAfterTax = $totalTable->duzz_addChild('tr', ['class' => 'pricing-border-top-total-price']);
        $rowAfterTax->duzz_addChild('td', [], 'After Tax');
        $rowAfterTax->duzz_addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . number_format($total_aftertax_sum, 2));

        $this->table->duzz_addChild($totalContainer);

        return $total_aftertax_sum;
    }


    public function duzz_getTable() {
        return $this->table;
    }

    public function duzz_getTotalSum() {
        return $this->totalSum;
    }

    public function duzz_getTaxTotal() {
        return $this->totalSum * ($this->salesTax / 100);
    }

    public function duzz_getLineItems() {
        return $this->lineItems;
    }
}
