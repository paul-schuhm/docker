import time
import os

import redis
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
    # Chemin vers le fichier secret
    secret_file_path = "/run/secrets/my_secret"  # Ce chemin peut varier selon le système d'exploitation

    # Vérifier si le fichier secret existe
    if os.path.exists(secret_file_path):
        # Lire le contenu du fichier secret
        with open(secret_file_path, 'r') as secret_file:
            secret_value = secret_file.read().strip()
    else:
        secret_value = 'not found, sorry'
    return 'Coucou  ! I have seen this {} times. Voici le secret : {}\n'.format(count, secret_value)
