<?php


namespace App\Http\Controller;


use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class WebRtcController
 * @package App\Http\Controller
 * @Controller()
 */
class WebRtcController
{
    /**
     * @RequestMapping("/webrtc/index")
     */
    public function index()
    {
        return view('webrtc/index');
    }
}