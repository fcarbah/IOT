import sys
import RPi.GPIO as GPIO
from time import sleep
import datetime
import os
from picamera import PiCamera



class Camera:

    __scriptpath = '/home/pi/motion'
    __scriptconf = '/home/pi/motion-mmalcam-both.conf'
    __photopath = '/var/www/iot/public/campics/'
    __on = False
    __cam =None

    def __init__(self):
        self.__cam = None#PiCamera()


    def run(self,action):
        if action == 'on':
            return self.start()

        if(action == 'off'):
            return self.stop()

        if action == 'photo':
            return self.takephoto()


    def start(self,notify=True):

        #if not self.__on:
        try:
            os.system('cd /home/pi && sudo ./motion -c motion-mmalcam-both.conf > /dev/null 2>&1 &')
            sleep(1)
            self.__on = True
            if notify:
                return {'error': False}
        except:
            return {'error': False}

    def stop(self,notify=True):
        #if self.__on:
        try:
            os.system('sudo killall motion')
            sleep(1)
            self.__on = False
            if notify:
                return {'error': False}
        except:
            return {'error': False}

    def takephoto(self):
        name= str(datetime.datetime.now().timestamp()) + '_image.jpg'
        self.stop(False)
        cam = PiCamera()
        cam.capture(self.__photopath+name)
        sleep(1)
        cam.close()
        del cam
        self.start(False)
        return {'error':False,'name':name,'path':'/campics/'+name}



class Buzzer:

    __pin=None
    __buzzer = None

    __frequency= {
        'CL': [0,131,147,165,175,196,211,248],
        'CM': [0,262,294,330,350,393,441,495],
        'CH': [0,525,589,661,700,786,882,990]
    }
    __song = None;

    def __init__(self,pin):
        self.__pin = pin;
        self.__buzzer = GPIO.PWM(pin,440)
        self.__song =[330,393,441,330,294,330,393,441,262,441,393,262,330,294,294,330,393,294,330,330,441,441,
                      441,262,294,330,292,495,441,262,393]

    def __del__(self):
        GPIO.cleanup()

    def play(self):
        beat = [1,1,3,1,1,3,1,1,1,1,1,1,1,3,1,1,3,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1,1,3]
        self.__buzzer.start(80);
        for i in range(0,len(self.__song)//10):
            self.__buzzer.ChangeFrequency(self.__song[i])
            sleep(beat[i]*0.5)
        self.__buzzer.stop();





class RGBLed:

    __red_pin = __blue_pin = __green_pin = None

    def __init__(self,red_pin,green_pin,blue_pin):
        self.__red_pin = red_pin
        self.__blue_pin = blue_pin
        self.__green_pin = green_pin

        GPIO.setup(red_pin,GPIO.OUT)
        GPIO.setup(blue_pin, GPIO.OUT)
        GPIO.setup(green_pin, GPIO.OUT)

        self.__turnOff()

    def __del__(self):
        GPIO.cleanup()

    def blueon(self):
        self.turnon(0,1,0)

    def greenon(self):
        self.turnon(0,0,1)

    def redon(self):
        self.turnon(1,0,0)

    def turnon(self,red,blue,green):
        GPIO.output(self.__red_pin, red)
        GPIO.output(self.__blue_pin, blue)
        GPIO.output(self.__green_pin, green)

    def turnoff(self):
        self.__turnOff()

    def __turnOff(self):
        GPIO.output(self.__red_pin,GPIO.LOW)
        GPIO.output(self.__blue_pin, GPIO.LOW)
        GPIO.output(self.__green_pin, GPIO.LOW)



class SW420:
    __pin = None;
    __count =0;
    __callbackfn=None
    __freq = None;

    def __init__(self,pin,callback:callable,freq=1000):
        self.__pin = pin
        self.__callbackfn = callback
        self.__freq = freq
        self.__setup()


    def __setup(self):
        GPIO.setup(self.__pin,GPIO.IN,GPIO.PUD_DOWN)
        GPIO.add_event_detect(self.__pin,GPIO.RISING,callback=self.callback,bouncetime=1)

    def __del__(self):
        GPIO.cleanup()

    def callback(self,pin):
        self.__count += 1
        if(self.__count == self.__freq):
            self.__count = 0;
            self.__callbackfn()