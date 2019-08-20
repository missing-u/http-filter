### 参数过滤


###  原出处: https://laracasts.com/series/eloquent-techniques/episodes/4


###  说明

<h3>
    如果使用　多个where 条件 那么可能的写法是　如下
</h3>

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



###有些过滤器　和是否传递参数无关　


  有些过滤器　和是否传递参数无关　
  比如　分页　　当不传递　page　参数时
  也需要　添加过滤器　 page=默认值　(1)
 
  实现方式
 
  获取到所有　trait
 
  将　trait 中的　方法　
 




参考　实现　InvokeFilterRegardlessRequest
对　trait 添加属性　
```
static $invoke_regardless_request = "{$方法名}";
```

即可　在　HttpFilter 里自动出发　对应的　方法　(无参数)



certainfunc 里的trait  调用时候　必须初始化　$this->request 
