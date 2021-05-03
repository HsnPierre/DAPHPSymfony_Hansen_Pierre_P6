**Pierre Hansen, Projet 6 OpenClassroom, "Développez de A à Z le site communautaire SnowTricks"**
### Instructions d'installation
#### Etape 1 - **Créez votre dossier**

Rendez vous dans votre dossier 'www' ou 'htdocs' selon votre serveur puis créez le dossier dans lequel vous souhaitez cloner le fichier (vous pouvez aussi directement cloner le fichier directement dans 'www' ou 'htdocs').  

#### Etape 2- **Clonez le projet**

Pour se faire rendez vous sur l'onglet "<> Code" sur la page du projet GitHub et cliquez sur le bouton vert "Code". Copiez ensuite le lien HTTPS.  

Rendez vous ensuite dans le dossier créé plus tôt avec GitBash - si ce n'est pas déjà fait initialisez Git dans ce dossier en executant 'git init' sur GitBash-  

Puis clonez le projet avec la commande 'git clone URL'.  

#### Etape 3- **Intégrez la base de donnée**

Depuis votre console saisissez "symfony console" (ou php bin/console) "doctrine:migrations:migrate".

Ensuite saisissez la commande "doctrine:fixtures:load" afin de charger les data fixtures. 

A noter que le mot de passe pour les utilisateurs générés est "motdepasse". 

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b5f5dde55f6345929763aeb820d3a63a)](https://www.codacy.com/gh/HsnPierre/DAPHPSymfony_Hansen_Pierre_P6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=HsnPierre/DAPHPSymfony_Hansen_Pierre_P6&amp;utm_campaign=Badge_Grade)  


