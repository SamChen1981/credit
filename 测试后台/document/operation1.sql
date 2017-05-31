/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : operation1

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-04-29 11:13:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for operation_comments
-- ----------------------------
DROP TABLE IF EXISTS `operation_comments`;
CREATE TABLE `operation_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL COMMENT '评论的内容',
  `content_id` int(11) DEFAULT NULL COMMENT '被评论的新闻id',
  `content_title` varchar(150) DEFAULT NULL COMMENT '被评论的对象标题',
  `content_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '被评论主题类型（1：活动，2调查，3投票）',
  `catalog_id` int(11) DEFAULT NULL COMMENT '被评论的新闻的栏目id',
  `catalog_name` varchar(150) DEFAULT NULL COMMENT '被评论的新闻栏目名称',
  `reply_id` int(11) DEFAULT '0' COMMENT '评论回复ID',
  `create_time` int(11) DEFAULT NULL COMMENT '评论时间',
  `user_id` int(11) DEFAULT NULL COMMENT '评论人的id',
  `user_name` varchar(60) DEFAULT NULL COMMENT '用户名称',
  `user_nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `ip` varchar(16) DEFAULT NULL COMMENT 'ip地址',
  `mac` varchar(17) DEFAULT NULL COMMENT 'mac地址',
  `floors` varchar(255) DEFAULT NULL COMMENT '引用该评论的楼层id集合',
  `report` int(11) DEFAULT '0' COMMENT '举报次数',
  `praise` int(11) DEFAULT '0' COMMENT '被赞次数',
  `status` tinyint(2) DEFAULT NULL COMMENT '评论状态 0:待审 1:已审 -1:审核未通过',
  `anonymous` tinyint(2) DEFAULT '0' COMMENT '如果是匿名评论，该字段为1,默认0',
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `anonymous` (`anonymous`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='用户评论表';

-- ----------------------------
-- Records of operation_comments
-- ----------------------------
INSERT INTO `operation_comments` VALUES ('43', 'that\'s crazy!\r\n	', '1', 'slam dunk!', '1', '1', 'basketball', '0', '1438517968', '1', null, ' ', '127.0.0.1', null, null, '0', '6', '-1', '0', '2015-08-06 10:31:21');
INSERT INTO `operation_comments` VALUES ('44', 'I *\'T BELIEVE WHAT I HAVE SEEN~~', '1', 'slam dunk!', '2', '1', 'basketball', '0', '1438517988', '1', null, ' ', '127.0.0.1', null, null, '0', '0', '0', '0', '2015-08-13 13:09:16');
INSERT INTO `operation_comments` VALUES ('1', '**内容1', '1', 'content标题1', '1', null, null, '0', '1438678011', '1', 'hyh', ' ', null, 'jljsdkfs', null, '0', '0', '1', '0', '2015-08-13 13:18:11');

-- ----------------------------
-- Table structure for operation_comment_users
-- ----------------------------
DROP TABLE IF EXISTS `operation_comment_users`;
CREATE TABLE `operation_comment_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `my_user_id` int(11) NOT NULL COMMENT '被评论用户id',
  `my_comment` varchar(255) NOT NULL COMMENT '被评论内容',
  `reply_user_id` int(11) NOT NULL COMMENT '评论评论的用户id',
  `reply_user_nick` varchar(150) NOT NULL COMMENT '评论评论的用户昵称',
  `reply_comment` varchar(255) NOT NULL COMMENT '评论评论的内容',
  `reply_avatar` varchar(255) NOT NULL COMMENT '评论评论的用户头像',
  `comment_id` int(11) NOT NULL COMMENT 'comments表id',
  `create_time` int(11) NOT NULL COMMENT '评论时间',
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `my_user_id` (`my_user_id`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of operation_comment_users
-- ----------------------------

-- ----------------------------
-- Table structure for operation_configs
-- ----------------------------
DROP TABLE IF EXISTS `operation_configs`;
CREATE TABLE `operation_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(60) NOT NULL COMMENT '评论设置key',
  `value` varchar(255) NOT NULL COMMENT '评论设置值',
  `name` varchar(150) NOT NULL COMMENT '评论设置名称',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of operation_configs
-- ----------------------------
INSERT INTO `operation_configs` VALUES ('1', 'comments_check', '1', '评论审核', '0000-00-00 00:00:00', '2015-08-10 10:14:28');
INSERT INTO `operation_configs` VALUES ('2', 'comments', '1', '评论', '0000-00-00 00:00:00', '2015-08-10 10:14:28');
INSERT INTO `operation_configs` VALUES ('3', 'interval_time', 'sdf', '用户评论间隔时长', '2015-07-29 16:51:27', '2015-08-10 10:14:28');
INSERT INTO `operation_configs` VALUES ('4', 'comments_num', '-1', '每篇内容最多评论条数', '2015-07-29 16:51:37', '2015-08-10 10:14:28');
INSERT INTO `operation_configs` VALUES ('5', 'comments_in_one_day', '-20', '24小时内最多发布评论条数', '2015-07-29 16:51:42', '2015-08-10 10:14:28');
INSERT INTO `operation_configs` VALUES ('6', 'paike_ftp_url', 'http://1123123.com:80/a/b', '拍客上传ftp上传地址', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `operation_configs` VALUES ('7', 'paike_ftp_user', 'hyh', 'ftp用户名', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `operation_configs` VALUES ('8', 'paike_ftp_password', '12345676', 'ftp用户密码', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `operation_configs` VALUES ('9', 'paike_ftp_port', '80', 'ftp地址端口', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `operation_configs` VALUES ('10', 'paike_ftp_path', '/vmsyszd/source/webftp/', 'ftp上传路径', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for operation_exchange_logs
-- ----------------------------
DROP TABLE IF EXISTS `operation_exchange_logs`;
CREATE TABLE `operation_exchange_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `user_name` varchar(60) DEFAULT NULL COMMENT '用户名',
  `mobile_phone` varchar(11) DEFAULT NULL COMMENT '用户手机号码',
  `product_id` int(11) DEFAULT '0' COMMENT '商品id',
  `activity_name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `product_name` varchar(150) DEFAULT NULL COMMENT '商品名称',
  `credits` int(11) DEFAULT '0' COMMENT '花费积分',
  `code` varchar(32) DEFAULT '' COMMENT '兑换码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '兑换状态（1：未兑换，2：已兑换）',
  `runner_id` int(11) DEFAULT '0' COMMENT '操作者id',
  `runner_name` varchar(150) DEFAULT NULL COMMENT '操作者昵称',
  `activity_id` int(11) DEFAULT '0' COMMENT '活动id',
  `prize_id` int(11) DEFAULT '0' COMMENT '奖品id',
  `hit_status` tinyint(2) DEFAULT '1' COMMENT '中奖状态（2：中奖，1：未中奖）',
  `exchange_type` int(2) NOT NULL DEFAULT '1' COMMENT '兑换类型(1,积分兑换，2,摇一摇兑换，3,活动游戏兑换，4，活动)',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `code` (`code`),
  KEY `product_name` (`product_name`),
  KEY `create_time` (`create_time`),
  KEY `status` (`status`),
  KEY `activity_id` (`activity_id`),
  KEY `prize_id` (`prize_id`),
  KEY `hit_status` (`hit_status`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='奖品兑换表';

-- ----------------------------
-- Records of operation_exchange_logs
-- ----------------------------
INSERT INTO `operation_exchange_logs` VALUES ('1', '1', 'hyh', null, '0', '测试活动添加', null, '100', '568c8c544790975fabb4d2b314a4d0af', '1', '0', null, '14', '1', '2', '2', '1438930005', '1438930005');
INSERT INTO `operation_exchange_logs` VALUES ('2', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'bb0e1bc19e5d1a060ca12a3d1dd077ef', '1', '0', null, '14', '1', '2', '1', '1438930051', '1438930051');
INSERT INTO `operation_exchange_logs` VALUES ('3', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'c9674447c32daf1442458754840feaa1', '1', '0', null, '14', '1', '2', '1', '1438930771', '1438930771');
INSERT INTO `operation_exchange_logs` VALUES ('4', '0', 'hyh', null, '0', '测试活动添加', null, '100', '2bb15220e6fe27aeb441da516179a5b7', '1', '0', null, '14', '2', '2', '1', '1438930818', '1438930818');
INSERT INTO `operation_exchange_logs` VALUES ('5', '0', 'hyh', null, '0', '测试活动添加', null, '100', '7e007e47783f6d1115ed99adbf41bd12', '1', '0', null, '14', '2', '2', '1', '1438930830', '1438930830');
INSERT INTO `operation_exchange_logs` VALUES ('6', '0', 'hyh', null, '0', '测试活动添加', null, '100', '43bfd75bbc00cf70d8a134af980194ac', '1', '0', null, '14', '1', '2', '1', '1438930846', '1438930846');
INSERT INTO `operation_exchange_logs` VALUES ('7', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'b8d04dedff278fb5f061abe428f56f6f', '1', '0', null, '14', '2', '2', '1', '1438930856', '1438930856');
INSERT INTO `operation_exchange_logs` VALUES ('8', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'be244d9ffe9e21423453027e4e60be55', '1', '0', null, '14', '2', '2', '1', '1438930889', '1438930889');
INSERT INTO `operation_exchange_logs` VALUES ('9', '0', 'hyh', null, '0', '测试活动添加', null, '100', '2cb35010c7bc0b2615ce5e6aa0b5346f', '1', '0', null, '14', '2', '2', '1', '1438930914', '1438930914');
INSERT INTO `operation_exchange_logs` VALUES ('10', '0', 'hyh', null, '0', '测试活动添加', null, '100', '4da326e9e7f2fb9fdb447f9e3056d0a3', '1', '0', null, '14', '1', '2', '1', '1438931094', '1438931094');
INSERT INTO `operation_exchange_logs` VALUES ('11', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'cdcff2f27b31297d7204f94e2eb8a159', '1', '0', null, '14', '2', '2', '1', '1438931115', '1438931115');
INSERT INTO `operation_exchange_logs` VALUES ('12', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'b636f8f184fbd1bcc39f1a793368ade8', '1', '0', null, '14', '2', '2', '1', '1438931199', '1438931199');
INSERT INTO `operation_exchange_logs` VALUES ('13', '0', 'hyh', null, '0', '测试活动添加', null, '100', 'bcfe425a2de3d3a69933e440c8402388', '1', '0', null, '14', '2', '2', '1', '1438931234', '1438931234');
INSERT INTO `operation_exchange_logs` VALUES ('14', '1', 'hyh', '13711111111', '0', '测试活动添加', null, '100', 'e78af89f59ef38405995f1e9bb7d5f2c', '1', '0', null, '14', '2', '2', '2', '1438931255', '1438931255');
INSERT INTO `operation_exchange_logs` VALUES ('15', '1', 'hyh', '13711111111', '0', '测试活动添加', null, '100', '95f350bd4df57ff7eb69d52eedec9921', '1', '0', null, '14', '1', '2', '2', '1438931341', '1438931341');
INSERT INTO `operation_exchange_logs` VALUES ('16', '1', 'hyh', '13711111111', '0', '测试活动添加', null, '100', '388535664e778e54a86357cc91b97d71', '1', '0', null, '14', '1', '2', '2', '1438931359', '1438931359');
INSERT INTO `operation_exchange_logs` VALUES ('17', '1', 'hyh', '', '0', '测试活动添加', null, '100', 'fc1055ccdcde0ed5836f7a0c2d98d56d', '1', '0', null, '14', '1', '2', '2', '1438935178', '1438935178');
INSERT INTO `operation_exchange_logs` VALUES ('18', '1', 'hyh', null, '0', '活动名1', '产品名1', '1000', 'asdfasdfasdfasdfwer', '1', '0', null, '1', '0', '2', '1', '11', '0');
INSERT INTO `operation_exchange_logs` VALUES ('19', '1', 'hyh', '13711111111', '2', '活动名2', '产品名2', '200', '82bfaf3f3ba533ab27a50f21f77b742f', '1', '0', null, '0', '0', '2', '1', '1438943037', '1438943037');
INSERT INTO `operation_exchange_logs` VALUES ('20', '1038', 'user_UhXByT0Ul4xk', '13514141414', '0', '游戏1', null, '0', '714abc3085d7d6a15811b2fed0f2238b', '1', '0', null, '1', '1', '2', '3', '1439259992', '1439259992');
INSERT INTO `operation_exchange_logs` VALUES ('21', '1048', '', '', '0', '测试摇一摇活动', null, '100', '', '1', '0', null, '1', '0', '0', '2', '1439469273', '1439469273');
INSERT INTO `operation_exchange_logs` VALUES ('22', '1048', '', '', '0', '测试摇一摇活动', null, '100', '', '1', '0', null, '1', '0', '0', '2', '1439469291', '1439469291');
INSERT INTO `operation_exchange_logs` VALUES ('23', '1049', 'user_jMzZDUMa6qtn', '18284595623', '1', '活动名1', '产品名1', '100', 'aab3ba60ee1cd6227be80a3f601f8c2e', '1', '0', null, '1', '0', '1', '1', '1439779999', '1439779999');
INSERT INTO `operation_exchange_logs` VALUES ('24', '1042', 'user_jMzZDUMa6qtn', '18284595623', '1', '活动名1', '产品名1', '0', 'aab3ba60ee1cd6227be80a3f601f8c2e', '1', '0', null, '1', '1', '2', '3', '1439779999', '1439779999');
INSERT INTO `operation_exchange_logs` VALUES ('47', '1100', 'user_xzc9jkMzfOqQ', null, '0', '测试摇一摇活动', null, '1', '9345cb09f32f3c22d7adf3c031352a30', '1', '0', null, '1', '73', '2', '2', '1439977954', '1439977954');
INSERT INTO `operation_exchange_logs` VALUES ('48', '1100', 'user_xzc9jkMzfOqQ', null, '0', '测试摇一摇活动', null, '1', '1ab2fc127835a4589b997603fe28bfdc', '1', '0', null, '1', '79', '2', '2', '1439978086', '1439978086');

-- ----------------------------
-- Table structure for operation_feedbacks
-- ----------------------------
DROP TABLE IF EXISTS `operation_feedbacks`;
CREATE TABLE `operation_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact` varchar(255) NOT NULL COMMENT '联系方式',
  `content` text NOT NULL COMMENT '反馈内容',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `user_name` varchar(255) NOT NULL DEFAULT ' ' COMMENT '用户名',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态,',
  `modify_time` int(10) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of operation_feedbacks
-- ----------------------------
INSERT INTO `operation_feedbacks` VALUES ('1', '3', '4', '1', '2', '1', '1439453723', '2015-08-13');
INSERT INTO `operation_feedbacks` VALUES ('2', '3', '4', '1', '2', '1', '1439453768', '2015-08-13');
INSERT INTO `operation_feedbacks` VALUES ('3', '1', '1', '1', ' 1', '1', '1', '2015-08-04');
INSERT INTO `operation_feedbacks` VALUES ('4', '3', '4', '1', '2', '1', '1439454111', '2015-08-13');

-- ----------------------------
-- Table structure for operation_paikes
-- ----------------------------
DROP TABLE IF EXISTS `operation_paikes`;
CREATE TABLE `operation_paikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL COMMENT '标题',
  `description` tinytext NOT NULL COMMENT '描述',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '拍客类型：1图片；2视频；',
  `catalog_id` int(11) NOT NULL DEFAULT '0' COMMENT '栏目id',
  `user_id` int(11) NOT NULL COMMENT '作者id',
  `user_name` varchar(60) NOT NULL COMMENT '作者user_name',
  `provider` varchar(255) DEFAULT NULL COMMENT '上传者姓名',
  `phone` varchar(11) NOT NULL COMMENT '作者手机号',
  `email` varchar(64) NOT NULL,
  `source` varchar(120) NOT NULL COMMENT '资源id，以逗号分开',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='拍客主表';

-- ----------------------------
-- Records of operation_paikes
-- ----------------------------

-- ----------------------------
-- Table structure for operation_paike_catalogs
-- ----------------------------
DROP TABLE IF EXISTS `operation_paike_catalogs`;
CREATE TABLE `operation_paike_catalogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '栏目名称',
  `library_id` int(11) NOT NULL DEFAULT '0' COMMENT '素材库对应栏目id',
  `library_name` varchar(255) NOT NULL DEFAULT '' COMMENT '素材库对应栏目名称',
  `sort` int(11) NOT NULL DEFAULT '99' COMMENT '越小越靠前',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：隐藏，1：显示）',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='拍客栏目表';

-- ----------------------------
-- Records of operation_paike_catalogs
-- ----------------------------
INSERT INTO `operation_paike_catalogs` VALUES ('34', 'test', '1', 'test', '99', '1', '2015-08-11 13:11:30', '2015-08-11 13:11:30');
INSERT INTO `operation_paike_catalogs` VALUES ('11', 'testTEST!', '0', '', '99', '1', '2015-07-31 07:00:10', '2015-08-11 13:05:04');
INSERT INTO `operation_paike_catalogs` VALUES ('12', 'testTEST!!', '0', '', '99', '1', '2015-07-31 07:01:03', '2015-07-31 07:01:03');
INSERT INTO `operation_paike_catalogs` VALUES ('13', 'testTEST!!!', '0', '', '99', '1', '2015-07-31 07:06:20', '2015-07-31 07:06:20');
INSERT INTO `operation_paike_catalogs` VALUES ('16', 'testTEST!!!!', '0', '', '99', '1', '2015-08-04 04:58:12', '2015-08-04 04:58:12');

-- ----------------------------
-- Table structure for operation_paike_sources
-- ----------------------------
DROP TABLE IF EXISTS `operation_paike_sources`;
CREATE TABLE `operation_paike_sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '主表id',
  `name` varchar(150) NOT NULL COMMENT '资源名称',
  `description` tinytext NOT NULL COMMENT '资源描述',
  `path` varchar(255) NOT NULL COMMENT '资源路径（图片：图地址，视频：封面图）',
  `width` int(5) NOT NULL,
  `height` int(5) NOT NULL,
  `video_id` varchar(40) NOT NULL COMMENT '资源在内容中心的id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='拍客资源表';

-- ----------------------------
-- Records of operation_paike_sources
-- ----------------------------
INSERT INTO `operation_paike_sources` VALUES ('4', '0', '', '', '', '0', '0', '', '0', '1438853898');
INSERT INTO `operation_paike_sources` VALUES ('5', '0', '', '', '', '0', '0', '', '0', '1438854599');
INSERT INTO `operation_paike_sources` VALUES ('6', '0', '', '', '', '0', '0', '', '0', '1438854610');

-- ----------------------------
-- Table structure for operation_products
-- ----------------------------
DROP TABLE IF EXISTS `operation_products`;
CREATE TABLE `operation_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品id',
  `activity_name` varchar(60) NOT NULL COMMENT '活动名称',
  `product_name` text NOT NULL COMMENT '产品名称',
  `quantity` int(11) NOT NULL DEFAULT '99999' COMMENT '产品数量',
  `status` tinyint(2) NOT NULL COMMENT '活动状态（1：进行中，2：已结束，3：已兑完，4：已撤销，5：草稿箱）',
  `create_by` int(11) NOT NULL COMMENT '创建人id',
  `exchange_quantity` int(11) DEFAULT '0' COMMENT '已兑换数量',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `validity_date` int(11) NOT NULL COMMENT '兑换有效期',
  `credits1` int(11) DEFAULT NULL COMMENT '积分1',
  `credits2` int(11) DEFAULT NULL COMMENT '积分2',
  `credits3` int(11) DEFAULT NULL COMMENT '积分3',
  `exchange_times` int(11) DEFAULT NULL COMMENT '每个用户兑换次数',
  `thumb_img` varchar(255) DEFAULT NULL COMMENT '商品缩略图',
  `banner_img` varchar(255) DEFAULT NULL COMMENT 'banner图片',
  `accept_way` tinyint(2) NOT NULL COMMENT '领取方式（1：自领，2：快递）',
  `accept_addr` varchar(255) DEFAULT NULL COMMENT '自领地址',
  `link_man` varchar(60) DEFAULT NULL COMMENT '联系人',
  `link_phone` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `accept_time` tinyint(2) DEFAULT '2' COMMENT '领取时间（1：工作日，2：不限）',
  `accept_time_desc` varchar(255) DEFAULT '' COMMENT '领取时间详细',
  `member_nums` int(11) NOT NULL DEFAULT '0' COMMENT '参与用户数量',
  `participation_nums` int(11) NOT NULL DEFAULT '0' COMMENT '参与总次数',
  `sort` tinyint(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `end_time` int(11) DEFAULT '0',
  `type` tinyint(2) DEFAULT '2',
  `market_price` int(11) DEFAULT NULL COMMENT '市场价',
  `order_start_time` int(11) DEFAULT NULL COMMENT '自取订单有效期开始时间',
  `order_end_time` int(11) DEFAULT NULL COMMENT '订单自取结束时间',
  PRIMARY KEY (`id`),
  KEY `start_time` (`start_time`),
  KEY `status` (`status`),
  KEY `activity_name` (`activity_name`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='积分商城产品表';

-- ----------------------------
-- Records of operation_products
-- ----------------------------
INSERT INTO `operation_products` VALUES ('1', '葡萄籽精华胶囊', '葡萄籽提取素可以有助于防止和治疗心血管疾病，诸如高血压和高胆固醇这样的疾病。通过限制氧化，在葡萄籽提取素中的抗氧化剂可以有助于防止老化，而这些老化包括对血管的损害，对血管的损害可能导致心脏病的发展。在葡萄籽提取素中的物质也可以阻挡一种酶的功效，而这种酶对脂肪和包括日常饮食中的胆固醇进行处理。', '100', '1', '1', '1', '1438051440', '1451968021', '1447114692', '0', '100', null, null, '0', '/upload/20151108/20150073943692.jpg', null, '2', '', '', '', null, '', '1000', '2000', '0', '1454209146', '2', '68', null, null);
INSERT INTO `operation_products` VALUES ('2', '活动名2', '产品名2', '100', '6', '2', '1', '1438051440', '1446832567', '1446832567', '86400', '200', null, null, '1', 'http://113.142.30.203/o2o/data/upload/shop/adv/f5295d2197a1857cc6914f233d67019b.jpg', null, '1', '领取地址', '联系人', '13111111111', '1', '时间描述', '100', '1000', '9', '0', '2', '99', null, null);
INSERT INTO `operation_products` VALUES ('3', '胶原蛋白口服液', '人体的胶原蛋白每天流失，所以，有必要补充适量的胶原蛋白以保持皮肤的年轻状态。“胶原蛋白液态饮”小分子，易吸收，口感好，纯净天然，每瓶含量高达5000毫克，能充分补充人体每日胶原蛋白的流失量。胶原蛋白分子量在1000道尔顿以下，属超级小分子，纯净，天然，无激素，可充分被机体吸收，达到人体补充胶原蛋白的最佳效果。', '11', '1', '12', '1', '1446834737', '1451968367', '1447177048', '0', '11', null, null, '11', '/upload/20151108/20158669555573.jpg', null, '2', '', '', '', null, '', '0', '0', '0', '1456369156', '2', '98', null, null);
INSERT INTO `operation_products` VALUES ('4', '玉兰油水润两件套', '为肌肤迅速补充大量水分，持续锁水，仿佛让肌肤喝矿泉水般营养滋润！', '100', '1', '12', '1', '1446834832', '1451968354', '1447177187', '0', '4000008', null, null, '1', '/upload/20151108/20153381431451.jpg', null, '2', '', '', '', null, '', '0', '0', '0', '1456358400', '2', '78', null, null);
INSERT INTO `operation_products` VALUES ('5', '双立人套锅', 'ZWILLING Pro锅具隶属于双立人锅具产品系列。产品由曾为宝格丽、施华洛世奇等众多知名品牌提供经典设计的双立人首席设计师Matteo Thun操刀设计。他突破性将珠宝设计灵感融入其中，使ZWILLING Pro系列产品简约而又优雅。产品独有的“超镜”表面，以无可比拟的镜光效果点亮厨房，让美食成为放松心情，享受生活的方式。', '111', '1', '12', '1', '1446842768', '1456399249', '1447177690', '0', '111', null, null, '111', '/upload/20151108/20158735869009.jpg', '', '2', '', '', '', null, '', '0', '0', '1', '1456369167', '2', '56', null, null);
INSERT INTO `operation_products` VALUES ('6', '锅具四件套', ' 罗麦璀璨系列锅具，是北京罗麦科技有限公司针对现代社会人们对厨房新的认识和对新的烹饪方式的追求，从消费者的健康需求的角度出发，有罗麦全资子公司，北京罗麦厨具有限公司自行设计生产。全套锅具的设计是秉承“用国际标准，造中国锅具”的设计思想，将中华传统饮食习惯与科学烹饪相结合，融合了更多的人性化的设计，是最新家居理念“有氧厨房”的必备用具，也是实用与时尚并重的系列新型环保高档锅具。\r\n      本系列锅具分为镜光和砂光面的两种：每一种，均由四个独立锅和十七个套装组件，共有二十一件单品组成。', '100', '1', '12', '1', '1446989037', '1456477493', '1447177782', '0', '10000', null, null, '1', '/upload/20151108/20158464727252.jpg', '/upload/20160225/20160638881620.jpg', '2', '', '', '', null, '', '0', '0', '0', '1456369112', '2', '100', null, null);
INSERT INTO `operation_products` VALUES ('7', '欧莱雅补水三件套', '欧莱雅（LOREAL）葡萄籽保湿女士套装 套装G三件套 葡萄籽 膜力水+洗面奶+滋润霜', '100', '1', '12', '1', '1446989122', '1456398764', '1447300855', '0', '1000', null, null, '1', '/upload/20151109/20150731631871.jpg', '/upload/20160225/20160608430856.jpg', '2', '', '', '', null, '', '0', '0', '1', '1456369131', '2', '1200', null, null);
INSERT INTO `operation_products` VALUES ('8', '玉兰油保湿套装', '内外兼修 多效修护内外兼修装 ，多效修护内外兼修装，内在滋润，外在修护', '10000', '4', '12', '0', '1450060423', '1456371535', '1450060378', '0', '1', null, null, '1', '/upload/20151214/20152773661026.jpg', null, '2', '', '', '', null, '', '0', '0', '1', '1456367588', '2', '1234', null, null);
INSERT INTO `operation_products` VALUES ('10', '钻石快线 心形黄金戒指 足金女戒 黄金戒指指环女款 活口可调节尺寸', '钻石快线 心形黄金戒指 足金女戒 黄金戒指指环女款 活口可调节尺寸\r\n约2.20-2.30克，下单送2016年台历，全场10元无门槛优惠券\r\n戒壁宽度约4毫米；厚度约1.5毫米\r\n定制15工作日内发货，现货3工作日内发货。默认为定制产品，产品不定时备有少量现货，如需现货请联系在线客服或致电客服热线4008309903。', '100', '5', '12', '0', '1450853343', '1450853343', '1450853255', '0', '999999', null, null, '2', '/upload/20151223/20150626893481.png', null, '2', null, null, null, '2', '', '0', '0', '0', '1450861200', '2', '1000', null, null);
INSERT INTO `operation_products` VALUES ('11', '测试', '测试测试测试测试测试', '1000', '1', '12', '9', '1456383088', '1456483796', '1456339888', '0', '388', null, null, '5', '/upload/20160225/20160809247483.jpg', '/upload/20160226/20160974879676.jpg', '2', '', '', '', null, '', '0', '0', '1', '1458931888', '2', '2345', null, null);
INSERT INTO `operation_products` VALUES ('12', '测试1', '测试1测试1测试1测试1测试1', '1200', '1', '12', '1', '1456383614', '1456727415', '1454957913', '432000', '2', null, null, '5', '/upload/20160225/20161307190907.jpg', '/upload/20160225/20160682540552.jpg', '1', '索贝数码科技', '王大锤', '13546474757', '1', '早8:00-12:00', '0', '0', '1', '1458068330', '2', '1200', null, null);
INSERT INTO `operation_products` VALUES ('13', '二个人通过v', '色的人跟帖儿童', '54', '1', '12', '0', '1456814386', '1456814386', '1459405876', '0', '4545', null, null, '4', '/upload/20160301/20160932913822.jpg', '/upload/20160301/20160440581908.jpg', '2', '对方根本点让', '色热水', '134565656567', '2', '', '0', '0', '1', '1456813879', '2', '345', null, null);
INSERT INTO `operation_products` VALUES ('14', '测试2', '测试2测试2测试2测试2测试2测试2', '12345', '1', '12', '0', '1456814591', '1456814591', '1456814544', '0', '12345', null, null, '3', '/upload/20160301/20161120645966.jpg', '/upload/20160301/20160333723030.jpg', '1', '谁v对方根本对方根本电饭锅', '对方把电', '13456767890', '2', '', '0', '0', '1', '1458801751', '2', '12345', null, null);
INSERT INTO `operation_products` VALUES ('15', '谁点水电费v水电费', '大丰v谁吧', '45', '1', '12', '0', '1456815017', '1456815493', '1456771763', '0', '5656', null, null, '56', '/upload/20160301/20161095401533.jpg', '/upload/20160301/20161158738454.jpg', '1', '黑人和认同呢', '对方根本发', '135678677877', '2', '', '0', '0', '1', '1458758965', '2', '565', '1456944572', '1458326974');
INSERT INTO `operation_products` VALUES ('16', 'sdfdr', '对方根本电饭锅', '45', '1', '12', '5', '1456827918', '1457681220', '1457475895', '0', '4545', null, null, '45', '/upload/20160301/20160427914878.jpg', '/upload/20160301/20161121973251.jpg', '1', '谁电饭锅白癜风如果行事风格', '提供的', '156678987677', null, '', '0', '0', '0', '1459463097', '2', '45456', '1458674801', '1459365993');

-- ----------------------------
-- Table structure for operation_product_imgs
-- ----------------------------
DROP TABLE IF EXISTS `operation_product_imgs`;
CREATE TABLE `operation_product_imgs` (
  `id` int(11) NOT NULL COMMENT '产品id',
  `content` text COMMENT '图片内容',
  `count` int(5) NOT NULL COMMENT '图片数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of operation_product_imgs
-- ----------------------------
INSERT INTO `operation_product_imgs` VALUES ('1', 'a:3:{i:0;s:35:\"/upload/20160105/20168643742618.jpg\";i:1;s:35:\"/upload/20160105/20165298673529.jpg\";i:2;s:35:\"/upload/20160105/20168776098201.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('3', 'a:3:{i:0;s:35:\"/upload/20160105/20161128227491.jpg\";i:1;s:35:\"/upload/20160105/20162997648958.jpg\";i:2;s:35:\"/upload/20160105/20163513765782.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('4', 'a:3:{i:0;s:35:\"/upload/20151209/20155619247402.jpg\";i:1;s:35:\"/upload/20151209/20151798884510.jpg\";i:2;s:35:\"/upload/20151209/20150076629668.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('5', 'a:3:{i:0;s:35:\"/upload/20160105/20165387865356.jpg\";i:1;s:35:\"/upload/20160105/20161390674524.jpg\";i:2;s:35:\"/upload/20160105/20161277101757.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('6', 'a:3:{i:0;s:35:\"/upload/20160105/20160465560290.jpg\";i:1;s:35:\"/upload/20160105/20161899648620.jpg\";i:2;s:35:\"/upload/20160105/20165900663188.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('7', 'a:3:{i:0;s:35:\"/upload/20160105/20166157742664.jpg\";i:1;s:35:\"/upload/20160105/20162264547706.jpg\";i:2;s:35:\"/upload/20160105/20164859200399.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('8', 'a:3:{i:0;s:35:\"/upload/20160105/20165675723175.jpg\";i:1;s:35:\"/upload/20160105/20167274176985.jpg\";i:2;s:35:\"/upload/20160105/20169465952683.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('9', 'a:4:{i:0;s:35:\"/upload/20151223/20152327321441.png\";i:1;s:35:\"/upload/20151223/20156100607975.png\";i:2;s:35:\"/upload/20151223/20152235217597.png\";i:3;s:35:\"/upload/20151223/20155499306200.png\";}', '4');
INSERT INTO `operation_product_imgs` VALUES ('10', 'a:5:{i:0;s:35:\"/upload/20151223/20156029109596.png\";i:1;s:35:\"/upload/20151223/20159900163197.png\";i:2;s:35:\"/upload/20151223/20158134686728.png\";i:3;s:35:\"/upload/20151223/20159946605958.png\";i:4;s:35:\"/upload/20151223/20155794481108.png\";}', '5');
INSERT INTO `operation_product_imgs` VALUES ('11', 'a:4:{i:0;s:35:\"/upload/20160225/20160572455082.jpg\";i:1;s:35:\"/upload/20160225/20160656694389.jpg\";i:2;s:35:\"/upload/20160225/20160009888012.jpg\";i:3;s:35:\"/upload/20160225/20160196046066.jpg\";}', '4');
INSERT INTO `operation_product_imgs` VALUES ('12', 'a:4:{i:0;s:35:\"/upload/20160225/20160970423665.jpg\";i:1;s:35:\"/upload/20160225/20160129115043.jpg\";i:2;s:35:\"/upload/20160225/20160670042304.jpg\";i:3;s:35:\"/upload/20160225/20160266570910.jpg\";}', '4');
INSERT INTO `operation_product_imgs` VALUES ('13', 'a:2:{i:0;s:35:\"/upload/20160301/20160504793338.jpg\";i:1;s:35:\"/upload/20160301/20160881561330.jpg\";}', '2');
INSERT INTO `operation_product_imgs` VALUES ('14', 'a:3:{i:0;s:35:\"/upload/20160301/20160419829421.jpg\";i:1;s:35:\"/upload/20160301/20160154425174.jpg\";i:2;s:35:\"/upload/20160301/20160806189419.jpg\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('15', 'a:2:{i:0;s:35:\"/upload/20160301/20161205437549.jpg\";i:1;s:35:\"/upload/20160301/20160057188755.jpg\";}', '2');
INSERT INTO `operation_product_imgs` VALUES ('16', 'a:1:{i:0;s:35:\"/upload/20160301/20160025787356.jpg\";}', '1');
INSERT INTO `operation_product_imgs` VALUES ('17', 'a:3:{i:0;s:35:\"/upload/20151231/20151632029386.png\";i:1;s:35:\"/upload/20151231/20151522818976.png\";i:2;s:35:\"/upload/20151231/20155802135327.png\";}', '3');
INSERT INTO `operation_product_imgs` VALUES ('18', 'a:3:{i:0;s:35:\"/upload/20151231/20158386095105.jpg\";i:1;s:35:\"/upload/20151231/20158599699018.jpg\";i:2;s:35:\"/upload/20151231/20154396355897.jpg\";}', '3');

-- ----------------------------
-- Table structure for operation_product_logs
-- ----------------------------
DROP TABLE IF EXISTS `operation_product_logs`;
CREATE TABLE `operation_product_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `user_name` varchar(60) DEFAULT NULL COMMENT '用户名',
  `mobile_phone` varchar(11) DEFAULT NULL COMMENT '用户手机号码',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `activity_name` varchar(255) NOT NULL COMMENT '活动名称',
  `product_name` varchar(150) DEFAULT NULL COMMENT '商品名称',
  `credits` int(11) NOT NULL COMMENT '花费积分',
  `code` varchar(32) DEFAULT '' COMMENT '兑换码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '兑换状态（1：未兑换，2：已兑换）',
  `runner_id` int(11) DEFAULT '0' COMMENT '操作者id',
  `runner_name` varchar(150) DEFAULT NULL COMMENT '操作者昵称',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `code` (`code`),
  KEY `product_name` (`product_name`),
  KEY `create_time` (`create_time`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品和用户兑换关系表';

-- ----------------------------
-- Records of operation_product_logs
-- ----------------------------

-- ----------------------------
-- Table structure for operation_sensitive_words
-- ----------------------------
DROP TABLE IF EXISTS `operation_sensitive_words`;
CREATE TABLE `operation_sensitive_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sensitive` varchar(255) NOT NULL COMMENT '敏感词',
  `replace_word` varchar(255) DEFAULT NULL COMMENT '被替换词',
  `modified` date DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of operation_sensitive_words
-- ----------------------------
INSERT INTO `operation_sensitive_words` VALUES ('4', 'df', 'sdf', null, null);
INSERT INTO `operation_sensitive_words` VALUES ('6', 'fuck', 'f**k', '2015-08-10', '1439189538');
INSERT INTO `operation_sensitive_words` VALUES ('7', 't', 'aa', '2015-08-10', '1439190586');
INSERT INTO `operation_sensitive_words` VALUES ('8', 'ssd', 'aaa', null, null);
INSERT INTO `operation_sensitive_words` VALUES ('9', 'e', 'gfd', '2015-08-13', '1439452708');
INSERT INTO `operation_sensitive_words` VALUES ('10', 'CAN', '*', '2015-08-13', '1439464148');
INSERT INTO `operation_sensitive_words` VALUES ('11', '评论', '**', '2015-08-13', '1439464681');

-- ----------------------------
-- Table structure for operation_shake_activities
-- ----------------------------
DROP TABLE IF EXISTS `operation_shake_activities`;
CREATE TABLE `operation_shake_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_type` tinyint(2) DEFAULT NULL COMMENT '选择活动类型(app活动专属)',
  `activity_id` int(11) DEFAULT NULL COMMENT '选择活动id(app活动专属)',
  `company_id` int(11) DEFAULT NULL COMMENT '所属单位id（TV活动专属）',
  `programme_id` int(11) DEFAULT NULL COMMENT '所属节目组id（TV活动专属）',
  `logo` varchar(255) DEFAULT NULL COMMENT '台标（TV活动专属）',
  `name` varchar(60) NOT NULL COMMENT '活动名称',
  `type` tinyint(2) NOT NULL COMMENT '类型（1：app活动，2：tv活动，3：现场活动）',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `create_by` int(11) NOT NULL COMMENT '创建人',
  `desc` varchar(100) NOT NULL COMMENT '简介',
  `is_creditprize` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否有积分奖品（0：没有，1：有）',
  `is_otherprize` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否有其他奖品（0：没有，1：有）',
  `participate_times` int(11) NOT NULL COMMENT '每个用户参与次数',
  `share_times` int(11) DEFAULT NULL COMMENT '分享获得的参与次数',
  `participate_groups` varchar(100) DEFAULT '0' COMMENT '允许参与活动的用户组（用户组id用逗号间隔，0为全部）',
  `credits1` int(11) DEFAULT NULL COMMENT '参与活动获得积分1',
  `credits2` int(11) DEFAULT NULL COMMENT '参与活动获得积分2',
  `credits3` int(11) DEFAULT NULL COMMENT '参与活动获得积分3',
  `credits4` int(11) DEFAULT NULL COMMENT '参与活动消耗积分1',
  `credits5` int(11) DEFAULT NULL COMMENT '参与活动消耗积分2',
  `credits6` int(11) DEFAULT NULL COMMENT '参与活动消耗积分3',
  `address` varchar(255) DEFAULT '' COMMENT '现场摇一摇地址',
  `rules` text COMMENT '活动规则',
  `range` int(11) DEFAULT NULL COMMENT '活动范围',
  `longitude` varchar(15) DEFAULT NULL COMMENT '位置经度',
  `interval_time` int(11) NOT NULL DEFAULT '0' COMMENT '摇奖间隔时间（0：不间隔）',
  `latitude` varchar(15) DEFAULT NULL COMMENT '位置纬度',
  `thumb_img` varchar(255) DEFAULT NULL COMMENT '活动缩略图',
  `banner_img` varchar(255) DEFAULT NULL COMMENT 'banner图',
  `validity_time` int(11) NOT NULL COMMENT '活动有效期',
  `accept_way` tinyint(2) NOT NULL COMMENT '领取方式（1：自领，2：快递）',
  `accept_addr` varchar(255) NOT NULL COMMENT '领奖地址',
  `link_man` varchar(50) DEFAULT NULL COMMENT '联系人',
  `link_phone` varchar(200) DEFAULT NULL COMMENT '联系电话',
  `accept_time_type` tinyint(2) DEFAULT NULL COMMENT '领奖时间（1：工作日，2：不限）',
  `accept_time_desc` varchar(255) DEFAULT NULL COMMENT '领奖时间详细',
  `member_nums` int(11) DEFAULT '0' COMMENT '参与用户数量',
  `is_login` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否允许未登录摇奖（1：允许，2：不允许））',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态（1，待发布，2进行中，3已结束，4已撤销）',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE,
  KEY `activity_type` (`activity_type`) USING BTREE,
  KEY `company_id` (`company_id`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `start_time` (`start_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='摇一摇活动表';

-- ----------------------------
-- Records of operation_shake_activities
-- ----------------------------
INSERT INTO `operation_shake_activities` VALUES ('1', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1441107390', '123123', '简介', '0', '0', '30', '3', '1005', '1', '0', '0', '0', '0', '0', '', '先擦速度', '100', '104.070434', '60', '30.576326', '/upload/20150810/20150188108013.jpg', '/upload/20150810/20151383184874.jpg', '864000', '1', '三大', '阿斯顿', '阿斯顿', '1', '按时打算', '0', '1', '2', '0', '1441009602');
INSERT INTO `operation_shake_activities` VALUES ('2', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '3', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('3', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '3', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('4', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '4', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('5', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '1', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('7', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '3', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('8', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '3', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('9', '0', null, null, null, 'http://www.baidu.com', '测试摇一摇活动', '3', '1437465583', '1437467453', '123123', '简介', '0', '0', '5', '3', '0', '0', '0', '0', '0', '0', '0', '', '先擦速度', '1', '1', '0', '按时打算', '阿斯顿', '阿斯顿', '0', '0', '三大', '阿斯顿', '阿斯顿', '0', '按时打算', '0', '1', '3', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('14', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1538325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '30', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '1', '2', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('15', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '30', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '2', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('16', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '30', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '2', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('17', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '30', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '2', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('18', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '30', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '2', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('19', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '9590400', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '3', '0', '1440049214');
INSERT INTO `operation_shake_activities` VALUES ('20', null, null, null, null, null, '测试活动添加', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '30', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '2', '0', '0');
INSERT INTO `operation_shake_activities` VALUES ('21', null, null, null, null, null, '测试活动添加111111111', '3', '1438066674', '1442300274', '0', '213123123123', '0', '0', '10', '1', '2', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '72000', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '950400', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '2', '1438245549', '1440143845');
INSERT INTO `operation_shake_activities` VALUES ('37', '3', null, null, null, null, '测试整个活动', '3', '1438245892', '1438332292', '0', '这是活动简介，补充的哈哈哈哈哈哈哈', '0', '0', '10', '1', '0', '1000', '100', null, null, null, null, '', '看哈发生地卡个速度呀', '100', '104.041429', '12960000', '30.7077', '/upload/20150729/20150622783240.png', '/upload/20150729/20150436299274.png', '30', '1', '按时打算大', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '1', '1', '1438243832', '1438243832');
INSERT INTO `operation_shake_activities` VALUES ('38', '3', null, null, null, null, '测试草搞箱', '3', '1438160880', '1438938480', '0', '', '0', '0', '10', '1', '0', '100', '100', null, null, null, null, '', '合肥看到过', '100', '104.028781', '1200', '30.700248', '/upload/20150729/20150057904873.png', '/upload/20150729/20150292884802.png', '30', '1', '下次行啊的方式地方', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '1', '5', '1438161181', '1438161181');
INSERT INTO `operation_shake_activities` VALUES ('39', null, null, '3', '14', '/upload/20150731/20150113560070.jpg', '测试TV活动', '2', '1438222139', '1447211339', '0', '测试TV活动', '0', '0', '10', '1', '2', '200', null, null, null, null, null, '', '啦啦啦啦啦啦啦啦', null, null, '3600', null, '/upload/20150731/20150403382244.jpg', '/upload/20150731/20150246013525.jpg', '259200', '1', '成都市此还上爱上打开', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '1', '2', '1438308662', '1440143939');
INSERT INTO `operation_shake_activities` VALUES ('40', null, null, '2', '10', '/upload/20150812/20150798077098.png', '测试TV活动添加', '2', '1442456095', '1440728095', '0', '活动简介', '0', '0', '10', '1', '2', '200', null, null, null, null, null, '', '活动规则', null, null, '10800', null, '/upload/20150731/20150295561499.jpg', '/upload/20150731/20151122618551.jpg', '432000', '2', '', '', '', null, '', '0', '1', '1', '1440048924', '1440143890');
INSERT INTO `operation_shake_activities` VALUES ('41', null, null, '3', '14', 'http://www.shake.cn/upload/20150731/20150818228199.jpg', '测试TV活动返回', '2', '1438136626', '1438395826', '0', '测试返回简介', '0', '0', '10', '1', '0', '1000', null, null, null, null, null, '', '啊是大叔大叔的', null, null, '10800', null, '/upload/20150731/20151140216642.jpg', '/upload/20150731/20150029241434.jpg', '172800', '2', '', '', '', '0', '', '0', '1', '3', '1439364825', '1439364825');
INSERT INTO `operation_shake_activities` VALUES ('42', '3', '40', null, null, null, '测试APP活动添加', '1', '0', '1440599990', '0', '', '0', '0', '0', null, '0', null, null, null, null, null, null, '', null, null, null, '0', null, null, null, '0', '0', '', '', '', '0', null, '0', '1', '5', '1438594402', '1438594402');
INSERT INTO `operation_shake_activities` VALUES ('43', null, null, null, null, null, '测试活动统计', '3', '1438580126', '1440048926', '0', '测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计测试活动统计', '0', '0', '1', '1', '2', '12', '100', null, null, null, null, '', '啊是大叔大叔的', '200', '104.037979', '3600', '30.6759', '/upload/20150804/20150578387774.jpg', '/upload/20150804/20150425128247.jpg', '2147483647', '1', '打发打发的武器额', '按时打算大', '12312312312313', '1', '请问请问', '0', '1', '3', '1438666685', '1440143796');
INSERT INTO `operation_shake_activities` VALUES ('44', null, null, null, null, null, '测试活动', '3', '1438066674', '1438325874', '0', '', '0', '0', '10', '1', '0', '100', '200', null, null, null, null, '', '的风格的风格的风格', '100', '104.03223', '20', '30.674409', '/upload/20150729/20151348403019.png', '/upload/20150729/20151135912233.png', '1728000', '1', '的风格都是对方身份', '张三', '1231231231233', '1', '上午9点到下午10点', '0', '2', '3', '0', '1438761847');
INSERT INTO `operation_shake_activities` VALUES ('45', '5', '2', null, null, null, '测试APP活动添加', '1', '0', '1439558854', '0', '', '0', '0', '0', null, '0', null, null, null, null, null, null, '', null, null, null, '0', null, null, null, '0', '0', '', '', '', '0', null, '0', '1', '4', '1438759669', '1438759669');
INSERT INTO `operation_shake_activities` VALUES ('46', '5', '2', null, null, null, '测试添加商品', '1', '1438936151', '1439994545', '0', '', '0', '0', '0', null, '0', null, null, null, null, null, null, '', null, null, null, '0', null, null, null, '0', '0', '', '', '', '0', null, '0', '1', '1', '1438936151', '1438936151');
INSERT INTO `operation_shake_activities` VALUES ('47', null, null, null, null, null, '测试活动地址', '3', '1439188406', '1440138806', '0', '测试活动地址测试活动地址测试活动地址测试活动地址', '0', '0', '1', '1', '2', '100', null, null, null, null, null, '四川省成都市', '啊的看法U客观地啊发呆卡U发生地啊', '200', '104.071054', '3600', '30.576232', '/upload/20150811/20151318193197.png', '/upload/20150811/20150117735188.png', '1900800', '1', '快点开始覅大概覅', '按时打算大', '12312312312313', '1', '请问请问', '0', '1', '3', '1439274971', '1440143778');

-- ----------------------------
-- Table structure for operation_shake_activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `operation_shake_activity_logs`;
CREATE TABLE `operation_shake_activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL COMMENT '活动id',
  `prize_id` int(11) NOT NULL DEFAULT '0' COMMENT '奖品id',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_mobile_phone` varchar(11) DEFAULT NULL COMMENT '用户手机号',
  `code` varchar(32) DEFAULT NULL COMMENT '兑换码',
  `reward_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '兑换状态（1：未兑换，2：已兑换）',
  `hit_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '中奖状态（2：中奖，1：未中奖）',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `activity_id` (`activity_id`),
  KEY `prize_id` (`prize_id`),
  KEY `reward_status` (`reward_status`),
  KEY `create_time` (`create_time`),
  KEY `hit_status` (`hit_status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='摇一摇用户中奖关系表';

-- ----------------------------
-- Records of operation_shake_activity_logs
-- ----------------------------
INSERT INTO `operation_shake_activity_logs` VALUES ('1', '1', '1', '1', 'hyh', '17211111111', 'asdfasdfasdfasdfwer', '1', '2', '1111111111', '1111111111');
INSERT INTO `operation_shake_activity_logs` VALUES ('2', '1', '2', '1', 'hyh', '17211111111', 'werwerwerwe', '1', '2', '1111111111', '1111111111');
INSERT INTO `operation_shake_activity_logs` VALUES ('6', '1', '1', '1', 'hyh', '13711111111', '6719dfd6532b9b1127abd66bfd7c3b5d', '1', '2', '1438587777', '1438587777');
INSERT INTO `operation_shake_activity_logs` VALUES ('7', '1', '1', '1', 'hyh', '13711111111', '2297c29fcc3f2bdaca28993ee1dfefc8', '1', '2', '1438587859', '1438587859');
INSERT INTO `operation_shake_activity_logs` VALUES ('8', '1', '1', '0', '', '', '755dfd42b1e9adc9a1a6bb8fd539906b', '1', '2', '1438590011', '1438590011');

-- ----------------------------
-- Table structure for operation_shake_prizes
-- ----------------------------
DROP TABLE IF EXISTS `operation_shake_prizes`;
CREATE TABLE `operation_shake_prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT '奖品名称',
  `activityid` int(11) NOT NULL COMMENT '摇一摇活动id',
  `type` tinyint(2) NOT NULL COMMENT '奖品类型（1：积分，2：其他奖品）',
  `quantity` int(11) DEFAULT NULL COMMENT '奖品总数',
  `win_quantity` int(11) NOT NULL DEFAULT '0' COMMENT '已被抽中的奖品数量',
  `exchange_quantity` int(11) NOT NULL DEFAULT '0' COMMENT '已兑换的数量',
  `number` int(11) DEFAULT '1' COMMENT '奖品数量（积分专用：一份奖品中的积分数量）',
  `level_name` varchar(60) DEFAULT NULL COMMENT '奖项名称',
  `thumb_img` varchar(255) DEFAULT NULL COMMENT '其他奖品专用（奖品缩略图）',
  `probability_x` int(11) DEFAULT NULL COMMENT '中奖几率分子',
  `probability_y` int(11) DEFAULT NULL COMMENT '中奖几率分母',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `modify_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `activityid` (`activityid`),
  KEY `level_name` (`level_name`),
  KEY `create_time` (`create_time`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COMMENT='摇一摇奖品表';

-- ----------------------------
-- Records of operation_shake_prizes
-- ----------------------------
INSERT INTO `operation_shake_prizes` VALUES ('1', '奖品一', '14', '2', '10', '7', '0', '1', '一等奖', '1.jpg', '500000', '1000000', '11111111', '11111111');
INSERT INTO `operation_shake_prizes` VALUES ('2', '奖品二', '14', '1', '100', '5', '0', '100', '二等奖', '2.jpg', '500000', '1000000', '11111111', '11111111');
INSERT INTO `operation_shake_prizes` VALUES ('7', '积分', '43', '1', '10', '0', '0', '10', '1', null, '1', '10000', '1440143796', '1440143796');
INSERT INTO `operation_shake_prizes` VALUES ('8', '积分', '43', '1', '123', '0', '0', '1', '2', null, '12', '10000', '1440143796', '1440143796');
INSERT INTO `operation_shake_prizes` VALUES ('9', '电饭煲', '43', '2', '12', '0', '0', '1', '', '/upload/20150804/20150698526722.jpg', '500000', '10000', '1440143796', '1440143796');
INSERT INTO `operation_shake_prizes` VALUES ('10', '积分', '44', '1', '123', '0', '0', '12', '1', null, '12', '123123', '1438761847', '1438761847');
INSERT INTO `operation_shake_prizes` VALUES ('11', '电饭煲', '44', '2', '12', '0', '0', '1', '', '/upload/20150729/20150971865237.png', '1', '100000000', '1438761847', '1438761847');
INSERT INTO `operation_shake_prizes` VALUES ('26', '积分', '41', '1', '10', '0', '0', '10', '1', null, '8000', '10000', '1439364825', '1439364825');
INSERT INTO `operation_shake_prizes` VALUES ('27', '苹果电脑', '41', '2', '12', '0', '0', '1', '', '/upload/20150729/20150971865237.png', '9000', '10000', '1439364825', '1439364825');
INSERT INTO `operation_shake_prizes` VALUES ('34', '积分', '47', '1', '123', '0', '0', '12', '一等奖', null, '12', '10000', '1440143778', '1440143778');
INSERT INTO `operation_shake_prizes` VALUES ('35', '电饭煲333', '47', '2', '10000', '0', '0', '1', '一等奖', '/upload/20150729/20150971865237.png', '1', '10000', '1440143778', '1440143778');
INSERT INTO `operation_shake_prizes` VALUES ('80', '积分', '1', '1', '10', '0', '0', '10', '1', null, '10', '10000', '1441009602', '1441009602');
INSERT INTO `operation_shake_prizes` VALUES ('81', '积分', '1', '1', '20', '0', '0', '20', '2', null, '10', '10000', '1441009602', '1441009602');
INSERT INTO `operation_shake_prizes` VALUES ('82', '积分', '1', '1', '30', '1', '0', '30', '3', null, '10000', '10000', '1441009602', '1441009602');
INSERT INTO `operation_shake_prizes` VALUES ('83', '笔记本电脑', '1', '2', '1', '0', '0', '1', '一等奖', '/upload/20150810/20150317568437.jpg', '1', '10000', '1441009602', '1441009602');
INSERT INTO `operation_shake_prizes` VALUES ('84', '电饭煲', '1', '2', '2', '0', '0', '1', '一等奖', '/upload/20150810/20150807666755.jpg', '2', '10000', '1441009602', '1441009602');
INSERT INTO `operation_shake_prizes` VALUES ('85', '电视机', '1', '2', '300', '0', '0', '1', '一等奖', '/upload/20150810/20151039965094.jpg', '5', '10000', '1441009602', '1441009602');
INSERT INTO `operation_shake_prizes` VALUES ('90', '电饭煲', '47', '2', '20', '0', '0', '1', '一等奖', '/upload/20150729/20150331627500.png', '10', '10000', '1440143778', '1440143778');
INSERT INTO `operation_shake_prizes` VALUES ('99', '积分', '40', '1', '10', '0', '0', '10123123', '一等奖', null, '8000', '10000', '1440143890', '1440143890');
INSERT INTO `operation_shake_prizes` VALUES ('100', '积分', '40', '1', '123', '0', '0', '12', '1', null, '10', '10000', '1440143890', '1440143890');
INSERT INTO `operation_shake_prizes` VALUES ('101', '苹果电脑223414124', '40', '2', '12', '0', '0', '1', '一等奖', '/upload/20150729/20150971865237.png', '9000', '10000', '1440143890', '1440143890');
INSERT INTO `operation_shake_prizes` VALUES ('102', '电视机', '40', '2', '20', '0', '0', '1', '', '/upload/20150731/20151258304364.png', '10', '10000', '1440143890', '1440143890');
INSERT INTO `operation_shake_prizes` VALUES ('103', '积分', '19', '1', '1', '0', '0', '10', '一等奖', null, '10', '10000', '1440049214', '1440049214');
INSERT INTO `operation_shake_prizes` VALUES ('104', '积分', '19', '1', '10', '0', '0', '12', '1', null, '12', '10000', '1440049214', '1440049214');
INSERT INTO `operation_shake_prizes` VALUES ('105', '电饭煲', '19', '2', '10000', '0', '0', '1', '', '/upload/20150729/20150428289048.png', '10', '10000', '1440049214', '1440049214');
INSERT INTO `operation_shake_prizes` VALUES ('106', '积分', '21', '1', '10', '0', '0', '10', '一等奖', null, '12', '10000', '1440143845', '1440143845');
INSERT INTO `operation_shake_prizes` VALUES ('107', '电饭煲', '21', '2', '20', '0', '0', '1', '', '/upload/20150729/20150971865237.png', '10', '10000', '1440143845', '1440143845');
INSERT INTO `operation_shake_prizes` VALUES ('108', '积分', '39', '1', '10', '0', '0', '10', '一等奖', null, '10', '10000', '1440143939', '1440143939');
INSERT INTO `operation_shake_prizes` VALUES ('109', '苹果电脑23444', '39', '2', '20', '0', '0', '1', '一等奖', '/upload/20150729/20150331627500.png', '10', '10000', '1440143939', '1440143939');

-- ----------------------------
-- Table structure for operation_tvs
-- ----------------------------
DROP TABLE IF EXISTS `operation_tvs`;
CREATE TABLE `operation_tvs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '栏目或者电视台名称',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '栏目所属电视台id（电视台该字段为0）',
  `type` tinyint(2) NOT NULL COMMENT '类型（1：电视台，2：栏目）',
  `is_show` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否显示（0：不显示，1：显示）',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='摇一摇tv活动（电视台基础数据）';

-- ----------------------------
-- Records of operation_tvs
-- ----------------------------
INSERT INTO `operation_tvs` VALUES ('1', '四川卫视', '0', '1', '1', '2015-07-21 08:54:26', '2015-07-21 08:54:26');
INSERT INTO `operation_tvs` VALUES ('2', '湖南卫视', '0', '1', '1', '2015-07-21 08:54:26', '2015-07-21 08:54:26');
INSERT INTO `operation_tvs` VALUES ('3', '浙江卫视', '0', '1', '1', '2015-07-21 08:54:26', '2015-07-21 08:54:26');
INSERT INTO `operation_tvs` VALUES ('4', '天津卫视', '0', '1', '1', '2015-07-21 08:54:26', '2015-07-21 08:54:26');
INSERT INTO `operation_tvs` VALUES ('5', '四川新闻', '1', '2', '1', '2015-07-21 08:54:26', '2015-07-21 08:54:26');
INSERT INTO `operation_tvs` VALUES ('6', '天气预报', '1', '2', '1', '2015-07-21 08:54:26', '2015-07-21 08:54:26');
INSERT INTO `operation_tvs` VALUES ('7', '生财有道', '1', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('8', '今日视点', '1', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('9', '好好学吧', '2', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('10', '变形记', '2', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('11', '天天向上', '2', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('12', '粑粑去哪儿', '2', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('13', '奔跑吧兄弟', '3', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('14', '海洋预报', '3', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('15', '娱乐梦工厂', '3', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('16', '今日聚焦', '3', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('17', '藏龙卧虎', '4', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('18', '科学大见闻', '4', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('19', '浙江卫视', '4', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
INSERT INTO `operation_tvs` VALUES ('20', '时代智商', '4', '2', '1', '2015-07-21 08:54:27', '2015-07-21 08:54:27');
