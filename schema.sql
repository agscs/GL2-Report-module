CREATE TABLE IF NOT EXISTS `user_reports` (
  `UR_id` int(11) NOT NULL AUTO_INCREMENT,
  `UR_reported_by` int(11) DEFAULT '0',
  `UR_reported_user` int(11) DEFAULT '0',
  `UR_report_text` mediumtext,
  `UR_report_reason` int(11) DEFAULT '0',
  `UR_date` int(11) DEFAULT '0',
  PRIMARY KEY (`UR_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_reports_reasons` (
  `URR_id` int(11) NOT NULL AUTO_INCREMENT,
  `URR_name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`URR_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `user_reports_reasons` (`URR_id`, `URR_name`) VALUES
	(1, 'Other'),
	(2, 'Multiple Accounts'),
	(3, 'Account Sharing'),
	(4, 'Hacking'),
	(5, 'Ban Dispute'),
	(6, 'Warning Dispute'),
	(7, 'Spamming'),
	(8, 'Profile Violation'),
	(9, 'Forum Violation'),
	(10, 'Chat Violation'),
	(11, 'Harassment'),
	(12, 'Bug Abuse'),
	(13, 'Discrimination'),
	(14, 'Middlemanning'),
	(15, 'Staff Violation');
