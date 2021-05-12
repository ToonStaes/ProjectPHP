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

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
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
        'numeric' => ':attribute moet :max zijn.',
        'file' => ':attribute moet :max kilobytes zijn.',
        'string' => ':attribute moet :max karakters bevatten.',
        'array' => ':attribute moet :max objecten bevatten.',
    ],
    'mimes' => ':attribute moet een bestand zijn van het type: :values.',
    'mimetypes' => ':attribute moet een bestand zijn van het type: :values.',
    'min' => [
        'numeric' => ':attribute moet :min zijn.',
        'file' => ':attribute moet :min kilobytes zijn.',
        'string' => ':attribute moet :min karakters bevatten.',
        'array' => ':attribute moet :min objecten bevatten.',
    ],
    'not_in' => 'Het geselecteerde :attribute is ongeldig.',
    'not_regex' => 'Het :attribute formaat is ongeldig.',
    'numeric' => ':attribute moet een nummer zijn.',
    'password' => 'Het wachtwoord.',
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
