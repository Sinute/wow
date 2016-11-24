# -*- coding: utf-8 -*-
import wow
import sys

if len(sys.argv) < 2:
	print 'Param Error'
	sys.exit()

if sys.argv[1] == '-ah':
	if len(sys.argv) < 3:
		print 'Param Error'
		sys.exit()
	expireTime     = 1 * 3600
	cookieFilePath = '/home/pi/workspace/wow/fetch/apicookie/'

	wowah = wow.WOW()
	if not wowah.Login('account', 'password', expireTime, cookieFilePath):
		print 'Login Falied'
		sys.exit()
	print wowah.GetAH(itemId = sys.argv[2])
elif sys.argv[1] == '-itip':
	if len(sys.argv) < 3:
		print 'Param Error'
		sys.exit()
	wowah = wow.WOW()
	print wowah.GetItemTooltip(itemId = sys.argv[2])
