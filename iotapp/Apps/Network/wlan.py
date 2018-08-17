import json
import netifaces
import subprocess
import sys
sys.path.append('/home/pi/iotapp')
from Classes import wifi


action = sys.argv[1]

data = json.loads(sys.argv[2])

res = wifi.Wifi.do(action,data)

print(json.dumps(res))