#! /usr/bin/python

import datetime
import os
import time
import sys
import urllib
import json
import MySQLdb as mdb

HOST = "127.0.0.1"
USER = "root"
PW = ""
DB = "recoProj"


def getUnprocessed():	
	imageDict = {}	
	try:    		
	        con = mdb.connect(HOST, USER, PW, DB);
    		cur = con.cursor()
    		cur.execute("SELECT mId,image from movie where image!='' and imdbRating IS NOT NULL")
    		rows = cur.fetchall()        		
    		for row in rows:
    			imageDict[row[0]]=row[1]
    		return imageDict
	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()

def downloadImage(mId, image):
    print "Downloading: "+mId
    urllib.urlretrieve(image, "images/"+mId+".jpg")

if __name__ == "__main__":
    imageDict = getUnprocessed()
    for mId, image in imageDict.iteritems():
        if (not (os.path.isfile("/Users/bhavdeepsethi/Sites/images/"+mId+".jpg") or (os.path.isfile("images/"+mId+".jpg")) or (os.path.isfile("assets/"+mId+".jpg")) )):
            downloadImage(mId, image)


