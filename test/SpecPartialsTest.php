<?php
class SpecPartialsTest extends PHPUnit_Framework_TestCase{
    public function testBasicBehavior() {
        $test = array (
  'name' => 'Basic Behavior',
  'desc' => 'The greater-than operator should expand to the named partial.',
  'data' => 
  array (
  ),
  'template' => '"{{>text}}"',
  'partials' => 
  array (
    'text' => 'from partial',
  ),
  'expected' => '"from partial"',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^"from\\s*partial"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testFailedLookup() {
        $test = array (
  'name' => 'Failed Lookup',
  'desc' => 'The empty string should be used when the named partial is not found.',
  'data' => 
  array (
  ),
  'template' => '"{{>text}}"',
  'partials' => 
  array (
  ),
  'expected' => '""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testContext() {
        $test = array (
  'name' => 'Context',
  'desc' => 'The greater-than operator should operate within the current context.',
  'data' => 
  array (
    'text' => 'content',
  ),
  'template' => '"{{>partial}}"',
  'partials' => 
  array (
    'partial' => '*{{text}}*',
  ),
  'expected' => '"*content*"',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^"\\*content\\*"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testRecursion() {
        $test = array (
  'name' => 'Recursion',
  'desc' => 'The greater-than operator should properly recurse.',
  'data' => 
  array (
    'content' => 'X',
    'nodes' => 
    array (
      0 => 
      array (
        'content' => 'Y',
        'nodes' => 
        array (
        ),
      ),
    ),
  ),
  'template' => '{{>node}}',
  'partials' => 
  array (
    'node' => '{{content}}<{{#nodes}}{{>node}}{{/nodes}}>',
  ),
  'expected' => 'X<Y<>>',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^X\\<Y\\<\\>\\>$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSurroundingWhitespace() {
        $test = array (
  'name' => 'Surrounding Whitespace',
  'desc' => 'The greater-than operator should not alter surrounding whitespace.',
  'data' => 
  array (
  ),
  'template' => '| {{>partial}} |',
  'partials' => 
  array (
    'partial' => '	|	',
  ),
  'expected' => '| 	|	 |',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\|\\s*\\|\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testInlineIndentation() {
        $test = array (
  'name' => 'Inline Indentation',
  'desc' => 'Whitespace should be left untouched.',
  'data' => 
  array (
    'data' => '|',
  ),
  'template' => '  {{data}}  {{> partial}}
',
  'partials' => 
  array (
    'partial' => '>
>',
  ),
  'expected' => '  |  >
>
',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\s*\\|\\s*\\>\\s*\\>\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneLineEndings() {
        $test = array (
  'name' => 'Standalone Line Endings',
  'desc' => '"\\r\\n" should be considered a newline for standalone tags.',
  'data' => 
  array (
  ),
  'template' => '|
{{>partial}}
|',
  'partials' => 
  array (
    'partial' => '>',
  ),
  'expected' => '|
>|',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/\\s*|\\s*>\\s*|\\s*/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutPreviousLine() {
        $test = array (
  'name' => 'Standalone Without Previous Line',
  'desc' => 'Standalone tags should not require a newline to precede them.',
  'data' => 
  array (
  ),
  'template' => '  {{>partial}}
>',
  'partials' => 
  array (
    'partial' => '>
>',
  ),
  'expected' => '  >
  >>',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/\\s*>\\s*>\\s*>\\s*/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutNewline() {
        $test = array (
  'name' => 'Standalone Without Newline',
  'desc' => 'Standalone tags should not require a newline to follow them.',
  'data' => 
  array (
  ),
  'template' => '>
  {{>partial}}',
  'partials' => 
  array (
    'partial' => '>
>',
  ),
  'expected' => '>
  >
  >',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\>\\s*\\>\\s*\\>$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneIndentation() {
        $test = array (
  'name' => 'Standalone Indentation',
  'desc' => 'Each line of the partial should be indented before rendering.',
  'data' => 
  array (
    'content' => '<
->',
  ),
  'template' => '\\
 {{>partial}}
/
',
  'partials' => 
  array (
    'partial' => '|
{{{content}}}
|
',
  ),
  'expected' => '\\
 |
 <
->
 |
/
',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\\\\\s*\\|\\s*\\<\\s*\\-\\>\\s*\\|\\s*\\/\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testPaddingWhitespace() {
        $test = array (
  'name' => 'Padding Whitespace',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
    'boolean' => true,
  ),
  'template' => '|{{> partial }}|',
  'partials' => 
  array (
    'partial' => '[]',
  ),
  'expected' => '|[]|',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\|\\[\\]\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
