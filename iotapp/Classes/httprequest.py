import urllib3



class HttpRequest:

    __http=None

    def __init__(self):
        self.__http = urllib3.PoolManager()


    def post(self,url,data={},headers={}):
        self.__http.request('POST',url,data,headers)

    def get(self,url,headers={}):
        self.__http.request('get',url,{},headers)