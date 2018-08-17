import Database.models as Models
import Classes.config
from Classes.config import *

import Database.dbconnect as DBConnect

#db = DBConnect.DB("localhost",'root','','iot')
#db.connect()

#print(db.getRecords("select * from users"))

'''config = Models.SystemConfig.get(Models.SystemConfig.id==1)

sys_config = SystemConfiguration(config)

print(sys_config.temperature)'''

'''from Classes.components import Camera
import time


camera = Camera()

camera.start()
time.sleep(30)
camera.stop()'''

#! /usr/bin/python

# A simple Python command line tool to control an Omron MEMS Temp Sensor D6T-44L
# By Greg Griffes http://yottametric.com
# GNU GPL V3

# Jan 2015

import smbus

import sys
import getopt
import time
import pigpio

def toF(celcius):
    return (1.8*celcius) + 32

for x in range(0,10):

    i2c_bus = smbus.SMBus(1)
    OMRON_1=0x0a                # 7 bit I2C address of Omron MEMS Temp Sensor D6T-44L
    OMRON_BUFFER_LENGTH=35     # Omron data buffer size
    temp_data=[0]*OMRON_BUFFER_LENGTH    # initialize the temperature data list

    # intialize the pigpio library and socket connection to the daemon (pigpiod)
    pi = pigpio.pi()              # use defaults
    version = pi.get_pigpio_version()
    #print('PiGPIO version = '+str(version))
    handle = pi.i2c_open(1, 0x0a) # open Omron D6T device at address 0x0a on bus 1

    # initialize the device based on Omron's appnote 1
    result=i2c_bus.write_byte(OMRON_1,0x4c)
    #print 'write result = '+str(result)

    #for x in range(0, len(temperature_data)):
      #print x
      # Read all data  tem
      #temperature_data[x]=i2c_bus.read_byte(OMRON_1)
    (bytes_read, temp_data) = pi.i2c_read_device(handle, len(temp_data))

    # Display data
    #print('Bytes read from Omron D6T: '+str(bytes_read))
    #print('Data read from Omron D6T : ')


    #for x in range(bytes_read):
        #print(temp_data[x]),
    #print 'done'

    tPtat = temp_data[1]*256 + temp_data[0]
    tPtat = toF(tPtat * 0.1)
    tp = []

    for i in range(2,33,2) :
        temp = temp_data[i+1]* 256 + temp_data[i]
        tp.append(toF(temp*0.1))


    tPec = temp_data[34]/10


    print("PTAT: %d" % (tPtat))
    print(tp)
    #print("PEC: %d" % (tPec))

    #print("\n New\n")
    pi.i2c_close(handle)
    pi.stop()
    time.sleep(5)


'''import omrond6t as om
for x in range(0,10):
    sensor =  om.OmronD6T(1,0x0a,8)
    bytes_read, temp = sensor.read()

    print(temp)
    time.sleep(5)'''


