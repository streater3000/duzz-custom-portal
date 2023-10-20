document.addEventListener('DOMContentLoaded', (event) => {
    const forms = document.querySelectorAll('form'); // Collect all forms on the page

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const fields = Array.from(form.querySelectorAll('[data-duzz-required]')).map(el => el.id);
            let errors = {};

            fields.forEach(fieldId => {
                let field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.style.borderColor = '#e11c1c';
                    errors[fieldId] = `Error: ${formatFieldName(fieldId)} is Empty`;
                } else {
                    field.style.borderColor = '';
                    field.placeholder = formatFieldName(fieldId); // Reset the placeholder when it's not an error
                }
            });

            if (Object.keys(errors).length) {
                e.preventDefault();
                alert(Object.values(errors).join("\n"));
                displayDynamicErrors(errors);
            }
        });
    });

    // Clear the error styling once the user starts typing
    const inputs = document.querySelectorAll('input[data-duzz-required]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '';
            this.style.color = ''; // Reset the text color
            this.placeholder = formatFieldName(this.id);
        });
    });

    function formatFieldName(fieldName) {
        // Check and remove the word 'customer' if it exists in the fieldName
        let cleanedFieldName = fieldName.replace(/customer/gi, '').replace(/[_-]/g, ' ');

        // Split by spaces, capitalize each word, and join back
        return cleanedFieldName
            .trim() // ensure there are no leading/trailing spaces after removal
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    function displayDynamicErrors(errors) {
        for (let fieldId in errors) {
            let field = document.getElementById(fieldId);
            field.placeholder = errors[fieldId]; // Set the error as the field's placeholder
            field.style.color = '#e11c1c'; // Make the text color red to indicate error
        }
    }
});

