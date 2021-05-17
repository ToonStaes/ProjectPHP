@extends('layouts.template')
@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endsection

@section('main')
    <h1>Vergoedingen behandelen <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Op deze pagina kan u vergoedingen die goedgekeurd zijn door de kostenplaatsverantwoordelijke, af- en goedkeuren."></i></h1>
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
                        <h5 class="modal-title" id="commentaar">Commentaar</h5>
                        <button type="button" class="close" aria-label="Close">
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
                        <button type="button" class="btn btn-secondary annuleren">Annuleren</button>
                        <input type="submit" class="btn btn-primary" value="Opslaan">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="betaal-modal" tabindex="-1" role="dialog" aria-labelledby="betaal-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openstaandeBetalingen">Openstaande betalingen</h5>
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="openstaande_betalingen_modal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary annuleren">Annuleren</button>
                    <button type="button" class="btn btn-primary" id="betalen_knop">Betalen</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_after')
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
        /* Create an array with the values of all the select options in a column */
        $.fn.dataTable.ext.order['dom-select'] = function  ( settings, col )
        {
            return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
                return $('select', td).val();
            } );
        }

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
                {"name": "Status financieel medewerker", "orderable": true, "orderDataType": "dom-select"},
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
            },
            "order": [[8, "desc"], [7, "desc"]]
        });

        $(document).ready(function () {
            buildTable();

            $(".close").click(function () {
                $("#commentaar-modal").modal('hide');
                $("#betaal-modal").modal('hide');
            })

            $(".annuleren").click(function () {
                $("#commentaar-modal").modal('hide');
                $("#betaal-modal").modal('hide');
            })

            $("#openstaande_betalingen").click(function () {
                $("#betaal-modal").modal("show");
            })

            let previous = "";
            $("#requestsTable").on('focus', '.status-select', function () {
                previous = $(this).val();
            });

            $("#requestsTable").on('change', '.status-select', function () {
                if($(this).val() === "afgekeurd"){
                    $("#commentaar-modal").modal('show');
                    let id = $(this).data('id');
                    let type = $(this).data('type');
                    let keuring = $(this).val();

                    $("#commentaar-id").val(id);
                    $("#commentaar-type").val(type);
                    $("#commentaar-keuring").val(keuring);

                    $(this).val(previous);
                } else {
                    let action = "/financial_employee/saveComment";
                    let pars = {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'put',
                        'commentaar': null,
                        'type': $(this).data('type'),
                        'id': $(this).data('id'),
                        'keuring': $(this).val(),
                    };

                    $.post(action, pars, 'json')
                        .done(function (data) {
                            let notification = new Noty({
                                type: "success",
                                text: "Beoordeling opgeslagen",
                                layout: "topRight",
                                timeout: 2000,
                                progressBar: true,
                                modal: false
                            }).show();

                            buildTable();
                        });
                }
            });

            $("#commentaar-modal form").submit(function (e) {
                e.preventDefault();

                let action = $(this).attr('action');
                let pars = $(this).serialize();

                $.post(action, pars, 'json')
                    .done(function (data) {
                        $("#commentaar-modal").modal('hide');
                        $("#commentaar-modal form textarea").val("");

                        let notification = new Noty({
                            type: "success",
                            text: "Beoordeling opgeslagen",
                            layout: "topRight",
                            timeout: 2000,
                            progressBar: true,
                            modal: false
                        }).show();

                        buildTable();
                    });
            });

            $("#openstaande_betalingen").click(function () {
                buildModal();
            })

            $("#betalen_knop").click(function () {
                let pars = {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'post'
                };

                $.post("/financial_employee/payOpenPayments", pars)
                    .done(function (data) {
                        $("#betaal-modal").modal('hide');
                        let notification = new Noty({
                            type: "success",
                            text: data,
                            layout: "topRight",
                            timeout: 5000,
                            progressBar: true,
                            modal: false
                        }).show();
                        buildTable();
                });
            })
        })

        function buildTable() {

            $.getJSON('/financial_employee/getRequests')
                .done(function (data) {
                    // tbody leeg maken
                    table.clear();

                    $.each(data.diverse_requests, function (key, value) {

                        let request_date = value.request_date;
                        let cost_center_name = value.cost_center_name;
                        let user_name = value.username;

                        let select = '';
                        if (value.comment_Financial_employee == null){
                            value.comment_Financial_employee = "";
                            select = `<span data-toggle="tooltip" data-placement="top" title="${value.comment_Financial_employee}" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='divers'`;
                        } else {
                            select = `<span data-toggle="tooltip" data-placement="top" data-html="true" title="<p>Commentaar: ${value.comment_Financial_employee}</p><p>Datum: ${value.review_date_Financial_employee}</p><p>Door: ${value.fe_name}</p>" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='divers'`;
                        }

                        if (value.status_FE === "betaald" || value.status_FE === "afgekeurd"){
                            select += `disabled style="pointer-events: none;"`;
                        }
                        select += `>`;

                        let status_counter = 0;
                        $.each(data.statuses, function (key, value2) {
                            status_counter += 1;

                            if (value.status_FE === "betaald") {
                                select+= `<option selected>betaald</option>`;
                            }

                            if (status_counter <= 3){
                                select += `<option`;
                                if (value2.name === value.status_FE){
                                    select += ` selected`;
                                }
                                select +=  `>${value2.name}</option>`;
                            }
                        })
                        select += `</select></span>`;

                        let status_cc_manager = value.status_CC_manager;
                        if (value.review_date_Cost_center_manager != null){
                            status_cc_manager = `<nobr><p>${value.status_CC_manager} <i class="fas fa-info-circle" data-toggle="tooltip" data-html="true" data-placement="top" title="<p>Datum: ${value.review_date_Cost_center_manager}</p><p>Door: ${value.ccm_name}</p>"></i></p></nobr>`;
                        }

                        let evidence = '';
                        //Loopen over elke diverse aanvraag lijn
                        $.each(value.diverse_reimbursement_lines, function (key, value) {
                            //Alle bewijsstukken achter elkaar zetten
                            $.each(value.diverse_reimbursement_evidences, function (key2, value2) {
                                evidence += `<a class="btn btn-outline-dark" href="/storage/DiverseBewijzen/${value2.filepath}" download><nobr><img src='/assets/icons/file_icons/${value2.icon}' alt="file icon" width="25px"> ${value2.name.substring(13)}</nobr></a>`;
                            });
                        })

                        //Alle data toevoegen aan tabel
                        table.row.add([
                            request_date,
                            value.review_date_Financial_employee,
                            cost_center_name,
                            user_name,
                            value.description,
                            "€" + (value.amount).toFixed(2),
                            evidence,
                            status_cc_manager,
                            select
                        ]).draw(false);
                    });

                    $.each(data.laptop_requests, function (key, value) {
                        //Status dropdown maken
                        let select = '';
                        if (value.comment_Financial_employee == null){
                            value.comment_Financial_employee = "";
                            select = `<span data-toggle="tooltip" data-placement="top" title="${value.comment_Financial_employee}" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='laptop'`;
                        } else {
                            select = `<span data-toggle="tooltip" data-placement="top" data-html="true" title="<p>Commentaar: ${value.comment_Financial_employee}</p><p>Datum: ${value.review_date_Financial_employee}</p><p>Door: ${value.fe_name}</p>" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='laptop'`;
                        }

                        if (value.status_FE === "betaald" || value.status_FE === "afgekeurd"){
                            select += `disabled style="pointer-events: none;"`;
                        }
                        select += `>`;

                        let status_counter = 0;
                        $.each(data.statuses, function (key, value2) {
                            status_counter += 1;

                            if (value.status_FE === "betaald") {
                                select+= `<option selected>betaald</option>`;
                            }

                            if (status_counter <= 3){
                                select += `<option`;
                                if (value2.name === value.status_FE){
                                    select += ` selected`;
                                }
                                select +=  `>${value2.name}</option>`;
                            }
                        })
                        select += `</select></span>`;

                        let request_date = value.laptop_invoice.updated_at;
                        if (request_date == null){
                            request_date = value.laptop_invoice.created_at;
                        }

                        let cost_center = '';
                        $.each(value.laptop_reimbursement_parameters, function (key2, value2) {
                            if (value2.parameter.standard_Cost_center_id != null){
                                cost_center = value2.parameter.cost_center_name;
                            }
                        })
                        let user_name = value.laptop_invoice.username;

                        let status_cc_manager = value.status_CC_manager;
                        if (value.review_date_Cost_center_manager != null){
                            status_cc_manager = `<nobr><p>${value.status_CC_manager} <i class="fas fa-info-circle" data-toggle="tooltip" data-html="true" data-placement="top" title="<p>Datum: ${value.review_date_Cost_center_manager}</p><p>Door: ${value.ccm_name}</p>"></i></p></nobr>`;
                        }

                        let evidence = `<a class="btn btn-outline-dark" href="/storage/LaptopBewijzen/${value.laptop_invoice.filepath}" download><nobr><img src='/assets/icons/file_icons/${value.laptop_invoice.file_icon}' alt="file icon" width="25px"> ${value.laptop_invoice.file_name.substring(13)}</nobr></a>`;
                        table.row.add([
                            request_date,
                            value.review_date_Financial_employee,
                            cost_center,
                            user_name,
                            value.laptop_invoice.invoice_description,
                            "€" + (value.laptop_invoice.amount).toFixed(2),
                            evidence,
                            status_cc_manager,
                            select
                        ]).draw(false);
                    });

                    $.each(data.bike_reimbursements, function (key, value) {
                        //Status dropdown maken
                        let select = '';
                        if (value.comment_Financial_employee == null){
                            value.comment_Financial_employee = "";
                            select = `<span data-toggle="tooltip" data-placement="top" title="${value.comment_Financial_employee}" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='fiets'`;
                        } else {
                            select = `<span data-toggle="tooltip" data-placement="top" data-html="true" title="<p>Commentaar: ${value.comment_Financial_employee}</p><p>Datum: ${value.review_date_Financial_employee}</p><p>Door: ${value.fe_name}</p>" class="d-inline-block" tabindex="0"><select class="form-control w-auto status-select" data-id='${value.id}' data-type='fiets'`;
                        }

                        if (value.status_FE === "betaald" || value.status_FE === "afgekeurd"){
                            select += `disabled style="pointer-events: none;"`;
                        }
                        select += `>`;

                        let status_counter = 0;
                        $.each(data.statuses, function (key, value2) {
                            status_counter += 1;

                            if (value.status_FE === "betaald") {
                                select+= `<option selected>betaald</option>`;
                            }

                            if (status_counter <= 3){
                                select += `<option`;
                                if (value2.name === value.status_FE){
                                    select += ` selected`;
                                }
                                select +=  `>${value2.name}</option>`;
                            }
                        })
                        select += `</select></span>`;;

                        table.row.add([
                            value.request_date,
                            value.review_date_Financial_employee,
                            value.costcenter,
                            value.username,
                            value.name,
                            "€" + value.amount,
                            "Fietsvergoeding",
                            "",
                            select
                        ]).draw(false);
                    });

                    $("#openstaande_betalingen").text(`Openstaande betalingen uitbetalen (€${(data.total_open_payments).toFixed(2)})`);
                    makeTooltipsVisible();
                });
        }

        function makeTooltipsVisible() {
            $('[data-toggle="tooltip"]').tooltip({html:true});
        }

        function buildModal(){
            //Modal leegmaken voor alles er wordt aan toegevoegd
            $("#openstaande_betalingen_modal").html("");

            $.getJSON('/financial_employee/getOpenPayments')
                .done(function (data) {
                    $.each(data.diverse_requests, function (key, val) {
                        $("#openstaande_betalingen_modal").append("<p>" + val.username + " (" + val.iban + ") - €" + val.amount + ": " + val.description +"</p>")
                    });
                    $.each(data.laptop_requests, function (key, val) {
                        $("#openstaande_betalingen_modal").append("<p>" + val.laptop_invoice.username + " (" + val.laptop_invoice.iban + ") - €" + val.laptop_invoice.amount + ": " + val.laptop_invoice.invoice_description +"</p>")
                    });
                    $.each(data.bike_reimbursements, function (key, val) {
                        $("#openstaande_betalingen_modal").append("<p>" + val.username + " (" + val.iban + ") - €" + val.amount + ": " + val.name +"</p>")
                    });
                });
        }
    </script>
@endsection
