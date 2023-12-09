<?php

namespace uims\searchengine\modules\search\commands;

use app\components\helper\S3UploadHelper;
use uims\searchengine\modules\search\base\SearchEngineBaseApplication as SearchWebApp;
use uims\searchengine\modules\search\dom\Parser;
use uims\searchengine\modules\search\models\CrawlRequest;
use uims\user\modules\jiuser\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class CrawlController extends Controller
{
    public function actionIndex($id = null)
    {
        $this->interactive = true;
        $pendingRequests = CrawlRequest::find()
            ->where(['status' => CrawlRequest::STATUS_DRAFT])
            ->cache(7200);
        /** To handle relative urls */

//        $_SERVER['SCRIPT_FILENAME'] = 'uims/web/index.php';

        $config = require(Yii::getAlias('@app') . '/config/web.php');
        $webApp = new SearchWebApp($config);
        $user = User::find()->where(['username' => 'adminsamarth', 'status' => User::STATUS_ACTIVE])->cache(7200)->one();
        $webApp->getUser()->setIdentity($user);
        $webApp->getRequest()->setScriptUrl('uims/web/index.php');
        $webApp->layout = false;
        $webApp->setAliases(['@enginelayout' => 'main']);

        $elementAttributeMap = [
            'a' => ['href' => true],
            'img' => ['src' => true],
        ];

        $queryMap = [
            'text' => [
                'title'
            ],
            'html' => [
                'div.search',
                'div#klop'
            ],
            'tags' => [
                'a' => [
                    'attributes' => [
                        'href'
                    ]
                ]
            ]
        ];

        /* $route = 'search/crawler';
         $webApp->getRequest()->getHeaders()->set('X-Rewrite-Url', $route);
 
         try {
             $result = $webApp->runAction($route, [
                 'id' => 'b6d3c1feb473d073df4f062a363127e0893a00aa495c449188b6a48608e0b6f0123'
             ]);
 
             file_put_contents(Yii::$app->getRuntimePath() . "/debug-crawl.html", $result);
             echo $result;
         } catch (\Exception $e) {
             file_put_contents(Yii::$app->getRuntimePath() . "/debug-crawl.html", "<pre>$e</pre>");
         }*/

        $totalRequests = $pendingRequests->count();
        $count = 0;
        /** @var CrawlRequest $crawlRequest */
        foreach ($pendingRequests->each(10) as $crawlRequest) {

            $requestUrl = $crawlRequest->url;

            Console::startProgress(++$count, $totalRequests, "Request id #{$crawlRequest->id}");

            $urlParts = parse_url($requestUrl);

            // Extract the path
            $path = isset($urlParts['path']) ? trim($urlParts['path'], '/') : '';

            $route = substr($path, 18);

            // Extract the query string
            $queryString = isset($urlParts['query']) ? $urlParts['query'] : '';

            // Parse the query string into an associative array
            $params = parse_str($queryString, $queryParams);

            $webApp->getRequest()->getHeaders()->set('X-Rewrite-Url', $route);

//            try {

            Yii::$app->params['left-side-menu-item'] = null;

            $result = $webApp->runAction($route, $queryParams, false);

//                echo "previous hashing: 4c60a2509f838f59db0a668c7d442a128ed7fd741d80367041ea2081f85a499f\n";
            // 4c60a2509f838f59db0a668c7d442a128ed7fd741d80367041ea2081f85a499f
//                echo "hashing...\n";
//                echo hash('sha256', $result) . PHP_EOL;

//                try {
            $fp = fopen(Yii::$app->getRuntimePath() . "/parsed.txt", 'a+');
            /** @var \DOMElement $dom
             * @var \DOMXPath $xpath
             */

            list($dom, $xpath) = Parser::createDom($result);

            /**
             * Filtering only valid elements and attributes
             * @var array $elementAttributeMap
             */
            foreach ($elementAttributeMap as $elementName => &$attributes) {

//                /** check for self tags having no attributes
//                 * @example <title></title>
//                 */
//                if (is_numeric($elementName) && is_string($attributes)) {
//                    $elementName = (string)$attributes;
//                    $attributes = [];
//                }

                /**
                 * Filtering only specific attributes
                 * @var array $elementAttributeMap
                 */
                $domElements = $dom->getElementsByTagName($elementName);

                Console::endProgress();
                Console::startProgress($count, $totalRequests, "#Request id {$crawlRequest->id}, crawling tag <$elementName> ({$domElements->length})");

                foreach ($domElements as $domElement) {
                    $attributesProccessed = 0;
                    /**
                     * @var \DOMAttr $attribute
                     */
                    Console::endProgress();
                    foreach ($domElement->attributes as $attribute) {
                        /**
                         * For self closing tags
                         */
                        if (isset($attributes[$attribute->name]) && $attributes[$attribute->name]) {
                            Console::endProgress();
                            ++$attributesProccessed;
                            Console::updateProgress($count, $totalRequests, "#Request id {$crawlRequest->id}, crawling tag <$elementName> ({$attributesProccessed} / {$domElement->attributes->length})");
                            /*  echo "<pre>";
                              var_dump($attribute->value);

                            */
                            fwrite($fp, $webApp->controller->module->id . PHP_EOL);
                            fwrite($fp, $attribute->value . PHP_EOL);
//                                    echo "</pre>";
                        }
                    }
                }
            }
            /*echo "<pre>";
            var_dump('');
            echo "</pre>";
            die;*/
            fclose($fp);
//                } catch (\Exception $e) {
//                    continue;
//                }

            file_put_contents(Yii::$app->getRuntimePath() . "/debug-crawl.html", $result);
            echo $result;
//            } catch (\Exception $e) {
//                file_put_contents(Yii::$app->getRuntimePath() . "/debug-crawl.html", "<pre>$e</pre>");
//            }
            Console::endProgress(true);
        }

        echo 'Crawl completed';
    }
}