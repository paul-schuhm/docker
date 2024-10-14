# VERSION 0.2
# DOCKER-VERSION 0.3.4
# To build:
# 1. Install docker (http://docker.io)
# 2. Checkout source: git@github.com:gasi/docker-node-hello.git
# 3. Build container: docker build .

FROM centos:8

#Fix l'URL des dépots (mirror.centos.org n'existe plus depuis le 1er Juin 2024)
RUN sed -i s/mirror.centos.org/vault.centos.org/g /etc/yum.repos.d/CentOS-*.repo
RUN sed -i s/^#.*baseurl=http/baseurl=http/g /etc/yum.repos.d/CentOS-*.repo
RUN sed -i s/^mirrorlist=http/#mirrorlist=http/g /etc/yum.repos.d/CentOS-*.repo

#Update
RUN yum upgrade -y

# Install Node.js and npm
RUN curl -sL https://rpm.nodesource.com/setup_20.x | bash -
RUN dnf install nodejs -y

# App
ADD . /src
# Install app dependencies
RUN cd /src; npm install

EXPOSE  8080
CMD ["node", "/src/index.js"]