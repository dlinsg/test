#!/usr/bin/python
# coding: utf-8

import datetime
today = str(datetime.date.today().strftime('%m/%d/%Y'))

import sendgrid
sg = sendgrid.SendGridClient('dlintestapi', 'testingapi123')

message = sendgrid.Mail()
message.set_from('Dave Lin <david.lin@sendgrid.com>')
message.add_to('David Lin <david.lin@sendgrid.com>')
message.set_subject("Hello %tag1% García, your résumé balance is %tag2% as of %tag3%. Thank you and 谢谢!!!")
message.set_text("test email")

message.add_substitution("%tag1%", "José")
message.add_substitution("%tag2%", "1.234£")
message.add_substitution("%tag3%", today)

status, msg = sg.send(message)
print str(status) + ' ' + msg
