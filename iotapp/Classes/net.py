import re
import subprocess

class Network:

    __subpattern= r"\s{2,}\w+"

    def getnetinfo(self,info:list):
        item = {}
        head = ''
        for i in info:

            obj = None
            if( not re.match(self.__subpattern,i) and not i==''):
                head = re.split('\s+',i)[0]
                item[head] ={}
            if(not i==''):
                obj = self.__details(i)

            if not obj == None:
                item[head].update(obj)
        return item


    def getgateway(self,info:list):
        part1 = re.split('\s+',info[0])
        part2 = re.split('\s+', info[1]) if len(info) <4 else re.split('\s+', info[3])
        gws = [];
        for x in info:
            item =self.__gwdetails(x)
            if item != None:
                gws.append(item)

        return gws
        #return {'gateway':part1[2],'interface':part1[4],'cidr':part2[0].split('/')[1]}


    def getdns(self,info:list):
        dns =[]

        for i in info:
            if self.__ismatch(i,'nameserver.*'):
                dns.append(re.split('\s+',i)[1])

        return dns

    def set(self,info:dict):
        lines =[]
        file = open('/etc/network/interfaces','r')
        lines = file.readlines()
        file.close()

        indices = self.__getStartEnd(lines)
        newConfig = self.__dataToWrite(info)

        newData= lines[0:indices['start']]
        newData.extend(newConfig)
        newData.extend(lines[indices['end']:])

        fw = open('/etc/network/interfaces','w+')
        fw.writelines(newData)
        fw.close()

        subprocess.call(['ifdown', 'eth0'])
        subprocess.call(['ifup', 'eth0'])

        return {'error':False}


    def __dataToWrite(self,info:dict):
        if(info['mode']==1):
            return ['iface eth0 inet manual\n\n']

        elif(info['mode'] == 2):
            dns= ' '.join(info['dns'])
            return [
                'auto eth0\n','iface eth0 inet static\n',
                'address '+info['ip']+'\n',
                'netmask '+info['subnet']+'\n',
                'gateway '+info['gateway']+'\n',
                'dns-nameservers '+dns+'\n\n'
            ]

        else:
            return []


    def __getStartEnd(self,lines):
        start_indx = end_indx = None

        for x in range(0, len(lines)):

            if (re.search(r'eth0', lines[x]) and start_indx == None):
                start_indx = x

            elif (start_indx != None and re.search(r'(wlan\d|lo\d|eth[1-9]\d?)', lines[x])):
                end_indx = x;
                break;

        return {'start':start_indx,'end':end_indx}



    def __setIPInfo(self,info:dict):

        if(info['mode']==1):
            self.__cleardns()
            cmd = ['dhclient','eth0']
            subprocess.call(cmd)

        elif(info['mode']==2):
            cmd = ['sudo','ifconfig','eth0',info['ip'],'netmask',info['subnet']]
            cmd2 = ['sudo','route','add','default','gw',info['gateway'],'eth0']
            cmd3 = ['ifdown','eth0']
            cmd4 = ['ifup','eth0']
            cmds =[cmd,cmd2,cmd3,cmd4]

            for comm in cmds:
                subprocess.call(comm)
            self.__writedns(info['dns'])

        return {'error':False}

    def __writedns(self,dns:list):

        if(len(dns)<1):
            return

        self.__cleardns()
        with open('/etc/resolvconf.conf', 'a+') as f:
            for d in dns:
                f.write('nameserver '+d+'\n')
        f.close()

    def __cleardns(self):

        data =[];

        f = open('/etc/resolvconf.conf', 'r')
        data = f.read().split('\n')
        f.close()

        fi = open('/etc/resolvconf.conf', 'w+')
        for i in range(0,len(data)-1):
            if(re.match(r'^nameserver\s\d{1,3}(\.\d{1,3}){3}$',data[i].strip())):
                del data[i]
            else:
                data[i] = data[i]+'\n'
        fi.writelines(data)
        fi.close()

    def __details(self,item):
        parts = item.strip().split(' ')
        if re.match(r'.*HWaddr.*',item):
            return {'mac':parts[len(parts)-1]}
        elif (re.match(r'.*inet6.*', item)):
            return {'ipv6': parts[len(parts) - 2]}
        elif(re.match(r'.*inet.*',item)):
            return {'ipv4': parts[1].split(':')[1],'subnet': parts[3].split(':')[1]}
        else:
            return None

    def __gwdetails(self,item):
        parts = item.strip().split(' ')
        if re.match(r'.*default.*',item):
            return {'ip':parts[2],'interface':parts[4],'metric':parts[len(parts)-1],'cidr':''}
        elif (item.strip() != ''):
            return {'cidr': parts[0].split('/')[1],'interface':parts[2]}
        else:
            return None


    def __ismatch(self,item,pattern='.*'):
        return re.match(pattern,item)