import json
import netifaces
import subprocess
import re
import sys
sys.path.append('/home/pi/iotapp')
from Classes import net

net_info = net.Network()


addr_info = gw_info = dns_info =None


def ipinfo():
    info = re.split('\n',subprocess.check_output('ifconfig').decode())
    return net_info.getnetinfo(info)

def gatewayinfo():
    gwinfo = re.split('\n',subprocess.check_output(['ip','route']).decode())
    return net_info.getgateway(gwinfo)

def dnsinfo():
    info=[]
    with open('/etc/resolv.conf','r') as f:
        data = f.read().split('\n')
        info = net_info.getdns(data)
    f.close()
    return info

def fallback():
    wlan_info= netifaces.ifaddresses('wlan0')
    eth_info = netifaces.ifaddresses("eth0")
    gw = netifaces.gateways()



addr_info = ipinfo()
gw_info = gatewayinfo()
dns_info = dnsinfo()

addressInfo = {'address':addr_info,'dns':dns_info,'gateways':gw_info}

print(json.dumps(addressInfo))
