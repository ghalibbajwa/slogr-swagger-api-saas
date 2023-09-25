from time import sleep
import requests

import requests


while(True):
    x = requests.get('http://localhost/api/mq2')
    print(x)
    sleep(1)
