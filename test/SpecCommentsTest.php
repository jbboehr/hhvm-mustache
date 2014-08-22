<?php
class SpecCommentsTest extends PHPUnit_Framework_TestCase{
    public function testInline() {
        $test = array (
  'name' => 'Inline',
  'desc' => 'Comment blocks should be removed from the template.',
  'data' => 
  array (
  ),
  'template' => '12345{{! Comment Block! }}67890',
  'expected' => '1234567890',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^1234567890$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testMultiline() {
        $test = array (
  'name' => 'Multiline',
  'desc' => 'Multiline comments should be permitted.',
  'data' => 
  array (
  ),
  'template' => '12345{{!
  This is a
  multi-line comment...
}}67890
',
  'expected' => '1234567890
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^1234567890\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandalone() {
        $test = array (
  'name' => 'Standalone',
  'desc' => 'All standalone comment lines should be removed.',
  'data' => 
  array (
  ),
  'template' => 'Begin.
{{! Comment Block! }}
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

    public function testIndentedStandalone() {
        $test = array (
  'name' => 'Indented Standalone',
  'desc' => 'All standalone comment lines should be removed.',
  'data' => 
  array (
  ),
  'template' => 'Begin.
  {{! Indented Comment Block! }}
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
{{! Standalone Comment }}
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
  'template' => '  {{! I\'m Still Standalone }}
!',
  'expected' => '!',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\!$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testStandaloneWithoutNewline() {
        $test = array (
  'name' => 'Standalone Without Newline',
  'desc' => 'Standalone tags should not require a newline to follow them.',
  'data' => 
  array (
  ),
  'template' => '!
  {{! I\'m Still Standalone }}',
  'expected' => '!
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\!\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testMultilineStandalone() {
        $test = array (
  'name' => 'Multiline Standalone',
  'desc' => 'All standalone comment lines should be removed.',
  'data' => 
  array (
  ),
  'template' => 'Begin.
{{!
Something\'s going on here...
}}
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

    public function testIndentedMultilineStandalone() {
        $test = array (
  'name' => 'Indented Multiline Standalone',
  'desc' => 'All standalone comment lines should be removed.',
  'data' => 
  array (
  ),
  'template' => 'Begin.
  {{!
    Something\'s going on here...
  }}
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

    public function testIndentedInline() {
        $test = array (
  'name' => 'Indented Inline',
  'desc' => 'Inline comments should not strip whitespace',
  'data' => 
  array (
  ),
  'template' => '  12 {{! 34 }}
',
  'expected' => '  12 
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*12\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testSurroundingWhitespace() {
        $test = array (
  'name' => 'Surrounding Whitespace',
  'desc' => 'Comment removal should preserve surrounding whitespace.',
  'data' => 
  array (
  ),
  'template' => '12345 {{! Comment Block! }} 67890',
  'expected' => '12345  67890',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^12345\\s*67890$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
