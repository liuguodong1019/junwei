<?php
namespace Api\Controller;

use Think\Controller;

class WxPayController extends Controller
{
    /**
     * 初始化方法
     */
    public function _initialize()
    {
        vendor('WxpayAPI.lib.WeChatAppPay');
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
        $wxPay = C('wxPay');
        $order = M('order');
        $course = M('course');
        $resource = new SubmitController();
        $wxappid = $wxPay['appid'];
        $mch_id = $wxPay['mch_id'];
        $notify_url = $wxPay['notify_url'];;
        $wxkey = $wxPay['key'];
        $wechatAppPay = new \wechatAppPay($wxappid, $mch_id, $notify_url, $wxkey);
        if (IS_POST) {
            $data['uid'] = I('request.uid');
            $data['course_id'] = I('request.course_id');
            $data['pay_ways'] = I('request.pay_ways');
            $data['create_time'] = time();
            $course_id = $data['course_id'];
            if (!is_numeric($course_id) && empty($course_id)) {
                echo json_encode($status[2],$msg[2]);exit();
            }
            $res = M('course')->field('now_price,course_name,introduction')->where("id = '$course_id'")->find();
            $data['total_amount'] = $res['now_price'];
            $data['subject'] = $res['course_name'];
            $data['boy'] = $res['introduction'];
            if (is_numeric($course_id) && is_numeric($data['uid'])) {
                if ($order->add($data)) {
                    $dat = $order->field('id')->where("course_id = '$course_id'")->find();
                    $out_trade_no = $dat['id'];
                    $total_amount = $data['total_amount'];
                    $boy = $data['boy'];
                    $params = array();
                    $params['body'] = $boy;
                    $params['out_trade_no'] = $out_trade_no;
                    $params['total_fee'] = ($total_amount * 100);
                    $params['trade_type'] = 'APP';

                    $wx_result = $wechatAppPay->unifiedOrder($params);
                    if ($wx_result['return_code'] === 'SUCCESS' && $wx_result['result_code'] === 'SUCCESS') {
                        $prepay_id = $wx_result['prepay_id'];
                        $sign_array = $wechatAppPay->getAppPayParams($prepay_id);
                        echo json_encode([
                            'status' => $status[0],
                            'msg' => '预支付完成',
                            'data' => $sign_array
                        ]);
                        exit();
                    } else {
                        echo $resource::state($status[1], $msg[1]);exit();
                    }
                } else {
                    echo $resource::state($status[1], $msg[1]);exit();
                }
            } else {
                echo $resource::state($status[2], $msg[2]);exit();
            }
        } else {
            echo $resource::state($status[3], $msg[3]);exit();
        }
    }

    /**
     * 微信异步回调
     */
    public function notifyUrl()
    {
        $wechatAppPay = new \wechatAppPay();
        $data = $wechatAppPay->getNotifyData();
        if (empty($data)) {
            return false;
        }
        if ($data['return_code'] != 'SUCCESS') {
            return false;
        }
        $w_sign = array();           //参加验签签名的参数数组
        $w_sign['appid'] = $data['appid'];
        $w_sign['mch_id'] = $data['mch_id'];
        $w_sign['nonce_str'] = $data['nonce_str'];
        $w_sign['result_code'] = $data['result_code'];
        $w_sign['openid'] = $data['openid'];
        $w_sign['trade_type'] = $data['trade_type'];
        $w_sign['bank_type'] = $data['bank_type'];
        $w_sign['total_fee'] = $data['total_fee'];
        $w_sign['cash_fee'] = $data['cash_fee'];
        $w_sign['transaction_id'] = $data['transaction_id'];
        $w_sign['out_trade_no'] = $data['out_trade_no'];
        $w_sign['time_end'] = $data['time_end'];

        $w_sign['fee_type'] = $data['fee_type'];
        $w_sign['is_subscribe'] = $data['is_subscribe'];
        $w_sign['return_code'] = $data['return_code'];
        $verify_sign = $wechatAppPay->MakeSign($w_sign);//生成验签签名
        if ($data['sign'] != $verify_sign) {
            return false;
        }
        $wechatAppPay->replyNotify();
    }

    /**
     * 查询订单状态
     * @param  string $out_trade_no 订单号
     * @return xml               订单查询结果
     */
    public function queryOrder() {
        $wechatAppPay = new \wechatAppPay();
        $out_trade_no = I('request.out_trade_no');
        $result = $wechatAppPay->orderQuery($out_trade_no);
        // print_r($result);die;
        if ($result['result_code'] == 'SUCCESS') {
            $rew = self::updateOrder($result);
            echo $rew;exit();
        } else {
            echo $result['err_msg'];exit();
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
         $data['pay_buyer_email'] = $result['bank_type'];
         $data['pay_notify_id'] = $result['openid'];
         $data['pay_time'] = $result['time_end'];
         if ($order->where("id = $out_trade_no")->save($data)) {
               return json_encode([
                   'status' => $status[0],
                   'msg' => '支付成功',
               ]);
         }else {
             return json_encode([
                 'status' => $status[0],
                 'msg' => '支付失败',
             ]);
         }
    }
}




