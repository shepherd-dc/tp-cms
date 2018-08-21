<?php 
namespace Common\TagLib;
use Think\Template\TagLib;
class Shepherd extends TagLib{
	protected $tags = array(
		//导航列表
		'navlist' => array(
			'attr'  => '',
			'close' => 1, //是否闭合
		),
	);
	public function _navlist($attr,$content){
		$str = <<<str
		<?php
		\$_navlist=getCategory(1);
		\$_navlist=Common\Lib\Category::toDownLayer(\$_navlist);
		foreach(\$_navlist as \$navlist):
			\$navlist['url']=get_list_url(\$navlist);
		?>
str;
		$str .= $content;
		$str .= "<?php endforeach;?>";
		return $str;
	}
}
/*输出结果：
	<?php
	 $_navlist=getCategory(1); 
	 $_navlist=Common\Lib\Category::toDownLayer($_navlist); 
	 foreach($_navlist as $navlist): 
	 	$navlist['url']=get_list_url($navlist); 
	?>
	 <li><a href="<?php echo ($navlist["url"]); ?>"><?php echo ($navlist["name"]); ?></a></li>
	<?php endforeach;?>
*/