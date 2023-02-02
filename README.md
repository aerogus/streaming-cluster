# Cluster de streaming

Le but du projet est de proposer un serveur de streaming vidéo à haute disponibilité et extensible.

Pour cela on va utiliser des briques logicielles libres :

- HAProxy
- NGinx
- nginx-rtmp-module
- ffmpeg
- openssl

## Installation

L'architecture proposée fait appel à plusieurs hosts, listons les dans notre `/etc/hosts` local :

```
127.0.0.1 streaming-cluster.test
127.0.0.1 broadcast1.streaming-cluster.test
127.0.0.1 broadcast2.streaming-cluster.test
127.0.0.1 broadcast3.streaming-cluster.test
127.0.0.1 broadcast4.streaming-cluster.test
```

On utilise l'environnement `Docker`

Sous MacOS, avec `homebrew` :

```
brew install docker
brew install docker --cask
brew install docker-compose
```

Lancez le démon `docker` (ou clic sur l'icone dans Applications)

Cloner localement le dépot et se rendre dans son répertoire

```
git clone https://github.com/aerogus/streaming-cluster
cd streaming-cluster
```

Lancer l'ensemble de l'architecture Docker (containeurs, réseau et volumes) :

```
docker compose up
```

Aller avec un navigateur sur `https://streaming-cluster.test`

## Architecture générale

@TODO faire un schéma de principe mieux que cette espèce d'équipe de foot

```
      LB1   LB2

  B1    B2    B3    B4

      DB1   DB2

       I1   I2

       S1   S2
```

## Présentation des containers

### lb (x2)

Les répartiteurs de charge, ils vont dispatcher les clients utilisant le domaine principal à travers
les différents serveurs web "broadcast".
Ils vont donner au client un jeton qui leur demandera d'utiliser le même serveur broadcast à chaque requête

## broadcast (x4)

Des serveurs web servant des fichiers statiques. La webapp mais aussi les fragments vidéo HLS.
Ils ont chacun une ip publique et peuvent acheminer les données directement au client final (sans passer par les répartiteurs de charge) ce qui permet d'augmenter d'autant la bande passante et donc la capacité d'accueil des téléspactateurs.

## db (x2)

S'il y a besoin de persistance (authentification d'utilisateurs, messages de chat...) une base de données partagée peut être nécessaire. Elle peut être redondée également.

## ingest (x2)

Serveurs d'ingest, ils s'occupent de réceptionner le flux vidéo source du broadcaster (issu d'OBS par exemple)
et de le réencoder à plusieurs débits différents, de plus ils le découpent façon puzzle en p'tits bouts de trucs
les .ts (fragment vidéos) référencés dans des listes de lectures par débit (.m3u8), elles mêmes référencées
dans une liste de lecture globale que le lecteur vidéo public lira.

## source (x2)

Pour les tests uniquement, un générateur d'une mire audio/vidéo remplaçant le réel contenu

# Production

Pour le passage en production

- ne pas utiliser de certificats TLS/SSL autosignés mais au moins Let's Encrypt.
- le nombre de nodes "broadcast" est dépendant de la charge attendue. Ex de calcul :

| Débit vidéo \ Viewers |        1 |      100 |     1000 |    5000 |
| :-------------------- | -------: | -------: | -------: | ------: |
| 1 Mbps                |   1 Mbps | 100 Mbps |   1 Gbps |  5 Gbps |
| 2 Mbps                |   2 Mbps | 200 Mbps |   2 Gbps | 10 Gbps |
| 3.5 Mbps              | 3.5 Mbps | 350 Mbps | 3.5 Gbps | 35 Gbps |
| 5 Mbps                |   5 Mbps | 500 Mbps |   5 Gbps | 50 Gbps |

Un serveur du commerce propose un débit montant généralement à partir de 100 Mbps
pour les plus bons marchés, jusqu'à 10 Gbps pour les plus chers.

- Bien tester l'ajout/retrait de nodes dynamiquement, préparer les commandes, les scripter ...
- Ce sont surtout les serveurs ingest qui ont besoin de cpu pour l'encodage
- Les broadcast ont surtout besoin de bande passante
