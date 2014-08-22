<?php
class SpecInterpolationTest extends PHPUnit_Framework_TestCase{
    public function testNoInterpolation() {
        $test = array (
  'name' => 'No Interpolation',
  'desc' => 'Mustache-free templates should render as-is.',
  'data' => 
  array (
  ),
  'template' => 'Hello from {Mustache}!
',
  'expected' => 'Hello from {Mustache}!
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^Hello\\s*from\\s*\\{Mustache\\}\\!\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testBasicInterpolation() {
        $test = array (
  'name' => 'Basic Interpolation',
  'desc' => 'Unadorned tags should interpolate content into the template.',
  'data' => 
  array (
    'subject' => 'world',
  ),
  'template' => 'Hello, {{subject}}!
',
  'expected' => 'Hello, world!
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^Hello,\\s*world\\!\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testHTMLEscaping() {
        $test = array (
  'name' => 'HTML Escaping',
  'desc' => 'Basic interpolation should be HTML escaped.',
  'data' => 
  array (
    'forbidden' => '& " < >',
  ),
  'template' => 'These characters should be HTML escaped: {{forbidden}}
',
  'expected' => 'These characters should be HTML escaped: &amp; &quot; &lt; &gt;
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^These\\s*characters\\s*should\\s*be\\s*HTML\\s*escaped\\:\\s*&amp;\\s*&quot;\\s*&lt;\\s*&gt;\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustache() {
        $test = array (
  'name' => 'Triple Mustache',
  'desc' => 'Triple mustaches should interpolate without HTML escaping.',
  'data' => 
  array (
    'forbidden' => '& " < >',
  ),
  'template' => 'These characters should not be HTML escaped: {{{forbidden}}}
',
  'expected' => 'These characters should not be HTML escaped: & " < >
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^These\\s*characters\\s*should\\s*not\\s*be\\s*HTML\\s*escaped\\:\\s*&\\s*"\\s*\\<\\s*\\>\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersand() {
        $test = array (
  'name' => 'Ampersand',
  'desc' => 'Ampersand should interpolate without HTML escaping.',
  'data' => 
  array (
    'forbidden' => '& " < >',
  ),
  'template' => 'These characters should not be HTML escaped: {{&forbidden}}
',
  'expected' => 'These characters should not be HTML escaped: & " < >
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^These\\s*characters\\s*should\\s*not\\s*be\\s*HTML\\s*escaped\\:\\s*&\\s*"\\s*\\<\\s*\\>\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testBasicIntegerInterpolation() {
        $test = array (
  'name' => 'Basic Integer Interpolation',
  'desc' => 'Integers should interpolate seamlessly.',
  'data' => 
  array (
    'mph' => 85,
  ),
  'template' => '"{{mph}} miles an hour!"',
  'expected' => '"85 miles an hour!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"85\\s*miles\\s*an\\s*hour\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustacheIntegerInterpolation() {
        $test = array (
  'name' => 'Triple Mustache Integer Interpolation',
  'desc' => 'Integers should interpolate seamlessly.',
  'data' => 
  array (
    'mph' => 85,
  ),
  'template' => '"{{{mph}}} miles an hour!"',
  'expected' => '"85 miles an hour!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"85\\s*miles\\s*an\\s*hour\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersandIntegerInterpolation() {
        $test = array (
  'name' => 'Ampersand Integer Interpolation',
  'desc' => 'Integers should interpolate seamlessly.',
  'data' => 
  array (
    'mph' => 85,
  ),
  'template' => '"{{&mph}} miles an hour!"',
  'expected' => '"85 miles an hour!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"85\\s*miles\\s*an\\s*hour\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testBasicDecimalInterpolation() {
        $test = array (
  'name' => 'Basic Decimal Interpolation',
  'desc' => 'Decimals should interpolate seamlessly with proper significance.',
  'data' => 
  array (
    'power' => 1.21,
  ),
  'template' => '"{{power}} jiggawatts!"',
  'expected' => '"1.21 jiggawatts!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"1\\.21\\s*jiggawatts\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustacheDecimalInterpolation() {
        $test = array (
  'name' => 'Triple Mustache Decimal Interpolation',
  'desc' => 'Decimals should interpolate seamlessly with proper significance.',
  'data' => 
  array (
    'power' => 1.21,
  ),
  'template' => '"{{{power}}} jiggawatts!"',
  'expected' => '"1.21 jiggawatts!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"1\\.21\\s*jiggawatts\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersandDecimalInterpolation() {
        $test = array (
  'name' => 'Ampersand Decimal Interpolation',
  'desc' => 'Decimals should interpolate seamlessly with proper significance.',
  'data' => 
  array (
    'power' => 1.21,
  ),
  'template' => '"{{&power}} jiggawatts!"',
  'expected' => '"1.21 jiggawatts!"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"1\\.21\\s*jiggawatts\\!"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testBasicContextMissInterpolation() {
        $test = array (
  'name' => 'Basic Context Miss Interpolation',
  'desc' => 'Failed context lookups should default to empty strings.',
  'data' => 
  array (
  ),
  'template' => 'I ({{cannot}}) be seen!',
  'expected' => 'I () be seen!',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^I\\s*\\(\\)\\s*be\\s*seen\\!$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustacheContextMissInterpolation() {
        $test = array (
  'name' => 'Triple Mustache Context Miss Interpolation',
  'desc' => 'Failed context lookups should default to empty strings.',
  'data' => 
  array (
  ),
  'template' => 'I ({{{cannot}}}) be seen!',
  'expected' => 'I () be seen!',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^I\\s*\\(\\)\\s*be\\s*seen\\!$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersandContextMissInterpolation() {
        $test = array (
  'name' => 'Ampersand Context Miss Interpolation',
  'desc' => 'Failed context lookups should default to empty strings.',
  'data' => 
  array (
  ),
  'template' => 'I ({{&cannot}}) be seen!',
  'expected' => 'I () be seen!',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^I\\s*\\(\\)\\s*be\\s*seen\\!$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesBasicInterpolation() {
        $test = array (
  'name' => 'Dotted Names - Basic Interpolation',
  'desc' => 'Dotted names should be considered a form of shorthand for sections.',
  'data' => 
  array (
    'person' => 
    array (
      'name' => 'Joe',
    ),
  ),
  'template' => '"{{person.name}}" == "{{#person}}{{name}}{{/person}}"',
  'expected' => '"Joe" == "Joe"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Joe"\\s*\\=\\=\\s*"Joe"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesTripleMustacheInterpolation() {
        $test = array (
  'name' => 'Dotted Names - Triple Mustache Interpolation',
  'desc' => 'Dotted names should be considered a form of shorthand for sections.',
  'data' => 
  array (
    'person' => 
    array (
      'name' => 'Joe',
    ),
  ),
  'template' => '"{{{person.name}}}" == "{{#person}}{{{name}}}{{/person}}"',
  'expected' => '"Joe" == "Joe"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Joe"\\s*\\=\\=\\s*"Joe"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesAmpersandInterpolation() {
        $test = array (
  'name' => 'Dotted Names - Ampersand Interpolation',
  'desc' => 'Dotted names should be considered a form of shorthand for sections.',
  'data' => 
  array (
    'person' => 
    array (
      'name' => 'Joe',
    ),
  ),
  'template' => '"{{&person.name}}" == "{{#person}}{{&name}}{{/person}}"',
  'expected' => '"Joe" == "Joe"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Joe"\\s*\\=\\=\\s*"Joe"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesArbitraryDepth() {
        $test = array (
  'name' => 'Dotted Names - Arbitrary Depth',
  'desc' => 'Dotted names should be functional to any level of nesting.',
  'data' => 
  array (
    'a' => 
    array (
      'b' => 
      array (
        'c' => 
        array (
          'd' => 
          array (
            'e' => 
            array (
              'name' => 'Phil',
            ),
          ),
        ),
      ),
    ),
  ),
  'template' => '"{{a.b.c.d.e.name}}" == "Phil"',
  'expected' => '"Phil" == "Phil"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Phil"\\s*\\=\\=\\s*"Phil"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesBrokenChains() {
        $test = array (
  'name' => 'Dotted Names - Broken Chains',
  'desc' => 'Any falsey value prior to the last part of the name should yield \'\'.',
  'data' => 
  array (
    'a' => 
    array (
    ),
  ),
  'template' => '"{{a.b.c}}" == ""',
  'expected' => '"" == ""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""\\s*\\=\\=\\s*""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesBrokenChainResolution() {
        $test = array (
  'name' => 'Dotted Names - Broken Chain Resolution',
  'desc' => 'Each part of a dotted name should resolve only against its parent.',
  'data' => 
  array (
    'a' => 
    array (
      'b' => 
      array (
      ),
    ),
    'c' => 
    array (
      'name' => 'Jim',
    ),
  ),
  'template' => '"{{a.b.c.name}}" == ""',
  'expected' => '"" == ""',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^""\\s*\\=\\=\\s*""$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesInitialResolution() {
        $test = array (
  'name' => 'Dotted Names - Initial Resolution',
  'desc' => 'The first part of a dotted name should resolve as any other name.',
  'data' => 
  array (
    'a' => 
    array (
      'b' => 
      array (
        'c' => 
        array (
          'd' => 
          array (
            'e' => 
            array (
              'name' => 'Phil',
            ),
          ),
        ),
      ),
    ),
    'b' => 
    array (
      'c' => 
      array (
        'd' => 
        array (
          'e' => 
          array (
            'name' => 'Wrong',
          ),
        ),
      ),
    ),
  ),
  'template' => '"{{#a}}{{b.c.d.e.name}}{{/a}}" == "Phil"',
  'expected' => '"Phil" == "Phil"',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^"Phil"\\s*\\=\\=\\s*"Phil"$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testDottedNamesContextPrecedence() {
        $test = array (
  'name' => 'Dotted Names - Context Precedence',
  'desc' => 'Dotted names should be resolved against former resolutions.',
  'data' => 
  array (
    'a' => 
    array (
      'b' => 
      array (
      ),
    ),
    'b' => 
    array (
      'c' => 'ERROR',
    ),
  ),
  'template' => '{{#a}}{{b.c}}{{/a}}',
  'expected' => '',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testInterpolationSurroundingWhitespace() {
        $test = array (
  'name' => 'Interpolation - Surrounding Whitespace',
  'desc' => 'Interpolation should not alter surrounding whitespace.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '| {{string}} |',
  'expected' => '| --- |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*\\-\\-\\-\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustacheSurroundingWhitespace() {
        $test = array (
  'name' => 'Triple Mustache - Surrounding Whitespace',
  'desc' => 'Interpolation should not alter surrounding whitespace.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '| {{{string}}} |',
  'expected' => '| --- |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*\\-\\-\\-\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersandSurroundingWhitespace() {
        $test = array (
  'name' => 'Ampersand - Surrounding Whitespace',
  'desc' => 'Interpolation should not alter surrounding whitespace.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '| {{&string}} |',
  'expected' => '| --- |',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\s*\\-\\-\\-\\s*\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testInterpolationStandalone() {
        $test = array (
  'name' => 'Interpolation - Standalone',
  'desc' => 'Standalone interpolation should not alter surrounding whitespace.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '  {{string}}
',
  'expected' => '  ---
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*\\-\\-\\-\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustacheStandalone() {
        $test = array (
  'name' => 'Triple Mustache - Standalone',
  'desc' => 'Standalone interpolation should not alter surrounding whitespace.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '  {{{string}}}
',
  'expected' => '  ---
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*\\-\\-\\-\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersandStandalone() {
        $test = array (
  'name' => 'Ampersand - Standalone',
  'desc' => 'Standalone interpolation should not alter surrounding whitespace.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '  {{&string}}
',
  'expected' => '  ---
',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\s*\\-\\-\\-\\s*$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testInterpolationWithPadding() {
        $test = array (
  'name' => 'Interpolation With Padding',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '|{{ string }}|',
  'expected' => '|---|',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\-\\-\\-\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testTripleMustacheWithPadding() {
        $test = array (
  'name' => 'Triple Mustache With Padding',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '|{{{ string }}}|',
  'expected' => '|---|',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\-\\-\\-\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

    public function testAmpersandWithPadding() {
        $test = array (
  'name' => 'Ampersand With Padding',
  'desc' => 'Superfluous in-tag whitespace should be ignored.',
  'data' => 
  array (
    'string' => '---',
  ),
  'template' => '|{{& string }}|',
  'expected' => '|---|',
);
        $result = mustache_render($test['template'], $test['data']);

        $expectRegExp = '/^\\|\\-\\-\\-\\|$/m';
        $this->assertRegExp($expectRegExp, $result);
    }

}
