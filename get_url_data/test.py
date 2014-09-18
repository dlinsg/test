from __future__ import print_function
import sys, json, traceback
from twisted.python import failure
from twisted.internet import reactor, defer
from twisted.web.client import getPage

#defaults
limit = 5	
page = "http://coreinterview.sendgrid.net/sample?n=" + str(limit)

#to unbuffer stdout
class Unbuffered(object):
   def __init__(self, stream):
       self.stream = stream
   def write(self, data):
       self.stream.write(data)
       self.stream.flush()
   def __getattr__(self, attr):
       return getattr(self.stream, attr)
sys.stdout = Unbuffered(sys.stdout)

# to print on single line
def pp(str):
	print(str, end='')

# callbacks 1
def cbGetPage(page):
	json_data = json.loads(page)
	urls = json_data['urls']
	for url_json in urls:
		pp("GET.")
		url = url_json['url'].encode("utf-8")
		d2 = getPage(url)
		d2.addCallback(cbGetPage2)
		d2.addErrback(ebGetPage2)
		d2.addCallbacks(stopCase,stopCase)

def ebGetPage(error):
	pp("ERROR getting page of urls")
	reactor.stop()

# callbacks 2
def cbGetPage2(message):
	global limit
	json_data = json.loads(message)
	status_code = json_data['status_code']
	pp(status_code)
	pp(".RECV.")

def ebGetPage2(error):
	pp("ERROR(" + error.value.status + ").")

def stopCase(ignored):
	global limit
	limit -= 1
	if limit <= 0:
		reactor.stop()


#main
if __name__ == "__main__":
	d = getPage(page)
	d.addCallbacks(cbGetPage,ebGetPage)
	reactor.run()
