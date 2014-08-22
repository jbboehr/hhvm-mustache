<?php
class SpecInvertedTest extends PHPUnit_Framework_TestCase{
    public function testFalsey() {
        $test = array (
  'name' => 'Falsey',
  'desc' => 'Falsey sections should have their contents rendered.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '"{{^boolean}}This should be rendered.{{/boolean}}"',
  'expected' => '"This should be rendered."',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"This\\s*should\\s*be\\s*rendered\\."$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTruthy() {
        $test = array (
  'name' => 'Truthy',
  'desc' => 'Truthy sections should have their contents omitted.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '"{{^boolean}}This should not be rendered.{{/boolean}}"',
  'expected' => '""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testContext() {
        $test = array (
  'name' => 'Context',
  'desc' => 'Objects and hashes should behave like truthy values.',
  'data' => 
  array (
    'context' => 
    array (
      'name' => 'Joe',
    ),
  ),
  'template' => '"{{^context}}Hi {{name}}.{{/context}}"',
  'expected' => '""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testList() {
        $test = array (
  'name' => 'List',
  'desc' => 'Lists should behave like truthy values.',
  'data' => 
  array (
    'list' => 
    array (
      0 => 
      array (
        'n' => 1,
      ),
      1 => 
      array (
        'n' => 2,
      ),
      2 => 
      array (
        'n' => 3,
      ),
    ),
  ),
  'template' => '"{{^list}}{{n}}{{/list}}"',
  'expected' => '""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testEmptyList() {
        $test = array (
  'name' => 'Empty List',
  'desc' => 'Empty lists should behave like falsey values.',
  'data' => 
  array (
    'list' => 
    array (
    ),
  ),
  'template' => '"{{^list}}Yay lists!{{/list}}"',
  'expected' => '"Yay lists!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Yay\\s*lists\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDoubled() {
        $test = array (
  'name' => 'Doubled',
  'desc' => 'Multiple inverted sections per template should be permitted.',
  'data' => 
  array (
    'bool' => false,
    'two' => 'second',
  ),
  'template' => '{{^bool}}
* first
{{/bool}}
* {{two}}
{{^bool}}
* third
{{/bool}}
',
  'expected' => '* first
* second
* third
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\*\\s*first\\s*\\*\\s*second\\s*\\*\\s*third\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testNestedFalsey() {
        $test = array (
  'name' => 'Nested (Falsey)',
  'desc' => 'Nested falsey sections should have their contents rendered.',
  'data' => 
  array (
    'bool' => false,
  ),
  'template' => '| A {{^bool}}B {{^bool}}C{{/bool}} D{{/bool}} E |',
  'expected' => '| A B C D E |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*A\\s*B\\s*C\\s*D\\s*E\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testNestedTruthy() {
        $test = array (
  'name' => 'Nested (Truthy)',
  'desc' => 'Nested truthy sections should be omitted.',
  'data' => 
  array (
    'bool' => true,
  ),
  'template' => '| A {{^bool}}B {{^bool}}C{{/bool}} D{{/bool}} E |',
  'expected' => '| A  E |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*A\\s*E\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testContextMisses() {
        $test = array (
  'name' => 'Context Misses',
  'desc' => 'Failed context lookups should be considered falsey.',
  'data' => 
  array (
  ),
  'template' => '[{{^missing}}Cannot find key \'missing\'!{{/missing}}]',
  'expected' => '[Cannot find key \'missing\'!]',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\[Cannot\\s*find\\s*key\\s*\'missing\'\\!\\]$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesTruthy() {
        $test = array (
  'name' => 'Dotted Names - Truthy',
  'desc' => 'Dotted names should be valid for Inverted Section tags.',
  'data' => 
  array (
    'a' => 
    array (
      'b' => 
      array (
        'c' => true,
      ),
    ),
  ),
  'template' => '"{{^a.b.c}}Not Here{{/a.b.c}}" == ""',
  'expected' => '"" == ""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""\\s*\\=\\=\\s*""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesFalsey() {
        $test = array (
  'name' => 'Dotted Names - Falsey',
  'desc' => 'Dotted names should be valid for Inverted Section tags.',
  'data' => 
  array (
    'a' => 
    array (
      'b' => 
      array (
        'c' => false,
      ),
    ),
  ),
  'template' => '"{{^a.b.c}}Not Here{{/a.b.c}}" == "Not Here"',
  'expected' => '"Not Here" == "Not Here"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Not\\s*Here"\\s*\\=\\=\\s*"Not\\s*Here"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesBrokenChains() {
        $test = array (
  'name' => 'Dotted Names - Broken Chains',
  'desc' => 'Dotted names that cannot be resolved should be considered falsey.',
  'data' => 
  array (
    'a' => 
    array (
    ),
  ),
  'template' => '"{{^a.b.c}}Not Here{{/a.b.c}}" == "Not Here"',
  'expected' => '"Not Here" == "Not Here"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Not\\s*Here"\\s*\\=\\=\\s*"Not\\s*Here"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSurroundingWhitespace() {
        $test = array (
  'name' => 'Surrounding Whitespace',
  'desc' => 'Inverted sections should not alter surrounding whitespace.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => ' | {{^boolean}}	|	{{/boolean}} | 
',
  'expected' => ' | 	|	 | 
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*\\|\\s*\\|\\s*\\|\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testInternalWhitespace() {
        $test = array (
  'name' => 'Internal Whitespace',
  'desc' => 'Inverted should not alter internal whitespace.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => ' | {{^boolean}} {{! Important Whitespace }}
 {{/boolean}} | 
',
  'expected' => ' |  
  | 
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*\\|\\s*\\|\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testIndentedInlineSections() {
        $test = array (
  'name' => 'Indented Inline Sections',
  'desc' => 'Single-line sections should not alter surrounding whitespace.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => ' {{^boolean}}NO{{/boolean}}
 {{^boolean}}WAY{{/boolean}}
',
  'expected' => ' NO
 WAY
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*NO\\s*WAY\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneLines() {
        $test = array (
  'name' => 'Standalone Lines',
  'desc' => 'Standalone lines should be removed from the template.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '| This Is
{{^boolean}}
|
{{/boolean}}
| A Line
',
  'expected' => '| This Is
|
| A Line
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*This\\s*Is\\s*\\|\\s*\\|\\s*A\\s*Line\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneIndentedLines() {
        $test = array (
  'name' => 'Standalone Indented Lines',
  'desc' => 'Standalone indented lines should be removed from the template.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '| This Is
  {{^boolean}}
|
  {{/boolean}}
| A Line
',
  'expected' => '| This Is
|
| A Line
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*This\\s*Is\\s*\\|\\s*\\|\\s*A\\s*Line\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneLineEndings() {
        $test = array (
  'name' => 'Standalone Line Endings',
  'desc' => '"\\r\\n" should be considered a newline for standalone tags.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '|
{{^boolean}}
{{/boolean}}
|',
  'expected' => '|
|',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutPreviousLine() {
        $test = array (
  'name' => 'Standalone Without Previous Line',
  'desc' => 'Standalone tags should not require a newline to precede them.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '  {{^boolean}}
^{{/boolean}}
/',
  'expected' => '^
/',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\^\\s*\\/$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutNewline() {
        $test = array (
  'name' => 'Standalone Without Newline',
  'desc' => 'Standalone tags should not require a newline to follow them.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '^{{^boolean}}
/
  {{/boolean}}',
  'expected' => '^
/
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\^\\s*\\/\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testPadding() {
        $test = array (
  'name' => 'Padding',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '|{{^ boolean }}={{/ boolean }}|',
  'expected' => '|=|',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\=\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
