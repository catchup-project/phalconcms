CREATE TABLE IF NOT EXISTS `bingli_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `parentId` int(10) DEFAULT '0',
  `rootId` int(10) DEFAULT '0',
  `sortOrder` int(10) DEFAULT '0',
  `createdAt` int(10) DEFAULT NULL,
  `count` int(10) DEFAULT '0',
  `leftId` int(15) DEFAULT '0',
  `rightId` int(15) DEFAULT '0',
  `imageId` int(10) DEFAULT NULL,
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `bingli_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `flag` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visibility` enum('public','private','password') COLLATE utf8_unicode_ci NOT NULL,
  `codeType` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'markdown',
  `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'en',
  `parentId` int(10) DEFAULT '0',
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` int(10) DEFAULT '0',
  `createdAt` int(10) NOT NULL,
  `userId` int(10) DEFAULT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updatedAt` int(10) DEFAULT NULL,
  `editorId` int(10) DEFAULT NULL,
  `editorName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentStatus` enum('open','closed','authority') COLLATE utf8_unicode_ci DEFAULT 'open',
  `commentType` varchar(15) COLLATE utf8_unicode_ci DEFAULT 'local',
  `commentCount` int(10) DEFAULT '0',
  `count` bigint(20) DEFAULT '0',
  `imageId` int(10) DEFAULT NULL,
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sourceName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sourceUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `voteScore` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `createdAt` (`createdAt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bingli_tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tagName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `parentId` int(10) DEFAULT '0',
  `rootId` int(10) DEFAULT '0',
  `sortOrder` int(10) DEFAULT '0',
  `count` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bingli_tag_article` (
  `tagId` int(10) NOT NULL,
  `articletId` int(10) NOT NULL,
  PRIMARY KEY (`tagId`,`articletId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `bingli_texts` (
  `articleId` int(20) NOT NULL,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `toc` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `bingli_article_votes` (
  `articleId` int(10) NOT NULL,
  `upVote` int(10) NOT NULL,
  `downVote` int(10) NOT NULL,
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `bingli_article_votes_users` (
  `articleId` int(10) NOT NULL,
  `userId` int(10) NOT NULL,
  `voteType` enum('upVote','downVote') COLLATE utf8_unicode_ci DEFAULT 'upVote',
  `createdTime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `bingli_article_favors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL,
  `articleId` int(10) NOT NULL,
  `createdTime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId` (`userId`,`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
