@extends('admin.index')
@section('content')
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">შექმნის თარიღი</th>
        <th scope="col">ფასი</th>
        <th scope="col">ინვოისის ბმული</th>
        <th scope="col">სტატუსი</th>
        <th scope="col">დამატებითი ინფორმაცია</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orders as $order)
        @php
          if ($order->valuta == 1) {
            $valuta = 'GEL';
          }
          elseif ($order->valuta == 2) {
            $valuta = 'EURO';
          }
          elseif ($order->valuta == 3) {
            $valuta = 'USD';
          }
        @endphp
        <tr class="products-tr">
            <th scope="row">{{$order->created_at}}</th>
            <td>{{$order->total}} {{$valuta}}</td>
            <td><a href="/order/{{$order->front_code}}">{{$url}}/order/{{$order->front_code}}</a></td>
            <td>
                @if($order->status == -1)
                    <button type="button" class="btn btn-danger">ჩაშლილი</button>
                @elseif($order->status == 0)
                    <button type="button" class="btn btn-primary">აქტიური</button>
                @elseif($order->status == 1)
                    <button type="button" class="btn btn-success">გადახდილი</button>
                @endif
            </td>
            <td>{{$order->description}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
