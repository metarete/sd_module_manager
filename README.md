
# Setup

<<<<<<< Updated upstream
Sistemare il file .env in base alle proprie esigenze
(Copiare in file env.dist in .env)
=======
Copiare il file .env.dist in .env e sistemarlo in base alle proprie esigenze (quello in repo già funziona, al netto dell'integrazione con SD Manager).
>>>>>>> Stashed changes

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
$ yarn dev
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

Accedere all'applicativo all'indirizzo:

```
http://localhost:54001/
```


Gli utenti di SD Manager potranno entrare (una volta fatto il sync) dando la stessa username e password che usano per l'app.

__NOTA__: SD Madule Manager può essere utilizzato solo in combinazione con il backend di SD Manager (https://sdmanager.it), per il quale occorrono le chiavi API
