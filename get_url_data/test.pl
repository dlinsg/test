#!/usr/bin/perl
use utf8;
use strict;
use warnings;
use diagnostics;
use AnyEvent;
use AnyEvent::Strict;
use AnyEvent::HTTP;
use LWP::Simple qw(get);
use JSON;
use Data::Dumper;

$| = 1;
$AnyEvent::HTTP::MAX_PER_HOST = 10;

use constant LIMIT => 5;
my $host = "http://coreinterview.sendgrid.net/sample";
my $cv = AnyEvent->condvar( cb => sub { print ".done\n"; });

sub main {
	print "getting urls...";
    my $host = $host . "?n=" . LIMIT;
	my $body = get($host);
	my $json = JSON::decode_json($body);
	print "got urls...starting.";
	my ($k, $v);
	while (($k, $v) = each $json->{'urls'}) {
		my $url = $v->{'url'};		
		getUrl($url);
	}
	print $cv->recv;
}
 
sub getUrl {
	print ".requesting";
	$cv->begin;
	my $url = shift;
	my $request; $request = http_request(
		GET => $url,
		timeout => 20,
		sub {
			my ($body, $header) = @_;
			if ($header->{Status} == "200") {
				my $json = JSON::decode_json($body);
				my $status = $json->{'status_code'};
				print "." . $status;
			} else {
				print ".error:" . $header->{Status};
			}
			undef $request;
			$cv->end;
		}
	);
}

main;