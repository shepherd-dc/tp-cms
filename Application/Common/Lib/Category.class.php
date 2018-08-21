<?php 
namespace Common\Lib;
class Category{
	static public function toLevel( $cate, $delimiter='--', $pid=0, $level=0 ){
		$arr = array();
		foreach($cate as $v){
			if( $v['pid'] == $pid ){
				$v['level'] = $level+1;
				$v['delimiter'] = str_repeat($delimiter, $level);
				$arr[] = $v;
				$arr = array_merge( $arr, self::toLevel($cate, $delimiter, $v['id'], $v['level']) ); 
			}
		}
		return $arr;
	}


	/**
	 * 传递一个子分类id，返回所有父分类
	 */
	static public function getParents( $cate, $id ){
		$arr = array();
		foreach($cate as $v){
			if( $v['id'] == $id ){
				$arr[] = $v;
				$arr = array_merge( self::getParents($cate, $v['pid']), $arr );
			}
		}
		return $arr;
	}


	/**
	 * 将父分类改造成多维数组，'key'对应$name
	 */
	static function toUpLayer( $cate, $name='parent', $pid=0 ){
		$arr = array();
		foreach($cate as $v){
			if( $v['id'] == $pid ){
				$v[$name] = self::toUpLayer( $cate, $name, $v['pid'] );
				$arr[] = $v;
			}
		}
		return $arr;
	}


	/**
	 * 传递一个父级分类id，返回所有子分类
	 */
	static public function getChildren( $cate, $pid ){
		$arr = array();
		foreach($cate as $v){
			if( $v['pid'] == $pid ){
				$arr[] = $v;
				$arr = array_merge( $arr, self::getChildren($cate, $v['id']) );
			}
		}
		return $arr;
	}

	/**
	 * 将子分类改造成多维数组，'key'对应$name
	 */
	static function toDownLayer( $cate, $name='child', $pid=0 ){
		$arr = array();
		foreach($cate as $v){
			if( $v['pid'] == $pid ){
				$v[$name] = self::toDownLayer( $cate, $name, $v['id'] );
				$arr[] = $v;
			}
		}
		return $arr;
	}

	
	/**
	 * 返回所有子分类的ID
	 * @param     array      $cate [description]
	 * @param     int        $pid  [description]
	 * @param     int        $flag [默认值0表示不保存顶级ID]
	 * @return    [type]            [description]
	 */
	static public function getChildrenId( $cate, $pid, $flag=0 ){
		$arr = array();
		if($flag){
			$arr[] = $pid;
		}
		foreach($cate as $v){
			if( $v['pid'] == $pid ){
				$arr[] = $v['id'];
				$arr = array_merge( $arr, self::getChildrenId($cate, $v['id']) );
			}
		}
		return $arr;
	}

	/**
	 * 返回指定ID的分类信息
	 * @param     [type]      $cate [description]
	 * @param     [type]      $id   [description]
	 * @return    [type]            [description]
	 */
	static public function getSelf( $cate, $id ){
		$arr = array();
		foreach($cate as $v){
			if($v['id']==$id){
				$arr = $v;
				return $arr;
			}
		}
		return $arr;
	}



}


