-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2023 at 06:17 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sponzy`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `update_length` int(10) UNSIGNED NOT NULL COMMENT 'The max length of updates',
  `status_page` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Offline, 1 Online',
  `email_verification` enum('0','1') NOT NULL COMMENT '0 Off, 1 On',
  `email_no_reply` varchar(200) NOT NULL,
  `email_admin` varchar(200) NOT NULL,
  `captcha` enum('on','off') NOT NULL DEFAULT 'on',
  `file_size_allowed` int(11) UNSIGNED NOT NULL COMMENT 'Size in Bytes',
  `google_analytics` text NOT NULL,
  `paypal_account` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `pinterest` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `google_adsense` text NOT NULL,
  `currency_symbol` char(10) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `min_subscription_amount` int(11) UNSIGNED NOT NULL,
  `payment_gateway` enum('PayPal','Stripe') NOT NULL DEFAULT 'PayPal',
  `min_width_height_image` varchar(100) NOT NULL,
  `fee_commission` int(10) UNSIGNED NOT NULL,
  `max_subscription_amount` int(10) UNSIGNED NOT NULL,
  `date_format` varchar(200) NOT NULL,
  `link_privacy` varchar(200) NOT NULL,
  `link_terms` varchar(200) NOT NULL,
  `currency_position` varchar(100) NOT NULL DEFAULT 'left',
  `facebook_login` enum('on','off') NOT NULL DEFAULT 'off',
  `amount_min_withdrawal` int(10) UNSIGNED NOT NULL,
  `youtube` varchar(200) NOT NULL,
  `github` varchar(200) NOT NULL,
  `comment_length` int(10) UNSIGNED NOT NULL,
  `days_process_withdrawals` int(10) UNSIGNED NOT NULL,
  `google_login` enum('on','off') NOT NULL DEFAULT 'off',
  `number_posts_show` tinyint(3) UNSIGNED NOT NULL,
  `number_comments_show` tinyint(3) UNSIGNED NOT NULL,
  `registration_active` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 No, 1 Yes',
  `account_verification` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 No, 1 Yes',
  `logo` varchar(100) NOT NULL,
  `logo_2` varchar(100) NOT NULL,
  `favicon` varchar(100) NOT NULL,
  `home_index` varchar(100) NOT NULL,
  `bg_gradient` varchar(100) NOT NULL,
  `img_1` varchar(100) NOT NULL,
  `img_2` varchar(100) NOT NULL,
  `img_3` varchar(100) NOT NULL,
  `img_4` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `show_counter` enum('on','off') NOT NULL DEFAULT 'on',
  `color_default` varchar(100) NOT NULL,
  `decimal_format` enum('comma','dot') NOT NULL DEFAULT 'dot',
  `version` varchar(5) NOT NULL,
  `link_cookies` varchar(200) NOT NULL,
  `story_length` int(10) UNSIGNED NOT NULL,
  `maintenance_mode` enum('on','off') NOT NULL DEFAULT 'off',
  `company` varchar(100) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip` varchar(100) NOT NULL,
  `vat` varchar(100) NOT NULL,
  `widget_creators_featured` enum('on','off') NOT NULL DEFAULT 'on',
  `home_style` int(10) UNSIGNED NOT NULL,
  `file_size_allowed_verify_account` int(10) UNSIGNED NOT NULL,
  `payout_method_paypal` enum('on','off') NOT NULL DEFAULT 'on',
  `payout_method_bank` enum('on','off') NOT NULL DEFAULT 'on',
  `min_tip_amount` int(10) UNSIGNED NOT NULL,
  `max_tip_amount` int(10) UNSIGNED NOT NULL,
  `min_ppv_amount` int(10) UNSIGNED NOT NULL,
  `max_ppv_amount` int(10) UNSIGNED NOT NULL,
  `min_deposits_amount` int(10) UNSIGNED NOT NULL,
  `max_deposits_amount` int(10) UNSIGNED NOT NULL,
  `button_style` enum('rounded','normal') NOT NULL DEFAULT 'rounded',
  `twitter_login` enum('on','off') NOT NULL DEFAULT 'off',
  `hide_admin_profile` enum('on','off') NOT NULL DEFAULT 'off',
  `requests_verify_account` enum('on','off') NOT NULL DEFAULT 'on',
  `navbar_background_color` varchar(30) NOT NULL,
  `navbar_text_color` varchar(30) NOT NULL,
  `footer_background_color` varchar(30) NOT NULL,
  `footer_text_color` varchar(30) NOT NULL,
  `preloading` enum('on','off') NOT NULL DEFAULT 'off',
  `preloading_image` varchar(100) NOT NULL,
  `watermark` enum('on','off') NOT NULL DEFAULT 'on',
  `earnings_simulator` enum('on','off') NOT NULL DEFAULT 'on',
  `custom_css` text NOT NULL,
  `custom_js` text NOT NULL,
  `alert_adult` enum('on','off') NOT NULL DEFAULT 'off',
  `genders` varchar(250) NOT NULL,
  `cover_default` varchar(100) NOT NULL,
  `who_can_see_content` enum('all','users') NOT NULL DEFAULT 'all',
  `users_can_edit_post` enum('on','off') NOT NULL DEFAULT 'on',
  `disable_wallet` enum('on','off') NOT NULL DEFAULT 'on',
  `disable_banner_cookies` enum('on','off') NOT NULL DEFAULT 'off',
  `wallet_format` enum('real_money','credits','points','tokens') NOT NULL DEFAULT 'real_money',
  `maximum_files_post` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `maximum_files_msg` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `announcement` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `announcement_show` varchar(100) NOT NULL,
  `announcement_cookie` varchar(20) NOT NULL,
  `limit_categories` int(10) UNSIGNED NOT NULL DEFAULT 3,
  `captcha_contact` enum('on','off') NOT NULL DEFAULT 'on',
  `disable_tips` enum('on','off') NOT NULL DEFAULT 'off',
  `payout_method_payoneer` enum('on','off') NOT NULL DEFAULT 'off',
  `payout_method_zelle` enum('on','off') NOT NULL DEFAULT 'off',
  `type_announcement` char(10) NOT NULL DEFAULT 'primary',
  `referral_system` enum('on','off') NOT NULL DEFAULT 'off',
  `auto_approve_post` enum('on','off') NOT NULL DEFAULT 'on',
  `watermark_on_videos` enum('on','off') NOT NULL DEFAULT 'on',
  `percentage_referred` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `referral_transaction_limit` char(10) NOT NULL DEFAULT '1',
  `video_encoding` enum('on','off') NOT NULL DEFAULT 'off',
  `live_streaming_status` enum('on','off') NOT NULL DEFAULT 'off',
  `live_streaming_minimum_price` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `live_streaming_max_price` int(10) UNSIGNED NOT NULL DEFAULT 100,
  `agora_app_id` varchar(200) NOT NULL,
  `tiktok` varchar(200) NOT NULL,
  `snapchat` varchar(200) NOT NULL,
  `limit_live_streaming_paid` int(10) UNSIGNED NOT NULL,
  `limit_live_streaming_free` int(10) UNSIGNED NOT NULL,
  `live_streaming_free` enum('0','1') NOT NULL DEFAULT '0',
  `type_withdrawals` char(50) NOT NULL DEFAULT 'custom',
  `shop` tinyint(1) NOT NULL DEFAULT 0,
  `min_price_product` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `max_price_product` int(10) UNSIGNED NOT NULL DEFAULT 100,
  `digital_product_sale` tinyint(1) NOT NULL DEFAULT 0,
  `custom_content` tinyint(1) NOT NULL DEFAULT 0,
  `tax_on_wallet` tinyint(1) NOT NULL DEFAULT 1,
  `stripe_connect` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `stripe_connect_countries` longtext NOT NULL,
  `physical_products` tinyint(1) NOT NULL DEFAULT 0,
  `disable_login_register_email` tinyint(1) NOT NULL DEFAULT 0,
  `disable_contact` tinyint(1) NOT NULL DEFAULT 0,
  `specific_day_payment_withdrawals` int(10) UNSIGNED NOT NULL,
  `disable_new_post_notification` tinyint(1) NOT NULL DEFAULT 0,
  `disable_search_creators` tinyint(1) NOT NULL DEFAULT 0,
  `search_creators_genders` tinyint(1) NOT NULL DEFAULT 0,
  `generate_qr_code` tinyint(1) NOT NULL DEFAULT 0,
  `autofollow_admin` tinyint(1) NOT NULL DEFAULT 0,
  `allow_zip_files` tinyint(1) NOT NULL DEFAULT 1,
  `reddit` varchar(200) NOT NULL,
  `telegram` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `push_notification_status` tinyint(1) NOT NULL DEFAULT 0,
  `onesignal_appid` varchar(150) NOT NULL,
  `onesignal_restapi` varchar(150) NOT NULL,
  `status_pwa` tinyint(1) NOT NULL DEFAULT 1,
  `zip_verification_creator` tinyint(1) NOT NULL DEFAULT 1,
  `amount_max_withdrawal` int(10) UNSIGNED NOT NULL,
  `story_status` tinyint(1) NOT NULL DEFAULT 0,
  `story_image` tinyint(1) NOT NULL DEFAULT 1,
  `story_text` tinyint(1) NOT NULL DEFAULT 1,
  `story_max_videos_length` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `payout_method_western_union` enum('on','off') NOT NULL DEFAULT 'off',
  `payout_method_crypto` enum('on','off') NOT NULL DEFAULT 'off',
  `threads` varchar(200) NOT NULL,
  `watermak_video` varchar(100) NOT NULL,
  `coconut_key` varchar(255) NOT NULL,
  `encoding_method` varchar(255) NOT NULL DEFAULT 'ffmpeg',
  `allow_scheduled_posts` tinyint(1) DEFAULT 0,
  `google_tag_manager_head` text NOT NULL,
  `google_tag_manager_body` text NOT NULL,
  `live_streaming_private` enum('on','off') NOT NULL DEFAULT 'off',
  `live_streaming_minimum_price_private` decimal(10,2) DEFAULT NULL,
  `live_streaming_max_price_private` decimal(10,2) DEFAULT NULL,
  `limit_live_streaming_private` int(10) UNSIGNED NOT NULL,
  `show_address_company_footer` tinyint(1) DEFAULT 0,
  `watermark_position` varchar(50) NOT NULL DEFAULT 'center'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `title`, `description`, `keywords`, `update_length`, `status_page`, `email_verification`, `email_no_reply`, `email_admin`, `captcha`, `file_size_allowed`, `google_analytics`, `paypal_account`, `twitter`, `facebook`, `pinterest`, `instagram`, `google_adsense`, `currency_symbol`, `currency_code`, `min_subscription_amount`, `payment_gateway`, `min_width_height_image`, `fee_commission`, `max_subscription_amount`, `date_format`, `link_privacy`, `link_terms`, `currency_position`, `facebook_login`, `amount_min_withdrawal`, `youtube`, `github`, `comment_length`, `days_process_withdrawals`, `google_login`, `number_posts_show`, `number_comments_show`, `registration_active`, `account_verification`, `logo`, `logo_2`, `favicon`, `home_index`, `bg_gradient`, `img_1`, `img_2`, `img_3`, `img_4`, `avatar`, `show_counter`, `color_default`, `decimal_format`, `version`, `link_cookies`, `story_length`, `maintenance_mode`, `company`, `country`, `address`, `city`, `zip`, `vat`, `widget_creators_featured`, `home_style`, `file_size_allowed_verify_account`, `payout_method_paypal`, `payout_method_bank`, `min_tip_amount`, `max_tip_amount`, `min_ppv_amount`, `max_ppv_amount`, `min_deposits_amount`, `max_deposits_amount`, `button_style`, `twitter_login`, `hide_admin_profile`, `requests_verify_account`, `navbar_background_color`, `navbar_text_color`, `footer_background_color`, `footer_text_color`, `preloading`, `preloading_image`, `watermark`, `earnings_simulator`, `custom_css`, `custom_js`, `alert_adult`, `genders`, `cover_default`, `who_can_see_content`, `users_can_edit_post`, `disable_wallet`, `disable_banner_cookies`, `wallet_format`, `maximum_files_post`, `maximum_files_msg`, `announcement`, `announcement_show`, `announcement_cookie`, `limit_categories`, `captcha_contact`, `disable_tips`, `payout_method_payoneer`, `payout_method_zelle`, `type_announcement`, `referral_system`, `auto_approve_post`, `watermark_on_videos`, `percentage_referred`, `referral_transaction_limit`, `video_encoding`, `live_streaming_status`, `live_streaming_minimum_price`, `live_streaming_max_price`, `agora_app_id`, `tiktok`, `snapchat`, `limit_live_streaming_paid`, `limit_live_streaming_free`, `live_streaming_free`, `type_withdrawals`, `shop`, `min_price_product`, `max_price_product`, `digital_product_sale`, `custom_content`, `tax_on_wallet`, `stripe_connect`, `stripe_connect_countries`, `physical_products`, `disable_login_register_email`, `disable_contact`, `specific_day_payment_withdrawals`, `disable_new_post_notification`, `disable_search_creators`, `search_creators_genders`, `generate_qr_code`, `autofollow_admin`, `allow_zip_files`, `reddit`, `telegram`, `linkedin`, `push_notification_status`, `onesignal_appid`, `onesignal_restapi`, `status_pwa`, `zip_verification_creator`, `amount_max_withdrawal`, `story_status`, `story_image`, `story_text`, `story_max_videos_length`, `payout_method_western_union`, `payout_method_crypto`, `threads`, `watermak_video`, `coconut_key`, `encoding_method`, `allow_scheduled_posts`, `google_tag_manager_head`, `google_tag_manager_body`, `live_streaming_private`, `live_streaming_minimum_price_private`, `live_streaming_max_price_private`, `limit_live_streaming_private`, `show_address_company_footer`, `watermark_position`) VALUES
(1, 'Sponzy', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut tortor rutrum massa efficitur tincidunt vel nec lacus. Curabitur porta aliquet diam, eu gravida neque lacinia.', 'donations,support,creators,Creaty,subscription', 250, '1', '0', 'no-reply@sponzy.net', 'admin@admin.com', 'off', 5120, '', 'paypal@yousite.com', '', '', '', '', '', '$', 'USD', 2, 'PayPal', '400', 5, 100, 'M d, Y', 'https://yousite.com/p/privacy', 'https://yousite.com/p/terms-of-service', 'left', 'off', 50, '', '', 150, 7, 'off', 5, 2, '1', '1', 'logo.png', 'logo-blue.png', 'favicon.png', 'home_index.png', 'bg-gradient.jpg', 'img_1.svg', 'img_2.svg', 'img_3.svg', 'img_4.png', 'default.jpg', 'off', '#450ea7', 'dot', '5.1', 'https://yousite.com/p/cookies', 500, 'off', '', '', '', '', '', '', 'off', 0, 1024, 'on', 'on', 1, 100, 5, 100, 5, 100, 'rounded', 'off', 'off', 'on', '#ffffff', '#3a3a3a', '#ffffff', '#5f5f5f', 'off', '', 'on', 'on', '', '', 'off', 'male,female', '', 'all', 'on', 'off', 'off', 'real_money', 5, 5, '', '', '', 3, 'on', 'off', 'off', 'off', 'primary', 'off', 'on', 'on', 5, '1', 'off', 'off', 5, 100, '', '', '', 0, 0, '0', 'custom', 0, 5, 100, 0, 0, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, '', '', '', 0, '', '', 0, 1, 0, 0, 1, 1, 30, 'off', 'off', '', 'watermak-video.png', '', 'ffmpeg', 0, '', '', 'off', NULL, NULL, 0, 0, 'center');

-- --------------------------------------------------------

--
-- Table structure for table `advertisings`
--

CREATE TABLE `advertisings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `image` varchar(150) NOT NULL,
  `clicks` int(10) UNSIGNED NOT NULL,
  `impressions` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ad_click_impressions`
--

CREATE TABLE `ad_click_impressions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advertisings_id` bigint(20) UNSIGNED NOT NULL,
  `type` char(20) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `image` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `updates_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `mode` enum('on','off') NOT NULL DEFAULT 'on',
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `keywords`, `mode`, `image`) VALUES
(1, 'Artist', 'artist', '', '', 'on', 'artist-By4bfBlXy75qPKK99RgC5MN9g8lUTWo8.jpg'),
(2, 'Designer', 'designer', '', '', 'on', 'designer-SP7jB58pOuQW7Wz3Tf2v32OOZrU6zgxh.jpg'),
(3, 'Drawing and Painting', 'drawing-and-painting', '', '', 'on', 'drawing-and-painting-sRKBmjTngYwmwZ5PZ2OIPr2K2MS5WT2Q.jpg'),
(4, 'Developer', 'developer', '', '', 'on', 'developer-fpZjQGTE9TCtNIkE3K7XmOyoFHsrcI7Q.jpg'),
(5, 'Animation', 'animation', 'Category Animation', 'animate, animation', 'on', 'animation-N600Gsc1uJffH9Jsdjo9kYpq3TKl7E9x.jpg'),
(7, 'Photography', 'photography', '', '', 'on', 'photography-Q8AA4mr4v3Y7AkNEj9dU46oHjTJqAWcO.jpg'),
(8, 'Video and Film', 'video-and-film', '', '', 'on', 'video-and-film-F4BHush4GW0y9WzMrxWCJXEoKMcZWZeZ.jpg'),
(9, 'Podcasts', 'podcasts', '', '', 'on', 'podcasts-ubacYB2BVCgzu79lqOXp9Zfa2jtjwgFE.jpg'),
(10, 'Others', 'others', '', '', 'on', 'others-PplPsJxWsOu7ex6qWqWdQtMhYDufxM2k.jpg'),
(12, 'Writing', 'writing', '', '', 'on', 'writing-Q5b6A8vlTiE4W1pokW1sR1TGxJigTLAv.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `updates_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Trash, 1 Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `updates_id`, `user_id`, `reply`, `date`, `status`) VALUES
(1, 2, 1, 'hi', '2023-12-05 04:59:25', '1');

-- --------------------------------------------------------

--
-- Table structure for table `comments_likes`
--

CREATE TABLE `comments_likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comments_id` int(10) UNSIGNED NOT NULL,
  `replies_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People\'s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People\'s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virgin Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `txn_id` varchar(200) NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','pending') NOT NULL DEFAULT 'active',
  `screenshot_transfer` varchar(100) NOT NULL,
  `percentage_applied` varchar(50) DEFAULT NULL,
  `transaction_fee` double(10,2) NOT NULL,
  `taxes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"e65a8655-8291-48bd-830c-11afb1fb0393\",\"displayName\":\"App\\\\Listeners\\\\NewPostListener\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":19:{s:5:\\\"class\\\";s:29:\\\"App\\\\Listeners\\\\NewPostListener\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:23:\\\"App\\\\Events\\\\NewPostEvent\\\":1:{s:4:\\\"post\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Updates\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1701762054, 1701762054),
(2, 'default', '{\"uuid\":\"92a3c56c-2878-4225-9ca6-0fbd3ae5ab82\",\"displayName\":\"App\\\\Listeners\\\\NewPostListener\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":19:{s:5:\\\"class\\\";s:29:\\\"App\\\\Listeners\\\\NewPostListener\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:23:\\\"App\\\\Events\\\\NewPostEvent\\\":1:{s:4:\\\"post\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Updates\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1701763153, 1701763153);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `abbreviation` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `abbreviation`) VALUES
(1, 'English', 'en'),
(2, 'Español', 'es');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `updates_id` int(11) UNSIGNED NOT NULL,
  `status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '0 trash, 1 active',
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `updates_id`, `status`, `date`) VALUES
(1, 1, 2, '1', '2023-12-05 07:59:17');

-- --------------------------------------------------------

--
-- Table structure for table `live_comments`
--

CREATE TABLE `live_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `live_streamings_id` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `joined` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `tip` enum('0','1') NOT NULL DEFAULT '0',
  `tip_amount` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_likes`
--

CREATE TABLE `live_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `live_streamings_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_online_users`
--

CREATE TABLE `live_online_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `live_streamings_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_streamings`
--

CREATE TABLE `live_streamings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'normal',
  `user_id` int(10) UNSIGNED NOT NULL,
  `buyer_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `channel` text NOT NULL,
  `minutes` int(10) UNSIGNED NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `joined_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `availability` char(50) NOT NULL DEFAULT 'all_pay',
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_streaming_private_requests`
--

CREATE TABLE `live_streaming_private_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `minutes` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_sessions`
--

CREATE TABLE `login_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(100) NOT NULL,
  `device` varchar(100) DEFAULT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_sessions`
--

INSERT INTO `login_sessions` (`id`, `user_id`, `ip`, `device`, `device_type`, `browser`, `platform`, `country`, `created_at`, `updated_at`) VALUES
(1, 2, '::1', 'WebKit', 'desktop', 'Opera 105.0.0.0', 'Windows 10.0', NULL, '2023-12-03 12:57:18', '2023-12-03 12:57:18'),
(2, 1, '::1', 'WebKit', 'desktop', 'Opera 105.0.0.0', 'Windows 10.0', NULL, '2023-12-03 13:20:32', '2023-12-05 04:34:42'),
(3, 2, '::1', 'WebKit', 'desktop', 'Chrome 119.0.0.0', 'Windows 10.0', NULL, '2023-12-05 11:13:34', '2023-12-05 11:57:55'),
(4, 1, '::1', 'WebKit', 'desktop', 'Chrome 119.0.0.0', 'Windows 10.0', NULL, '2023-12-05 11:53:21', '2023-12-05 11:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `updates_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `width` varchar(5) DEFAULT NULL,
  `height` varchar(5) DEFAULT NULL,
  `img_type` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `encoded` enum('yes','no') NOT NULL DEFAULT 'no',
  `video_poster` varchar(255) DEFAULT NULL,
  `duration_video` varchar(50) DEFAULT NULL,
  `quality_video` varchar(20) DEFAULT NULL,
  `video_embed` varchar(200) NOT NULL,
  `music` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` enum('active','pending') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `job_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_messages`
--

CREATE TABLE `media_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `messages_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `file` varchar(255) NOT NULL,
  `width` varchar(5) DEFAULT NULL,
  `height` varchar(5) DEFAULT NULL,
  `video_poster` varchar(255) DEFAULT NULL,
  `duration_video` varchar(50) DEFAULT NULL,
  `quality_video` varchar(20) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` enum('active','pending') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `encoded` enum('yes','no') NOT NULL DEFAULT 'no',
  `job_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_products`
--

CREATE TABLE `media_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `products_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_stories`
--

CREATE TABLE `media_stories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stories_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `video_length` varchar(20) DEFAULT NULL,
  `video_poster` varchar(100) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `font_color` varchar(50) NOT NULL DEFAULT '#ffffff',
  `font` varchar(50) NOT NULL DEFAULT 'Arial',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `job_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_welcome_messages`
--

CREATE TABLE `media_welcome_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `file` varchar(255) NOT NULL,
  `width` varchar(5) DEFAULT NULL,
  `height` varchar(5) DEFAULT NULL,
  `video_poster` varchar(255) DEFAULT NULL,
  `duration_video` varchar(50) DEFAULT NULL,
  `quality_video` varchar(20) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `file_size_bytes` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `encoded` enum('yes','no') NOT NULL DEFAULT 'no',
  `token` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `job_id` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `conversations_id` int(11) UNSIGNED NOT NULL,
  `from_user_id` int(10) UNSIGNED NOT NULL,
  `to_user_id` int(10) UNSIGNED NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attach_file` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('new','readed') NOT NULL DEFAULT 'new',
  `remove_from` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Delete, 1 Active',
  `file` varchar(150) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `format` varchar(10) NOT NULL,
  `size` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `tip` enum('yes','no') NOT NULL DEFAULT 'no',
  `tip_amount` int(10) UNSIGNED NOT NULL,
  `mode` enum('active','pending') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `destination` int(10) UNSIGNED NOT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `target` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL COMMENT '1 Subscribed, 2  Like, 3 reply, 4 Like Comment',
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 unseen, 1 seen',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` mediumtext DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `lang` char(10) NOT NULL DEFAULT 'es',
  `access` varchar(50) NOT NULL DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`, `description`, `keywords`, `lang`, `access`) VALUES
(2, 'Terms of Service', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets</p>', 'terms-of-service', 'Terms of Service of Sponzy', 'terms, Terms of Service', 'en', 'all'),
(3, 'Privacy', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \n\n<br/><br/>\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'privacy', '', '', 'en', 'all'),
(5, 'About us', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'about', '', '', 'en', 'all'),
(8, 'How it works', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>', 'how-it-works', '', '', 'en', 'all'),
(9, 'Cookies Policy', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets\r\n\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets\r\n\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets\r\n\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets', 'cookies', 'Page Cookies Policy', 'cookies, policy', 'en', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `token` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(255) NOT NULL,
  `enabled` enum('1','0') NOT NULL DEFAULT '1',
  `sandbox` enum('true','false') NOT NULL DEFAULT 'true',
  `fee` decimal(3,1) NOT NULL,
  `fee_cents` decimal(6,2) NOT NULL,
  `email` varchar(80) NOT NULL,
  `token` varchar(200) NOT NULL,
  `key` varchar(255) NOT NULL,
  `key_secret` varchar(255) NOT NULL,
  `passkey` varchar(100) DEFAULT NULL,
  `shortcode` varchar(100) DEFAULT NULL,
  `shortcode_type` varchar(100) DEFAULT NULL,
  `storenumber` varchar(100) DEFAULT NULL,
  `converter_api` varchar(100) DEFAULT NULL,
  `bank_info` text NOT NULL,
  `recurrent` enum('yes','no') NOT NULL,
  `logo` varchar(50) NOT NULL,
  `webhook_secret` varchar(255) NOT NULL,
  `subscription` enum('yes','no') NOT NULL DEFAULT 'yes',
  `ccbill_accnum` varchar(200) NOT NULL,
  `ccbill_subacc` varchar(200) NOT NULL,
  `ccbill_flexid` varchar(200) NOT NULL,
  `ccbill_salt` varchar(200) NOT NULL,
  `ccbill_subacc_subscriptions` varchar(200) NOT NULL,
  `project_id` varchar(255) DEFAULT NULL,
  `project_secret` varchar(255) DEFAULT NULL,
  `ccbill_datalink_username` varchar(255) DEFAULT NULL,
  `ccbill_datalink_password` varchar(255) DEFAULT NULL,
  `ccbill_skip_subaccount_cancellations` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `name`, `type`, `enabled`, `sandbox`, `fee`, `fee_cents`, `email`, `token`, `key`, `key_secret`, `passkey`, `shortcode`, `shortcode_type`, `storenumber`, `converter_api`, `bank_info`, `recurrent`, `logo`, `webhook_secret`, `subscription`, `ccbill_accnum`, `ccbill_subacc`, `ccbill_flexid`, `ccbill_salt`, `ccbill_subacc_subscriptions`, `project_id`, `project_secret`, `ccbill_datalink_username`, `ccbill_datalink_password`, `ccbill_skip_subaccount_cancellations`) VALUES
(1, 'PayPal', 'normal', '1', 'false', 10.0, 0.00, '', '02bGGfD9bHevK3eJN06CdDvFSTXsTrTG44yGdAONeN1R37jqnLY1PuNF0mJRoFnsEygyf28yePSCA1eR0alQk4BX89kGG9Rlha2D2KX55TpDFNR5o774OshrkHSZLOFo2fAhHzcWKnwsYDFKgwuaRg', 'ATUhgjZ5Ow2nTEPFGktWzNyhqAJatbHuj94qUvubliGmb7D4p6JDGGPJ5X02Z0wUKu8elNhWt6rUA44x', 'EN5KasZ2jT7k69-v6W1fEYNUPx4KuXLDzl8MKdBgbsbHVjE9pIKIvNOdrIRFfh4dkHrx7OP8P7D4gAaw', NULL, NULL, NULL, NULL, NULL, '', 'yes', 'paypal.png', '81U19155ML6016348', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(2, 'Stripe', 'card', '0', 'true', 2.9, 0.30, '', 'asfQSGRvYzS1P0X745krAAyHeU7ZbTpHbYKnxI2abQsBUi48EpeAu5lFAU2iBmsUWO5tpgAn9zzussI4Cce5ZcANIAmfBz0bNR9g3UfR4cserhkJwZwPsETiXiZuCixXVDHhCItuXTPXXSA6KITEoT', '', '', NULL, NULL, NULL, NULL, NULL, '', 'yes', 'stripe.png', '', 'yes', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(3, 'Bank', 'bank', '0', 'false', 10.0, 0.00, '', 'MUXRUWEuhBulbgDGUS4PbTCCjlUGr4VcGb59RU2rRWtgCHstntxq2eOeeDvIHKUwxj5z2njJ6DUGfmt0PzFnEWuspasmCserf705dJeQcVttZtHugTlBMlzt0Uf6K4DjVmGVtyZuPzXakjrFGVLYRd', '', '', NULL, NULL, NULL, NULL, NULL, '<b>1 USD is KES 160</b>\r\nAccount Number: 0160284565291\r\nAccount Name: Briceka Enterprise\r\nSwift/IBAN: EQBLKENA016\r\nBank Name: Equity Bank Kenya', 'no', '', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(4, 'CCBill', 'card', '0', 'true', 0.0, 0.00, '', 'zYYFZRjTfypGTPg0TCgWK8SKdLjGEMuNGMYedjl1D0hgmSPDS0UUduCNqkYL609NWH6ru6qdaBGFIzxeL85HwEtIOBfEQRC16qIQZ1zkTM1wbcC4fe2Wxpom6g8zmoOR1ppFN6MbPFHUJQur434Nzg', '', '', NULL, NULL, NULL, NULL, NULL, '', 'yes', '', '', 'yes', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(5, 'Paystack', 'card', '1', 'false', 10.0, 0.00, '', '4w2JnfBSvoZKLiVRzHRWu3pzkgsFslVZBn8Z4mmwzVJ5KVfiFZN9kTgfFEVu90E1wPN8TnaXhtmYKwkzZ0p6HGUo1S5yVXisVxlD2PIOcm6v096akLbMAgjxiXCdFlCALkZXqi4pfrwxpYd7BGFk8c', 'pk_live_5fc7fe795a95dec46c3074f4d3f915e0e9761e10', 'sk_live_1d42553566ef647489327b89120440ea10fd75fc', NULL, NULL, NULL, NULL, NULL, '', 'yes', '', '', 'yes', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(6, 'Coinpayments', 'normal', '1', 'false', 10.0, 0.00, '', 'nzfADI4PgRHRn8m3r7UrR0arnfT9u1rDOQL5SKGbqPjDOZGDZA35NX3y0lQBAdmIyGVS58Rr9OtebfpGNpiXROq1G1DJNqq6xoD04fLKWTaAS2Xh3CZvECa1OfH5cchJ2P6kEl167Caax2XyAwwjGl', '0457800b622485774593628a41e1c5b9', 'BPcmWzUj8WucOeth', NULL, NULL, NULL, NULL, NULL, '', 'no', 'coinpayments.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(7, 'Mercadopago', 'normal', '0', 'true', 0.0, 0.00, '', 'UmqNKk1EzDyjGgJxAswkhzL8bSdHusZh8RxF4hqzCEf52qdMOAMzMCchsKrLZgPcuTry6AZLLG7DUGwlpXVVpv1CajJbiV3j4EvPqbbIkBCoOptrDWZY1nRJhMc39v6NDOKFo9TjHQdnwU5pZSSReW', '', '', NULL, NULL, NULL, NULL, NULL, '', 'no', 'mercadopago.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(8, 'Flutterwave', 'normal', '1', 'false', 10.0, 0.00, '', 'jMF4RWTW14dfJxlusZ22jGeCLdc8i3416CNiw2ny4l8kGBILQDTrhLtCsDxmb1MfBQA6xGh5BhQBrOXCUBB8lQMZrCaJQykG3Nxbv0ADFUSJiNewq7kbSkxyR3rCbbVjhBYMtWt6dpJTB78CafpA6T', 'FLWPUBK-0a2963f93e398b35e2ce1d9a34f1896e-X', 'FLWSECK-b917afa958bb5797942a075fea730968-18a4628b927vt-X', NULL, NULL, NULL, NULL, NULL, '', 'no', 'flutterwave.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(9, 'Mollie', 'normal', '0', 'true', 0.0, 0.00, '', 'EqvhQZimw0L3zWQ35AV93iDOqgQnkXV9cqLnIvgsnz2zRLpjeZUv5JnC3QzTjtctYuVmzuo06N31tLCRPFJNkomdh7Rhk2zxe68GzgWsL1hc6YEM5V5W6JVnqwQfAzNGZkY6ZRFKmZCMbFQXOF3WkR', '', '', NULL, NULL, NULL, NULL, NULL, '', 'no', 'mollie.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(10, 'Razorpay', 'normal', '0', 'true', 0.0, 0.00, '', 'PsNqGq2rlLjo4AaTTsJpxshttwFFrLXjaKcwwQeG2r8aekDoETl9OG1cqND9PluycCfXazyzUiLo7ZFWs2tBWYwOpGcM8i5ID93Kr7Y6l79XrBcST56QONVpEcAuLRs36Z2t1Q1MBlR315c6vSpAFX', '', '', NULL, NULL, NULL, NULL, NULL, '', 'no', 'razorpay.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(11, 'Coinbase', 'normal', '0', 'false', 0.0, 0.00, '', 'sjuRSAj4VdjaBx2bcdXgDbvTdpiHJF40CNsX5vqw4VaaaohvDS48Oc30N6DAxIxChfd2qWQ99q6e11E90OOKwmo0RbXUv3fdpPksWMvJUkm8i7F5ajbsy0LbjqyidUBqJP0BG6H3NkidnbhtfcDF45', '', '', NULL, NULL, NULL, NULL, NULL, '', 'no', 'coinbase.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(12, 'NowPayments', 'normal', '1', 'false', 10.0, 0.00, '', '4DPdFIbqqPqI8XjseCCV1RRVwVuuQx6WtYdHIkMDtxgUKCQaS9Mqww4WUwa6fIxn7NN9ediLstZyIYQQIIt9l6rUANRSzrUVt86J5FopCkZHvysC8CMRBS3LOnKDbNHiUmVh91CkmvjiwCrFApfrtH', 'RHE1M9M-4YD4XAK-K0XRMQ2-JYAXHJ6', 'pfdUHtx34NTCspv', NULL, NULL, NULL, NULL, NULL, '', 'no', 'nowpayments.png', '', 'no', '', '', '', '', '', NULL, NULL, NULL, NULL, 0),
(13, 'Mpesa', 'normal', '1', 'false', 12.0, 0.00, '', '4DPdFIbqqPqI8XjseCCV1RRVwVuuQx6WtYdHIkMDtxgUKCQaS9Mqww4WUwa6fIxn7NN9ediLstZyIYQQIIt9l6rUANRSzrUVt86J5FopCkZHvysC8CMRBS3LOnKDbNHiUmVh91CkmvjiwCrFApfrtH', 'x4EIf8wvTahGKy80TnDzq7Bp6ghlp6VI', 'oA0vNNx8GgmAd9mW', '3d0e15d768d0240b6ce3d52d4d64c5ee014a6df398716bf39a23ab3ac3ab92d8', '6319797', 'CustomerBuyGoodsOnline', '8399360', 'e3fd55d299ff6f0000b03a9d', '', 'no', 'mpesa.png', '', 'yes', '', '', '', '', '', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pay_per_views`
--

CREATE TABLE `pay_per_views` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `updates_id` int(10) UNSIGNED NOT NULL,
  `messages_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `interval` varchar(100) NOT NULL,
  `paystack` varchar(150) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `user_id`, `name`, `price`, `interval`, `paystack`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'user_1_weekly', 2.00, 'weekly', '', '1', '2023-12-05 11:57:23', '2023-12-05 11:57:23'),
(2, 1, 'user_1', 2.00, 'monthly', '', '1', '2023-12-05 11:57:24', '2023-12-05 11:57:24');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` char(20) NOT NULL DEFAULT 'digital',
  `price` decimal(10,2) NOT NULL,
  `delivery_time` int(10) UNSIGNED NOT NULL,
  `country_free_shipping` char(20) NOT NULL,
  `tags` text NOT NULL,
  `description` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `mime` varchar(50) DEFAULT NULL,
  `extension` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `box_contents` varchar(200) NOT NULL,
  `category` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transactions_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `products_id` int(10) UNSIGNED NOT NULL,
  `delivery_status` varchar(50) NOT NULL DEFAULT 'delivered',
  `description_custom_content` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `zip` varchar(50) DEFAULT NULL,
  `phone` char(20) DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `referred_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_transactions`
--

CREATE TABLE `referral_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transactions_id` int(10) UNSIGNED DEFAULT NULL,
  `referrals_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `referred_by` int(10) UNSIGNED NOT NULL,
  `earnings` double(10,2) NOT NULL,
  `type` char(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `updates_id` bigint(20) UNSIGNED NOT NULL,
  `comments_id` bigint(20) UNSIGNED NOT NULL,
  `reply` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `user_id`, `updates_id`, `comments_id`, `reply`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '@Admin  wow', '2023-12-05 04:59:34', '2023-12-05 04:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `report_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reserved`
--

CREATE TABLE `reserved` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reserved`
--

INSERT INTO `reserved` (`id`, `name`) VALUES
(14, 'account'),
(31, 'api'),
(2, 'app'),
(49, 'artisan'),
(47, 'blog'),
(30, 'bootstrap'),
(34, 'categories'),
(36, 'collections'),
(29, 'comment'),
(42, 'config'),
(25, 'contact'),
(45, 'core'),
(46, 'cpanel'),
(32, 'creators'),
(37, 'dashboard'),
(41, 'database'),
(50, 'explore'),
(35, 'featured'),
(1, 'fuck'),
(9, 'goods'),
(52, 'home'),
(11, 'jobs'),
(21, 'join'),
(53, 'lang'),
(16, 'latest'),
(20, 'login'),
(33, 'logout'),
(27, 'members'),
(13, 'messages'),
(19, 'notifications'),
(48, 'offline'),
(15, 'popular'),
(6, 'porn'),
(12, 'projects'),
(3, 'public'),
(23, 'register'),
(40, 'resources'),
(39, 'routes'),
(17, 'search'),
(7, 'sex'),
(51, 'shop'),
(26, 'signup'),
(44, 'storage'),
(8, 'tags'),
(38, 'tests'),
(24, 'upgrade'),
(28, 'upload'),
(4, 'vendor'),
(5, 'xxx');

-- --------------------------------------------------------

--
-- Table structure for table `restrictions`
--

CREATE TABLE `restrictions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_restricted` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

CREATE TABLE `shop_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `countries_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL,
  `code` char(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `story_backgrounds`
--

CREATE TABLE `story_backgrounds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `story_backgrounds`
--

INSERT INTO `story_backgrounds` (`id`, `name`) VALUES
(1, '01.jpg'),
(2, '02.png'),
(3, '03.jpg'),
(4, '04.jpg'),
(5, '05.jpg'),
(6, '06.png'),
(7, '07.jpg'),
(8, '08.png'),
(9, '09.jpg'),
(10, '10.png'),
(11, '11.jpg'),
(12, '12.jpg'),
(13, '13.jpg'),
(14, '14.jpg'),
(15, '15.png');

-- --------------------------------------------------------

--
-- Table structure for table `story_fonts`
--

CREATE TABLE `story_fonts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `story_views`
--

CREATE TABLE `story_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `media_stories_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_state_tokens`
--

CREATE TABLE `stripe_state_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `stripe_status` varchar(255) NOT NULL,
  `stripe_price` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_payment` varchar(255) DEFAULT NULL,
  `free` enum('yes','no') NOT NULL DEFAULT 'no',
  `subscription_id` varchar(50) NOT NULL,
  `cancelled` enum('yes','no') NOT NULL DEFAULT 'no',
  `rebill_wallet` enum('on','off') NOT NULL DEFAULT 'off',
  `interval` varchar(100) NOT NULL DEFAULT 'monthly',
  `taxes` text DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_deleteds`
--

CREATE TABLE `subscription_deleteds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `stripe_product` varchar(255) DEFAULT NULL,
  `stripe_price` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `percentage` decimal(5,2) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `iso_state` char(10) DEFAULT NULL,
  `stripe_id` varchar(100) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `txn_id` varchar(250) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `subscriptions_id` int(10) UNSIGNED NOT NULL,
  `subscribed` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `earning_net_user` decimal(10,2) NOT NULL,
  `earning_net_admin` decimal(10,2) NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `approved` varchar(50) NOT NULL DEFAULT '1' COMMENT '0 Pending, 1 Success, 2 Canceled',
  `amount` float NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'subscription',
  `percentage_applied` varchar(50) NOT NULL,
  `referred_commission` int(10) UNSIGNED NOT NULL,
  `taxes` text DEFAULT NULL,
  `direct_payment` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_codes`
--

CREATE TABLE `two_factor_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `token_id` varchar(255) NOT NULL,
  `locked` enum('yes','no') NOT NULL DEFAULT 'no',
  `fixed_post` enum('0','1') NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `status` char(20) NOT NULL DEFAULT 'active',
  `video_views` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(200) DEFAULT NULL,
  `scheduled_date` timestamp NULL DEFAULT NULL,
  `schedule` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`id`, `title`, `description`, `user_id`, `date`, `token_id`, `locked`, `fixed_post`, `price`, `status`, `video_views`, `ip`, `scheduled_date`, `schedule`) VALUES
(1, '', 'hello world', 1, '2023-12-05 04:40:52', 'ZEsRWaAm9e9lFYnxyZwaK6ZhLuCjenmEEXA3AigNyVIUIqQtPWlYXurAB2cb19txopeJh8r5nFXDZpmfbWHKW9DTP0sx4KTGIL61UwbUZ3UPRXUPxQaoP85chLK48v9Cc4av9ix0ByJVepJ7kVL86B', 'yes', '0', 0.00, 'active', 0, '::1', '0000-00-00 00:00:00', 0),
(2, '', 'hello world again', 1, '2023-12-05 04:59:12', 'Re18uG00x7JGRgf45UmMoosdeTlJHg8P6LdNjJ6q49raA8hD8oSUP5AjPEBQbIwzpIwknEgN5Mn1Etrw7K49XcFKkWjqmXjVv90iBMiAG4hgA7w2plTn5Jyuj5YyKZqIp1YVoXduUurBm93qx2jw3O', 'yes', '0', 0.00, 'active', 0, '::1', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) NOT NULL,
  `countries_id` char(25) NOT NULL,
  `password` char(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(70) NOT NULL,
  `cover` varchar(70) NOT NULL,
  `status` enum('pending','active','suspended','delete') NOT NULL DEFAULT 'active',
  `role` enum('normal','admin') NOT NULL DEFAULT 'normal',
  `permission` enum('all','none') NOT NULL DEFAULT 'none',
  `remember_token` varchar(100) NOT NULL,
  `token` varchar(80) NOT NULL,
  `confirmation_code` varchar(125) NOT NULL,
  `paypal_account` varchar(200) NOT NULL,
  `payment_gateway` varchar(50) NOT NULL,
  `bank` text NOT NULL,
  `featured` enum('yes','no') NOT NULL DEFAULT 'no',
  `featured_date` timestamp NULL DEFAULT NULL,
  `about` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `story` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profession` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `oauth_uid` varchar(255) NOT NULL,
  `oauth_provider` varchar(255) NOT NULL,
  `categories_id` varchar(255) NOT NULL,
  `website` varchar(200) NOT NULL,
  `stripe_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `pm_type` varchar(255) DEFAULT NULL,
  `pm_last_four` varchar(4) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `verified_id` enum('yes','no','reject') DEFAULT 'no',
  `address` varchar(200) NOT NULL,
  `city` varchar(150) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `youtube` varchar(200) NOT NULL,
  `pinterest` varchar(200) NOT NULL,
  `github` varchar(200) NOT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  `email_new_subscriber` enum('yes','no') NOT NULL DEFAULT 'yes',
  `plan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notify_new_subscriber` enum('yes','no') NOT NULL DEFAULT 'yes',
  `notify_liked_post` enum('yes','no') NOT NULL DEFAULT 'yes',
  `notify_commented_post` enum('yes','no') NOT NULL DEFAULT 'yes',
  `company` varchar(50) NOT NULL,
  `post_locked` enum('yes','no') NOT NULL DEFAULT 'yes',
  `ip` varchar(40) NOT NULL,
  `dark_mode` enum('on','off') NOT NULL DEFAULT 'off',
  `gender` varchar(50) NOT NULL,
  `birthdate` varchar(30) NOT NULL,
  `allow_download_files` enum('no','yes') NOT NULL DEFAULT 'no',
  `language` varchar(10) NOT NULL,
  `free_subscription` enum('yes','no') NOT NULL DEFAULT 'no',
  `wallet` decimal(10,2) NOT NULL,
  `tiktok` varchar(200) NOT NULL,
  `snapchat` varchar(200) NOT NULL,
  `paystack_plan` varchar(100) NOT NULL,
  `paystack_authorization_code` varchar(100) NOT NULL,
  `paystack_last4` int(10) UNSIGNED NOT NULL,
  `paystack_exp` varchar(50) NOT NULL,
  `paystack_card_brand` varchar(25) NOT NULL,
  `notify_new_tip` enum('yes','no') NOT NULL DEFAULT 'yes',
  `hide_profile` enum('yes','no') NOT NULL DEFAULT 'no',
  `hide_last_seen` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_login` varchar(250) NOT NULL,
  `hide_count_subscribers` enum('yes','no') NOT NULL DEFAULT 'no',
  `hide_my_country` enum('yes','no') NOT NULL DEFAULT 'no',
  `show_my_birthdate` enum('yes','no') NOT NULL DEFAULT 'no',
  `notify_new_post` enum('yes','no') NOT NULL DEFAULT 'yes',
  `notify_email_new_post` enum('yes','no') NOT NULL DEFAULT 'yes',
  `custom_fee` int(10) UNSIGNED NOT NULL,
  `hide_name` enum('yes','no') NOT NULL DEFAULT 'no',
  `birthdate_changed` enum('yes','no') NOT NULL DEFAULT 'no',
  `email_new_tip` enum('yes','no') NOT NULL DEFAULT 'yes',
  `email_new_ppv` enum('yes','no') NOT NULL DEFAULT 'yes',
  `notify_new_ppv` enum('yes','no') NOT NULL DEFAULT 'yes',
  `active_status_online` enum('yes','no') NOT NULL DEFAULT 'yes',
  `payoneer_account` varchar(200) NOT NULL,
  `zelle_account` varchar(200) NOT NULL,
  `notify_liked_comment` enum('yes','no') NOT NULL DEFAULT 'yes',
  `permissions` text NOT NULL,
  `blocked_countries` text NOT NULL,
  `two_factor_auth` enum('yes','no') NOT NULL DEFAULT 'no',
  `notify_live_streaming` enum('yes','no') NOT NULL DEFAULT 'yes',
  `notify_mentions` enum('yes','no') NOT NULL DEFAULT 'yes',
  `stripe_connect_id` varchar(255) DEFAULT NULL,
  `completed_stripe_onboarding` tinyint(1) NOT NULL DEFAULT 0,
  `device_token` varchar(255) DEFAULT NULL,
  `telegram` varchar(200) NOT NULL,
  `vk` varchar(200) NOT NULL,
  `twitch` varchar(200) NOT NULL,
  `discord` varchar(200) NOT NULL,
  `reddit` varchar(200) NOT NULL,
  `spotify` varchar(200) NOT NULL,
  `posts_privacy` tinyint(1) NOT NULL DEFAULT 1,
  `document_id` varchar(100) NOT NULL,
  `crypto_wallet` varchar(255) NOT NULL,
  `threads` varchar(200) NOT NULL,
  `allow_live_streaming_private` enum('on','off') NOT NULL DEFAULT 'off',
  `price_live_streaming_private` decimal(10,2) DEFAULT NULL,
  `allow_dm` tinyint(1) NOT NULL DEFAULT 1,
  `welcome_message_new_subs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_welcome_message` tinyint(1) NOT NULL DEFAULT 0,
  `price_welcome_message` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `countries_id`, `password`, `email`, `date`, `avatar`, `cover`, `status`, `role`, `permission`, `remember_token`, `token`, `confirmation_code`, `paypal_account`, `payment_gateway`, `bank`, `featured`, `featured_date`, `about`, `story`, `profession`, `oauth_uid`, `oauth_provider`, `categories_id`, `website`, `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`, `price`, `balance`, `verified_id`, `address`, `city`, `zip`, `facebook`, `twitter`, `instagram`, `youtube`, `pinterest`, `github`, `last_seen`, `email_new_subscriber`, `plan`, `notify_new_subscriber`, `notify_liked_post`, `notify_commented_post`, `company`, `post_locked`, `ip`, `dark_mode`, `gender`, `birthdate`, `allow_download_files`, `language`, `free_subscription`, `wallet`, `tiktok`, `snapchat`, `paystack_plan`, `paystack_authorization_code`, `paystack_last4`, `paystack_exp`, `paystack_card_brand`, `notify_new_tip`, `hide_profile`, `hide_last_seen`, `last_login`, `hide_count_subscribers`, `hide_my_country`, `show_my_birthdate`, `notify_new_post`, `notify_email_new_post`, `custom_fee`, `hide_name`, `birthdate_changed`, `email_new_tip`, `email_new_ppv`, `notify_new_ppv`, `active_status_online`, `payoneer_account`, `zelle_account`, `notify_liked_comment`, `permissions`, `blocked_countries`, `two_factor_auth`, `notify_live_streaming`, `notify_mentions`, `stripe_connect_id`, `completed_stripe_onboarding`, `device_token`, `telegram`, `vk`, `twitch`, `discord`, `reddit`, `spotify`, `posts_privacy`, `document_id`, `crypto_wallet`, `threads`, `allow_live_streaming_private`, `price_live_streaming_private`, `allow_dm`, `welcome_message_new_subs`, `send_welcome_message`, `price_welcome_message`) VALUES
(1, 'Admin', 'Admin', '1', '$2y$10$U3BsZ5Xx/MpiZLl2N54t8OP0h1EdWOQEDuzO/tAVwKM4AF/nAn9Tu', 'admin@example.com', '2021-03-13 08:01:01', 'default.jpg', '', 'active', 'admin', 'all', 'eAVSNf6UfQnnqN3XcdqvwcoETFj50vwVpWK4gxi6ujKU20V4pUnjTb9yfcsK', 'Wy4VkAl2dxHb9WHoXjTowSGPXFPnEQHca6RBe2yeqqmRafs0hSbCEobhNkZZAbCDIru60ceLzAAOI3fj', '', '', '', '', 'yes', '2019-02-21 03:25:00', '', 'Welcome to my page. If you like my content, please consider support. Any donation will be well received. Thank you for your support!', '', '', '', '0', '', NULL, NULL, NULL, NULL, 0.00, 0.00, 'yes', '', '', '', '', '', '', '', '', '', '2023-12-05 11:57:40', 'yes', '', 'yes', 'yes', 'yes', '', 'yes', '', 'off', '', '', 'no', '', 'no', 0.00, '', '', '', '', 0, '', '', 'yes', 'no', 'no', '', 'no', 'no', 'no', 'yes', 'no', 0, 'no', 'no', 'yes', 'yes', 'yes', 'yes', '', '', 'yes', 'full_access', '', 'no', 'yes', 'yes', NULL, 0, NULL, '', '', '', '', '', '', 1, '', '', '', 'off', NULL, 1, '', 0, NULL),
(2, 'kevin kibebe', 'kevin2', '', '$2y$10$zrwYMGcwAtyUFb47Le/tYO8lU2gvzdLitSsS.4ql.iM2x52ykeK0K', 'kibebekevin@gmail.com', '2023-12-03 12:57:17', 'default.jpg', '', 'active', 'normal', 'none', '', 'yKyj9yGhnrhBpZUlJW0GGbGq4gNC7qrFnfYxiEhCCldF3bZ9ldMVb5wrpv6OiJOT66HuNBqtifQ', '', '', '', '', 'no', NULL, '', 'Welcome to my page. If you like my content, please consider support. Thank you for your support!', '', '', '', '', '', NULL, NULL, NULL, NULL, 0.00, 0.00, 'no', '', '', '', '', '', '', '', '', '', '2023-12-05 12:12:47', 'yes', '', 'yes', 'yes', 'yes', '', 'yes', '::1', 'off', '', '', 'no', 'en', 'no', 0.00, '', '', '', '', 0, '', '', 'yes', 'no', 'no', '', 'no', 'no', 'no', 'yes', 'yes', 0, 'yes', 'no', 'yes', 'yes', 'yes', 'yes', '', '', 'yes', '', '', 'no', 'yes', 'yes', NULL, 0, NULL, '', '', '', '', '', '', 1, '', '', '', 'off', NULL, 1, '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `player_id` varchar(255) NOT NULL,
  `device_type` char(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_requests`
--

CREATE TABLE `verification_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(150) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `image_reverse` varchar(100) DEFAULT NULL,
  `image_selfie` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `form_w9` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `video_views`
--

CREATE TABLE `video_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `updates_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `amount` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `gateway` varchar(100) NOT NULL,
  `account` text NOT NULL,
  `estimated_payment` timestamp NULL DEFAULT NULL,
  `date_paid` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `txn_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisings`
--
ALTER TABLE `advertisings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clicks` (`clicks`),
  ADD KEY `impressions` (`impressions`),
  ADD KEY `expired_at` (`expired_at`);

--
-- Indexes for table `ad_click_impressions`
--
ALTER TABLE `ad_click_impressions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advertisings_id` (`advertisings_id`),
  ADD KEY `type` (`type`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags` (`tags`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookmarks_user_id_index` (`user_id`),
  ADD KEY `bookmarks_updates_id_index` (`updates_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post` (`updates_id`,`user_id`,`status`);

--
-- Indexes for table `comments_likes`
--
ALTER TABLE `comments_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_likes_user_id_index` (`user_id`),
  ADD KEY `comments_likes_comments_id_index` (`comments_id`),
  ADD KEY `comments_likes_replies_id_index` (`replies_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_1` (`user_1`,`user_2`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usr` (`user_id`,`updates_id`,`status`);

--
-- Indexes for table `live_comments`
--
ALTER TABLE `live_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_comments_user_id_index` (`user_id`),
  ADD KEY `live_comments_live_streamings_id_index` (`live_streamings_id`);

--
-- Indexes for table `live_likes`
--
ALTER TABLE `live_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_likes_user_id_index` (`user_id`),
  ADD KEY `live_likes_live_streamings_id_index` (`live_streamings_id`);

--
-- Indexes for table `live_online_users`
--
ALTER TABLE `live_online_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_online_users_user_id_index` (`user_id`),
  ADD KEY `live_online_users_live_streamings_id_index` (`live_streamings_id`);

--
-- Indexes for table `live_streamings`
--
ALTER TABLE `live_streamings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_streamings_user_id_index` (`user_id`),
  ADD KEY `type` (`type`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `minutes` (`minutes`),
  ADD KEY `joined_at` (`joined_at`),
  ADD KEY `token` (`token`),
  ADD KEY `updated_at` (`updated_at`);

--
-- Indexes for table `live_streaming_private_requests`
--
ALTER TABLE `live_streaming_private_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `minutes` (`minutes`);

--
-- Indexes for table `login_sessions`
--
ALTER TABLE `login_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_updates_id_index` (`updates_id`),
  ADD KEY `media_user_id_index` (`user_id`),
  ADD KEY `media_type_index` (`type`),
  ADD KEY `media_token_index` (`token`),
  ADD KEY `media_encoded_index` (`encoded`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `media_messages`
--
ALTER TABLE `media_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_messages_messages_id_index` (`messages_id`),
  ADD KEY `media_messages_type_index` (`type`),
  ADD KEY `media_messages_token_index` (`token`),
  ADD KEY `media_messages_encoded_index` (`encoded`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `media_products`
--
ALTER TABLE `media_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_products_products_id_index` (`products_id`);

--
-- Indexes for table `media_stories`
--
ALTER TABLE `media_stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_stories_stories_id_index` (`stories_id`),
  ADD KEY `media_stories_name_index` (`name`),
  ADD KEY `media_stories_type_index` (`type`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `media_welcome_messages`
--
ALTER TABLE `media_welcome_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_welcome_messages_creator_id_index` (`creator_id`),
  ADD KEY `media_welcome_messages_type_index` (`type`),
  ADD KEY `media_welcome_messages_encoded_index` (`encoded`),
  ADD KEY `media_welcome_messages_token_index` (`token`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `remove_from` (`remove_from`),
  ADD KEY `conversation_id` (`conversations_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `mode` (`mode`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination` (`destination`),
  ADD KEY `author` (`author`),
  ADD KEY `target` (`target`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hash` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_per_views`
--
ALTER TABLE `pay_per_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pay_per_views_user_id_index` (`user_id`),
  ADD KEY `pay_per_views_updates_id_index` (`updates_id`),
  ADD KEY `pay_per_views_messages_id_index` (`messages_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_name_unique` (`name`),
  ADD KEY `plans_user_id_index` (`user_id`),
  ADD KEY `plans_paystack_index` (`paystack`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_index` (`user_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_transactions_id_index` (`transactions_id`),
  ADD KEY `purchases_user_id_index` (`user_id`),
  ADD KEY `purchases_products_id_index` (`products_id`),
  ADD KEY `expired_at` (`expired_at`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrals_user_id_index` (`user_id`),
  ADD KEY `referrals_referred_by_index` (`referred_by`);

--
-- Indexes for table `referral_transactions`
--
ALTER TABLE `referral_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referral_transactions_referrals_id_index` (`referrals_id`),
  ADD KEY `referral_transactions_user_id_index` (`user_id`),
  ADD KEY `referral_transactions_referred_by_index` (`referred_by`),
  ADD KEY `referral_transactions_transactions_id_index` (`transactions_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `replies_user_id_index` (`user_id`),
  ADD KEY `replies_updates_id_index` (`updates_id`),
  ADD KEY `replies_comments_id_index` (`comments_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user_id`,`report_id`);

--
-- Indexes for table `reserved`
--
ALTER TABLE `reserved`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `restrictions`
--
ALTER TABLE `restrictions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restrictions_user_id_index` (`user_id`),
  ADD KEY `restrictions_user_restricted_index` (`user_restricted`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_categories_slug_index` (`slug`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_countries_id_index` (`countries_id`),
  ADD KEY `name` (`name`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stories_user_id_index` (`user_id`),
  ADD KEY `stories_status_index` (`status`);

--
-- Indexes for table `story_backgrounds`
--
ALTER TABLE `story_backgrounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `story_backgrounds_name_index` (`name`);

--
-- Indexes for table `story_fonts`
--
ALTER TABLE `story_fonts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `story_views`
--
ALTER TABLE `story_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `story_views_user_id_index` (`user_id`),
  ADD KEY `story_views_media_stories_id_index` (`media_stories_id`);

--
-- Indexes for table `stripe_state_tokens`
--
ALTER TABLE `stripe_state_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_payment` (`last_payment`(191)),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `stripe_status` (`stripe_status`),
  ADD KEY `stripe_id` (`stripe_id`),
  ADD KEY `stripe_price` (`stripe_price`),
  ADD KEY `free` (`free`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indexes for table `subscription_deleteds`
--
ALTER TABLE `subscription_deleteds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_deleteds_creator_id_index` (`creator_id`),
  ADD KEY `subscription_deleteds_user_id_index` (`user_id`);

--
-- Indexes for table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_id` (`subscription_id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subscriber` (`subscriptions_id`),
  ADD KEY `subscribed` (`subscribed`),
  ADD KEY `earning_net_user` (`earning_net_user`),
  ADD KEY `earning_net_admin` (`earning_net_admin`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `amount` (`amount`),
  ADD KEY `approved` (`approved`);

--
-- Indexes for table `two_factor_codes`
--
ALTER TABLE `two_factor_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_id` (`token_id`),
  ADD KEY `video_views` (`video_views`),
  ADD KEY `status` (`status`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`),
  ADD KEY `permission` (`permission`),
  ADD KEY `categories_id` (`categories_id`),
  ADD KEY `stripe_id` (`stripe_id`(191)),
  ADD KEY `users_blocked_countries_index` (`blocked_countries`(1024)),
  ADD KEY `username` (`username`),
  ADD KEY `status` (`status`),
  ADD KEY `permissions` (`permissions`(1024)),
  ADD KEY `countries_id` (`countries_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_devices_player_id_unique` (`player_id`);

--
-- Indexes for table `verification_requests`
--
ALTER TABLE `verification_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `video_views`
--
ALTER TABLE `video_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_views_user_id_index` (`user_id`),
  ADD KEY `video_views_updates_id_index` (`updates_id`),
  ADD KEY `video_views_ip_index` (`ip`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaings_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `advertisings`
--
ALTER TABLE `advertisings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ad_click_impressions`
--
ALTER TABLE `ad_click_impressions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments_likes`
--
ALTER TABLE `comments_likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `live_comments`
--
ALTER TABLE `live_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_likes`
--
ALTER TABLE `live_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_online_users`
--
ALTER TABLE `live_online_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_streamings`
--
ALTER TABLE `live_streamings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_streaming_private_requests`
--
ALTER TABLE `live_streaming_private_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_sessions`
--
ALTER TABLE `login_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_messages`
--
ALTER TABLE `media_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_products`
--
ALTER TABLE `media_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_stories`
--
ALTER TABLE `media_stories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_welcome_messages`
--
ALTER TABLE `media_welcome_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pay_per_views`
--
ALTER TABLE `pay_per_views`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_transactions`
--
ALTER TABLE `referral_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reserved`
--
ALTER TABLE `reserved`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `restrictions`
--
ALTER TABLE `restrictions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop_categories`
--
ALTER TABLE `shop_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `story_backgrounds`
--
ALTER TABLE `story_backgrounds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `story_fonts`
--
ALTER TABLE `story_fonts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `story_views`
--
ALTER TABLE `story_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_state_tokens`
--
ALTER TABLE `stripe_state_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_deleteds`
--
ALTER TABLE `subscription_deleteds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `two_factor_codes`
--
ALTER TABLE `two_factor_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_requests`
--
ALTER TABLE `verification_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `video_views`
--
ALTER TABLE `video_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
