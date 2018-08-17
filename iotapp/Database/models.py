import peewee
from peewee import *
import Database.dbconnect as DBConnection


#db = DBConnection.DB("localhost","root","messi","iot")

#db.connect()

#cnx = db.getConnection()

db = MySQLDatabase(host="localhost",user='root',password='messi',database='iot')


class Users(Model):
    username = CharField()
    id = PrimaryKeyField()

    class Meta:
        database = db
        db_table= 'users'


class SystemConfig(Model):
    id = PrimaryKeyField()
    wireless = TextField()
    network = TextField()
    notification = TextField()
    security = TextField()
    temperature = TextField()
    defTemperature = TextField()

    class Meta:
        database = db
        db_table = 'system_config'
