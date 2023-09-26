@extends('admin.index')
@section('content')
    <div class="container py-3">
        @if ($errors->any())
            <div class="note note-danger mb-3">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <form method="post" action="{{ route('texts.store') }}">
            @csrf

            <div class="form-group">
                <label for="var">Variable</label>
                <input class="form-control" id="var" name="var" value="{{ old('var') }}">
            </div>

            <ul class="nav nav-tabs" role="tablist">
                @foreach (config('custom_vars.languages') as $val)
                    <li class="nav-item">
                        <a class="nav-link @if ($loop->first) active @endif" data-toggle="tab"
                            href="#tab_{{ $val }}" role="tab" aria-controls="tab_{{ $val }}"
                            aria-selected="true">
                            {{ $val }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content px-0 py-2">
                @foreach (config('custom_vars.languages') as $val)
                    <div class="tab-pane fade @if ($loop->first) show active @endif"
                        id="tab_{{ $val }}" role="tabpanel">
                        <div class="form-group mb-3">
                            <label for="{{ $val }}">
                                ({{ $val }})
                            </label>
                            <input type="text" class="form-control" name="{{ $val }}" id="{{ $val }}"
                                value="{{ old($val) }}" />
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-green btn-md">Save</button>
        </form>
    </div>
@endsection
