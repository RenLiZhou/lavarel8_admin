/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1_3306
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 127.0.0.1:3306
 Source Schema         : lvbase8

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 26/09/2022 09:41:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for lv_admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `lv_admin_logs`;
CREATE TABLE `lv_admin_logs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建者',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '日志类型',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求url',
  `method` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方式',
  `param` json NOT NULL COMMENT '请求参数',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_admin_id`(`admin_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lv_admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for lv_admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `lv_admin_roles`;
CREATE TABLE `lv_admin_roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_role_id_rule_id`(`admin_id`, `role_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of lv_admin_roles
-- ----------------------------
INSERT INTO `lv_admin_roles` VALUES (1, 1, 1, '2021-05-28 08:37:38', '2021-05-28 08:37:38');

-- ----------------------------
-- Table structure for lv_admins
-- ----------------------------
DROP TABLE IF EXISTS `lv_admins`;
CREATE TABLE `lv_admins`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '邮箱',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '登录令牌',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '账号状态',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lv_admins
-- ----------------------------
INSERT INTO `lv_admins` VALUES (1, 'admin', '$2y$12$rQdJekLHMNPAEoDICNvb.Or0hS/NBNCMexB5D0vEqHFrg4dKpu/M6', '15011111111', '123456@qq1.com', NULL, 1, '2021-05-28 08:37:38', '2021-08-31 07:27:59');

-- ----------------------------
-- Table structure for lv_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `lv_failed_jobs`;
CREATE TABLE `lv_failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lv_failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for lv_migrations
-- ----------------------------
DROP TABLE IF EXISTS `lv_migrations`;
CREATE TABLE `lv_migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lv_migrations
-- ----------------------------
INSERT INTO `lv_migrations` VALUES (1, '2021_09_03_022632_star_sql', 1);
INSERT INTO `lv_migrations` VALUES (2, '2021_11_23_092223_create_failed_jobs_table', 1);

-- ----------------------------
-- Table structure for lv_role_rules
-- ----------------------------
DROP TABLE IF EXISTS `lv_role_rules`;
CREATE TABLE `lv_role_rules`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `rule_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_role_id_rule_id`(`role_id`, `rule_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of lv_role_rules
-- ----------------------------
INSERT INTO `lv_role_rules` VALUES (25, 1, 28, NULL, NULL);
INSERT INTO `lv_role_rules` VALUES (2, 1, 2, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (3, 1, 3, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (4, 1, 4, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (5, 1, 5, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (6, 1, 6, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (7, 1, 7, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (8, 1, 8, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (9, 1, 9, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (10, 1, 10, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (11, 1, 11, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (12, 1, 12, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (13, 1, 13, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (14, 1, 14, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (15, 1, 15, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (16, 1, 16, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (17, 1, 17, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (18, 1, 18, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (19, 1, 19, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (20, 1, 20, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (21, 1, 21, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (22, 1, 22, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (23, 1, 23, '2021-05-28 08:37:38', '2021-05-28 08:37:38');
INSERT INTO `lv_role_rules` VALUES (24, 1, 24, '2021-05-28 08:37:38', '2021-05-28 08:37:38');

-- ----------------------------
-- Table structure for lv_roles
-- ----------------------------
DROP TABLE IF EXISTS `lv_roles`;
CREATE TABLE `lv_roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户组名',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lv_roles
-- ----------------------------
INSERT INTO `lv_roles` VALUES (1, '超级管理员', '2021-05-28 08:37:38', '2021-05-28 08:37:38');

-- ----------------------------
-- Table structure for lv_rules
-- ----------------------------
DROP TABLE IF EXISTS `lv_rules`;
CREATE TABLE `lv_rules`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限菜单名称',
  `href` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '链接url',
  `rule` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '控制器方法',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级id',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '类型:0仅权限,1菜单和权限',
  `level` tinyint(4) NOT NULL DEFAULT 0,
  `icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` smallint(6) NOT NULL DEFAULT 0 COMMENT '排序',
  `islog` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否需要记录日志:0不需要,1需要',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lv_rules
-- ----------------------------
INSERT INTO `lv_rules` VALUES (2, '修改密码', '', 'admin.admin.safe', 4, 0, 3, '', 50, 1, NULL, '2021-09-01 08:02:40');
INSERT INTO `lv_rules` VALUES (3, '权限管理', NULL, NULL, 0, 1, 1, 'layui-icon-vercode', 51, 0, NULL, '2021-09-01 08:02:26');
INSERT INTO `lv_rules` VALUES (4, '管理员', '/admin/admin', 'admin.admin.index', 3, 1, 2, NULL, 50, 0, NULL, NULL);
INSERT INTO `lv_rules` VALUES (5, '添加管理员页面', NULL, 'admin.admin.create', 4, 0, 3, NULL, 50, 0, NULL, NULL);
INSERT INTO `lv_rules` VALUES (6, '添加管理员', NULL, 'admin.admin.store', 4, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (7, '禁用管理员', NULL, 'admin.admin.active', 4, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (8, '编辑管理员页面', NULL, 'admin.admin.edit', 4, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (9, '编辑管理员', NULL, 'admin.admin.update', 4, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (10, '权限列表', '/admin/rule', 'admin.rule.index', 3, 1, 2, NULL, 50, 0, NULL, NULL);
INSERT INTO `lv_rules` VALUES (11, '添加权限页面', NULL, 'admin.rule.create', 10, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (12, '添加权限', NULL, 'admin.rule.store', 10, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (13, '编辑权限页面', NULL, 'admin.rule.edit', 10, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (14, '编辑权限', NULL, 'admin.rule.update', 10, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (15, '删除权限', NULL, 'admin.rule.destroy', 10, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (16, '角色列表', '/admin/role', 'admin.role.index', 3, 1, 2, NULL, 50, 0, NULL, NULL);
INSERT INTO `lv_rules` VALUES (17, '添加角色', NULL, 'admin.role.store', 16, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (18, '编辑角色', NULL, 'admin.role.update', 16, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (19, '删除角色', NULL, 'admin.role.destroy', 16, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (20, '配置权限页面', NULL, 'admin.role.set', 16, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (21, '配置权限', NULL, 'admin.role.setted', 16, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (22, '删除管理员', NULL, 'admin.admin.destroy', 4, 0, 3, NULL, 50, 1, NULL, NULL);
INSERT INTO `lv_rules` VALUES (23, '系统管理', NULL, NULL, 0, 1, 1, 'layui-icon-set', 50, 0, NULL, NULL);
INSERT INTO `lv_rules` VALUES (24, '管理员日志', '/admin/system/adminlog', 'admin.admin.adminlog', 23, 1, 2, NULL, 50, 0, NULL, NULL);
INSERT INTO `lv_rules` VALUES (28, '更新记录日志状态', '', 'admin.rule.islog', 10, 0, 3, '', 50, 1, '2021-08-13 06:09:03', '2021-09-02 01:42:31');

SET FOREIGN_KEY_CHECKS = 1;
