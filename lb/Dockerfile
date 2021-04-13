FROM haproxy:2.3.9

LABEL maintainer="Guillaume Seznec <guillaume@seznec.fr>"

ENV DOMAIN="streaming-cluster"
ENV CN="streaming-cluster.test"

# installe OpenSSL 1.1.1d  10 Sep 2019
RUN apt-get update && \
    apt-get install -y openssl && \
    rm -rf /var/lib/apt/lists/*

# génère une clé privée unique (KEY) + le certificat (CRT)
RUN openssl req -x509 -newkey rsa:2048 -sha256 -days 365 -nodes \
  -keyout $DOMAIN.key -out $DOMAIN.crt -subj "/C=FR/CN=$CN" \
  -addext "subjectAltName=DNS:*.broadcast.$CN,DNS:broadcast1.$CN,DNS:broadcast2.$CN,DNS:broadcast3.$CN,DNS:broadcast4.$CN"

# génère le PEM (KEY + CRT) nécessaire à HaProxy
RUN cat $DOMAIN.key $DOMAIN.crt >> /etc/ssl/private/$DOMAIN.pem

# pour checker le certificat généré
# openssl x509 -noout -text -in /etc/ssl/private/$DOMAIN.pem

COPY haproxy.cfg /usr/local/etc/haproxy/haproxy.cfg
