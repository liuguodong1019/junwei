<?php
namespace Api\Controller;

use Think\Controller;

class WxPayController extends Controller
{
    public $error = null;
    const PREPAY_GATEWAY = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    const QUERY_GATEWAY = 'https://api.mch.weixin.qq.com/pay/orderquery';
    /**
     * 初始化方法
     */
    public function _initialize()
    {
        vendor('WxPayAPI\lib.WxPayData');
        vendor('WxPayAPI\lib.WxPayApi');
        vendor('WxPayAPI\lib.WxPayNotify');
        vendor('WxPayAPI\logs');
    }

    /**
     * 生成预支付交易单的必选参数
     * appid
     * mch_id
     * nonce_str
     * boy
     * Out_trade_no
     * Total_fee
     * Spbill_create_ip
     * Notify_url
     * Trade_type
     */
    public function order()
    {
        $status = C('status');
        $msg = C('msg');
        $order = M('order');
        $resource = new SubmitController();

        $string = \WxPayApi::getNonceStr();//随机字符串
//        $newPara = array();
//        //应用ID
//        $newPara["appid"] = \WxPayConfig::APPID;
//        //商户号
//        $newPara["mch_id"] = \WxPayConfig::MCHID;
//        //随机字符串,这里推荐使用函数生成
//        $newPara["nonce_str"] = $string;
//        //商品描述
//        $newPara["body"] = 'APP测试';
//        //商户订单号,这里是商户自己的内部的订单号
//        $newPara["out_trade_no"] = '1415659998';
//        //总金额
//        $newPara["total_fee"] = 1;
//        //终端IP
//        $newPara["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
//        //通知地址，注意，这里的url里面不要加参数
//        $newPara["notify_url"] = "http://sikao.junweiedu.com/junwei/api/wxPay/notify";
//        //交易类型
//        $newPara["trade_type"] = "APP";
//        $newPara['sign'] = self::getSign($newPara);
//        $xml = self::array2xml($newPara);
//        $get_data = self::post(self::PREPAY_GATEWAY,$xml);
////        print_r($get_data);die;
//        if ($get_data['return_code'] === 'SUCCESS' && $get_data['result_code'] === 'SUCCESS') {
//            $newPara["nonce_str"] = "5K8264ILTKCH16CQ2502SI8ZNMTM67VS";
//            //二次签名所需的时间戳
//            $newPara['timeStamp'] = time();
//            //二次签名剩余参数的补充
//            $secondSignArray = array(
//                "appid" => $newPara['appid'],
//                "noncestr" => $newPara['nonce_str'],
//                "package" => "Sign=WXPay",
//                "prepayid" => $get_data['prepay_id'],
//                "partnerid" => $newPara['mch_id'],
//                "timestamp" => $newPara['timeStamp'],
//            );
//            $str = 'appid=' . $newPara['appid'] . '&noncestr=' . $newPara['nonce_str'] . '&package=Sign=WXPay&partnerid=' . $newPara['mch_id'] . '&prepayid=' . $get_data['prepay_id'] . '&timestamp=' . $newPara['timeStamp'] . '&key=' . \WxPayConfig::KEY;
//            $re1 = md5($str);
//            $res = strtoupper($re1);
//            $json = array();
//            $json['datas'] = $secondSignArray;
//            $json['ordersn'] = $newPara["out_trade_no"];
//            $json['datas']['sign'] = $res;
//            $json['message'] = "预支付完成";
//            print_r($json['datas']);
//            die;

//        }

        if (IS_POST) {
            $data['uid'] = I('request.uid');
            $data['course_id'] = I('request.course_id');
            $data['total_fee'] = I('request.now_price');
            $data['pay_ways'] = I('request.pay_ways');
            $data['subject'] = I('request.course_name');
            $data['boy'] = I('request.introduction');
            $data['create_time'] = time();
            $id = $data['course_id'];
            $course_id = $data['course_id'];
            if (is_numeric($id) && is_numeric($data['uid'])) {
                if ($order->add($data)) {
                    $dat = $order->field('id,total_amount,boy')->where("course_id = '$course_id'")->find();
                    $out_trade_no = $dat['id'];
                    $total_amount = $dat['total_amount'];
                    $boy = $dat['boy'];
                    $string = \WxPayApi::getNonceStr();//随机字符串
                    $newPara = array();
                    //应用ID
                    $newPara["appid"] = \WxPayConfig::APPID;
                    //商户号
                    $newPara["mch_id"] = \WxPayConfig::MCHID;
                    //随机字符串,这里推荐使用函数生成
                    $newPara["nonce_str"] = $string;
                    //商品描述
                    $newPara["body"] = $boy;
                    //商户订单号,这里是商户自己的内部的订单号
                    $newPara["out_trade_no"] = $out_trade_no;
                    //总金额
                    $newPara["total_fee"] = $total_amount;
                    //终端IP
                    $newPara["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
                    //通知地址，注意，这里的url里面不要加参数
                    $newPara["notify_url"] = "http://sikao.junweiedu.com/junwei/api/wxPay/notify";
                    //交易类型
                    $newPara["trade_type"] = "APP";
                    $newPara['sign'] = self::getSign($newPara);
                    $xml = self::array2xml($newPara);
                    $get_data = self::post(self::PREPAY_GATEWAY,$xml);
                    if ($get_data['return_code'] === 'SUCCESS' && $get_data['result_code'] === 'SUCCESS') {
                        $newPara["nonce_str"] = "5K8264ILTKCH16CQ2502SI8ZNMTM67VS";
                        //二次签名所需的时间戳
                        $newPara['timeStamp'] = time();
                        //二次签名剩余参数的补充
                        $secondSignArray = array(
                            "appid" => $newPara['appid'],
                            "noncestr" => $newPara['nonce_str'],
                            "package" => "Sign=WXPay",
                            "prepayid" => $get_data['prepay_id'],
                            "partnerid" => $newPara['mch_id'],
                            "timestamp" => $newPara['timeStamp'],
                        );
                        $str = 'appid=' . $newPara['appid'] . '&noncestr=' . $newPara['nonce_str'] . '&package=Sign=WXPay&partnerid=' . $newPara['mch_id'] . '&prepayid=' . $get_data['prepay_id'] . '&timestamp=' . $newPara['timeStamp'] . '&key=' . \WxPayConfig::KEY;
                        $re1 = md5($str);
                        $res = strtoupper($re1);
                        $json = array();
                        $json['datas'] = $secondSignArray;
//                        print_r($json['datas']);die;
                        $json['ordersn'] = $newPara["out_trade_no"];
                        $json['datas']['sign'] = $res;
                        $json['message'] = "预支付完成";
                        echo json_encode([
                            'status' => $status[0],
                            'msg' => $json['message'],
                            'data' => $json
                        ]);
                        exit();
                    } else {
                        echo $resource::state(0, 'fail');
                        exit();
                    }
                } else {
                    echo $resource::state(0, 'fail');
                    exit();
                }
            } else {
                echo $resource::state($status[2], $msg[2]);
            }
        } else {
            echo $resource::state($status[3], $msg[3]);
            exit();
        }
    }

    /**
     * 微信异步回调
     */
    public function notifyUrl()
    {
        $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
//        $xml = file_get_contents('php://input');
        if (empty($xml)) {
            return false;
        }
        $rew = self::XMLDataParse($xml);
        if ($rew === false) {
            return false;
        }
        if (!empty($rew->return_code)) {
            if ($rew['return_code'] != 'SUCCESS') {
                return false;
            } else {
                $data = array(
                    'appid' => $rew->appid,
                    'mch_id' => $rew->mch_id,
                    'nonce_str' => $rew->nonce_str,
                    'result_code' => $rew->result_code,
                    'openid' => $rew->openid,
                    'trade_type' => $rew->trade_type,
                    'bank_type' => $rew->bank_type,
                    'total_fee' => $rew->total_fee,
                    'cash_fee' => $rew->cash_fee,
                    'transaction_id' => $rew->transaction_id,
                    'out_trade_no' => $rew->out_trade_no,
                    'time_end' => $rew->time_end
                );
                // 拼装数据进行第三次签名
                $sign = self::getSign($data);        // 获取签名

                /** 将签名得到的sign值和微信传过来的sign值进行比对，如果一致，则证明数据是微信返回的。 */
                if ($sign == $rew->sign) {
                    $reply = "<xml>
                    <return_code><![CDATA[SUCCESS]]></return_code>
                    <return_msg><![CDATA[OK]]></return_msg>
                </xml>";
                    echo $reply;      // 向微信后台返回结果。
                    exit;
                }
            }
        }
    }

    /**
     * 查询订单状态
     * @param  string $out_trade_no 订单号
     * @return xml               订单查询结果
     */
    public function queryOrder($out_trade_no) {
        $string = \WxPayApi::getNonceStr();//随机字符串
        $data = array(
            'appid'        =>    \WxPayConfig::APPID,
            'mch_id'    =>    \WxPayConfig::MCHID,
            'out_trade_no'    =>    $out_trade_no,
            'nonce_str'            =>    $string
        );
        $data['sign'] = self::getSign($data);
        $xml_data = self::array2xml($data);
        $result = self::post(self::QUERY_GATEWAY,$xml_data);
        if ($result['result_code'] == 'SUCCESS') {
            self::updateOrder($result);
//            return $result['trade_state'];
        } else {
            $this->error = $result['err_code_des'];
            return null;
        }
    }

    /**
     * 修改订单状态
     */
    private function updateOrder ($result)
    {
         $status = C('status');
         $order = M("order");
         $out_trade_no = $result['out_trade_no'];
         $data['pay_status'] = 1;
         $data['pay_trade_no'] = $result['transaction_id'];
         $data['pay_notify_id'] = $result['openid'];
         $data['pay_time'] = $result['time_end'];
         if ($order->where("id = $out_trade_no")->save($data)) {
               echo json_encode([
                   'status' => $status[0],
                   'msg' => '支付成功',
               ]);exit();
         }else {
             echo json_encode([
                 'status' => $status[0],
                 'msg' => '支付失败',
             ]);exit();
         }
    }
    private function getSign($params)
    {
        ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
        foreach ($params as $key => $item) {
            if (!empty($item)) {         //剔除参数值为空的参数
                $newArr[] = $key . '=' . $item;     // 整合新的参数数组
            }
        }
        $stringA = implode("&", $newArr);         //使用 & 符号连接参数
        $stringSignTemp = $stringA . "&key=" . \WxPayConfig::KEY;        //拼接key

        // key是在商户平台API安全里自己设置的
        $stringSignTemp = MD5($stringSignTemp);       //将字符串进行MD5加密
        $sign = strtoupper($stringSignTemp);      //将所有字符转换为大写
        return $sign;
    }


    private function array2xml($array)
    {

        if (!is_array($array)
            || count($array) <= 0
        ) {
            throw new \Exception("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    private function post($url,$data)
    {
//        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $header[] = "Content-type: text/xml";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            print curl_error($curl);
        }
        curl_close($curl);
        return self::XMLDataParse($data);
    }

    //xml格式数据解析函数
    public static function XMLDataParse($data)
    {
        $msg = array();
        $msg = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $msg;
    }
}




