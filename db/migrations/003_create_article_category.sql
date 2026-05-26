CREATE TABLE `article_category`
(
    `article_id`  int(10) UNSIGNED NOT NULL,
    `category_id` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`article_id`, `category_id`) USING BTREE,
    INDEX      `FK_category_id`(`category_id` ASC) USING BTREE,
    CONSTRAINT `FK_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT `FK_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;