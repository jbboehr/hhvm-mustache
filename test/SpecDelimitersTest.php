<?php
class SpecDelimitersTest extends PHPUnit_Framework_TestCase{
    public function testPairBehavior() {
        $test = array (
  'name' => 'Pair Behavior',
  'desc' => 'The equals sign (used on both sides) should permit delimiter changes.',
  'data' => 
  array (
    'text' => 'Hey!',
  ),
  'template' => '{{=<% %>=}}(<%text%>)',
  'expected' => '(Hey!)',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\(Hey\\!\\)$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSpecialCharacters() {
        $test = array (
  'name' => 'Special Characters',
  'desc' => 'Characters with special meaning regexen should be valid delimiters.',
  'data' => 
  array (
    'text' => 'It worked!',
  ),
  'template' => '({{=[ ]=}}[text])',
  'expected' => '(It worked!)',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\(It\\s*worked\\!\\)$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSections() {
        $test = array (
  'name' => 'Sections',
  'desc' => 'Delimiters set outside sections should persist.',
  'data' => 
  array (
    'section' => true,
    'data' => 'I got interpolated.',
  ),
  'template' => '[
{{#section}}
  {{data}}
  |data|
{{/section}}

{{= | | =}}
|#section|
  {{data}}
  |data|
|/section|
]
',
  'expected' => '[
  I got interpolated.
  |data|

  {{data}}
  I got interpolated.
]
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\[\\s*I\\s*got\\s*interpolated\\.\\s*\\|data\\|\\s*\\{\\{data\\}\\}\\s*I\\s*got\\s*interpolated\\.\\s*\\]\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testInvertedSections() {
        $test = array (
  'name' => 'Inverted Sections',
  'desc' => 'Delimiters set outside inverted sections should persist.',
  'data' => 
  array (
    'section' => false,
    'data' => 'I got interpolated.',
  ),
  'template' => '[
{{^section}}
  {{data}}
  |data|
{{/section}}

{{= | | =}}
|^section|
  {{data}}
  |data|
|/section|
]
',
  'expected' => '[
  I got interpolated.
  |data|

  {{data}}
  I got interpolated.
]
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\[\\s*I\\s*got\\s*interpolated\\.\\s*\\|data\\|\\s*\\{\\{data\\}\\}\\s*I\\s*got\\s*interpolated\\.\\s*\\]\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testPartialInheritence() {
        $test = array (
  'name' => 'Partial Inheritence',
  'desc' => 'Delimiters set in a parent template should not affect a partial.',
  'data' => 
  array (
    'value' => 'yes',
  ),
  'partials' => 
  array (
    'include' => '.{{value}}.',
  ),
  'template' => '[ {{>include}} ]
{{= | | =}}
[ |>include| ]
',
  'expected' => '[ .yes. ]
[ .yes. ]
',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\[\\s*\\.yes\\.\\s*\\]\\s*\\[\\s*\\.yes\\.\\s*\\]\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testPostPartialBehavior() {
        $test = array (
  'name' => 'Post-Partial Behavior',
  'desc' => 'Delimiters set in a partial should not affect the parent template.',
  'data' => 
  array (
    'value' => 'yes',
  ),
  'partials' => 
  array (
    'include' => '.{{value}}. {{= | | =}} .|value|.',
  ),
  'template' => '[ {{>include}} ]
[ .{{value}}.  .|value|. ]
',
  'expected' => '[ .yes.  .yes. ]
[ .yes.  .|value|. ]
',
);
        $result = mustache_render($test['template'], $test['data'], $test['partials']);

        $expectRegExp = '/^\\[\\s*\\.yes\\.\\s*\\.yes\\.\\s*\\]\\s*\\[\\s*\\.yes\\.\\s*\\.\\|value\\|\\.\\s*\\]\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSurroundingWhitespace() {
        $test = array (
  'name' => 'Surrounding Whitespace',
  'desc' => 'Surrounding whitespace should be left untouched.',
  'data' => 
  array (
  ),
  'template' => '| {{=@ @=}} |',
  'expected' => '|  |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testOutlyingWhitespaceInline() {
        $test = array (
  'name' => 'Outlying Whitespace (Inline)',
  'desc' => 'Whitespace should be left untouched.',
  'data' => 
  array (
  ),
  'template' => ' | {{=@ @=}}
',
  'expected' => ' | 
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*\\|\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneTag() {
        $test = array (
  'name' => 'Standalone Tag',
  'desc' => 'Standalone lines should be removed from the template.',
  'data' => 
  array (
  ),
  'template' => 'Begin.
{{=@ @=}}
End.
',
  'expected' => 'Begin.
End.
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^Begin\\.\\s*End\\.\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testIndentedStandaloneTag() {
        $test = array (
  'name' => 'Indented Standalone Tag',
  'desc' => 'Indented standalone lines should be removed from the template.',
  'data' => 
  array (
  ),
  'template' => 'Begin.
  {{=@ @=}}
End.
',
  'expected' => 'Begin.
End.
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^Begin\\.\\s*End\\.\\s*$/m';
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
{{= @ @ =}}
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
  ),
  'template' => '  {{=@ @=}}
=',
  'expected' => '=',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\=$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutNewline() {
        $test = array (
  'name' => 'Standalone Without Newline',
  'desc' => 'Standalone tags should not require a newline to follow them.',
  'data' => 
  array (
  ),
  'template' => '=
  {{=@ @=}}',
  'expected' => '=
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\=\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testPairWithPadding() {
        $test = array (
  'name' => 'Pair with Padding',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
  ),
  'template' => '|{{= @   @ =}}|',
  'expected' => '||',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
