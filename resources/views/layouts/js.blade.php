<script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" data-auto-replace-svg="nest"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

<script src="../assets/index.js"></script>

<audio id="myAudio">
    <source src="../notification.mp3" type="audio/mpeg">
</audio>
<div class="alert alert-success notification" role="alert">
    შემოვიდა ახალი შეკვეთა
</div>
@php
    if (isset($countTransaction)) {
    } else {
        $countTransaction = 0;
    }
@endphp

@if(isset(Auth::user()->id))
<script>
    var $id = '{{Auth::user()->id}}';
</script>
@else
<script>
    var $id = -1;
</script>
@endif
<script>
    var x = document.getElementById("myAudio");

    function playAudio() {
        x.play();
    }
    $transactionCount = {{ $countTransaction }};
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: "eu"
    });
    const channel = pusher.subscribe('public');

    // recive
    channel.bind('chat', function(data) {
        $.post("/receive", {
                _token: '{{ csrf_token() }}',
                message: data.message,
            })
            .done(function(res) {
                alert(123);
            })
        $data = data.message;
        console.log($data)
        if ($data == $id) {
            $transactionCount = $transactionCount + 1
            $(".transaction-count").text($transactionCount);
            $('.notification').toggle();
            playAudio()
            setTimeout(
                function() {
                  $('.notification').toggle();
                }, 5000);
        }
    })
</script>
