<?php
/**
 * Part of composer package: axelitus/asseter
 *
 * @package     axelitus\Asseter
 * @version     0.1
 * @author      Axel Pardemann (axelitusdev@gmail.com)
 * @license     MIT License
 * @copyright   2013 - Axel Pardemann
 * @link        http://axelitus.mx/projects/axelitus/asseter
 */

namespace axelitus\Asseter;

class TestsHtml extends TestCase
{
    /**
     * @test
     */
    public function test_arrayToAttr()
    {
        $expected = 'id="myTest" title="This is my test attribute array to attributes." style="border: 1px solid green;"';
        $output = Html::arrayToAttr(
            [
                'id'    => 'myTest',
                'title' => 'This is my test attribute array to attributes.',
                'style' => 'border: 1px solid green;'
            ]
        );
        $this->assertEquals($expected, $output);

        $expected = 'id="inName" name="theName" autofocus';
        $output = Html::arrayToAttr(['id' => 'inName', 'name' => 'theName', 'autofocus']);
        $this->assertEquals($expected, $output);

        $expected = 'id="inName" name="theName" disabled="disabled"';
        $output = Html::arrayToAttr(['id' => 'inName', 'name' => 'theName', 'disabled'], true);
        $this->assertEquals($expected, $output);
    }

    /**
     * @test
     * @depends test_arrayToAttr
     */
    public function test_tag()
    {
        $expected = '<a href="http://google.com" name="anchorGoogle">Navigate to Google</a>';
        $output = Html::tag('a', ['href' => 'http://google.com', 'name' => 'anchorGoogle'], 'Navigate to Google');
        $this->assertEquals($expected, $output);

        $expected = '<img src="assets/img/test.jpg" id="imgTest">';
        $output = Html::tag('img', ['src' => 'assets/img/test.jpg', 'id' => 'imgTest']);
        $this->assertEquals($expected, $output);

        $expected = '<img src="assets/img/test.jpg" id="imgTest">';
        $output = Html::tag('img', ['src' => 'assets/img/test.jpg', 'id' => 'imgTest']);
        $this->assertEquals($expected, $output);

        $expected = '<br>';
        $output = Html::tag('br');
        $this->assertEquals($expected, $output);

        $expected = '<br />';
        $output = Html::tag('br', null, null, true);
        $this->assertEquals($expected, $output);
    }

    /**
     * @test
     * @depends test_tag
     */
    public function test_css()
    {
        $css = <<<'CSS'
.style1 {
    border: 1px solid blue;
    width: 100%;
}

div.style2 {
    border: 1px solid yellow;
}
CSS;

        $expected = <<<INLINE_CSS
<style type="text/css">
$css
</style>
INLINE_CSS;
        $output = Html::css($css, [], true);
        $this->assertEquals($expected, $output);

        $expected = '<link type="text/css" rel="stylesheet" href="assets/css/styles.css">';
        $output = Html::css('assets/css/styles.css', []);
        $this->assertEquals($expected, $output);
    }

    /**
     * @test
     * @depends test_tag
     */
    public function test_script()
    {
        $script = <<<'SCRIPT'
alert('This is an alert!');
SCRIPT;

        $expected = <<<INLINE_SCRIPT
<script type="text/javascript">
$script
</script>
INLINE_SCRIPT;
        $output = Html::script($script, [], true);
        $this->assertEquals($expected, $output);

        $expected = '<script type="text/javascript" src="assets/js/script.js"></script>';
        $output = Html::script('assets/js/script.js', []);
        $this->assertEquals($expected, $output);
    }
}
