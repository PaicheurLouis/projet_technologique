Projet QCM — Application web de passage de QCM

Ce projet est une application web qui permet à des utilisateurs de créer un compte, de se connecter, puis de passer un QCM de 10 questions. Après avoir répondu aux questions, l’utilisateur obtient une note sur 20, voit ses mauvaises réponses avec les corrections, et peut consulter son historique ainsi que sa moyenne.

Une interface d’administration est aussi présente pour gérer les utilisateurs et les questions du QCM.

---

Technologies utilisées

Le projet utilise les technologies suivantes :

* HTML5
* CSS3
* PHP
* MySQL / MariaDB
* WAMP
* phpMyAdmin

Le projet est réalisé sans framework PHP.

---

Fonctionnalités utilisateur

Un utilisateur peut :

* créer un compte ;
* se connecter ;
* se déconnecter ;
* lancer un QCM ;
* répondre à 10 questions tirées aléatoirement ;
* choisir une réponse parmi 4 propositions ;
* obtenir une note sur 20 ;
* voir le nombre de bonnes réponses ;
* consulter les questions où il s’est trompé ;
* voir la bonne réponse en cas d’erreur ;
* consulter son historique de tentatives ;
* consulter sa moyenne générale.

---

Fonctionnalités anti-triche

Pendant le passage du QCM, plusieurs éléments limitent la triche :

* le QCM est lancé en plein écran ;
* une sortie du mode plein écran déclenche un avertissement ;
* un changement d’onglet ou de fenêtre déclenche aussi un avertissement ;
* après trop d’avertissements, le QCM peut être annulé.

---

Fonctionnalités administrateur

Un administrateur peut accéder à une interface réservée.

Depuis cette interface, il peut gérer les utilisateurs :

* voir la liste des utilisateurs ;
* bloquer un utilisateur ;
* supprimer un utilisateur.

Il peut aussi gérer les questions :

* ajouter une question ;
* modifier une question ;
* supprimer une question.

---

Base de données

La base de données s’appelle qcm_app.

Elle contient 4 tables principales :

utilisateurs :
id, nom, prenom, email, mot_de_passe, role, bloque, date_creation

questions :
id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie

tentatives :
id, utilisateur_id, score, date_tentative

reponses :
id, tentative_id, question_id, reponse_utilisateur, correcte

---

Installation du projet

1. Placer le dossier du projet dans WAMP

Le dossier du projet doit être placé ici :

C:\wamp64\www\qcm

2. Lancer WAMP

Il faut démarrer WAMP et vérifier que les services Apache et MySQL sont bien actifs.

3. Créer la base de données

Ouvrir phpMyAdmin avec cette adresse :

http://localhost/phpmyadmin

Créer ensuite une base de données nommée :

qcm_app

4. Importer le fichier SQL

Dans phpMyAdmin :

* sélectionner la base qcm_app ;
* aller dans l’onglet Importer ;
* choisir le fichier bdd.sql ;
* lancer l’importation.

5. Vérifier la connexion à la base de données

Dans le fichier includes/config.php, vérifier que les informations correspondent à la configuration locale :

host : localhost
dbname : qcm_app
username : root
password : vide

6. Lancer le site

Dans le navigateur, aller à l’adresse suivante :

http://localhost/qcm

---

Utilisation du site

Pour un utilisateur :

1. Aller sur la page d’inscription.
2. Créer un compte avec un nom, un prénom, un email et un mot de passe.
3. Se connecter.
4. Lancer un QCM depuis l’espace utilisateur.
5. Répondre aux 10 questions.
6. Valider le QCM.
7. Consulter le résultat, les corrections et l’historique.

Pour un administrateur :

Il faut se connecter avec un compte ayant le rôle admin.

L’administrateur peut ensuite accéder à l’interface d’administration pour gérer les utilisateurs et les questions.

---

Sécurité

Le projet contient plusieurs éléments de sécurité :

* les mots de passe sont hashés avec password_hash() ;
* les mots de passe sont vérifiés avec password_verify() ;
* les requêtes SQL utilisent PDO et des requêtes préparées ;
* les pages privées sont protégées avec les sessions PHP ;
* l’interface administrateur est réservée aux comptes ayant le rôle admin ;
* un compte utilisateur peut être bloqué.

---

Livrables du projet

Le projet contient les éléments demandés :

* le code source de l’application ;
* le fichier SQL de la base de données ;
* un README ;
* un schéma de la base de données ;
* une courte présentation du projet.
