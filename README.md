# ThinkPHP仿A岛匿名版

ThinkPHP框架练手作，Api完全与A岛官方一致（参照[Api.md](https://github.com/wsdzl/nimingban_tp/blob/master/API.md)）

------

Api接口已全部完成（修改一下A岛开源app的服务器和图片cdn应该可以正常使用）

包括：获取饼干、获取板块列表、获取板块串、获取串内容、发表新串、查询订阅、增加订阅、删除订阅、图片接口、略缩图接口。

前端页面编码中，计划PC版使用bs框架，响应式布局，不使用ajax（便于seo），手机版页面前后端完全分离，全ajax数据获取。

##版本日志

**v1.1**

使用ThinkPHP内置图像处理类

**v1.0**

初始版本，完成全部Api接口，可用于A岛app。