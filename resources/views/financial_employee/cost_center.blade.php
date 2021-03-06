<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <title>Kostenplaats beheren</title>
</head>
<body>
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
        <tbody>
            @foreach($cost_centers as $cost_center)
                <tr data-id="{{$cost_center->id}}">
                    <td>{{count($cost_center->programmes) ? $cost_center->programmes[0]->name : "Onbekend"}}</td>
                    <td class="cost_center_name">{{$cost_center->name}}</td>
                    <td>{{$cost_center->user->first_name." ".$cost_center->user->last_name}}</td>
                    <td>{{$cost_center->description}}</td>
                    <td><input class="input-budget" type="number" value="{{count($cost_center->cost_center_budgets) ? $cost_center->cost_center_budgets[0]->amount : 0}}"></td>
                    <td>
                        <button type="submit" class="btn btn-outline-danger deleteCostCenter">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-primary" id="button-save">opslaan</button>
    <form action=""></form>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script>
    let budgets_changed = [];
    let budgets_incomplete = [];
    let budget_fails = 0;
    let deletion_fails = 0;
    let _csrf = "{{csrf_token()}}";
    let _query_url = "http://cma.test/cost_centers/";
    let _datatable;

    $(document).ready( function () {
        console.log("ready function called...");
        _datatable = $('#table-cost_center').DataTable();
        jQuery.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": _csrf
            }
        });
    } );

    /*
    *   Deze functie wordt geroepen als er geen AJAX request meer bezig zijn
    *   We kijken na of er requests mislukt zijn
    *   indien ja: proberen we ze opnieuw te sturen,
    *   als we te vaak falen stoppen we met requests te sturen
    * */
    $(document).bind("ajaxStop", function(){
        if(budgets_incomplete.length === 0){
            budgets_changed = [];
            budget_fails = 0;
            return;
        }
        if(budget_fails >= 2){
            alert("3 AJAX failures detected, stopping requests...");
            budgets_incomplete = [];
            return;
        }
        let old_budgets = Array.from(budgets_changed);
        budgets_changed = [];
        for(budget_index in old_budgets){
            for(incomplete_index in budgets_incomplete){
                if(old_budgets[budget_index].id === budgets_incomplete[incomplete_index].id){
                    budgets_changed.push(old_budgets[budget_index]);
                    break;
                }
            }
        }
        budgets_incomplete = [];
        budget_fails++;
        send_budget_changes();
    });

    /*
    *   When we want to save our changes by pressing the button,
    *   we send an asynchronous ajax request to the server,
    *   updating the resource with our specified data
    * */
    $('#button-save').on('click', function(){
        budget_fails = 0;
        send_budget_changes();
    });

    $('.input-budget').change(function() {
        budget = $(this).val();
        id = parseInt($(this).parent().parent().data("id"), 10);
        budget_fails = 0;
        modify_budgets_changed(id, budget);
    });

    function send_budget_changes(){
        if(!(budgets_changed.length === 0)){
            for(budget_index in budgets_changed){
                jQuery.ajax({
                    url: _query_url+budgets_changed[budget_index].id,
                    /*
                    *   Because we are sending asynchronous requests
                    *   we cannot rely on the budget_index for when
                    *   our requests finishes
                    *
                    *   We can store this value in the context
                    *   and later reference it by using "this"
                    * */
                    context: {index: budget_index},
                    method: "PUT",
                    data: budgets_changed[budget_index]
                }).fail(function(jqXHR, statusText, errorText){
                    budgets_incomplete.push({id: budgets_changed[this.index].id});
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
        console.log(budgets_changed);
    }

    $(".deleteCostCenter").on("click", function(){
        id = parseInt($(this).parent().parent().data("id"), 10);
        deletion_fails = 0;
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
            context: {id: center_id}
        }).done(function(data){
            delete_cost_center_row(this.id);
        }).fail(function(jqXHR, statusText, errorText){
            if(deletion_fails <= 2){
                send_deletion(this.id);
            }
        });
    }

    function delete_cost_center_row(id){
        _datatable.row($("tr[data-id="+id+"]")).remove().draw();
    }
</script>
</html>
