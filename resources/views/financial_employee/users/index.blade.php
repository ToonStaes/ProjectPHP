@extends('layouts.template')
@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
@endsection

@section('main')
    <div class="messages"></div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#gebruiker_toevoegen" id="gebruiker_toevoegen_knop">
        Gebruiker toevoegen
    </button>


    <!-- Modal -->
    <div class="modal fade" id="gebruiker_toevoegen" tabindex="-1" role="dialog" aria-labelledby="gebruiker_toevoegenLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gebruiker_toevoegenLabel">Gebruiker toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/users" method="post">
                    @method('post')
                    @csrf
                    @include('financial_employee.users.form')
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gebruiker_bewerken" tabindex="-1" role="dialog" aria-labelledby="gebruiker_bewerkenLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gebruiker_bewerkenLabel">Gebruiker bewerken</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    @method('put')
                    @csrf
                    @include('financial_employee.users.form')
                </form>
            </div>
        </div>
    </div>

    <div id="tabel">
        <table id="usersTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Adres</th>
                <th>IBAN</th>
                <th>Email</th>
                <th>Telefoonnummer</th>
                <th>Aantal km</th>
                <th>Opleiding(en)</th>
                <th>Actief</th>
                <th>Kostenplaats&#8203;verantwoordelijke</th>
                <th>Financieel medewerker</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('script_after')
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
        let table = $('#usersTable').DataTable({
            "columns": [
                {"name": "ID", "orderable": true},
                {"name": "Naam", "orderable": true},
                {"name": "Adres", "orderable": true},
                {"name": "IBAN", "orderable": true},
                {"name": "Email", "orderable": true},
                {"name": "Telefoonnummer", "orderable": true},
                {"name": "AantalKM", "orderable": true},
                {"name": "Opleiding(en)", "orderable": true},
                {"name": "Geactiveerd", "orderable": true},
                {"name": "Kostenplaats", "orderable": true},
                {"name": "Financieel", "orderable": true},
                {"name": "Acties", "orderable": false},
            ],
            "language": {
                "lengthMenu": "_MENU_ gebruikers per pagina",
                "zeroRecords": "Er zijn geen gebruikers gevonden",
                "info": "Gebruikers _START_ tot _END_ van _TOTAL_",
                "infoEmpty": "",
                "infoFiltered": "(gefilterd uit _MAX_ gebruikers)",
                "search": "Filteren:",
                "paginate": {
                    "next": "Volgende",
                    "previous": "Vorige",
                    "first": "Eerste",
                    "last": "Laatste"
                }
            }
        });

        $(document).ready( function () {
            buildTable();
            getProgrammes();

            $('tbody').on('click', '.btn-delete', function () {
                let id = $(this).data('id');
                deleteUser(id);
            });

            $('#gebruiker_toevoegen form').submit(function (e) {
                // Don't submit the form
                e.preventDefault();

                let action = $(this).attr('action');
                let pars = $(this).serialize();

                let opleidingen = [];
                // Opleidingen ophalen
                $("#gebruiker_toevoegen .geselecteerde_opleidingen li").each(function () {
                    opleidingen.push($(this).data('id'));
                })

                pars += '&opleidingen=' + opleidingen;
                console.log(pars);
                $.post(action, pars, 'json')
                    .done(function (data) {
                        console.log(data);
                        // Hide the modal
                        $('#gebruiker_toevoegen').modal('hide');
                        $("div.messages").html(data);
                        // Rebuild the table
                        buildTable();
                    })
                    .fail(function (data) {
                        let errors = JSON.parse(data.responseText).errors;
                        $.each(errors, function (key, val) {
                            $("#" + key + "_error").text(val);
                        });
                    });
            });

            $('#gebruiker_bewerken form').submit(function (e) {
                // Don't submit the form
                e.preventDefault();

                let action = $(this).attr('action');
                let pars = $(this).serialize();
                let opleidingen = [];
                // Opleidingen ophalen
                $('#gebruiker_bewerken form .geselecteerde_opleidingen li').each(function () {
                    opleidingen.push($(this).data('id'));
                })

                pars += '&opleidingen=' + opleidingen;
                console.log(pars);
                $.post(action, pars, 'json')
                    .done(function (data) {
                        console.log(data);
                        // Hide the modal
                        $('#gebruiker_bewerken').modal('hide');
                        $("div.messages").html(data);
                        // Rebuild the table
                        buildTable();
                    })
                    .fail(function (data) {
                        let errors = JSON.parse(data.responseText).errors;
                        $.each(errors, function (key, val) {
                            $("#" + key + "_error").text(val);
                        });
                    });
            });

            $('tbody').on('click', '.btn-edit', function () {
                let id = $(this).data('id');
                editUser(id);
            });

            $(".opleiding_zoek").keyup(function () {
                let filter = $(this).val();
                console.log(filter);
                getProgrammes(filter);
            });

            $(".opleiding_toevoegen").click(function (e) {
                e.preventDefault();

                let id = $(this).parent().prev('.opleidingen').find("option:selected").val();
                let name = $(this).parent().prev('.opleidingen').find("option:selected").text();

                let opleidingen = [];
                $(this).parent().parent().find('.geselecteerde_opleidingen li').each(function () {
                    opleidingen.push($(this).data('id'));
                })

                console.log("Name", name);
                console.log("Id", id);
                console.log("Opleidingen", opleidingen);

                if (jQuery.inArray(parseInt(id), opleidingen) === -1) {
                    $(".geselecteerde_opleidingen").append(`<li data-id="${id}"><a href="#!" class="verwijder-li-opleiding"><i class="fas fa-minus-square"></i></a> ${name}</li>`);
                }
            });

            $("#gebruiker_toevoegen_knop").click(function () {
                $(".geselecteerde_opleidingen").empty();
                $("#gebruiker_toevoegen form")[0].reset();
            })

            $('.geselecteerde_opleidingen').on('click', '.verwijder-li-opleiding', function () {
                $(this).parent().remove();
            });

        } );

        function buildTable() {

            $.getJSON('/users/getUsers')
                .done(function (data) {
                    console.log('data', data);
                    // Clear tbody tag
                    table.clear();
                    $.each(data, function (key, value) {
                        let is_active = '';
                        if (value.is_active) {
                            is_active = '<i class="fas fa-check"></i>';
                        }

                        let isCost_Center_manager = '';
                        if (value.isCost_Center_manager){
                            isCost_Center_manager = '<i class="fas fa-check"></i>'
                        }

                        let isFinancial_employee = '';
                        if (value.isFinancial_employee){
                            isFinancial_employee = '<i class="fas fa-check"></i>'
                        }

                        let opleidingen = '';
                        if (value.user_programmes.length === 0){
                            opleidingen = 'N.V.T'
                        } else {
                            $.each(value.user_programmes, function (key, value) {
                                opleidingen += value.programme.name + '\n';
                            })
                        }
                        table.row.add([
                            value.id,
                            value.name,
                            value.address,
                            value.IBAN,
                            `<span class="email_table">${value.email}</span>`,
                            value.phone_number,
                            value.number_of_km,
                            opleidingen,
                            is_active,
                            isCost_Center_manager,
                            isFinancial_employee,
                            `<a href="#!" class="btn-edit" data-id="${value.id}"><i class="fas fa-edit"></i></a> <a href="#!" class="btn-delete" data-id="${value.id}"><i class="fas fa-trash-alt"></i></a>`
                        ]).draw(false);
                    });
                })
                .fail(function (e) {
                    console.log('error', e);
                })
        }

        function deleteUser(id) {
            let pars = {
                '_token': '{{ csrf_token() }}',
                '_method': 'delete'
            };
            $.post(`/users/${id}`, pars, 'json')
                .done(function (data) {
                    console.log('message', data);
                    $("div.messages").html(data);
                    // Rebuild the table
                    buildTable();
                })
                .fail(function (e) {
                    console.log('error', e);
                });
        }

        function editUser(id) {
            $.getJSON(`/users/${id}`)
                .done(function (data) {
                    console.log('data', data);
                    $('#gebruiker_bewerken input[name="voornaam"]').val(data.first_name);
                    $('#gebruiker_bewerken input[name="achternaam"]').val(data.last_name);
                    $('#gebruiker_bewerken input[name="adres"]').val(data.address);
                    $('#gebruiker_bewerken input[name="postcode"]').val(data.zip_code);
                    $('#gebruiker_bewerken input[name="woonplaats"]').val(data.city);
                    $('#gebruiker_bewerken input[name="iban"]').val(data.IBAN);
                    $('#gebruiker_bewerken input[name="email"]').val(data.email);
                    $('#gebruiker_bewerken input[name="telefoonnummer"]').val(data.phone_number);
                    $('#gebruiker_bewerken input[name="aantal_km"]').val(data.number_of_km);

                    if(data.is_active){
                        $('#gebruiker_bewerken input[name="actief"]').prop( "checked", true );
                    }

                    if(data.isCost_Center_manager){
                        $('#gebruiker_bewerken input[name="kostenplaats_verantwoordelijke"]').prop( "checked", true );
                    }

                    if(data.isFinancial_employee){
                        $('#gebruiker_bewerken input[name="financieel_medewerker"]').prop( "checked", true );
                    }

                    if (data.user_programmes.length !== 0){
                        $(".geselecteerde_opleidingen").empty();
                        $.each(data.user_programmes, function (key, value) {
                            console.log(value.programme.name);
                            $(".geselecteerde_opleidingen").append(`<li data-id="${value.programme.id}"><a href="#!" class="verwijder-li-opleiding"><i class="fas fa-minus-square"></i></a> ${value.programme.name}</li>`);
                        })
                    }

                    $('#gebruiker_bewerken form').attr('action', "/users/" + data.id);
                    $('#gebruiker_bewerken').modal('show');
                })
                .fail(function (e) {
                    console.log('error', e);
                })
        }

        function getProgrammes(filter) {
            let pars = {
                '_token': '{{ csrf_token() }}',
                '_method': 'get',
                'filter': filter
            };

            $.post(`/users/getProgrammes`, pars, 'json')
                .done(function (data) {
                    console.log('data', data);

                    let select = $(".opleidingen_select");
                    select.empty();
                    $.each(data, function (key, value) {
                        select.append(`<option value="${value.id}">
                                       ${value.name}
                                  </option>`);
                    });
                })
                .fail(function (e) {
                    console.log('error', e);
                });
        }
    </script>
@endsection
