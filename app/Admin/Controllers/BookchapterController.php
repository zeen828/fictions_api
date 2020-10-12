<?php

namespace App\Admin\Controllers;

use App\Model\Books\Bookchapter;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class BookchapterController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bookchapter';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(Request $request)
    {
        $grid = new Grid(new Bookchapter());

        $grid->column('id', __('Id'));
        $grid->column('book_id', __('Book id'));
        $grid->column('name', __('Name'));
        $grid->column('content', __('Content'));
        $grid->column('description', __('Description'));
        $grid->column('next_description', __('Next description'));
        $grid->column('oss_route', __('Oss route'));
        $grid->column('free', __('Free'));
        $grid->column('money', __('Money'));
        $grid->column('sort', __('Sort'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id, Request $request)
    {
        $show = new Show(Bookchapter::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('book_id', __('Book id'));
        $show->field('name', __('Name'));
        $show->field('content', __('Content'));
        $show->field('description', __('Description'));
        $show->field('next_description', __('Next description'));
        $show->field('oss_route', __('Oss route'));
        $show->field('free', __('Free'));
        $show->field('money', __('Money'));
        $show->field('sort', __('Sort'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(Request $request)
    {
        $form = new Form(new Bookchapter());

        $form->number('book_id', __('Book id'));
        $form->text('name', __('Name'));
        $form->textarea('content', __('Content'));
        $form->text('description', __('Description'));
        $form->textarea('next_description', __('Next description'));
        $form->text('oss_route', __('Oss route'));
        $form->switch('free', __('Free'));
        $form->number('money', __('Money'));
        $form->number('sort', __('Sort'));
        $form->switch('status', __('Status'))->default(2);

        return $form;
    }
}
