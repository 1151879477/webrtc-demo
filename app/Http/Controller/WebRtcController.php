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


    /**
     * @RequestMapping("/webrtc/calling")
     */
    public function calling()
    {
        return view('webrtc/calling');
    }

    /**
     * @RequestMapping("/webrtc/called")
     */
    public function called()
    {
        return view('webrtc/called');
    }
}