# VERSION 0.2
# DOCKER-VERSION 0.3.4
# To build:
# 1. Install docker (http://docker.io)
# 2. Checkout source: git@github.com:gasi/docker-node-hello.git
# 3. Build container: docker build .

FROM    centos:centos7

# Enable EPEL for Node.js
# RUN rpm -Uvh https://mirror.in2p3.fr/pub/epel/epel-release-latest-7.noarch.rpm
RUN rpm -Uvh https://mirror.in2p3.fr/pub/epel/epel-release-latest-7.noarch.rpm
# Install Node.js and npm
RUN     yum install -y -q npm

# App
ADD . /src
# Install app dependencies
RUN cd /src; npm install

EXPOSE  8080
CMD ["node", "/src/index.js"]
