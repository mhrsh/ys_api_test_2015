<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class SampleController extends AppController {

  public function index() {
    //共通ファイルの読み込み
    require_once(dirname(__FILE__). "/common.php");
    //モデルは使わない
    $this->modelClass = false;        

    //フォームで渡された値の有無をチェックしてローカル変数に代入  
    if(empty($this->request->data["sort"]) && array_key_exists($this->request->data["sort"], $sortOrder)){
      $sort = $this->request->data["sort"];
    }else{
      $sort = "-score"; 
    }
    if($category_id = ctype_digit($this->request->data["category_id"]) && array_key_exists($this->request->data["category_id"], $categories)){
      $category_id = $this->request->data["category_id"];
    }else{
      $category_id = 1;
    }

    //クエリを作って投げる．結果を格納．
    if ($this->request->data['query'] != "") {
      $query4url = rawurlencode($this->request->data['query']);    
      $sort4url = rawurlencode($sort);   
      $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?appid=$appid&query=$query4url&category_id=$category_id&sort=$sort4url";
      $xml = simplexml_load_file($url);
      if ($xml["totalResultsReturned"] != 0) {//検索件数が0件でない場合,変数$hitsに検索結果を格納します。
        $this->set("hits", $xml->Result->Hit);
      }
    }

  }//index

  public function categoryRanking(){
    //共通ファイルの読み込み
    require_once(dirname(__FILE__). "/common.php");
    //モデルは使わない
    $this->modelClass = false;

    //フォームで渡された値の有無をチェックしてローカル変数に代入  
    if($category_id = ctype_digit($this->request->data["category_id"]) && array_key_exists($this->request->data["category_id"], $categories)){
      $category_id = $this->request->data["category_id"];
    }else{
      $category_id = 1;
    }
    
    //クエリを作って投げる．結果を格納．
    if ($category_id != "") {
      $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/categoryRanking?appid=$appid&category_id=$category_id";
      $xml = simplexml_load_file($url);
      if ($xml["totalResultsReturned"] != 0) {//問い合わせ結果が0件でない場合,変数$ranking_dataに問い合わせ結果を格納します。
        //$ranking_data = $xml->Result->RankingData;
        $this->set("ranking_data", $xml->Result->RankingData);
      }
    }
  }//categoryRanking

}//SampleController