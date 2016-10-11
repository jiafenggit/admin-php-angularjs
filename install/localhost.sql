SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- 資料庫： `userinfo`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin_info`
--

CREATE TABLE `admin_info` (
  `uid` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `utime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `ip` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `admin_info` (`uid`, `username`, `name`, `password`, `role`, `status`, `utime`, `ctime`, `ip`) VALUES
(0, 'admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 0, 1, 1475044139, 1473404039, 0);

ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`uid`);

ALTER TABLE `admin_info`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- 資料表結構 `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `router` varchar(6535) NOT NULL,
  `resource` varchar(6535) NOT NULL,
  `status` int(11) NOT NULL,
  `utime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `admin_role` (`id`, `label`, `router`,`resource`, `status`, `utime`, `ctime`) VALUES
(0, '超级管理员', '*', '*',1, 1475039129, 1475039129);

ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `admin_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------

--
-- 資料表結構 `token_key`
--

CREATE TABLE `token_key` (
  `id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `uid` int(11) NOT NULL,
  `utime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `token_key`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `token_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;



-- --------------------------------------------------------

--
-- 資料表結構 `resource_controller`
--

CREATE TABLE `resource_controller` (
  `id` int(11) NOT NULL,
  `controller` varchar(20) NOT NULL,
  `resource` varchar(20) NOT NULL,
  `tbl` varchar(20) NOT NULL,
  `model` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  `utime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `resource_controller` (`id`, `controller`, `resource`, `tbl`, `model`, `status`, `utime`, `ctime`) VALUES
(0, 'admin', 'users', 'admin_info', 'Admin_user_model', 1, 1473404039, 1473404039),
(1, 'admin', 'roles', 'admin_role', 'Admin_role_model', 1, 1473404039, 1473404039),

ALTER TABLE `resource_controller`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `resource_controller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2