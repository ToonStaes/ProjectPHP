@extends('layouts.template')

@section('title', 'Mijn aanvragen')
@section('extra_css')
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

        let table = $('#mijnAanvragen').DataTable({
            "columns": [
                {"name": "Aanvraagdatum", "orderable": true},
                {"name": "Datum beoordeling", "orderable": true},
                {"name": "Datum terugbetaling", "orderable": true},
                {"name": "Naam kostenplaats", "orderable": true},
                {"name": "Beschrijving", "orderable": true},
                {"name": "Status Kostenplaatsverantwoordelijke", "orderable": true},
                {"name": "Status Financieel Medewerker", "orderable": true},
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
                    table.clear();
                    // diverse reimbursements
                    $.each(data.diverse_requests, function (key, value) {
                        let request_date = value.request_date;
                        let cost_center_name = value.cost_center.name;

                        $.each(value.diverse_reimbursement_lines, function (key, value) {
                            let beschrijving = value.description;
                            let statusFE = value.status_fe.name;
                            let statusCCM = value.status_cc_manager.name;
                            let review_date_Cost_center_manager = value.review_date_Cost_center_manager;
                            if (review_date_Cost_center_manager == null) {
                                review_date_Cost_center_manager = 'TBD'
                            }
                            let review_date_Financial_employee = value.review_date_Financial_employee;
                            if (review_date_Financial_employee == null) {
                                review_date_Financial_employee = 'TBD'
                            }
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center_name,
                                beschrijving,
                                statusCCM,
                                statusFE
                            ]).draw(false);
                        })
                    });

                    // laptop reimbursements
                    $.each(data.laptop_requests, function (key, value) {
                        let request_date = value.laptop_invoice.purchase_date;
                        let review_date_Cost_center_manager = value.review_date_Cost_center_manager;
                        if (review_date_Cost_center_manager == null) {
                            review_date_Cost_center_manager = 'TBD'
                        }
                        let review_date_Financial_employee = value.review_date_Financial_employee;
                        if (review_date_Financial_employee == null) {
                            review_date_Financial_employee = 'TBD'
                        }
                        let cost_center = '';
                        $.each(value.laptop_reimbursement_parameters, function (key2, value2) {
                            if (value2.parameter.standard_Cost_center_id != null){
                                cost_center = value2.parameter.cost_center_name;
                            }
                        })
                        let description = value.laptop_invoice.invoice_description;
                        let statusFE = value.status_fe.name;
                        let statusCCM = value.status_cc_manager.name;
                        table.row.add([
                            request_date,
                            review_date_Cost_center_manager,
                            review_date_Financial_employee,
                            cost_center,
                            description,
                            statusCCM,
                            statusFE
                        ]).draw(false);
                    });

                    // bike reimbursements
                    $.each(data.bike_requests, function (key, value) {
                        let request_date = value.request_date;
                        let review_date_Cost_center_manager = 'NVT';
                        let review_date_Financial_employee = value.review_date_Financial_employee;
                        if (review_date_Financial_employee == null) {
                            review_date_Financial_employee = 'TBD'
                        }
                        let cost_center = '';
                        $.each(value.bike_reimbursement_parameters, function (key2, value2) {
                            if (value2.parameter.standard_Cost_center_id != null){
                                cost_center = value2.parameter.cost_center.name;
                            }
                            else{
                                cost_center = 'niet in database'
                            }
                        })
                        let description = value.name;
                        let statusFE = 'niet gelinkt';
                        let statusCCM = 'NVT';
                        table.row.add([
                            request_date,
                            review_date_Cost_center_manager,
                            review_date_Financial_employee,
                            cost_center,
                            description,
                            statusCCM,
                            statusFE
                        ]).draw(false)
                    })
                })


        }

        // bootstrap tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

@endsection
