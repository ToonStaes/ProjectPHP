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
        <tbody>
{{--            @foreach($users as $user)--}}
{{--                <tr>--}}
{{--                    <td>{{$user->userID}}</td>--}}
{{--                    <td>{{$user->name}}</td>--}}
{{--                    <td>{{$user->address}}</td>--}}
{{--                    <td>{{$user->IBAN}}</td>--}}
{{--                    <td>{{$user->email}}</td>--}}
{{--                    <td>{{$user->phone_number}}</td>--}}
{{--                    <td>{{$user->number_of_km}}</td>--}}
{{--                    <td>{{$user->isActive}}</td>--}}
{{--                    <td>{{$user->isCost_Center_manager}}</td>--}}
{{--                    <td>{{$user->isFinancial_employee}}</td>--}}
{{--                    <td>--}}
{{--                        <i class="fas fa-edit"></i>--}}
{{--                        <form action="/users/{{$user->userID}}" method="post">--}}
{{--                            @method('delete')--}}
{{--                            @csrf--}}
{{--                            <button type="submit" class="deleteUser"><i class="fas fa-trash-alt"></i></button>--}}
{{--                        </form>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
        </tbody>
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
    } );

    function buildTable() {

        $.getJSON('/users/getUsers')
            .done(function (data) {
                console.log('data', data);
                // Clear tbody tag
                table.clear();
                $.each(data, function (key, value) {
                    table.row.add([
                        value.userID,
                        value.name,
                        value.address,
                        value.IBAN,
                        value.email,
                        value.phone_number,
                        value.number_of_km,
                        value.is_active,
                        value.isCost_Center_manager,
                        value.isFinancial_employee,
                        `<a href="#!" class="btn-edit" data-id="${value.userID}"><i class="fas fa-edit"></i></a> <a href="#!" class="btn-delete" data-id="${value.userID}"><i class="fas fa-trash-alt"></i></a>`
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
                console.log("Ik ga nu de tabel opnieuw opbouwen");

                buildTable();
            })
            .fail(function (e) {
                console.log('error', e);
            });
    }
</script>
</body>
</html>
