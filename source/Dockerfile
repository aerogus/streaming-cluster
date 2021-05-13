FROM buildpack-deps:buster

LABEL maintainer="Guillaume Seznec <guillaume@seznec.fr>"

# Host ou ip de destination
ARG INGEST_IP

ENV RESOLUTION 1920x1080
ENV FRAMERATE 25
ENV CRF 18
ENV INGEST_PORT 1935

ENV APP ingest
ENV NAME mire

# Install dependencies
RUN apt-get update && \
    apt-get install -y ffmpeg && \
    rm -rf /var/lib/apt/lists/*

CMD ffmpeg \
    -re \
    -f lavfi -i "smptebars=rate=$FRAMERATE:size=$RESOLUTION" \
    -f lavfi -i "sine=frequency=1000" \
    -c:v libx264 -b:v 4096k \
    -c:a aac -b:a 128k \
    -crf $CRF \
    -f flv \
    rtmp://$INGEST_IP:1935/$APP/$NAME

