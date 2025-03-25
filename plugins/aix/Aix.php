<?php

namespace Altum\Plugin;

use Altum\Plugin;

class Aix {
    public static $plugin_id = 'aix';

    public static function install() {

        /* Run the installation process of the plugin */
        $queries = [
            "INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('aix', '');",
            "alter table users add aix_words_current_month bigint unsigned default 0 after source;",
            "alter table users add aix_images_current_month bigint unsigned default 0 after source;",
            "alter table users add aix_transcriptions_current_month bigint unsigned default 0 after source;",
            "alter table users add aix_chats_current_month bigint unsigned default 0 after source;",

            "CREATE TABLE `templates_categories` (
            `template_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(128) DEFAULT NULL,
            `settings` text,
            `icon` varchar(32) DEFAULT NULL,
            `emoji` varchar(32) DEFAULT NULL,
            `color` varchar(16) DEFAULT NULL,
            `background` varchar(16) DEFAULT NULL,
            `order` int DEFAULT NULL,
            `is_enabled` tinyint unsigned DEFAULT '1',
            `datetime` datetime DEFAULT NULL,
            `last_datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`template_category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            "INSERT INTO `templates_categories` (`template_category_id`, `name`, `settings`, `icon`, `emoji`, `color`, `background`, `order`, `is_enabled`, `datetime`, `last_datetime`) VALUES
            (1, 'Text', '{\"translations\":{\"english\":{\"name\":\"Text\"}}}', 'fa fa-paragraph', '📝', '#14b8a6', '#f0fdfa', 1, 1, '2023-03-25 17:33:19', NULL),
            (2, 'Website', '{\"translations\":{\"english\":{\"name\":\"Website\"}}}', 'fa fa-globe', '🌐', '#0ea5e9', '#f0f9ff', 1, 1, '2023-03-25 17:33:19', NULL),
            (3, 'Social Media', '{\"translations\":{\"english\":{\"name\":\"Social Media\"}}}', 'fa fa-hashtag', '🕊️', '#3b82f6', '#eff6ff', 1, 1, '2023-03-25 17:33:19', NULL),
            (4, 'Others', '{\"translations\":{\"english\":{\"name\":\"Others\"}}}', 'fa fa-fire', '🔥', '#6366f1', '#eef2ff', 1, 1, '2023-03-25 17:33:19', NULL);",

            "CREATE TABLE `templates` (
            `template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `template_category_id` bigint unsigned DEFAULT NULL,
            `name` varchar(128) DEFAULT NULL,
            `prompt` text,
            `settings` text,
            `icon` varchar(32) DEFAULT NULL,
            `order` int DEFAULT NULL,
            `total_usage` bigint unsigned DEFAULT '0',
            `is_enabled` tinyint unsigned DEFAULT '1',
            `datetime` datetime DEFAULT NULL,
            `last_datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`template_id`),
            KEY `template_category_id` (`template_category_id`),
            CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            "INSERT INTO `templates` (`template_id`, `template_category_id`, `name`, `prompt`, `settings`, `icon`, `order`, `total_usage`, `is_enabled`, `datetime`, `last_datetime`) VALUES
            (1, 1, 'Summarize', 'Summarize the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Summarize\",\"description\":\"Get a quick summary of a long piece of text, only the important parts.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to summarize\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-align-left', 1, 1, 1, '2023-03-25 23:28:59', NULL),
            (2, 1, 'Explain like I am 5', 'Explain & summarize the following text like I am 5: {text}', '{\"translations\":{\"english\":{\"name\":\"Explain like I am 5\",\"description\":\"Get a better understanding on a topic, subject or piece of text.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or concept to explain\",\"placeholder\":\"How does a rocket go into space?\",\"help\":null}}}}}', 'fa fa-child', 2, 1, 1, '2023-03-25 23:28:59', NULL),
            (3, 1, 'Text spinner/rewriter', 'Rewrite the following text in a different manner: {text}', '{\"translations\":{\"english\":{\"name\":\"Text spinner/rewriter\",\"description\":\"Rewrite a piece of text in another unique way, using different words.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to rewrite\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-sync', 3, 1, 1, '2023-03-25 23:28:59', NULL),
            (4, 1, 'Keywords generator', 'Extract important keywords from the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Keywords generator\",\"description\":\"Extract important keywords from a piece of text.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract keywords from\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-key', 4, 1, 1, '2023-03-25 23:28:59', NULL),
            (5, 1, 'Grammar fixer', 'Fix the grammar on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Grammar fixer\",\"description\":\"Make sure your text is written correctly with no spelling or grammar errors.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to grammar fix\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-spell-check', 5, 1, 1, '2023-03-25 23:28:59', NULL),
            (6, 1, 'Text to Emoji', 'Transform the following text into emojis: {text}', '{\"translations\":{\"english\":{\"name\":\"Text to Emoji\",\"description\":\"Convert the meaning of a piece of text to fun emojis.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to convert\",\"placeholder\":\"The pirates of the Caribbean\",\"help\":null}}}}}', 'fa fa-smile-wink', 6, 1, 1, '2023-03-25 23:28:59', NULL),
            (7, 1, 'Blog Article Idea', 'Write multiple blog article ideas based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Idea\",\"description\":\"Generate interesting blog article ideas based on the topics that you want.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":\"Best places to travel as a couple\",\"help\":null}}}}}', 'fa fa-lightbulb', 7, 1, 1, '2023-03-25 23:29:00', NULL),
            (8, 1, 'Blog Article Intro', 'Write a good intro for a blog article, based on the title of the blog post: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Intro\",\"description\":\"Generate a creative intro section for your blog article.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title of the blog article\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-keyboard', 8, 1, 1, '2023-03-25 23:29:00', NULL),
            (9, 1, 'Blog Article Idea & Outline', 'Write ideas for a blog article title and outline, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Idea & Outline\",\"description\":\"Generate unlimited blog article ideas and structure with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-blog', 9, 1, 1, '2023-03-25 23:29:00', NULL),
            (10, 1, 'Blog Article Section', 'Write a blog sections about \"{title}\" using the \"{keywords}\" keywords', '{\"translations\":{\"english\":{\"name\":\"Blog Article Section\",\"description\":\"Generate a full and unique section/paragraph for your blog article.\"}},\"inputs\":{\"title\":{\"icon\":\"fa fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Best traveling tips and tricks\",\"help\":null}}},\"keywords\":{\"icon\":\"fa fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords\",\"placeholder\":\"Airport luggage, Car rentals, Quality Airbnb stays\",\"help\":null}}}}}', 'fa fa-rss', 10, 1, 1, '2023-03-25 23:29:00', NULL),
            (11, 1, 'Blog Article', 'Write a long article / blog post on \"{title}\" with the \"{keywords}\" keywords and the following sections \"{sections}\"', '{\"translations\":{\"english\":{\"name\":\"Blog Article\",\"description\":\"Generate a simple and creative article / blog post for your website.\"}},\"inputs\":{\"title\":{\"icon\":\"fa fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Places you must visit in winter\",\"help\":null}}},\"keywords\":{\"icon\":\"fa fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords\",\"placeholder\":\"Winter, Hotel, Jacuzzi, Spa, Ski\",\"help\":null}}},\"sections\":{\"icon\":\"fa fa-feather\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Sections\",\"placeholder\":\"Austria, Italy, Switzerland\",\"help\":null}}}}}', 'fa fa-feather', 11, 1, 1, '2023-03-25 23:29:00', NULL),
            (12, 1, 'Blog Article Outro', 'Write a blog article outro based on the blog title \"{title}\" and the \"{description}\" description', '{\"translations\":{\"english\":{\"name\":\"Blog Article Outro\",\"description\":\"Generate the conclusion section of your blog article.\"}},\"inputs\":{\"title\":{\"icon\":\"fa fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Warm places to visit in December\",\"help\":null}}},\"description\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Describe what your blog article is about\",\"help\":null}}}}}', 'fa fa-pen-nib', 12, 1, 1, '2023-03-25 23:29:00', NULL),
            (13, 1, 'Reviews', 'Write a review or testimonial about \"{name}\" using the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Reviews\",\"description\":\"Generate creative reviews / testimonials for your service or product.\"}},\"inputs\":{\"name\":{\"icon\":\"fa fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Wandering Agency: Travel with confidence\",\"help\":null}}},\"description\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"We plan and set up your perfect traveling experience to the most exotic places, from start to finish.\",\"help\":null}}}}}', 'fa fa-star', 13, 1, 1, '2023-03-25 23:29:00', NULL),
            (14, 1, 'Translate', 'Translate the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Translate\",\"description\":\"Translate a piece of text to another language with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-language', 14, 1, 1, '2023-03-25 23:29:00', NULL),
            (15, 3, 'Social media bio', 'Write a short social media bio profile description based on those keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Social media bio\",\"description\":\"Generate Twitter, Instagram, TikTok bio for your account.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords to be used\",\"placeholder\":\"Yacht traveling, Boat charter, Summer, Sailing\",\"help\":null}}}}}', 'fa fa-share-alt', 15, 1, 1, '2023-03-25 23:29:00', NULL),
            (16, 3, 'Social media hashtags', 'Generate hashtags for a social media post based on the following description: {text}', '{\"translations\":{\"english\":{\"name\":\"Social media hashtags\",\"description\":\"Generate hashtags for your social media posts.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract hashtags from\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-hashtag', 16, 1, 1, '2023-03-25 23:29:00', NULL),
            (17, 3, 'Video Idea', 'Write ideas for a video scenario, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Idea\",\"description\":\"Generate a random video idea based on the topics that you want.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-video', 17, 1, 1, '2023-03-25 23:29:00', NULL),
            (18, 3, 'Video Title', 'Write a video title, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Title\",\"description\":\"Generate a catchy video title for your video.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-play', 18, 1, 1, '2023-03-25 23:29:00', NULL),
            (19, 3, 'Video Description', 'Write a video description, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Description\",\"description\":\"Generate a brief and quality video description.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-film', 19, 1, 1, '2023-03-25 23:29:00', NULL),
            (20, 3, 'Tweet generator', 'Generate a tweet based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Tweet generator\",\"description\":\"Generate tweets based on your ideas/topics/keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fab fa-twitter', 20, 1, 1, '2023-03-25 23:29:00', NULL),
            (21, 3, 'Instagram caption', 'Generate an instagram caption for a post based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Instagram caption\",\"description\":\"Generate an instagram post caption based on text or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fab fa-instagram', 21, 1, 1, '2023-03-25 23:29:00', NULL),
            (22, 2, 'Website Headline', 'Write a website short headline for the \"{name}\" product with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Website Headline\",\"description\":\"Generate creative, catchy and unique headlines for your website.\"}},\"inputs\":{\"name\":{\"icon\":\"fa fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Sunset Agents: Best summer destinations\",\"help\":null}}},\"description\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our blog helps you find and plan your next summer vacation.\",\"help\":null}}}}}', 'fa fa-feather', 22, 1, 1, '2023-03-25 23:29:00', NULL),
            (23, 2, 'SEO Title', 'Write an SEO Title for a web page based on those keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Title\",\"description\":\"Generate high quality & SEO ready titles for your web pages.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords to be used\",\"placeholder\":\"Traveling, Summer, Beach, Pool\",\"help\":null}}}}}', 'fa fa-heading', 23, 1, 1, '2023-03-25 23:29:00', NULL),
            (24, 2, 'SEO Description', 'Write an SEO description, maximum 160 characters, for a web page based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Description\",\"description\":\"Generate proper descriptions for your web pages to help you rank better\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-pen', 24, 1, 1, '2023-03-25 23:29:00', NULL),
            (25, 2, 'SEO Keywords', 'Write SEO meta keywords, maximum 160 characters, for a web page based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Keywords\",\"description\":\"Extract and generate meaningful and quality keywords for your website.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract keywords from\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-file-word', 25, 1, 1, '2023-03-25 23:29:00', NULL),
            (26, 2, 'Ad Title', 'Write a short ad title, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Ad Title\",\"description\":\"Generate a short & good title copy for any of your ads.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-money-check-alt', 26, 1, 1, '2023-03-25 23:29:00', NULL),
            (27, 2, 'Ad Description', 'Write a short ad description, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Ad Description\",\"description\":\"Generate the description for an ad campaign.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-th-list', 27, 1, 1, '2023-03-25 23:29:00', NULL),
            (28, 4, 'Name generator', 'Generate multiple & relevant product names based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Name generator\",\"description\":\"Generate interesting product names for your project.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-file-signature', 28, 1, 1, '2023-03-25 23:29:00', NULL),
            (29, 4, 'Startup ideas', 'Generate multiple & relevant startup business ideas based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Startup ideas\",\"description\":\"Generate startup ideas based on your topic inputs.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-user-tie', 29, 1, 1, '2023-03-25 23:29:00', NULL),
            (30, 4, 'Viral ideas', 'Generate a viral idea based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Viral ideas\",\"description\":\"Generate highly viral probability ideas based on your topics or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fa fa-bolt', 30, 1, 1, '2023-03-25 23:29:01', NULL),
            (31, 4, 'Custom prompt', '{text}', '{\"translations\":{\"english\":{\"name\":\"Custom prompt\",\"description\":\"Ask our AI for anything & he will do it is best to give you quality content.\"}},\"inputs\":{\"text\":{\"icon\":\"fa fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Question or task\",\"placeholder\":\"What are the top 5 most tourist friendly destinations?\",\"help\":null}}}}}', 'fa fa-star', 31, 1, 1, '2023-03-25 23:29:23', NULL);",

            
            "CREATE TABLE `documents` (
            `document_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int DEFAULT NULL,
            `project_id` int DEFAULT NULL,
            `template_id` bigint unsigned DEFAULT NULL,
            `template_category_id` bigint unsigned DEFAULT NULL,
            `name` varchar(64) DEFAULT NULL,
            `type` varchar(32) DEFAULT NULL,
            `input` text,
            `content` text,
            `words` int unsigned DEFAULT NULL,
            `settings` text,
            `model` varchar(64) DEFAULT NULL,
            `api_response_time` int unsigned DEFAULT NULL,
            `datetime` datetime DEFAULT NULL,
            `last_datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`document_id`),
            KEY `user_id` (`user_id`),
            KEY `project_id` (`project_id`),
            KEY `documents_templates_template_id_fk` (`template_id`),
            KEY `documents_templates_categories_template_category_id_fk` (`template_category_id`),
            CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `documents_templates_categories_template_category_id_fk` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `documents_templates_template_id_fk` FOREIGN KEY (`template_id`) REFERENCES `templates` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            "CREATE TABLE `images` (
            `image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int DEFAULT NULL,
            `project_id` int DEFAULT NULL,
            `variants_ids` text,
            `name` varchar(64) DEFAULT NULL,
            `input` text,
            `image` varchar(40) DEFAULT NULL,
            `style` varchar(128) DEFAULT NULL,
            `artist` varchar(128) DEFAULT NULL,
            `lighting` varchar(128) DEFAULT NULL,
            `mood` varchar(128) DEFAULT NULL,
            `size` varchar(16) DEFAULT NULL,
            `settings` text,
            `api_response_time` int unsigned DEFAULT NULL,
            `datetime` datetime DEFAULT NULL,
            `last_datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`image_id`),
            KEY `user_id` (`user_id`),
            KEY `project_id` (`project_id`),
            CONSTRAINT `images_projects_project_id_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `images_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            "CREATE TABLE `transcriptions` (
            `transcription_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int DEFAULT NULL,
            `project_id` int DEFAULT NULL,
            `name` varchar(64) DEFAULT NULL,
            `input` text,
            `content` text,
            `words` int unsigned DEFAULT NULL,
            `language` varchar(32) DEFAULT NULL,
            `settings` text,
            `api_response_time` int unsigned DEFAULT NULL,
            `datetime` datetime DEFAULT NULL,
            `last_datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`transcription_id`),
            KEY `user_id` (`user_id`),
            KEY `project_id` (`project_id`),
            CONSTRAINT `transcriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `transcriptions_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            "CREATE TABLE `chats` (
            `chat_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int DEFAULT NULL,
            `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `settings` text COLLATE utf8mb4_unicode_ci,
            `total_messages` int unsigned DEFAULT '0',
            `used_tokens` int unsigned DEFAULT '0',
            `datetime` datetime DEFAULT NULL,
            `last_datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`chat_id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

            "CREATE TABLE `chats_messages` (
            `chat_message_id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `chat_id` bigint unsigned DEFAULT NULL,
            `user_id` int DEFAULT NULL,
            `role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `content` text COLLATE utf8mb4_unicode_ci,
            `model` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `api_response_time` int unsigned DEFAULT NULL,
            `datetime` datetime DEFAULT NULL,
            PRIMARY KEY (`chat_message_id`),
            KEY `chat_id` (`chat_id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `chats_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `chats_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        ];

        foreach($queries as $query) {
            database()->query($query);
        }

        return Plugin::save_status(self::$plugin_id, 'active');

    }

    public static function uninstall() {

        /* Run the installation process of the plugin */
        $queries = [
            "DELETE FROM `settings` WHERE `key` = 'aix';",
            "DELETE FROM `settings` WHERE `key` = 'ai_writer';",
            "alter table `users` drop ai_writer_words_current_month;",
            "alter table `users` drop aix_words_current_month;",
            "alter table `users` drop aix_images_current_month;",
            "alter table `users` drop aix_transcriptions_current_month;",
            "alter table `users` drop aix_chats_current_month;",
            "drop table `documents`",
            "drop table `images`",
            "drop table `transcriptions`",
            "drop table `chats_messages`",
            "drop table `chats`",
            "drop table `templates`",
            "drop table `templates_categories`",
        ];

        foreach($queries as $query) {
            try {
                database()->query($query);
            } catch (\Exception $exception) {
                // :)
            }
        }

        return Plugin::save_status(self::$plugin_id, 'uninstalled');

    }

    public static function activate() {
        return Plugin::save_status(self::$plugin_id, 'active');
    }

    public static function disable() {
        return Plugin::save_status(self::$plugin_id, 'installed');
    }

}
