**Pierre Hansen, Projet 6 OpenClassroom, "Développez de A à Z le site communautaire SnowTricks"**
### Instructions d'installation
#### Etape 1 - **Créez votre dossier**

Choisissez le dossier dans lequel vous souhaitez cloner le dossier et initialisez-y Git à l'aide de GitBash en executant 'git init'.  

#### Etape 2- **Clonez le projet**

Pour se faire rendez vous sur l'onglet "<> Code" sur la page du projet GitHub et cliquez sur le bouton vert "Code". Copiez ensuite le lien HTTPS.  

Puis clonez le projet avec la commande 'git clone URL'.  

Réalisez dans le terminal la commande "composer install".  

Paramétrez ensuite le fichier généré ".env" pour les informations de connexion à la base de données.

#### Etape 3- **Intégrez la base de donnée**

Depuis votre console saisissez "symfony console" (ou php bin/console) "doctrine:migrations:migrate".

Ensuite saisissez la commande "doctrine:fixtures:load" afin de charger les data fixtures. 

A noter que le mot de passe pour les utilisateurs générés est "motdepasse". 

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b5f5dde55f6345929763aeb820d3a63a)](https://www.codacy.com/gh/HsnPierre/DAPHPSymfony_Hansen_Pierre_P6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=HsnPierre/DAPHPSymfony_Hansen_Pierre_P6&amp;utm_campaign=Badge_Grade)  