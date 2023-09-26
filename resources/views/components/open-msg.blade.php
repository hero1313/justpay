@extends('index')
@section('content')


    <style>
        .menu {
            display: none;
        }
        button{
            display: none !important;
        }
    </style>
    <script>
        Swal.fire({
  icon: 'success',
  title: 'გადარიცხვის მოთხოვნა მიღებულია',
  text: '',
  footer: '<a href="https://onpay.cloud/landing-page">მთავარ გვერდზე დაბრუნება</a>'
})
</script>

@stop
