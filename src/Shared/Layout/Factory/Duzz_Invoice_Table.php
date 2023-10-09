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

    public function duzz_addHeader($invoiceType, $invoiceName) {
        $headerContainer = new Duzz_Return_HTML('div', ['class' => 'header-pricing-container']);
        $headerContainer->duzz_addChild('div', ['class' => 'invoice-feed-title'], $invoiceType);
        $headerContainer->duzz_addChild('div', ['class' => 'progress-sub-title'], $invoiceName);

        $this->table->duzz_addChild($headerContainer);
    }

    public function duzz_addRow($item, $units, $unitType, $price) {
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
        $rowContainer->duzz_addChild('td', [], sanitize_text_field($item));
        $rowContainer->duzz_addChild('td', ['class' => 'invoice-align-right'], '$' . $total);

        $subRowContainer = new Duzz_Return_HTML('tr', ['class' => 'pricing-invoice-sub']);
        $subRowContainer->duzz_addChild('td', [], $units . ' ' . $unitType . 's');
        $subRowContainer->duzz_addChild('td', ['class' => 'invoice-align-right'], '$' . $price . ' per ' . $unitType);

        $this->table->duzz_addChild($rowContainer);
        $this->table->duzz_addChild($subRowContainer);
    }


        private function duzz_addRowsFromItems() {
        foreach ($this->items as $itemData) {
            $this->duzz_addRow(
                sanitize_text_field($itemData['item']), 
                intval($itemData['units']), 
                sanitize_text_field($itemData['unit_type']), 
                floatval($itemData['price'])
            );
        }
    }

    public function duzz_getTable() {
        $totalTax = $this->duzz_getTaxTotal();
        $totalAfterTax = $this->duzz_getTotalAfterTax();

        $totalsContainer = new Duzz_Return_HTML('div', ['class' => 'total-invoice-pricing-container']);
        $totalsTable = new Duzz_Return_HTML('table');

        $totalsTable->duzz_addChild('tr', ['class' => 'pricing-border-top'])
            ->duzz_addChild('td', [], 'Total')
            ->duzz_addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . $this->totalSum);
        
        $totalsTable->duzz_addChild('tr', ['class' => 'pricing-border-top'])
            ->duzz_addChild('td', [], 'Tax')
            ->duzz_addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], $this->salesTax . '%');
        
        $totalsTable->duzz_addChild('tr', ['class' => 'pricing-border-top-total-price'])
            ->duzz_addChild('td', [], 'After Tax')
            ->duzz_addChild('td', ['class' => 'pricing-invoice-header invoice-align-right'], '$' . $totalAfterTax);
        
        $totalsContainer->duzz_addChild($totalsTable);
        
        $totalsContainer->duzz_addChild('a', [
            'href' => '#',
            'class' => 'featherlight-stipe-trigger',
            'data-featherlight' => '#stripe-popup',
            'data-secret' => '<?php echo $intent->client_secret; ?>',
            'data-amount' => $totalAfterTax
        ], 'Pay Now');

        $this->table->duzz_addChild($totalsContainer);

              return $this->table;
    }

    public function duzz_getTotalSum() {
        return $this->totalSum;
    }

    public function duzz_getTotalAfterTax() {
        return $this->totalSum * (($this->salesTax / 100) + 1);
    }

    public function duzz_getTaxTotal() {
        return $this->totalSum * ($this->salesTax / 100);
    }

        public function duzz_getLineItems() {
        return $this->lineItems;
    }
}

