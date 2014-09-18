package main

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"runtime"
	"strconv"
)

type Url struct {
	Url string `json:url`
}

type Urls struct {
	Urls []Url `json:urls`
}

var totalUrls int

func getUrlList(host string) Urls {
	// request list of urls
	fmt.Print("get urls.")
	response, err := http.Get(host)
	defer response.Body.Close()
	if err != nil {
		log.Fatal(fmt.Sprintf("%s", err))
	}
	var contents []byte
	contents, err = ioutil.ReadAll(response.Body)
	if err != nil {
		log.Fatal(fmt.Sprintf("%s", err))
	}

	// parse json response and get list of urls
	var urls Urls
	err = json.Unmarshal(contents, &urls)
	if err != nil {
		log.Fatal(fmt.Sprintf("%s", err))
	}

	fmt.Print("got urls.")
	return urls
}

func requestUrls(urls Urls, cs chan string) {
	totalUrls = len(urls.Urls)
	for _, url := range urls.Urls {
		go getUrl(url.Url, cs)
	}
}

func getUrl(url string, cs chan string) {
	// request url
	response, err := http.Get(url)
	defer response.Body.Close()
	if err != nil {
		log.Fatal(fmt.Sprintf("%s", err))
	}
	var contents []byte
	contents, err = ioutil.ReadAll(response.Body)
	if err != nil {
		log.Fatal(fmt.Sprintf("%s", err))
	}

	// parse json response and return response "status_code"
	var data interface{}
	err = json.Unmarshal(contents, &data)
	if err != nil {
		log.Fatal(fmt.Sprintf("%s", err))
	}

	st := data.(map[string]interface{})
	code := st["status_code"].(float64)
	cs <- strconv.FormatFloat(code, 'f', 0, 64)
}

func printResponse(cs chan string) {
	for totalUrls > 0 {
		select {
		case response, ok := <-cs:
			if ok {
				fmt.Print(".", response)
				totalUrls -= 1
			} else {
				fmt.Print(".")
			}
		}
	}
}

func main() {
	// set defaults
	runtime.GOMAXPROCS(2)
	totalUrls = 5
	host := "http://coreinterview.sendgrid.net/sample?n=" + strconv.Itoa(totalUrls)

	// get list of urls
	urls := getUrlList(host)

	// create channel for go routine to async call to get each url
	cs := make(chan string)

	fmt.Print("start.")
	go requestUrls(urls, cs)
	printResponse(cs)
	fmt.Println(".end")
}
