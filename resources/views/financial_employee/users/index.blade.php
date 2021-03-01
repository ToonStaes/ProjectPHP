<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <title>Users</title>
</head>
<body>

<main class="container">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#gebruiker_toevoegen">
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
                {{--<th>Unit</th>--}}
                <th>Geactiveerd</th>
                <th>Kostenplaats verantwoordelijke</th>
                <th>Financieel medewerker</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<script src="{{ mix('js/app.js') }}"></script>
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
            {"name": "Geactiveerd", "orderable": true},
            {"name": "Kostenplaats", "orderable": true},
            {"name": "Financieel", "orderable": true},
            {"name": "Acties", "orderable": false},
        ]
    });

    $(document).ready( function () {
        buildTable();

        $('tbody').on('click', '.btn-delete', function () {
            let id = $(this).data('id');
            deleteUser(id);
        });

        $('#gebruiker_toevoegen form').submit(function (e) {
            // Don't submit the form
            e.preventDefault();

            let action = $(this).attr('action');
            let pars = $(this).serialize();
            console.log(pars);
            $.post(action, pars, 'json')
                .done(function (data) {
                    console.log(data);
                    // Hide the modal
                    $('#gebruiker_toevoegen').modal('hide');
                    // Rebuild the table
                    buildTable();
                })
                .fail(function (e) {
                    console.log('error', e);
                });
        });

        $('#gebruiker_bewerken form').submit(function (e) {
            // Don't submit the form
            e.preventDefault();

            let action = $(this).attr('action');
            let pars = $(this).serialize();
            console.log(pars);
            $.post(action, pars, 'json')
                .done(function (data) {
                    console.log(data);
                    // Hide the modal
                    $('#gebruiker_bewerken').modal('hide');
                    // Rebuild the table
                    buildTable();
                })
                .fail(function (e) {
                    console.log('error', e);
                });
        });

        $('tbody').on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            editUser(id);
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
                    table.row.add([
                        value.id,
                        value.name,
                        value.address,
                        value.IBAN,
                        value.email,
                        value.phone_number,
                        value.number_of_km,
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
                console.log('data', data);
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

                $('#gebruiker_bewerken form').attr('action', "/users/" + data.id);
                $('#gebruiker_bewerken').modal('show');
            })
            .fail(function (e) {
                console.log('error', e);
            })
    }
</script>
</body>
</html>
