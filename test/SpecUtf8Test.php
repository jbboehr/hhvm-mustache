<?php
class SpecUtf8Test extends PHPUnit_Framework_TestCase{
    public function testLotus() {
        $test = array (
  'name' => 'Lotus',
  'desc' => 'Lotus',
  'data' => 
  array (
  ),
  'template' => '妙法蓮華経',
  'expected' => '妙法蓮華経',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^妙法蓮華経$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testBird() {
        $test = array (
  'name' => 'Bird',
  'desc' => 'Bird',
  'data' => 
  array (
    'animal' => '鳥',
  ),
  'template' => '{{animal}}は卵の中から抜け出ようと戦う。卵は世界だ。生まれようと欲する物は、一つの世界を破壊しなければならない。{{animal}}は神に向かって飛ぶ。神の名はＡｂｒａｘａｓと言う。',
  'expected' => '鳥は卵の中から抜け出ようと戦う。卵は世界だ。生まれようと欲する物は、一つの世界を破壊しなければならない。鳥は神に向かって飛ぶ。神の名はＡｂｒａｘａｓと言う。',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^鳥は卵の中から抜け出ようと戦う。卵は世界だ。生まれようと欲する物は、一つの世界を破壊しなければならない。鳥は神に向かって飛ぶ。神の名はＡｂｒａｘａｓと言う。$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testRedBean() {
        $test = array (
  'name' => 'Red Bean',
  'desc' => 'Red Bean',
  'data' => 
  array (
    'noms' => '紅豆',
    'location' => '南國',
  ),
  'template' => '{{noms}}生{{location}} 春來發幾枝 願君多采擷 此物最相思',
  'expected' => '紅豆生南國 春來發幾枝 願君多采擷 此物最相思',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^紅豆生南國\\s*春來發幾枝\\s*願君多采擷\\s*此物最相思$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
