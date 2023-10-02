document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe(duzzStripeData.stripeKey);

    jQuery(document).on('click', '.featherlight-stipe-trigger', function(e) {
        e.preventDefault();

        const amount = e.currentTarget.getAttribute("data-amount");
        const clientSecret = e.currentTarget.getAttribute("data-secret");
        const userEmail = e.currentTarget.getAttribute("data-email");
        const dataProjectID = e.currentTarget.getAttribute("data-project-id");

        openPopup(stripe, amount, clientSecret, dataProjectID);
    });

    function openPopup(stripe, amount, clientSecret, projectId) {
        const popupContent = `
            <div id="stripe-popup" class="stripe-popup-container">
                <div id="popup-modal">
                    <div id="payment-element">
                        <!--Stripe.js injects the Payment Element-->
                    </div>
                    <p>Amount: $${amount}</p>
                    <button id="submit">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Pay now</span>
                    </button>
                    <div id="payment-message" class="hidden"></div>
                </div>
                <div id="popup-overlay"></div>
            </div>
        `;

        jQuery('.featherlight-content').append(popupContent);

        const elements = stripe.elements({ clientSecret: clientSecret });
        const currentUrl = window.location.href;

        const paymentElementOptions = {
            layout: "tabs",
        };

        const paymentElement = elements.create("payment", paymentElementOptions);
        paymentElement.mount("#payment-element");

        document.getElementById("submit").addEventListener("click", async function() {
            setLoading(true);

            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: currentUrl
                }
            });

            if (error) {
                setLoading(false);
                showMessage(error.message);
                return;
            }

            try {
                const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);
            } catch (error) {}

            switch (paymentIntent.status) {
                case "succeeded":
                    setLoading(false);
                    showMessage("Payment succeeded!");

                    // Trigger the AJAX call to WordPress
                    jQuery.ajax({
                        type: "POST",
                        url: my_ajax_object.ajax_url,
                        data: {
                            action: "add_payment_comment",
                            amount: amount,
                            project_id: projectId,
                            nonce: my_ajax_object.nonce 
                        },
                        success: function(response) {},
                        error: function(error) {}
                    });
                    break;
                case "processing":
                    setLoading(false);
                    showMessage("Your payment is processing.");
                    break;
                case "requires_payment_method":
                    setLoading(false);
                    showMessage("Your payment was not successful, please try again.");
                    break;
                default:
                    setLoading(false);
                    showMessage("Something went wrong.");
                    break;
            }
        });
    }

    function showMessage(messageText) {
        const messageContainer = document.querySelector("#payment-message");

        messageContainer.classList.remove("hidden");
        messageContainer.textContent = messageText;

        setTimeout(function () {
            messageContainer.classList.add("hidden");
            messageContainer.textContent = "";
        }, 4000);
    }

    function setLoading(isLoading) {
        if (isLoading) {
            document.querySelector("#submit").disabled = true;
            document.querySelector("#spinner").classList.remove("hidden");
            document.querySelector("#button-text").classList.add("hidden");
        } else {
            document.querySelector("#submit").disabled = false;
            document.querySelector("#spinner").classList.add("hidden");
            document.querySelector("#button-text").classList.remove("hidden");
        }
    }
});

