@extends('layouts.template')

@section('title', 'Help - gebruikers beheren')

@section('main')
    <div class="help container">
        <h1>Help - gebruikers beheren</h1>
            <p>Net boven de tabel kan je kiezen hoeveel rijen je wil zien per pagina: <img src="/assets/icons/help/financial_employee/manageUsers/gebruikersPerPaginapng.png" alt="Aantal rijen per pagina"></p>
            <p>Rechts onderaan kan je navigeren tussen pagina’s: <img src="/assets/icons/help/financial_employee/manageUsers/navigate.png" alt="Navigeren tussen paginas"></p>
            <p>De tabel kan volledig gesorteerd worden en er kan gefilterd worden op elke kolom. Sorteren doe je door op de 2 pijltjes naast de kolomtitel te klikken. Wil je graag op meerdere kolommen sorteren? Dat kan ook, als je de shift toets ingedrukt houdt terwijl je op de pijltjes duwt.</p>
            <p>Filteren kan door rechts bovenaan een filteropdracht in te geven in het tekstveldje: <img src="/assets/icons/help/financial_employee/manageUsers/filter.png" alt="Filteroptie"></p>
            <p>Deze filteropdracht gaat zoeken naar gelijkenissen in elke kolom. Om de filter te verwijderen, kan je het tekstveldje gewoon terug leeg maken.</p>

            <h2>Gebruikers beheren</h2>
            <p>Als financiële medewerker, kan je de gebruikers beheren. Dit houdt in dat je alle gebruikers kan bewerken en verwijderen maar ook dat je nieuwe gebruikers kan aanmaken. Het aanmaken van gebruikers is op deze manier, zodat er geen mensen ongewenst een account kunnen aanmaken en zo vergoedingen aanvragen.</p>
            <p>Je kan de pagina bereiken via het dropdownmenu onder je gebruikersnaam, waar je kan kiezen voor ‘Gebruikers beheren’.</p>
            <p>Op de pagina zie je bovenaan een knop om gebruikers toe te voegen en daaronder een tabel met alle gebruikers:</p>
            <p><img src="/assets/icons/help/financial_employee/manageUsers/manageUser.PNG" width="80%" alt="De pagina gebruikers beheren"></p>
            <h2>Gebruiker toevoegen</h2>
            <p>Om een gebruiker toe te voegen, druk je op de knop “Gebruiker toevoegen” bovenaan de pagina: <img src="/assets/icons/help/financial_employee/manageUsers/addUser.png" alt="Knop gebruiker toevoegen"></p>
            <p>Er zal een venster tevoorschijn komen waar je alle gegevens van de gebruiker kan ingeven.</p>
            <p><img src="/assets/icons/help/financial_employee/manageUsers/addUser1.png" alt="Pop-up gebruiker toevoegen"> <img src="/assets/icons/help/financial_employee/manageUsers/addUser2.png" alt="Pop-up gebruiker toevoegen"></p>
            <p>Een gebruiker kan tot meerdere opleidingen (=units) behoren. Om deze opleidingen toe te wijzen kan je in de lijst onder “Opleiding toevoegen” een opleiding selecteren en dan op de knop “Geselecteerde opleiding toevoegen” klikken. Deze opleiding wordt dan toegevoegd aan de lijst van alle opleidingen waar de gebruiker deel van uitmaakt. Om een opleiding terug te verwijderen uit deze lijst kan je op de min voor de opleiding klikken.</p>
            <p>Als je niet wil dat een gebruiker nog kan inloggen, kan je deze deactiveren. Standaard staat het vakje ‘Geactiveerd’ aangeduid bij het aanmaken van een nieuwe user, omdat het bijna niet voorkomt dat een nieuwe user niet mag inloggen. Wil je toch dat de nieuwe user niet kan inloggen, dan kan je dit vakje uitvinken.</p>
            <p>Als de nieuwe user verantwoordelijk is voor een kostenplaats, kan je dat hier al aanduiden. Later kan je deze dan toewijzen aan een bepaalde kostenplaats. Meer info hierover kan je vinden bij het onderdeel ‘Kostenplaatsen beheren’.</p>
            <p>Bij het opslaan van de nieuwe gebruiker, krijgt deze gebruiker een mail aan met een wachtwoord in. Met dit wachtwoord zal de gebruiker kunnen inloggen waarna hij zelf een wachtwoord kan kiezen.</p>

            <h2>Gebruikers bewerken en verwijderen</h2>
            <p>Rechts bij elke gebruiker staan 2 knopjes: een potloodje om te bewerken en een vuilbakje om de gebruiker te deactiveren: <img src="/assets/icons/help/financial_employee/manageUsers/editDelete.png" alt="Bewerken en verwijderen van een gebruiker"></p>
            <p>Het vuilbakje verwijdert de gebruiker dus niet, maar zorgt er wel voor dat deze niet meer kan inloggen.</p>
            <p>Het bewerken van een gebruiker gebeurt identiek aan het aanmaken van een gebruiker. Je kan alle gegevens gewoon aanpassen en nadien op de knop “opslaan” drukken.</p>
        <a href="#!" class="btn btn-primary m-0 mt-2" id="back">Terug</a>
    </div>
@endsection
@section('script_after')
    <script type="text/javascript">
        $('#back').click(function(){
            window.history.back();
        });
    </script>
@endsection
