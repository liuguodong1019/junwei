<?php
namespace Api\Controller;


use Think\Controller;

class AlipayController extends Controller
{
    /**
     * 初始化方法
     */
    public function _initialize()
    {

        vendor('Alipay\aop.AopClient');
        vendor('Alipay\aop.request.AlipayTradeAppPayRequest');
        vendor('Alipay\aop.request.AlipayOpenPublicTemplateMessageIndustryModifyRequest');
    }

//    /**
//     * 创建订单
//     */
//    public function create_order ()
//    {
//        $data = array();
//        $token = I('post.token');
//        $order = M('order');
//        $msg = C('msg');
//        $resource = new SubmitController();
//        $rew = M('users')->field('uid')->where("token = '$token'")->find();
//        if (!empty($rew)) {
//            $data['uid'] = $rew['id'];
//            $data['course_id'] = I('request.course_id');
//            $data['total_fee'] = I('request.now_price');
//            $data['pay_ways'] = I('request.pay_ways');
//            $data['create_time'] = time();
//            if (is_numeric($data['course_id']) && is_numeric($data['pay_ways'])) {
//                if ($order->add($data)) {
//                    echo $resource::state(1,'success');exit();
//                }else {
//                    echo $resource::state(0,'fail');exit();
//                }
//            }else {
//                echo $resource::state(403,$msg[2]);exit();
//            }
//        }else {
//            echo $resource::state(404,$msg[2]);exit();
//        }
//    }
    /**
     * 集成客户端请求参数
     */
    public function order ()
    {
//        $token = I('post.token');
        $order = M('order');
        $msg = C('msg');
        $pay = C('alipay_config');
        $content = array();
        $resource = new SubmitController();
//        $rew = M('users')->field('uid')->where("token = '$token'")->find();
//        if (!empty($rew)) {
            $data['uid'] = I('request.uid');
            $data['course_id'] = I('request.course_id');
            $data['total_fee'] = I('request.now_price');
            $data['pay_ways'] = I('request.pay_ways');
            $data['subject'] = I('request.subject');
            $data['create_time'] = time();
            $id = $data['course_id'];
            $course_id = $data['course_id'];
            if (is_numeric($id) && is_numeric($data['uid'])) {
                if ($order->add($data)) {
                    $dat = $order->field('id,total_amount,subject,boy')->where("course_id = '$course_id'")->find();
                    $out_trade_no = $dat['id'];
                    $total_amount = $dat['total_amount'];
                    $subject = $dat['subject'];
                    $boy = $dat['boy'];
                    $content['product_code'] = $pay['product_code'];
                    $content['total_amount'] = $total_amount;
                    $content['subject'] = $subject;
                    $content['body'] = $boy;
                    $content['out_trade_no'] = $out_trade_no;
                    $con = json_encode($content);//$content是biz_content的值,将之转化成字符串
                    $aop = new \AopClient;
                    //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
                    $request = new \AlipayTradeAppPayRequest();
                    $aop->appId = $pay['app_id'];
                    $bizcontent = $con;
                    $request->setBizContent($bizcontent);
                    $aop->postCharset = "utf-8";
                    $aop->format = "json";
                    $request->setNotifyUrl($pay['notify_url']);
                    $aop->signType = "RSA2";
//                    $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
                    $aop->rsaPrivateKey = $pay['private_key'];
                    $aop->alipayrsaPublicKey = $pay['alipay_public_key'];
                    $response = $aop->sdkExecute($request);
                    echo htmlspecialchars($response);die;
                }else {
                    echo $resource::state(0,'fail');exit();
                }
            }else {
                echo $resource::state(403,$msg[2]);exit();
            }
//        }else {
//            echo $resource::state(404,$msg[2]);exit();
//        }
    }
    /**
     * 提供给支付宝调用的接口
     */
    public function notifyUrl()
    {
        $aop = new \AopClient;
        $pay = C('alipay_config');
        $aop->alipayrsaPublicKey = $pay['alipay_public_key'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        if ($flag) {
            $data = $_POST;// 获取所有数据
            $app_id = $data['app_id'];
            $out_trade_no = $data['out_trade_no'];  // 唯一订单号
            $notify_time = $data['notify_time'];    //通知时间
            $notify_type = $data['notify_type'];    //通知类型
            $notify_id = $data['notify_id'];        // 通知校验ID
            $trade_no = $data['trade_no'];          //支付宝交易号
            $trade_status = $data['trade_status'];  //交易状态
            $buyer_email = $data['buyer_email'];    //买家支付宝账号
            $pay_time = $data['gmt_payment'];       // 交易付款时间
            // 金额相关
            $total_fee = $data['total_fee'];//交易金额
            $seller_email = $data['seller_email'];//卖家支付宝账号
            $parameter = array(
                "pay_trade_no" => $trade_no,     //支付宝交易号；
                "total_amount" => sprintf("%.2f", $total_fee),    //交易金额；
                "pay_trade_status" => $trade_status, //交易状态
                "pay_notify_id" => $notify_id,    //通知校验ID。
                "pay_notify_time" => $notify_time,  //通知的发送时间。
                "pay_buyer_email" => $buyer_email,  //买家支付宝帐号；
                "pay_time" => strtotime($pay_time),     // 交易付款时间
            );
            if ($trade_status == 'TRADE_FINISHED') {

            } elseif ($trade_status == 'TRADE_SUCCESS') {
                if ($this->checkOrderStatus($out_trade_no, $total_fee, $seller_email,$app_id)) {
                    $parameter['pay_status'] = 1;
                    $this->orderHandle($out_trade_no, $parameter);
                } else {
                    echo "fail";
                    exit;
                }
            }
            echo "success"; // 请不要修改或删除
        }else {
            // 验证失败
            echo "fail";
        }
    }

    /**
     * @param $order_sn 订单号
     * @param $total_fee 总价格
     * @param $seller 收款帐号
     * @return bool  检查支付宝返回的关键数据是否合法
     */
    private function checkOrderStatus($order_sn, $total_fee, $seller,$app_id)
    {
        $pay = C('alipay_config');
        $account = $pay['seller_email'];
        $appId = $pay['app_id'];
        $OrderSer = M('order');
        $sumPrice = $OrderSer->field('total_amount')->where("id = '$order_sn'")->find();//获得该订单的价格，比较价格是否正确
        if ($sumPrice['total_amount'] == $total_fee && $account == $seller && $appId == $app_id) {
            return true;
        }

        return false;
    }

    /**
     * @param $orderno 支付宝返回的订单号
     * @param $orderData  支付宝返回的相关信息
     * 支付宝订单返回的数据处理，将需要保存的结果，存入到数据库中去
     */
    private function orderHandle($orderno, $orderData)
    {
        $orderno = trim($orderno);
        $OrderSer = M('order');
        if ($OrderSer->where("id = '$orderno'")->save($orderData)) {
            $this->success('修改成功');
        }else {
            $this->error('修改失败');
        }
    }
}