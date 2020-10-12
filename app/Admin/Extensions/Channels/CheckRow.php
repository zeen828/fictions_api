<?php

namespace App\Admin\Extensions\Channels;

use Encore\Admin\Admin;

class CheckRow
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-check-row').on('click', function () {
    // Your code.
    var channel_id = $(this).data('id')
    var version_id = $('.apk_version').val()
    console.log('渠道ID', channel_id);
    console.log('客戶端ID', version_id);
    // 防呆
    if(!version_id){
        alert('請選擇客戶端');
        return;
    }
    var api_url = '/api/v1/ajax/package/' + channel_id + '/' + version_id;
    $.get(api_url, function(result){
        console.log(result);
        alert(result.msg);
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());
        if($this->id == 0){
            return "<a class='btn btn-sm btn-success grid-check-row' data-id='{$this->id}'>更新所有渠道包</a>";
        }else{
            return "<a class='btn btn-sm btn-success grid-check-row' data-id='{$this->id}'>更新渠道包</a>";
        }
    }

    public function __toString()
    {
        return $this->render();
    }
}