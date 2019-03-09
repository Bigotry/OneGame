/*
Navicat MySQL Data Transfer

Source Server         : localhost_link
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : onegame

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-03-09 12:48:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ob_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `ob_action_log`;
CREATE TABLE `ob_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行会员id',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` char(30) NOT NULL DEFAULT '' COMMENT '执行行为者ip',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '行为名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '执行的URL',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25462 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of ob_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_addon`
-- ----------------------------
DROP TABLE IF EXISTS `ob_addon`;
CREATE TABLE `ob_addon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `config` text NOT NULL COMMENT '配置',
  `author` varchar(40) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of ob_addon
-- ----------------------------
INSERT INTO `ob_addon` VALUES ('3', 'File', '文件上传', '文件上传插件', '', 'Jack', '1.0', '1', '0', '0');
INSERT INTO `ob_addon` VALUES ('4', 'Icon', '图标选择', '图标选择插件', '', 'Bigotry', '1.0', '1', '0', '0');
INSERT INTO `ob_addon` VALUES ('5', 'Editor', '文本编辑器', '富文本编辑器', '', 'Bigotry', '1.0', '1', '0', '0');

-- ----------------------------
-- Table structure for `ob_api`
-- ----------------------------
DROP TABLE IF EXISTS `ob_api`;
CREATE TABLE `ob_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(150) NOT NULL DEFAULT '' COMMENT '接口名称',
  `group_id` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '接口分组',
  `request_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '请求类型 0:POST  1:GET',
  `api_url` char(50) NOT NULL DEFAULT '' COMMENT '请求路径',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '接口描述',
  `describe_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '接口富文本描述',
  `is_request_data` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要请求数据',
  `request_data` text NOT NULL COMMENT '请求数据',
  `response_data` text NOT NULL COMMENT '响应数据',
  `is_response_data` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要响应数据',
  `is_user_token` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要用户token',
  `is_response_sign` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否返回数据签名',
  `is_request_sign` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否验证请求数据签名',
  `response_examples` text NOT NULL COMMENT '响应栗子',
  `developer` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '研发者',
  `api_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '接口状态（0:待研发，1:研发中，2:测试中，3:已完成）',
  `is_page` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为分页接口 0：否  1：是',
  `sort` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=193 DEFAULT CHARSET=utf8 COMMENT='API表';

-- ----------------------------
-- Records of ob_api
-- ----------------------------
INSERT INTO `ob_api` VALUES ('186', '登录或注册', '34', '0', 'common/login', '系统登录注册接口，若用户名存在则验证密码正确性，若用户名不存在则注册新用户，返回 user_token 用于操作需验证身份的接口', '', '1', '[{\"field_name\":\"username\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u7528\\u6237\\u540d\"},{\"field_name\":\"password\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u5bc6\\u7801\"}]', '[{\"field_name\":\"data\",\"data_type\":\"2\",\"field_describe\":\"\\u4f1a\\u5458\\u6570\\u636e\\u53causer_token\"}]', '1', '0', '1', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;member_id&quot;: 51,\r\n        &quot;nickname&quot;: &quot;sadasdas&quot;,\r\n        &quot;username&quot;: &quot;sadasdas&quot;,\r\n        &quot;create_time&quot;: &quot;2017-09-09 13:40:17&quot;,\r\n        &quot;user_token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmVCYXNlIEpXVCIsImlhdCI6MTUwNDkzNTYxNywiZXhwIjoxNTA0OTM2NjE3LCJhdWQiOiJPbmVCYXNlIiwic3ViIjoiT25lQmFzZSIsImRhdGEiOnsibWVtYmVyX2lkIjo1MSwibmlja25hbWUiOiJzYWRhc2RhcyIsInVzZXJuYW1lIjoic2FkYXNkYXMiLCJjcmVhdGVfdGltZSI6IjIwMTctMDktMDkgMTM6NDA6MTcifX0.6PEShODuifNsa-x1TumLoEaR2TCXpUEYgjpD3Mz3GRM&quot;\r\n    }\r\n}', '0', '1', '0', '0', '1', '1504501410', '1504949075');
INSERT INTO `ob_api` VALUES ('187', '文章分类列表', '44', '0', 'article/categorylist', '文章分类列表接口', '', '0', '', '[{\"field_name\":\"id\",\"data_type\":\"0\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7bID\"},{\"field_name\":\"name\",\"data_type\":\"0\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7b\\u540d\\u79f0\"}]', '1', '0', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: [\r\n        {\r\n            &quot;id&quot;: 2,\r\n            &quot;name&quot;: &quot;测试文章分类2&quot;\r\n        },\r\n        {\r\n            &quot;id&quot;: 1,\r\n            &quot;name&quot;: &quot;测试文章分类1&quot;\r\n        }\r\n    ]\r\n}', '0', '0', '0', '2', '1', '1504765581', '1507366297');
INSERT INTO `ob_api` VALUES ('188', '文章列表', '44', '0', 'article/articlelist', '文章列表接口', '', '1', '[{\"field_name\":\"category_id\",\"data_type\":\"0\",\"is_require\":\"0\",\"field_describe\":\"\\u82e5\\u4e0d\\u4f20\\u9012\\u6b64\\u53c2\\u6570\\u5219\\u4e3a\\u6240\\u6709\\u5206\\u7c7b\"}]', '', '0', '0', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;total&quot;: 9,\r\n        &quot;per_page&quot;: &quot;10&quot;,\r\n        &quot;current_page&quot;: 1,\r\n        &quot;last_page&quot;: 1,\r\n        &quot;data&quot;: [\r\n            {\r\n                &quot;id&quot;: 16,\r\n                &quot;name&quot;: &quot;11111111&quot;,\r\n                &quot;category_id&quot;: 2,\r\n                &quot;describe&quot;: &quot;22222222&quot;,\r\n                &quot;create_time&quot;: &quot;2017-08-07 13:58:37&quot;\r\n            },\r\n            {\r\n                &quot;id&quot;: 15,\r\n                &quot;name&quot;: &quot;tttttt&quot;,\r\n                &quot;category_id&quot;: 1,\r\n                &quot;describe&quot;: &quot;sddd&quot;,\r\n                &quot;create_time&quot;: &quot;2017-08-07 13:24:46&quot;\r\n            }\r\n        ]\r\n    }\r\n}', '0', '0', '1', '1', '1', '1504779780', '1507366268');
INSERT INTO `ob_api` VALUES ('189', '首页接口', '45', '0', 'combination/index', '首页聚合接口', '', '1', '[{\"field_name\":\"category_id\",\"data_type\":\"0\",\"is_require\":\"0\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7bID\"}]', '[{\"field_name\":\"article_category_list\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7b\\u6570\\u636e\"},{\"field_name\":\"article_list\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u6570\\u636e\"}]', '1', '0', '1', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;article_category_list&quot;: [\r\n            {\r\n                &quot;id&quot;: 2,\r\n                &quot;name&quot;: &quot;测试文章分类2&quot;\r\n            },\r\n            {\r\n                &quot;id&quot;: 1,\r\n                &quot;name&quot;: &quot;测试文章分类1&quot;\r\n            }\r\n        ],\r\n        &quot;article_list&quot;: {\r\n            &quot;total&quot;: 8,\r\n            &quot;per_page&quot;: &quot;2&quot;,\r\n            &quot;current_page&quot;: &quot;1&quot;,\r\n            &quot;last_page&quot;: 4,\r\n            &quot;data&quot;: [\r\n                {\r\n                    &quot;id&quot;: 15,\r\n                    &quot;name&quot;: &quot;tttttt&quot;,\r\n                    &quot;category_id&quot;: 1,\r\n                    &quot;describe&quot;: &quot;sddd&quot;,\r\n                    &quot;create_time&quot;: &quot;2017-08-07 13:24:46&quot;\r\n                },\r\n                {\r\n                    &quot;id&quot;: 14,\r\n                    &quot;name&quot;: &quot;1111111111111111111&quot;,\r\n                    &quot;category_id&quot;: 1,\r\n                    &quot;describe&quot;: &quot;123123&quot;,\r\n                    &quot;create_time&quot;: &quot;2017-08-04 15:37:20&quot;\r\n                }\r\n            ]\r\n        }\r\n    }\r\n}', '0', '0', '1', '0', '1', '1504785072', '1504948716');
INSERT INTO `ob_api` VALUES ('190', '详情页接口', '45', '0', 'combination/details', '详情页接口', '', '1', '[{\"field_name\":\"article_id\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u6587\\u7ae0ID\"}]', '[{\"field_name\":\"article_category_list\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7b\\u6570\\u636e\"},{\"field_name\":\"article_details\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u8be6\\u60c5\\u6570\\u636e\"}]', '1', '0', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;article_category_list&quot;: [\r\n            {\r\n                &quot;id&quot;: 2,\r\n                &quot;name&quot;: &quot;测试文章分类2&quot;\r\n            },\r\n            {\r\n                &quot;id&quot;: 1,\r\n                &quot;name&quot;: &quot;测试文章分类1&quot;\r\n            }\r\n        ],\r\n        &quot;article_details&quot;: {\r\n            &quot;id&quot;: 1,\r\n            &quot;name&quot;: &quot;213&quot;,\r\n            &quot;category_id&quot;: 1,\r\n            &quot;describe&quot;: &quot;test001&quot;,\r\n            &quot;content&quot;: &quot;第三方发送到&quot;&quot;&quot;,\r\n            &quot;create_time&quot;: &quot;2014-07-22 11:56:53&quot;\r\n        }\r\n    }\r\n}', '0', '0', '0', '0', '1', '1504922092', '1504923179');
INSERT INTO `ob_api` VALUES ('191', '修改密码', '34', '0', 'common/changepassword', '修改密码接口', '', '1', '[{\"field_name\":\"old_password\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u65e7\\u5bc6\\u7801\"},{\"field_name\":\"new_password\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u65b0\\u5bc6\\u7801\"}]', '', '0', '1', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;exe_time&quot;: &quot;0.037002&quot;\r\n}', '0', '0', '0', '0', '-1', '1504941496', '1528686103');
INSERT INTO `ob_api` VALUES ('192', '合作方角色查询', '72', '0', 'Game/roles', '导量合作方角色信息查询接口', '', '1', '[{\"field_name\":\"member_id\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u4f1a\\u5458ID\"},{\"field_name\":\"channel\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u6e20\\u9053\\u6807\\u8bc6\"},{\"field_name\":\"game_code\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u6e38\\u620f\\u6807\\u8bc6\"},{\"field_name\":\"cp_server_id\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u6e38\\u620f\\u670d\\u52a1\\u5668\"}]', '', '0', '0', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;UserID&quot;: &quot;55941&quot;,\r\n        &quot;UserName&quot;: &quot;aaa1ed3333&quot;,\r\n        &quot;ServerName&quot;: &quot;双线76服&quot;,\r\n        &quot;UserRole&quot;: &quot;吉胜翔&quot;,\r\n        &quot;UserLevel&quot;: &quot;62&quot;,\r\n        &quot;Payment&quot;: 0\r\n    },\r\n    &quot;exe_time&quot;: &quot;0.116797&quot;\r\n}', '0', '1', '0', '0', '1', '1526090942', '1528713086');

-- ----------------------------
-- Table structure for `ob_api_group`
-- ----------------------------
DROP TABLE IF EXISTS `ob_api_group`;
CREATE TABLE `ob_api_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(120) NOT NULL DEFAULT '' COMMENT 'aip分组名称',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COMMENT='api分组表';

-- ----------------------------
-- Records of ob_api_group
-- ----------------------------
INSERT INTO `ob_api_group` VALUES ('34', '基础接口', '0', '1504501195', '0', '1');
INSERT INTO `ob_api_group` VALUES ('44', '文章接口', '1', '1504765319', '1504765319', '1');
INSERT INTO `ob_api_group` VALUES ('45', '聚合接口', '0', '1504784149', '1504784149', '1');
INSERT INTO `ob_api_group` VALUES ('72', '游戏接口', '0', '1526089819', '1526089819', '1');

-- ----------------------------
-- Table structure for `ob_article`
-- ----------------------------
DROP TABLE IF EXISTS `ob_article`;
CREATE TABLE `ob_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '文章名称',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text NOT NULL COMMENT '文章内容',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面图片id',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件id',
  `img_ids` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章表';

-- ----------------------------
-- Records of ob_article
-- ----------------------------
INSERT INTO `ob_article` VALUES ('1', '1', '1', '关于OneGame', '7', '关于OneGame', '1', '0', '0', '', '1525244213', '1545997631', '1');
INSERT INTO `ob_article` VALUES ('2', '1', '1', '联系我们', '7', '联系我们', '2', '0', '0', '', '1525244237', '1545997645', '1');
INSERT INTO `ob_article` VALUES ('3', '11', '1', '诚聘英才', '7', '诚聘英才', '3', '0', '0', '', '1525244262', '1525244262', '1');
INSERT INTO `ob_article` VALUES ('4', '1', '1', '商务合作', '7', '商务合作', '4', '0', '0', '', '1525244278', '1545997666', '1');
INSERT INTO `ob_article` VALUES ('5', '11', '1', '家长监护', '7', '家长监护', '5', '0', '0', '', '1525244297', '1525244297', '1');
INSERT INTO `ob_article` VALUES ('6', '1', '1', '挂机辅助', '12', '挂机辅助', '6', '0', '0', '', '1525314199', '1546001775', '1');
INSERT INTO `ob_article` VALUES ('7', '13', '1', '交易系统', '12', '交易系统', '7', '0', '0', '', '1525315870', '1525315870', '1');
INSERT INTO `ob_article` VALUES ('8', '13', '1', '锻造系统', '12', '锻造系统', '8', '0', '0', '', '1525315913', '1525946359', '1');
INSERT INTO `ob_article` VALUES ('10', '24', '59748', 'OneGame《XXX》6月2日14：50点双线71服开启', '10', '', '1', '0', '0', '', '1527854400', '1530251095', '1');
INSERT INTO `ob_article` VALUES ('11', '24', '59748', 'OneGame《XXX》6月3日10：00点双线72服开启', '10', '', '2', '0', '0', '', '1527940800', '1530251117', '1');
INSERT INTO `ob_article` VALUES ('12', '24', '59748', 'OneGame《XXX》6月5日14：50点双线73服开启', '10', '', '3', '0', '0', '', '1528113600', '1530251151', '1');
INSERT INTO `ob_article` VALUES ('13', '24', '59748', '6月5日《XXX》合区公告', '17', '', '1', '0', '0', '', '1528113600', '1528926217', '1');
INSERT INTO `ob_article` VALUES ('14', '24', '59748', '6月6号《XXX》全民斗笠活动来袭~', '10', '', '2', '0', '0', '', '1528200000', '1528926314', '1');
INSERT INTO `ob_article` VALUES ('15', '24', '59748', 'OneGame《XXX》6月7日14：50点双线74服开启', '10', '', '4', '0', '0', '', '1528286400', '1530251187', '1');
INSERT INTO `ob_article` VALUES ('16', '24', '59748', 'OneGame《XXX》6月9日14：50点双线75服开启', '10', '', '5', '0', '0', '', '1528459200', '1530251206', '1');
INSERT INTO `ob_article` VALUES ('17', '24', '59748', 'OneGame《XXX》6月11日14：50点双线76服开启', '10', '', '6', '0', '0', '', '1528632000', '1530251242', '1');
INSERT INTO `ob_article` VALUES ('18', '24', '59748', '6月12日《XXX》合区公告', '17', '', '7', '0', '0', '', '1528718400', '1528926554', '1');
INSERT INTO `ob_article` VALUES ('19', '24', '59748', 'OneGame《XXX》 6月13日14：50点双线77服开启', '10', '', '8', '0', '0', '', '1528804800', '1530251265', '1');
INSERT INTO `ob_article` VALUES ('20', '24', '59748', '6月13日《XXX》全民宝石活动来袭~', '10', '', '3', '0', '0', '', '1528804800', '1529495939', '1');
INSERT INTO `ob_article` VALUES ('21', '24', '59748', 'OneGame《XXX》平台升级更新', '10', '', '9', '0', '0', '', '1528891200', '1528927191', '1');
INSERT INTO `ob_article` VALUES ('22', '24', '59748', '《XXX》充值回馈', '7', '', '10', '0', '0', '', '1515672000', '1528927279', '1');

-- ----------------------------
-- Table structure for `ob_article_category`
-- ----------------------------
DROP TABLE IF EXISTS `ob_article_category`;
CREATE TABLE `ob_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `icon` char(20) NOT NULL DEFAULT '' COMMENT '分类图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分类表';

-- ----------------------------
-- Records of ob_article_category
-- ----------------------------
INSERT INTO `ob_article_category` VALUES ('7', '新闻资讯', '新闻资讯', '1509620712', '1524298122', '1', 'fa-street-view');
INSERT INTO `ob_article_category` VALUES ('10', '游戏公告', '游戏公告', '1516781906', '1524298104', '1', '');
INSERT INTO `ob_article_category` VALUES ('12', '新手指南', '新手指南', '1516781920', '1524297837', '1', '');
INSERT INTO `ob_article_category` VALUES ('17', '合区', '合区', '1516781953', '1516781953', '1', '');

-- ----------------------------
-- Table structure for `ob_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `ob_auth_group`;
CREATE TABLE `ob_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `describe` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of ob_auth_group
-- ----------------------------
INSERT INTO `ob_auth_group` VALUES ('1', '', '公会代理', '公会代理，拥有发展公会的权限。', '1', '1,208,209,210,211,229,230,237,232,233,252,235,236', '1', '1526284895', '1517046565');
INSERT INTO `ob_auth_group` VALUES ('2', '', '渠道合作方', '渠道合作方，只能查看此渠道相关会员及充值数据。', '1', '', '1', '1517046698', '1517046698');
INSERT INTO `ob_auth_group` VALUES ('3', '', '公司财务', '拥有查看导出所有充值订单及公公相关数据权限。', '1', '1,208,209,229,230,237,232,233,262,234,252,235,236,257,264', '1', '1529568731', '1517046754');
INSERT INTO `ob_auth_group` VALUES ('4', '', '公会管理员', '公会管理员，可以管理公会员工及角色绑定等权限。', '1', '1,208,212,213,214,215,216,231,217,218,229,230,237,232,233,262,234,235,236', '1', '1530689517', '1517046855');
INSERT INTO `ob_auth_group` VALUES ('5', '', '公会员工', '公会员工，能够查看自己导入的角色及充值记录。', '1', '1,208,215,216,231,217,218,229,230,237,232,235,236', '1', '1530689516', '1517046928');
INSERT INTO `ob_auth_group` VALUES ('6', '', '公司运营', '公司运营者权限组，以管理员身份维护部分功能保证业务正常，记录操作行为。', '1', '1,144,145,150,153,238,146,147,154,148,149,16,17,253,254,255,203,206,239,240,241,243,244,207,246,247,205,248,249,204,250,251,68,219,220,221,222,223,224,225,226,227,228,208,263,209,259,260,261,210,211,212,213,214,215,216,231,217,256,218,245,229,230,237,232,233,262,234,252,235,258,236,257,264,265', '1', '1530689516', '1524129841');
INSERT INTO `ob_auth_group` VALUES ('7', '', '公司管理', '公司管理人员，拥有运营权限，并可以查看操作日志。', '1', '1,144,145,150,153,238,146,147,154,148,149,166,174,16,17,253,254,255,203,206,239,240,241,243,244,207,246,247,205,248,249,204,250,251,68,219,220,221,222,223,224,225,226,227,228,208,263,209,259,260,261,210,211,212,213,214,215,216,231,217,256,218,245,229,230,237,232,233,262,234,252,235,258,236,257,264,265', '1', '1530689351', '1525945896');
INSERT INTO `ob_auth_group` VALUES ('8', '', '系统演示', '系统演示权限组', '1', '1,144,145,146,157,158,162,166,167,174,176,177,178,198,199,200,201,202,16,17,203,206,241,207,205,204,68,135,136,140,141,142,143,219,220,224,225,70,126,75,124,208,209,259,260,212,215,217,229,230,237,232,233,262,234,252,235,236,257,264', '1', '1546096943', '1546096156');

-- ----------------------------
-- Table structure for `ob_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `ob_auth_group_access`;
CREATE TABLE `ob_auth_group_access` (
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组授权表';

-- ----------------------------
-- Records of ob_auth_group_access
-- ----------------------------
INSERT INTO `ob_auth_group_access` VALUES ('4', '8', '1546096431', '1546096431', '1');

-- ----------------------------
-- Table structure for `ob_blogroll`
-- ----------------------------
DROP TABLE IF EXISTS `ob_blogroll`;
CREATE TABLE `ob_blogroll` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL DEFAULT '' COMMENT '链接名称',
  `img_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '链接图片封面',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of ob_blogroll
-- ----------------------------
INSERT INTO `ob_blogroll` VALUES ('4', 'OneBase', '0', 'https://gitee.com/Bigotry/OneBase', '', '0', '1', '1552104589', '1552104589');
INSERT INTO `ob_blogroll` VALUES ('5', 'OneVideo', '0', 'https://gitee.com/Bigotry/OneVideo', '', '0', '1', '1552104603', '1552104603');
INSERT INTO `ob_blogroll` VALUES ('6', 'OneBaidu', '0', 'https://gitee.com/Bigotry/OneBaidu', '', '0', '1', '1552104619', '1552104619');

-- ----------------------------
-- Table structure for `ob_config`
-- ----------------------------
DROP TABLE IF EXISTS `ob_config`;
CREATE TABLE `ob_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置选项',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COMMENT='配置表';

-- ----------------------------
-- Records of ob_config
-- ----------------------------
INSERT INTO `ob_config` VALUES ('1', 'seo_title', '1', '网站标题', '1', '', '网站标题前台显示标题，优先级低于SEO模块', '1378898976', '1552106301', '1', '基于OneBase开发的游戏联运平台OneGame', '3');
INSERT INTO `ob_config` VALUES ('2', 'seo_description', '2', '网站描述', '1', '', '网站搜索引擎描述，优先级低于SEO模块', '1378898976', '1552106301', '1', 'OneGame 致力于打造国内最强页游联运平台', '100');
INSERT INTO `ob_config` VALUES ('3', 'seo_keywords', '2', '网站关键字', '1', '', '网站搜索引擎关键字，优先级低于SEO模块', '1378898976', '1552106301', '1', '页游联运平台,OneGame,网页游戏平台,OneBase', '99');
INSERT INTO `ob_config` VALUES ('9', 'config_type_list', '3', '配置类型列表', '3', '', '主要用于数据解析和页面表单的生成', '1378898976', '1528923967', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片\r\n6:文件\r\n7:富文本\r\n8:单选\r\n9:多选\r\n10:日期\r\n11:时间\r\n12:颜色', '100');
INSERT INTO `ob_config` VALUES ('20', 'config_group_list', '3', '配置分组', '3', '', '配置分组', '1379228036', '1528923967', '1', '1:基础\r\n2:数据\r\n3:系统\r\n4:API\r\n5:业务\r\n6:客服', '100');
INSERT INTO `ob_config` VALUES ('25', 'list_rows', '0', '每页数据记录数', '2', '', '数据每页显示记录数', '1379503896', '1528962913', '1', '15', '10');
INSERT INTO `ob_config` VALUES ('29', 'data_backup_part_size', '0', '数据库备份卷大小', '2', '', '该值用于限制压缩后的分卷最大长度。单位：B', '1381482488', '1528962913', '1', '52428800', '7');
INSERT INTO `ob_config` VALUES ('30', 'data_backup_compress', '4', '数据库备份文件是否启用压缩', '2', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1528962913', '1', '1', '9');
INSERT INTO `ob_config` VALUES ('31', 'data_backup_compress_level', '4', '数据库备份文件压缩级别', '2', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1528962913', '1', '9', '10');
INSERT INTO `ob_config` VALUES ('33', 'allow_url', '3', '不受权限验证的url', '3', '', '', '1386644047', '1528923967', '1', '0:file/pictureupload\r\n1:addon/execute\r\n2:file/fileupload\r\n3:accounts/getnewroleoptions\r\n4:analyze/getserveroptions\r\n5:accounts/updaterole\r\n6:file/editorpictureupload', '100');
INSERT INTO `ob_config` VALUES ('43', 'empty_list_describe', '1', '数据列表为空时的描述信息', '2', '', '', '1492278127', '1528962913', '1', 'aOh! 暂时还没有数据~', '0');
INSERT INTO `ob_config` VALUES ('44', 'trash_config', '3', '回收站配置', '3', '', 'key为模型名称，值为显示列。', '1492312698', '1528923967', '1', 'Config:name\r\nAuthGroup:name\r\nMember:nickname\r\nMenu:name\r\nArticle:name\r\nArticleCategory:name\r\nAddon:name\r\nPicture:name\r\nFile:name\r\nActionLog:describe\r\nApi:name\r\nApiGroup:name\r\nBlogroll:name\r\nExeLog:exe_url\r\nSeo:name', '0');
INSERT INTO `ob_config` VALUES ('49', 'static_domain', '1', '静态资源域名', '1', '', '若静态资源为本地资源则此项为空，若为外部资源则为存放静态资源的域名', '1502430387', '1552106301', '1', '', '0');
INSERT INTO `ob_config` VALUES ('52', 'team_developer', '3', '研发团队人员', '4', '', '', '1504236453', '1528962834', '1', '0:Bigotry\r\n1:扫地僧', '0');
INSERT INTO `ob_config` VALUES ('53', 'api_status_option', '3', 'API接口状态', '4', '', '', '1504242433', '1528962834', '1', '0:待研发\r\n1:研发中\r\n2:测试中\r\n3:已完成', '0');
INSERT INTO `ob_config` VALUES ('54', 'api_data_type_option', '3', 'API数据类型', '4', '', '', '1504328208', '1528962834', '1', '0:字符\r\n1:文本\r\n2:数组\r\n3:文件', '0');
INSERT INTO `ob_config` VALUES ('55', 'frontend_theme', '1', '前端主题', '1', '', '', '1504762360', '1552106301', '1', 'default', '0');
INSERT INTO `ob_config` VALUES ('56', 'api_domain', '1', 'API部署域名', '4', '', '', '1504779094', '1528962834', '1', 'http://youlaiwan.com', '0');
INSERT INTO `ob_config` VALUES ('57', 'api_key', '1', 'API加密KEY', '4', '', '泄露后API将存在安全隐患', '1505302112', '1528962834', '1', 'l2V|gfZp{8`;jzR~6Y1_', '0');
INSERT INTO `ob_config` VALUES ('58', 'loading_icon', '4', '页面Loading图标设置', '1', '1:图标1\r\n2:图标2\r\n3:图标3\r\n4:图标4\r\n5:图标5\r\n6:图标6\r\n7:图标7', '页面Loading图标支持7种图标切换', '1505377202', '1552106301', '1', '7', '80');
INSERT INTO `ob_config` VALUES ('59', 'sys_file_field', '3', '文件字段配置', '3', '', 'key为模型名，值为文件列名。', '1505799386', '1528923967', '1', '0_article:file_id', '0');
INSERT INTO `ob_config` VALUES ('60', 'sys_picture_field', '3', '图片字段配置', '3', '', 'key为模型名，值为图片列名。', '1506315422', '1528923967', '1', '0_article:cover_id\r\n1_article:img_ids', '0');
INSERT INTO `ob_config` VALUES ('61', 'jwt_key', '1', 'JWT加密KEY', '4', '', '', '1506748805', '1528962834', '1', 'l2V|DSFXXXgfZp{8`;FjzR~6Y1_', '0');
INSERT INTO `ob_config` VALUES ('64', 'is_write_exe_log', '4', '是否写入执行记录', '3', '0:否\r\n1:是', '', '1510544340', '1528923967', '1', '0', '101');
INSERT INTO `ob_config` VALUES ('65', 'admin_allow_ip', '3', '超级管理员登录IP', '3', '', '后台超级管理员登录IP限制，其他角色不受限。', '1510995580', '1528923967', '1', '0:27.22.112.250', '0');
INSERT INTO `ob_config` VALUES ('66', 'pjax_mode', '8', 'PJAX模式', '3', '0:否\r\n1:是', '若为PJAX模式则浏览器不会刷新，若为常规模式则为AJAX+刷新', '1512370397', '1528923967', '1', '1', '120');
INSERT INTO `ob_config` VALUES ('67', 'auth_group_id_agency', '0', '公会代理权限组ID', '5', '', '此数值修改后会发生无法想象的灾难。', '1517047527', '1528934088', '1', '1', '0');
INSERT INTO `ob_config` VALUES ('68', 'auth_group_id_manage', '0', '公会管理权限组ID', '5', '', '此数值修改后会发生无法想象的灾难。', '1517047756', '1528934088', '1', '4', '0');
INSERT INTO `ob_config` VALUES ('69', 'auth_group_id_employee', '0', '公会员工权限组ID', '5', '', '此数值修改后会发生无法想象的灾难。', '1519898879', '1528934088', '1', '5', '0');
INSERT INTO `ob_config` VALUES ('70', 'auth_group_id_finance', '0', '公司财务权限组ID', '5', '', '此数值修改后会发生无法想象的灾难。', '1519899036', '1528934088', '1', '3', '0');
INSERT INTO `ob_config` VALUES ('71', 'auth_group_id_channel', '0', '渠道合作方权限组ID', '5', '', '此数值修改后会发生无法想象的灾难。', '1519899057', '1528934088', '1', '2', '0');
INSERT INTO `ob_config` VALUES ('72', 'web_site_logo', '5', '网站LOGO', '1', '', '', '1519959965', '1552106301', '1', '152', '120');
INSERT INTO `ob_config` VALUES ('73', 'service_qq', '1', '客服QQ', '6', '', '', '1520050246', '1545992762', '1', '3162875', '0');
INSERT INTO `ob_config` VALUES ('74', 'service_business_qq', '1', '商务QQ', '6', '', '', '1520050307', '1528931777', '1', '2411064605', '0');
INSERT INTO `ob_config` VALUES ('75', 'site_icp', '1', '网站备案号', '1', '', '', '1520069355', '1552106301', '1', '鄂ICP备XXXXXX号', '0');
INSERT INTO `ob_config` VALUES ('76', 'site_copyright', '1', '网站版权', '1', '', '', '1520069417', '1552106301', '1', 'Copyright  2018  OneGame 版权所有', '0');
INSERT INTO `ob_config` VALUES ('77', 'site_licence', '5', '网络文化经营许可证', '1', '', '网络文化经营许可证', '1520069756', '1552106301', '1', '210', '121');
INSERT INTO `ob_config` VALUES ('78', 'site_qrcode', '5', '网站浮动二维码', '1', '', '', '1520070135', '1545995394', '1', '210', '122');
INSERT INTO `ob_config` VALUES ('82', 'site_culture', '1', '网络文化经营许可证', '1', '', '', '1525244774', '1552106301', '1', '鄂网文 [2017] XXXXX-XXX号', '0');
INSERT INTO `ob_config` VALUES ('79', 'web_site_name', '1', '网站名称', '1', '', '', '1521601709', '1552106301', '1', 'OneGame', '0');
INSERT INTO `ob_config` VALUES ('80', 'auth_group_id_operation', '0', '公司运营权限组ID', '5', '', '', '1524129592', '1528934088', '1', '6', '0');
INSERT INTO `ob_config` VALUES ('81', 'service_email', '1', '投诉邮箱', '6', '', '', '1524306415', '1545992762', '1', 'service@xxxxx.com', '0');
INSERT INTO `ob_config` VALUES ('83', 'site_telecom', '1', '增值电信业务许可证', '1', '', '', '1525244802', '1552106301', '1', '鄂B2-XXXXXXXX', '0');
INSERT INTO `ob_config` VALUES ('84', 'pay_min_money', '0', '最小支付金额', '5', '', '', '1526104777', '1528934088', '1', '10', '0');

-- ----------------------------
-- Table structure for `ob_driver`
-- ----------------------------
DROP TABLE IF EXISTS `ob_driver`;
CREATE TABLE `ob_driver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `service_name` varchar(40) NOT NULL DEFAULT '' COMMENT '服务标识',
  `driver_name` varchar(20) NOT NULL DEFAULT '' COMMENT '驱动标识',
  `config` text NOT NULL COMMENT '配置',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of ob_driver
-- ----------------------------
INSERT INTO `ob_driver` VALUES ('1', 'Webgame', 'Lhsc', 'a:2:{s:9:\"login_key\";s:32:\"3NZ58pBaRq4ThlVCFcYtbKD9Qwz6AUdI\";s:7:\"pay_key\";s:32:\"Lx59TswKGFdopBX73anr6NfJQH8lVMPC\";}', '1', '1525074638', '1525074638');
INSERT INTO `ob_driver` VALUES ('2', 'Pay', 'Alipay', 'a:7:{s:14:\"alipay_account\";s:8:\"xxxxxxxx\";s:14:\"alipay_partner\";s:12:\"xxxxxxxxxxxx\";s:10:\"alipay_key\";s:0:\"\";s:12:\"alipay_appid\";s:9:\"xxxxxxxxx\";s:20:\"alipay_rsaPrivateKey\";s:0:\"\";s:25:\"alipay_alipayrsaPublicKey\";s:0:\"\";s:4:\"icon\";s:8:\"pay_ico2\";}', '1', '1546089520', '1546090424');
INSERT INTO `ob_driver` VALUES ('3', 'Pay', 'Wxpay', 'a:5:{s:5:\"appid\";s:12:\"xxxxxxxxxxxx\";s:9:\"appsecret\";s:11:\"xxxxxxxxxxx\";s:9:\"partnerid\";s:12:\"xxxxxxxxxxxx\";s:10:\"partnerkey\";s:10:\"xxxxxxxxxx\";s:4:\"icon\";s:8:\"pay_ico1\";}', '1', '1546089540', '1546090435');
INSERT INTO `ob_driver` VALUES ('4', 'Pay', 'Yeepay', 'a:3:{s:12:\"yeepay_merid\";s:11:\"xxxxxxxxxxx\";s:10:\"yeepay_key\";s:10:\"xxxxxxxxxx\";s:4:\"icon\";s:8:\"pay_ico3\";}', '1', '1546089550', '1546090440');

-- ----------------------------
-- Table structure for `ob_exe_log`
-- ----------------------------
DROP TABLE IF EXISTS `ob_exe_log`;
CREATE TABLE `ob_exe_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `ip` char(50) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `exe_url` varchar(2000) NOT NULL DEFAULT '' COMMENT '执行URL',
  `exe_time` float(10,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '执行时间 单位 秒',
  `exe_memory` char(20) NOT NULL DEFAULT '' COMMENT '内存占用KB',
  `exe_os` char(30) NOT NULL DEFAULT '' COMMENT '操作系统',
  `source_url` varchar(2000) NOT NULL DEFAULT '' COMMENT '来源URL',
  `session_id` char(32) NOT NULL DEFAULT '' COMMENT 'session_id',
  `browser` char(30) NOT NULL DEFAULT '' COMMENT '浏览器',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `login_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行者ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型  0 ： 应用范围 ， 1：API 范围 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17222 DEFAULT CHARSET=utf8 COMMENT='执行记录表';

-- ----------------------------
-- Records of ob_exe_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_file`
-- ----------------------------
DROP TABLE IF EXISTS `ob_file`;
CREATE TABLE `ob_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '保存名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文件表';

-- ----------------------------
-- Records of ob_file
-- ----------------------------
INSERT INTO `ob_file` VALUES ('4', 'b7e908de1642224e0c55db6d96f26187.xlsx', '20180510/b7e908de1642224e0c55db6d96f26187.xlsx', 'upload/file/20180510/b7e908de1642224e0c55db6d96f26187.xlsx', 'dedfe49b5d2cbcc990c47c2b1175f7d37389dbf8', '1525948151', '1525948151', '1');

-- ----------------------------
-- Table structure for `ob_hook`
-- ----------------------------
DROP TABLE IF EXISTS `ob_hook`;
CREATE TABLE `ob_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `describe` varchar(255) NOT NULL COMMENT '描述',
  `addon_list` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='钩子表';

-- ----------------------------
-- Records of ob_hook
-- ----------------------------
INSERT INTO `ob_hook` VALUES ('36', 'File', '文件上传钩子', 'File', '1', '0', '0');
INSERT INTO `ob_hook` VALUES ('37', 'Icon', '图标选择钩子', 'Icon', '1', '0', '0');
INSERT INTO `ob_hook` VALUES ('38', 'ArticleEditor', '富文本编辑器', 'Editor', '1', '0', '0');

-- ----------------------------
-- Table structure for `ob_ip`
-- ----------------------------
DROP TABLE IF EXISTS `ob_ip`;
CREATE TABLE `ob_ip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `ip` char(20) NOT NULL DEFAULT '' COMMENT 'IP',
  `area` varchar(100) NOT NULL DEFAULT '' COMMENT '地区',
  `isp` varchar(50) NOT NULL DEFAULT '' COMMENT '网络提供商',
  PRIMARY KEY (`id`),
  KEY `index_ip` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=14572 DEFAULT CHARSET=utf8 COMMENT='IP地址表';

-- ----------------------------
-- Records of ob_ip
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_member`
-- ----------------------------
DROP TABLE IF EXISTS `ob_member`;
CREATE TABLE `ob_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `username` char(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(64) NOT NULL DEFAULT '' COMMENT '密码',
  `password_version` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '密码版本  0:旧版迁移密码  1:新版注册密码',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `leader_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '上级会员ID',
  `is_share_member` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否共享会员',
  `is_inside` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为后台使用者',
  `ip` char(30) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `is_test` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为测试账号，可以提前进入区服',
  `qqopenid` char(50) NOT NULL DEFAULT '' COMMENT 'qqopenid',
  PRIMARY KEY (`id`),
  KEY `index_username` (`username`),
  KEY `index_qqopenid` (`qqopenid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of ob_member
-- ----------------------------
INSERT INTO `ob_member` VALUES ('1', 'admin', 'admin', '7f41a2ad81a36b60bf3d59ad4e247435', '1', '3162875@qq.com', '18555550710', '1552106846', '0', '1', '1', '0', '1', '127.0.0.1', '0', '');

-- ----------------------------
-- Table structure for `ob_member_extend`
-- ----------------------------
DROP TABLE IF EXISTS `ob_member_extend`;
CREATE TABLE `ob_member_extend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `real_name` char(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `identity_card` char(30) NOT NULL DEFAULT '' COMMENT '身份证',
  `anti_addiction` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '防沉迷认证 0 : 不满18， 1：满18',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员表扩展信息表';

-- ----------------------------
-- Records of ob_member_extend
-- ----------------------------
INSERT INTO `ob_member_extend` VALUES ('1', '0', '', '', '0');

-- ----------------------------
-- Table structure for `ob_menu`
-- ----------------------------
DROP TABLE IF EXISTS `ob_menu`;
CREATE TABLE `ob_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `module` char(20) NOT NULL DEFAULT '' COMMENT '模块',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `is_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `icon` char(30) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of ob_menu
-- ----------------------------
INSERT INTO `ob_menu` VALUES ('1', '系统首页', '0', '0', 'admin', 'index/index', '0', 'fa-home', '1', '1521186550', '0');
INSERT INTO `ob_menu` VALUES ('16', '会员管理', '0', '3', 'admin', 'member/index', '0', 'fa-users', '1', '1521173735', '0');
INSERT INTO `ob_menu` VALUES ('17', '会员列表', '16', '1', 'admin', 'member/memberlist', '0', 'fa-list', '1', '1495272875', '0');
INSERT INTO `ob_menu` VALUES ('18', '会员添加', '16', '0', 'admin', 'member/memberadd', '0', 'fa-user-plus', '1', '1491837324', '0');
INSERT INTO `ob_menu` VALUES ('27', '权限管理', '16', '0', 'admin', 'auth/grouplist', '0', 'fa-key', '1', '1492000451', '0');
INSERT INTO `ob_menu` VALUES ('32', '权限组编辑', '27', '0', 'admin', 'auth/groupedit', '1', '', '1', '1492002620', '0');
INSERT INTO `ob_menu` VALUES ('34', '授权', '27', '0', 'admin', 'auth_manager/group', '1', '', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('35', '菜单授权', '27', '0', 'admin', 'auth/menuauth', '1', '', '1', '1492095653', '0');
INSERT INTO `ob_menu` VALUES ('36', '会员授权', '27', '0', 'admin', 'auth_manager/memberaccess', '1', '', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('68', '系统管理', '0', '4', 'admin', 'config/group', '0', 'fa-wrench', '1', '1521173735', '0');
INSERT INTO `ob_menu` VALUES ('69', '系统设置', '68', '1', 'admin', 'config/setting', '0', 'fa-cogs', '1', '1491748512', '0');
INSERT INTO `ob_menu` VALUES ('70', '配置管理', '68', '4', 'admin', 'config/index', '0', 'fa-cog', '1', '1491668183', '0');
INSERT INTO `ob_menu` VALUES ('71', '配置编辑', '70', '0', 'admin', 'config/configedit', '1', '', '1', '1491674180', '0');
INSERT INTO `ob_menu` VALUES ('72', '配置删除', '70', '0', 'admin', 'config/configDel', '1', '', '1', '1491674201', '0');
INSERT INTO `ob_menu` VALUES ('73', '配置添加', '70', '0', 'admin', 'config/configadd', '0', 'fa-plus', '1', '1491666947', '0');
INSERT INTO `ob_menu` VALUES ('75', '菜单管理', '68', '5', 'admin', 'menu/index', '0', 'fa-th-large', '1', '1491318724', '0');
INSERT INTO `ob_menu` VALUES ('98', '菜单编辑', '75', '0', 'admin', 'menu/menuedit', '1', '', '1', '1512459021', '0');
INSERT INTO `ob_menu` VALUES ('108', '修改密码', '17', '0', 'admin', 'user/update_password', '1', '', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('109', '修改昵称', '17', '0', 'admin', 'user/update_nickname', '1', '', '1', '1491578211', '0');
INSERT INTO `ob_menu` VALUES ('124', '菜单列表', '75', '0', 'admin', 'menu/menulist', '0', 'fa-list', '1', '1491318271', '0');
INSERT INTO `ob_menu` VALUES ('125', '菜单添加', '75', '0', 'admin', 'menu/menuadd', '0', 'fa-plus', '1', '1491318307', '0');
INSERT INTO `ob_menu` VALUES ('126', '配置列表', '70', '0', 'admin', 'config/configlist', '0', 'fa-list', '1', '1491666890', '1491666890');
INSERT INTO `ob_menu` VALUES ('127', '菜单删除', '75', '0', 'admin', 'menu/menuDel', '1', '', '1', '1491674128', '1491674128');
INSERT INTO `ob_menu` VALUES ('128', '权限组添加', '27', '0', 'admin', 'auth/groupadd', '1', '', '1', '1492002635', '1492002635');
INSERT INTO `ob_menu` VALUES ('134', '授权', '17', '0', 'admin', 'member/memberauth', '1', '', '1', '1492238568', '1492101426');
INSERT INTO `ob_menu` VALUES ('135', '回收站', '68', '0', 'admin', 'trash/trashlist', '0', ' fa-recycle', '1', '1492320214', '1492311462');
INSERT INTO `ob_menu` VALUES ('136', '回收站数据', '135', '0', 'admin', 'trash/trashdatalist', '1', 'fa-database', '1', '1492319477', '1492319392');
INSERT INTO `ob_menu` VALUES ('140', '服务管理', '68', '0', 'admin', 'service/servicelist', '0', 'fa-server', '1', '1492359063', '1492352972');
INSERT INTO `ob_menu` VALUES ('141', '插件管理', '68', '0', 'admin', 'addon/index', '0', 'fa-puzzle-piece', '1', '1492428072', '1492427605');
INSERT INTO `ob_menu` VALUES ('142', '钩子列表', '141', '0', 'admin', 'addon/hooklist', '0', 'fa-anchor', '1', '1492427665', '1492427665');
INSERT INTO `ob_menu` VALUES ('143', '插件列表', '141', '0', 'admin', 'addon/addonlist', '0', 'fa-list', '1', '1492428116', '1492427838');
INSERT INTO `ob_menu` VALUES ('144', '文章管理', '0', '1', 'admin', 'article/index', '0', 'fa-edit', '1', '1521173745', '1492480187');
INSERT INTO `ob_menu` VALUES ('145', '文章列表', '144', '0', 'admin', 'article/articlelist', '0', 'fa-list', '1', '1492480245', '1492480245');
INSERT INTO `ob_menu` VALUES ('146', '文章分类', '144', '0', 'admin', 'article/articlecategorylist', '0', 'fa-list', '1', '1492480359', '1492480342');
INSERT INTO `ob_menu` VALUES ('147', '文章分类编辑', '146', '0', 'admin', 'article/articlecategoryedit', '1', '', '1', '1492485294', '1492485294');
INSERT INTO `ob_menu` VALUES ('148', '分类添加', '144', '0', 'admin', 'article/articlecategoryadd', '0', 'fa-plus', '1', '1492486590', '1492486576');
INSERT INTO `ob_menu` VALUES ('149', '文章添加', '144', '0', 'admin', 'article/articleadd', '0', 'fa-plus', '1', '1492518453', '1492518453');
INSERT INTO `ob_menu` VALUES ('150', '文章编辑', '145', '0', 'admin', 'article/articleedit', '1', '', '1', '1492879589', '1492879589');
INSERT INTO `ob_menu` VALUES ('151', '插件安装', '143', '0', 'admin', 'addon/addoninstall', '1', '', '1', '1492879763', '1492879763');
INSERT INTO `ob_menu` VALUES ('152', '插件卸载', '143', '0', 'admin', 'addon/addonuninstall', '1', '', '1', '1492879789', '1492879789');
INSERT INTO `ob_menu` VALUES ('153', '文章删除', '145', '0', 'admin', 'article/articledel', '1', '', '1', '1492879960', '1492879960');
INSERT INTO `ob_menu` VALUES ('154', '文章分类删除', '146', '0', 'admin', 'article/articlecategorydel', '1', '', '1', '1492879995', '1492879995');
INSERT INTO `ob_menu` VALUES ('156', '驱动安装', '140', '0', 'admin', 'service/driverinstall', '1', '', '1', '1502267009', '1502267009');
INSERT INTO `ob_menu` VALUES ('157', '接口管理', '0', '1', 'admin', 'api/index', '0', 'fa fa-book', '1', '1521185699', '1504000434');
INSERT INTO `ob_menu` VALUES ('158', '分组管理', '157', '0', 'admin', 'api/apigrouplist', '0', 'fa fa-fw fa-th-list', '1', '1504000977', '1504000723');
INSERT INTO `ob_menu` VALUES ('159', '分组添加', '157', '0', 'admin', 'api/apigroupadd', '0', 'fa fa-fw fa-plus', '1', '1504004646', '1504004646');
INSERT INTO `ob_menu` VALUES ('160', '分组编辑', '157', '0', 'admin', 'api/apigroupedit', '1', '', '1', '1504004710', '1504004710');
INSERT INTO `ob_menu` VALUES ('161', '分组删除', '157', '0', 'admin', 'api/apigroupdel', '1', '', '1', '1504004732', '1504004732');
INSERT INTO `ob_menu` VALUES ('162', '接口列表', '157', '0', 'admin', 'api/apilist', '0', 'fa fa-fw fa-th-list', '1', '1504172326', '1504172326');
INSERT INTO `ob_menu` VALUES ('163', '接口添加', '157', '0', 'admin', 'api/apiadd', '0', 'fa fa-fw fa-plus', '1', '1504172352', '1504172352');
INSERT INTO `ob_menu` VALUES ('164', '接口编辑', '157', '0', 'admin', 'api/apiedit', '1', '', '1', '1504172414', '1504172414');
INSERT INTO `ob_menu` VALUES ('165', '接口删除', '157', '0', 'admin', 'api/apidel', '1', '', '1', '1504172435', '1504172435');
INSERT INTO `ob_menu` VALUES ('166', '优化维护', '0', '2', 'admin', 'maintain/index', '0', 'fa-legal', '1', '1521173747', '1505387256');
INSERT INTO `ob_menu` VALUES ('167', 'SEO管理', '166', '0', 'admin', 'seo/seolist', '0', 'fa-list', '1', '1506309608', '1505387303');
INSERT INTO `ob_menu` VALUES ('168', '数据库', '166', '0', 'admin', 'maintain/database', '0', 'fa-database', '1', '1505539670', '1505539394');
INSERT INTO `ob_menu` VALUES ('169', '数据备份', '168', '0', 'admin', 'database/databackup', '0', 'fa-download', '1', '1506309900', '1505539428');
INSERT INTO `ob_menu` VALUES ('170', '数据还原', '168', '0', 'admin', 'database/datarestore', '0', 'fa-exchange', '1', '1506309911', '1505539492');
INSERT INTO `ob_menu` VALUES ('171', '文件清理', '166', '0', 'admin', 'fileclean/cleanlist', '0', 'fa-file', '1', '1506310152', '1505788517');
INSERT INTO `ob_menu` VALUES ('174', '行为日志', '166', '0', 'admin', 'log/loglist', '0', 'fa-street-view', '1', '1507201516', '1507200836');
INSERT INTO `ob_menu` VALUES ('176', '执行记录', '166', '0', 'admin', 'exelog/index', '0', 'fa-list-alt', '1', '1509433351', '1509433351');
INSERT INTO `ob_menu` VALUES ('177', '全局范围', '176', '0', 'admin', 'exelog/applist', '0', 'fa-tags', '1', '1509433570', '1509433570');
INSERT INTO `ob_menu` VALUES ('178', '接口范围', '176', '0', 'admin', 'exelog/apilist', '0', 'fa-tag', '1', '1509433591', '1509433591');
INSERT INTO `ob_menu` VALUES ('198', '统计分析', '0', '2', 'admin', 'statistic/index', '1', 'fa-connectdevelop', '1', '1521185714', '1512638014');
INSERT INTO `ob_menu` VALUES ('199', '权限等级', '198', '0', 'admin', 'statistic/membertree', '1', 'fa-users', '1', '1512638868', '1512638868');
INSERT INTO `ob_menu` VALUES ('200', '浏览器统计', '198', '0', 'admin', 'statistic/performerfacility', '1', 'fa-edge', '1', '1512727672', '1512727672');
INSERT INTO `ob_menu` VALUES ('201', '执行速度', '198', '0', 'admin', 'statistic/exespeed', '1', 'fa-fighter-jet', '1', '1512787226', '1512787226');
INSERT INTO `ob_menu` VALUES ('202', '会员增长', '198', '0', 'admin', 'statistic/membergrowth', '1', 'fa-line-chart', '1', '1512801997', '1512801997');
INSERT INTO `ob_menu` VALUES ('203', '游戏管理', '0', '3', 'admin', 'game/index', '0', 'fa-gamepad', '1', '1521173748', '1516262344');
INSERT INTO `ob_menu` VALUES ('204', '分类列表', '203', '10', 'admin', 'game/categorylist', '0', 'fa-th', '1', '1516761844', '1516262436');
INSERT INTO `ob_menu` VALUES ('205', '游戏列表', '203', '9', 'admin', 'game/gamelist', '0', 'fa-th', '1', '1516761850', '1516262480');
INSERT INTO `ob_menu` VALUES ('206', '礼包列表', '203', '5', 'admin', 'gift/giftlist', '0', 'fa-th', '1', '1516783983', '1516262574');
INSERT INTO `ob_menu` VALUES ('207', '区服列表', '203', '8', 'admin', 'game/serverlist', '0', 'fa-server', '1', '1516764827', '1516764827');
INSERT INTO `ob_menu` VALUES ('208', '公会管理', '0', '4', 'admin', 'conference/index', '0', 'fa-group', '1', '1521173750', '1517048393');
INSERT INTO `ob_menu` VALUES ('209', '公会列表', '208', '0', 'admin', 'conference/conferencelist', '0', 'fa-th-list', '1', '1517048475', '1517048475');
INSERT INTO `ob_menu` VALUES ('210', '公会添加', '209', '0', 'admin', 'conference/conferenceadd', '1', 'fa-plus', '1', '1519802361', '1517048539');
INSERT INTO `ob_menu` VALUES ('211', '公会编辑', '209', '0', 'admin', 'conference/conferenceedit', '1', 'fa-edit', '1', '1517048577', '1517048577');
INSERT INTO `ob_menu` VALUES ('212', '员工列表', '208', '0', 'admin', 'conference/employeelist', '0', 'fa-th', '1', '1519802269', '1517049598');
INSERT INTO `ob_menu` VALUES ('213', '员工添加', '212', '0', 'admin', 'conference/employeeadd', '1', 'fa-plus', '1', '1519802284', '1517049779');
INSERT INTO `ob_menu` VALUES ('214', '员工编辑', '212', '0', 'admin', 'conference/employeeedit', '1', 'fa-edit', '1', '1519802308', '1517049810');
INSERT INTO `ob_menu` VALUES ('215', '链接管理', '208', '0', 'admin', 'conference/linklist', '0', 'fa-link', '1', '1519806137', '1519806137');
INSERT INTO `ob_menu` VALUES ('216', '链接新增', '215', '0', 'admin', 'conference/linkadd', '1', 'fa-plus', '1', '1519806232', '1519806232');
INSERT INTO `ob_menu` VALUES ('217', '绑定管理', '208', '0', 'admin', 'conference/bindlist', '0', 'fa-object-group', '1', '1519888964', '1519888964');
INSERT INTO `ob_menu` VALUES ('218', '绑定新增', '217', '0', 'admin', 'conference/bindadd', '1', 'fa-plus', '1', '1519889101', '1519889101');
INSERT INTO `ob_menu` VALUES ('219', '友情链接', '68', '0', 'admin', 'blogroll/index', '0', 'fa-joomla', '1', '1519960583', '1519960524');
INSERT INTO `ob_menu` VALUES ('220', '链接列表', '219', '0', 'admin', 'blogroll/blogrolllist', '0', 'fa-th-list', '1', '1519960612', '1519960612');
INSERT INTO `ob_menu` VALUES ('221', '链接新增', '219', '0', 'admin', 'blogroll/blogrolladd', '0', 'fa-plus', '1', '1519960641', '1519960641');
INSERT INTO `ob_menu` VALUES ('222', '链接编辑', '219', '0', 'admin', 'blogroll/blogrolledit', '1', 'fa-pencil-square-o', '1', '1519960660', '1519960660');
INSERT INTO `ob_menu` VALUES ('223', '链接删除', '219', '0', 'admin', 'blogroll/blogrolldel', '1', 'fa-minus', '1', '1519960676', '1519960676');
INSERT INTO `ob_menu` VALUES ('224', '轮播管理', '68', '0', 'admin', 'slider/index', '0', 'fa-picture-o', '1', '1519977798', '1519977798');
INSERT INTO `ob_menu` VALUES ('225', '轮播列表', '224', '0', 'admin', 'slider/sliderlist', '0', 'fa-th-large', '1', '1519977840', '1519977840');
INSERT INTO `ob_menu` VALUES ('226', '轮播新增', '224', '0', 'admin', 'slider/slideradd', '0', 'fa-plus', '1', '1519977870', '1519977870');
INSERT INTO `ob_menu` VALUES ('227', '轮播编辑', '224', '0', 'admin', 'slider/slideredit', '1', 'fa-pencil-square-o', '1', '1519978017', '1519977899');
INSERT INTO `ob_menu` VALUES ('228', '轮播删除', '224', '0', 'admin', 'slider/sliderdel', '1', 'fa-minus', '1', '1519978013', '1519978013');
INSERT INTO `ob_menu` VALUES ('229', '游戏统计', '0', '6', 'admin', 'analyze/index', '0', 'fa-line-chart', '1', '1525838042', '1525838030');
INSERT INTO `ob_menu` VALUES ('230', '注册记录', '229', '0', 'admin', 'analyze/registerlist', '0', 'fa-user-plus', '1', '1525838089', '1525838089');
INSERT INTO `ob_menu` VALUES ('231', 'AJAX获取员工', '215', '0', 'admin', 'conference/getemployeeoptions', '1', '', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('232', '每日汇总', '229', '0', 'admin', 'analyze/everydaylist', '0', 'fa-th-list', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('233', '游戏汇总', '229', '0', 'admin', 'analyze/gamelist', '0', 'fa-gamepad', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('234', '员工汇总', '229', '0', 'admin', 'analyze/employeelist', '0', 'fa-users', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('235', '账目管理', '0', '8', 'admin', 'accounts/index', '0', 'fa-cny', '1', '1525923293', '0');
INSERT INTO `ob_menu` VALUES ('236', '充值订单', '235', '1', 'admin', 'accounts/orderlist', '0', 'fa-calendar-check-o', '1', '1528429303', '0');
INSERT INTO `ob_menu` VALUES ('237', 'AJAX获取服务器', '230', '0', 'admin', 'analyze/getserveroptions', '1', '', '1', '1525940797', '1525940797');
INSERT INTO `ob_menu` VALUES ('238', '文章状态设置', '145', '0', 'admin', 'article/setstatus', '1', '', '1', '1525946604', '1525946604');
INSERT INTO `ob_menu` VALUES ('239', '礼包编辑', '206', '0', 'admin', 'gift/giftedit', '1', 'fa-pencil-square-o', '1', '1525946827', '1525946827');
INSERT INTO `ob_menu` VALUES ('240', '礼包删除', '206', '0', 'admin', 'gift/giftdel', '1', '', '1', '1525946853', '1525946853');
INSERT INTO `ob_menu` VALUES ('241', '库存列表', '206', '0', 'admin', 'gift/inventorylist', '1', '', '1', '1525946882', '1525946882');
INSERT INTO `ob_menu` VALUES ('243', '礼包KEY新增', '206', '0', 'admin', 'gift/giftaddkey', '1', '', '1', '1525947012', '1525947012');
INSERT INTO `ob_menu` VALUES ('244', '礼包KEY批量导入', '206', '0', 'admin', 'gift/giftimportkey', '1', '', '1', '1525947042', '1525947042');
INSERT INTO `ob_menu` VALUES ('245', '绑定审核', '217', '0', 'admin', 'conference/bindcheck', '1', '', '1', '1525947206', '1525947206');
INSERT INTO `ob_menu` VALUES ('246', '区服编辑', '207', '0', 'admin', 'game/serveredit', '1', '', '1', '1525947442', '1525947442');
INSERT INTO `ob_menu` VALUES ('247', '区服删除', '207', '0', 'admin', 'game/serverdel', '1', '', '1', '1525947458', '1525947458');
INSERT INTO `ob_menu` VALUES ('248', '游戏编辑', '205', '0', 'admin', 'game/gameedit', '1', '', '1', '1525947502', '1525947502');
INSERT INTO `ob_menu` VALUES ('249', '游戏删除', '205', '0', 'admin', 'game/gamedel', '1', '', '1', '1525947524', '1525947524');
INSERT INTO `ob_menu` VALUES ('250', '游戏分类编辑', '204', '0', 'admin', 'game/categoryedit', '1', '', '1', '1525947561', '1525947561');
INSERT INTO `ob_menu` VALUES ('251', '游戏分类删除', '204', '0', 'admin', 'game/categorydel', '1', '', '1', '1525947577', '1525947577');
INSERT INTO `ob_menu` VALUES ('252', '公会汇总', '229', '0', 'admin', 'analyze/conferencelist', '0', 'fa-pie-chart', '1', '1526002093', '1526002093');
INSERT INTO `ob_menu` VALUES ('253', '设置测试账号', '17', '0', 'admin', 'member/settestmember', '1', '', '1', '1526009660', '1526009660');
INSERT INTO `ob_menu` VALUES ('254', '重置密码', '17', '0', 'admin', 'member/resetpassword', '1', '', '1', '1526011416', '1526011416');
INSERT INTO `ob_menu` VALUES ('255', '订单转移', '17', '0', 'admin', 'member/shiftorder', '1', '', '1', '1526021171', '1526021171');
INSERT INTO `ob_menu` VALUES ('256', '批量审核', '217', '0', 'admin', 'conference/bindallCheck', '1', '', '1', '1526022823', '1526022823');
INSERT INTO `ob_menu` VALUES ('257', '充值排行', '235', '2', 'admin', 'accounts/paytoppinglist', '0', 'fa-sort-amount-desc', '1', '1528429320', '1526026051');
INSERT INTO `ob_menu` VALUES ('258', '补单', '235', '0', 'admin', 'accounts/replenishorder', '1', '', '1', '1526028084', '1526028084');
INSERT INTO `ob_menu` VALUES ('259', '元宝池-游戏', '209', '0', 'admin', 'conference/gamegold', '1', '', '1', '1526035624', '1526035624');
INSERT INTO `ob_menu` VALUES ('260', '元宝池-区服', '209', '0', 'admin', 'conference/servergold', '1', '', '1', '1526035655', '1526035655');
INSERT INTO `ob_menu` VALUES ('261', '元宝池-调额', '209', '0', 'admin', 'conference/deductgold', '1', '', '1', '1526035700', '1526035700');
INSERT INTO `ob_menu` VALUES ('262', '区服明细', '233', '0', 'admin', 'analyze/serverlist', '1', 'fa-facebook-f', '1', '1529049506', '1529049506');
INSERT INTO `ob_menu` VALUES ('263', '导出公会', '208', '0', 'admin', 'conference/exportconferencelist', '1', 'fa-download', '1', '1529141811', '1529141811');
INSERT INTO `ob_menu` VALUES ('264', '公会结算', '235', '20', 'admin', 'accounts/conferenceaccounts', '0', 'fa-credit-card', '1', '1529567976', '1529567976');
INSERT INTO `ob_menu` VALUES ('265', '公会结算导出', '264', '0', 'admin', 'accounts/conferenceaccountsexport', '1', 'fa-download', '1', '1530689307', '1530689307');

-- ----------------------------
-- Table structure for `ob_picture`
-- ----------------------------
DROP TABLE IF EXISTS `ob_picture`;
CREATE TABLE `ob_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图片名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='图片表';

-- ----------------------------
-- Records of ob_picture
-- ----------------------------
INSERT INTO `ob_picture` VALUES ('149', 'dfaed8968ba4effb24ae05e8447625d3.jpg', '20181228/dfaed8968ba4effb24ae05e8447625d3.jpg', 'upload/picture/20181228/dfaed8968ba4effb24ae05e8447625d3.jpg', 'e4b5514aae0b95daa076f7bf50d1dee084485042', '1545994259', '1545994259', '1');
INSERT INTO `ob_picture` VALUES ('150', 'c520777230d82f049d07b3b6478129d9.jpg', '20181228/c520777230d82f049d07b3b6478129d9.jpg', '', '3f63e44f931c3c4a554a84b0e8e85cdad39b46b4', '1545994527', '0', '1');
INSERT INTO `ob_picture` VALUES ('151', '2bb5326d02bf24460b149bd172e0f224.jpg', '20181228/2bb5326d02bf24460b149bd172e0f224.jpg', '', '53d874b3ac0bf17872993d9da0d39ecaba62c302', '1545994550', '0', '1');
INSERT INTO `ob_picture` VALUES ('152', '1c0ab1bc819df356ccb75ec11a5cb231.png', '20181228/1c0ab1bc819df356ccb75ec11a5cb231.png', '', '66930055b758c6d170ffc4ac0fe15ff7da95bab8', '1545995160', '0', '1');
INSERT INTO `ob_picture` VALUES ('153', '9048c62cb7d36419d749469de1cba8c3.png', '20181228/9048c62cb7d36419d749469de1cba8c3.png', '', '6a11cb4fed0c3927b691a98efe5d084876897bca', '1545995391', '0', '1');
INSERT INTO `ob_picture` VALUES ('154', '11fcfd38c2897150448af88f20d74907.jpg', '20181228/11fcfd38c2897150448af88f20d74907.jpg', '', 'e21de7c19e6f6334dc350ae25deb4e8e2e7835cf', '1545995761', '0', '1');
INSERT INTO `ob_picture` VALUES ('155', 'e51a8730d3eae7261dc4a7c14e9186ab.jpg', '20181228/e51a8730d3eae7261dc4a7c14e9186ab.jpg', '', '30eb50b01c31511e89c8e27b1461dd75a4dc9628', '1545995871', '0', '1');
INSERT INTO `ob_picture` VALUES ('156', '2544d617ea3aac420d24187933aa1b61.png', '20181228/2544d617ea3aac420d24187933aa1b61.png', '', '4b49a1d3876c5d019db0aebed16a3cc7c10b95ea', '1545996065', '0', '1');
INSERT INTO `ob_picture` VALUES ('157', '46a2718b8e7a19a3fe853c530b42c929.png', '20181228/46a2718b8e7a19a3fe853c530b42c929.png', '', '70df12872a90ce95b88e020d468cb09a56399161', '1545996204', '0', '1');
INSERT INTO `ob_picture` VALUES ('158', '75dae23da69b7e23eadff646eab274d9.jpg', '20181228/75dae23da69b7e23eadff646eab274d9.jpg', '', '27deb19980b24f5cc95d5e2db2ca7dec0afacede', '1545996408', '0', '1');
INSERT INTO `ob_picture` VALUES ('159', '3bfcdab51c5eb1423bd997e703df9030.jpg', '20181228/3bfcdab51c5eb1423bd997e703df9030.jpg', '', '98f4a14f714647c2324bf6835715934302d80f08', '1545996410', '0', '1');
INSERT INTO `ob_picture` VALUES ('160', '503e4ad436a0e61bb736fd55b430a104.jpg', '20181228/503e4ad436a0e61bb736fd55b430a104.jpg', '', 'e413c23c83db156353261d820117f83b35e1be1a', '1545996804', '0', '1');
INSERT INTO `ob_picture` VALUES ('161', '1c4feeead546471189d99256089c49e2.png', '20181228/1c4feeead546471189d99256089c49e2.png', '', '4731c597d7ef699030e061f756bb745773c54f8f', '1545996807', '0', '1');
INSERT INTO `ob_picture` VALUES ('162', '94fbe35ee92c1efd2b6a01fdaf55f0d4.png', '20181228/94fbe35ee92c1efd2b6a01fdaf55f0d4.png', '', 'f3214fcccfbe56e41b2ec6572a150b077fbc6302', '1545996990', '0', '1');
INSERT INTO `ob_picture` VALUES ('163', '394fe32a96928e4ec00f130b4dbbc28c.png', '20181228/394fe32a96928e4ec00f130b4dbbc28c.png', '', '6883860ce7b9603f1d59991c52ac9cd078444c02', '1545996993', '0', '1');
INSERT INTO `ob_picture` VALUES ('164', '83c28bd2c8be08d17a03ea255c59d2b8.png', '20181228/83c28bd2c8be08d17a03ea255c59d2b8.png', '', '8a8649e41aa805d82a7445ed36fc6dccf92136dd', '1545997139', '0', '1');
INSERT INTO `ob_picture` VALUES ('165', '14a079c7e96a426accb4e533db3027d1.png', '20181228/14a079c7e96a426accb4e533db3027d1.png', '', '3a66e6f80af310336eeef22eb36177b98bca25be', '1545997143', '0', '1');
INSERT INTO `ob_picture` VALUES ('166', '0813281eee5fbb700819da6190e17eac.png', '20181228/0813281eee5fbb700819da6190e17eac.png', '', 'e4dfbff8e2450f376565a914f57ca4e54a0eb433', '1545997146', '0', '1');
INSERT INTO `ob_picture` VALUES ('167', '9ef1d82441ca11444cdcda94387b7e2b.png', '20181228/9ef1d82441ca11444cdcda94387b7e2b.png', '', '97702e2d393ef8a38bcf54f9084be4620999f590', '1545997277', '0', '1');
INSERT INTO `ob_picture` VALUES ('168', 'bb0c30b88ea2165ce51957fc56c0adc4.png', '20181228/bb0c30b88ea2165ce51957fc56c0adc4.png', '', '98af41f41082e38d94687d50bf633dfdf37945a4', '1545997277', '0', '1');
INSERT INTO `ob_picture` VALUES ('169', '67d1e6127e6c282407fe23b719c261cc.png', '20181228/67d1e6127e6c282407fe23b719c261cc.png', '', 'db4e6fd726c9bf5b27b7708912770540db2f7a18', '1545997277', '0', '1');
INSERT INTO `ob_picture` VALUES ('170', '430e8f8e755d33eab7824218ff1a51a7.jpg', '20181228/430e8f8e755d33eab7824218ff1a51a7.jpg', '', 'e8477777a0486fd7476631422418444acac12382', '1545997284', '0', '1');
INSERT INTO `ob_picture` VALUES ('171', 'a53e0944721d422c7fa4975aa05d5084.jpg', '20181228/a53e0944721d422c7fa4975aa05d5084.jpg', '', '0da17235a09b9542840cdd9249af9915e045cf4d', '1545997284', '0', '1');
INSERT INTO `ob_picture` VALUES ('172', 'c4f86407aa953d02de103ed1f21f4ef5.jpg', '20181228/c4f86407aa953d02de103ed1f21f4ef5.jpg', '', '2dfe5d78fda6b0245a918969f3053b1672e1ea89', '1545997284', '0', '1');
INSERT INTO `ob_picture` VALUES ('173', '80e48f96e1b8c9ebc0288587d4cc1406.jpg', '20181228/80e48f96e1b8c9ebc0288587d4cc1406.jpg', '', '3929de0d4bfe961a2f0693aa939841d12bfd21a2', '1546002159', '0', '1');
INSERT INTO `ob_picture` VALUES ('174', '3274de4ce97f6c2f3fcbcf6956ede4c6.jpg', '20181229/3274de4ce97f6c2f3fcbcf6956ede4c6.jpg', '', 'e6cc9ac0e97be041efc02d919d91e762c92c7d0d', '1546060782', '0', '1');
INSERT INTO `ob_picture` VALUES ('175', 'af269c80eda85550c57dc8a7da3afdd4.jpg', '20181229/af269c80eda85550c57dc8a7da3afdd4.jpg', '', 'a7e9f7be5e66d17642c0a87728b6b2aa8039c6fc', '1546072894', '0', '1');
INSERT INTO `ob_picture` VALUES ('176', '4592ca6c27d1c8c5463f06d51a939811.jpg', '20181229/4592ca6c27d1c8c5463f06d51a939811.jpg', '', 'f2866c661a9bd0292b13a04e31047824fae0fcbc', '1546072988', '0', '1');
INSERT INTO `ob_picture` VALUES ('177', '056e2c16c48f0ffe07d8503dd3228e44.jpg', '20181229/056e2c16c48f0ffe07d8503dd3228e44.jpg', '', '2442de8710d15d8c4fbc3e4f507601db0a7309cc', '1546072988', '0', '1');
INSERT INTO `ob_picture` VALUES ('178', '195acace12f3b6264f30df3ebdc41b72.png', '20181229/195acace12f3b6264f30df3ebdc41b72.png', '', 'c306172406725838e7f8717cf26b1aa9a672d9b6', '1546073040', '0', '1');
INSERT INTO `ob_picture` VALUES ('179', 'b0d320f6d81d347261fed0733d67f998.jpg', '20181229/b0d320f6d81d347261fed0733d67f998.jpg', '', '2d60f045997e9775e6d8faefa76bf4714ef95192', '1546073098', '0', '1');
INSERT INTO `ob_picture` VALUES ('180', '446798e43e2b5727901eb50a7600c105.jpg', '20181229/446798e43e2b5727901eb50a7600c105.jpg', '', '6c742f943a1106375306fffc4ea3a4bb0621abff', '1546073315', '0', '1');
INSERT INTO `ob_picture` VALUES ('181', '8c904c91a250188f94c91a35ec69ca53.jpg', '20181229/8c904c91a250188f94c91a35ec69ca53.jpg', '', '974bf032bb7b10306e2afa7c7133fa3673caa0f4', '1546073359', '0', '1');
INSERT INTO `ob_picture` VALUES ('182', '1d34da10bc4d1b9d0e730dd4cf551aac.jpg', '20181229/1d34da10bc4d1b9d0e730dd4cf551aac.jpg', '', '7b906f06bba40dbe4e7abf156d06bae4d0a917f2', '1546073390', '0', '1');
INSERT INTO `ob_picture` VALUES ('183', 'c5f195a5885b637902bbdac71fd7653d.jpg', '20181229/c5f195a5885b637902bbdac71fd7653d.jpg', '', '5bf9fcb7d44a79eb3555aee3d1d9ea7fd1ee79b4', '1546073414', '0', '1');
INSERT INTO `ob_picture` VALUES ('184', 'f3b6df2bd3438b646c5dc2d571f009ee.png', '20181229/f3b6df2bd3438b646c5dc2d571f009ee.png', '', 'c483098c82555d5f0e6ffd6fee067c9cb0760583', '1546073445', '0', '1');
INSERT INTO `ob_picture` VALUES ('185', 'bdf6857f084152be2442724ac7aa4378.jpg', '20181229/bdf6857f084152be2442724ac7aa4378.jpg', '', '6ca805b172dce781bbc3c6e948dba364f8ae5700', '1546073762', '0', '1');
INSERT INTO `ob_picture` VALUES ('186', '0c85b116702feef9fb52e91cdf4c1a22.jpg', '20181229/0c85b116702feef9fb52e91cdf4c1a22.jpg', '', 'd5a18ff53d7e9e0d4f666aaf4f4a2afc54936cfa', '1546073768', '0', '1');
INSERT INTO `ob_picture` VALUES ('187', '1c1661e252ffcfd7c64446914bc3af92.png', '20181229/1c1661e252ffcfd7c64446914bc3af92.png', '', '23e789f407440ed053290bed886519777f17dd49', '1546073863', '0', '1');
INSERT INTO `ob_picture` VALUES ('188', 'fe8be59e38a4511dd5339e56659e3863.png', '20181229/fe8be59e38a4511dd5339e56659e3863.png', '', '32b78a2e8cd5c9d4b452474602ed7d25d4b8e196', '1546073895', '0', '1');
INSERT INTO `ob_picture` VALUES ('189', 'c5093b8100e89a256fe289e565b50bf9.jpg', '20181229/c5093b8100e89a256fe289e565b50bf9.jpg', '', '88cb4f97c4b3893678558b16b595ef0206ddf07c', '1546074315', '0', '1');
INSERT INTO `ob_picture` VALUES ('190', '9294706d7da47e773ab53714ac1b1ffd.png', '20181229/9294706d7da47e773ab53714ac1b1ffd.png', '', '06f4a210da74ddd17504e4cf0230b6eb0872a343', '1546074318', '0', '1');
INSERT INTO `ob_picture` VALUES ('191', '4ae4ea173ebe5c1bd7aed30405eefa30.jpg', '20181229/4ae4ea173ebe5c1bd7aed30405eefa30.jpg', '', '54bf6478fcf2f8c0f1971c3ad7f015ce4aa80437', '1546074323', '0', '1');
INSERT INTO `ob_picture` VALUES ('192', '9af1f54e1d4f6897f542bf47b9492b5f.png', '20181229/9af1f54e1d4f6897f542bf47b9492b5f.png', '', '5a4ffd1d59376502457260f035e0df6c00381d80', '1546074329', '0', '1');
INSERT INTO `ob_picture` VALUES ('193', 'a9d3d89b674e94f8c9dc798967dfe9e6.jpg', '20181229/a9d3d89b674e94f8c9dc798967dfe9e6.jpg', '', '4d5d809f5cfdd2da00591196ad1dbdbd0a4ad479', '1546074346', '0', '1');
INSERT INTO `ob_picture` VALUES ('194', '6b60aab55f25dd9b2876d1c4fa92dd22.jpg', '20181229/6b60aab55f25dd9b2876d1c4fa92dd22.jpg', '', 'ff41de021eb7b0adc3ad64407a7819b1c9b96ea8', '1546075271', '0', '1');
INSERT INTO `ob_picture` VALUES ('195', '27e5bfb7adcd98be86d2c45cdaef7012.png', '20181229/27e5bfb7adcd98be86d2c45cdaef7012.png', '', '6aad1cb6b12206bb7301e576d51c97529cfe0642', '1546075277', '0', '1');
INSERT INTO `ob_picture` VALUES ('196', '04204d34e6a66a1e48806ebae87c5e5f.png', '20181229/04204d34e6a66a1e48806ebae87c5e5f.png', '', '35a94920580195d697c490b5b7ab5b012531937d', '1546075288', '0', '1');
INSERT INTO `ob_picture` VALUES ('197', '449d3e97d6450ee283ddbfc3ddb016b4.png', '20181229/449d3e97d6450ee283ddbfc3ddb016b4.png', '', '8de24189ef0f160faf9d43181d18d91a7c28b96a', '1546075297', '0', '1');
INSERT INTO `ob_picture` VALUES ('198', '85a6818f512e3f55d2763e04625808dd.png', '20181229/85a6818f512e3f55d2763e04625808dd.png', '', 'd817e2ed9146da3818676dd53384eafb0ba6d1ed', '1546075317', '0', '1');
INSERT INTO `ob_picture` VALUES ('199', '41b659b6ec6bd72b867e65a8934a1208.jpg', '20181229/41b659b6ec6bd72b867e65a8934a1208.jpg', '', 'f3be2b7c168ee60b53b74cba58851dbcfed45531', '1546075322', '0', '1');
INSERT INTO `ob_picture` VALUES ('200', 'a022151250f99c0e61ee3663e5818fae.jpg', '20181229/a022151250f99c0e61ee3663e5818fae.jpg', '', 'aa4bd2e81b9005594d1ae1770f77222111ed7c0e', '1546075731', '0', '1');
INSERT INTO `ob_picture` VALUES ('201', 'a108f53cc30b8fecf79d551389a22c1e.png', '20181229/a108f53cc30b8fecf79d551389a22c1e.png', '', 'c6cb9d0756c307803c3c59445870526a688b8c03', '1546075737', '0', '1');
INSERT INTO `ob_picture` VALUES ('202', '489c124b932550e0a5e811c672be49c8.png', '20181229/489c124b932550e0a5e811c672be49c8.png', '', 'd4e8b77ef8237aed45fa994d5271f9c869c19a46', '1546075740', '0', '1');
INSERT INTO `ob_picture` VALUES ('203', 'c59c61a179ec4196762351beffc12462.png', '20181229/c59c61a179ec4196762351beffc12462.png', '', 'c5e84e898f06b5c0cabe1976a9c864802908a5b0', '1546075744', '0', '1');
INSERT INTO `ob_picture` VALUES ('204', '2dadae90f05daa8e713584fe07af1808.jpg', '20181229/2dadae90f05daa8e713584fe07af1808.jpg', '', 'd50b966f79188f610871c8c94f66c76b1ec6f265', '1546075749', '0', '1');
INSERT INTO `ob_picture` VALUES ('205', 'a7ef7acf00a41402369f5e440c3e626b.jpg', '20181229/a7ef7acf00a41402369f5e440c3e626b.jpg', '', 'ddbd9b29c039747430ce3576a47cbc5fc7eab9a0', '1546076079', '0', '1');
INSERT INTO `ob_picture` VALUES ('206', 'e8f9ce248bc5eea493410c41999683b8.png', '20181229/e8f9ce248bc5eea493410c41999683b8.png', '', '663559eb5cb1546206ba3a923f9b79ca29df3a2a', '1546076082', '0', '1');
INSERT INTO `ob_picture` VALUES ('207', 'cdda2b81dd6e9f6a04be64a4e92ac4e2.png', '20181229/cdda2b81dd6e9f6a04be64a4e92ac4e2.png', '', '30888c43bc14120008433ecc6763b968d7c9d77c', '1546076087', '0', '1');
INSERT INTO `ob_picture` VALUES ('208', 'e866962cc0362e21066c1190597b0630.png', '20181229/e866962cc0362e21066c1190597b0630.png', '', '9ebcb99a05908ab1fd771ed881d870efb1e10745', '1546076090', '0', '1');
INSERT INTO `ob_picture` VALUES ('209', '21a8f1ad8d395de84c56e97409594b9f.jpg', '20181229/21a8f1ad8d395de84c56e97409594b9f.jpg', '', '449305dd65b76a185cbcd5ac9a1fbdcf4e1d1f2d', '1546076098', '0', '1');
INSERT INTO `ob_picture` VALUES ('210', '6d092b92cfb3aaa1d21017697b2933ad.png', '20190309/6d092b92cfb3aaa1d21017697b2933ad.png', '', 'f595c0bd89ae18f6ed9a36a35778de16901ef9f8', '1552106200', '0', '1');

-- ----------------------------
-- Table structure for `ob_seo`
-- ----------------------------
DROP TABLE IF EXISTS `ob_seo`;
CREATE TABLE `ob_seo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(40) NOT NULL DEFAULT '' COMMENT '模块',
  `seo_title` text NOT NULL COMMENT '标题',
  `seo_keywords` text NOT NULL COMMENT '关键字',
  `seo_description` text NOT NULL COMMENT '描述',
  `usable_val` varchar(255) NOT NULL DEFAULT '' COMMENT '可用变量',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='seo表';

-- ----------------------------
-- Records of ob_seo
-- ----------------------------
INSERT INTO `ob_seo` VALUES ('40', '首页SEO信息', 'index/index/index', 'OneBase 开发架构{$category_name}{$article_title}', 'OneBase,PHP,{$category_name},{$article_title}', '一款基于ThinkPHP5研发的开源免费基础架构，基于OneBase可以快速的研发各类Web应用。{$article_describe}', '{$category_name}，{$article_title}，{$article_describe}', '0', '1', '1505445912', '1505470293');
INSERT INTO `ob_seo` VALUES ('41', 'OneBase-系统登录', 'index/index/login', 'OneBase', 'OneBase', 'OneBase', '', '0', '1', '1505538002', '1505538026');

-- ----------------------------
-- Table structure for `ob_slider`
-- ----------------------------
DROP TABLE IF EXISTS `ob_slider`;
CREATE TABLE `ob_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '轮播图片名称',
  `img_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '图片id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='轮播表';

-- ----------------------------
-- Records of ob_slider
-- ----------------------------
INSERT INTO `ob_slider` VALUES ('16', '三国群雄传', '149', 'javascript:;', '1545994264', '1545994324', '0', '1');
INSERT INTO `ob_slider` VALUES ('17', '裁决者', '150', 'javascript:;', '1545994529', '1545994529', '0', '1');
INSERT INTO `ob_slider` VALUES ('18', '灭神', '151', 'javascript:;', '1545994552', '1545994552', '0', '1');

-- ----------------------------
-- Table structure for `ob_wg_bind`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_bind`;
CREATE TABLE `ob_wg_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conference_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会ID',
  `employee_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '员工ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `is_check` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0：未审核  ， 1：审核通过，2：审核未通过',
  `check_member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核人ID',
  PRIMARY KEY (`id`),
  KEY `index_conference_id` (`conference_id`),
  KEY `index_employee_id` (`employee_id`),
  KEY `index_is_check` (`is_check`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户绑定表';

-- ----------------------------
-- Records of ob_wg_bind
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_category`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_category`;
CREATE TABLE `ob_wg_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `category_describe` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块分类表';

-- ----------------------------
-- Records of ob_wg_category
-- ----------------------------
INSERT INTO `ob_wg_category` VALUES ('2', '传奇游戏', '传奇类游戏', '1500263210', '1521189711', '1');
INSERT INTO `ob_wg_category` VALUES ('3', '角色扮演', '角色扮演类游戏', '1500263227', '1520052994', '1');
INSERT INTO `ob_wg_category` VALUES ('5', '模拟经营', '模拟经营类游戏', '1500265944', '1520053000', '1');
INSERT INTO `ob_wg_category` VALUES ('6', '战争策略', '战争策略类游戏', '1500265960', '1520053010', '1');

-- ----------------------------
-- Table structure for `ob_wg_code`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_code`;
CREATE TABLE `ob_wg_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conference_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会成员ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `code` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '推广编码',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `index_conference_id` (`conference_id`),
  KEY `index_member_id` (`member_id`),
  KEY `index_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='游戏服务器表';

-- ----------------------------
-- Records of ob_wg_code
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_conference`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_conference`;
CREATE TABLE `ob_wg_conference` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conference_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公会名称',
  `contact_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_mobile` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` char(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `account_holder` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '开户人',
  `opening_bank` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_account` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '银行账户',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会管理员ID',
  `source_member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会代理会员ID',
  `ratio` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分成比例 100 表示 100%',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块公会表';

-- ----------------------------
-- Records of ob_wg_conference
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_conference_limit`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_conference_limit`;
CREATE TABLE `ob_wg_conference_limit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conference_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作会员ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `server_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务器ID',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '消耗金额',
  `use` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用途',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块公会元宝池表';

-- ----------------------------
-- Records of ob_wg_conference_limit
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_conference_member`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_conference_member`;
CREATE TABLE `ob_wg_conference_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conference_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会成员ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `index_conference_id` (`conference_id`),
  KEY `index_member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块公会成员表';

-- ----------------------------
-- Records of ob_wg_conference_member
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_game`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_game`;
CREATE TABLE `ob_wg_game` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏分类id',
  `game_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '游戏名称',
  `game_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '游戏code纯英文字母，唯一标识',
  `game_intro` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '游戏简介',
  `game_cover` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏封面',
  `endways_cover` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '纵向封面',
  `game_logo` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏logo',
  `game_head` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏头像图片',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否热门',
  `game_currency_ratio` int(11) NOT NULL DEFAULT '1' COMMENT '游戏币比例',
  `website_bg_img` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '官网大背景图',
  `website_intro_imgs` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '官网左侧简介图（多图）',
  `website_screenshot` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '官网游戏截图（多图）',
  `website_job_imgs` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '游戏人物职业截图（多图）',
  `client_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '微端安装文件下载URL',
  `client_cover` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '微端封面图',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `maintain_end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '游戏维护结束时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块游戏表';

-- ----------------------------
-- Records of ob_wg_game
-- ----------------------------
INSERT INTO `ob_wg_game` VALUES ('1', '2', '三国群雄传', 'sgqxz', '《XXX》是一款东方魔幻风格的多人在线角色扮演游戏，国服由欢乐人游戏运营，游戏以升级、打装备、结识兄弟为游戏主要玩法，并融合一些创新的特色玩法；从风沙漫天的沙漠之城，到幽深远古的封魔森林；从窒息黑暗的玛雅古城，到冰雪皑皑的神圣冰宫。 ', '155', '157', '154', '156', '1', '1', '100', '149', '162,163', '167,168,169,170,171,172', '164,165,166', 'http://xx.xxxx.com/client/sgqxz.exe', '0', '0', '1545840000', '1', '1545997287', '1545995622');
INSERT INTO `ob_wg_game` VALUES ('2', '2', '灭神', 'ms', '《XXX》是一款东方魔幻风格的多人在线角色扮演游戏，国服由欢乐人游戏运营，游戏以升级、打装备、结识兄弟为游戏主要玩法，并融合一些创新的特色玩法；从风沙漫天的沙漠之城，到幽深远古的封魔森林；从窒息黑暗的玛雅古城，到冰雪皑皑的神圣冰宫。 ', '179', '0', '179', '178', '1', '1', '100', '175', '176,177', '170,172,171,167,169,168', '166,164,165', '', '0', '0', '1545840000', '1', '1546073162', '1546072897');
INSERT INTO `ob_wg_game` VALUES ('3', '2', '裁决者', 'cjz', '', '182', '183', '182', '184', '1', '1', '100', '180', '', '', '', '', '0', '0', '1545840000', '1', '1546073477', '1546073447');
INSERT INTO `ob_wg_game` VALUES ('4', '2', '蓝月传奇', 'lycq', '', '185', '187', '186', '188', '1', '1', '100', '174', '', '167,168,169,170,171,172', '166,164,165', '', '0', '0', '1545840000', '1', '1546073954', '1546073954');
INSERT INTO `ob_wg_game` VALUES ('5', '2', '御龙在天', 'ylzt', '', '189', '192', '191', '190', '1', '1', '100', '193', '192', '170,172,171,167,169,168', '166,165,164', '', '0', '0', '1545282000', '1', '1546074399', '1546074399');
INSERT INTO `ob_wg_game` VALUES ('6', '2', '热血合击', 'rxhj', '', '194', '195', '196', '198', '1', '1', '100', '199', '195,197', '168,167,169,170,171,172', '166,165,164', '', '0', '0', '1545297600', '1', '1546075361', '1546075361');
INSERT INTO `ob_wg_game` VALUES ('7', '2', '血饮传说', 'xycs', '', '200', '201', '202', '203', '0', '0', '100', '204', '201', '170,171,172,167,168,169', '166,165,164', '', '0', '0', '1545269100', '1', '1546075794', '1546075794');
INSERT INTO `ob_wg_game` VALUES ('8', '2', '红月传说', 'hycs', '', '205', '208', '206', '207', '0', '0', '100', '209', '208', '170,171,172,167,168,169', '166,165,164', '', '0', '0', '1545269100', '1', '1546076128', '1546076128');

-- ----------------------------
-- Table structure for `ob_wg_gift`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_gift`;
CREATE TABLE `ob_wg_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `gift_name` varchar(200) NOT NULL DEFAULT '' COMMENT '礼包名称',
  `gift_describe` varchar(255) NOT NULL DEFAULT '' COMMENT '礼包描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='页游模块礼包';

-- ----------------------------
-- Records of ob_wg_gift
-- ----------------------------
INSERT INTO `ob_wg_gift` VALUES ('42', '8', '红月新手大礼包', '天山雪莲(大) *1, 复活玫瑰*2, 消红药水*1, 副本卷轴*5, 玛雅通行证*2, 神魔秘境卷轴*2', '1546076262', '1546076262', '1');
INSERT INTO `ob_wg_gift` VALUES ('43', '4', '蓝月传奇新手礼包', '十全大补鸡*1、复活玫瑰*2、消红药水*1、副本卷轴*5、玛雅通行证*2、锁妖塔卷轴*2', '1546076341', '1546076341', '1');
INSERT INTO `ob_wg_gift` VALUES ('44', '1', '三国群雄礼包', '铜钱50000、粮草50000、普通材料包X5', '1546076411', '1546076411', '1');
INSERT INTO `ob_wg_gift` VALUES ('45', '3', '裁决者新手礼包', '超级经验丹*1；书页*10；10W元宝*1；神魂丹(中)*1', '1546076462', '1546076462', '1');
INSERT INTO `ob_wg_gift` VALUES ('46', '2', '灭神注册大礼包', '换镖令*3;200圣妖币*2;复活丹*2;初级藏宝图*1', '1546076555', '1546076555', '1');
INSERT INTO `ob_wg_gift` VALUES ('47', '5', '御龙在天新手礼包', '普通血纹强化石*20、普通血纹洗炼石*20', '1546076597', '1546076597', '1');

-- ----------------------------
-- Table structure for `ob_wg_gift_key`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_gift_key`;
CREATE TABLE `ob_wg_gift_key` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gift_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼包ID',
  `key` varchar(100) NOT NULL DEFAULT '' COMMENT '礼包key',
  `is_get` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0 未领取，1 已领取',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取会员ID',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'key创建时间',
  `get_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '礼包领取时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `index_gift_id` (`gift_id`),
  KEY `index_key` (`key`),
  KEY `index_member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页游模块礼包KEY';

-- ----------------------------
-- Records of ob_wg_gift_key
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_order`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_order`;
CREATE TABLE `ob_wg_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `pay_code` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '支付方式标识',
  `pay_name` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态 0:未支付  1:已支付',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值会员id',
  `role_id` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色ID',
  `order_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `order_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态 0：未充值  1：已充值',
  `request_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '充值请求URL',
  `response` text COLLATE utf8_unicode_ci NOT NULL COMMENT '充值接口响应结果',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为后台下单 0：否，1：是',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '数据状态',
  `conference_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会ID',
  `c_member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会成员ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `server_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务器ID',
  `create_date` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '创建日期',
  `create_month` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '创建年月',
  `ip` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '下单IP',
  PRIMARY KEY (`id`),
  KEY `index_conference_id` (`conference_id`),
  KEY `index_c_member_id` (`c_member_id`),
  KEY `index_create_date` (`create_date`) USING BTREE,
  KEY `index_member_id` (`member_id`),
  KEY `index_role_id` (`role_id`),
  KEY `index_create_month` (`create_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块订单表';

-- ----------------------------
-- Records of ob_wg_order
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_player`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_player`;
CREATE TABLE `ob_wg_player` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `server_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务器ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `extend` text COLLATE utf8_unicode_ci NOT NULL COMMENT '扩展字段（json）',
  `version` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '扩展版本',
  `login_ip` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '登录IP',
  `login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `partner_code` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '合作伙伴code',
  `register_code` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '注册代码',
  `conference_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会ID',
  `c_member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公会成员ID',
  `create_date` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '日期',
  `create_month` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '创建月份',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `index_conference_id` (`conference_id`),
  KEY `index_c_member_id` (`c_member_id`),
  KEY `index_member_id` (`member_id`),
  KEY `index_create_date` (`create_date`),
  KEY `index_login_ip` (`login_ip`),
  KEY `index_create_month` (`create_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块玩家表';

-- ----------------------------
-- Records of ob_wg_player
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_role`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_role`;
CREATE TABLE `ob_wg_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `role_id` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色ID',
  `role_level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色等级',
  `role_name` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `index_player_id` (`player_id`),
  KEY `index_role_name` (`role_name`),
  KEY `index_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块用户角色表';

-- ----------------------------
-- Records of ob_wg_role
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_wg_server`
-- ----------------------------
DROP TABLE IF EXISTS `ob_wg_server`;
CREATE TABLE `ob_wg_server` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属游戏',
  `server_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '服务器名称',
  `maintain_end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '维护结束时间',
  `cp_server_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏提供商服务器ID',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开服时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页游模块服务器表';

-- ----------------------------
-- Records of ob_wg_server
-- ----------------------------
INSERT INTO `ob_wg_server` VALUES ('1', '8', '双线1服', '1544630400', '1', '1545926400', '1546076712', '1546076753', '1');
INSERT INTO `ob_wg_server` VALUES ('2', '7', '双线22服', '1543338300', '22', '1545945900', '1546076801', '1546076801', '1');
INSERT INTO `ob_wg_server` VALUES ('3', '1', '双线15服', '1545875100', '15', '1545337200', '1546076822', '1546076822', '1');
INSERT INTO `ob_wg_server` VALUES ('4', '6', '双线55服', '1545182700', '55', '1545945900', '1546076848', '1546076848', '1');
INSERT INTO `ob_wg_server` VALUES ('5', '2', '双线19服', '1543177200', '19', '1544064600', '1546076874', '1546076874', '1');
INSERT INTO `ob_wg_server` VALUES ('6', '4', '双线17服', '1543973100', '17', '1544607900', '1546076946', '1546076946', '1');
INSERT INTO `ob_wg_server` VALUES ('7', '5', '双线65服', '1544487600', '65', '1544063100', '1546076961', '1546076961', '1');
INSERT INTO `ob_wg_server` VALUES ('8', '3', '双线11服', '1543957500', '11', '1544595900', '1546076985', '1546076985', '1');
