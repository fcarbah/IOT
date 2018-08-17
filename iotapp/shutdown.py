import sys
import RPi.GPIO as GPIO


import Adafruit_DHT as TempSensor
sys.path.append('/home/pi/iotapp')

from Classes.httprequest import HttpRequest
from Classes.components import Camera



#setup variables and sensor pins
sensor_pin=16
psensor_pin=20

GPIO.cleanup()

GPIO.setup(psensor_pin,GPIO.IN)

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)


humidity = temperature = presence =0

camera = Camera()

def program():

    global humidity, temperature, thresholds, ws

    humidity, temperature = TempSensor.read_retry(TempSensor.DHT11, sensor_pin)

    i = GPIO.input(psensor_pin)

    HttpRequest().post('http://localhost/local/temperature/new',
                  {'temp':toFahrenheit(temperature), 'humidity': int(humidity), 'presence': i,
                   'upper':thresholds['upper'],'lower':thresholds['lower']})

    camera.stop()


def toFahrenheit(celcius):
    if(celcius==None):
        return 0
    return int((1.8*celcius) + 32)


#run app
program()


