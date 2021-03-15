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
    @include('financial_employee.mailcontent.modal')
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
                // console.log('data', data);

                $('tbody').empty();

                $.each(data, function (key, value) {
                    let tr = `<tr>
                            <td class="column1">${value.mailtype}</td>
                            <td>${value.content}</td>
                            <td data-id="${value.id}" data-type="${value.mailtype}" data-content="${value.content}">
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

        $('tbody').on('click', '.btn-edit', function () {
            // Get data attributes from td tag
            let id = $(this).closest('td').data('id');
            let type = $(this).closest('td').data('type');
            let content = $(this).closest('td').data('content');
            // Update the modal
            $('.modal-title').text(`Edit ${type}`);
            $('form').attr('action', `/financial_employee/Mailcontent/${id}`);
            $('#mailcontent').val(content);
            $('input[name="_method"]').val('put');
            // Show the modal
            $('#modal-mailcontent').modal('show');
        });

        $('#modal-mailcontent form').submit(function (e) {
            // Don't submit the form
            e.preventDefault();
            // Get the action property (the URL to submit)
            let action = $(this).attr('action');
            console.log(action, 'actionnpm')
            // Serialize the form and send it as a parameter with the post
            let pars = $(this).serialize();
            console.log(pars);
            // Post the data to the URL
            $.post(action, pars, 'json')
                .done(function (data) {
                    console.log(data);
                    // Hide the modal
                    $('#modal-mailcontent').modal('hide');
                    // Rebuild the table
                    loadTable();
                })
                .fail(function (e) {
                    console.log('error', e);
                    console.log('log')
                    // e.responseJSON.errors contains an array of all the validation errors
                    console.log('error message', e.responseJSON.errors);
                    // Loop over the e.responseJSON.errors array and create an ul list with all the error messages
                    let msg = '<ul>';
                    $.each(e.responseJSON.errors, function (key, value) {
                        msg += `<li>${value}</li>`;
                    });
                    msg += '</ul>';
                    // // show the errors
                    // VinylShop.toast({
                    //     type: 'error',
                    //     text: msg
                    // });
                });
        });
    </script>
@endsection

