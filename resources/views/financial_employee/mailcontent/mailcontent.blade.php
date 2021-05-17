@extends('layouts.template')

@section('title', 'Mailteksten beheren')

@section('main')
    <h1>Mailteksten beheren
        <a href="#!" id="help">
            <i class="fas fa-info-circle"></i>
        </a>
    </h1>
    <div id="Message">

    </div>
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
    @include('financial_employee.mailcontent.modal_help')
@endsection

@section('script_after')
    <script>
        $(document).ready(function () {
            loadTable();

            $(".close").click(function () {
                $('.modal').modal('hide');
            })

            $('.annuleren').click(function () {
                $('#modal-mailcontent').modal('hide');
            })
        });

        // load mailcontents with AJAX
        // buttons die er moeten zijn: afwijzing aanvraag, nieuwe user aangemaakt met wachtwoord in, nieuw paswoord aanvragen
        function loadTable() {
            $.getJSON('/Mailcontent/qryMailcontents').done(function (data) {

                $('tbody').empty();

                $.each(data.mailcontent, function (key, value) {
                    let tr = `<tr>
                            <td class="column1">${value.mailtype}</td>
                            <td class="content">${value.content}</td>
                            <td data-id="${value.id}" data-type="${value.mailtype}">
                                        <a href="#!" class="btn-edit" data-toggle="tooltip" title="Wijzig de mail voor ${value.mailtype}">
                                            <i class="fas fa-edit"></i>
                                        </a></td>`;
                    $('tbody').append(tr);
                })
            })
        }

        $('tbody').on('click', '.btn-edit', function () {
            // Get data attributes from td tag
            let id = $(this).closest('td').data('id');
            let type = $(this).closest('td').data('type');
            let content = $(this).closest('tr').find('.content').text(); // To prevent <br>-tag from appearing in edit-form
            // Update the modal
            $('form').attr('action', `/Mailcontent/${id}`);
            $('.modal-title').text(`Wijzig ${type}`);
            $('#mailcontent').val(content);
            $('#mailtype').val(type);
            $('input[name="_method"]').val('put');
            // Show the modal
            $('#modal-mailcontent').modal('show');
        });

        $('body').on('click', '#help', function () {
            $('.modal-title').text("Gebruik van 'variabelen'");
            // Show the modal
            $('#modal-mailhelp').modal('show');
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
                    let notification = new Noty({
                        type: data.kind,
                        text: data.text,
                        layout: "topRight",
                        timeout: 5000,
                        progressBar: true,
                        modal: false
                    }).show();
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

