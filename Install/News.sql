
DROP TABLE IF EXISTS `cms_news`;

CREATE TABLE `cms_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '简介',
  `detail_url` text NOT NULL COMMENT '文章链接',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `show_status` tinyint(11) NOT NULL COMMENT '显示状态 0/1 隐藏/显示',
  `thumb` text,
  `release_date` varchar(32) NOT NULL DEFAULT '' COMMENT '发布日期',
  `release_time` int(11) NOT NULL COMMENT '发布时间',
  `sort` int(11) NOT NULL COMMENT '序号',
  `author_id` int(11) NOT NULL COMMENT '作者ID',
  `author_name` varchar(64) NOT NULL DEFAULT '' COMMENT '作者名称',
  `images` text NOT NULL COMMENT '图片列表',
  `view_amount` int(11) NOT NULL COMMENT '浏览数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;