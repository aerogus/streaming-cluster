#!/usr/bin/env bash

##
# génération des certificats
# ancienne méthode
# ne pas utiliser
# on peut faire + court
##

# nom des fichiers générés
PROJECT="streaming-cluster.test"

# génère une clé privée unique (KEY)
openssl genrsa -out "$PROJECT.key" 2048

# génère un demande de signature de certificat (CSR)
openssl req -new -config cert.conf -key "$PROJECT.key" -out "$PROJECT.csr"

# crée le certificat auto-signé (CRT) à partir du CSR + KEY
openssl x509 -req -days 365 -in "$PROJECT.csr" -signkey "$PROJECT.key" -out "$PROJECT.crt"

# génère le PEM (KEY + CRT)
cat "$PROJECT.key" "$PROJECT.crt" >> "/etc/ssl/private/$PROJECT.pem"
