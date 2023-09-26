@extends('admin.index')
@section('content')
<div class="d-flex">
<div class="table-lang mr-5">
<h3>სამაგალითო ენა</h3>
    <table class="table" style="width: 200px;  height:400px; overflow-h:auto;">
        <thead>
            <tr>
                <th scope="col">key</th>
                <th scope="col">value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eng as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="table-lang ">
    <h3>არჩეული ენა</h3>
    <table class="table" style="width: 200px;  height:400px; overflow-h:auto;">
        <thead>
            <tr>
                <th scope="col">key</th>
                <th scope="col">value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($languageVariablesValue as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>



<form action="../admin/language-update" method="POST">
    @csrf
    <div class="dropdown ml-2">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Config::get('app.locale') }}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="../locale/en">en</a>
          <a class="dropdown-item" href="../locale/ge">ge</a>
          <a class="dropdown-item" href="../locale/az">az</a>

          <a class="dropdown-item" href="../locale/am">arm</a>
          <a class="dropdown-item" href="../locale/de">ger</a>
          <a class="dropdown-item" href="../locale/kz">kz</a>
          <a class="dropdown-item" href="../locale/ru">ru</a>
          <a class="dropdown-item" href="../locale/tj">tj</a>
          <a class="dropdown-item" href="../locale/tr">tr</a>
          <a class="dropdown-item" href="../locale/ua">ua</a>
          <a class="dropdown-item" href="../locale/uz">uz</a>
        </div>
    </div>
    <div class="dropdown mt-3 mb-3">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="langDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            აირჩიეთ ცვლადი
        </button>
        <div class="dropdown-menu" aria-labelledby="langDropdown">
            @foreach ($languageVariables as $variable)
            <li class="option save-li" id="option_{{$variable}}" value="{{$variable}}">{{$variable}}</li>
            <script>
                $("#option_{{$variable}}").click(function() {
                    var id = "{{$variable}}";
                    console.log(id)
                    $.ajax({
                        type: 'get',
                        url: '{{ url("/getvalue") }}',
                        data: 'id=' + id,
                        success: function(response) {
                            console.log(response)
                            $('#value').val(response);
                            $('#key').val(id);

                            $("#langDropdown").empty();
                            $("#langDropdown").append(id);


                        }
                    })
                })
            </script>
            @endforeach

        </div>
    </div>
    <br>
    <div class="form-group">
        <input type="text" name="value" id="value" class="form-control" placeholder="Enter email">
        <input type="hidden" name="key" id="key" class="form-control" placeholder="Enter email">

    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        padding: 12px 16px;
        z-index: 1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
@stop