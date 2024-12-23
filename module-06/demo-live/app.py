import time

import redis
import os
from flask import Flask

app = Flask(__name__)
cache = redis.Redis(host='redis', port=6379)

def get_hit_count():
    retries = 5
    while True:
        try:
            return cache.incr('hits')
        except redis.exceptions.ConnectionError as exc:
            if retries == 0:
                raise exc
            retries -= 1
            time.sleep(0.5)

@app.route('/')
def hello():
    count = get_hit_count()

    # Afficher secret
    secret_path = "/run/secrets/my_secret"
    if os.path.exists(secret_path):
        with open(secret_path, 'r') as secret_file:
            secret_value = secret_file.read().strip()

    return f'Salut world ! I have been seen {count} times. Le secret : {secret_value}\n'
