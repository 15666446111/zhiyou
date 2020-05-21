<?php

namespace App\Admin\Actions;

use Throwable;
use Encore\Admin\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\ImportMachines as ImportMachine;
use Encore\Admin\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportDeliverGoods extends Action
{
    protected $selector = '.import-machines';

    public function handle(Request $request)
    {
        try {

            $brand  = $request->brand;

            $result = Excel::toArray(null, request()->file('file'));

            // 只取第一个Sheet
            if (count($result[0]) > 0) 
            {
                $rows = $result[0];

                $headings = [];

                if (count($rows) > 0){
                    foreach ($rows[0] as $key => $col) $headings[Str::snake($col)] = $key;
                }

                $data = [];

                foreach ($rows as $key => $row){
                    if ( $key > 0 && isset($row[$headings['s_n']]) ) $data[] = $row[$headings['s_n']];
                }

                $eplice = \App\Merchant::whereIn('merchant_terminal', $data)->pluck('merchant_terminal')->toArray();
                // 交集
                $epliceRows = array_intersect($data, $eplice);
                // 差集
                $InsertData = array_diff($data, $eplice);

                foreach ($InsertData as $key => $value) {
                    \App\Merchant::create([
                        'merchant_terminal' =>  $value,
                        'brand_id'          =>  $brand,
                    ]);
                }

                return $this->response()->success('入库成功, 入库'.count($InsertData).'台!')->refresh();

            } else  return $this->response()->success('无数据!')->refresh();

        } catch (ValidationException $validationException) {

            return Response::withException($validationException);

        } catch (Throwable $throwable) {

            $this->response()->status = false;

            return $this->response()->swal()->error($throwable->getMessage());
        }

        return $this->response()->success('导入成功!')->refresh();
    }

    /**
     * [html 展示的HTML]
     * @author Pudding
     * @DateTime 2020-04-21T15:58:43+0800
     * @return   [type]                   [description]
     */
    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-machines" style="position:absolute;  right: 350px;"><i class="fa fa-upload" style="margin-right: 3px;"></i>导入发货</a>
HTML;
    }

    /**
     * [form 点击的按钮 出来的表单]
     * @author Pudding
     * @DateTime 2020-04-21T15:58:56+0800
     * @return   [type]                   [description]
     */
    public function form()
    {
        $user = \App\Buser::pluck('nickname as name','id');
        $this->select('user', '配送会员')->options($user)->rules('required', ['required' => '请选择品牌']);


        $policy = \App\Policy::where('active', '1')->pluck('title as name','id');
        $this->select('policy', '政策活动')->options($policy)->rules('required', ['required' => '请选择品牌']);

        $this->file('file', '上传导入模版')->rules('required', ['required' => '文件不能为空']);
    }


    /**
     * @return string
     * 上传效果
     */
    public function handleActionPromise()
    {
        $resolve = <<<'SCRIPT'
var actionResolverss = function (data) {
            $('.modal-footer').show()
            $('.tips').remove()
            var response = data[0];
            var target   = data[1];

            if (typeof response !== 'object') {
                return $.admin.swal({type: 'error', title: 'Oops!'});
            }

            var then = function (then) {
                if (then.action == 'refresh') {
                    $.admin.reload();
                }

                if (then.action == 'download') {
                    window.open(then.value, '_blank');
                }

                if (then.action == 'redirect') {
                    $.admin.redirect(then.value);
                }
            };

            if (typeof response.html === 'string') {
                target.html(response.html);
            }

            if (typeof response.swal === 'object') {
                $.admin.swal(response.swal);
            }

            if (typeof response.toastr === 'object') {
                $.admin.toastr[response.toastr.type](response.toastr.content, '', response.toastr.options);
            }

            if (response.then) {
              then(response.then);
            }
        };

        var actionCatcherss = function (request) {
            $('.modal-footer').show()
            $('.tips').remove()

            if (request && typeof request.responseJSON === 'object') {
                $.admin.toastr.error(request.responseJSON.message, '', {positionClass:"toast-bottom-center", timeOut: 10000}).css("width","500px")
            }
        };
SCRIPT;

        Admin::script($resolve);

        return <<<'SCRIPT'
         $('.modal-footer').hide()
         let html = `<div class='tips' style='color: red;font-size: 18px;'>导入时间取决于数据量，请耐心等待结果不要关闭窗口！<img src="data:image/gif;base64,R0lGODlhEAAQAPQAAP///1VVVfr6+np6eqysrFhYWG5ubuPj48TExGNjY6Ojo5iYmOzs7Lq6utjY2ISEhI6OjgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAAFUCAgjmRpnqUwFGwhKoRgqq2YFMaRGjWA8AbZiIBbjQQ8AmmFUJEQhQGJhaKOrCksgEla+KIkYvC6SJKQOISoNSYdeIk1ayA8ExTyeR3F749CACH5BAkKAAAALAAAAAAQABAAAAVoICCKR9KMaCoaxeCoqEAkRX3AwMHWxQIIjJSAZWgUEgzBwCBAEQpMwIDwY1FHgwJCtOW2UDWYIDyqNVVkUbYr6CK+o2eUMKgWrqKhj0FrEM8jQQALPFA3MAc8CQSAMA5ZBjgqDQmHIyEAIfkECQoAAAAsAAAAABAAEAAABWAgII4j85Ao2hRIKgrEUBQJLaSHMe8zgQo6Q8sxS7RIhILhBkgumCTZsXkACBC+0cwF2GoLLoFXREDcDlkAojBICRaFLDCOQtQKjmsQSubtDFU/NXcDBHwkaw1cKQ8MiyEAIfkECQoAAAAsAAAAABAAEAAABVIgII5kaZ6AIJQCMRTFQKiDQx4GrBfGa4uCnAEhQuRgPwCBtwK+kCNFgjh6QlFYgGO7baJ2CxIioSDpwqNggWCGDVVGphly3BkOpXDrKfNm/4AhACH5BAkKAAAALAAAAAAQABAAAAVgICCOZGmeqEAMRTEQwskYbV0Yx7kYSIzQhtgoBxCKBDQCIOcoLBimRiFhSABYU5gIgW01pLUBYkRItAYAqrlhYiwKjiWAcDMWY8QjsCf4DewiBzQ2N1AmKlgvgCiMjSQhACH5BAkKAAAALAAAAAAQABAAAAVfICCOZGmeqEgUxUAIpkA0AMKyxkEiSZEIsJqhYAg+boUFSTAkiBiNHks3sg1ILAfBiS10gyqCg0UaFBCkwy3RYKiIYMAC+RAxiQgYsJdAjw5DN2gILzEEZgVcKYuMJiEAOwAAAAAAAAAAAA=="><\/div>`
         $('.modal-header').append(html)
process.then(actionResolverss).catch(actionCatcherss);
SCRIPT;
    }
}