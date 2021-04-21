@extends('layouts.template')

@section('title', 'Mijn aanvragen')
@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endsection

@section('main')
    <div id="Message">

    </div>
    <div class="container">
        <table id="mijnAanvragen" class="table">
            <thead>
            <tr>
                <th>Aanvraagdatum</th>
                <th>Datum beoordeling kostenplaats-verantwoordelijke</th>
                <th>Datum terugbetaling</th>
                <th>Naam kostenplaats</th>
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
        $(document).ready(function () {
            buildTable();

            $('#delete').click(function () {
                $(this).parent().addClass('d-none');
                $('#uploadFile').removeClass('d-none');
                $('#bestand').prop('required', true);
            })

            $('#modal-laptop').on('hidden.bs.modal' ,function () {
                $('#oldfile').removeClass('d-none');
                $('#uploadFile').addClass('d-none');
                $('#bestand').prop('required', false);
            })

            $('#modal-laptop form').submit(function (e) {
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
                        $('#modal-laptop').modal('hide');
                        // Rebuild the table
                        buildTable();
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
        })

        $('tbody').on('click', '.btn-edit', function () {
            if($(this).hasClass('laptopvergoeding')) {
                // Get data attributes from td tag
                let id = $(this).data('id');
                let amount = $(this).data('amount');
                let purchaseDate = $(this).data('purchasedate');
                console.log("purchase Date :" + purchaseDate);
                let filepath = $(this).data('filepath');
                let description = $(this).data('description');
                let file_icon = $(this).data('fileicon');
                console.log("file icon: " + file_icon)
                let file_name = $(this).data('filename')
                let content = `<img src="../assets/icons/file_icons/` + file_icon + `" alt="file icon" width="25px">` + file_name
                // Update the modal
                $('.modal-title').text(`Pas laptopaanvraag aan`);
                $('form').attr('action', `/user/laptop/${id}`);

                $('#bedrag').val(amount);
                $('#reden').val(description);
                $('#datum').val(purchaseDate);
                $('#filepath').attr('href', filepath).html(content);
                $('input[name="_method"]').val('put');
                // Show the modal
                $('#modal-laptop').modal('show');
            }
        });

        let table = $('#mijnAanvragen').DataTable({
            "columns": [
                {"name": "Aanvraagdatum", "orderable": true},
                {"name": "Datum beoordeling Kostenplaatsverantwoordelijke", "orderable": true},
                {"name": "Datum terugbetaling", "orderable": true},
                {"name": "Naam kostenplaats", "orderable": true},
                {"name": "Beschrijving", "orderable": true},
                {"name": "Bedrag", "orderable": true},
                {"name": "Status Kostenplaatsverantwoordelijke", "orderable": true},
                {"name": "Status Financieel Medewerker", "orderable": true},
                {"name": "Aanvraag aanpassen", "orderable": true}
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
                        let cost_center_name = value.cost_center_name;
                        let beschrijving = value.description;
                        let amount = value.amount;
                        amount = '€ ' + amount;
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
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `" data-placement="top">` + statusCCM + `</p>`
                        } else {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `<br> Opmerking: ` + CCMComment + `" data-placement="top">` + statusCCM + `</p>`
                        }

                        if (FEComment == null || FEName == null) {
                            FE = statusFE;
                        } else if (FEComment == null && FEName !== " ") {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `" data-placement="top">` + statusFE + `</p>`
                        } else {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `<br> Opmerking: ` + FEComment + `" data-placement="top">` + statusFE + `</p>`
                        }

                        if ((statusCCM === "in afwachting") || (statusFE === "afgekeurd")) {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center_name,
                                beschrijving,
                                amount,
                                CCM,
                                FE,
                                `<a href="#!" class="btn-edit" data-id="${value.id}"><i class="fas fa-edit"></i></a>`
                            ]).draw(false);
                        } else {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center_name,
                                beschrijving,
                                amount,
                                CCM,
                                FE
                            ]).draw(false);
                        }

                    });

                    // laptop reimbursements
                    $.each(data.laptop_reimbursements, function (key, value) {
                        let request_date = value.laptop_invoice.purchase_date;
                        let review_date_Cost_center_manager = value.review_date_Cost_center_manager;
                        if (review_date_Cost_center_manager == null) {
                            review_date_Cost_center_manager = null
                        }
                        let review_date_Financial_employee = value.review_date_Financial_employee;
                        if (review_date_Financial_employee == null) {
                            review_date_Financial_employee = null
                        }
                        let cost_center = '';
                        $.each(value.laptop_reimbursement_parameters, function (key2, value2) {
                            if (value2.parameter.standard_Cost_center_id != null) {
                                cost_center = value2.parameter.cost_center_name;
                            }
                        })
                        let description = value.laptop_invoice.invoice_description;
                        let statusFE = value.status_FE;
                        let statusCCM = value.status_CC_manager;
                        let amount = value.amount;
                        amount = '€ ' + amount;


                        let CCMName = value.cc_manager_name;
                        let CCMComment = value.comment_Cost_center_manager;
                        let FEName = value.fe_name;
                        let FEComment = value.comment_Financial_employee;
                        let CCM;
                        let FE;

                        if (CCMComment == null) {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `" data-placement="top">` + statusCCM + `</p>`
                        } else {
                            CCM = `<p data-html="true" data-toggle="tooltip" title="Kostenplaatsverantwoordelijke: ` + CCMName + `<br> Opmerking: ` + CCMComment + `" data-placement="top">` + statusCCM + `</p>`
                        }

                        if (FEComment == null && FEName == null) {
                            FE = statusFE;
                        } else if (FEComment == null && FEName !== " ") {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `" data-placement="top">` + statusFE + `</p>`
                        } else {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `<br> Opmerking: ` + FEComment + `" data-placement="top">` + statusFE + `</p>`
                        }

                        if ((statusCCM === "in afwachting") || (statusFE === "afgekeurd")) {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center,
                                description,
                                amount,
                                CCM,
                                FE,
                                `<a href="#!" class="btn-edit laptopvergoeding" data-id="${value.laptop_invoice.id}" data-amount="${value.laptop_invoice.amount}" data-purchasedate="${value.laptop_invoice.purchase_date}" data-filepath="${value.laptop_invoice.filepath}" data-description="${value.laptop_invoice.invoice_description}" data-fileIcon="${value.laptop_invoice.file_icon}" data-filename="${value.laptop_invoice.file_name}"><i class="fas fa-edit"></i></a>`
                            ]).draw(false);
                        } else {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center,
                                description,
                                amount,
                                CCM,
                                FE
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
                        let cost_center = value.cost_center_name;
                        let description = value.name;
                        let statusFE = value.status_FE;
                        let statusCCM = null;
                        let amount = value.amount;
                        amount = '€ ' + amount;

                        let FEName = value.fe_name;
                        let FEComment = value.comment_Financial_employee;
                        let FE;

                        if (FEComment == null && FEName == null) {
                            FE = statusFE;
                        } else if (FEComment == null && FEName !== " ") {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `" data-placement="top">` + statusFE + `</p>`
                        } else {
                            FE = `<p data-html="true" data-toggle="tooltip" title="Financieel medewerker: ` + FEName + `<br> Opmerking: ` + FEComment + `" data-placement="top">` + statusFE + `</p>`
                        }

                        if ((statusFE === "in afwachting") || (statusFE === "afgekeurd")) {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center,
                                description,
                                amount,
                                statusCCM,
                                FE,
                                `<a href="#!" class="btn-edit" data-id="${value.id}"><i class="fas fa-edit"></i></a>`
                            ]).draw(false);
                        } else {
                            table.row.add([
                                request_date,
                                review_date_Cost_center_manager,
                                review_date_Financial_employee,
                                cost_center,
                                description,
                                amount,
                                statusCCM,
                                FE
                            ]).draw(false);
                        }
                    })
                    tooltips();
                })
        }

        // bootstrap tooltips
        function tooltips() {
            $('[data-toggle="tooltip"]').tooltip()
        }
    </script>

@endsection
