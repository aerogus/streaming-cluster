FROM debian:buster

LABEL maintainer="Guillaume Seznec <guillaume@seznec.fr>"

# Host ou ip de destination
ARG INGEST_IP

ENV RESOLUTION 1280x720
ENV FRAMERATE 25
ENV CRF 18

# Install dependencies
RUN apt-get update && \
    apt-get install -y ffmpeg && \
    rm -rf /var/lib/apt/lists/*

CMD ffmpeg \
    -re \
    -f lavfi \
    -i smptebars \
    -crf $CRF \
    -s $RESOLUTION \
    -r $FRAMERATE \
    -f flv \
    rtmp://$INGEST_IP:1935/ingest/mire

