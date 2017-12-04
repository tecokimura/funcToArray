<?php
/**
 * Created by PhpStorm.
 * User: tecokimura
 * Date: 2017/12/04
 * Time: 18:42
 */

main();
exit;

/**
 * メイン処理
 * Util::toArray() の動作サンプル
 */
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

    // 再起させる最大回数
    const TO_ARRAY_LOOP_MAX = 10;

    /**
     * オブジェクトで
     *  toArrayを呼び出せる場合は呼び出し
     *  stdClassの場合は配列にキャストするようにする
     *
     * @param $var
     * @param int $count
     * @return array
     */
    static function toArray($var, $count=0) {

        $ret = $var;

        if( $count <= self::TO_ARRAY_LOOP_MAX ) {
            // toArray()を呼べるなら
            if( self::methodExists($var,'toArray') ) {
                $ret = self::toArray($var->toArray(), $count+1);
            }else {
                // stdClassの場合は配列にして返す
                if(is_object($var) && get_class($var) == 'stdClass'){
                    $var = (array)$var;
                }

                // 配列の場合は再起させる
                if( is_array($var) ) {
                    foreach($var as $key => $value) {
                        $ret[$key] = self::toArray($value, $count+1);
                    }
                } else {
                    $ret = $var;
                }
            }
        }

        return $ret;
    }


    /**
     * $instanceにメソッドがあるか調べる
     * objectではないのならメソットは存在し得ない
     * method_existsで未知のクラスが呼ばれるとauto_loaderが走るのをis_objectで防ぐ
     *
     * @param object $instance 調べるインスタンス
     * @param string $methodName 関数名前を調べる
     * @return bool TRUE=メソッドがある、FALSE=メソッドがない
     */
    static function methodExists($instance,$methodName) {
        return (is_object($instance) && method_exists($instance, $methodName));
    }


    /**
     * 文字出力して改行する関数
     *
     * @param String $str
     */
    static function out($str) {
        echo $str. PHP_EOL;
    }
}
