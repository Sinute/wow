# -*- coding: utf-8 -*-
import wow
import config
import json
import MySQLdb
import sys
import time
import traceback

def Log(message):
	print time.strftime('%Y-%m-%d %H:%M:%S') + ': ' + message

def getTableName(serverName):
	return 'w_auction_house_' + serverName + '_' + str(time.localtime().tm_year)


Log('Start')

expireTime     = 1 * 3600
cookieFilePath = '/home/pi/workspace/wow/fetch/cookie/'
searchItemNum  = 500

try:
	# connect to main db to get servers & item info
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
	cursorMain.execute("SELECT `id` FROM `w_item_search` \
		WHERE `enable` = 1 \
		ORDER BY `rank` DESC \
		LIMIT 0, %s" %(searchItemNum))
	items = cursorMain.fetchall()
	cursorMain.execute("SELECT `id`, `server_name_en`, `account_name`, `password`, \
						`db_name`, `db_host`, `db_port`, `db_user`, `db_pwd`, `db_charset` \
						FROM `w_server` WHERE `enable` = 1")
	servers = cursorMain.fetchall()
	cursorMain.close()
	connMain.close()
	if len(items) > 0:
		# insert into each server db
		for server in servers:
			wowah = wow.WOW()
			if not wowah.Login(server['account_name'], server['password'], expireTime, cookieFilePath):
				Log("Failed to login")
				sys.exit()
			serverId = str(server['id']).rjust(3, '0')
			connServer = MySQLdb.connect(
				host    = server['db_host'], 
				port    = server['db_port'], 
				user    = server['db_user'], 
				passwd  = server['db_pwd'], 
				db      = server['db_name'], 
				charset = server['db_charset'], 
				use_unicode = False
			)
			cursorServer = connServer.cursor(cursorclass = MySQLdb.cursors.DictCursor)
			param = []
			tableName = getTableName(server['server_name_en'])
			d = time.strftime('%Y-%m-%d')
			t = time.strftime('%H:00:00')
			# get each item info
			for item in items:
				data = json.loads(wowah.GetAH(itemId = item['id'], end = 1))
				if data['status'] and len(data['data']) > 0:
					param.append((data['data'][0]['itemId'], data['data'][0]['gold'], data['data'][0]['silver'], data['data'][0]['copper'], data['data'][0]['goldBuyOut'], data['data'][0]['silverBuyOut'], data['data'][0]['copperBuyOut'], data['data'][0]['quantity'], d, t))
				else: # get item failed: skip
					continue
			Log('Server ' + serverId + ' - DB ' + server['db_name'] + ' - Table ' + tableName + ': ' + str(len(param)) + ' records')
			cursorServer.executemany("INSERT INTO `" + tableName + "`(`item_id`, `gold`, `silver`, `copper`, `gold_buyout`, `silver_buyout`, `copper_buyout`, `quantity`, `date`, `time`) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", param)
			cursorServer.close()
			connServer.commit()
			connServer.close()

	Log('End - Success')

except Exception, e:
	Log('End - Fail')
	print traceback.format_exc()
