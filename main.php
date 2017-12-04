<?php
/**
 * Created by PhpStorm.
 * User: kimura
 * Date: 2017/12/04
 * Time: 18:42
 */

main();
exit;

function main() {

    // クラスをそのまま
    $memA = new Member('Aさん', 10);
    $memA->data = 'Tokyo';

    $memB = new Member('Bさん', 21);
    $memB->data = $memA;

    $memC = new Member('Cさん', 32);
    $memC->data = 777;


    Util::out('========================================');
    Util::out('new Member() で初期化した値を var_dump() で表示');
    var_dump(['memA' => $memA, 'memB' => $memB, 'memC' => $memC]);
    Util::out('--------------------------');
    Util::out('new Member() で初期化した値を Util::toArray() で表示');
    var_dump(Util::toArray(['memA' => $memA, 'memB' => $memB, 'memC' => $memC]));

    Util::out(' ');


    // 配列に入れる
    $aryMem = [
        $memA, 100, $memB, "Hello", $memC,
    ];

    Util::out('========================================');
    Util::out('配列に入った色々なデータを var_dump() で表示');
    var_dump(['aryMem' => $aryMem]);

    Util::out('========================================');
    Util::out('配列に入った色々なデータを Util::toArray() で表示');
    var_dump(Util::toArray(['aryMem' => $aryMem]));
}


/**
 * Class Member
 */
class Member {

    public $name;
    public $age;

    // 人により色々なデータが入る
    public $data;


    /**
     * コンストラクタ
     */
    function __construct($name='', $age=0) {
        $this->name = $name;
        $this->age  = $age;
    }

    /**
     * 配列にして返す
     */
    function toArray() {
        return ['name' => $this->name, 'age' => $this->age, 'data' => $this->data];
    }

}


/**
 * Class Util
 * 動作テスト用にtoArray()だけ実装したクラス
 */
class Util {

    // 再起させる回数
    const TO_ARRAY_LOOP_MAX = 10;

    // 再起させてtoArrayを呼べるものは呼ぶ
    // stdClassの場合は配列にキャスつするように変更  2012/05/16 ohtsuki
    static function toArray($var, $count=0) {

        $ret = $var;

        if( self::TO_ARRAY_LOOP_MAX < $count ) {
            // 再起しすぎを止める(オブジェクトのまま返す)
            return $var;
        } else {
            // toArray()を呼べるなら
            if( self::methodExists($var,'toArray') ) {
                return $var->toArray();
            }else {
                // stdClassの場合は配列にして返す
                if(is_object($var) && get_class($var) == 'stdClass'){
                    $var = (array)$var;
                    $ret = $var;
                }

                // 配列の場合は再起させる
                if( is_array($var) ) {
                    $count++;
                    foreach($var as $key => $value) {
                        $ret[$key] = self::toArray($value, $count);
                    }
                }
            }
        }

        return $ret;
    }


    /**
     * $instanceにメソッドがあるか調べる
     * objectではないのならメソットは存在し得ない
     * method_existsで未知のクラスが呼ばれるとautoloaderが走るのをis_objectで防ぐ
     *
     * @author tecokimura
     * @param object $instance 調べるインスタンス
     * @param string $methodName 関数名前を調べる
     * @return bool TRUE=メソッドがある、FALSE=メソッドがない
     */
    static function methodExists($instance,$methodName) {
        return (is_object($instance) && method_exists($instance, $methodName));
    }


    /**
     * 文字出力用の関数
     */
    static function out($str) {
        echo $str. PHP_EOL;
    }
}
