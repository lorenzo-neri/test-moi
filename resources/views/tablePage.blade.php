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
            const dataValues = {};

            // Itera su tutti gli elementi di select nel form
            const selects = document.querySelectorAll('select');
            selects.forEach(function(select) {
                const columnName = select.value;
                selectValues.push(columnName);
                const columnIndex = headers.indexOf(columnName);
                dataValues[columnName] = csvData[1][columnIndex]; // Assuming data is in the second row
            });

            // Ora 'selectValues' contiene i nomi delle colonne selezionate
            // 'dataValues' contiene i dati corrispondenti a tali colonne
            console.log('selectValues', selectValues);
            console.log('dataValues', dataValues);

            // Invia i dati al controller tramite Axios
            axios.post('/salva_dati', {
                    selectValues,
                    dataValues
                })
                .then(response => {
                    console.log('Dati inviati con successo!');
                })
                .catch(error => {
                    alert("Errore durante l'invio dei dati.");
                });
        }
    </script>
@endsection
