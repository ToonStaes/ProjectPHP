@extends('layouts.template')

@section('title', 'Mailtekst beheren')

@section('main')
    <h1>Mailtekst aanpassen</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Mailtype</th>
                <th>Mailcontent</th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('script_after')
    <script>
        $(document).ready(function () {
            loadTable();
        });

        // load mailcontents with AJAX
        // buttons die er moeten zijn: afwijzing aanvraag, nieuwe user aangemaakt met wachtwoord in, nieuw paswoord aanvragen
        function loadTable() {
            $.getJSON('/financial_employee/Mailcontent/qryMailcontents').done(function (data) {
                console.log('data', data);

                $('tbody').empty();

                $.each(data, function (key, value) {
                    let tr = `<tr>
                            <td class="column1">${value.mailtype}</td>
                            <td>${value.content}</td>
                            <td data-id="${value.id}" data-mailtype="${value.mailtype}">
                                        <a href="#!" class="btn btn-outline-success btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a></td>`;
                    $('tbody').append(tr);
                })
            })
            .fail(function (e) {
                console.log('error', e);
            })
        }

        // $('#buttons').on('click', '#Afwijzing', function () {
        //     console.log('button afwijzing geklikt');
        //     $('#form').empty().append();
        // })
        //
        // $('#buttons').on('click', '#Nieuweuser', function () {
        //     console.log('button "nieuwe user" geklikt');
        //     $('#form').empty().append();
        // })
        //
        // $('#buttons').on('click', '#Wachtwoordvergeten', function () {
        //     console.log('button "wachtwoord vergeten" geklikt');
        //     $('#form').empty().append();
        // })
    </script>
@endsection

