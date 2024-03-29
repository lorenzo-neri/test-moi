@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="fs-4 text-secondary my-4">
            {{ __('Dashboard') }}
        </h2>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">{{ __('User Dashboard') }}</div>

                    <div class="card-body">
                        {{-- @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif --}}

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
            <div class="row py-5">
                <div class="col">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="card">

                        <form action="/upload" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file">
                            <button type="submit">Carica</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
