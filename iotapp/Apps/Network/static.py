import json
import sys
sys.path.append('/home/pi/iotapp')
from Classes import net

net_info = net.Network()

data = json.loads(sys.argv[1])

#res = net_info.setIPInfo(data)
#test_data ={'ip':'192.168.0.4','subnet':'255.255.255.0','gateway':'192.168.0.1','dns':['192.168.0.1',''],'mode':2}

res = net_info.set(data)

print(json.dumps(res))
