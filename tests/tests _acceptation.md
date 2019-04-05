** Anonymous user

Test 1 : Vérification des données d'enregistrement
- Cliquer sur le lien de la page d'enregistrement
- Le formulaire d'enregistrement s'affiche
- Entrer les informations de l'utilisateur à enregistrer dans le formulaire puis valider
- Si le format de toutes les données est correct et si la date de naissance traduit un âge d'au moins 23 ans : redirection vers la page de login avec un message indiquant que les données ont été postées avec succès
- Si le format d'au moins l'une des données est incorrect ou si la date de naissance traduit un âge inférieur à 23 ans, nouvelle invitation à saisir avec message explicatif d'erreur

Test 2 : Authentification
- Cliquer sur le lien de la page d'enregistrement 
- Enregistrer un utilisateur avec un email et un mot de passe corrects
- La page de login s'affiche
- Entrer l'email et le mot de passe puis valider
- Si le mot de passe correspond à l'email : l'authentification se réalise avec succès
- Si le mot de passe ne correspond pas à l'email : l'authentification est refusée



** Authenticated user

Test 3 : Lecture de tous les articles
- Cliquer sur le lien "Tous les articles"
- L'ensemble des images et des titres s'affichent (sous forme de cards)

Test 4 : Lecture d'un article
- Cliquer sur le lien "Tous les articles"
- L'ensemble des images et des titres s'affichent (sous forme de cards)
- Cliquer sur la card correspondant à l'article
- L'article s'affiche avec son contenu sur une nouvelle page

Test 5 : Lecture des articles de l'utilisateur
- Cliquer sur le lien "Mes articles"
- Seuls les articles écrits par l'utilisateur s'affichent

Test 6 : Lecture d'un article de l'utilisateur
- Cliquer sur le lien "Mes articles"
- L'ensemble des images et des titres s'affichent (sous forme de cards) 
- Cliquer sur la card correspondant à l'article
- L'article s'affiche avec son contenu sur une nouvelle page 

Test 7 : Modification d'un article
- Cliquer sur le lien "Mes articles"
- L'ensemble des images et des titres s'affichent (sous forme de cards) 
- Cliquer sur la card correspondant à l'article
- L'article s'affiche avec son contenu sur une nouvelle page
- Cliquer sur le bouton "Modifier"
- Une page s'affiche avec un formulaire dans lequel figurent les données existantes de l'article
- Modifier les données de l'article puis valider
- L'article actualisé s'affiche sur une nouvelle page

Test 8 : Suppression d'un article
- Cliquer sur le lien "Mes articles"
- L'ensemble des images et des titres s'affichent (sous forme de cards) 
- Cliquer sur la card correspondant à l'article
- L'article s'affiche avec son contenu sur une nouvelle page
- Cliquer sur le bouton "Supprimer"
- Une fenêtre s'affiche pour confirmation ou infirmation
- Si confirmation : la page "Mes articles" s'affiche, et l'article supprimé n'y figure plus
- Si infirmation : la page de l'article est retournée

Test 9 : Création d'un article
- Cliquer sur le lien "Créer un article"
- Le formulaire de création s'affiche
- Entrer le titre, le contenu et la catégorie de l'article à créer, puis valider
- La page "Mes articles" s'affiche, avec en son sein le nouvel article créé

Test 10 : Modification des infos de son profil utilisateur
- Cliquer sur le lien "Mon profil"
- Une page s'affiche avec les données du profil
- Cliquer sur le bouton "Modifier" 
- Une page s'affiche avec un formulaire dans lequel figurent les données existantes du profil
- Modifier les données du profil puis valider
- La page de profil s'affiche avec les données actualisées

** Admin user