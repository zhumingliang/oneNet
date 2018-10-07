define({ "api": [  {    "type": "GET",    "url": "/api/v1/receive/list",    "title": "获取数据列表",    "group": "API",    "version": "1.0.1",    "description": "<p>根据设备IMEI号，开始时间和截止时间获取历史数据</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://oil.mengant.cn/api/v1/receive/list?equipmentId=865820031313187&startTime=2018-09-20&endTime=2018-10-01&page=1&size=2",        "type": "get"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "equipmentId",            "description": "<p>设备IMEI号</p>"          },          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "startTime",            "description": "<p>开始时间</p>"          },          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "endTime",            "description": "<p>截止时间</p>"          },          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "page",            "description": "<p>当前页数</p>"          },          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "size",            "description": "<p>每页条数</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"total\":784,\"per_page\":\"2\",\"current_page\":1,\"last_page\":392,\"data\":[{\"id\":920,\"at\":\"1537991802230\",\"imei\":\"865820031313187\",\"type\":1,\"ds_id\":\"3316_0_5700\",\"value\":\"4.82\",\"dev_id\":\"44631936\",\"create_time\":\"2018-09-27 03:56:42\"},{\"id\":919,\"at\":\"1537991759033\",\"imei\":\"865820031313187\",\"type\":1,\"ds_id\":\"3300_0_5750\",\"value\":\"0.1A0.2A5A180A\",\"dev_id\":\"44631936\",\"create_time\":\"2018-09-27 03:55:59\"}]}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "total",            "description": "<p>数据总数</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "per_page",            "description": "<p>每页多少条数据</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "current_page",            "description": "<p>当前页码</p>"          },          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "last_page",            "description": "<p>最后页码</p>"          },          {            "group": "返回参数说明",            "type": "obj",            "optional": false,            "field": "data",            "description": "<p>数据</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Index.php",    "groupTitle": "API",    "name": "GetApiV1ReceiveList"  },  {    "type": "GET",    "url": "/api/v1/receive/recent",    "title": "获取指定设备最近一条数据",    "group": "API",    "version": "1.0.1",    "description": "<p>根据设备IMEI号，获取最近一条设备数据</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://oil.mengant.cn/api/v1/receive/recent?equipmentId=865820031313187",        "type": "get"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "equipmentId",            "description": "<p>设备IMEI号</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"id\":920,\"at\":\"1537991802230\",\"imei\":\"865820031313187\",\"type\":1,\"ds_id\":\"3316_0_5700\",\"value\":\"4.82\",\"dev_id\":\"44631936\",\"create_time\":\"2018-09-27 03:56:42\"}",          "type": "json"        }      ]    },    "filename": "application/api/controller/v1/Index.php",    "groupTitle": "API",    "name": "GetApiV1ReceiveRecent"  },  {    "type": "GET",    "url": "/api/v1/receive/send",    "title": "向设备发送指令",    "group": "API",    "version": "1.0.1",    "description": "<p>根据设备IMEI号，获取最近一条设备数据</p>",    "examples": [      {        "title": "请求样例:",        "content": "http://oil.mengant.cn/api/v1/receive/send?ds_id=\"3300_0_5700\"&imei=\"865820031289270\"&X=0.1&Y=0.2&threshold=5&interval=180",        "type": "get"      }    ],    "parameter": {      "fields": {        "请求参数说明": [          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "ds_id",            "description": "<p>设备参数组：obj_id/obj_inst_id/res_id 三个参数用&quot;_&quot;连接</p>"          },          {            "group": "请求参数说明",            "type": "String",            "optional": false,            "field": "imei",            "description": "<p>设备IMEI号</p>"          },          {            "group": "请求参数说明",            "type": "float",            "optional": false,            "field": "X",            "description": "<p>X倾角</p>"          },          {            "group": "请求参数说明",            "type": "float",            "optional": false,            "field": "Y",            "description": "<p>Y倾角</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "threshold",            "description": "<p>警告阀值</p>"          },          {            "group": "请求参数说明",            "type": "int",            "optional": false,            "field": "interval",            "description": "<p>测量间隔 单位S</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "返回样例:",          "content": "{\"msg\":\"ok\",\"errorCode\":0}",          "type": "json"        }      ],      "fields": {        "返回参数说明": [          {            "group": "返回参数说明",            "type": "int",            "optional": false,            "field": "error_code",            "description": "<p>错误码： 0表示操作成功无错误</p>"          },          {            "group": "返回参数说明",            "type": "String",            "optional": false,            "field": "msg",            "description": "<p>信息描述</p>"          }        ]      }    },    "filename": "application/api/controller/v1/Index.php",    "groupTitle": "API",    "name": "GetApiV1ReceiveSend"  }] });
