@extends('layouts.template')
@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
@endsection

@section('main')
    <div class="messages"></div>
    <p class="text-right"><button class="btn-primary mb-5" id="openstaande_betalingen">Openstaande betalingen uitbetalen (€)</button></p>

    <div id="tabel">
        <table id="requestsTable" class="table">
            <thead>
            <tr>
                <th>Aanvraag&#8203;datum</th>
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

    <div class="modal fade" id="commentaar-modal" tabindex="-1" role="dialog" aria-labelledby="Commentaar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="/financial_employee/saveComment">
                    @method("put")
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Commentaar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="commentaar">Commentaar</label>
                            <textarea name="commentaar" id="commentaar" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <input type="hidden" value="" name="type" id="commentaar-type">
                        <input type="hidden" value="" name="id" id="commentaar-id">
                        <input type="hidden" value="" name="keuring" id="commentaar-keuring">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Opslaan">
                    </div>
                </form>
            </div>
        </div>
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

            let previous = "";
            $("#requestsTable").on('focus', '.status-select', function () {
                previous = $(this).val();
            });

            $("#requestsTable").on('change', '.status-select', function () {
                $("#commentaar-modal").modal('show');
                let id = $(this).data('id');
                let type = $(this).data('type');
                let keuring = $(this).val();

                $("#commentaar-id").val(id);
                $("#commentaar-type").val(type);
                $("#commentaar-keuring").val(keuring);

                $(this).val(previous);
            });

            $("#commentaar-modal form").submit(function (e) {
                e.preventDefault();

                let action = $(this).attr('action');
                let pars = $(this).serialize();

                $.post(action, pars, 'json')
                    .done(function (data) {
                        console.log(data);
                        $("#commentaar-modal").modal('hide');
                        $("#commentaar-modal form textarea").val("");
                        buildTable();
                    })
                    .fail(function (data) {
                        console.log(data);
                    });
            });
        })

        function buildTable() {

            $.getJSON('/financial_employee/getRequests')
                .done(function (data) {
                    console.log('data', data);
                    // tbody leeg maken
                    table.clear();

                    $.each(data.diverse_requests, function (key, value) {

                        let request_date = value.request_date;
                        let cost_center_name = value.cost_center_name;
                        let user_name = value.username;

                        if (value.comment_Financial_employee == null){
                            value.comment_Financial_employee = "";
                        }

                        let select = `<span data-toggle="tooltip" data-placement="top" data-html="true" title="<p>Commentaar: ${value.comment_Financial_employee}</p><p>Datum: ${value.review_date_Financial_employee}</p><p>Door: ${value.fe_name}</p>" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='divers'`;
                        if (value.status_FE === "betaald"){
                            select += `disabled style="pointer-events: none;"`;
                        }
                        select += `>`;
                        $.each(data.statuses, function (key, value2) {
                            select += `<option`;
                            if (value2.name === value.status_FE){
                                select += ` selected`;
                            }
                            select +=  `>${value2.name}</option>`;
                        })
                        select += `</select></span>`;

                        let status_cc_manager = value.status_CC_manager;
                        if (value.comment_Cost_center_manager != null){
                            status_cc_manager = `<nobr><p>${value.status_CC_manager} <i class="fas fa-info-circle" data-toggle="tooltip" data-html="true" data-placement="top" title="<p>Commentaar: ${value.comment_Cost_center_manager}</p><p>Datum: ${value.review_date_Cost_center_manager}</p><p>Door: ${value.ccm_name}</p>"></i></p></nobr>`;
                        }

                        let evidence = '';
                        //Loopen over elke diverse aanvraag lijn
                        $.each(value.diverse_reimbursement_lines, function (key, value) {
                            //Alle bewijsstukken achter elkaar zetten
                            $.each(value.diverse_reimbursement_evidences, function (key2, value2) {
                                evidence += `<a class="btn btn-outline-dark" href="${value2.filepath}"><nobr><img src='/assets/icons/file_icons/${value2.icon}' alt="file icon" width="25px"> ${value2.name}</nobr></a>`;
                            });
                        })

                        //Alle data toevoegen aan tabel
                        table.row.add([
                            request_date,
                            value.review_date_Financial_employee,
                            cost_center_name,
                            user_name,
                            value.description,
                            "€" + value.amount,
                            evidence,
                            status_cc_manager,
                            select
                        ]).draw(false);
                    });

                    $.each(data.laptop_requests, function (key, value) {
                        //Status dropdown maken
                        if (value.comment_Financial_employee == null){
                            value.comment_Financial_employee = "";
                        }

                        let select = `<span data-toggle="tooltip" data-placement="top" data-html="true" title="<p>Commentaar: ${value.comment_Financial_employee}</p><p>Datum: ${value.review_date_Financial_employee}</p><p>Door: ${value.fe_name}</p>" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='laptop'`;
                        if (value.status_FE === "betaald" || value.status_FE === "afgekeurd"){
                            select += `disabled style="pointer-events: none;"`;
                        }
                        select += `>`;
                        $.each(data.statuses, function (key, value2) {
                            select += `<option`;
                            if (value2.name === value.status_FE){
                                select += ` selected`;
                            }
                            select +=  `>${value2.name}</option>`;
                        })
                        select += `</select></span>`;;

                        let request_date = value.laptop_invoice.purchase_date;
                        let cost_center = '';
                        $.each(value.laptop_reimbursement_parameters, function (key2, value2) {
                            if (value2.parameter.standard_Cost_center_id != null){
                                cost_center = value2.parameter.cost_center_name;
                            }
                        })
                        let user_name = value.laptop_invoice.user.name;

                        let status_cc_manager = value.status_CC_manager;
                        if (value.comment_Cost_center_manager != null){
                            status_cc_manager = `<nobr><p>${value.status_CC_manager} <i class="fas fa-info-circle" data-toggle="tooltip" data-html="true" data-placement="top" title="<p>Commentaar: ${value.comment_Cost_center_manager}</p><p>Datum: ${value.review_date_Cost_center_manager}</p><p>Door: ${value.ccm_name}</p>"></i></p></nobr>`;
                        }

                        let evidence = `<a class="btn btn-outline-dark" href="${value.laptop_invoice.filepath}"><nobr><img src='/assets/icons/file_icons/${value.laptop_invoice.file_icon}' alt="file icon" width="25px"> ${value.laptop_invoice.file_name}</nobr></a>`;
                        table.row.add([
                            request_date,
                            value.review_date_Financial_employee,
                            cost_center,
                            user_name,
                            value.laptop_invoice.invoice_description,
                            "€" + value.amount,
                            evidence,
                            status_cc_manager,
                            select
                        ]).draw(false);
                    });
                    $("#openstaande_betalingen").text(`Openstaande betalingen uitbetalen (€${data.total_open_payments})`);
                    makeTooltipsVisible();
                })
                .fail(function (e) {
                    console.log('error', e);
                });
        }

        function makeTooltipsVisible()
        {
            $('[data-toggle="tooltip"]').tooltip()
        }
    </script>
@endsection
