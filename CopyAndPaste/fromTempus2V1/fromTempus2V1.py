'''
Created on Aug 29, 2017

@author: peter.dell
'''
'''
Created on Aug 22, 2017

@author: peter.dell
'''

import requests
import json
import numpy
import xml.etree.ElementTree
#from xml.dom import minidom
from pprint import pprint
import re
from requests.exceptions import ConnectionError
import sys
from requests_toolbelt.utils import dump

true = True
false = False
CONST_SWAG_LOCK = 58
CONST_SWAG_VAR  = 57

def tempusFindProjectName(TempusProjectName):
    try :
    #Get Project Name and then the Swag value
        urlIncludeParams = urlTempusFindProjectName + '?name='
        urlIncludeParams += TempusProjectName
        urlIncludeParams += "&page=1&pageSize=10"
        u = requests.get(urlIncludeParams, headers=headersTempus)
        data = dump.dump_all(u)
        print(data.decode('utf-8'))
        if u.status_code == 200:                                                #need the json response value
            #(parse the value below next, could be multiple rows returned that match the startsWith)
            #{"page":1,"pageSize":10,"totalItems":2,"items":[{"createdOn":"2017-08-25T12:36:00.1877869","createdBy":{"resourceId":835,"systemActor":null},"updatedOn":"2017-08-25T12:36:00.1877869","updatedBy":{"resourceId":835,"systemActor":null},"startDate":"2017-08-24T00:00:00","endDate":"2017-10-08T00:00:00","securityGroupId":1,"id":555,"name":"Dell Networking"},{"createdOn":"2017-08-25T13:30:10.513793","createdBy":{"resourceId":835,"systemActor":null},"updatedOn":"2017-08-25T13:30:10.513793","updatedBy":{"resourceId":835,"systemActor":null},"startDate":"2017-08-24T00:00:00","endDate":"2017-09-08T00:00:00","

	    nameMatch=false
	    entityId = 0
            e = json.loads(u.text)
            for key, value in e.iteritems():
                if key == 'items' :
                    myItemsList = value
                    print ("myItemList = ", myItemsList)
                    z = 0
                    for s in myItemsList:
                        print("s={0}, i={1}".format(s, z))
                        for key, value in s.items():
                            z = z + 1
			
			    if key == 'name':  #accounting for more than one match of the name
				print("project name now = {0}".format(value))
				if TempusProjectName == value:
				    print("value of project name matches {0} vs {1}".format(TempusProjectName, value))
				    nameMatch=true
				    #return entityId
                            if key == 'id':
                                print("found id")
                                print("id = {0}".format(value))
                                entityId = value
                                print("entityId = {0}".format(entityId))
				if nameMatch == true:
                                	return entityId
        else:
            print('failed to locate project in Tempus = %s' % TempusProjectName)
    except ConnectionError as theError:
        print (theError)
        return -1

def V1UpdateProjectSwagValue(v1ProjectId, swagValue):
    urlIncludeParams = updateSwagV1 + str(v1ProjectId)
    jsonDataUpdate = '{\n    \"Attributes\": {\n'
    jsonDataUpdate += '        \"TargetSwag\": {\n'
    jsonDataUpdate += '            \"value\": '
    jsonDataUpdate += str(swagValue)
    jsonDataUpdate += ',\n' 
    jsonDataUpdate += '            \"act\": \"set\"\n'      
    jsonDataUpdate += '        }\n'           
    jsonDataUpdate += '    }\n'            
    jsonDataUpdate += '}'
    #print(jsonDataUpdate)
    u = requests.post(urlIncludeParams, data=jsonDataUpdate, headers=headersV1Post)
    data = dump.dump_all(u)
    print(data.decode('utf-8'))    #    }
    if u.status_code == 200:                                                #need the json response value
        my_list = json.loads(u.text)
        print(my_list)
        
def V1UpdateProjectDatesValue(v1ProjectId, dateBegin, dateEnd):
    urlIncludeParams = updateDatesV1 + str(v1ProjectId)
    jsonDataUpdate = '{\n    \"Attributes\": {\n'
    jsonDataUpdate += '        \"BeginDate\": {\n'
    jsonDataUpdate += '            \"value\": \"'
    jsonDataUpdate += str(dateBegin[:-10])
    jsonDataUpdate += '\",\n' 
    jsonDataUpdate += '            \"act\": \"set\"\n'      
    jsonDataUpdate += '        },\n'           
    jsonDataUpdate += '        \"EndDate\": {\n'
    jsonDataUpdate += '            \"value\": \"'
    jsonDataUpdate += str(dateEnd[:-10])
    jsonDataUpdate += '\",\n' 
    jsonDataUpdate += '            \"act\": \"set\"\n'      
    jsonDataUpdate += '        }\n'           
    jsonDataUpdate += '    }\n'            
    jsonDataUpdate += '}'
    print("url = {0}\ndata={1}".format((urlIncludeParams), (jsonDataUpdate)))
    u = requests.post(urlIncludeParams, data=jsonDataUpdate, headers=headersV1Post)
    print(u.text)
    data = dump.dump_all(u)
    print(data.decode('utf-8'))    #    }
    if u.status_code == 200:                                                #need the json response value
        my_list = json.loads(u.text)
        print(my_list)
    else:
	print("status code = " + str(u.status_code))
	print("failed to write the post request" + urlIncludeParams + jsonDataUpdate + "\n")
    #sys.exit()
    
def tempusGetProjectSwagValue(tempusProjectId):
    urlIncludeParams = urlTempusUpdateCustomSwagValue + '?projectIds='
    urlIncludeParams += str(tempusProjectId)
    u = requests.get(urlIncludeParams, headers=headersTempus)
    data = dump.dump_all(u)
    print(data.decode('utf-8'))
    #    {
    #    "value": false,
    #    "customFieldId": 58,
    #    "entityIds": [
    #        555
    #    ]
    #    }

    tempusSwagValue = -1
    if u.status_code == 200:                                                #need the json response value
        my_list = json.loads(u.text)
        iMatched = 0
        for x in my_list:
            pprint(x)  
            for key, value in sorted(x.items()):
                if key == 'customFieldId' :
                    idValue = value
                    if idValue == 57:
                        print("matched idValue = 57, breaking out now")
                        iMatched = 57
            #if key == 'entityIds':
            #    if value[0] == projectId :                    
                elif key == 'value' and iMatched == 57:                    
                    tempusSwagValue = value
                    print("tempusSwagValue = {0}".format(tempusSwagValue))
                    iMatched = 0
                    return tempusSwagValue
    return tempusSwagValue  

def tempusGetProjectDateValues(tempusProjectId):
    urlIncludeParams = urlTempusFindProjectName + '/'
    urlIncludeParams += str(tempusProjectId)
    u = requests.get(urlIncludeParams, headers=headersTempus)

    data = dump.dump_all(u)
    print(data.decode('utf-8'))
    tempusStartDateValue = -1
    tempusEndDateValue = -1
    if u.status_code == 200:    
        data = u.text
        d = json.loads(data)
        for key, value in d.iteritems():
            if key == 'startDate':
                pprint("startDate: is equal to {0}".format(value)) 
                startDate = value                    
            elif key == 'endDate' :                    
                endDate = value
                print("endDate iw equal to {0}".format(endDate))
    return startDate, endDate  
     
#CONST_V1_HOST = "https://www9.v1host.com/"
CONST_V1_HOST = "https://versionone.force10networks.com/"
#urlV1_save = "https://www9.v1host.com/DellSandbox/rest-1.v1/Data/Scope?sel=Name,BeginDate,TargetSwag,TargetEstimate,SecurityScope"
urlV1_save = "https://versionone.force10networks.com/V1/rest-1.oauth.v1/Data/Scope?sel=Name,BeginDate,EndDate,TargetEstimate,SecurityScope,TargetSwag"
#urlV1 = CONST_V1_HOST + "DellSandbox/rest-1.v1/Data/Scope?sel=Name,TargetSwag,TargetEstimate,BeginDate,EndDate"
urlV1 = CONST_V1_HOST + "V1/rest-1.oauth.v1/Data/Scope?sel=Name,BeginDate,EndDate,TargetEstimate,SecurityScope,TargetSwag"
updateSwagV1 = CONST_V1_HOST + "V1/rest-1.oauth.v1/Data/Scope/"
updateDatesV1 = updateSwagV1
#headersV1 = {'Authorization' : 'Bearer 1.eBHz2Iy835YmxiaHBU73NBkD20g=',
#           'Accept' : 'application/json'}
headersV1 = {'Authorization' : 'Bearer 1.Gjmujpc8AH1oOy5QiEqej9VrtD8=',
           'Accept' : 'application/json',
           'Content-Type': 'application/json; charset=utf-8'
           }

#headersV1Post = {'Authorization' : 'Bearer 1.eBHz2Iy835YmxiaHBU73NBkD20g=',
#           'Accept' : 'application/json',
#           'Content-Type' : 'application/json'}
headersV1Post = {'Authorization' : 'Bearer 1.Gjmujpc8AH1oOy5QiEqej9VrtD8=',
           'Accept' : 'application/json',
           'Content-Type': 'application/json; charset=utf-8'}

CONST_TEMPUS_HOST = "http://tempus-eqx-01.force10networks.com/"
urlTempusCreateProjectName = CONST_TEMPUS_HOST + "sg/api/sg/v1/Projects"
urlTempusAddCustomSwagField = CONST_TEMPUS_HOST + "sg/api/sg/v1/CustomFields"
urlTempusUpdateCustomSwagValue = CONST_TEMPUS_HOST + "sg/api/sg/v1/Projects/CustomFieldValues"
urlTempusUpdateCustomSwagLockValue = CONST_TEMPUS_HOST + "sg/api/sg/v1/Projects/CustomFieldValues"
urlTempusFindProjectName = CONST_TEMPUS_HOST + "sg/api/sg/v1/Projects/Get"


headersTempus = {'Authorization' : 'Bearer 835-7ea596c7-0765-43d2-ba39-1bfcfb19a678',
           'Accept' : 'application/json',
           'accept-encoding': 'gzip, deflate',
           'Content-Type' : 'application/json'}
#,
#            'Accept-Encoding' : 'application/json',
#swagValue = tempusGetProjectSwagValue(557)
#print(swagValue)
#V1UpdateProjectSwagValue(1013, swagValue)
#sys.exit()
print("about to fetch request from {0}".format(urlV1))
r = requests.get(urlV1, headers=headersV1)

data = r.text
d = json.loads(data)
#print(d.keys())
#       walk through each project and get the name and swag detail from versionOne
V1ProjectId = -1
for key, value in d.iteritems():
    if key == 'total':
        print("total Assets = %d" % value) 
    i = 0
    if key == 'Assets' :
        myAssetList = value
        print ("myAssetList = ", myAssetList)
        print ("length = ", len(myAssetList))
        for s in myAssetList:
            #s={u'Attributes': 
                #{u'BeginDate': {u'_type': u'Attribute', u'name': u'BeginDate', u'value': u'2017-07-13'}, 
                #u'TargetSwag': {u'_type': u'Attribute', u'name': u'TargetSwag', u'value': 15}, 
                #u'EndDate': {u'_type': u'Attribute', u'name': u'EndDate', u'value': None}, 
                #u'Name': {u'_type': u'Attribute', u'name': u'Name', u'value': u'Dell Networking'}, 
                #u'TargetEstimate': {u'_type': u'Attribute', u'name': u'TargetEstimate', u'value': 15}}, 
            #u'_type': u'Asset', 
            #u'href': u'/DellSandbox/rest-1.v1/Data/Scope/1003', 
            #u'id': u'Scope:1003'
            #}, i=0
            print("s=%s, i=%d"% (s, i))
            TempusProjectName="not found yet"
            for key, value in sorted(s.items()):
                i = i + 1
                if key == 'href':
                    print('href = %s' % value)
                elif key == "Attributes":
#                    print("found Attributes in array")
                    myAssets = value
                    for key, value in myAssets.items():
#                        print("key is now under myAssets.items() =%s" % key)
#                        print("value is now under myAssets.items()=%s" % value)
                        if key == 'Name':
                            #print ("Name = %s" % value)
                            breakoutName = value
                            for key, value in breakoutName.items():
                                if( key == "value"):
                                    print("Name of Project = %s" % value)
                                    TempusProjectName = value
                elif key == 'id':
                    print('found Project id')
                    V1ProjectId = value
                    V1ProjectId = V1ProjectId[6:]
		    #if V1ProjectId == "1044":
                    print('V1ProjectId = {0}'.format(V1ProjectId))
                    
                    entityId = tempusFindProjectName(TempusProjectName)
                    print("entityId = {0}".format(entityId))
                    swagValue = tempusGetProjectSwagValue(entityId)
                    print(swagValue)
                    V1UpdateProjectSwagValue(V1ProjectId, swagValue)
		    print("got to project id = 1044\n")
                    dateBegin, dateEnd = tempusGetProjectDateValues(entityId)
                    V1UpdateProjectDatesValue(V1ProjectId, dateBegin, dateEnd)

