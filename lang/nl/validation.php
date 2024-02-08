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

    'accepted' => 'Het :attribute veld moet geaccepteerd zijn.',
    'accepted_if' => 'Het :attribute veld moet geaccepteerd worden wanneer :other :value. is.',
    'active_url' => 'Het :attribute veld moet een geldige URL zijn.',
    'after' => 'Het :attribute veld moet een datum na :date zijn.',
    'after_or_equal' => 'Het :attribute veld moet een datum na of gelijkwaardig zijn aan :date.',
    'alpha' => 'Het :attribute veld mag alleen letters bevatten',
    'alpha_dash' => 'Het :attribute veld mag alleen letters, nummers, streepjes en onderstrepingstekens bevatten.',
    'alpha_num' => 'Het :attribute veld mag alleen letters en nummers bevatten.',
    'array' => 'Het :attribute veld moet een array zijn.',
    'ascii' => 'Het :attribute veld mag alleen alfanumerieke tekens en symbolen van één byte bevatten.',
    'before' => 'Het :attribute veld moet een datum vóór :date zijn.',
    'before_or_equal' => 'Het :attribute veld moet een datum vóór of gelijkwaardig aan :date zijn.',
    'between' => [
        'array' => 'Het :attribute veld moet tussen de :min en :max items bevatten.',
        'file' => 'Het :attribute veld moet tussen de :min en :max kilobytes bevatten.',
        'numeric' => 'Het :attribute veld moet tussen de :min en :max. bevatten',
        'string' => 'Het :attribute veld moet tussen de :min en :max karakters bevatten.',
    ],
    'boolean' => 'Het :attribute veld moet true of false zijn.',
    'can' => 'Het :attribute veld bevat een onbevoegde waarde.',
    'confirmed' => 'Het :attribute bevestingsveld komt niet overeen met :attribute',
    'current_password' => 'Het wachtwoord is incorrect',
    'current_username' => 'De gebruikersnaam is incorrect.',
    'current_account' => 'Het wachtwoord of gebruikersnaam is incorrect.',
    'date' => 'Het :attribute veld moet een geldige datum zijn.',
    'date_equals' => 'Het :attribute veld moet een datum gelijkwaardig aan :date. zijn',
    'date_format' => 'Het :attribute veld moet een gelijkwaardig formaat aan :formaat zijn',
    'decimal' => 'Het :attribute veld moet :decimal decimalen bevatten',
    'declined' => 'Het :attribute veld moet afgewezen worden.',
    'declined_if' => 'Het :attribute veld moet afgewezen worden wanneer :other :value is.',
    'different' => 'Het :attribute veld en :other moeten verschillen.',
    'digits' => 'Het :attribute veld moet :digits cijfers zijn.',
    'digits_between' => 'Het :attribute veld moet tussen de :min en :max cijfers zijn',
    'dimensions' => 'Het :attribute veld heeft ongeldige afbeeldingsdimensies.',
    'distinct' => 'Het :attribute veld heeft een herhaalde waarde',
    'doesnt_end_with' => 'Het :attribute veld mag niet eindigen met het volgende: :values.',
    'doesnt_start_with' => 'Het :attribute veld mag niet beginnen met het volgende: :values.',
    'email' => 'Het :attribute veld moet een geldig email adres zijn.',
    'ends_with' => 'Het :attribute veld moet eindigen met een van het volgende: :values.',
    'enum' => 'Het geselecteerde :attribute is ongeldig',
    'exists' => 'Het geselecteerde :attribute is ongeldig',
    'file' => 'Het :attribute veld moet een bestand zijn.',
    'filled' => 'Het :attribute veld moet een waarde hebben.',
    'gt' => [
        'array' => 'Het :attribute veld moet meer dan :value items hebben.',
        'file' => 'Het :attribute veld moet meer dan :value kilobytes zijn.',
        'numeric' => 'Het :attribute veld moet hoger zijn dan :value.',
        'string' => 'Het :attribute veld moet meer dan :value karakters hebben.',
    ],
    'gte' => [
        'array' => 'Het :attribute veld moet :value of meer items hebben.',
        'file' => 'Het :attribute veld moet groter dan, of gelijkwaardig zijn aan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet groter dan, of gelijkwaardig zijn aan :value.',
        'string' => 'Het :attribute veld moet groter zijn dan, of gelijkwaardig zijn aan :value karakters.',
    ],
    'image' => 'Het :attribute veld moet een afbeelding zijn.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'in_array' => 'Het :attribute veld moet bestaan in :other.',
    'integer' => 'Het :attribute veld moet een nummer zijn.',
    'ip' => 'Het :attribute veld moet een geldig IP-adres zijn.',
    'ipv4' => 'Het :attribute veld moet een geldig IPv4-adres zijn.',
    'ipv6' => 'Het :attribute veld moet een geldig IPv6-adres zijn.',
    'json' => 'Het :attribute veld moet een geldige json reeks zijn.',
    'lowercase' => 'Het :attribute veld moet volledig uit kleine letters bestaan.
    ',
    'lt' => [
        'array' => 'Het :attribute veld moet minder dan :value items hebben.',
        'file' => 'Het :attribute veld moet minder dan :value kilobytes hebben.',
        'numeric' => 'Het :attribute veld moet minder dan :value zijn.',
        'string' => 'Het :attribute veld moet minder dan :value karakters hebben.',
    ],
    'lte' => [
        'array' => 'Het :attribute veld moet niet maar dan :value items hebben.',
        'file' => 'Het :attribute veld moet minder of hetzelfde als :value kilobytes zijn.',
        'numeric' => 'Het :attribute veld moet minder of hetzelfde als :value zijn.',
        'string' => 'Het :attribute veld moet minder of hetzelfde zijn als :value karakters.',
    ],
    'mac_address' => 'Het :attribute veld moet een geldig MAC adres zijn.',
    'max' => [
        'array' => 'Het :attribute veld mag niet meer dan :max items hebben.',
        'file' => 'Het :attribute veld mag niet meer dan :max kilobytes zijn.',
        'numeric' => 'Het :attribute veld mag niet hoger zijn dan :max.',
        'string' => 'Het :attribute veld mag niet meer dan :max karakters hebben.',
    ],
    'max_digits' => 'Het :attribute veld mag niet meer dan :max nummers hebben',
    'mimes' => 'Het :attribute veld moet een bestand van het type :values zijn.',
    'mimetypes' => 'Het :attribute veld moet een bestand van het type :values zijn.',
    'min' => [
        'array' => 'Het :attribute veld moet minstens :min items hebben.',
        'file' => 'Het :attribute veld moet minstens :min Kilobytes zijn.',
        'numeric' => 'Het :attribute veld moet minstens :min zijn.',
        'string' => 'Het :attribute veld moet minstens :min karakters hebben.',
    ],
    'min_digits' => 'Het :attribute veld moet minstens :min nummers hebben.',
    'missing' => 'Het :attribute veld moet leeg zijn.',
    'missing_if' => 'Het :attribute veld moet leeg zijn wanneer :other :value is.',
    'missing_unless' => 'Het :attribute veld moet leeg zijn behalve als :other :value is.',
    'missing_with' => 'Het :attribute veld moet leeg zijn wanneer :values aanwezig is',
    'missing_with_all' => 'Het :attribute veld moet leeg zijn wanneer :values aanwezig zijn',
    'multiple_of' => 'Het :attribute veld moet een veelvoud zijn van :value.',
    'not_in' => 'Het geselecteerde :attribute is ongeldig',
    'not_regex' => 'Het :attribute veld formaat is ongeldig.',
    'numeric' => 'Het :attribute veld moet een nummer zijn.',
    'password' => [
        'letters' => 'Het :attribute veld moet minstens één letter hebben.',
        'mixed' => 'Het :attribute veld moet minstens één hoofdletter en één kleine letter hebben.',
        'numbers' => 'Het :attribute veld moet minstens één nummer hebben.',
        'symbols' => 'Het :attribute veld moet minstens één teken hebben.',
        'uncompromised' => 'Het opgegeven :attribute is aanwezig in een datalek. Kies alstublieft een andere :attribute.',
    ],
    'present' => 'Het :attribute veld moet aanwezig zijn.',
    'prohibited' => 'Het :attribute veld is niet toegestaan.',
    'prohibited_if' => 'Het :attribute veld is niet toegestaan wanneer :other :value is.',
    'prohibited_unless' => 'Het :attribute veld is niet toegestaan behalve als :other :values is',
    'prohibits' => 'Het :attribute veld blokkeert :other van aanwezig zijn.',
    'regex' => 'Het :attribute veld formaat is ongeldig.',
    'required' => 'Het :attribute veld is vereist.',
    'required_array_keys' => 'Het :attribute veld moet inzendingen hebben voor :values.',
    'required_if' => 'Het :attribute veld is vereist wanneer :other :value is.',
    'required_if_accepted' => 'Het :attribute veld is vereist wanneer :other geaccepteerd is.',
    'required_unless' => 'Het :attribute veld is vereist behalve als :other in :values is.',
    'required_with' => 'Het :attribute veld is vereist wanneer :values aanwezig is.',
    'required_with_all' => 'Het :attribute veld is vereist wanneer :values aanwezig zijn.',
    'required_without' => 'Het :attribute veld is vereist wanneer :values niet aanwezig is.',
    'required_without_all' => 'Het :attribute veld is vereist wanneer geen van de :values aanwezig zijn.',
    'same' => 'Het :attribute veld moet hetzelfde zijn als :other.',
    'size' => [
        'array' => 'Het :attribute moet :size items hebben.',
        'file' => 'Het :attribute veld moet :size kilobytes zijn.',
        'numeric' => 'Het attribute veld moet :size nummers zijn.',
        'string' => 'Het :attribute veld moet :size karakters zijn.',
    ],
    'starts_with' => 'Het :attribute veld moet starten het een van het volgende: :values.',
    'string' => 'Het :attribute veld moet een string zijn.',
    'timezone' => 'Het :attribute veld moet een geldige tijdzone zijn.',
    'unique' => 'De :attribute is al in gebruik.',
    'uploaded' => 'De upload van :attribute is gefaald.',
    'uppercase' => 'Het :attribute veld moet alleen uit hoofdletters bestaan.',
    'url' => 'het :attribute veld moet een geldige URL zijn.',
    'ulid' => 'Het :attribute veld moet een geldige ULID zijn.',
    'uuid' => 'Het :attribute veld moet een geldige UUID zijn.',

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
