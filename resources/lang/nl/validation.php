<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute moet geaccepteerd worden.',
    'active_url' => ':attribute is geen geldige URL.',
    'after' => ':attribute moet een datum zijn na :date.',
    'after_or_equal' => ':attribute moet een datum zijn na of gelijk aan :date.',
    'alpha' => ':attribute mag alleen letters bevatten.',
    'alpha_dash' => ':attribute mmag alleen letters, nummers, koppeltekens en liggende streepjes bevatten.',
    'alpha_num' => ':attribute mag alleen letters en nummers bevatten.',
    'array' => ':attribute moet een matrix zijn.',
    'before' => ':attribute moet een datum zijn voor :date.',
    'before_or_equal' => ':attribute moet een datum zijn voor of gelijk aan :date.',
    'between' => [
        'numeric' => ':attribute moet tussen :min en :max liggen.',
        'file' => ':attribute moet tussen :min en :max kilobytes liggen.',
        'string' => ':attribute moet tussen :min en :max aantal karakters bevatten.',
        'array' => ':attribute moet tussen :min en :max objecten hebben.',
    ],
    'boolean' => ':attribute moet waar of onwaar zijn.',
    'confirmed' => ':attribute bevestiging komt niet overeen.',
    'date' => ':attribute is geen geldige datum.',
    'date_equals' => ':attribute moet een datum gelijk aan :date zijn.',
    'date_format' => ':attribute komt niet overeen met :format.',
    'different' => ':attribute en :other moeten verschillend zijn.',
    'digits' => ':attribute moet :digits cijfers bevatten.',
    'digits_between' => ':attribute moet tussen :min en :max cijfers bevatten.',
    'dimensions' => ':attribute heeft ongeldige afmetingen.',
    'distinct' => ':attribute heeft een dubbele waarde.',
    'email' => ':attribute moet een geldig emailadres zijn.',
    'ends_with' => ':attribute moet eindigen met één van de volgende waarden: :values.',
    'exists' => 'Het geselecteerde :attribute is ongeldig.',
    'file' => ':attribute moet een bestand zijn.',
    'filled' => ':attribute moet een waarde hebben.',
    'gt' => [
        'numeric' => ':attribute moet groter zijn dan :value.',
        'file' => ':attribute moet groter zijn dan :value kilobytes.',
        'string' => ':attribute moet meer dan :value karakters bevatten.',
        'array' => ':attribute moet meer dan :value objecten bevatten.',
    ],
    'gte' => [
        'numeric' => ':attribute moet groter of gelijk zijn aan :value.',
        'file' => ':attribute moet groter of gelijk zijn aan :value kilobytes.',
        'string' => ':attribute moet evenveel of meer dan :value karakters bevatten.',
        'array' => ':attribute moet :value of meer objecten hebben.',
    ],
    'image' => ':attribute moet een foto zijn.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'in_array' => ':attribute bestaat niet in :other.',
    'integer' => ':attribute moet een geheel getal zijn.',
    'ip' => ':attribute moet een geldig IP-adres zijn.',
    'ipv4' => ':attribute moet een geldig IPv4-adres zijn.',
    'ipv6' => ':attribute moet een geldig IPv6-adres zijn.',
    'json' => ':attribute moet een geldige JSON tekst zijn.',
    'lt' => [
        'numeric' => ':attribute moet kleiner zijn dan :value.',
        'file' => ':attribute moet kleiner zijn dan :value kilobytes.',
        'string' => ':attribute moet kleiner zijn dan :value characters.',
        'array' => ':attribute moet minder dan :value objecten hebben.',
    ],
    'lte' => [
        'numeric' => ':attribute moet kleiner of gelijk zijn aan :value.',
        'file' => ':attribute moet kleiner of gelijk zijn aan :value kilobytes.',
        'string' => ':attribute moet kleiner of gelijk zijn aan :value characters.',
        'array' => ':attribute mag niet meer dan :value objecten hebben.',
    ],
    'max' => [
        'numeric' => ':attribute mag maximum :max zijn.',
        'file' => ':attribute mag maximum :max kilobytes zijn.',
        'string' => ':attribute mag maximum :max karakters bevatten.',
        'array' => ':attribute mag maximum :max objecten bevatten.',
    ],
    'mimes' => ':attribute moet een bestand zijn van het type: :values.',
    'mimetypes' => ':attribute moet een bestand zijn van het type: :values.',
    'min' => [
        'numeric' => ':attribute moet minimum :min zijn.',
        'file' => ':attribute moet minimum :min kilobytes zijn.',
        'string' => ':attribute moet minimum :min karakters bevatten.',
        'array' => ':attribute moet minimum :min objecten bevatten.',
    ],
    'not_in' => 'Het geselecteerde :attribute is ongeldig.',
    'not_regex' => 'Het :attribute formaat is ongeldig.',
    'numeric' => ':attribute moet een nummer zijn.',
    'password' => 'Het wachtwoord is incorrect.',
    'present' => ':attribute moet aanwezig zijn.',
    'regex' => 'Het :attribute formaat is ongeldig.',
    'required' => ':attribute is verplicht.',
    'required_if' => ':attribute is verplicht wanneer :other gelijk is aan :value.',
    'required_unless' => ':attribute is verplicht wanneer :other in :values zit.',
    'required_with' => ':attribute is verplicht wanneer :values aanwezig is.',
    'required_with_all' => 'The :attribute is verplicht wanneer :values aanwezig zijn.',
    'required_without' => ':attribute is verplicht wanneer :values niet aanwezig is.',
    'required_without_all' => ':attribute is verplicht wanneer geen enkele van :values aanwezig zijn.',
    'same' => ':attribute en :other moeten gelijk zijn.',
    'size' => [
        'numeric' => ':attribute moet :size zijn.',
        'file' => ':attribute moet :size kilobytes zijn.',
        'string' => ':attribute moet :size karakters bevatten.',
        'array' => ':attribute moet :size objecten bevatten.',
    ],
    'starts_with' => ':attribute moet beginnen met 1 van de volgende :values.',
    'string' => ':attribute moet een tekst zijn.',
    'timezone' => ':attribute moet een geldige tijdzone zijn.',
    'unique' => ':attribute is al in gebruik.',
    'uploaded' => 'De upload van :attribute is mislukt.',
    'url' => ':attribute format is ongeldig.',
    'uuid' => ':attribute moet een geldige UUID zijn.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
