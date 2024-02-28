FROM gcc:4.9
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
RUN gcc -o guess-my-number -static main.c
CMD ["./guess-my-number"]