<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;

use App\Admin\Forms\Settings;
use App\Admin\Forms\Steps;
use Encore\Admin\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\MultipleSteps;
use Encore\Admin\Widgets;

class TestsController extends Controller
{
    public function index(Content $content)
    {

        $content->title('Form');

        $form = new Widgets\Form();
        $form->method('get');

        $form->keyValue('extensions');

        $content->body(new Widgets\Box('Form', $form));

        return $content;
    }
}
