@extends('admin.index')

@section('content')
<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Number</th>
        <th scope="col">Identity number</th>
        <th scope="col">Bank Code</th>
        <th scope="col">Tbc</th>
        <th scope="col">Ipay</th>
        <th scope="col">Payze</th>
        <th scope="col">Stripe</th>
        <th scope="col">percent</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($merchants as $merchant)
        <tr class="products-tr">
            <th scope="row">{{$merchant->id}}</th>
            <td><a href="/admin/merchant/{{$merchant->id}}">{{$merchant->name}}</a></td>
            <td>{{$merchant->email}}</td>
            <td>{{$merchant->number}}</td>
            <td>{{$merchant->identity_number}}</td>
            <td>{{$merchant->bank_code}}</td>
            <td>{{$merchant->tbc}}</td>
            <td>{{$merchant->ipay}}</td>
            <td>{{$merchant->payze}}</td>
            <td>{{$merchant->stripe}}</td>
            <td>{{$merchant->percent}}%</td>
            <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>


  <!-- @foreach ($keys as $k)
        {{$k}}
      @endforeach -->
@stop
