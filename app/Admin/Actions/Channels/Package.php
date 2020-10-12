<?php

namespace App\Admin\Actions\Channels;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class Package extends BatchAction
{
    protected $selector = '.report-posts';

    public function handle(Collection $collection, Request $request)
    {
        foreach ($collection as $model) {
            // 
        }

        return $this->response()->success('举报已提交！')->refresh();
    }

    public function form()
    {
        $this->checkbox('type', '类型')->options([]);
        $this->textarea('reason', '原因')->rules('required');
    }

    public function html()
    {
        return "<a class='report-posts btn btn-sm btn-danger'><i class='fa fa-info-circle'></i>举报</a>";
    }
}