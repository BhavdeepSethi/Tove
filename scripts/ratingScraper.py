#! /usr/bin/python

import datetime
import os
import time
import sys
import urllib2
import json
import MySQLdb as mdb

URL = "http://lab.abhinayrathore.com/imdb/imdbWebService.php?o=json&m="
HOST = "127.0.0.1"
USER = "root"
PW = ""
DB = "recoProj"



def fetchData(mId):
	global URL	
	req = urllib2.Request(URL+mId)
	req.add_header('Referer', 'http://lab.abhinayrathore.com')
	try:
		resp = urllib2.urlopen(req, timeout=30)	
		content = resp.read()
	#print content
		return json.loads(content)
	except:
		return None
	#return

def getUnprocessed():	
	try:    		
		con = mdb.connect(HOST, USER, PW, DB);
    		cur = con.cursor()
    		cur.execute("SELECT mId from movie where imdbRating IS NULL order by rand() limit 1")
    		row = cur.fetchone()        		
    		if row is None:
    			return None
    		else: 
    			return row[0]
    		
	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()


def updateMovie(mId, rating, votes):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		if not rating:
			rating = "-1"
			votes = "-1"

		query = 'UPDATE movie SET imdbRating="%s",imdbVotes="%s"  where mId= "%s" ' % (rating, votes.replace(",","") , mId)
		#print query
		cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	  


def updateMovieList(mId, status):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		query = 'UPDATE movieList SET status=%s where mId= "%s" ' % (status, mId)
		#print query
		cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	        		
		


if __name__ == "__main__":
	while(True):		
		mId = getUnprocessed()		
		print "Fetching new record: "+mId
		if mId is None: 
			print "All ids processed. Sleeping for 3 seconds"
			#time.sleep(3)
			break
			#continue
		data = fetchData(mId)
		if data == None:
			continue
		#print mId
		if "TITLE" not in data:
			print "Skipping "+mId+" because of some error"
			updateMovieList(mId, 4)
			continue		
		updateMovie(mId, data["RATING"], data["VOTES"])
		
	
	

