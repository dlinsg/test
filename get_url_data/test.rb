require "net/http"
require "uri"
require "rubygems"
require "json"
require 'eventmachine'
require 'em-http'

HTTP_OK = 200
$stdout.sync = true

def main()
	response = Net::HTTP.get_response("coreinterview.sendgrid.net","/sample?n=5")
	body = JSON.parse(response.body)
	urls = body["urls"]
	urls.each {|url_link|
		getUrl(url_link["url"])
	}
end

def getUrl(url)
	print "."
	EM.run do
	  http = EM::HttpRequest.new(url).get
	  http.callback do
	    if http.response_header.status == HTTP_OK
	      body = JSON.parse(http.response)
	      print body["status_code"]
	    else
	      puts http.response_header.status
	    end
	    EM.stop
	  end
  	  http.errback do
	    puts http.error
	    EM.stop
	  end
	end
end



main()