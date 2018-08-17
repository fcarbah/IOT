from gps3 import gps3
import sys
from time import sleep
sys.path.append('/home/pi/iotapp')
from Classes.httprequest import  HttpRequest

sleep(60);

info = {}

while True:

    gps_socket = gps3.GPSDSocket()
    data_stream = gps3.DataStream()
    gps_socket.connect()
    gps_socket.watch()

    for new_data in gps_socket:

        if new_data:
            data_stream.unpack(new_data)
            alt = data_stream.TPV['alt']
            lat = data_stream.TPV['lat']
            lon = data_stream.TPV['lon']
            speed = data_stream.TPV['speed']
            print(lat)

            if lat != 'n/a' and lon != 'n/a' and speed != 'n/a' and alt != 'n/a':

                info = {'lat': '{:.4f}'.format(float(lat)), 'lon': '{:.4f}'.format(float(lon)),
                        'alt': '{:.2f}'.format(float(alt)), 'speed': '{:.1f}'.format(float(speed))}

                HttpRequest().post('http://localhost/local/location',info)
                break
    sleep(1)