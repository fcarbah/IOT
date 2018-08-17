
import mysql.connector as MySQLdb


class DB:
    __host=""
    __user=""
    __password=""
    __database=""

    __db=None
    __cursor =None

    def __init__(self,host,username,password,database):
        self.__host = host
        self.__user = username
        self.__password = password
        self.__database = database

    def close(self):
        self.__db.close()

    def connect(self):
        self.__db = MySQLdb.connect(host=self.__host,user=self.__user,password=self.__password,db=self.__database)

    def getConnection(self):
        return self.__db

    def getRecord(self,query):
        self.__cursor = self.__db.cursor()
        self.__cursor.execute(query)
        row = self.__cursor.fetch()
        self.__db.close()
        return row

    def getRecords(self,query):
        self.__cursor = self.__db.cursor()
        self.__cursor.execute(query)
        rows = self.__cursor.fetchall()
        self.__db.close()
        return rows
