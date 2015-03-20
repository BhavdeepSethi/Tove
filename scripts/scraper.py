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
	resp = urllib2.urlopen(req)	
	content = resp.read()
	#print content
	return json.loads(content)
	#return

def getUnprocessed():	
	try:    		
		con = mdb.connect(HOST, USER, PW, DB);
    		cur = con.cursor()
    		cur.execute("SELECT mId from movieList where status=0;")
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

def insertMovies(mId, title, year, tagLine, plot, image, runtime, contentRating):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		query = 'INSERT IGNORE INTO movie (mId, title, year, tagLine, plot, image, runtime, contentRating) values ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s") ' % (mId, title, year, tagLine, plot, image, runtime, contentRating)
		print query
		cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	

def insertMovieGenre(mId, genreList):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		for genre in genreList:
			query = 'INSERT IGNORE INTO movieGenre (mId, genre) values ("%s", "%s") ' % (mId, genre)
			print query
			cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	

def insertMovieCast(mId, writers, directors, cast):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		for key,val in writers.iteritems():
			query = 'INSERT IGNORE INTO movieCast (mId, castType, castName) values ("%s", "writer", "%s") ' % (mId, val.lower())
			print query
			cur.execute(query)
		for key,val in directors.iteritems():
			query = 'INSERT IGNORE INTO movieCast (mId, castType, castName) values ("%s", "director", "%s") ' % (mId, val.lower())
			print query
			cur.execute(query)
		count = 1
		for key,val in cast.iteritems():
			if count > 3:
				break
			query = 'INSERT IGNORE INTO movieCast (mId, castType, castName) values ("%s", "cast", "%s") ' % (mId, val.lower())
			print query
			count += 1
			cur.execute(query)
		
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	        				


def updateMovieList(mId):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		query = 'UPDATE movieList SET status=1 where mId= "%s" ' % (mId)
		print query
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
		if mId is None: 
			print "All ids processed. Sleeping for 1 minute"
			time.sleep(60)
			continue
		data = fetchData(mId)
		insertMovies(mId, data["TITLE"], data["YEAR"], data["TAGLINE"], data["PLOT"], data["POSTER"], data["RUNTIME"], data["MPAA_RATING"])
		insertMovieGenre(mId, data["GENRES"])
		insertMovieCast(mId, data["WRITERS"], data["DIRECTORS"], data["CAST"])
		updateMovieList(mId)
	
	

