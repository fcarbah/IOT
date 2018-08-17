import sys
import RPi.GPIO as GPIO
from time import sleep
from socketIO_client import SocketIO, SocketIONamespace

import Adafruit_DHT as TempSensor
sys.path.append('/home/pi/iotapp')
import Database.models as Models
from Classes.httprequest import HttpRequest
from Classes.config import *


#setup variables and sensor pins
sensor_pin=24
psensor_pin=27



GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

GPIO.setup(psensor_pin,GPIO.IN)

sleep(1)


humidity = temperature = presence =0
alarm_on = False
thresholds = {}

ws = SocketIO('localhost',4000,SocketIONamespace)

# functions

def setThreshold():
    global thresholds

    config = Models.SystemConfig.get(Models.SystemConfig.id == 1)
    sys_config = SystemConfiguration(config)
    thresholds = sys_config.temperature


def detectpresence():
    global presence
    i = GPIO.input(psensor_pin)

    presence = i
    HttpRequest().post('http://localhost/local/presence', {'presence': i})
    if(i==1):
        return True
    else:
        return False


def run():
    global alarm_on, presence

    while(detectpresence() or alarm_on):
        program()



def program():

    global humidity, temperature, thresholds, ws, presence

    humidity, temperature = TempSensor.read_retry(TempSensor.DHT11, sensor_pin)

    if humidity == None or temperature == None:
        return

    HttpRequest().post('http://localhost/local/temperature/new',
                  {'temp':toFahrenheit(temperature), 'humidity': int(humidity), 'presence': presence,
                   'upper':thresholds['upper'],'lower':thresholds['lower']})

    ws.wait(60)


def toFahrenheit(celcius):
    if(celcius==None):
        return 0
    return int((1.8*celcius) + 32)


def thresholdReached(temp):

    f_temp = toFahrenheit(temp)
    return f_temp <= thresholds['lower'] or f_temp >= thresholds['upper']

def toggleStatusLight(on):
    if on:
        print('')


def on_temp_update(res):
    setThreshold()

def on_alarm(res):
    global alarm_on
    alarm_on= True

def off_alarm(res):
    global alarm_on
    alarm_on=False

#run app

setThreshold()

#subscribe to socket
ws.on('sys_temp_upd',on_temp_update)
ws.on('sys_alarm_on', on_alarm)
ws.on('sys_alarm_off', off_alarm)

program()

try:
    while(True):
        run()

except:
    GPIO.cleanup()
    ws.disconnect()

