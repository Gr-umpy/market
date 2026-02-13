<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div id="checkout">
        <!-- Checkout will insert the payment form here -->
    </div>

    <script>
        const stripe = Stripe('pk_test_51T0IOn1xxtZxKTnGtswQxZRfGoUBokNAJvANtSEM1K2j41wirDKN3mmgLriBCHrrvxdSoSsEg86mMyqEkAHH69Yg00PJIZQJDf');

        initialize();

        async function initialize() {
            const fetchClientSecret = async () => {
                const response = await fetch("{{ route('checkout.session') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const { clientSecret } = await response.json();
                return clientSecret;
            };

            const checkout = await stripe.initEmbeddedCheckout({
                fetchClientSecret,
            });

            // Mount Checkout
            checkout.mount('#checkout');
        }
    </script>
</body>

</html>