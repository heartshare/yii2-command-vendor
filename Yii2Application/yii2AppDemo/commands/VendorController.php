<?php 

namespace app\commands;
use Yii;
use yii\console\Controller;

/**
 * this command is clean vendor dirs and files, just keep usefull dirs and files.remove .git
 * 
 * yii vendor
 *
 * vendor目录太大了，使用以下命令直接让vendor变小
 * 执行 yii vendor 命令即可 .git目录会被删除
 * 
 * @author Sun Kang <68103403@qq.com>
 * @since 2.0
 */
class VendorController extends Controller
{
	public $path = [
		'.git','test','doc','docs'
	];
	public $file = [
		'README.md','composer.json',
		'CHANGELOG.md','.gitignore',
		'.jshintrc','.travis.yml','CONTRIBUTING.md','Gruntfile.js',
		'LICENSE','package.json','UPGRADE.md','LICENSE.md'
	];
	static $v;
	static $f;
    public function actionIndex()
    {
    	$vendor = realpath(YII2_PATH.'/../../'); 
    	$list = $this->dir($vendor);
		if(static::$v){
			foreach(static::$v as $v){
				$this->rmdir($v);
			}
		}
		if(static::$f){
			foreach(static::$f as $v){
				unlink($v);
			}
		}
		echo 'clean vendor finish.';
    }
    public function dir($vendor){
    	$list = scandir($vendor);
		foreach($list as $v){
			$dr = $vendor.'/'.$v;
			if(is_dir($dr) && $v!='.' && $v!='..'){
				 if(in_array($v,$this->path)){
				 	static::$v[] = $dr;
				 }
				 $this->dir($dr);
			}elseif(in_array($v,$this->file)){
				 	static::$f[] = $dr;
			}
		}
    }
    public function rmdir($dir)
    { 
        if(strtolower(substr(PHP_OS, 0, 3))=='win'){
        	$dir = str_replace('/','\\',$dir);
        	$ex = "rmdir /s/q ".$dir;
        }
        else{
        	$dir = str_replace('\\','/',$dir);
        	$ex = "rm -rf ".$dir;   
        } 
        @exec($ex); 
    }
}
