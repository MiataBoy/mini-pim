### Installatie van deze Minipim applicatie
*$ characters betekent dat dit een apart commando is*

Installatie van alle benodigde packages gaat via NPM en Composer:
```shell
$ npm i; composer install
```

Voor de applicatie moet je een key hebben. Deze kun je genereren:
```shell
$ php artisan key:generate
```

Als deze packages geïnstalleerd zijn moet je ook de .env opzetten met de correcte data.
**Voeg ook het IP van je computer toe voor IP inlogfunctionaliteit**. MSSQL database is vereist.

Hierna moet je de applicatie runnen:

*Het IP waar je de server op runt moet overeenkomen met het APP_URL IP in de .env*
```shell
$ npm run watch
$ php artisan serve --host=deviceIp:port
```


Voordat je inlogt of de applicatie gebruikt, moet je eerst de migrations runnen:
```shell
$ php artisan migrate
```

en de seeders runnen:
```shell
$ php artisan db:seed --class=databaseSeeder
```

De minipim applicatie start altijd met het 'Intermix' account.
