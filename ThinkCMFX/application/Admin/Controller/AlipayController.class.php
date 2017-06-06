<?php
namespace Admin\Controller;

use Api\Controller\SubmitController;
use Common\Controller\AdminbaseController;

class AlipayController extends AdminbaseController
{
    /**
     * 初始化方法
     */
    public function _initialize()
    {
        vendor('Alipay.Corefunction');
        vendor('Alipay.RSAfunction');
        vendor('Alipay.Notify');
    }

    public function order ()
    {
        $status = C('status');
        $msg = C('msg');
        $pay = C('alipay_config');

        $response = new SubmitController();
        if (IS_GET) {
            $id = I('request.id');
            $course = M('course')->field('course_name,now_price')->where("id = $id")->find();
            $biz_content = array(
                'subject'       => $course['course_name'],
                'out_trade_no'  => '20170515'.$id,
                'total_amount'  => $course['now_price'],
                'product_code'  => 'QUICK_MSECURITY_PAY',
            );
//            $parameter = array_merge($pay,$biz_content);
//            $re = implode($parameter);
//            print_r($parameter);die;
            $parameter = array(
                'appid' =>'2017010604888586',//商户密钥
//                'rsaPrivateKey' =>'',//私钥
                   'partner'=>'2088421540577515',//(商家的参数,新版本的好像用不到)
                   'input_charset'=>strtolower('utf-8'),//编码
                   'notify_url' =>'http://sikao.junweiedu.com/junwei/index.php?g=api&m=alipay&a=notifyUrl',//回调地址(支付宝支付成功后回调修改订单状态的地址)
                   'payment_type' =>1,//(固定值)
                   'seller_id' =>'kciwwe7930@sandbox.com',//收款商家账号
                   'charset'    => 'utf-8',//编码
                   'sign_type' => 'RSA',//签名方式
                   'timestamp' =>date("Y-m-d Hi:i:s"),
                   'version'   =>"1.0",//固定值
                   'method'    => 'alipay.trade.app.pay',//固定值
            );
            $parameter = array_merge($parameter,$biz_content);
            $parameter = implode($parameter);
            $data = createLinkstring($parameter);
            logResult($data);
            $rsa_sign = urlencode(rsaSign($data, $pay['private_key']));
            $data = $data.'&sign='.'"'.$rsa_sign.'"'.'&sign_type='.'"'.$pay['sign_type'].'"';
            $re = implode($data);
            print_r($re);die;
            echo json_encode([
                'status'  => $status[0],
                'msg'     => $msg[0],
                'data'    => $data
            ]);exit();
        }else {
            echo $response::state($status[2],$msg[2]);
        }
    }
    /**
     * 提供给支付宝调用的接口
     */
    public function notifyUrl()
    {
        // 计算得出通知验证结果
        $alipay_config = C('alipay_config');
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        $order_id = I('request.id');
        if ($verify_result) { // 验证成功
            $data = $_POST;// 获取所有数据

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

            // 数据库相关参数
            $parameter = array(
                "pay_trade_no" => $trade_no,     //支付宝交易号；
                "total_fee" => sprintf("%.2f", $total_fee),    //交易金额；
                "pay_trade_status" => $trade_status, //交易状态
                "pay_notify_id" => $notify_id,    //通知校验ID。
                "pay_notify_time" => $notify_time,  //通知的发送时间。
                "pay_buyer_email" => $buyer_email,  //买家支付宝帐号；
                "pay_time" => $pay_time,     // 交易付款时间
            );

            if ($trade_status == 'TRADE_FINISHED') {

            } elseif ($trade_status == 'TRADE_SUCCESS') {
                if ($this->checkOrderStatus($out_trade_no, $total_fee, $seller_email)) {
                    $parameter['pay_status'] = 1;
                    $this->orderHandle($out_trade_no, $parameter);
                } else {
                    echo "fail";
                    exit;
                }
            }

            echo "success"; // 请不要修改或删除
        } else {
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
    private function checkOrderStatus($order_sn, $total_fee, $seller)
    {
        $account = C('alipay')['seller_email'];

        $OrderSer = M('order');
        $sumPrice = $OrderSer->field('total_fee')->where("order_sn = '$order_sn'")->find();//获得该订单的价格，比较价格是否正确
        if ($sumPrice['money'] == $total_fee && $account == $seller) {
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
        $where['order_sn'] = trim($orderno);
        $OrderSer = M('order');
        $OrderSer->where($where)->save($orderData);
    }
}