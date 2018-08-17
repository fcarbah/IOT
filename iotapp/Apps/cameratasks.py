import json
import sys
sys.path.append('/home/pi/iotapp')
from Classes.components import  Camera

action = sys.argv[1]

camera = Camera()

res = camera.run(action)

print(json.dumps(res))