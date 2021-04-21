@extends('layouts.template')

@section('title', 'Mailteksten beheren')

@section('main')
    <div id="Message">

    </div>
    <h1>Mailteksten beheren</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Mailtype</th>
                <th>Mailtekst</th>
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
            $.getJSON('/Mailcontent/qryMailcontents').done(function (data) {
                // console.log('data', data);

                $('tbody').empty();

                $.each(data, function (key, value) {
                    let tr = `<tr>
                            <td class="column1">${value.mailtype}</td>
                            <td class="content">${value.content}</td>
                            <td data-id="${value.id}" data-type="${value.mailtype}">
                                        <a href="#!" class="btn-edit">
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
            let content = $(this).closest('tr').find('.content').text(); // To prevent <br>-tag from appearing in edit-form
            // Update the modal
            $('form').attr('action', `/financial_employee/mailcontent/${id}`);
            $('.modal-title').text(`Wijzig ${type}`);
            $('#mailcontent').val(content);
            $('#mailtype').val(type);
            $('input[name="_method"]').val('put');
            // Show the modal
            $('#modal-mailcontent').modal('show');
        });

        $('#modal-mailcontent form').submit(function (e) {
            // Don't submit the form
            e.preventDefault();
            // Get the action property (the URL to submit)
            let action = $(this).attr('action');
            // Serialize the form and send it as a parameter with the post
            let pars = $(this).serialize();
            // Post the data to the URL
            $.post(action, pars, 'json')
                .done(function (data) {
                    $('#Message').html(data);
                    // Hide the modal
                    $('#modal-mailcontent').modal('hide');
                    // Rebuild the table
                    loadTable();
                })
                .fail(function (e) {
                    // Loop over the e.responseJSON.errors array and create an ul list with all the error messages
                    let msg = '<p>Errors: <ul>';
                    $.each(e.responseJSON.errors, function (key, value) {
                        msg += `<li>${value}</li>`;
                    });
                    msg += '</ul></p>';
                    $('#Message').html(msg);
                });
        });
    </script>
@endsection

