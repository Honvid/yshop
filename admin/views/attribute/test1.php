<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "品牌列表";
?>
<section class="content-header">
                    <h1>
                        属性管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/attribute/index"> 属性管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/attribute/view" class="btn btn-default" title="添加属性"><span class="icon glyphicon glyphicon-plus"></span> 添加属性</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div ng-controller="gridCtr as showCase">
                                <p class="text-danger">You selected the following rows:</p>
                                <p>
                                </p><pre>{{ showCase.selected |json }}</pre>
                                <p></p>
                                <p class="text-danger"><strong>{{ showCase.message }}</strong></p>
                                <table datatable="" dt-options="showCase.dtOptions" dt-columns="showCase.dtColumns" dt-instance="showCase.dtInstance" class="table table-striped table-bordered hover">
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                </section>
    <div class="col-sm-6 form-group">
        <label for="1" class="col-sm-3 control-label text-right" style="padding:0;">
            作者
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="1" />
            <input type="text" class="form-control" id="1" name="attr_value_list[]"
                   value="张三">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="2" class="col-sm-3 control-label text-right" style="padding:0;">
            出版社
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="2" />
            <input type="text" class="form-control" id="2" name="attr_value_list[]"
                   value="北京大学出版社">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="3" class="col-sm-3 control-label text-right" style="padding:0;">
            图书书号/ISBN
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="3" />
            <input type="text" class="form-control" id="3" name="attr_value_list[]"
                   value="ISBN111111">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="4" class="col-sm-3 control-label text-right" style="padding:0;">
            出版日期
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="4" />
            <input type="text" class="form-control" id="4" name="attr_value_list[]"
                   value="2016-01-02">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="5" class="col-sm-3 control-label text-right" style="padding:0;">
            开本
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="5" />
            <input type="text" class="form-control" id="5" name="attr_value_list[]"
                   value="A4">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="6" class="col-sm-3 control-label text-right" style="padding:0;">
            图书页数
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="6" />
            <input type="text" class="form-control" id="6" name="attr_value_list[]"
                   value="430">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="8" class="col-sm-3 control-label text-right" style="padding:0;">
            图书规格
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="8" />
            <input type="text" class="form-control" id="8" name="attr_value_list[]"
                   value="A4">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="9" class="col-sm-3 control-label text-right" style="padding:0;">
            版次
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="9" />
            <input type="text" class="form-control" id="9" name="attr_value_list[]"
                   value="1">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="10" class="col-sm-3 control-label text-right" style="padding:0;">
            印张
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="10" />
            <input type="text" class="form-control" id="10" name="attr_value_list[]"
                   value="1">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="11" class="col-sm-3 control-label text-right" style="padding:0;">
            字数
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="11" />
            <input type="text" class="form-control" id="11" name="attr_value_list[]"
                   value="1000000">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="12" class="col-sm-3 control-label text-right" style="padding:0;">
            所属分类
        </label>
        <div class="col-sm-9">
            <input type="hidden" name="attr_id_list[]" value="12" />
            <input type="text" class="form-control" id="12" name="attr_value_list[]"
                   value="国内">
            <input type="hidden" name="attr_price_list[]" value="0" />
        </div>
    </div>
    <div class="col-sm-6 form-group">
        <label for="0" class="col-sm-3 control-label text-right" style="padding:0;">
            图书装订
        </label>
        <div class="col-sm-9">
            <div class="attr-group">
                <div class="col-sm-4" style="padding: 0;">
                    <input type="hidden" name="attr_id_list[]" value="7" />
                    <input type="text" class="form-control" id="0" name="attr_value_list[]"
                           value="平装">
                </div>
                <div class="col-sm-6" style="padding: 0;">
                    <div class="row">
                        <label class="col-sm-4" style="line-height: 34px;padding: 0 0 0 20px;">
                            价格:
                        </label>
                        <input type="number" class="form-controls col-sm-8" name="attr_price_list[]"
                               value="100" />
                    </div>
                </div>
                <div class="col-sm-2" style="padding: 0;">
                <span class="input-group-btn">
                    <a href="javascript:;" class="btn btn-info btn-flat attr-add">
                        <span class="fa fa-plus">
                        </span>
                    </a>
                </span>
                </div>
            </div>
            <div class="attr-group">
                <div class="col-sm-4" style="padding: 0;">
                    <input type="hidden" name="attr_id_list[]" value="7" />
                    <input type="text" class="form-control" id="1" name="attr_value_list[]"
                           value="黑白">
                </div>
                <div class="col-sm-6" style="padding: 0;">
                    <div class="row">
                        <label class="col-sm-4" style="line-height: 34px;padding: 0 0 0 20px;">
                            价格:
                        </label>
                        <input type="number" class="form-controls col-sm-8" name="attr_price_list[]"
                               value="120" />
                    </div>
                </div>
                <div class="col-sm-2" style="padding: 0;">
                <span class="input-group-btn">
                    <a href="javascript:;" class="btn btn-warning btn-flat attr-delete">
                        <span class="fa fa-remove">
                        </span>
                    </a>
                </span>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
<?php
AppAsset::addCss($this,'https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap4.min.css');
AppAsset::addCss($this,'@web/js/angular-datatables/vendor/datatables-colvis/css/dataTables.colVis.css');
AppAsset::addCss($this,'@web/js/angular-datatables/vendor/datatables-select/css/select.dataTables.css');
AppAsset::addCss($this,'@web/js/angular-datatables/vendor/datatables-tabletools/css/dataTables.tableTools.css');
AppAsset::addJs($this,'https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/angular-datatables.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/vendor/datatables-light-columnfilter/dist/dataTables.lightColumnFilter.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/light-columnfilter/angular-datatables.light-columnfilter.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/vendor/datatables-colreorder/js/dataTables.colReorder.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/colreorder/angular-datatables.colreorder.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/vendor/datatables-colvis/js/dataTables.colVis.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/colvis/angular-datatables.colvis.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/buttons/angular-datatables.buttons.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/vendor/datatables-buttons/js/buttons.colVis.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/tabletools/angular-datatables.tabletools.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/vendor/datatables-tabletools/js/dataTables.tableTools.js');
AppAsset::addJs($this,'@web/js/angular-datatables/dist/plugins/select/angular-datatables.select.min.js');
AppAsset::addJs($this,'@web/js/angular-datatables/vendor/datatables-select/js/dataTables.select.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        var app = angular.module("mall", ["datatables", 'datatables.bootstrap','datatables.colvis','datatables.light-columnfilter','datatables.tabletools', 'datatables.colreorder','datatables.select','datatables.buttons']);
        app.controller('gridCtr', gridCtr);

        function gridCtr($scope, $compile, DTOptionsBuilder, DTColumnBuilder) {
            var titleHtml = '<input type="checkbox" ng-model="showCase.selectAll" ng-click="showCase.toggleAll(showCase.selectAll, showCase.selected)">';
            var vm = this;
            vm.message = '';
            vm.edit = edit;
            vm.delete = deleteRow;
            vm.dtInstance = {};
            vm.persons = {};
            vm.selected = {};
            vm.selectAll = false;
            vm.toggleAll = toggleAll;
            vm.toggleOne = toggleOne;

            vm.dtOptions = DTOptionsBuilder.newOptions()
                .withPaginationType('full_numbers')
                .withOption(
                    'ajax', {
                        data:{"a":"basdff"},
                        dataSrc: 'data',
                        url: '/attribute/list',
                        type:'POST'
                    })
                .withLanguage({
                    "sEmptyTable":     "No data available in table",
                    "sInfo":           "Showing _START_ to _END_ of _TOTAL_ entries",
                    "sInfoEmpty":      "Showing 0 to 0 of 0 entries",
                    "sInfoFiltered":   "(filtered from _MAX_ total entries)",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ",",
                    "sLengthMenu":     "Show _MENU_ entries",
                    "sLoadingRecords": "Loading...",
                    "sProcessing":     "Processing...",
                    "sSearch":         "Search:",
                    "sZeroRecords":    "No matching records found",
                    "oPaginate": {
                        "sFirst":    "第一页",
                        "sLast":     "最后一页",
                        "sNext":     "下一页",
                        "sPrevious": "上一页"
                    },
                    "oAria": {
                        "sSortAscending":  ": activate to sort column ascending",
                        "sSortDescending": ": activate to sort column descending"
                    }
                })
                .withDataProp('data')
                .withOption('processing', false)
                .withOption('serverSide', true)
                .withPaginationType('full_numbers')
//                .setLoadingTemplate('<img src="/images/loading.gif">')
                .withBootstrap()
                .withBootstrapOptions({
                    TableTools: {
                        classes: {
                            container: 'btn-group',
                            buttons: {
                                normal: 'btn btn-danger'
                            }
                        }
                    },
                    ColVis: {
                        classes: {
                            masterButton: 'btn btn-primary'
                        }
                    },
                    pagination: {
                        classes: {
                            ul: 'pagination pagination-sm'
                        }
                    }
                })
                .withSelect({
                    style:    'os',
                    selector: 'td:first-child'
                })
                .withColVis()
                .withColVisStateChange(stateChange)
//                .withColVisOption('aiExclude', [2])
                .withColReorder()
                .withLightColumnFilter({
                    '3' : {
                        type : 'text'
                    },
                    '4' : {
                        type : 'select',
                        cssClass: "form-control select2",
                        values: [{
                            value: 'Yoda', label: 'Yoda foobar'
                        }, {
                            value: 'Titi', label: 'Titi foobar'
                        }, {
                            value: 'Kyle', label: 'Kyle foobar'
                        }, {
                            value: 'Bar', label: 'Bar foobar'
                        }, {
                            value: 'Whateveryournameis', label: 'Whateveryournameis foobar'
                        }]
                    }
                })
                .withTableTools('/js/angular-datatables/vendor/datatables-tabletools/swf/copy_csv_xls_pdf.swf')
                .withButtons([
                    'columnsToggle',
                    'colvis',
                    'copy',
                    'print',
                    'excel',
                    {
                        text: 'Some button',
                        key: '1',
                        action: function (e, dt, node, config) {
                            alert('Button activated');
                        }
                    }
                ])
//                .withTableToolsButtons([
//                    'copy',
//                    'print', {
//                        'sExtends': 'collection',
//                        'sButtonText': 'Save',
//                        'aButtons': ['csv', 'xls', 'pdf']
//                    }
//                ])
                .withOption('headerCallback', function(header) {
                    if (!vm.headerCompiled) {
                        // Use this headerCompiled field to only compile header once
                        vm.headerCompiled = true;
                        $compile(angular.element(header).contents())($scope);
                    }
                })
                .withOption('createdRow', createdRow)
                .withColReorderOption('iFixedColumnsRight', 1)
                .withColReorderCallback(function() {
                    console.log('Columns order has been changed with: ' + this.fnOrder());
                });
            vm.dtColumns = [
                DTColumnBuilder.newColumn(null).withTitle('')
                    .notSortable()
                    .withClass('select-checkbox')
                    // Need to define the mRender function, otherwise we get a [Object Object]
                    .renderWith(function() {return '';}),
                DTColumnBuilder.newColumn(null).withTitle(titleHtml).notSortable()
                    .renderWith(function(data, type, full, meta) {
                        vm.selected[full.id] = false;
                        return '<input type="checkbox" ng-model="showCase.selected[' + data.id + ']" ng-click="showCase.toggleOne(showCase.selected)">';
                    }),
                DTColumnBuilder.newColumn('id').withTitle('ID').withClass('text-danger'),
                DTColumnBuilder.newColumn('firstName').withTitle('First name'),
                DTColumnBuilder.newColumn('lastName').withTitle('Last name'),
                DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
                    .renderWith(actionsHtml)
            ];
            function stateChange(iColumn, bVisible) {
                console.log('The column', iColumn, ' has changed its status to', bVisible);
            }
            function edit(person) {
                vm.message = 'You are trying to edit the row: ' + JSON.stringify(person);
                // Edit some data and call server to make changes...
                // Then reload the data so that DT is refreshed
                vm.dtInstance.reloadData();
            }
            function deleteRow(person) {
                vm.message = 'You are trying to remove the row: ' + JSON.stringify(person);
                // Delete some data and call server to make changes...
                // Then reload the data so that DT is refreshed
                vm.dtInstance.reloadData();
            }
            function createdRow(row, data, dataIndex) {
                // Recompiling so we can bind Angular directive to the DT
                $compile(angular.element(row).contents())($scope);
            }
            function actionsHtml(data, type, full, meta) {
                vm.persons[data.id] = data;
                return '<button class="btn btn-warning" ng-click="showCase.edit(showCase.persons[' + data.id + '])">' +
                    '   <i class="fa fa-edit"></i>' +
                    '</button>&nbsp;' +
                    '<button class="btn btn-danger" ng-click="showCase.delete(showCase.persons[' + data.id + '])" )"="">' +
                    '   <i class="fa fa-trash-o"></i>' +
                    '</button>';
            }
            function toggleAll (selectAll, selectedItems) {
                for (var id in selectedItems) {
                    if (selectedItems.hasOwnProperty(id)) {
                        selectedItems[id] = selectAll;
                    }
                }
            }
            function toggleOne (selectedItems) {
                for (var id in selectedItems) {
                    if (selectedItems.hasOwnProperty(id)) {
                        if(!selectedItems[id]) {
                            vm.selectAll = false;
                            return;
                        }
                    }
                }
                vm.selectAll = true;
            }
        }
    </script>
<?php $this->endBlock(); ?>