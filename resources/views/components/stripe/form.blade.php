@extends('index')
@section('content')
{{-- <form action="{{ route('stripe.payment') }}" method="post">
    <script
        src="https://checkout.stripe.com/checkout.js"
        class="stripe-button"
        data-key="pk_test_51LTMwVFPefbRqaCagR6ohCK2mT45sLAIluvxN3Ej03DNce9A8cx3Zy3ZkUS7sPW0982St2YKoq5STOcg4UwUen0V00r7gDDlu7"
        data-amount="500"
        data-currency="usd">
    </script>
</form> --}}
<form action="{{ route('stripe.payment') }}" method="post">
    <script
      src="https://checkout.stripe.com/checkout.js"
      class="stripe-button"
      data-key="pk_test_51LTMwVFPefbRqaCagR6ohCK2mT45sLAIluvxN3Ej03DNce9A8cx3Zy3ZkUS7sPW0982St2YKoq5STOcg4UwUen0V00r7gDDlu7"
      data-name="Gold Tier"
      data-description="Monthly subscription"
      data-amount="2000"
      data-currency="usd"
      data-label="Subscribe">
    </script>
</form>
@stop


