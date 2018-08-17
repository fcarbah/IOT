import json

from Database.models import SystemConfig

class SystemConfiguration:

    wireless=None
    network=None
    temperature=None

    def __init__(self,config: SystemConfig):
        self.network = json.loads(config.network)
        self.temperature = json.loads(config.temperature)
        self.wireless = json.loads(config.wireless)


