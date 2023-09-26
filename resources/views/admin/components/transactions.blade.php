@extends('admin.index')
@section('content')
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">შექმნის თარიღი</th>
        <th scope="col">ტრანზაქციის კოდი</th>
        <th scope="col">ფასი</th>
        <th scope="col">სახელი</th>
        <th scope="col">სტატუსი</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($transactions as $order)
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
            <th scope="row">{{$order->pay_id}}</th>

            <td>{{$order->total}} {{$valuta}}</td>
            <td>{{$order->full_name}}</td>
            <td>
                @if($order->transaction_status == -1)
                    <button type="button" class="btn btn-danger">ჩაშლილი</button>
                @elseif($order->transaction_status == 0)
                    <button type="button" class="btn btn-primary">აქტიური</button>
                @elseif($order->transaction_status == 1)
                    <button type="button" class="btn btn-success">გადახდილი</button>
                @endif
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
