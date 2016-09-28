-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2016-09-28 06:31:04
-- 伺服器版本: 5.7.11
-- PHP 版本： 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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

--
-- 資料表的匯出資料 `admin_info`
--

INSERT INTO `admin_info` (`uid`, `username`, `name`, `password`, `role`, `status`, `utime`, `ctime`, `ip`) VALUES
(0, 'admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 0, 1, 1475044139, 1473404039, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `power` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `utime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `admin_role`
--

INSERT INTO `admin_role` (`id`, `label`, `power`, `status`, `utime`, `ctime`) VALUES
(0, '超级管理员', '*', 1, 1475039129, 1475039129);

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

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`uid`);

--
-- 資料表索引 `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `token_key`
--
ALTER TABLE `token_key`
  ADD PRIMARY KEY (`id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用資料表 AUTO_INCREMENT `token_key`
--
ALTER TABLE `token_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
