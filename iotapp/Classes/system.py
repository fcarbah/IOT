import platform
import psutil
import cpuinfo

class System:


    def getInfo(self):
        return {
            'cpu':self.__cpu(),
            'disk': self.__disks(),
            'ram': self.__memory(),
            'system': self.__system()
        }

    def __cpu(self):
        return cpuinfo.get_cpu_info()


    def __disks(self):
        return psutil.disk_usage('/')

    def __memory(self):
        return psutil.virtual_memory()

    def __system(self):
        return{
            'boot':psutil.boot_time(),
            'type':platform.machine(),
            'name': platform.node(),
            'platform': platform.platform(),
            'processor': platform.processor(),
            'system': platform.system(),
            'version': platform.version()
        }