<?php

namespace Duzz\Shared\Layout\Factory;

class Duzz_ProjectTabs {
    private $tabContents = array();

    public function duzz_addTabContent($tabContent, $identifier) {
        // Apply a filter for the tab label using the provided identifier
        if (!empty($identifier)) {
            $filter_name = "tab_label_filter_{$identifier}";
            if (has_filter($filter_name)) {
                $tabContent['label'] = apply_filters($filter_name, $tabContent['label'], $identifier);
            }
        }

        $this->tabContents[] = $tabContent;
    }

public function duzz_render() {
    $tabs = '';
    $tabContents = '';
    $anchor = '<div id="tab-anchor"></div>';
    foreach ($this->tabContents as $index => $tabContent) {
        $tabId = $tabContent['label'];
        $activeClass = $index === 0 ? ' active' : '';
        $tabs .= '<div class="tablinks' . $activeClass . '" onclick="openTab(event, \'' . $tabId . '\')">' . $tabContent['label'] . '</div>';

        $beforeContentIdentifier = "tab_content_before_{$tabId}";
        $afterContentIdentifier = "tab_content_after_{$tabId}";
        
        $beforeContent = has_action($beforeContentIdentifier) ? apply_filters($beforeContentIdentifier, '') : '';
        $afterContent = has_action($afterContentIdentifier) ? apply_filters($afterContentIdentifier, '') : '';

        $displayStyle = $index === 0 ? 'block' : 'none';
        $defaultClass = $index === 0 ? ' defaultTabContent' : '';
        $tabContents .= '<div class="tabcontent' . $defaultClass . '" id="' . $tabId . '">' . $beforeContent . $tabContent['content'] . $afterContent . '</div>';
    }

    return '<div class="tabs">' . $anchor . $tabs . '</div><div class="tabcontent-container">' . $tabContents . '</div><style>.tabcontent-container { margin-top: 20px; }</style><script>

jQuery(document).ready(function($) {
  if (typeof acf !== \'undefined\') {
    acf.validation.active = false;
  }
});

function openTab(event, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  event.currentTarget.className += " active";

  // Set the URL extension when a tab is clicked
  location.hash = tabName + "-anchor";
}

// Function to check if URL has an extension like \'Funds-anchor\'
function hasUrlExtension() {
  var hash = location.hash.substring(1);
  return hash.endsWith("-anchor");
}

// Function to set the active tab based on the URL extension
function setActiveTabFromUrlExtension() {
    if (hasUrlExtension()) {
        var hash = location.hash.substring(1);
        var tabId = hash.replace("-anchor", "");
        var tabLink = document.querySelector(".tablinks[onclick*=\'" + tabId + "\']");
        if (tabLink) {
            // Scroll to the anchor before clicking the tab link
            var anchor = document.getElementById(\'tab-anchor\');
            if (anchor) {
                anchor.scrollIntoView();
            }
            tabLink.click();
        }
    }
}


// Set the active tab before the page loads
if (!hasUrlExtension()) {
  var defaultTab = document.querySelector(".tablinks[onclick*=\'Info\']");
  if (defaultTab) {
    defaultTab.click();
  }
} else {
  setActiveTabFromUrlExtension();
}

function findHighestRowID() {
    var inputs = document.querySelectorAll(\'input\');
    var highestID = -1;
    for (var i = 0; i < inputs.length; i++) {
        var name = inputs[i].name;
        var matches = name.match(/\[(\d+)\]/);
if (matches) {
  if (matches[1]) {
            var rowID = parseInt(matches[1]);
            highestID = Math.max(highestID, rowID);
        }
    }
}
    return highestID;
}

var firstRowID = 0; // Initialize firstRowID to 0
var lastRowID = findHighestRowID(); // Initialize lastRowID to the highest ID among existing rows

function formatPriceField(input) {
    var value = parseFloat(input.value);
    input.value = isNaN(value) ? \'\' : value.toFixed(2);
}

function bindInputEvents(input) {
    // bind input event to recalculate total whenever a value is entered or changed
    input.addEventListener(\'input\', function() {
        formatPriceField(this);
        calculateTotal(); 
    });
}

window.addRow = function(btn) {
    var parentRow = btn.parentNode.parentNode;
    var table = parentRow.parentNode;
    var rowCopy = parentRow.cloneNode(true);

    // Here you reference the total row using its ID.
    var totalRow = document.getElementById(\'total-value\').parentNode;
        var taxRow = document.getElementById(\'tax-total-value\').parentNode;
    var totalTaxRow = document.getElementById(\'total-tax-value\').parentNode;

    var inputs = parentRow.querySelectorAll(\'input\');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].value = \'\';
        // Manually dispatch the input event
        inputs[i].dispatchEvent(new Event(\'input\'));
    }

    var select = parentRow.querySelector(\'select\');
    var selectedIndex = select.selectedIndex;
    select.selectedIndex = 0;

    var button = rowCopy.querySelector(\'button\');
    button.innerHTML = \'<span class="dashicons dashicons-trash"></span>\';
    button.classList.add("remove-line-item-button");
    button.onclick = function() {
        this.parentNode.parentNode.remove();
        calculateTotal();
    };

    var newRowID = lastRowID + 1; // Calculate the new ID based on the lastRowID
    if (lastRowID === -1) {
        newRowID = firstRowID; // If there are no existing rows, set newRowID to the firstRowID
    }

    var inputs = rowCopy.querySelectorAll(\'input\');
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].name.match(/\[\]/)) {
            inputs[i].name = inputs[i].name.replace(/\[\]/, \'[\' + newRowID + \']\');
        } else {
            inputs[i].name = inputs[i].name.replace(/\[(\d+)\]/, \'[\' + newRowID + \']\');
        }

        // Attach an event listener to the input fields
        inputs[i].addEventListener(\'input\', calculateTotal);

        // Check if the input has the class \'price-input\'
        if (inputs[i].classList.contains(\'price-input\')) {
            // Add onblur event to format the price
            inputs[i].onblur = function() {
                formatPriceField(this);
                calculateTotal();  // Recalculate the total when price changes
            };
            // Format the price field immediately after the row is added
            formatPriceField(inputs[i]);
        }
    }

    var select = rowCopy.querySelector(\'select\');
    if (select.name.match(/\[\]/)) {
        select.name = select.name.replace(/\[\]/, \'[\' + newRowID + \']\');
    } else {
        select.name = select.name.replace(/\[(\d+)\]/, \'[\' + newRowID + \']\');
    }
    select.selectedIndex = selectedIndex;

    table.insertBefore(rowCopy, totalRow);
    lastRowID = newRowID; // Update lastRowID to the new ID
    calculateTotal(); 
};


function calculateTotal() {
    var table = document.querySelector(\'.payment-form .invoice-fields-wrapper\');
    var rows = table.querySelectorAll(\'tr\');
    var total = 0.0;
            var salesTax = document.getElementById(\'sales_tax\').value;
            var totalAfterTax = 0.0;
    for (var i = 1; i < rows.length - 1; i++) {  // Exclude the header and total rows
        var unitsInput = rows[i].querySelector(\'input[name^="units"]\');
        var priceInput = rows[i].querySelector(\'input[name^="price"]\');
        var units = unitsInput ? parseFloat(unitsInput.value) : 0;
        var price = priceInput ? parseFloat(priceInput.value) : 0;
if (!isNaN(units)) {
  if (!isNaN(price)) {
            total += units * price;
        }
    }
}

      totalTax = total * (salesTax / 100);
        totalAfterTax = total * (1 + salesTax / 100);
        document.getElementById(\'total-value\').textContent = total.toFixed(2);
              document.getElementById(\'tax-total-value\').textContent = totalTax.toFixed(2);
        document.getElementById(\'total-tax-value\').textContent = totalAfterTax.toFixed(2);
}


// Attach the calculateTotal event listener to all existing inputs on page load
document.querySelectorAll(\'input\').forEach(input => {
    input.addEventListener(\'input\', calculateTotal);
});

// Run the total calculation once on page load
calculateTotal();

window.onload = function() {
    // Get all remove buttons
    var removeButtons = document.querySelectorAll(\'.remove-line-item-button\');
    
    // Loop through each button
    for (var i = 0; i < removeButtons.length; i++) {
        // Add event listener to button
        removeButtons[i].onclick = function() {
            this.parentNode.parentNode.remove();
            calculateTotal();
        };
    }
};


   document.addEventListener(\'DOMContentLoaded\', function() {
            // Get all select elements on the page
            const selects = document.querySelectorAll(\'select\');

            // Loop through each select element
            selects.forEach(select => {
                // Get the selected option (if any)
                const selectedOption = select.querySelector(\'option[selected]\');

                // Check if a selected option is found
                if (selectedOption) {
                    // Get the text of the selected option
                    const selectedText = selectedOption.textContent;
                    console.log(\'Selected: \' + selectedText);
                }
            });


        });



</script>';
    }
}
