import json
import sys
sys.path.append('/home/pi/iotapp')
from Classes import system


sys_info = system.System().getInfo()

print(json.dumps(sys_info))


