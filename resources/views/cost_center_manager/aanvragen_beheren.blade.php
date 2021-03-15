@extends('layouts.template')
@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
@endsection

@section('main')
    <div class="messages"></div>

    <div id="tabel">
        <table id="requestsTable" class="table">
            <thead>
            <tr>
                <th>Aanvraagdatum</th>
                <th>Datum beoordeling</th>
                <th>Kostenplaats</th>
                <th>Personeelslid</th>
                <th>Beschrijving</th>
                <th>Bedrag</th>
                <th>Bewijsstuk(en)</th>
                <th>Status</th>
                <th>Status financieel medewerker</th>
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
        let table = $('#requestsTable').DataTable({
            "columns": [
                {"name": "Aanvraagdatum", "orderable": true},
                {"name": "Datum beoordeling", "orderable": true},
                {"name": "Kostenplaats", "orderable": true},
                {"name": "Personeelslid", "orderable": true},
                {"name": "Beschrijving", "orderable": true},
                {"name": "Bedrag", "orderable": true},
                {"name": "Bewijsstuk(en)", "orderable": true},
                {"name": "Status", "orderable": true},
                {"name": "Status financieel medewerker", "orderable": true},
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

        $(document).ready(function () {
            buildTable();
        })

        function buildTable() {

            $.getJSON('/getRequests')
                .done(function (data) {
                    console.log('data', data);
                    // Clear tbody tag
                    table.clear();
                    $.each(data, function (key, value) {
                        let request_date = value.request_date;
                        let review_date_Cost_center_manager = value.review_date_Cost_center_manager;
                        let cost_center_name = value.cost_center.name;
                        let user_name = value.user.name;

                        $.each(value.diverse_reimbursement_lines, function (key, value) {
                            let evidence = '';
                            $.each(value.diverse_reimbursement_evidences, function (key2, value2) {
                                evidence += value2.filepath;
                            })
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                cost_center_name,
                                user_name,
                                value.description,
                                value.amount,
                                evidence,
                                "Status",
                                "Status FM"
                            ]).draw(false);
                        })
                    });
                })
                .fail(function (e) {
                    console.log('error', e);
                })
        }
    </script>
@endsection
