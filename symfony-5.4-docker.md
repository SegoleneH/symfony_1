# Symfony 5.4 - Docker

Symfony est livré avec un fichier `docker-compose.yml` et `docker-compose.override.yml` qui permettent d'utiliser la BDD PostgreSQL et un serveur de mail de test nommé `MailCatcher` dans des containers docker.

Nous allons voir comment utiliser docker dans ce contexte.

## Installation du driver PostgreSQL pour PHP

Vérifiez votre numéro de version de PHP :

```
php --version
```

Installez le driver :

```
sudo apt install -y php8.2-pgsql
```

Attention : adaptez le numéro de version de PHP à votre version.

## Configuration des ports publiques des services

Ouvrez le fichier `docker-compose.override.yml` et modifiez les lignes suivantes :

```
  version: '3'
  
  services:
  ###> doctrine/doctrine-bundle ###
    database:
      ports:
-       - "5432"
+       - "5432:5432"
  ###< doctrine/doctrine-bundle ###
  
  ###> symfony/mailer ###
    mailer:
      image: schickling/mailcatcher
-     ports: ["1025", "1080"]
+     ports: ["1025:1025", "1080:1080"]
  ###< symfony/mailer ###
```

Ces modifications permettent de choisir le port publique des containers.
Le numéro à gauche du `:` deux point est le port publique, celui à droite est le port privé.
Le port publique est celui que que la machine hôte peut utiliser pour se connecter au serice, c-à-d celui que PHP pourra utiliser.

## Configuration des codes d'accès de la BDD dans PostgreSQL

Ces codes d'accès seront utilisé pour créer le user et la BDD dans PostgreSQL.

Si vous voulez changer les codes d'accès par défaut, ouvrez le fichier `docker-compose.yml` et modifiez les lignes ci-dessous :

```
-       POSTGRES_DB: ${POSTGRES_DB:-app}
+       POSTGRES_DB: ${POSTGRES_DB:-ma_bdd}
        # You should definitely change the password in production
-       POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-!ChangeMe!}
+       POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-mon_password}
-       POSTGRES_USER: ${POSTGRES_USER:-app}
+       POSTGRES_USER: ${POSTGRES_USER:-mon_user}
```

Personnellement, j'utilise les codes d'accès par défaut.

## Configuration des codes d'accès de la BDD dans Symfony

Ces codes d'accès seront utilisés par Symfony pour accéder à la BDD.

Assurez-vous que la variable `DATABASE_URL` dans votre fichier `.env` (ou `.env.local` ou autre) utilise bien le protocole `postgresql`.

Exemple dans `.env` :

```
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
```

Attention : si une variable `DATABASE_URL` est présente dans un fichier `.env.*`, elle écrasera celle de `.env`.

## Adaptation de la classe `User` pour assurer la compatibilité avec PostgreSQL

Pour être sûr que la classe `App\Entity\User` est bien compatible avec PostgreSQL, il va falloir la modifier un petit peu.

Ouvrez le fichier `src/Entity/User.php` et modifiez-le :

```
  #[ORM\Entity(repositoryClass: UserRepository::class)]
+ #[ORM\Table(name: 'my_user')]
  class User implements UserInterface, PasswordAuthenticatedUserInterface
```

Cette modification indique à doctrine que la table contenant les users devra s'appeler `my_user` (et non `user`).
Cela s'avère nécessaire car le mot clé `user` est un mot clé réservé dans PostgreSQL.
En réalité, cette modification n'est pas indispensable pour créer le schéma de BDD, mais personnellement j'ai eu des erreurs plus tard, lors de l'injection des fixtures.

Cette modification modifie le schéma de BDD, il faut donc créer un fichier de migration et l'appliquer :

```
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Mise en route des containers docker

Dans un premier terminal, vous pouvez lancer :

```
docker composer up --build
```

Dans un deuxième terminal, vous pouvez lancer :

```
bin/dofilo.sh
```

Si tout s'est bien passé, vous pouvez lancer votre serveur web de développement :

```
symfony serve
```

Et tester votre application [https://localhost:8000](https://localhost:8000).

## Arrêt des containers docker

Pour stopper les containers, un simple `CTRL C` fait l'affaire.

## Suppression des containers docker

Pour supprimer les containers vous pouvez lancer la commande suivante dans un terminal :

```
docker composer down
```

## Trouble shooting

Si vous avez des problèmes, avant de refaire chaque étape, prenez soin de bien supprimer le volume associé au container :

1. Stoppez les containers avec la commande `docker compose down`
2. Affichez la liste des volumes utilisé avec la commande `docker volume ls`
3. Supprimez le volume associé à votre container avec la commande `docker volume rm symfony-p11_database_data` (adaptez le nom du volume par rapport à ce que dit l'étape 2)

Et à la fin, pensez aussi à stopper et relancer votre serveur web de développement.

