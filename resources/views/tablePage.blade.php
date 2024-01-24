@extends('layouts.app')

@section('content')
    <div class="containe p-5">
        <div class="row justify-content-center">
            <div class="col">
                {!! $tableHtml !!}
            </div>
        </div>
    </div>
@endsection
