#! /usr/bin/python

import datetime
import os
import time
import sys
import urllib2
import json
import MySQLdb as mdb
from bs4 import BeautifulSoup


URL = "http://www.imdb.com/search/title?genres={0}&sort=moviemeter,asc&start={1}&title_type=feature"
HOST = "127.0.0.1"
USER = "root"
PW = ""
DB = "recoProj"

genre = ["action","animation","comedy","horror","crime","drama","sci_fi","thriller","fantasy"]


def fetchData(genre, current):
	global URL	
	req = urllib2.Request(URL.format(genre, current))
	#req.add_header('Referer', 'http://lab.abhinayrathore.com')
	resp = urllib2.urlopen(req)	
	content = resp.read()
	#print content
	return content
	#return

def getGenre():	
	try:    		
		con = mdb.connect(HOST, USER, PW, DB);
    		cur = con.cursor()
    		cur.execute("select genre,current from genreScraping where current<end order by rand() limit 1")
    		row = cur.fetchone()        		
    		if row is None:
    			return (None,None)
    		else:     			
    			return row
    		
	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	

def insertMoviesIntoList(idList):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		for currId in idList:
			query = 'INSERT IGNORE INTO movieList (mId, createdTime) values ("%s", CURRENT_TIMESTAMP) ' % (currId)
			#print query
			cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	



def updateMovieGenreList(genre, current):
	global HOST, USER, PW, DB
	try: 
		con = mdb.connect(HOST, USER, PW, DB);
		cur = con.cursor()
		query = 'UPDATE genreScraping SET current=%s+50 where genre= "%s" ' % (current, genre)
		print query
		cur.execute(query)
		con.commit()

    	except mdb.Error, e:
    		print "Error %d: %s" % (e.args[0],e.args[1])
    		sys.exit(1)
	finally:           
    		if con:    
        		con.close()	

def getIdList(resp):
	idList = set()
	soup = BeautifulSoup(resp)
	for span in soup.find_all('span'):
		currId = span.get('data-tconst')
		if not currId == None:
			idList.add(currId)
	return idList	


if __name__ == "__main__":
	while(True):
		(genre, current) = getGenre()
		if genre == None:
			break
		resp = fetchData(genre, current)
		idList = getIdList(resp)
		insertMoviesIntoList(idList)
		updateMovieGenreList(genre, current)
		print "Sleeping for 1 second"
		time.sleep(1)
		
		'''if mId is None: 
			print "All ids processed. Sleeping for 1 minute"
			time.sleep(60)
			continue
		data = fetchData(mId)
		insertMovies(mId, data["TITLE"], data["YEAR"], data["TAGLINE"], data["PLOT"], data["POSTER"], data["RUNTIME"], data["MPAA_RATING"])
		insertMovieGenre(mId, data["GENRES"])
		insertMovieCast(mId, data["WRITERS"], data["DIRECTORS"], data["CAST"])
		updateMovieList(mId)
		'''
	

