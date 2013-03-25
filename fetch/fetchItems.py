# -*- coding: utf-8 -*-
import wow
import config
import MySQLdb
import sys
import json

idStep   = 2000

minId    = 93500
maxId    = 100000
param    = []
wowItems = wow.WOW()
while minId <= maxId:
	data = json.loads(wowItems.GetItems(minId, (minId + idStep) if (minId + idStep) <= maxId else maxId))
	if not data['status']:
		raise Exception('Get page falied')
		sys.exit()
	for item in data['data']:
		param.append((item['itemId'], item['itemName'], item['image']))

	connMain = MySQLdb.connect(
		host    = config.db['main']['host'], 
		port    = config.db['main']['port'], 
		user    = config.db['main']['user'], 
		passwd  = config.db['main']['passwd'], 
		db      = config.db['main']['db'], 
		charset = config.db['main']['charset'], 
		use_unicode = False
	)
	cursorMain = connMain.cursor(cursorclass = MySQLdb.cursors.DictCursor)
	cursorMain.executemany("INSERT INTO `w_item`(`id`, `item_name`, `image`) VALUES(%s, %s, %s) ON DUPLICATE KEY UPDATE `item_name` = VALUES(`item_name`), `image` = VALUES(`image`)", param)
	cursorMain.close()
	connMain.close()

	minId = minId + idStep + 1

