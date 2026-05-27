# NYCK 短链接管理系统

轻量级短链接管理系统，基于纯 PHP + JSON 文件存储构建，无需数据库依赖，开箱即用。

## 核心功能

- **短链接生成** - 自动生成 6 位随机短码，支持自定义短码
- **访问统计** - 实时记录每条短链接的累计访问量（PV）
- **过期控制** - 为短链接设置有效期，到期后自动停止访问
- **多账号协作** - 管理员和普通用户两种角色，数据隔离
- **302 跳转** - 快速稳定的短码跳转服务

## 快速开始

### 环境要求

- PHP 8.1+
- 无需 MySQL/SQLite

### 启动服务

```bash
cd short-url/public
php -S 0.0.0.0:8080 router.php
```

访问 http://localhost:8080

### 默认账号

- 用户名：`admin`
- 密码：`admin123456`

## 系统角色

| 角色 | 权限 |
|------|------|
| 管理员 | 管理所有用户和短链，查看全站数据 |
| 普通用户 | 管理自己的短链，查看自己的访问统计 |

## 页面入口

| 页面 | 地址 |
|------|------|
| 官网首页 | `/home.php` |
| 登录入口 | `/login.php` |
| 注册入口 | `/register.php` |
| 后台仪表盘 | `/index.php` |
| 短链管理 | `/index.php?page=links` |
| 用户管理 | `/index.php?page=users` |
| 短链跳转 | `/{短码}` |

## 项目结构

```
short-url/
├── app/                # 应用代码
│   ├── Controllers/    # 控制器
│   ├── Services/       # 业务逻辑
│   ├── Repositories/   # 数据仓储
│   ├── Middleware/      # 中间件
│   ├── Support/        # 工具类
│   └── Views/          # 视图模板
├── config/             # 配置文件
├── public/             # 入口文件
├── storage/            # 数据存储
│   └── data/           # JSON 数据文件
├── assets/             # 静态资源
│   ├── css/
│   ├── js/
│   └── images/
└── tests/              # 测试用例
```

## 技术栈

- **后端**: PHP 8.1+
- **存储**: JSON 文件 + 文件锁
- **测试**: PHPUnit
- **静态分析**: PHPStan
- **代码规范**: PHP_CodeSniffer

## 运行测试

```bash
cd short-url

# 单元测试
php vendor/bin/phpunit

# 静态分析
php vendor/bin/phpstan analyse

# 代码规范
php vendor/bin/phpcs
```

## 设计风格

- iOS 轻质感 UI
- 浅灰背景 `#F2F2F7`
- 白色卡片 `#FFFFFF`
- 主色调 `#007AFF`
- 响应式布局，支持移动端

## 适用场景

- 个人短链接服务
- 团队内部链接管理
- 营销活动短链追踪
- 临时链接分享
- 渠道链接统计

## 跨平台兼容

- Windows
- Linux

## License

MIT
