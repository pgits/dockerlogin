
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
#from Carbon.Aliases import false

CONST_SWAG_LOCK = 58
CONST_SWAG_VAR  = 57
def isProjectSwagLocked(projectId):
    urlIncludeParams = urlTempusUpdateCustomSwagLockValue + '?projectIds='
    urlIncludeParams += str(projectId)
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

    isTrueOrFalse = false
    if u.status_code == 200:                                                #need the json response value
        my_list = json.loads(u.text)
        iMatched = 0
        for x in my_list:
            pprint(x)  
            for key, value in sorted(x.items()):
                if key == 'customFieldId' :
                    idValue = value
                    if idValue == 58:
                        print("matched idValue = 58, breaking out now")
                        iMatched = 58
            #if key == 'entityIds':
            #    if value[0] == projectId :                    
                elif key == 'value' and iMatched == 58:                    
                    isTrueOrFalse = value
                    if isTrueOrFalse == False:
                        print("we are returning False")
                    else:
                        print("we are returning True")
                    break
    return isTrueOrFalse       
#CONST_V1_HOST = "https://www9.v1host.com/"
CONST_V1_HOST = "https://versionone.force10networks.com/"
urlV1_save = "https://versionone.force10networks.com/V1/rest-1.v1/Data/Scope?sel=Name,BeginDate,TargetSwag,TargetEstimate,SecurityScope,EndDate"
#urlV1_save = "https://www9.v1host.com/DellSandbox/rest-1.v1/Data/Scope?sel=Name,BeginDate,TargetSwag,TargetEstimate,SecurityScope,EndDate"
#urlV1 = CONST_V1_HOST + "DellSandbox/rest-1.v1/Data/Scope?sel=Name,BeginDate,TargetSwag,TargetEstimate,SecurityScope"
urlV1 = CONST_V1_HOST + "V1/rest-1.v1/Data/Scope?sel=Name,BeginDate,TargetSwag,TargetEstimate,SecurityScope"
headersV1 = {'Authorization' : 'Bearer 1.Gjmujpc8AH1oOy5QiEqej9VrtD8=',
           'Accept' : 'application/json'}
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
headersTempusWW9 = {'Authorization' : 'Bearer 835-7ea596c7-0765-43d2-ba39-1bfcfb19a678',
           'Accept' : 'application/json',
           'accept-encoding': 'gzip, deflate',
           'Content-Type' : 'application/json'}
#,
#            'Accept-Encoding' : 'application/json',
print("urlV1 now equals" + urlV1)
print("urlV1save now equals " + urlV1_save)
r = requests.get(urlV1, headers=headersV1)

data = r.text
print(data)
print("post print")

#d = {}
d = json.loads(data)
#print(d.keys())
#       walk through each project and get the name and swag detail from versionOne
for key, value in d.iteritems():
    if key == 'total':
        print("total Assets = %d" % value) 
    i = 0
    if key == 'Assets' :
        myAssetList = value
        print ("myAssetList = ", myAssetList)
        print ("length = ", len(myAssetList))
        for s in myAssetList:
            print("s=%s, i=%d"% (s, i))
            for key, value in s.items():
                i = i + 1
                if key == 'id':
#                print("found id")
                    print("id = %s" % value)
                elif key == 'href':
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
                                    ProjectName = value
                        elif key == "TargetSwag":
                            #print("TargetSwag = %s" % value)
                            breakoutSwag = value
                            for key, value in breakoutSwag.items():
                                if( key == "value"):
                                    print("Swag  = %s" % value)
                                    SwagValue = value
                                    if SwagValue == None:
                                        SwagValue = 0
                                    quoteChar = '"'
                                    rightBracket = '}'
                                    #datas = "[{quoteChar updateSecurityGroup quoteChar: "true","securityGroupId": 1,"id": "null","name": \""
                                    datas = '[ { %c' % (quoteChar)
                                    #datas = '{ %c' % (quoteChar)
                                    datas += 'updateSecurityGroup%c: true, ' % (quoteChar)
                                    print (datas)
                                    datas += '{0}securityGroupId{1}'.format((quoteChar), (quoteChar))
                                    print (datas)
                                    datas += ': 1, {0}id{1}: null, '.format((quoteChar), (quoteChar))
                                    print (datas)
                                    datas += '{0}name{1}: '.format((quoteChar), (quoteChar))
                                    print (datas)
                                    # "securityGroupId": 1,"id": "null","name": \""
                                    datas += '{0}{1}{2}, '.format((quoteChar), (ProjectName), (quoteChar))
                                    print (datas)
                                    datas += '{0}updateProjectDates{1}: true, '.format((quoteChar), (quoteChar))
                                    print (datas)
                                    datas += '{0}startDate{1}: {2}2017-08-24T05:08:55Z{3}, '.format((quoteChar),(quoteChar), (quoteChar), (quoteChar))
                                    print (datas)
                                    #datas += '{0}endDate{1}: {2}2017-10-08T05:08:55Z{3} {4}'.format((quoteChar), (quoteChar), (quoteChar), (quoteChar), (rightBracket))
                                    datas += '{0}endDate{1}: {2}2017-10-08T05:08:55Z{3} {4} ]'.format((quoteChar), (quoteChar), (quoteChar), (quoteChar), (rightBracket))
                                    print("json datas now = %s" % datas)
                                    
                                    try :
                                       # t = requests.post(urlTempusCreateProjectName, json=datas, headers=headersTempus)
                                       ## t = requests.post(urlTempusCreateProjectName, data=json.dumps(datas), headers=headersTempus)
                                        t = requests.post(urlTempusCreateProjectName, data=datas, headers=headersTempus)
                                        data = dump.dump_all(t)
                                        print(data.decode('utf-8'))
                                        print("add project group response = [%s]\n" % t.text)
                                        print("project group response status = %d" % t.status_code)
                                        if t.status_code == 200: 
                                            #need the json response value
                                            entityId = re.findall(r'\d+', t.text)
                                            print("entityId = {0}".format(entityId[0]))
                                            # must use the code returned
                                            
                                            dataSwag = '[ { {0}value{1}: {2}, \n'.format((quoteChar),(quoteChar),(SwagValue))
                                            dataSwag += '{0}customFieldId{1}: 57,'.format((quoteChar), (quoteChar))
                                            dataSwag += '{0}entityIds{1}: ['.format((quoteChar), quoteChar)
                                            dataSwag += '{0}'.format(entityId)
                                            dataSwag += '],'
                                            dataSwag += '{0}assignmentIds{1}: null'.format((quoteChar), (quoteChar))
                                            dataSwag += ' \n} \n]' 
                                           # datasSwagOrig = [ { "value": SwagValue,
                                            #        "customFieldId": 57,  #create Swag field number
                                            #        "entityIds": [entityId    #552
                                            #                      ],
                                            #        "assignmentIds": null
                                           # } ]
                                            print('datasSwag = %s'% dataSwag)
                                            q = requests.put(urlTempusUpdateCustomSwagValue, data=dataSwag, headers=headersTempus )
                                            if q.status_code == 200:
                                                print("update Swag value = %s" % q.text)
                                                sys.exit()
                                        else:
                                            print("status != 200, =500 on create, it may be that it already exists!")
                                            urlIncludeParams = urlTempusFindProjectName + '?name='
                                            urlIncludeParams += ProjectName
                                            urlIncludeParams += "&page=1&pageSize=10"
                                            u = requests.get(urlIncludeParams, headers=headersTempus)
                                            data = dump.dump_all(u)
                                            print(data.decode('utf-8'))
                                            if u.status_code == 200:                                                #need the json response value
                                                #(parse the value below next)
                                                #{"page":1,"pageSize":10,"totalItems":2,"items":[{"createdOn":"2017-08-25T12:36:00.1877869","createdBy":{"resourceId":835,"systemActor":null},"updatedOn":"2017-08-25T12:36:00.1877869","updatedBy":{"resourceId":835,"systemActor":null},"startDate":"2017-08-24T00:00:00","endDate":"2017-10-08T00:00:00","securityGroupId":1,"id":555,"name":"Dell Networking"},{"createdOn":"2017-08-25T13:30:10.513793","createdBy":{"resourceId":835,"systemActor":null},"updatedOn":"2017-08-25T13:30:10.513793","updatedBy":{"resourceId":835,"systemActor":null},"startDate":"2017-08-24T00:00:00","endDate":"2017-09-08T00:00:00","

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
                                                                    if key == 'id':
                                                                        print("found id")
                                                                        print("id = {0}".format(value))
                                                                        entityId = value
                                                                        print("entityId = {0}".format(entityId))
                                                                        # must use the code returned
                                                                        dataSwag  = '[ { \n'
                                                                        dataSwag += '{0}value{1}: {2}, \n'.format((quoteChar),(quoteChar),(SwagValue))
                                                                        dataSwag += '{0}customFieldId{1}: 57,'.format((quoteChar), (quoteChar))
                                                                        dataSwag += '{0}entityIds{1}: ['.format((quoteChar), quoteChar)
                                                                        dataSwag += '{0}'.format(entityId)
                                                                        dataSwag += '],'
                                                                        dataSwag += '{0}assignmentIds{1}: null'.format((quoteChar), (quoteChar))
                                                                        dataSwag += ' \n} \n]'
                                                                        #datasSwag = [ {"value": SwagValue,
                                                                        #            "customFieldId": 57,  #create Swag field number
                                                                        #             "entityIds": [entityId    #552
                                                                        #                           ],
                                                                        #             "assignmentIds": null
                                                                        #} ]
                                                                        boolReturn = isProjectSwagLocked(entityId)
                                                                        print ('returned isTrueOrFalse = {0}'.format(boolReturn))
                                                                        if boolReturn == True:
                                                                            print("Not updating SwagValue because we found a SwagLock!!")
                                                                        else:
                                                                            print("datasSwag about to update = %s" % dataSwag)
                                                                            q = requests.put(urlTempusUpdateCustomSwagValue, data=dataSwag, headers=headersTempus )
                                                                            data = dump.dump_all(q)
                                                                            print(data.decode('utf-8'))
                                                                            if q.status_code == 204:
                                                                                print("updated Swag value = {0}".format(q.text))
                                                                            else:
                                                                                print("failed to update value = {0}".format(q.text))
                                                                                print(q.text)
                                                                            #sys.exit()
                                            else:
                                                print('failed to locate project = %s' % ProjectName)
                                    except ConnectionError as theError:
                                            print (theError)
                                            t = "No response"
                                    

