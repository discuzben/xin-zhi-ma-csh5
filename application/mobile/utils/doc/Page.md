# Page.php

## 解释

分页类

## 配置文件

`config/api/page.php`

## 用法

#### 客户端传入的数据

在查询字符串中提供(`get`)：

````
page
````

字段！这个可以在配置文件中更改！

#### 服务器处理

````
// 引入 Page 类
use app\api\util\Page;

// 查询条件
$where = [];
// 第一步：获取总记录数！
$count = db()->name('test')->where($where)->count();
// 获取分页信息
$page = Page::deal($count);
// 第二步：获取分页数据
$data = db()->name('test')->where($where)->limit($page['offset'] , $page['limit'])->select();
// 第三部：返回的数据统一如下
$res = Page::data($page , $data);
````

其中 `Page::data($page , $data)` 返回的数据格式如下：

````
[
    // 最小页数
    'min_page' => 1 ,
    // 最大页数
    'max_page' => 1 ,
    // 总记录数
    'count' => 1 ,
    // 每页显示记录数
    'limit' => 10 ,
    // 当前页数
    'page' => 10 ,
    // 数据
    'data' => $res
]
````

以上的数据格式可以被更改！请在配置文件 `config/api/page.php` 中进行更改！