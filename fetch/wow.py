# -*- coding: utf-8 -*-
import pycurl
import urllib
import sys
import re
import StringIO
import os
import time
import lxml.html
import json

class WOW():
	"""WOW"""
	cookieFilePath = "./"
	expireTime = 5 * 3600
	accountName = ''
	password = ''

	persistLogin = 'on'
	app = 'com-sc2'
	loginURL = 'https://www.battlenet.com.cn/login/zh/login.frag'
	ticketURL = 'http://www.battlenet.com.cn/wow/zh/?ST='
	ahURL = "https://www.battlenet.com.cn/wow/zh/vault/character/auction/horde/browse?"
	itemUrl = 'http://www.battlenet.com.cn/wow/zh/item/'

	def GetItems(self, minId = 0, maxId = 0):
		"""Get Item id & name"""
		message = 'success'
		status  = True
		items = []
		try:
			self.Log(str(minId) + ' - ' + str(maxId))
			itemIds = xrange(minId, maxId + 1) if maxId > minId >= 0 else [minId]
			for itemId in itemIds:
				result = self.__HttpRequest(
					self.itemUrl + str(itemId)
				)
				doc = lxml.html.document_fromstring(result.decode('utf-8'))
				title = doc.xpath('//*[@id="wiki"]/div[2]/div[1]/h2')
				if not title:
					continue
				itemName = title[0].text if title[0].text else ''
				if not itemName: # only one special item - id 49461
					self.Log('Get itemName failed: ' + str(itemId))
				image = doc.xpath('//*[@id="wiki"]/div[2]/div[2]/span')
				if image:
					image = re.search('url\("(.+)"\);', image[0].attrib['style'])
					image = image.group(1) if image else ''
				else:
					image = ''
				items.append({'itemId': itemId, 'itemName': itemName, 'image': image})
		except Exception, e:
			status  = False
			message = e.message
		return json.dumps({'status': status, 'message': message, 'data':items})

	def GetItemTooltip(self, itemId):
		"""Get item attribute"""
		message = 'success'
		status  = True
		result = ''
		try:
			result = self.__HttpRequest(
				self.itemUrl + str(itemId) + '/tooltip'
			)
		except Exception, e:
			status  = False
			message = e.message
		return json.dumps({'status': status, 'message': message, 'data':result})

	def GetAH(self, name = '', itemId = '', filterId = -1, minLvl = -1, maxLvl = -1, qual = 1, start = 0, end = 200, sort = 'unitBuyout', reverse = 'false'):
		"""Get AH info"""
		items    = []
		message = 'success'
		status  = True
		try:
			params = {
				'filterId': filterId,
				'minLvl': minLvl,
				'maxLvl': maxLvl,
				'qual': qual,
				'start': start,
				'end': end,
				'sort': sort,
				'reverse': reverse
			}
			if itemId:
				params['itemId'] = itemId
			else:
				params['n'] = name
			result = self.__HttpRequest(
				self.ahURL + urllib.urlencode(params), 
				self.__GetCookie()
			)
			# match items
			doc = lxml.html.document_fromstring(result.decode('utf-8'))
			trs = doc.xpath("//div[@class='table']/table/tbody/tr")
			for tr in trs:
				for td in tr:
					# item info
					if td.attrib['class'] == "item":
						# item id
						itemId   = re.search('\d+', td[0].attrib['href'])
						itemId   = itemId.group() if itemId else ''
						# item name
						# itemName = td.xpath(".//strong")
						# itemName = itemName[0].text if itemName else ''
					# quantity
					if td.attrib['class'] == "quantity":
						quantity = td.text if td.text else ''
					# price
					if td.attrib['class'] == "price":
						priceGold    = td.xpath(".//span[@class='icon-gold']")
						gold         = priceGold[2].text.replace(',', '') if len(priceGold) > 2 else '0'
						goldBuyOut   = priceGold[3].text.replace(',', '') if len(priceGold) > 3 else '0'
						priceSilver  = td.xpath(".//span[@class='icon-silver']")
						silver       = priceSilver[2].text if len(priceSilver) > 2 else '0'
						silverBuyOut = priceSilver[3].text if len(priceSilver) > 3 else '0'
						priceCopper  = td.xpath(".//span[@class='icon-copper']")
						copper       = priceCopper[2].text if len(priceCopper) > 2 else '0'
						copperBuyOut = priceCopper[3].text if len(priceCopper) > 3 else '0'

				items.append({
					'itemId': itemId, 
					# 'itemName': itemName, 
					'gold': gold, 
					'silver': silver, 
					'copper': copper, 
					'goldBuyOut': goldBuyOut, 
					'silverBuyOut': silverBuyOut, 
					'copperBuyOut': copperBuyOut, 
					'quantity': quantity
				})
		except Exception, e:
			status  = False
			message = e.message
		return json.dumps({'status': status, 'message': message, 'data':items})

	def Login(self, accountName, password, expireTime = 24 * 3600, cookieFilePath = './'):
		"""Login battlenet"""
		try:
			self.accountName = accountName
			self.password = password
			self.cookieFilePath = cookieFilePath
			self.expireTime = expireTime
			self.__CleanCookie()
			# get ticket
			cookieFile = self.__GetCookie()
			if not os.path.isfile(cookieFile):
				# cookie file not exists
				result = self.__HttpRequest(
					self.loginURL, 
					cookieFile, 
					urllib.urlencode({"accountName": self.accountName, "password": self.password, "persistLogin": self.persistLogin, "app": self.app})
				)
				result = re.search('\\\\\"loginTicket\\\\\":\\\\\"(.+?)\\\\\"', result)
				ticket = ''
				if result:
					ticket = result.group(1)
				else:
					self.Log( 'Login failed' )
					self.__CleanCookie()
					# login failed
					return False
				self.__HttpRequest(
					self.ticketURL + ticket, 
					cookieFile
				)
			return True
		except Exception, e:
			return False

	def __GetCookie(self):
		"""Get cookieFileName"""
		fileList = os.listdir(self.cookieFilePath)
		mktime = []
		for fileName in fileList:
			result = re.search(self.accountName + "#(\d+)", fileName)
			if result:
				mktime.append(int(result.group(1)))
		if not len(mktime) or time.time() - max(mktime) > self.expireTime:
			self.__CleanCookie()
			return self.cookieFilePath + os.sep + self.accountName + "#" + str(int(time.time())) + ".cookie"
		return self.cookieFilePath + os.sep + self.accountName + "#" + str(max(mktime)) + ".cookie"

	def __CleanCookie(self):
		"""Clean all the cookies owns by accountName"""
		fileList = os.listdir(self.cookieFilePath)
		for fileName in fileList:
			result = re.search(self.accountName + "#(\d+)", fileName)
			if result:
				os.remove(self.cookieFilePath + os.sep + result.group() + ".cookie")

	def __HttpRequest(self, url, cookieFile = False, postFields = False, userAgent = False):
		"""The method to start request"""
		userAgent = userAgent if userAgent else "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17"
		data = StringIO.StringIO()
		curl = pycurl.Curl()
		curl.setopt(pycurl.FOLLOWLOCATION, 1)
		curl.setopt(pycurl.MAXREDIRS, 5)
		curl.setopt(pycurl.SSL_VERIFYPEER, False)
		curl.setopt(pycurl.SSL_VERIFYHOST, False)
		curl.setopt(pycurl.USERAGENT, userAgent)
		curl.setopt(pycurl.URL, url)
		curl.setopt(pycurl.WRITEFUNCTION, data.write)
		if cookieFile:
			curl.setopt(pycurl.COOKIEFILE, cookieFile)
			curl.setopt(pycurl.COOKIEJAR, cookieFile)
		if postFields:
			curl.setopt(pycurl.POSTFIELDS,  postFields)
		tryTime = 0
		while True:
			try:
				curl.perform()
				return data.getvalue()
			except Exception, e:
				if tryTime < 3:
					tryTime += 1
					self.Log('Request url: ' + url + '  - failed ' + str(tryTime) + ' time(s)')
					time.sleep(3)
					continue
				else:
					raise e		

	def Log(self, message):
		print time.strftime('%Y-%m-%d %H:%M:%S') + ': ' + message
