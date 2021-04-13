# nginx et rtmp

Construction de l'image `nginx-rtmp` à partir du `Dockerfile`

```
docker build --tag nginx-rtmp:1.0 .
```

L'image construite fait environ 840Mo

Démarrer les containers `ingest1` et `ingest2` à partir de l'image `nginx-rtmp`

```
docker run -d -p 1935:1935 --name ingest1 nginx-rtmp:1.0
docker run -d -p 1936:1935 --name ingest2 nginx-rtmp:1.0
```

Streamer une mire

```
ffmpeg -re -f lavfi -i smptebars -crf 18 -s 1280x720 -r 25 -f flv rtmp://localhost:1935/ingest/mire
ffmpeg -re -f lavfi -i smptebars -crf 18 -s 1280x720 -r 25 -f flv rtmp://localhost:1936/ingest/mire
```


Sous MacOS

```
brew install docker
brew install docker --cask
```

Lancer le daemon Docker puis l'ensemble des containers

```
docker compose up
```

/etc/hosts du host Docker

```
127.0.0.1 streaming-cluster.test
127.0.0.1 broadcast1.streaming-cluster.test
127.0.0.1 broadcast2.streaming-cluster.test
127.0.0.1 broadcast3.streaming-cluster.test
127.0.0.1 broadcast4.streaming-cluster.test
127.0.0.1 ingest1.streaming-cluster.test
127.0.0.1 ingest2.streaming-cluster.test
127.0.0.1 db1.streaming-cluster.test
127.0.0.1 db2.streaming-cluster.test
```

