<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;


class Duzz_Invoice_Table {
    private $table;
    private $totalSum = 0;
    private $salesTax = 0;

    public function __construct($salesTax) {
        $this->salesTax = $salesTax;
        $this->table = new Duzz_Return_HTML('div', ['class' => 'invoice-pricing-container']);
    }

    public function addHeader($invoiceType, $invoiceName) {
        $headerContainer = new Duzz_Return_HTML('div', ['class' => 'header-pricing-container']);
        $headerContainer->addChild('div', ['class' => 'invoice-feed-title'], $invoiceType);
        $headerContainer->addChild('div', ['class' => 'progress-sub-title'], $invoiceName);

        $this->table->addChild($headerContainer);
    }

    public function addRow($item, $units, $unitType, $price) {
        $total = $units * $price;
        $this->totalSum += $total;

         $this->lineItems[] = [
            'item' => sanitize_text_field($item),
            'units' => $units,
            'unit_type' => $unitType,
            'price' => $price,
            'total' => $total
        ];

        $rowContainer = new Duzz_Return_HTML('tr', ['class' => 'pricing-invoice-header']);
        $rowContainer->addChild('td', [], sanitize_text_field($item));
        $rowContainer->addChild('td', ['class' => 'invoice-align-right'], '$' . $total);

        $subRowContainer = new Duzz_Return_HTML('tr', ['class' => 'pricing-invoice-sub']);
        $subRowContainer->addChild('td', [], $units . ' ' . $unitType . 's');
        $subRowContainer->addChild('td', ['class' => 'invoice-align-right'], '$' . $price . ' per ' . $unitType);

        $this->table->addChild($rowContainer);
        $this->table->addChild($subRowContainer);
    }


        private function addRowsFromItems() {
        foreach ($this->items as $itemData) {
            $this->addRow(
                sanitize_text_field($itemData['item']), 
                intval($itemData['units']), 
                sanitize_text_field($itemData['unit_type']), 
                floatval($itemData['price'])
            );
        }
    }

    public function getTable() {
        $totalTax = $this->getTaxTotal();
        $totalAfterTax = $this->getTotalAfterTax();

        $totalsContainer = new Duzz_Return_HTML('div', ['class' => 'total-invoice-pricing-container']);
        $totalsTable = new Duzz_Return_HTML('table');

        $totalsTable->addChild('tr', ['class' => 'pricing-border-top'])
            ->addChild('td', [], 'Total')
            ->addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . $this->totalSum);
        
        $totalsTable->addChild('tr', ['class' => 'pricing-border-top'])
            ->addChild('td', [], 'Tax')
            ->addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], $this->salesTax . '%');
        
        $totalsTable->addChild('tr', ['class' => 'pricing-border-top-total-price'])
            ->addChild('td', [], 'After Tax')
            ->addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . $totalAfterTax);
        
        $totalsContainer->addChild($totalsTable);
        
        $totalsContainer->addChild('a', [
            'href' => '#',
            'class' => 'featherlight-stipe-trigger',
            'data-featherlight' => '#stripe-popup',
            'data-secret' => '<?= $intent->client_secret ?>',
            'data-amount' => $totalAfterTax
        ], 'Pay Now');

        $this->table->addChild($totalsContainer);

              return $this->table;
    }

    public function getTotalSum() {
        return $this->totalSum;
    }

    public function getTotalAfterTax() {
        return $this->totalSum * (($this->salesTax / 100) + 1);
    }

    public function getTaxTotal() {
        return $this->totalSum * ($this->salesTax / 100);
    }

        public function getLineItems() {
        return $this->lineItems;
    }
}

