md = MarkDown
-> no code : facilement interprétable pour humains
-> mais se traduit facilement en code

ascii.doc : permet de faire de l'édition (fichiers epub)


# Symfony Promo (titre niveau 1)

Ce repository contient une application de gestion de formation. 
Il s'agit d'un projet pédagogique pour la promo 11.


## Prérequis (titre niveau 2)

- Linux, Mac OS ou Windows
- Bash
- PHP 8
- Composer
- Symfony-cli
* MariaDB 10
* Docker (optionnel)


## Installation

```
git clone https://github.com/SegoleneH/symfony_1
cd symfony
composer install
```

Créez une base de données & un utilisateur dédié pour cette base de données.


## Configuration

Créez un fichier `.env.local` à la racine du projet :

```
APP_ENV=dev
APP_DEBUG=true

APP_SECRET=123

DATABASE_URL="mysql://symfony:123@127.0.0.1:3306/symfony?serverVersion=mariadb-10.6.128&charset=utf8mb4"

```

Pensez à changer la variable `APP_SECRET` & les codes d'accès `123` dans la variable `DATABASE_URL`.

**ATTENTION : `APP_SECRET` doit être une chaîne e caractères de 32 caractères en hexadécimal.**


## Migration & Fixtures

Pour que l'application soit utilisable, vous devez créer le schéma de la base de données & charger des données :

```
bin/dofilo.sh
```


## Utilisation

Lancez le serveur web de développement

```
symfony serve
```

Puis ouvrez la page suivante : [https://localhost:8000](https://localhost:8000)


## Mentions légales

Ce projet est sous licence MIT.

La licence est disponible ici [LICENCE](LICENCE).