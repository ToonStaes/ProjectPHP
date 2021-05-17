@extends('layouts.template')

@section('title', 'Mijn aanvragen')
@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endsection

@section('main')
    <div id="Message">

    </div>
    <div class="container">
        <h1>Mijn aanvragen <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right"
                                          title="Op deze pagina vindt u een overzicht van alle door u ingediende aanvragen."></i>
        </h1>
        <table id="mijnAanvragen" class="table">
            <thead>
            <tr>
                <th>Aanvraagdatum</th>
                <th>Datum beoordeling kostenplaats-verantwoordelijke</th>
                <th>Datum terugbetaling</th>
                <th>Beschrijving</th>
                <th>Bedrag</th>
                <th>Status kostenplaatsverantwoordelijke</th>
                <th>Status Financieel Medewerker</th>
                <th>Aanvraag aanpassen</th>
            </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
    @include('user.MijnAanvragen.laptop_modal')
@endsection

@section('script_after')
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

    <script>
        @if (session()->has('success'))
        let success = new Noty({
            text: '{!! session()->get('success') !!}',
            type: 'success',
            layout: "topRight",
            timeout: 5000,
            progressBar: true,
            modal: false
        }).show();
        @endif
        @if (session()->has('danger'))
        let error = new Noty({
            text: '{!! session()->get('danger') !!}',
            type: 'error',
            layout: "topRight",
            timeout: 5000,
            progressBar: true,
            modal: false
        }).show();
        @endif
        $(document).ready(function () {
            buildTable();

            $(".close").click(function () {
                $('#modal-laptop').modal('hide');
            })

            $('.annuleren').click(function () {
                $('#modal-laptop').modal('hide');
            })

            $('#delete').click(function () {
                $(this).parent().addClass('d-none');
                $('#uploadFile').removeClass('d-none');
                $('#bestand').prop('required', true);
            })

            $('#modal-laptop').on('hidden.bs.modal', function () {
                $('#oldfile').removeClass('d-none');
                $('#uploadFile').addClass('d-none');
                $('#bestand').prop('required', false);
            })

            $('#modal-laptop form').submit(function (e) {
                // Don't submit the form
                e.preventDefault();
                let form = $('#modal-laptop form')[0];
                let formData = new FormData(form);

                // Get the action property (the URL to submit)
                let action = $(this).attr('action');

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: action,
                    data: formData,
                    cache: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,

                    success: function (data) {
                        // Hide the modal
                        $('#modal-laptop').modal('hide');
                        // Rebuild the table
                        buildTable();
                        // show noty
                        let notification = new Noty({
                            type: data.kind,
                            text: data.text,
                            layout: "topRight",
                            timeout: 5000,
                            progressBar: true,
                            modal: false
                        }).show();
                    },
                    error: function (error) {
                        let errors = JSON.parse(error.responseText).errors;
                        $.each(errors, function (key, val) {
                            $("#" + key + "_error").text(val);
                        });
                    },
                    fail: function (data) {
                        // show noty
                        let notification = new Noty({
                            type: error,
                            text: "Probeer opnieuw",
                            layout: "topRight",
                            timeout: 5000,
                            progressBar: true,
                            modal: false
                        }).show();

                    }
                });
            });
        })

        $('tbody').on('click', '.btn-edit', function () {
            if ($(this).hasClass('laptopvergoeding')) {
                // Get data attributes from td tag
                let id = $(this).data('id');
                let amount = $(this).data('amount');
                let purchaseDate = $(this).data('purchasedate');
                let filepath = "/storage/LaptopBewijzen/" + $(this).data('filepath');
                let description = $(this).data('description');
                let file_icon = $(this).data('fileicon');
                let file_name = $(this).data('filename')
                let content = `<img src="../assets/icons/file_icons/` + file_icon + `" alt="file icon" width="25px">` + file_name

                // Update the modal
                $('form').attr('action', `/user/laptop/${id}`);
                $('#bedrag').val(amount);
                $('#reden').val(description);
                $('#datum').val(purchaseDate);
                $('#filepath').attr('href', filepath).html(content);
                $('#bestand').val(null);
                $('input[name="_method"]').val('post');
                // Show the modal
                $('#modal-laptop').modal('show');
            }
        });

        let table = $('#mijnAanvragen').DataTable({
            "columns": [
                {"name": "Aanvraagdatum", "orderable": true},
                {"name": "Datum beoordeling Kostenplaatsverantwoordelijke", "orderable": true, "width": 150},
                {"name": "Datum terugbetaling", "orderable": true},
                {"name": "Beschrijving", "orderable": true},
                {"name": "Bedrag", "orderable": true},
                {"name": "Status Kostenplaatsverantwoordelijke", "orderable": true},
                {"name": "Status Financieel Medewerker", "orderable": true},
                {"name": "Aanvraag aanpassen", "orderable": false}
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
                    table.clear();
                    // diverse reimbursements
                    $.each(data.diverse_requests, function (key, value) {
                        let request_date = value.request_date;
                        let beschrijving = value.description;
                        let amount = (value.amount).toFixed(2);
                        let strAmount = '€ ' + amount;
                        let statusFE = value.status_FE;
                        let statusCCM = value.status_CC_manager;
                        let review_date_Financial_employee;
                        let CCMName = value.cc_manager_name;
                        let CCMComment = value.comment_Cost_center_manager;
                        let FEName = value.fe_name;
                        let FEComment = value.comment_Financial_employee;
                        if (statusFE === "afgekeurd") {
                            review_date_Financial_employee = null;
                        } else {
                            review_date_Financial_employee = value.review_date_Financial_employee;
                        }
                        let review_date_Cost_center_manager = value.review_date_Cost_center_manager;

                        let CCM;
                        let FE;

                        if (CCMComment == null) {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `" data-placement="top">` + statusCCM + ` <i class="fas fa-info-circle"></i></p>`
                        } else {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `<br> Opmerking: ` + CCMComment + `" data-placement="top">` + statusCCM + ` <i class="fas fa-info-circle"></i></p>`
                        }

                        if (FEName === " ") {
                            FE = statusFE;
                        } else if (FEComment == null && (FEName !== " ")) {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `" data-placement="top">` + statusFE + ` <i class="fas fa-info-circle"></i></p>`
                        } else {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `<br> Opmerking: ` + FEComment + `" data-placement="top">` + statusFE + ` <i class="fas fa-info-circle"></i></p>`
                        }

                        if ((statusCCM === "in afwachting") || (statusFE === "afgekeurd")) {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                beschrijving,
                                strAmount,
                                CCM,
                                FE,
                                `<a href="/user/divers/${value.id}" class="btn-edit diversevergoeding"><i class="fas fa-edit"></i></a>`
                            ]).draw(false);
                        } else {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                beschrijving,
                                amount,
                                CCM,
                                FE,
                                null
                            ]).draw(false);
                        }

                    });

                    // laptop reimbursements
                    $.each(data.laptop_reimbursements, function (key, value) {
                        let request_date = value.laptop_invoice.updated_at;
                        if (request_date == null){
                            request_date = value.laptop_invoice.created_at;
                        }

                        let review_date_Cost_center_manager = value.review_date_Cost_center_manager;
                        if (review_date_Cost_center_manager == null) {
                            review_date_Cost_center_manager = null
                        }
                        let review_date_Financial_employee = value.review_date_Financial_employee;
                        if (review_date_Financial_employee == null) {
                            review_date_Financial_employee = null
                        }
                        let description = value.laptop_invoice.invoice_description;
                        let statusFE = value.status_FE;
                        let statusCCM = value.status_CC_manager;
                        let amount = value.amount;
                        amount = '€ ' + amount;


                        let CCMName = value.cc_manager_name;
                        let CCMComment = value.comment_Cost_center_manager;
                        let FEName = value.financial_employee_name;
                        let FEComment = value.comment_Financial_employee;
                        let CCM;
                        let FE;

                        if (CCMComment == null) {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `" data-placement="top">` + statusCCM + ` <i class="fas fa-info-circle"></i></p>`
                        } else {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `<br> Opmerking: ` + CCMComment + `" data-placement="top">` + statusCCM + ` <i class="fas fa-info-circle"></i></p>`
                        }

                        if (FEComment == null && FEName == null) {
                            FE = statusFE;
                        } else if (FEComment == null && FEName !== " ") {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `" data-placement="top">` + statusFE + ` <i class="fas fa-info-circle"></i></p>`
                        } else {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `<br> Opmerking: ` + FEComment + `" data-placement="top">` + statusFE + ` <i class="fas fa-info-circle"></i></p>`
                        }

                        if (((statusCCM === "in afwachting" || statusCCM === "afgekeurd")) || (statusFE === "afgekeurd")) {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                description,
                                amount,
                                CCM,
                                FE,
                                `<a href="#!" class="btn-edit laptopvergoeding" data-id="${value.laptop_invoice.id}" data-amount="${value.laptop_invoice.amount}" data-purchasedate="${value.laptop_invoice.purchase_date_no_format}" data-description="${value.laptop_invoice.invoice_description}" data-fileIcon="${value.laptop_invoice.file_icon}" data-filename="${value.laptop_invoice.file_name.substring(13)}" data-filepath="${value.laptop_invoice.file_name}"><i class="fas fa-edit"></i></a>`
                            ]).draw(false);
                        } else {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                description,
                                amount,
                                CCM,
                                FE,
                                null
                            ]).draw(false);
                        }
                    });

                    // bike reimbursements
                    $.each(data.bike_requests, function (key, value) {
                        let request_date = value.request_date;
                        let review_date_Cost_center_manager = null;
                        let review_date_Financial_employee = value.review_date_Financial_employee;
                        if (review_date_Financial_employee == null) {
                            review_date_Financial_employee = null
                        }
                        let description = value.name;
                        let statusFE = value.status_FE;
                        let statusCCM = null;
                        let amount = (value.amount).toFixed(2);
                        amount = '€ ' + amount;

                        let FEName = value.fe_name;
                        let FEComment = value.comment_Financial_employee;
                        let FE;

                        if (FEComment == null && FEName == null) {
                            FE = statusFE;
                        } else if (FEComment == null && FEName !== " ") {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `" data-placement="top">` + statusFE + ` <i class="fas fa-info-circle"></i></p>`
                        } else {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `<br> Opmerking: ` + FEComment + `" data-placement="top">` + statusFE + ` <i class="fas fa-info-circle"></i></p>`
                        }

                        table.row.add([
                            request_date,
                            review_date_Cost_center_manager,
                            review_date_Financial_employee,
                            description,
                            amount,
                            statusCCM,
                            FE,
                            null
                        ]).draw(false);
                    })
                    tooltips();
                })
        }

        // bootstrap tooltips
        function tooltips() {
            $('[data-toggle="tooltip"]').tooltip({html:true})
        }
    </script>

@endsection
