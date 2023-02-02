FROM buildpack-deps:buster

LABEL maintainer="Guillaume Seznec <guillaume@seznec.fr>"

# cf. https://github.com/nginx/nginx
# version "stable"
ENV NGINX_VERSION nginx-1.23.3
# version "mainline"
#ENV NGINX_VERSION nginx-1.19.9
# cf. https://github.com/arut/nginx-rtmp-module
ENV NGINX_RTMP_MODULE_VERSION 1.2.2

# Install dependencies
RUN apt-get update && \
    apt-get install -y ffmpeg ca-certificates openssl libssl-dev && \
    rm -rf /var/lib/apt/lists/*

# Download and decompress Nginx
RUN mkdir -p /tmp/build/nginx && \
    cd /tmp/build/nginx && \
    wget -O ${NGINX_VERSION}.tar.gz https://nginx.org/download/${NGINX_VERSION}.tar.gz && \
    tar -zxf ${NGINX_VERSION}.tar.gz

# Download and decompress RTMP module
RUN mkdir -p /tmp/build/nginx-rtmp-module && \
    cd /tmp/build/nginx-rtmp-module && \
    wget -O nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}.tar.gz https://github.com/arut/nginx-rtmp-module/archive/v${NGINX_RTMP_MODULE_VERSION}.tar.gz && \
    tar -zxf nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}.tar.gz && \
    cd nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}
# https://github.com/sergey-dryabzhinsky/nginx-rtmp-module.git plus récent ?

# Compilation nginx
RUN cd /tmp/build/nginx/${NGINX_VERSION} && \
    ./configure \
        --sbin-path=/usr/local/sbin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --pid-path=/var/run/nginx/nginx.pid \
        --lock-path=/var/lock/nginx/nginx.lock \
        --http-log-path=/var/log/nginx/access.log \
        --http-client-body-temp-path=/tmp/nginx-client-body \
        --with-http_ssl_module \
        --with-threads \
        --with-ipv6 \
        --add-module=/tmp/build/nginx-rtmp-module/nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION} \
        --with-cc-opt="-Wimplicit-fallthrough=0" && \
    make -j $(getconf _NPROCESSORS_ONLN) && \
    make install && \
    mkdir /var/lock/nginx && \
    rm -rf /tmp/build

# Forward logs to Docker
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

# Set up config file
COPY nginx.conf /etc/nginx/nginx.conf
COPY stat.xsl /etc/nginx/stat.xsl

# répertoires de stockage des hls et flv d'enregistrement
RUN mkdir -p /data/hls /data/dash /data/records

EXPOSE 1935 80
CMD ["nginx", "-g", "daemon off;"]
