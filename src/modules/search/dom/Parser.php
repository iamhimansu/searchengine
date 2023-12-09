<?php

namespace uims\searchengine\modules\search\dom;

use DOMDocument as Dom;
use DOMXPath as Xpath;

class Parser
{
    /**
     * Removes extra spaces
     * @param $buffer
     * @return array|string|string[]|null
     */
    private static function minify(&$buffer)
    {
        $search = array(
            /*'/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/',*/
            '/(?:\/\*(?>[^*]*\*+)*?\/)|(?:\/\/.*)/',
            '/\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$/m',
            '/>\s+</',           // strip whitespaces before and after tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array(
            '',
            "$1",
            '><',
            '\\1',
            ''
        );

        return $buffer = preg_replace($search, $replace, $buffer);
    }

    /**
     * @param $html
     * Creates a html dom for the given html
     * @return array $html
     * @throws Exception
     */
    public static function createDom($html, $minify = true): array
    {
        if ($minify) {
            self::minify($html);
        }

        try {
            $dom = new Dom();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            $dom->strictErrorChecking = false;
            // Access $dom as an instance of Dom
            $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOBLANKS | LIBXML_HTML_NOIMPLIED);
            $dom->normalizeDocument(); // removes extra spaces

            //Remove doctype as this can cause some errors in PDFs
            if ($dom->doctype) {
                $dom->removeChild($dom->doctype);
            }
            //xPath
            $xpath = new Xpath($dom);
            $xpath->registerNamespace('style', 'http://www.w3.org/1999/xhtml');

            return [$dom, $xpath];
        } catch (Exception $e) {
            self::Log($e);
            throw $e;
        }
    }

    /**
     * Adds to log
     * @param $data
     * @return void
     * @throws Exception
     */
    private static function Log($data)
    {
        //Implement log
        /*throw new Exception((string)$data);*/
    }
}