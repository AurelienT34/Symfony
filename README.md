# Symfony

###### PORTAL Pierre 21502405
###### TROUCHE Aurélien 21502994
###### http://portal-trouche-blog.herokuapp.com/

# Comptes et accès

## Administrateur
###### Email: aurelien@symfony.com
###### Mot de passe: aurelien@symfony.com

## Editeur
###### Email: editeur@symfony.com
###### Mot de passe: editeur@symfony.com

## Utilisateur
###### Email: test3@symfony.com
###### Mot de passe: test3@symfony.com

# Commentaires
  * Controller --> OK
  * CRUD --> OK
  * timestampable --> OK
  * sluggable --> OK
  * API --> OK
  * API 5 DERNIER ARTICLES POST --> OK
  * API 5 DERNIERE ARTICLES GET --> OK
  * HERBERGEMENT --> OK
  * Securité --> OK
  * Roles --> OK
  * Bootstrap --> OK
  * Poster un commentaire --> OK

# Fonctionnement

###### Seul les editeurs et les administrateur peuvent toucher aux articles (création et modification).
###### Les utilisateurs peuvent consulter les articles et poster des commentaires.
###### Utilisation d'un bundle pour l'api et génération d'un json "à la main" sur la page d'acceuil.
###### Méthodes create et update d'article dans la même fonction du controller.
###### Utilisation des forms de symfony,Gedmo,Entity,EvenListener,TsVector.
###### Sécurisation des routes avec le prefixe des routes.
###### Gestion de l'unicité des email dans l'entité.
###### Gestion de la correspondance du mot de passe et de confirmation mot de passe dans l'entité.
###### Gestion de la taille des attributs dans l'entité.

###### Les fixtures sont couplés avec la librérie Faker (https://github.com/fzaninotto/Faker) lors de nos tests en local.
###### Si l'url rentrée  n'est pas bonne ou que l'utilisateur ne dispose pas des droits, alors il est redirigé vers la page d'acceuil.

###### Pour créer un administrateur ou un editeur il faut d'abbord créer un compte normal puis avec un compte d'admin (aurelien@symfony.com), vous pouvez modifier les droits des utilisateurs dans l'onglet "Utilisateurs".

###### Nous n'avons pas mis la possibilitée de créer des catégories à la volée.
###### Les utilisateurs peuvent modifier leurs informations personnels.
###### La recherche fonctionne par catégorie et avec le full text.

# MYSQL VS POSTGRESQL

###### Beaucoup de problème de base de donnée sont survenus.
###### Nous avions commencé avec Mysql en local mais Heroku utilise Postgresql.
###### Postgresql a "User" comme terme protégé et pas Mysql, nous avons donc renomé "User" en "UserName".
###### Pour le slug, Mysql crée l'id de l'objet trop tard pour Gedmo, alors les slug etaient composé uniquement du nom de l'article.
###### Nous sommes donc passés sur Postgresql en local.
###### Le passage de Postgresql à fait buggé la recherche full-text. 
###### Donc dans le code, il y a une extension MatchAgainst qui permet à Mysql de fonctionné avec deux index fulltext (title&content) et il y a l'extension Tsvector (https://packagist.org/packages/vertigolabs/doctrine-full-text-postgres) qui fait en sorte que Postgresql fonctionne.

###### La recherche se fait dans "ArticleRepository", la fonction s'appelle search.

