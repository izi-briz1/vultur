CREATE TABLE `articles`
(
    `id`           int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `description`  varchar(511) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `body`         text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `image`        varchar(255) CHARACTER SET ascii COLLATE ascii_bin NULL DEFAULT NULL,
    `views`        int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created_at`   timestamp NOT NULL DEFAULT current_timestamp(),
    `published_at` timestamp NULL DEFAULT NULL,
    `updated_at`   timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX          `IK_articles_views`(`views` DESC) USING BTREE,
    INDEX          `IK_articles_published_at`(`published_at` DESC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;