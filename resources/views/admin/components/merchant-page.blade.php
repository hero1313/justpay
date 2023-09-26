@extends('admin.index')
@section('content')
    <div class="center merchant-div">
        <img class="merchant-img" src="../../assets/image/{{$merchant->profile_photo_path}}" alt="">
        <h6>{{$merchant->name}}</h6>
        <h6>{{$merchant->email}}</h6>
        <h6>შემოსავალი : {{$total_income}}</h6>
        <br>
        <h3 class="font-weight-bold mt-3 ">orders</h3>
        <br>
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
        <br>
        <h3 class="font-weight-bold mt-3 ">save products</h3>
        <br>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">ID</th>
                <th scope="col">IMAGE</th>
                <th scope="col">NAME</th>
                <th scope="col">PRICE</th>
                <th scope="col">DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="products-tr">
                    <th scope="row">{{$product->id}}</th>
                    <td><img src="../../assets/image/{{$product->image}}" alt=""></td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{$product->description}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@stop
