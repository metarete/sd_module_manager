
# Setup

Copiare il file .env.dist in .env e sistemarlo in base alle proprie esigenze (quello in repo già funziona, al netto dell'integrazione con SD Manager).

Lanciare ambiente docker

```
$ cd <HOME-PROGETTO>
$ docker-compose up -d
```
Entrare nel container 

docker exec -it sd_module_manager_php-fpm bash

Installare/aggiornare pacchetti, dall'interno del container "sd_module_manager_php-fpm"

```
$ composer install
$ composer update
```

Aggiornare node_modules

```
$ yarn install
```

e successivamente ricostruire gli asset pubblici, in sviluppo:

```
$ yarn dev
```

o in produzione:

```
$ yarn build
```

Nel caso in cui non si riesca a compilare con yarn: togliere i doppi apici alla prima riga nel file bootstrap-icons.scss (v. commento nel file "./node_modules/bootstrap-icons/font/bootstrap-icons.scss") e rilanciare yarn


Eseguire migrations

```
$ php bin/console doctrine:migrations:migrate
```

All'avvio creare un utente admin con il comando 

```
$ php bin/console app:crea-operatore nome cognome ruolo 
```

Il ruolo va scelto tra ROLE_USER e ROLE_ADMIN

Inserire nel database tutta la lista delle diagnosi con il comando

```
php bin/console app:inserisci-lista-diagnosi
```

Accedere all'applicativo all'indirizzo:

```
http://localhost:54001/
```


Gli utenti di SD Manager potranno entrare (una volta fatto il sync) dando la stessa username e password che usano per l'app.

__NOTA__: SD Madule Manager può essere utilizzato solo in combinazione con il backend di SD Manager (https://sdmanager.it), per il quale occorrono le chiavi API

## Cron

Dovranno essere attivati i seguenti comandi a cron:

* app:verifica: verifica se ci sono Schede PAI associate a un Progetto in scadenza tra 7 giorni e setta lo stato in 'verifica'
* app:scarica-assistiti: invoca le API di SD Manager per scaricare/aggiornare la lista degli Assistiti
* app:scarica-operatori: invoca le API di SD Manager per scaricare/aggiornare la lista degli Operatori
* app:scarica-progetti: invoca le API di SD Manager per scaricare/aggiornare la lista dei Progetti (con il flag "Richiese Scheda PAI" attivo)
* app:email: invia la mail di riepilogo giornaliera con le attività richieste da ciascun tipo di Operatore

