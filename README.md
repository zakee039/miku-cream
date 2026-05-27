# miku-cream

一个为 [Typecho](https://typecho.org) 设计的极简响应式主题，灵感来自暖奶油色调与初音未来。

![Typecho](https://img.shields.io/badge/Typecho-1.3%2B-orange?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Version](https://img.shields.io/badge/Version-1.0.0-blue?style=flat-square)

## 特性

- 🎨 **暖奶油底色**（`#F3F0EE`）搭配圆角 stadium 卡片，温柔而干净
- 🧅 **大葱分割线**：站名与导航栏之间横亘着一根自适应宽度的大葱，left/middle/right 三段无缝拼接
- 🗂 **桌面三列文章卡片**，平板双列，移动端单列自适应
- 📱 **移动端抽屉式导航**，左上角浮动按钮，支持二级分类展开
- 🔍 **悬停下拉子分类**，子项以圆角 chip 排列，透明桥接解决 hover 断层问题
- 🌙 **深色圆角 Footer**，紧凑布局，支持动态登录状态检测与友情链接

## 截图

> 在浏览器中访问 [zakee.fun](https://zakee.fun) 查看实际效果

## 文件结构

```
miku-cream/
├── style.css        # 主样式表（CSS 变量 + 全局排版 + 响应式）
├── header.php       # 站点头部（品牌行 + 大葱分割线 + 导航行 + 移动端抽屉）
├── footer.php       # 站点底部（友情链接 + 版权信息）
├── index.php        # 首页文章列表（三列卡片）
├── archive.php      # 分类/标签归档列表
├── post.php         # 单篇文章阅读页
├── page.php         # 独立页面模板
├── comments.php     # 评论区与留言表单
├── functions.php    # 主题配置 + getCategoryTree() 分类树辅助函数
└── img/
    ├── left.png     # 大葱左端（43×100px）
    ├── middle.png   # 大葱茎部（可横向拉伸，64×100px）
    └── right.png    # 大葱右端/叶部（193×100px）
```

## 安装

1. 下载本仓库，将 `miku-cream` 文件夹放入 Typecho 的 `usr/themes/` 目录
2. 登录 Typecho 后台 → **外观** → 找到 **miku-cream** → 点击**启用**

## 设计说明

- 主色调取自 Mastercard 设计系统的暖奶油语言（canvas cream `#F3F0EE`）
- 橙色信号色（`#F37338`）用于悬停高亮与强调色
- 字体使用 [Sofia Sans](https://fonts.google.com/specimen/Sofia+Sans)（Google Fonts），接近 MarkForMC 的几何感
- 大葱分割线采用 `background-size: 100% 100%` 横向拉伸中段，消除 `repeat-x` 带来的像素锯齿

## 主题信息

| 项目 | 内容 |
|------|------|
| **主题名** | miku-cream |
| **作者** | zakee |
| **版本** | 1.0.0 |
| **适配** | Typecho 1.3+ |
| **开源协议** | MIT |

## 致谢

感谢 Typecho 提供的博客框架，以及初音未来带来的灵感与陪伴。

---

*「札记」发音同 zakee，也是这个主题名字的来源。*
