@extends('layouts.template')

@section('main')
    <table id="table-cost_center">
        <thead>
        <tr>
            <th>Unit</th>
            <th>Kostenplaats</th>
            <th>Verantwoordelijke</th>
            <th>Beschrijving</th>
            <th>Budget</th>
            <th>Verwijderen</th>
        </tr>
        </thead>
        <tbody id="table_body">
        @foreach($cost_centers as $cost_center)
            @if($cost_center->isActive)
                <tr data-id="{{$cost_center->id}}">
                    <td>{{count($cost_center->programmes) ? $cost_center->programmes[0]->name : "Onbekend"}}</td>
                    <td class="cost_center_name">{{$cost_center->name}}</td>
                    <td>{{$cost_center->user->first_name." ".$cost_center->user->last_name}}</td>
                    <td>{{$cost_center->description}}</td>
                    <td><input class="input-budget" type="number"
                           value="{{count($cost_center->cost_center_budgets) ? $cost_center->cost_center_budgets[0]->amount : 0}}"
                           step="0.01" min="0" oninput="this.value = (this.value < 0) ? 0 : this.value"></td>
                    <td>
                        <button type="submit" class="btn btn-outline-danger deleteCostCenter">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    <button class="btn btn-primary" id="button-save">opslaan</button>
    <button class="btn btn-primary" id="button-cost_center-add" data-toggle="modal" data-target="#cost_center_form_modal">Nieuwe kostenplaats</button>
    <div class="modal" id="cost_center_form_modal" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_cost_center_title">Kostenplaats toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
    <script>
        let budgets_changed = [];
        let _csrf = "{{csrf_token()}}";
        let _query_url = "http://cma.test/cost_centers/";
        let _datatable;

        $(document).ready( function () {
            _datatable = $('#table-cost_center').DataTable();
            jQuery.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": _csrf
                }
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
                    }).fail(function(jqXHR, statusText, errorText){
                        this.tryCount++;
                        if(this.tryCount > this.tryLimit) return;
                        jQuery.ajax(this);
                    }).always(function(){
                        if(this.tryCount>this.tryLimit){
                            alert("Er is een fout gebeurt bij het opslagen");
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

        $(".deleteCostCenter").on("click", function(){
            id = parseInt($(this).parent().parent().data("id"), 10);
            send_deletion(id);
        });

        /*  Because at this point in time we
        *   probably won't have more than 1
        *   deletion request at any one time,
        *   grouping failed requests is not that
        *   useful
        **/
        function send_deletion(center_id){
            jQuery.ajax({
                url: _query_url+center_id,
                method: "DELETE",
                tryCount: 0,
                tryLimit: 2,
                context: {id: center_id}
            }).done(function(data){
                delete_cost_center_row(this.id);
            }).fail(function(jqXHR, statusText, errorText){
                this.tryCount++;
                if(this.tryCount > this.tryLimit) return;
                jQuery.ajax(this);
            });
        }

        function delete_cost_center_row(id){
            _datatable.row($("tr[data-id="+id+"]")).remove().draw();
        }

        $("#cost_center_submit").on("click", function(){
            user_id = parseInt($("#responsible_list").val(), 10);
            user_name = $("#responsible_list option[selected]").text();
            programme_id = parseInt($("#programmes_list").val(), 10);
            programme_name = $("#programmes_list option[selected]").text();
            cost_center_name = $("#cost_center_input").val();
            cost_center_id = $("#cost_centers_list option[selected]").data("id");
            description = $("#descr_input").val() ?? " ";

            budget = parseInt($("#budget_input").val(), 10);
            if(isNaN(budget)) budget = 0;

            isActive = ($("#active_input").prop("checked")) ? 1 : 0;

            if(user_id == "" || programme_id == "" || cost_center_name == "")
            {
                alert("Vul alle vereiste velden in");
                return;
            }

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
            $("#responsible_list:first-child").attr("selected");

            $("#programmes_list option[selected]").removeAttr("selected");
            $("#programmes_list:first-child").attr("selected");

            $("#cost_centers_list option[selected]").removeAttr("selected");
            $("#cost_center_input").val("");

            $("#descr_input").val("");

            $("#budget_input").val("");

            $("#active_input").prop("checked", false);
        }

        function add_cost_center(cost_center){
            console.log(cost_center.isActive);
            if(!cost_center.isActive) return;
            _datatable.row.add([
                "<td>"+cost_center.programme_name+"</td>",
                "<td class='cost_center_name'>"+cost_center.cost_center_name+"</td>",
                "<td>"+cost_center.user_name+"</td>",
                "<td>"+cost_center.description+"</td>",
                "<td><input class=\"input-budget\" type=\"number\"value=\""+cost_center.budget+"\"\n" +
                "                           min=\"0\" oninput=\"this.value = (this.value < 0) ? 0 : this.value\"></td>",
                "<td><button type=\"submit\" class=\"btn btn-outline-danger deleteCostCenter\">\n" +
                "                        <i class=\"fas fa-trash-alt\"></i></button></td>"
            ]);
            _datatable.draw();
            $("#table-cost_center tbody:last-child").data("id", cost_center.cost_center_id);
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
            }).fail(function(jqXHR, statusText, errorText){
                if(jqXHR.status == 409){
                    alert(JSON.parse(jqXHR.responseText).message);
                    return;
                }
                this.tryCount++;
                if(this.tryCount > this.tryLimit) return;
                jQuery.ajax(this);
            })
        }
    </script>
@endsection
