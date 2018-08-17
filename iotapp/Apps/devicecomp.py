import sys
import RPi.GPIO as GPIO
from time import sleep
from socketIO_client import SocketIO, LoggingNamespace
sys.path.append('/home/pi/iotapp')
from Classes.components import *
from Classes.httprequest import HttpRequest

#setup variables and sensor pins
buzzer_pin=21

vs_pin = 12

rgb_g_pin=5
rgb_b_pin=13
rgb_r_pin = 26

psensor_pin=20


GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

GPIO.setup(buzzer_pin,GPIO.OUT)
GPIO.output(buzzer_pin,GPIO.HIGH)
GPIO.setup(psensor_pin,GPIO.IN)

sleep(5)

presence=0
driver =0
init =False

buzzer = Buzzer(buzzer_pin)

rgb = RGBLed(rgb_r_pin,rgb_g_pin,rgb_b_pin)
rgb.greenon()

alarm_on =False


def detectDriver():
    global driver, presence

    i = GPIO.input(psensor_pin)

    if(driver == 1 and i == 0) :
        buzzer.play()
        HttpRequest().post('http://localhost/local/driver/presence', {'driver': i, 'presence': presence})

    driver = i




def presence_update(res):
    global alarm_on, presence

    p = int(res['data'])
    presence = p

    if not alarm_on:
        if(p==1):
            rgb.blueon()
        else:
            rgb.greenon()


def on_alarm(res):
    global rgb, alarm_on
    alarm_on= True
    rgb.redon()

def off_alarm(res):
    global rgb, alarm_on
    alarm_on=False
    rgb.greenon()


def run():
    detectDriver()
    ws.wait(1)

def vibration_detected():
    global buzzer
    buzzer.play()


ws = SocketIO('localhost',4000)
ws.wait(5)
ws.on('sys_presence_upd', presence_update)
ws.on('sys_alarm_on', on_alarm)
ws.on('sys_alarm_off', off_alarm)

vsensor = SW420(vs_pin,vibration_detected)


try:
    while(True):
        run()

except:
    GPIO.cleanup()
    ws.disconnect()
