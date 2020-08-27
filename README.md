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
基于这样一个经验:
当我们需要修改代码或者排查问题的时候(列表接口)
那么对传递的过滤条件参数 是较少关心的
或者说关注点是分离的 
即
要么关心为什么传了某个过滤参数导致错误(不生效或者没数据等等)
要么关心的是不传递任何参数下 返回数据的不符合预期

由此产生一个朴素的想法 
将这些冗长的过滤条件拆分到单独的类 
一个筛选条件对应类中的一个方法
将一个公共的筛选条件抽取到一个trait以便复用
借由laravel的scope得以实现这一想法
```


###  对这些筛选条件的分类

```
这种分类方式未必合理 更多体现的是处理的细节
将条件分为两类

```
```
static $invoke_regardless_request = "{$方法名}";
```
```
即可　在　HttpFilter 里自动出发　对应的　方法　(无参数)



certainfunc 里的trait  调用时候　必须初始化　$this->request 
```


###  示例

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
    
           TradeApplicant::where(
               $where
           );
    
```
    
<h3>
    这样会使得代码非常臃肿 所以用这个包拆分代码
</h3>



