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
            @foreach($toReturn as $cost_center)
                <tr data-id="{{$cost_center->cost_centerID}}">
                    <td>{{$cost_center->programme_name}}</td>
                    <td class="cost_center_name">{{$cost_center->cost_center_name}}</td>
                    <td>{{$cost_center->first_name." ".$cost_center->last_name}}</td>
                    <td>{{$cost_center->description}}</td>
                    <td><input class="input-budget" type="number" value="{{$cost_center->amount}}"></td>
                    <td>
                        <form action="kostenplaats/{{$cost_center->cost_centerID}}" method="post">
                            @method("DELETE")
                            @csrf
                            <button type="submit" class="btn btn-outline-danger deleteCostCenter">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
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
    let incomplete = [];
    let failurecounter = 0;
    let _csrf = "{{csrf_token()}}";

    $(document).ready( function () {
        console.log("ready function called...");
        $('#table-cost_center').DataTable();
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
        console.log("ajax all done!");
        if(incomplete.length === 0){
            budgets_changed = [];
            failurecounter = 0;
            return;
        }
        if(failurecounter >= 2){
            alert("3 AJAX failures detected, stopping requests...");
            incomplete = [];
            return;
        }
        let old_budgets = Array.from(budgets_changed);
        budgets_changed = [];
        for(budget_index in old_budgets){
            for(incomplete_index in incomplete){
                if(old_budgets[budget_index].id === incomplete[incomplete_index].id){
                    budgets_changed.push(old_budgets[budget_index]);
                    break;
                }
            }
        }
        incomplete = [];
        failurecounter++;
        send_budget_changes();
    });

    /*
    *   When we want to save our changes by pressing the button,
    *   we send an asynchronous ajax request to the server,
    *   updating the resource with our specified data
    * */
    $('#button-save').on('click', send_budget_changes);

    $('.input-budget').change(function() {
        budget = $(this).val();
        id = parseInt($(this).parent().parent().data("id"), 10);
        failurecounter = 0;
        modify_budgets_changed(id, budget);
    });

    function send_budget_changes(){
        if(!(budgets_changed.length === 0)){
            for(budget_index in budgets_changed){
                jQuery.ajax({
                    url: "http://cma.test/kostenplaats/"+budgets_changed[budget_index].id,
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
                }).done(function(data){
                    console.log("ajax success");
                }).fail(function(jqXHR, statusText, errorText){
                    console.log("ajax failure");
                    incomplete.push({id: budgets_changed[this.index].id});
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
</script>
</html>
