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


-- Table structure for operation_tvs
-- ----------------------------
ALTER TABLE `operation_products`
ADD COLUMN `rank`  int(11) NOT NULL DEFAULT 0 COMMENT '推荐排行' AFTER `order_end_time`

-- ----------------------------
