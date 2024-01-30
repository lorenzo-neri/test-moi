@extends('layouts.app')

@section('content')
    <div class="">

        {{-- SELECT & BUTTON --}}
        <form {{-- action="{{ route('salva_dati') }}" method="post" --}} id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="p-5">
                @for ($i = 0; $i < count($columns); $i++)
                    <select name="colonna{{ $i + 1 }}" id="colonna{{ $i + 1 }}" class="">
                        @foreach ($columns as $column)
                            <option value="{{ $column }}">{{ $column }}</option>
                        @endforeach
                    </select>
                @endfor
            </div>

            <button onclick="inviaAlDatabase()" type="button" class=" mb-3 btn btn-primary">Invia al Database</button>
        </form>
        {{-- /SELECT & BUTTON --}}

        {{-- TABLE --}}
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
        {{-- /TABLE --}}

    </div>


    <script>
        function inviaAlDatabase() {
            const selectValues = [];
            // Itera su tutti gli elementi di select nel form
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
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
                .catch(error => {
                    alert("Errore durante l'invio dei dati.", error);
                });
        }
    </script>
@endsection
