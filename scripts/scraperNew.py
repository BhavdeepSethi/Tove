#! /usr/bin/python

import datetime
import os
import time
import sys
import urllib2
import json
import MySQLdb as mdb
from imdb import IMDb

URL = "http://lab.abhinayrathore.com/imdb/imdbWebService.php?o=json&m="
HOST = "127.0.0.1"
USER = "root"
PW = ""
DB = "recoProj"


ia = IMDb()
def fetchData(mId):
	global ia
	try: 
		return ia.get_movie(mId[2:])
	except:
		return None
	#return

def getUnprocessed():	
	try:    		
		con = mdb.connect(HOST, USER, PW, DB);
    		cur = con.cursor()
    		cur.execute("SELECT mId from movieList where status=2 order by rand() limit 1")
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

def getMovie(mId):	
	try:    		
		con = mdb.connect(HOST, USER, PW, DB);
    		cur = con.cursor()
    		cur.execute("SELECT mId from movie where mId='{0}'".format(mId))
    		row = cur.fetchone()        		
    		if row is None:
    			return mId
    		else: 
    			return None
    		
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
		query = 'INSERT IGNORE INTO movie (mId, title, year, tagLine, plot, image, runtime, contentRating, createdTime) values ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", CURRENT_TIMESTAMP) ' % (mId, title, year, tagLine, plot, image, runtime, contentRating)
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
	if len(genreList) == 0:
		return
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		for genre in genreList:
			query = 'INSERT IGNORE INTO movieGenre (mId, genre) values ("%s", "%s") ' % (mId, genre.lower())
			#print query
			cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	


def insertImdbReco(mId, reco):
	if len(reco) == 0:
		return
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		for key,val in reco.iteritems():
			query = 'INSERT IGNORE INTO item2ItemReco (mId, mIdReco, type) values ("%s", "%s", "imdb") ' % (mId, key)
			#print query
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
		if not len(writers) == 0:
			for key,val in writers.iteritems():
				query = 'INSERT IGNORE INTO movieCast (mId, castType, castName) values ("%s", "writer", "%s") ' % (mId, val.lower())
				#print query
				cur.execute(query)
		if not len(directors) == 0:
			for key,val in directors.iteritems():
				query = 'INSERT IGNORE INTO movieCast (mId, castType, castName) values ("%s", "director", "%s") ' % (mId, val.lower())
				#print query
				cur.execute(query)
		count = 1
		if not len(cast) == 0:
			for key,val in cast.iteritems():
				if count > 3:
					break
				query = 'INSERT IGNORE INTO movieCast (mId, castType, castName) values ("%s", "cast", "%s") ' % (mId, val.lower())
				#print query
				count += 1
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


def updateMovieRating(mId, rating, votes):
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



if __name__ == "__main__":
	while(True):		
		mId = getUnprocessed()		
		print "Fetching new record: "+mId
		if mId is None: 
			print "All ids processed. Sleeping for 3 seconds"
			#time.sleep(3)
			break
			#continue
		mId = getMovie(mId)
		if mId is None:
			print "Id already processed."
			updateMovieList(mId, 1)
			continue
		data = fetchData(mId)
		if data == None:
			continue
		#print mId
		if "title" not in data:
			print "Skipping "+mId+" because of some error"
			updateMovieList(mId, 2)
			continue		
		insertMovies(mId, data["title"], data["year"], data["TAGLINE"], data["plot"], data["POSTER"], data["RUNTIME"], data["MPAA_RATING"])
		insertMovieGenre(mId, data["GENRES"])
		insertMovieCast(mId, data["WRITERS"], data["DIRECTORS"], data["CAST"])
		insertImdbReco(mId, data["RECOMMENDED_TITLES"])
		updateMovieRating(mId, data["RATING"], data["VOTES"])
		updateMovieList(mId, 1)
		
	
	

