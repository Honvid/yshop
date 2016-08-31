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
                            品牌管理
                            <small><?= Html::encode($this->title) ?></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                            <li><a href="/brand/index"> 品牌管理</a></li>
                            <li class="active"><?= Html::encode($this->title) ?></li>
                        </ol>
                    </section>
                    <section class="content">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                                <div class="pull-right">
                                    <a href="/brand/view" class="btn btn-default" title="添加品牌"><span class="icon glyphicon glyphicon-plus"></span> 添加品牌</a>
                                </div>
                            </div>
                            <div class="box-body">
                                <div ng-controller="MainCtrl">
                                    <button id="refreshButton" type="button" class="btn btn-success" ng-click="refreshData()">Refresh Data</button>  <strong>Calls Pending:</strong> <span ng-bind="callsPending"></span>
                                    <div id="grid1" ui-grid="gridOptions" ui-grid-cellNav ui-grid-edit ui-grid-resize-columns ui-grid-pinning ui-grid-selection ui-grid-move-columns ui-grid-exporter ui-grid-grouping class="grid"></div>
                                </div>
                            </div>
                    </section>
<?php
AppAsset::addJs($this,'@web/js/angular/1.4.3/angular.js');
AppAsset::addJs($this,'@web/js/angular/1.4.3/angular-touch.js');
AppAsset::addJs($this,'@web/js/angular/1.4.3/angular-animate.js');
AppAsset::addJs($this,'http://ui-grid.info/docs/grunt-scripts/csv.js');
AppAsset::addJs($this,'http://ui-grid.info/docs/grunt-scripts/pdfmake.js');
AppAsset::addJs($this,'http://ui-grid.info/docs/grunt-scripts/vfs_fonts.js');
AppAsset::addJs($this,'http://ui-grid.info/release/ui-grid.js');
AppAsset::addCss($this, 'http://ui-grid.info/release/ui-grid.css');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        var app = angular.module("mall", ['ngTouch', 'ui.grid', 'ui.grid.cellNav', 'ui.grid.edit', 'ui.grid.resizeColumns', 'ui.grid.pinning', 'ui.grid.selection', 'ui.grid.moveColumns', 'ui.grid.exporter', 'ui.grid.grouping']);
        app.controller('MainCtrl',  ['$scope', '$http', '$timeout', '$interval', 'uiGridConstants', 'uiGridGroupingConstants',
            function ($scope, $http, $timeout, $interval, uiGridConstants, uiGridGroupingConstants) {

                $scope.gridOptions = {};
                $scope.gridOptions.data = 'myData';
                $scope.gridOptions.enableColumnResizing = true;
                $scope.gridOptions.enableFiltering = true;
                $scope.gridOptions.enableGridMenu = true;
                $scope.gridOptions.showGridFooter = true;
                $scope.gridOptions.showColumnFooter = true;
                $scope.gridOptions.fastWatch = true;

                $scope.gridOptions.rowIdentity = function(row) {
                    return row.id;
                };
                $scope.gridOptions.getRowIdentity = function(row) {
                    return row.id;
                };

                $scope.gridOptions.columnDefs = [
                    { name:'id', width:50 },
                    { name:'name', width:100 },
                    { name:'age', width:100, enableCellEdit: true, aggregationType:uiGridConstants.aggregationTypes.avg, treeAggregationType: uiGridGroupingConstants.aggregation.AVG },
                    { name:'address.street', width:150, enableCellEdit: true },
                    { name:'address.city', width:150, enableCellEdit: true },
                    { name:'address.state', width:50, enableCellEdit: true },
                    { name:'address.zip', width:50, enableCellEdit: true },
                    { name:'company', width:100, enableCellEdit: true },
                    { name:'email', width:100, enableCellEdit: true },
                    { name:'phone', width:200, enableCellEdit: true },
                    { name:'about', width:300, enableCellEdit: true },
                    { name:'friends[0].name', displayName:'1st friend', width:150, enableCellEdit: true },
                    { name:'friends[1].name', displayName:'2nd friend', width:150, enableCellEdit: true },
                    { name:'friends[2].name', displayName:'3rd friend', width:150, enableCellEdit: true },
                    { name:'agetemplate',field:'age', width:150, cellTemplate: '<div class="ui-grid-cell-contents"><span>Age 2:{{COL_FIELD}}</span></div>' },
                    { name:'Is Active',field:'isActive', width:150, type:'boolean' },
                    { name:'Join Date',field:'registered', cellFilter:'date', width:150, type:'date', enableFiltering:false },
                    { name:'Month Joined',field:'registered', cellFilter: 'date:"MMMM"', filterCellFiltered:true, sortCellFiltered:true, width:150, type:'date' }
                ];

                $scope.callsPending = 0;

                var i = 0;
                $scope.refreshData = function(){
                    $scope.myData = [];

                    var start = new Date();
                    var sec = $interval(function () {
                        $scope.callsPending++;

                        $http.get('/attribute/list')
                            .success(function(data) {
                                $scope.callsPending--;

                                data.forEach(function(row){
                                    row.id = i;
                                    i++;
                                    row.registered = new Date(row.registered)
                                    $scope.myData.push(row);
                                });
                            })
                            .error(function() {
                                $scope.callsPending--
                            });
                    }, 200, 10);


                    var timeout = $timeout(function() {
                        $interval.cancel(sec);
                        $scope.left = '';
                    }, 2000);

                    $scope.$on('$destroy', function(){
                        $timeout.cancel(timeout);
                        $interval.cancel(sec);
                    });

                };

            }]);
    </script>
<?php $this->endBlock(); ?>