<?php
class SpecSectionsTest extends PHPUnit_Framework_TestCase{
    public function testTruthy() {
        $test = array (
  'name' => 'Truthy',
  'desc' => 'Truthy sections should have their contents rendered.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '"{{#boolean}}This should be rendered.{{/boolean}}"',
  'expected' => '"This should be rendered."',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"This\\s*should\\s*be\\s*rendered\\."$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testFalsey() {
        $test = array (
  'name' => 'Falsey',
  'desc' => 'Falsey sections should have their contents omitted.',
  'data' => 
  array (
    'boolean' => false,
  ),
  'template' => '"{{#boolean}}This should not be rendered.{{/boolean}}"',
  'expected' => '""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testContext() {
        $test = array (
  'name' => 'Context',
  'desc' => 'Objects and hashes should be pushed onto the context stack.',
  'data' => 
  array (
    'context' => 
    array (
      'name' => 'Joe',
    ),
  ),
  'template' => '"{{#context}}Hi {{name}}.{{/context}}"',
  'expected' => '"Hi Joe."',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Hi\\s*Joe\\."$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDeeplyNestedContexts() {
        $test = array (
  'name' => 'Deeply Nested Contexts',
  'desc' => 'All elements on the context stack should be accessible.',
  'data' => 
  array (
    'a' => 
    array (
      'one' => 1,
    ),
    'b' => 
    array (
      'two' => 2,
    ),
    'c' => 
    array (
      'three' => 3,
    ),
    'd' => 
    array (
      'four' => 4,
    ),
    'e' => 
    array (
      'five' => 5,
    ),
  ),
  'template' => '{{#a}}
{{one}}
{{#b}}
{{one}}{{two}}{{one}}
{{#c}}
{{one}}{{two}}{{three}}{{two}}{{one}}
{{#d}}
{{one}}{{two}}{{three}}{{four}}{{three}}{{two}}{{one}}
{{#e}}
{{one}}{{two}}{{three}}{{four}}{{five}}{{four}}{{three}}{{two}}{{one}}
{{/e}}
{{one}}{{two}}{{three}}{{four}}{{three}}{{two}}{{one}}
{{/d}}
{{one}}{{two}}{{three}}{{two}}{{one}}
{{/c}}
{{one}}{{two}}{{one}}
{{/b}}
{{one}}
{{/a}}
',
  'expected' => '1
121
12321
1234321
123454321
1234321
12321
121
1
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^1\\s*121\\s*12321\\s*1234321\\s*123454321\\s*1234321\\s*12321\\s*121\\s*1\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testList() {
        $test = array (
  'name' => 'List',
  'desc' => 'Lists should be iterated; list items should visit the context stack.',
  'data' => 
  array (
    'list' => 
    array (
      0 => 
      array (
        'item' => 1,
      ),
      1 => 
      array (
        'item' => 2,
      ),
      2 => 
      array (
        'item' => 3,
      ),
    ),
  ),
  'template' => '"{{#list}}{{item}}{{/list}}"',
  'expected' => '"123"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"123"$/m';
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
  'template' => '"{{#list}}Yay lists!{{/list}}"',
  'expected' => '""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDoubled() {
        $test = array (
  'name' => 'Doubled',
  'desc' => 'Multiple sections per template should be permitted.',
  'data' => 
  array (
    'bool' => true,
    'two' => 'second',
  ),
  'template' => '{{#bool}}
* first
{{/bool}}
* {{two}}
{{#bool}}
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

    public function testNestedTruthy() {
        $test = array (
  'name' => 'Nested (Truthy)',
  'desc' => 'Nested truthy sections should have their contents rendered.',
  'data' => 
  array (
    'bool' => true,
  ),
  'template' => '| A {{#bool}}B {{#bool}}C{{/bool}} D{{/bool}} E |',
  'expected' => '| A B C D E |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*A\\s*B\\s*C\\s*D\\s*E\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testNestedFalsey() {
        $test = array (
  'name' => 'Nested (Falsey)',
  'desc' => 'Nested falsey sections should be omitted.',
  'data' => 
  array (
    'bool' => false,
  ),
  'template' => '| A {{#bool}}B {{#bool}}C{{/bool}} D{{/bool}} E |',
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
  'template' => '[{{#missing}}Found key \'missing\'!{{/missing}}]',
  'expected' => '[]',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\[\\]$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testImplicitIteratorString() {
        $test = array (
  'name' => 'Implicit Iterator - String',
  'desc' => 'Implicit iterators should directly interpolate strings.',
  'data' => 
  array (
    'list' => 
    array (
      0 => 'a',
      1 => 'b',
      2 => 'c',
      3 => 'd',
      4 => 'e',
    ),
  ),
  'template' => '"{{#list}}({{.}}){{/list}}"',
  'expected' => '"(a)(b)(c)(d)(e)"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"\\(a\\)\\(b\\)\\(c\\)\\(d\\)\\(e\\)"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testImplicitIteratorInteger() {
        $test = array (
  'name' => 'Implicit Iterator - Integer',
  'desc' => 'Implicit iterators should cast integers to strings and interpolate.',
  'data' => 
  array (
    'list' => 
    array (
      0 => 1,
      1 => 2,
      2 => 3,
      3 => 4,
      4 => 5,
    ),
  ),
  'template' => '"{{#list}}({{.}}){{/list}}"',
  'expected' => '"(1)(2)(3)(4)(5)"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"\\(1\\)\\(2\\)\\(3\\)\\(4\\)\\(5\\)"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testImplicitIteratorDecimal() {
        $test = array (
  'name' => 'Implicit Iterator - Decimal',
  'desc' => 'Implicit iterators should cast decimals to strings and interpolate.',
  'data' => 
  array (
    'list' => 
    array (
      0 => 1.1,
      1 => 2.2,
      2 => 3.3,
      3 => 4.4,
      4 => 5.5,
    ),
  ),
  'template' => '"{{#list}}({{.}}){{/list}}"',
  'expected' => '"(1.1)(2.2)(3.3)(4.4)(5.5)"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"\\(1\\.1\\)\\(2\\.2\\)\\(3\\.3\\)\\(4\\.4\\)\\(5\\.5\\)"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesTruthy() {
        $test = array (
  'name' => 'Dotted Names - Truthy',
  'desc' => 'Dotted names should be valid for Section tags.',
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
  'template' => '"{{#a.b.c}}Here{{/a.b.c}}" == "Here"',
  'expected' => '"Here" == "Here"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Here"\\s*\\=\\=\\s*"Here"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesFalsey() {
        $test = array (
  'name' => 'Dotted Names - Falsey',
  'desc' => 'Dotted names should be valid for Section tags.',
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
  'template' => '"{{#a.b.c}}Here{{/a.b.c}}" == ""',
  'expected' => '"" == ""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""\\s*\\=\\=\\s*""$/m';
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
  'template' => '"{{#a.b.c}}Here{{/a.b.c}}" == ""',
  'expected' => '"" == ""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""\\s*\\=\\=\\s*""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSurroundingWhitespace() {
        $test = array (
  'name' => 'Surrounding Whitespace',
  'desc' => 'Sections should not alter surrounding whitespace.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => ' | {{#boolean}}	|	{{/boolean}} | 
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
  'desc' => 'Sections should not alter internal whitespace.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => ' | {{#boolean}} {{! Important Whitespace }}
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
    'boolean' => true,
  ),
  'template' => ' {{#boolean}}YES{{/boolean}}
 {{#boolean}}GOOD{{/boolean}}
',
  'expected' => ' YES
 GOOD
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*YES\\s*GOOD\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneLines() {
        $test = array (
  'name' => 'Standalone Lines',
  'desc' => 'Standalone lines should be removed from the template.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '| This Is
{{#boolean}}
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

    public function testIndentedStandaloneLines() {
        $test = array (
  'name' => 'Indented Standalone Lines',
  'desc' => 'Indented standalone lines should be removed from the template.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '| This Is
  {{#boolean}}
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
    'boolean' => true,
  ),
  'template' => '|
{{#boolean}}
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
    'boolean' => true,
  ),
  'template' => '  {{#boolean}}
#{{/boolean}}
/',
  'expected' => '#
/',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^#\\s*\\/$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutNewline() {
        $test = array (
  'name' => 'Standalone Without Newline',
  'desc' => 'Standalone tags should not require a newline to follow them.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '#{{#boolean}}
/
  {{/boolean}}',
  'expected' => '#
/
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^#\\s*\\/\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testPadding() {
        $test = array (
  'name' => 'Padding',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '|{{# boolean }}={{/ boolean }}|',
  'expected' => '|=|',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\=\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
