@extends('layouts.template')

@section('main')


    @include('shared.alert')
    <h1 class="text-center">Fietsvergoeding aanvragen <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right"  data-html="true" title="Op deze pagina kan u een of meerdere fietsritten opslaan. Ook kan u op deze pagina een fietsvergoeding aanvragen van de opgeslagen fietsritten. <br/> <ul><li>Oranje datums = geslecteerde fietsritten</li> <li>Blauwe datums = opgeslagen fietsritten</li> <li>Grijze datums = aangevraagde fietsritten</li></ul>"></i></h1>
    <div class="container-calendar">
        <div class="calendar">

            <div class="month">
                <i class="fas fa-angle-left" id="prev" data-toggle="tooltip"></i>
                <div class="date">
                    <h1></h1>
                </div>
                <i class="fas fa-angle-right" id="next" data-toggle="tooltip"></i>
            </div>
            <div class="weekdays">
                <div>Zo</div>
                <div>Ma</div>
                <div>Di</div>
                <div>Wo</div>
                <div>Do</div>
                <div>Vr</div>
                <div>Za</div>

            </div>

                <div class="days" data-saved="{{$saved_fietsritten}}" data-requested="{{$requested_fietsritten}}">

            </div>
        </div>
    </div>
    <div class="row knoppen justify-content-between">
        <div class="col-4">
            <form action="/user/save_bikerides" method="post" class="row justify-content-start">
                <input id="fietsritten" name="fietsritten" type="hidden"/>
                <input  id="teVerwijderen" name="teVerwijderen" type="hidden"/>

                    <div class="col-3"><label class="mr-2" for="numberOfKm">Aantal km</label> <input style="width: 90px;" class="form-control {{ $errors->first('numberOfKm') ? 'is-invalid' : '' }}" id="numberOfKm" name="numberOfKm" type="number" min="0.0" step="0.1" value="{{$user->number_of_km}}"/></div>
                @csrf
                    <div class="col-4">
                        <span  id="save-tooltip-wrapper" class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="right" title="Er moeten fietsritten geselecteerd zijn om de ritten te kunnen opslaan." >
                    <button id="save" type="submit" class="btn btn-primary" disabled>Ritten opslaan</button>
                </span>
                    </div>
                <div class="col-3">
                    <div class="invalid-feedback">{{ $errors->first('bikereimbursement') }}</div>
                </div>
            </form>
        </div>
        <div class="col-3">
            <form action="/user/request_bikeReimbursement" method="post">
                @csrf
                <span id="request-tooltip-wrapper" class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="left" title="Er moeten fietsritten zijn opgeslagen om een fietsvergoeding aan te vragen.">
                <button id="request" type="submit" class="btn btn-secondary" disabled>Aanvraag indienen</button>
            </span>

            </form>
        </div>
    </div>
@endsection

@section('script_after')
    <script>
        const date = new Date();
        let selected_dates = [];
        let saved_dates = document.querySelector(".days").getAttribute("data-saved").split(',');
        let requested_days = document.querySelector(".days").getAttribute("data-requested").split(',');
        let te_verwijderen = [];

        function selecteer(el){
            if(selected_dates.includes(el.getAttribute("data-value"))){
                selected_dates.splice(selected_dates.indexOf(el.getAttribute("data-value")),  1);
                el.classList.remove("geselecteerd");
                document.getElementById("fietsritten").value = selected_dates;
            }
            else if(saved_dates.includes(el.getAttribute("data-value"))){
                el.classList.remove("opgeslaan");
                saved_dates.splice(selected_dates.indexOf(el.getAttribute("data-value")),  1);
                te_verwijderen.push(el.getAttribute("data-value"));
                document.getElementById("teVerwijderen").value = te_verwijderen;
                selecteerDatums();
            }
            else{
                selected_dates.push(el.getAttribute("data-value"));
                document.getElementById("fietsritten").value = selected_dates;
                selecteerDatums();
            }

        }

        function selecteerDatums(){
            if(document.getElementById("teVerwijderen").value !== ""){
                document.getElementById("save").disabled = false;
                //tooltip wijzigen
                document.getElementById("save-tooltip-wrapper").setAttribute("data-original-title", "De opgeslagen fietsritten wijzigen.");
                document.getElementById("save-tooltip-wrapper").setAttribute("title", "De opgeslagen fietsritten wijzigen.");
            }
            //indien er datums zijn geselecteerd
            if(document.getElementById("fietsritten").value !== ""){
                document.getElementById("save").disabled = false;
                //tooltip wijzigen
                document.getElementById("save-tooltip-wrapper").setAttribute("data-original-title", "De geselecteerde fietsritten opslaan.");
                document.getElementById("save-tooltip-wrapper").setAttribute("title", "De geselecteerde fietsritten opslaan.");
            }
            //indien er datums opgeslagen zijn
            if(saved_dates[0] !== ""){
                document.getElementById("request").disabled = false;
                document.getElementById("request-tooltip-wrapper").setAttribute("data-original-title", "Voor de opgeslagen fietsritten een fietsvergoeding aanvragen.");
                document.getElementById("request-tooltip-wrapper").setAttribute("title", "Voor de opgeslagen fietsritten een fietsvergoeding aanvragen.");
            }
            //controleren of datums opgeslagen/ geselecteerd/ aangevraagd zijn
            let result=document.querySelectorAll('[data-value]');
            for (let index in result){
                if (result.hasOwnProperty(index)){
                    if(selected_dates.includes(result[index].getAttribute('data-value'))){
                        result[index].classList.add("geselecteerd");
                    }
                    if(saved_dates.includes(result[index].getAttribute('data-value'))){
                        result[index].classList.add("opgeslaan");
                    }
                    if(te_verwijderen.includes(result[index].getAttribute('data-value'))){
                        result[index].classList.remove("opgeslaan");
                    }
                    if(requested_days.includes(result[index].getAttribute('data-value'))){
                        result[index].classList.add("aangevraagd");
                    }
                }
            }

        }
        function renderCalender(){

            date.setDate(1);

            const monthDays = document.querySelector(".days");

            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

            const prevLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();

            const firstDayIndex = date.getDay();

            const lastDayIndex = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDay();

            const nextDays = 7 - lastDayIndex - 1;

            const months = [
                "Januari",
                "Februari",
                "Maart",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Augustus",
                "September",
                "Oktober",
                "November",
                "December",
            ];


            document.querySelector(".date h1").innerHTML = months[date.getMonth()] +  " " + date.getFullYear();

            //tooltips vorige en volgende maand
            if(date.getMonth() === 11){
                document.querySelector('#next').setAttribute('title', "Ga naar januari");
                document.querySelector('#next').setAttribute('data-original-title', "Ga naar januari");
                document.querySelector('#next').setAttribute('data-bs-original-title', "Ga naar januari");
            } else{
                document.querySelector('#next').setAttribute('title', "Ga naar " + months[date.getMonth()+1].toLowerCase());
                document.querySelector('#next').setAttribute('data-original-title', "Ga naar " + months[date.getMonth()+1].toLowerCase());
                document.querySelector('#next').setAttribute('data-bs-original-title', "Ga naar " + months[date.getMonth()+1].toLowerCase());
            }

            if(date.getMonth() === 0){
                document.querySelector('#prev').setAttribute('title', "Ga naar december");
                document.querySelector('#prev').setAttribute('data-original-title', "Ga naar december");
                document.querySelector('#prev').setAttribute('data-bs-original-title', "Ga naar december");

            }else{
                document.querySelector('#prev').setAttribute('title', "Ga naar " + months[date.getMonth()-1].toLowerCase());
                document.querySelector('#prev').setAttribute('data-original-title', "Ga naar " + months[date.getMonth()-1].toLowerCase());
                document.querySelector('#prev').setAttribute('data-bs-original-title', "Ga naar " + months[date.getMonth()-1].toLowerCase());

            }


            let days="";

            for (let j = firstDayIndex; j > 0; j--){
                if(date.getMonth()===0){

                    days += `<div class="prev-date" onloadeddata="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()-1}-12-${prevLastDay -j +1}">${prevLastDay -j +1}</div>`;

                }
                else if(date.getMonth()<9){
                        days += `<div class="prev-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-0${date.getMonth()}-${prevLastDay -j +1}">${prevLastDay -j +1}</div>`;

                }
                else {
                    days += `<div class="prev-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-${date.getMonth()}-${prevLastDay -j +1}">${prevLastDay -j +1}</div>`;
                }
            }

            for (let i= 1; i <= lastDay; i++){
                if(i<10 && date.getMonth()<9){
                    days += `<div onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-0${date.getMonth()+1}-0${i}">${i}</div>`;
                }
                else if(i>=10 && date.getMonth()<9){
                    days += `<div onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-0${date.getMonth()+1}-${i}">${i}</div>`;
                }
                else{
                    days += `<div onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-${date.getMonth()+1}-${i}">${i}</div>`;
                }
                monthDays.innerHTML = days;
            }

            for (let x = 1; x <= nextDays; x++){
                if(x<10 && date.getMonth()<8){
                    days += `<div class="next-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-0${date.getMonth()+2}-0${x}">${x}</div>`;
                }
                else if(date.getMonth()===11){
                    days += `<div class="next-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()+1}-01-0${x}">${x}</div>`;
                }
                else{
                    days += `<div class="next-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${date.getFullYear()}-${date.getMonth()+2}-0${x}">${x}</div>`;
                }

                monthDays.innerHTML = days;
            }

            selecteerDatums();
        }

        document.querySelector("#prev").addEventListener("click", () => {
            date.setMonth(date.getMonth()-1);
            renderCalender();
        });

        document.querySelector("#next").addEventListener("click", () => {
            date.setMonth(date.getMonth()+1);
            renderCalender();
        });

        renderCalender();
    </script>
@endsection
