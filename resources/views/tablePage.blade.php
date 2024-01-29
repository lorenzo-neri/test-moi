@extends('layouts.app')

@section('content')
    <div class="">
        {{--  @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif --}}

        {{-- @if (!empty($csvData)) --}}

        <form {{-- action="{{ route('salva_dati') }}" method="post" --}} id="uploadForm" enctype="multipart/form-data">
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

            <button onclick="inviaAlDatabase()" type="button" class=" mb-3 btn btn-primary">Invia al Database</button>
        </form>





        <table class="table table-bordered table-responsive ">
            <thead>
                <tr>
                    @foreach ($headers as $header)
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


        {{--  @endif --}}
    </div>


    <script>
        function inviaAlDatabase() {
            const selectValues = [];

            // Itera su tutti gli elementi di select nel form
            const selects = document.querySelectorAll('select');
            selects.forEach(function(select) {
                selectValues.push(select.value);
            });

            // Ora 'selectValues' contiene i valori selezionati di tutti gli elementi di select
            console.log('prima di axios', selectValues);

            // Invia i dati al controller tramite Axios
            axios.post('/salva_dati', {
                    selectValues
                })
                .then(response => {
                    console.log('Dati inviati con successo!');
                    console.log(selectValues);

                })
            /* .catch(error => {
                alert("Errore durante l'invio dei dati.");
            }); */

        }
    </script>
@endsection
