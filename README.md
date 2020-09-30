### 参数过滤

###  原出处: https://laracasts.com/series/eloquent-techniques/episodes/4

<h3>
想法(或者说目的)
</h3>

```
对于列表的查询　
往往伴随较多的筛选(包含查询)条件　
这些筛选条件往往作用于 model 之上
其中有一些筛选条件是公共的　
例如分页数,每页的数量,对id的倒序 
对每一个列表重复这些将使逻辑部分代码变得臃肿
基于这样一种经验:
当我们需要修改代码或者排查问题的时候(列表接口)
对传递的过滤条件参数是较少关心的
或者说关注点是分离的 
即
要么关心为什么传了某个过滤参数导致错误(不生效或者没数据等等)
要么关心的是不传递任何参数下 返回数据是什么样子的(为什么不符合预期)

由此产生一个朴素的想法 
将这些冗长的过滤条件拆分到单独的类 (filter类) 
一个筛选条件对应类中的一个方法(方便起见,约定 参数名与方法名相同)
将公共常用的筛选条件抽取到一个trait以便复用
借由laravel的scope得以实现这一想法
```


###  对这些筛选条件的分类

```
这种分类方式未必合理 更多体现的是处理的细节
将筛选条件分为两类
第一类 
通过 http 请求 显式传递过来的 有则过滤 没有则不额外处理
比如 GET|POST 允许传递参数 name 
如果传递 name = 'yang' 
则筛选 name like '%yang%'的记录
如果不传递 则不处理
第二类 
不是通过 http 请求传递过来的 或者不论是否通过http请求传递参数
过滤条件都需要生效
比如 order by 这个过滤条件

对于第一类过滤条件 
我们对传递过来的参数做遍历
对于每一个参数 如果存在与参数名相同的函数 则调用这个函数


对于第二类过滤条件
我们可以在 __construct 中手动调用
但是特别的 因为这些条件使用的非常频繁 
出于编码的简洁的目的
我们做如下约定

当 filter类中 存在 匹配 auto_invoked_register_%s  这样格式的静态属性名时  
我们将会调用 以 这个属性对应的值 为函数名的函数
理论上 %s 这个地方填写什么都可以 
它的作用是防止引入多个 trait  时候 属性名冲突
约定
一般情况下 令 %s = 这个属性的值 

示例
    本项目下 src/IsolatedFilter/OrderByFilter.php 文件中的 OrderByFilter 类
存在属性
    static $invoke_regardless_request_order_by = 'order_by'
那么 order_by 函数将会被调用

```

###  示例

#####  不使用 Filter
```

$pass_params = request()->all();

$where = [];

if (isset($pass_params[ 'mobile' ])) {
    $where[ 'mobile' ] = [
        'mobile','like',$pass_params['mobile']
    ];
}

if (isset($pass_params[ 'idcard' ])) {
    $where[ 'idcard' ] = [
        'idcard','like',$pass_params['idcard']
    ];
}

if (isset($pass_params[ 'from' ])) {
    $where[ 'from' ] = [
        'created_time','>=',$pass_params['from']
    ];
}

if (isset($pass_params[ 'to' ])) {
    $where[ 'to' ] = [
        'created_time','>=',$pass_params['to']
    ];
}

Person::where(
    $where
);

```

#####  使用 Filter
```
    
class PersonIndexFilter extends \HttpFilter\Filter
{
    use \HttpFilter\IsolatedFilter\TimePeriod;

    public function __construct($request)
    {
        parent::__construct($request);

        //如果 字段是 created_at 
        //那么就不用调用下面这里
        //哪这个举个例子
        $this->setTimeColumnName('create_time');
    }

    public function mobile(int $mobile)
    {
        $this->builder->where(
            'mobile', 'like', $mobile
        );
    }

    public function idcard(string $idcard)
    {
        $this->builder->where(
            'idcard', 'like', $idcard
        );
    }
}

$filter = new PersonIndexFilter(request());

Person::addGlobalScope($filter);

TpShopInfo::first();
```
