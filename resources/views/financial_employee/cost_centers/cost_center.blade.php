@extends('layouts.template')

@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
@endsection

@section('main')
    <h1>Kostenplaatsen beheren <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="Op deze pagina kan u kostenplaatsen toevoegen en verwijderen. Ook kan u hier de kostenplaatsen wijzigen."></i></h1>
    <button class="btn btn-primary mb-4" id="button-cost_center-add"> <i class="fas fa-plus"></i> Kostenplaats toevoegen</button>
    <button class="btn btn-primary float-right" id="button-save" data-toggle="tooltip" data-placement="left" title="De wijzigingen van de kostenplaatsen opslaan.">Opslaan</button>
    <table id="tabel" class="table">
        <thead>
        <tr>
            <th>Opleiding/Unit</th>
            <th>Kostenplaats</th>
            <th>Verantwoordelijke</th>
            <th>Beschrijving</th>
            <th>Budget</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody id="table_body">
        @foreach($cost_centers as $cost_center)
            <tr data-id="{{$cost_center->id}}">
                <td>{{($cost_center->programmes[0]->name) ?? "Geen opleiding"}}</td>
                <td class="cost_center_name">{{$cost_center->name}}</td>
                <td>
                    <select class="resp-select search-dropdown" name="Verantwoordelijkenlijst kostenplaats {{$cost_center->id}}" id="resp-list-{{$cost_center->id}}">
                        @foreach($users as $user)
                            <option value="{{$user->id}}" {{($cost_center->user->id == $user->id) ? "selected" : ""}}>{{$user->first_name." ".$user->last_name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>{{$cost_center->description}}</td>
                <td><input class="input-budget" type="number"
                           value="{{count($cost_center->cost_center_budgets) ? $cost_center->cost_center_budgets[0]->amount : 0}}"
                           step="0.01" min="0" oninput="this.value = (this.value < 0) ? 0 : this.value"></td>
                <td class="text-center">
                    <input class="form-check-input table-active-checkbox" {{($cost_center->isActive) ? "checked" : ""}} type="checkbox" id="cost_active_{{$cost_center->id}}">
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
        let message = "";
        let budgets_changed = [];
        let responsible_changed = [];
        let active_changed = [];
        let cost_center_names = [];
        let _csrf = "{{csrf_token()}}";
        let _query_url = "http://cma.test/cost_centers/";
        let _datatable;
        let _managers = {!! $users !!};

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

            document.getElementById("cost_center_form").addEventListener("submit", function(event){
                event.preventDefault();
                event.stopPropagation();
            });

            $(".search-dropdown").select2();
        });
        $(document).ajaxStop(function() {
            if (message !== ""){
                show_success_notification(message);
                message = "";
            }
        });
        /*
        *   When we want to save our changes by pressing the button,
        *   we send an asynchronous ajax request to the server,
        *   updating the resource with our specified data
        * */
        $('#button-save').on('click', function(){


            send_budget_changes();
            send_responsibles_changed();
            send_actives_changed();



        });


        $('.input-budget').change($.proxy(onBudgetChange));

        $('.table-active-checkbox').change($.proxy(onActiveChange))

        function onActiveChange(){
            active = (($(this).prop("checked")) ? 1 : 0);
            id = parseInt($(this).parent().parent().data("id"), 10);
            modify_active_changed(id, active);
        }

        function onBudgetChange(){
            budget = parseInt($(this).val(), 10);
            id = parseInt($(this).parent().parent().data("id"), 10);
            modify_budgets_changed(id, budget);
        }

        $('.resp-select').change($.proxy(onResponsibleChanged));

        function onResponsibleChanged(){
            resp_id = parseInt($(this).val(), 10);
            id = parseInt($(this).parent().parent().data("id"), 10);
            modify_responsible_changed(id, resp_id);
        }

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
                        message += " De budgetten werden succesvol geüpdated."
                    }).fail(function(jqXHR, statusText, errorText){
                        if(jqXHR.status == 500){
                            show_failure_notification("Er is een fout gebeurt bij het opslaan van de budgetten");
                            return;
                        }
                        this.tryCount++;
                        if(this.tryCount > this.tryLimit) return;
                        jQuery.ajax(this);
                    }).always(function(){
                        if(this.tryCount>this.tryLimit){
                            show_failure_notification("Er is een fout gebeurt bij het opslaan van de budgetten");
                        }
                    });
                }
            }
        }

        function send_responsibles_changed(){
            if(!(responsible_changed.length === 0)){
                for(responsible_index in responsible_changed){
                    jQuery.ajax({
                        url: _query_url+responsible_changed[responsible_index].id,
                        method: "PUT",
                        tryCount: 0,
                        tryLimit: 3,
                        context: {toCheck: responsible_changed[responsible_index]},
                        data: responsible_changed[responsible_index]
                    }).done(function(){
                        for(responsible in responsible_changed){
                            if(responsible_changed[responsible] == this.toCheck) responsible_changed.splice(responsible, 1);
                        }

                        if (!message.includes("De verantwoordelijken werden succesvol geüpdated.")){
                            message += " De verantwoordelijken werden succesvol geüpdated.";
                        }

                    }).fail(function(jqXHR, statusText, errorText){
                        if(jqXHR.status == 500){
                            show_failure_notification("Er is een fout gebeurt bij het opslaan van de verantwoordelijken.");
                            return;
                        }
                        this.tryCount++;
                        if(this.tryCount > this.tryLimit) return;
                        jQuery.ajax(this);
                    }).always(function(){
                        if(this.tryCount > this.tryLimit){
                            show_failure_notification("Er is een fout gebeurt bij het opslaan van de verantwoordelijken.")
                        }
                    });
                }
            }
        }

        function send_actives_changed(){
            if(!(active_changed.length === 0)){
                for(state_index in active_changed){
                    jQuery.ajax({
                        url: _query_url+active_changed[state_index].id,
                        method: "PUT",
                        tryCount: 0,
                        tryLimit: 3,
                        context: {toCheck: active_changed[state_index]},
                        data: active_changed[state_index]
                    }).done(function(){
                        for(state in active_changed){
                            if(active_changed[state] == this.toCheck) active_changed.splice(state, 1);
                        }
                        if (!message.includes("De statussen werden succesvol geüpdated.")){
                            message += " De statussen werden succesvol geüpdated.";
                        }

                    }).fail(function(jqXHR, statusText, errorText){
                        if(jqXHR.status == 500){
                            show_failure_notification("Er is een fout gebeurt bij het opslaan van de statussen.");
                            return;
                        }
                        this.tryCount++;
                        if(this.tryCount > this.tryLimit) return;
                        jQuery.ajax(this);
                    }).always(function(){
                        if(this.tryCount > this.tryLimit){
                            show_failure_notification("Er is een fout gebeurt bij het opslaan van de statussen.")
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
            if(budgets_changed.length === 0) budgets_changed.push({id: center_id, budget: center_budget});
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

        function modify_responsible_changed(center_id, resp_id){
            /*
            *   Check if the changed person responsible is already in the array
            *   if not, add it, else update the value in the array
            * */
            isPresent = false;
            if(responsible_changed.length === 0) responsible_changed.push({id: center_id, resp: resp_id});
            else {
                for(resp_index in responsible_changed) {
                    if(responsible_changed[resp_index].id === center_id){
                        responsible_changed[resp_index].resp = resp_id;
                        isPresent = true;
                        break;
                    }
                }
                if(!isPresent) responsible_changed.push({id: center_id, resp: resp_id});
            }
        }

        function modify_active_changed(center_id, center_state){
            /*
            *   Check if the changed state is already in the array
            *   if not, add it, else update the value in the array
            * */
            isPresent = false;
            if(active_changed.length === 0) active_changed.push({id: center_id, isActive: center_state});
            else {
                for(state_index in active_changed){
                    if(active_changed[state_index].id === center_id) {
                        active_changed[state_index].isActive = center_state;
                        isPresent = true;
                        break;
                    }
                }
                if(!isPresent) active_changed.push({id: center_id, isActive: center_state});
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
                if (!message.includes("De kostenplaats werd succesvol verwijderd.")){
                    message += " De kostenplaats werd succesvol verwijderd.";
                }

            }).fail(function(jqXHR, statusText, errorText){
                if(jqXHR.status == 500){
                    show_failure_notification("Er is een fout gebeurt bij het verwijderen.");
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
            user_name = htmlEntities($("#responsible_list option:selected").text());
            programme_id = parseInt($("#programmes_list").val(), 10);
            programme_name = htmlEntities($("#programmes_list option:selected").text());
            cost_center_name = htmlEntities($("#cost_center_input").val());
            cost_center_id = $("#cost_centers_list option:selected").data("id");
            description = htmlEntities($("#descr_input").val() ?? " ");
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

            rowstring = "";
            for(user_index in _managers){
                rowstring += "<option value=\""+_managers[user_index].id+"\" "+ ((_managers[user_index].id === cost_center.user_id) ? "selected" : "") +">" +
                    _managers[user_index].first_name + " " + _managers[user_index].last_name +
                    "</option>"
            }

            newrow = _datatable.row.add([
                "<td>"+cost_center.programme_name+"</td>",
                "<td class=\"cost_center_name\">"+cost_center.cost_center_name+"</td>",
                "<td><select class=\"resp-select\" name=\"Verantwoordelijkenlijst kostenplaats "+cost_center.id+"\" id=\"resp-list-"+cost_center.id+"\">"+rowstring+"</select></td>",
                "<td>"+cost_center.description+"</td>",
                "<td><input class=\"input-budget search-dropdown\" type=\"number\"value=\""+cost_center.budget+"\"\n" +
                "                           step=\"0.01\" min=\"0\" oninput=\"this.value = (this.value < 0) ? 0 : this.value\"></td>",
                "<td class=\"text-center\">" +
                "<input class=\"form-check-input table-active-checkbox\" "+((cost_center.isActive) ? "checked" : "")+" type=\"checkbox\" id=\"cost_active_"+cost_center.id+"\"></td>"
            ]).draw().node();
            _datatable.draw();
            _datatable.sort();
            $("td:nth-child(2)").addClass("cost_center_name");
            $("td:last-child").addClass("text-center");
            jQuery.data(newrow, "id", cost_center.cost_center_id);
            $(newrow).on('click','.deleteCostCenter', cost_center_delete_click);
            $(newrow).on('change', '.input-budget', onBudgetChange);
            $(newrow).on('change', '.resp-select', onResponsibleChanged);
            $(newrow).on('change', '.table-active-checkbox', onActiveChange);
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
                message += " De kostenplaats werd succesvol opgeslagen.";
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
                timeout: 5000,
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

        function htmlEntities(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>
@endsection
