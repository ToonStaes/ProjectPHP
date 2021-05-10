@extends('layouts.template')

@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
@endsection

@section('main')
    <h1>Kostenplaatsen beheren <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Op deze pagina kan u kostenplaatsen toevoegen en verwijderen. Ook kan u hier het budget van de kostenplaats wijzigen."></i></h1>
    <button class="btn btn-primary mb-4" id="button-cost_center-add"> <i class="fas fa-plus"></i> Kostenplaats toevoegen</button>
    <button class="btn btn-primary float-right" id="button-save" data-toggle="tooltip" data-placement="left" title="De wijzigingen van de budgetten opslaan.">Opslaan</button>
    <table id="tabel" class="table">
        <thead>
        <tr>
            <th>Opleiding/Unit</th>
            <th>Kostenplaats</th>
            <th>Verantwoordelijke</th>
            <th>Beschrijving</th>
            <th>Budget</th>
            <th>Verwijderen</th>
        </tr>
        </thead>
        <tbody id="table_body">
        @foreach($cost_centers as $cost_center)
            <tr data-id="{{$cost_center->id}}">
                <td>{{($cost_center->programmes[0]->name) ?? "Geen opleiding"}}</td>
                <td class="cost_center_name">{{$cost_center->name}}</td>
                <td>{{$cost_center->user->first_name." ".$cost_center->user->last_name}}</td>
                <td>{{$cost_center->description}}</td>
                <td><input class="input-budget" type="number"
                           value="{{count($cost_center->cost_center_budgets) ? $cost_center->cost_center_budgets[0]->amount : 0}}"
                           step="0.01" min="0" oninput="this.value = (this.value < 0) ? 0 : this.value"></td>
                <td class="text-center">
                    <button type="submit" class="deleteCostCenter">
                        <i class="fas fa-trash-alt" data-toggle="tooltip" title="Verwijder kostenplaats {{$cost_center->name}}"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <div class="modal" id="cost_center_form_modal" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_cost_center_title">Kostenplaats toevoegen</h5>
                    <button type="button" class="close modal-close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        @include('financial_employee.cost_centers.form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_after')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
        let budgets_changed = [];
        let cost_center_names = [];
        let _csrf = "{{csrf_token()}}";
        let _query_url = "http://cma.test/cost_centers/";
        let _datatable;

        $(document).ready( function () {
            $('#notification').hide();

            _datatable = $('#tabel').DataTable({
                "columns": [
                    {"name": "Opleiding", "orderable": true},
                    {"name": "Kostenplaats", "orderable": true},
                    {"name": "Verantwoordelijke", "orderable": true},
                    {"name": "Beschrijving", "orderable": true},
                    {"name": "Budget", "orderable": true},
                    {"name": "Verwijderen", "orderable": false},
                ],
                "language": {
                    "lengthMenu": "_MENU_ kostenplaatsen per pagina",
                    "zeroRecords": "Er zijn geen kostenplaatsen gevonden",
                    "info": "Kostenplaatsen _START_ tot _END_ van _TOTAL_",
                    "infoEmpty": "",
                    "infoFiltered": "(gefilterd uit _MAX_ kostenplaatsen)",
                    "search": "Filteren:",
                    "paginate": {
                        "next": "Volgende",
                        "previous": "Vorige",
                        "first": "Eerste",
                        "last": "Laatste"
                    }
                }
            });

            $('#cost_center_form_modal').modal();

            jQuery.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": _csrf
                }
            });

            //$('.deleteCostCenter').tooltip({html:true, container:'body', boundary:'window'});

            document.getElementById("cost_center_form").addEventListener("submit", function(event){
                event.preventDefault();
                event.stopPropagation();
            });
        });

        /*
        *   When we want to save our changes by pressing the button,
        *   we send an asynchronous ajax request to the server,
        *   updating the resource with our specified data
        * */
        $('#button-save').on('click', function(){
            send_budget_changes();
        });

        $('.input-budget').change(function() {
            budget = $(this).val();
            id = parseInt($(this).parent().parent().data("id"), 10);
            modify_budgets_changed(id, budget);
        });

        function send_budget_changes(){
            if(!(budgets_changed.length === 0)){
                for(budget_index in budgets_changed){
                    jQuery.ajax({
                        url: _query_url+budgets_changed[budget_index].id,
                        method: "PUT",
                        tryCount: 0,
                        tryLimit: 3,
                        context: {toCheck: budgets_changed[budget_index]},
                        data: budgets_changed[budget_index]
                    }).done(function(){
                        for(budget in budgets_changed){
                            if (budgets_changed[budget] == this.toCheck) budgets_changed.splice(budget, 1);
                        }
                        show_success_notification("De budgetten werden succesvol geüpdated");
                    }).fail(function(jqXHR, statusText, errorText){
                        if(jqXHR.status == 500){
                            show_failure_notification("Er is een fout gebeurt bij het opslagen");
                            return;
                        }
                        this.tryCount++;
                        if(this.tryCount > this.tryLimit) return;
                        jQuery.ajax(this);
                    }).always(function(){
                        if(this.tryCount>this.tryLimit){
                            show_failure_notification("Er is een fout gebeurt bij het opslagen");
                        }
                    });
                }
            }
        }

        function modify_budgets_changed(center_id, center_budget) {
            /*
            *   Check if the changed budget is already in the array
            *   if not, add it, else update the value in the array
            * */
            isPresent = false;
            if(budgets_changed.length === 0) budgets_changed.push({id: center_id, budget: center_budget})
            else {
                for(budget_index in budgets_changed) {
                    if(budgets_changed[budget_index].id === center_id) {
                        budgets_changed[budget_index].budget = center_budget;
                        isPresent = true;
                        break;
                    }
                }
                if(!isPresent) budgets_changed.push({id: center_id, budget: center_budget});
            }
        }

        $(".deleteCostCenter").on("click", $.proxy(cost_center_delete_click));

        function cost_center_delete_click(event){
            name = $(this).parent().parent().children(".cost_center_name").text();
            id = parseInt($(this).parent().parent().data("id"));
            send_deletion(id, name);
        }

        /*  Because at this point in time we
        *   probably won't have more than 1
        *   deletion request at any one time,
        *   grouping failed requests is not that
        *   useful
        **/
        function send_deletion(center_id, center_name){
            jQuery.ajax({
                url: _query_url+center_id,
                method: "DELETE",
                tryCount: 0,
                tryLimit: 2,
                context: {id: center_id, name: center_name}
            }).done(function(data){
                delete_cost_center_row(this.id, this.name);
                show_success_notification("De kostenplaats werd succesvol verwijderd");
            }).fail(function(jqXHR, statusText, errorText){
                if(jqXHR.status == 500){
                    show_failure_notification("Er is een fout gebeurt bij het verwijderen");
                    return;
                }
                this.tryCount++;
                if(this.tryCount > this.tryLimit) return;
                jQuery.ajax(this);
            });
        }

        $('#button-cost_center-add').on('click', function(){
            $('#cost_center_form_modal').modal('show');
        });

        $('.modal-close').on('click', function(){
            $('#cost_center_form_modal').modal('hide');
        });

        function delete_cost_center_row(id, name){
            _datatable.row($("tr td.cost_center_name:contains("+name+")")).remove().draw();
            $('#cost_centers_list option[data-id="'+id+'"]').remove();
        }

        $("#cost_center_submit").on("click", function(){
            user_id = parseInt($("#responsible_list").val(), 10);
            user_name = $("#responsible_list option:selected").text();
            programme_id = parseInt($("#programmes_list").val(), 10);
            programme_name = $("#programmes_list option:selected").text();
            cost_center_name = $("#cost_center_input").val();
            cost_center_id = $("#cost_centers_list option:selected").data("id");
            description = $("#descr_input").val() ?? " ";
            if(description.length == 0) description = " ";

            budget = parseInt($("#budget_input").val(), 10);
            if(isNaN(budget)) budget = 0;

            isActive = ($("#active_input").prop("checked")) ? 1 : 0;

            formData = {
                user_id: user_id,
                user_name: user_name,
                programme_id: programme_id,
                programme_name: programme_name,
                cost_center_name: cost_center_name,
                cost_center_id: cost_center_id,
                description: description,
                budget: budget,
                isActive: isActive
            };

            send_new_cost_center(formData);
        });

        function reset_form(){
            $("#responsible_list option[selected]").removeAttr("selected");

            $("#programmes_list option[selected]").removeAttr("selected");

            $("#cost_centers_list option[selected]").removeAttr("selected");

            $("#descr_input").val("");

            $("#budget_input").val("");

            $("#active_input").prop("checked", true);

            $("#cost_center_form_modal").modal('hide');

            $(".invalid-feedback").empty();
        }

        function add_cost_center(cost_center){
            if(cost_center.isActive == 0) return;

            update_cost_center_names();

            newrow = _datatable.row.add([
                "<td>"+cost_center.programme_name+"</td>",
                "<td class=\"cost_center_name\">"+cost_center.cost_center_name+"</td>",
                "<td>"+cost_center.user_name+"</td>",
                "<td>"+cost_center.description+"</td>",
                "<td><input class=\"input-budget\" type=\"number\"value=\""+cost_center.budget+"\"\n" +
                "                           min=\"0\" oninput=\"this.value = (this.value < 0) ? 0 : this.value\"></td>",
                "<td><button type=\"submit\" class=\"deleteCostCenter\">\n" +
                "                        <i class=\"fas fa-trash-alt\" data-toggle=\"tooltip\" title=\"Verwijder kostenplaats "+cost_center.cost_center_name+"\"></i></button></td>"
            ]).draw().node();
            _datatable.draw();
            _datatable.sort();
            $("td:nth-child(2)").addClass("cost_center_name");
            $("td:last-child").addClass("text-center");
            $(newrow).on('click','.deleteCostCenter', cost_center_delete_click);
            if(!cost_center_names.includes((cost_center.cost_center_name))){
                $("#cost_centers_list").append('<option data-id="'+cost_center.cost_center_id+'">'+cost_center.cost_center_name+'</option>');
            }
        }

        function send_new_cost_center(cost_center){
            jQuery.ajax({
                url: _query_url,
                method: "POST",
                tryCount: 0,
                tryLimit: 2,
                data: cost_center,
                context: {cost_center: cost_center}
            }).done(function(data){
                reset_form();
                this.cost_center.cost_center_id = data.id;
                add_cost_center(this.cost_center);
                show_success_notification("De kostenplaats werd succesvol opgeslagen");
            }).fail(function(jqXHR, statusText, errorText){
                //  Laravels form validation error code is 422
                if(jqXHR.status == 409){
                    $('#invalid-cost_center').text(JSON.parse(jqXHR.responseText).message);
                    return;
                }
                else if(jqXHR.status == 422){
                    errors = JSON.parse(jqXHR.responseText).errors;
                    error_names = Object.getOwnPropertyNames(errors);
                    error_names.forEach(function(name){
                        switch(name){
                            case "programme_id": $('#invalid-programme').text(errors.programme_id[0]); break;
                            case "user_id": $('#invalid-user').text(errors.user_id[0]); break;
                            case "budget": $('#invalid-budget').text(errors.budget[0]); break;
                            case "cost_center_name": $('#invalid-cost_center').text(errors.cost_center_name[0]); break;
                            case "description": $('#invalid-description').text(errors.description[0]); break;
                        }
                    });
                }
                this.tryCount++;
                if(this.tryCount > this.tryLimit) return;
                jQuery.ajax(this);
            });
        }

        function update_cost_center_names(){
            $('#table_body .cost_center_name').each(function(index){
                if(!cost_center_names.includes($(this).text())){
                    cost_center_names.push($(this).text());
                }
            })
        }

        function show_success_notification(text){
            let notification = new Noty({
                type: "success",
                text: text,
                layout: "topRight",
                timeout: 5000,,
                progressBar: true,
                modal: false
            }).show();
        }

        function show_failure_notification(text){
            let notification = new Noty({
                type: "error",
                text: text,
                layout: "topRight",
                timeout: 5000,
                progressBar: true,
                modal: false
            }).show();
        }
    </script>
@endsection
