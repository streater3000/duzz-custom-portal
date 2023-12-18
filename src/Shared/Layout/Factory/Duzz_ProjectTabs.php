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

  document.querySelector(\'.invoice-fields-wrapper\').addEventListener(\'input\', function(event) {
      var target = event.target;
      if (target && (target.matches(\'input[name^="units"]\') || target.matches(\'input[name^="price"]\'))) {
          calculateTotal();
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
    if (defaultTab) defaultTab.click();
  } else {
    setActiveTabFromUrlExtension();
  }

function findHighestRowID() {
    var inputs = document.querySelectorAll(\'input\');
    var highestID = 0;
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

var firstRowID = 1; // Initialize firstRowID to 0
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
    var newRow = parentRow.cloneNode(true);
    var lastRowID = findHighestRowID();
    var newRowID = lastRowID + 1; // Calculate the new ID based on the lastRowID

    // Transfer input and select values from the addRow row to the new row
    var currentInputs = parentRow.querySelectorAll(\'input\');
    var newInputs = newRow.querySelectorAll(\'input\');
    currentInputs.forEach(function(input, index) {
        newInputs[index].value = input.value; // Copy value from current to new input
        input.value = \'\'; // Clear the current input
        newInputs[index].name = input.name.replace(/\\[(\\d+)?\\]/, \'[\' + newRowID + \']\');
    });

    var currentSelects = parentRow.querySelectorAll(\'select\');
    var newSelects = newRow.querySelectorAll(\'select\');
    currentSelects.forEach(function(select, index) {
        newSelects[index].selectedIndex = select.selectedIndex; // Copy selected index from current to new select
        select.selectedIndex = 0; // Reset the current select
        newSelects[index].name = select.name.replace(/\\[(\\d+)?\\]/, \'[\' + newRowID + \']\');
    });

    // Update button in the new row to be a remove button
    var addButton = newRow.querySelector(\'button.add-line-item-button\');
    if (addButton) {
        addButton.innerHTML = \'<span class="dashicons dashicons-trash"></span>\';
        addButton.classList.remove(\'add-line-item-button\');
        addButton.classList.add(\'remove-line-item-button\');
        addButton.onclick = function() {
            this.parentNode.parentNode.remove();
            calculateTotal();
        };
    }

    // Append the new row after the addRow row
    parentRow.parentNode.insertBefore(newRow, parentRow.nextSibling);

    calculateTotal();
};



  // Calculate total
function calculateTotal() {
    // First check and calculate any existing rows
    var rows = document.querySelectorAll(\'.invoice-fields-wrapper tr.invoice-table-line-items-row\');
    var total = 0.0;
    var salesTax = parseFloat(document.getElementById(\'sales_tax\').value) || 0;
    rows.forEach(function(row) {
        var unitsInput = row.querySelector(\'input[name^="units"]\');
        var priceInput = row.querySelector(\'input[name^="price"]\');
        var units = unitsInput ? parseFloat(unitsInput.value) : 0;
        var price = priceInput ? parseFloat(priceInput.value) : 0;
        if (!isNaN(units) && !isNaN(price)) {
            total += units * price;
        }
    });

    var totalTax = total * (salesTax / 100);
    var totalAfterTax = total + totalTax;

    document.getElementById(\'total-value\').textContent = total.toFixed(2) || \'0.00\';
    document.getElementById(\'tax-total-value\').textContent = totalTax.toFixed(2) || \'0.00\';
    document.getElementById(\'total-tax-value\').textContent = totalAfterTax.toFixed(2) || \'0.00\';
}

// Function to bind events to inputs and selects for live calculation
function bindEventsToInvoiceFields() {
    // Logic to bind input events
    document.querySelectorAll(\'.invoice-fields-wrapper input\').forEach(function(input) {
        if (input.name.startsWith(\'units\') || input.name.startsWith(\'price\')) {
            input.addEventListener(\'input\', calculateTotal);
        }
    });

    var salesTaxInput = document.getElementById(\'sales_tax\');
    if (salesTaxInput) {
        salesTaxInput.addEventListener(\'input\', calculateTotal);
    }
}

// Call the function to bind events on page load
window.onload = function() {
    bindEventsToInvoiceFields();
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

    bindEventsToInvoiceFields();
    calculateTotal();
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