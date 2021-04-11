Liste des utilisateurs, fichier `users.csv` :

```
gus
jimmy
alann
billy
morgan
cloud
serge
bea
aymeric
manu
```

extrait de la conf nginx pour verrou par mdp

```
server {

  # toutes les urls sont protégées
  auth_basic "Zone protégée";
  auth_basic_user_file conf/htpasswd;

  # sauf ce répertoire
  location /public/ {
    auth_basic off;
  }

}
```

Générer un fichier htpasswd à partir de la liste des utilisateurs `users.csv`

touch .htpasswd
htpasswd .htpasswd $user <