<?php
if(date_default_timezone_get() != "Asia/Shanghai") date_default_timezone_set("Asia/Shanghai");
 
$cgname = array(
"CCTV-1"=>"b3666b9d",
"CCTV-2"=>"c5717c2d",
"CCTV-3"=>"53eda06f",
"CCTV-4"=>"0ccc41bf",
"CCTV-5"=>"6b26bee1",
"CCTV-5+"=>"e4e3801d",
"CCTV-6"=>"ddb707c0",
"CCTV-7"=>"f2d13f2a",
"CCTV-8"=>"13e8f054",
"CCTV-9"=>"8f932b7b",
"CCTV-10"=>"7651a0a2",
"CCTV-11"=>"0a2de840",
"CCTV-12"=>"1e983148",
"CCTV-13"=>"f5b1a323",
"CCTV-14"=>"6fff4f43",
"CCTV-15"=>"3201ff16",
"CCTV-17"=>"d3d48ldf",
"东方卫视"=>"95f40184",
"湖南卫视"=>"7d4daf1f",
"凤凰卫视"=>"65e0f71a",
"厦门卫视"=>"d6253770",
"安徽卫视"=>"06a195fd",
"浙江卫视"=>"9f005630",
"江苏卫视"=>"69e7e66e",
"东南卫视"=>"da0c3f96",
"北京卫视"=>"696f2203",
"广东卫视"=>"cb608c10",
"深圳卫视"=>"50ca1f4c",
"青海卫视"=>"6cf61152",
"海峡卫视"=>"1d7e3bf3",
"海南卫视"=>"8024c685",
"黑龙江卫视"=>"59736a35",
"吉林卫视"=>"a3aa4d01",
"辽宁卫视"=>"019d9e5e",
"河北卫视"=>"25feea76",
"河南卫视"=>"789d0d22",
"山西卫视"=>"d667526e",
"陕西卫视"=>"6ef94ac4",
"山东卫视"=>"c62d45e9",
"江西卫视"=>"61914025",
"湖北卫视"=>"526cbae7",
"贵州卫视"=>"a136a49d",
"云南卫视"=>"3a19b822",
"甘肃卫视"=>"fac1ecf6",
"宁夏卫视"=>"1c4d4f90",
"西藏卫视"=>"e237763b",
"新疆卫视"=>"aa83e122",
"内蒙古卫视"=>"442cb1d6",
"天津卫视"=>"b5cb5697",
"四川卫视"=>"e5b252e4",
"重庆卫视"=>"54cb9087",
"珠江频道"=>"e5815c01",
"南方卫视"=>"f0a3d1b2",
"深视都市频道"=>"0a0fa12e",
"深视电视剧频道"=>"8abb60ec",
"深视财经生活频道"=>"285dba33",
"深圳娱乐频道"=>"ee3c6fc6",
);
 
         
function compress_html($string) {
        $string = str_replace("\r", '', $string); //清除换行符
        $string = str_replace("\n", '', $string); //清除换行符
        $string = str_replace("\t", '', $string); //清除制表符
        return $string;
}
 
 
$cname = !empty($_GET["ch"]) ? $_GET["ch"] : exit(json_encode(["code" => 500, "msg" => "EPG频道参数不能为空！", "name" => $name, "date" => null, "data" => null], JSON_UNESCAPED_UNICODE));
$dt1=$_GET['date'];
$dt2=date('Y-m-d',strtotime($dt1)+86400);
$w1=date("w",strtotime($dt1));
if ($w1<'1') {$w1=7;}
$w0=$w1-1;
if ($w0<'1') {$w0=7;}
 
if (empty($cgname[$cname])) {
    exit(json_encode(["code" => 500, "msg" => "未定义频道ID！", "name" => $name, "date" => null, "data" => null], JSON_UNESCAPED_UNICODE));
} else {
    if ((strtotime($dt1) < time() && $w1 > date("w")) || date("Ymd", strtotime($dt1)) > (date("Ymd") - date("w") + '7')) {
        exit(json_encode(["code" => 500, "msg" => "超出搜视网时间范围！", "name" => $name, "date" => null, "data" => null], JSON_UNESCAPED_UNICODE));
    } else {
        $url0 = "https://www.tvsou.com/epg/";
        $t0 = array();
        $t1 = array();
        $nm = array();
        //获取前一天的最后一个节目名，作为当天第一个节目
        $url = $url0 . $cgname[$cname] . '/w' . $w0;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $re = curl_exec($ch);
        curl_close($ch);
        $re = compress_html($re);
        preg_match('|<table class="layui-table c_table"(.*?)layui-tab-item|i', $re, $u);
        preg_match_all('|<tr>([\s\S]+?)<\/tr>|', trim($u[1]), $u);
        $u = $u[1];
        $ysdnum = sizeof($u);
        preg_match_all('|_blank\'>(.*?)<\/a>|', $u[$ysdnum - 1], $tr);
        $lstnm = $tr[1][1];
        //获取当天节目表
        $url = $url0 . $cgname[$cname] . '/w' . $w1;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $re = curl_exec($ch);
        curl_close($ch);
        $re = compress_html($re);
        preg_match('|<table class="layui-table c_table"(.*?)layui-tab-item|i', $re, $u);
        preg_match_all('|<tr>([\s\S]+?)<\/tr>|', trim($u[1]), $u);
        $u = $u[1];
        $num = sizeof($u);
        for ($i = 0;$i < $num;$i++) {
            preg_match_all('|_blank\'>(.*?)<\/a>|', $u[$i], $tr);
            $t0[] = $tr[1][0];
            $nm[] = $tr[1][1];
        }
        //转码节目表
        for ($i = 1;$i < $num;$i++) {
            $t1[] = $t0[$i];
        }
        $t1[] = '00:00'; //当日最后一个节目设定结束时间，避免冲突
        $chn.= "{\"channel_name\":\"" . $cname . "\",\"date\":\"" . $dt1 . "\",\"epg_data\":[";
        $chn.= "{\"title\":\"" . $lstnm . "\",\"start\":\"00:00\",\"end\":\"" . $t0[0] . "\"},"; //前一天的最后一个节目名作为当天第一个节目，开始时间为00:00
        for ($i = 0;$i < $num;$i++) {
            $chn.= "{\"title\":\"" . $nm[$i] . "\",\"start\":\"" . $t0[$i] . "\",\"end\":\"" . $t1[$i] . "\"},";
        }
        $chn = substr($chn, 0, -1);
        $chn.= "]}";
    }
}
echo $chn;
?>
