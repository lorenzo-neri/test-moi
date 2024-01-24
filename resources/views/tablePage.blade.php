@extends('layouts.app')

@section('content')
    <div class="p-5">
        {{--  @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif --}}

        {{-- @if (!empty($csvData)) --}}

        <form {{-- action="{{ route('salva_dati') }}" --}} method="post">
            @csrf
            <div class="p-5">
                @for ($i = 0; $i < count($headers); $i++)
                    {{-- <label for="colonna{{ $i + 1 }}">Seleziona Colonna {{ $i + 1 }}:</label> --}}
                    <select name="colonna{{ $i + 1 }}" id="colonna{{ $i + 1 }}" class="">
                        @foreach ($headers as $header)
                            <option value="{{ $header }}">{{ $header }}</option>
                        @endforeach
                    </select>
                @endfor
            </div>


            <button type="submit" class=" mb-3 btn btn-primary">Invia al Database</button>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        @foreach ($csvData[0] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i < min(11, count($csvData)); $i++)
                        <tr>
                            @foreach ($csvData[$i] as $cellData)
                                <td>{{ $cellData }}</td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>


        </form>
        {{--  @endif --}}
    </div>
@endsection
