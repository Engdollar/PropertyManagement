@push('scripts')
@if(isset($stripe_session) && isset($property))

    <script src="https://js.stripe.com/v3/"></script>
    <script>
    var stripe = Stripe("{{ company_setting('stripe_key', $property->created_by, $property->workspace) }}");
    stripe.redirectToCheckout({
        sessionId: '{{ $stripe_session->id }}',
    }).then((result) => {
    });
    </script>
@endif
