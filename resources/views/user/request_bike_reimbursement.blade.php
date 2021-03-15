@extends('layouts.template')

@section('main')
    <h1>Fietsvergoeding aanvragen</h1>
    @include('shared.alert')
    <div class="container-calendar">
        <div class="calendar">
            <div class="month">
                <i class="fas fa-angle-left prev"></i>
                <div class="date">
                    <h1></h1>
                </div>
                <i class="fas fa-angle-right next"></i>
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
            <div class="days">
            </div>
        </div>
    </div>
    <div class="row knoppen justify-content-between">
        <form action="/save_bikerides" method="post">
            @csrf
            <input id="fietsritten" name="fietsritten" type="hidden"/>
            <button type="submit" class="btn-primary">Ritten opslaan</button>
        </form>
        <form action="/request_bikeReimbursement" method="post">
            @csrf
            <button type="submit" class="btn-primary">Aanvraag indienen</button>
        </form>
    </div>
    <h2>Opgeslagen fietsritten</h2>
    @if($bikerides != '')
        @foreach($bikerides as $bikeride)

            <p>{{$bikeride}}</p>

        @endforeach
        @endif
    <h2>Aangevraagde fietsritten</h2>
    @if($fietsritten !='')
        @foreach($fietsritten as $fietsrit)

            <p>{{$fietsrit->date}} {{$fietsrit->bike_reimbursement_id}}</p>

        @endforeach
    @endif
@endsection

@section('script_after')
    <script>
        const date = new Date();
        let selected_dates = [];
        function selecteer(el){
            if(selected_dates.includes(el.getAttribute("data-value"))){
                selected_dates.splice(selected_dates.indexOf(el.getAttribute("data-value")),  1);
                el.classList.remove("geselecteerd");
            }
            else{
                selected_dates.push(el.getAttribute("data-value"));
                selecteerDatums();
                document.getElementById("fietsritten").value = selected_dates;
            }

        }

        function selecteerDatums(){
            let result=document.querySelectorAll('[data-value]');
            for (let index in result){
                if (result.hasOwnProperty(index)){
                    if(selected_dates.includes(result[index].getAttribute('data-value'))){
                        result[index].classList.add("geselecteerd");
                    }
                }
            }
            console.log("Geselecteerde datums: " + selected_dates);
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

            let days="";

            for (let j = firstDayIndex; j > 0; j--){
                if(date.getMonth()===0){

                    days += `<div class="prev-date" onloadeddata="selecteerDatums()" onclick="selecteer(this)" data-value="${prevLastDay -j -1}/12/${date.getFullYear()-1}">${prevLastDay -j +1}</div>`;

                }
                else {
                    days += `<div class="prev-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${prevLastDay -j +1}/${date.getMonth()}/${date.getFullYear()}">${prevLastDay -j +1}</div>`;
                }
            }

            for (let i= 1; i <= lastDay; i++){
                days += `<div onload="selecteerDatums()" onclick="selecteer(this)" data-value="${i}/${date.getMonth()+1}/${date.getFullYear()}">${i}</div>`;
                monthDays.innerHTML = days;
            }

            for (let x = 1; x <= nextDays; x++){

                if(date.getMonth()===11){
                    days += `<div class="next-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${x}/1/${date.getFullYear()+1}">${x}</div>`;
                }
                else {
                    days += `<div class="next-date" onload="selecteerDatums()" onclick="selecteer(this)" data-value="${x}/${date.getMonth()+2}/${date.getFullYear()}">${x}</div>`;
                }
                monthDays.innerHTML = days;
            }
            selecteerDatums();
        }

        document.querySelector(".prev").addEventListener("click", () => {
            date.setMonth(date.getMonth()-1);
            renderCalender();
        });

        document.querySelector(".next").addEventListener("click", () => {
            date.setMonth(date.getMonth()+1);
            renderCalender();
        });



        renderCalender();


    </script>
@endsection
