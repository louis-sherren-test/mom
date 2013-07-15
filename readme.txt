一个简单的PHPhahahfjsakdl框架.

文件目录:
common: 全局常量定义和全局方法实现.
:x

lib:
	action: 用户自定义行为类.
	model:用户自定义模型类.
	core:框架核心文件.
tpl:用户自定义模板文件.
tpl_cache:框架自动生成编译后的模板文件.
index.php: 网站入口.

模型:

模型可选成员:
$_keys : array , value: 包含该表的全部键名.

$_keyMap : array, 在$_keys被设置后生效 .
	key:从客户端读取到的数据的键名.
	value: 真实数据库的键名.
	说明: 如果客户端传来的某个键名是 username,而该数据对应的数据库的键名又是name,则可以通过将该成员添加一个键值对, $_keyMap = array(..... , 'username'=>'name'); 来完成键名映射。

$_alias: array , 跟$_keyMap 作用相反.
	key:真实数据库的键名.
	value:别名.
	说明: 依然是上面那个例子,如果模板文件中的建名是username,而数据库的建名是name,则通过将该成员添加一个键值对,$_alias = array(..... , 'name'=>'username'); 来完成别名

$_table: 表名.

模型操作:

初始化一个模型:
$m = new TestModel();
快速初始化调用全局方法M，M支持创建混合模型（HybridModel）。
$m = M('Test');
$m = M(array('Test1','Test2'));

创建数据模型:
$data = $m->create($data = false);
@param data , array(), 如果设置为false,则会用$_POST来创建数据对象.
@return array(), 可以直接进行插入操作的数组.

summary:create方法完成丢失脏数据,键映射的操作,来返回有效的数据模型.

插入操作:
$m->insert($obj);
@param $obj , array(), 可以自定义,推荐传入create方法返回的数组（如果有模型文件）.
@return , bool

查找:
$m->select($fields = false,$multy = false);
@param $fields, array , 传入需要查找的键的名称,false则根据$_keys查找.
@param $multy , array , false:单条数据（一维数组）. true : 多条数据（二维数组）.
	单条数据:array('name'=>'zhangsan','old'=>'4','sex'=>'male');
	多条数据:array(
				array('name'=>'zhangsan','old'=>'4','sex'=>'male'),
				array('name'=>'lisi','old'=>'5','sex'=>'female'),
				);
@return,array.

更新:
$m->update();
类似插入操作.
@return , bool.

删除:
$m->delete()
@return , bool.

条件构成:
$m->table(); $m->where(); $m->limit(); $m->order(); .....
参数均为sql语句部分. 
@return $this;
比如:
$m->table('my_table')->where('id=1')->limit(3,5)->order('id desc')->select(false,false);

多表查:
方式一: 直接查找
$m->table('my_table m1,my_table2 m2')->where('m1.id = m2.tid')->select(false,true);

方式二: 通过Hybrid模型对象.
使用M方法传入数组则返回Hybrid对象.
$hm = M(array('test1','test2'));
$hm->multySelect();
