<x-layout>
    <x-slot:heading>
        Page de retour
    </x-slot:heading>
    <section id="success" class="hidden">
        <p>
            We appreciate your business! A confirmation email will be sent to <span id="customer-email"></span>.
        </p>
    </section>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        initialize();

        async function initialize() {
            const sessionId = '{{ $session_id }}';
            const response = await fetch("{{ route('checkout.status') }}", {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: "POST",
                body: JSON.stringify({ session_id: sessionId }),
            });
            const session = await response.json();

            if (session.status == 'open') {
                window.location.replace('{{ route('checkout') }}')
            } else if (session.status == 'complete') {
                document.getElementById('success').classList.remove('hidden');
                document.getElementById('customer-email').textContent = session.customer_email
            }
        }
    </script>
</x-layout>