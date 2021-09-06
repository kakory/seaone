<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\AdminUser;
use App\Models\Attachment;
use App\Admin\Selectable\Customers;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Grid\Displayers\DropdownActions;
use App\Admin\Actions\Finance\Accept;
use App\Admin\Actions\Finance\Refuse;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->setActionClass(DropdownActions::class);

        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('date', '收支日期');
        $grid->column('amount_of_money', __('Amount of money'))->display(function ($src) {
            if ($this->type == 0) {
                return "<span style='color:red'>+$src</span>";
            } else if ($this->type == 1){
                return "<span style='color:blue'>-$src</span>";
            }
        });
        $grid->column('customer.name', '客户名称');
        $grid->column('service', __('Service'));
        $grid->column('type_of_payment', __('Type of payment'))->using([0 => '微信', 1=> '支付宝', 2=> '银行卡']);
        $grid->column('adminUser.name','发起人');
        $grid->column('note', __('Note'));
        $grid->column('voucher', __('Voucher'))->lightbox(['width' => 50, 'height' => 50])->help("点击放大");
        $grid->column('attachments', __('Attachments'))->display(function ($comments) {
                return (count($comments) != 0) ? "展开（".count($comments)."）" : "展开";
            })->expand(function ($model) {
            $attachments = $model->Attachments()->get()->map(function ($attachment) {
                return $attachment->only(['id', 'url']);
            });
            $attachmentsArray = $attachments->toArray();
            foreach($attachmentsArray as &$attachment){
                preg_match('/[^\/]+(?!.*\/)/', $attachment['url'], $matches);
                $attachment['link'] = '<a href="http://seaone.test/upload/'.$attachment['url'].'" download="'.$matches[0].'" target="_blank">点击下载</a>';
                $attachment['url'] = $matches[0];
            }
            return new Table(['ID', '文件名', '链接'], $attachmentsArray);
        });
        $grid->column('appendixes', __('Appendixes'))->display(function ($comments) {
            return (count($comments) != 0) ? "展开（".count($comments)."）" : "展开";
        })->expand(function ($model) {
            $appendixes = $model->appendixes()->get()->map(function ($appendixes) {
                return $appendixes->only(['id', 'url']);
            });
            $appendixesArray = $appendixes->toArray();
            foreach($appendixesArray as &$appendixes){
                preg_match('/[^\/]+(?!.*\/)/', $appendixes['url'], $matches);
                $appendixes['link'] = '<a href="http://seaone.test/upload/'.$appendixes['url'].'" download="'.$matches[0].'" target="_blank">点击下载</a>';
                $appendixes['url'] = $matches[0];
            }
            return new Table(['ID', '文件名', '链接'], $appendixesArray);
        });
        $grid->column('step', __('Step'))->using([
            0 => '<span class="label label-danger">已拒绝</span>', 
            1 => '<span class="label label-warning">待审批</span>', 
            2 => '<span class="label label-info">待上传凭证</span>', 
            3 => '<span class="label label-success">审批通过</span>'
        ]);
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->disableRowSelector();
        $grid->actions(function ($actions) {
            if(Admin::user()->isRole('manager')){
                $actions->add(new Accept);
                $actions->add(new Refuse);
            }
            $actions->disableDelete();
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('admin_user_id', __('Admin user id'));
        $show->field('type', __('Type'));
        $show->field('date', __('Date'));
        $show->field('customer_id', __('Customer id'));
        $show->field('service', __('Service'));
        $show->field('amount_of_money', __('Amount of money'));
        $show->field('type_of_payment', __('Type of payment'));
        $show->field('voucher', __('Voucher'))->file();
        
        $show->field('note', __('Note'));
        $show->field('step', __('Step'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->hidden('admin_user_id', __('Admin user id'));
        $form->hidden('step', __('Step'));

        $form->column(1/2, function ($form) {
            $form->radioButton('type', '类型')->options([0 => '收入', 1=> '支出'])->default(0)->required();
            $form->datetime('date', '收支日期')->default(date('Y-m-d H:i:s'))->required();
            $form->belongsTo('customer_id', Customers::class, '客户')->required();
            $form->text('service', __('Service'))->required();
            $form->decimal('amount_of_money', __('Amount of money'))->required();
            $form->radioButton('type_of_payment', __('Type of payment'))->options([0 => '微信', 1=> '支付宝', 2=> '银行卡'])->default(0)->required();
            $form->text('note', __('Note'));
            $form->image('voucher', __('Voucher'))->move('vouchers')->uniqueName();
        });
        $form->column(1/2, function ($form) {
            $form->multipleFile('attachments', __('Attachments'))->pathColumn('url')->move('attachments')->help('营业执照/法人身份证/商标合同书/商标品类表格/清单资料');
            $form->multipleFile('appendixes', __('Appendixes'))->pathColumn('url')->move('appendixes')->help('TM回执文件/验证码/k标文件');
        });

        $form->saving(function (Form $form) {
            if (!$id = $form->model()->id) {
                //create
                $form->admin_user_id = Admin::user()->id;
                $form->step = 1;
                if($form->voucher == null && $form->type == 0){
                    admin_toastr('aaa', 'error');
                    throw new \Exception('aaa');
                }
            } else {
                //edit
                if (Admin::user()->id == $form->admin_user_id && $form->step == 1) {
                    
                } else if (Admin::user()->isRole('treasurer') && $form->step == 2) {
                    if ($form->voucher || $form->model()->voucher) {
                        $form->step = 3;
                    }
                } else {
                    admin_toastr('不可更改', 'error');
                    throw new \Exception('您不是该请求的发起人/该阶段不可更改');
                }
            }
        });
        
        return $form;
    }
}
