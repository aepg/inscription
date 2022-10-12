
# Inscription AEPG
Application Web pour gérer les inscriptions aux activités de la fête des écoles.

voir en live : http://inscription.aepg.fr


## Pré-requis

* Mysql 5.5+
* PHP 7.2+

## Lancer les développements

Lancer la commande:

```
docker-compose up -d
```

Ouvrir un navigateur sur l'adresse `localhost`.

## Création de la structure de BDD

Le conteneur docker est mappé avec un répertoire local `./mysqldata`.

* Créer une base `inscription`.
* Exécuter le script `database.sql` (via DBeaver par exemple).

## Source et paramétrage
les sources sont disponibles dans le répertoire `www`.

Les paramètres de connexion à la base de données sont disponibles dans le fichier `www/mysql.php`.

## affichage des logs

pour voir les logs :
```
docker-compose logs -f
```

## Arrêt du serveur

Pour stopper:
```
docker-compose down
```



## 
Titre par défaut : 

Inscription aux activités de la fête des écoles
Sous-titre par défaut : 
Aidez l'AEPG en participant aux activités et stands !
