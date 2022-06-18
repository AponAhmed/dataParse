<?php

namespace dataParse\src;

/**
 * Description of BackEnd
 *
 * @author Mahabub
 */
class BackEnd {

    static private string $QurFile = "que.json";
    static private string $ResFile = "res.csv";
    public array $que;
    private string $currentLink;
    public array $dataRow;

    public function __construct() {
        $this->getData();
    }

    //put your code here
    public function StartParse() {
        if (isset($_POST['urlData']) && !empty($_POST['urlData'])) {
            $urlData = $_POST['urlData'];
            $re = '@\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))@u';
            preg_match_all($re, $urlData, $matches, PREG_SET_ORDER, 0);
            // Print the entire match result
            if (file_exists(STORAGE . self::$ResFile)) {
                unlink(STORAGE . self::$ResFile);
            }
            $this->que = [];
            foreach ($matches as $mt) {
                $this->que[] = $mt[0];
            }
            if ($this->setData(true)) {
                echo json_encode(['done' => true, 'total' => count($this->que)]);
            } else {
                echo json_encode(['done' => false, 'msg' => 'Que File could not Created']);
            }
        }
    }

    private function setData($st = false) {
        $fileName = STORAGE . self::$QurFile;
        if ($st) {
            //First Time
            $crr = file_put_contents(STORAGE . self::$ResFile, "Url,Title,H1,Description,Keyword");
        } else {
            //Each Time
            $newRes = "\n";
            $newRes .= '"' . implode('","', $this->dataRow) . '"';
            $fp = fopen(STORAGE . self::$ResFile, 'a'); //opens file in append mode  
            fwrite($fp, $newRes);
            fclose($fp);
        }
        return file_put_contents($fileName, json_encode($this->que));
    }

    private function getData() {
        $fileName = STORAGE . self::$QurFile;
        if (file_exists($fileName)) {
            $content = file_get_contents($fileName);
            if ($content != "") {
                $this->que = json_decode($content, true);
            }
        } else {
            $this->que = [];
        }
    }

    //put your code here
    public function singleExe() {
        if (count($this->que) > 0) {
            $this->que = array_values($this->que);
            $this->currentLink = $this->que[0];
            $this->dataRow['url'] = $this->currentLink;
            unset($this->que[0]);
            $this->GetRemoteData();

            $this->setData();

            echo json_encode(['row' => $this->dataRow, 'left' => count($this->que)]);
        } else {
            echo 'complete';
        }
    }

    private function GetRemoteData() {
        $respo = $this->get_url($this->currentLink);
        if ($respo[1]['http_code'] == 200) {
            $content = $respo[0];
            $dom = new \DOMDocument();
            @$dom->loadHTML($content);
            $xpath = new \DOMXpath($dom);

            //Title Parser
            $titles = $dom->getElementsByTagName('title');
            if (isset($titles[0])) {
                $this->dataRow['title'] = self::strFilter($titles[0]->textContent);
            } else {
                $this->dataRow['title'] = "";
            }
            //--------
            //H1 Parser
            $h1Tags = $dom->getElementsByTagName('h1');
            if (isset($h1Tags[0])) {
                $this->dataRow['h1'] = self::strFilter($h1Tags[0]->textContent);
            } else {
                $this->dataRow['h1'] = "";
            }
            //--------
            $desc = $xpath->query("*/meta[@name='description']");
            if (isset($desc[0])) {
                $descObj = $desc[0];
                $desc = $descObj->getAttribute('content');
                if (!empty($desc)) {
                    $this->dataRow['description'] = self::strFilter($desc);
                } else {
                    $this->dataRow['description'] = "";
                }
            } else {
                $this->dataRow['description'] = "";
            }
            //-----Keyword---
            $keys = $xpath->query("*/meta[@name='keywords']");
            if (isset($keys[0])) {
                $keyObj = $keys[0];
                $keysStr = $keyObj->getAttribute('content');
                if (!empty($keysStr)) {
                    $this->dataRow['keywords'] = self::strFilter($keysStr);
                } else {
                    $this->dataRow['keywords'] = "";
                }
            } else {
                $this->dataRow['keywords'] = "";
            }
            //var_dump(implode(",", $this->dataRow));
            //var_dump($this->dataRow);
        }
    }

    static function strFilter($str = "") {
        $find = [];
        $replace = []; //&comma;
        $str = str_replace($find, $replace, $str);
        return $str;
    }

    function get_url($url, $javascript_loop = 0, $timeout = 5) {
        $url = str_replace("&amp;", "&", urldecode(trim($url)));

        $cookie = @tempnam("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

            if ($headers = get_headers($response['url'])) {
                foreach ($headers as $value) {
                    if (substr(strtolower($value), 0, 9) == "location:")
                        return get_url(trim(substr($value, 9, strlen($value))));
                }
            }
        }

        if (( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) &&
                $javascript_loop < 5
        ) {
            return get_url($value[1], $javascript_loop + 1);
        } else {
            return array($content, $response);
        }
    }

}
