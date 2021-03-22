@extends('layouts.template')

@section('title', 'Mijn aanvragen')
@section('extra_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endsection

@section('main')
    <div class="container">
        <table id="mijnAanvragen" class="table">
            <thead>
            <tr>
                <th>Aanvraagdatum</th>
                <th>Datum beoordeling</th>
                <th>Datum terugbetaling</th>
                <th>Naam kostenplaats</th>
                <th>Beschrijving</th>
                <th>Status Kostenplaatsverantwoordelijke</th>
                <th>Status Financieel Medewerker</th>
            </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('script_after')
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            buildTable();
        })

        let table = $('#requestsTable').DataTable({
            "columns": [
                {"name": "Aanvraagdatum", "orderable": true},
                {"name": "Datum beoordeling", "orderable": true},
                {"name": "Datum terugbetaling", "orderable": true},
                {"name": "Naam kostenplaats", "orderable": true},
                {"name": "Beschrijving", "orderable": true},
                {"name": "Status", "orderable": true},
            ],
            "language": {
                "lengthMenu": "_MENU_ aanvragen per pagina",
                "zeroRecords": "Er zijn geen aanvragen gevonden",
                "info": "Aanvragen _START_ tot _END_ van _TOTAL_",
                "infoEmpty": "",
                "infoFiltered": "(gefilterd uit _MAX_ aanvragen)",
                "search": "Filteren:",
                "paginate": {
                    "next": "Volgende",
                    "previous": "Vorige",
                    "first": "Eerste",
                    "last": "Laatste"
                }
            }
        });

        function buildTable() {
            $.getJSON('/user/mijnaanvragen/qryRequests')
                .done(function (data) {
                    console.log('data', data);
                    table.clear()
                    $.each(data.diverse_requests, function (key, value) {
                        let request_date = value.request_date;
                        let review_date_Cost_center_manager = value.review_date_Cost_center_manager;
                        let cost_center_name = value.cost_center.name;

                        $.each(value.diverse_reimbursement_lines, function (key, value) {
                            let beschrijving = value.desription;
                            let statusFE = value.status_FE;
                            let statusCCM = value.status_CC_Manager;
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                cost_center_name,
                                beschrijving,
                                statusCCM,
                                statusFE
                            ]).draw(false);
                        })
                    });
                })


        }

    </script>

@endsection
