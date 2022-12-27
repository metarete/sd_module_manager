
# Setup

Sistemare il file .env in base alle proprie esigenze

Lanciare ambiente docker

```
$ cd <HOME-PROGETTO>
$ docker-compose up -d
```

Aggiornare pacchetti

```
$ composer install
$ composer update
```

Aggiornare node_modules

```
$ yarn install
$ yarn dev
```

Eseguire migrations

```
$ php bin/console doctrine:migrations:migrate
```

Nel caso in cui non si riesca a compilare con yarn: togliere i doppi apici alla prima riga nel file bootstrap-icons.scss (v. commento nel file "./node_modules/bootstrap-icons/font/bootstrap-icons.scss") e rilanciare yarn

All'avvio creare un utente admin con il comando app:crea-operatore nome cognome ruolo 
Il ruolo va scelto tra ROLE_USER e ROLE_ADMIN
