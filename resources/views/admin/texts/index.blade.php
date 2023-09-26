@extends('admin.index')
@section('content')
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center">
            <h3 class="text-center">Texts for main site</h3>
            <div>
                <a class="btn btn-primary btn-md" href="{{ route('texts.create') }}">Create New</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>Variable</th>
                        @foreach (config('custom_vars.languages') as $lang)
                            <th>{{ $lang }}</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->var }}</td>
                            @foreach (config('custom_vars.languages') as $lang)
                                <th>{{ $item->$lang }}</th>
                            @endforeach
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            $(document).on("change", ".form-check-input", function() {
                var checked = $(this).is(':checked');
                var tr = $(this).closest("tr");
                var tds = $(tr).find("td");
                var readonly = (checked ? false : true);
                $(tds).each(function(i, e) {
                    if (i == 0 || i == 1) {
                        return true;
                    } else {
                        $(e).find("input:text").prop('disabled', readonly);
                    }
                });

                if ($(".form-check-input:checked").length > 0) {
                    $(".submit-btn").show();
                } else {
                    $(".submit-btn").hide();
                }

            });
        });
    </script>
@endsection
