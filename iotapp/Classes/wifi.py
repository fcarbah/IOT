import subprocess
import re

class Wifi:

    @staticmethod
    def do(action,data:dict):

        obj = Wifi()
        if(action=='add'):
            return obj.addwifi(data)
        elif(action=='delete'):
            return obj.removewifi(data['ssid'])
        elif(action=='edit'):
            return obj.editWifi(data)
        else:
            return {'error':True,'msg':'Invalid Operation'}

    def addwifi(self,info:dict):
        network = self.__newNetwork(info)

        data = self.__readInterfaceFile();
        networks = data['networks']

        if self.__exists(info['ssid'],networks):
            return {'error':True,'msg':'SSID already exist'}

        newData = self.__formatNetworks(networks);
        newData.append(network)
        newData.insert(0,data['title']);

        self.__writeInterfaceFile(newData)

        return {'error':False}


    def editWifi(self,info:dict):
        data = self.__readInterfaceFile();
        networks = data['networks']
        net = None
        for x in range(0, len(networks)):
            if re.match(r'.*#app.*', networks[x]) and re.match(r'.*' + info['oldssid'] + '.*', networks[x]):
                del networks[x]
                net = self.__newNetwork(info)
                break

        newData = self.__formatNetworks(networks)

        if(net != None):
            newData.append(net)

        newData.insert(0, data['title'])
        self.__writeInterfaceFile(newData)

        return {'error': False}


    def removewifi(self,ssid):
        data = self.__readInterfaceFile();
        networks = data['networks']

        for x in range(0,len(networks)):
            if re.match(r'.*#app.*',networks[x]) and re.match(r'.*'+ssid+'.*',networks[x]):
                del networks[x]
                break

        newData = self.__formatNetworks(networks)
        newData.insert(0,data['title'])
        self.__writeInterfaceFile(newData)

        return {'error':False}


    def __exists(self,ssid,networks:list):
        for net in networks:

            if re.match('.*' + ssid + '.*', net):
                return True

        return False



    def __formatNetworks(self,networks:list):

        formatted = []
        length = len(networks)

        for i in range(0,length):
            sp = '\n\t'
            fm = re.sub('\s+',sp,networks[i])
            indx = fm.rindex(sp)
            n_str = fm[0:indx]+'\n'+fm[indx+2:]+'\n\n'
            formatted.append(n_str)

        return formatted

    def __newNetwork(self,info:dict):
        if(info['password'] != ''):
            return 'network={\n\tssid="'+ info['ssid'] + '"\n\tpsk="'+info['password']+'"\n\t#app="true"\n}\n\n'

        return 'network={\n\tssid="' + info['ssid'] + '"\n\t#app="true"\n}\n\n'

    def __readInterfaceFile(self):

        f = open('/etc/wpa_supplicant/wpa_supplicant.conf','r')
        original = f.read();
        data = re.sub(r'\s',' ',original)
        f.close()

        networks = re.findall(r'network[=][{].*?[}]',data);
        others = original[0:original.index('network')]

        configs = [];

        #for x in networks:
            #configs.append(re.sub(r'network[=][{]|[}]','',x))

        return {'networks':networks,'title':others}


    def __writeInterfaceFile(self,data:list):
        f = open('/etc/wpa_supplicant/wpa_supplicant.conf', 'w+')
        f.writelines(data);
        f.close()