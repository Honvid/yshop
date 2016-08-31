<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "品牌列表";
?>
    <div ng-controller="MainCtrl">
        <button id="expandAll" type="button" class="btn btn-success" ng-click="expandAll()">Expand All</button>
        <button id="toggleFirstRow" type="button" class="btn btn-success" ng-click="toggleRow(0)">Toggle First Row</button>
        <button id="toggleSecondRow" type="button" class="btn btn-success" ng-click="toggleRow(1)">Toggle Second Row</button>
        <button id="toggleExpandNoChildren" type="button" class="btn btn-success" ng-click="toggleExpandNoChildren()">Toggle Expand No Children</button>
        <div id="grid1" ui-grid="gridOptions" ui-grid-tree-view class="grid"></div>
    </div>
<?php
AppAsset::addCss($this,'http://ui-grid.info/release/ui-grid.css');
AppAsset::addJs($this,'http://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.js');
AppAsset::addJs($this,'http://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-touch.js');
AppAsset::addJs($this,'http://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-animate.js');
AppAsset::addJs($this,'http://ui-grid.info/release/ui-grid.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        var app = angular.module('mall', ['ngAnimate', 'ngTouch', 'ui.grid', 'ui.grid.treeView', 'ui.grid.edit' ]);

        app.controller('MainCtrl', ['$scope', '$http', '$interval', 'uiGridTreeViewConstants', function ($scope, $http, $interval, uiGridTreeViewConstants ) {
            $scope.gridOptions = {
                enableSorting: true,
                enableFiltering: true,
                showTreeExpandNoChildren: true,
                columnDefs: [
                    { name: 'name', width: '30%' },
                    { name: 'gender', width: '20%',editableCellTemplate: 'ui-grid/dropdownEditor',editDropdownValueLabel: 'gender', editDropdownOptionsArray: [
                        { id: 1, gender: 'male' },
                        { id: 2, gender: 'female' }
                    ]  },
                    { name: 'age', width: '20%' },
                    { name: 'company', width: '25%' },
                    { name: 'state', width: '35%' },
                    { name: 'balance', width: '25%', cellFilter: 'currency' }
                ],
                onRegisterApi: function( gridApi ) {
                    $scope.gridApi = gridApi;
                    $scope.gridApi.treeBase.on.rowExpanded($scope, function(row) {
                        if( row.entity.$$hashKey === $scope.gridOptions.data[50].$$hashKey && !$scope.nodeLoaded ) {
                            $interval(function() {
                                $scope.gridOptions.data.splice(51,0,
                                    {name: 'Dynamic 1', gender: 'female', age: 53, company: 'Griddable grids', balance: 38000, $$treeLevel: 1},
                                    {name: 'Dynamic 2', gender: 'male', age: 18, company: 'Griddable grids', balance: 29000, $$treeLevel: 1}
                                );
                                $scope.nodeLoaded = true;
                            }, 2000, 1);
                        }
                    });
                }
            };

            $http.get('https://cdn.rawgit.com/angular-ui/ui-grid.info/gh-pages/data/500_complex.json')
                .success(function(data) {
                    for ( var i = 0; i < data.length; i++ ){
                        data[i].state = data[i].address.state;
                        data[i].balance = Number( data[i].balance.slice(1).replace(/,/,'') );
                    }
                    data[0].$$treeLevel = 0;
                    data[1].$$treeLevel = 1;
                    data[10].$$treeLevel = 1;
                    data[11].$$treeLevel = 1;
                    data[20].$$treeLevel = 0;
                    data[25].$$treeLevel = 1;
                    data[50].$$treeLevel = 0;
                    data[51].$$treeLevel = 0;
                    $scope.gridOptions.data = data;
                });

            $scope.expandAll = function(){
                $scope.gridApi.treeBase.expandAllRows();
            };

            $scope.toggleRow = function( rowNum ){
                $scope.gridApi.treeBase.toggleRowTreeState($scope.gridApi.grid.renderContainers.body.visibleRowCache[rowNum]);
            };

            $scope.toggleExpandNoChildren = function(){
                $scope.gridOptions.showTreeExpandNoChildren = !$scope.gridOptions.showTreeExpandNoChildren;
                $scope.gridApi.grid.refresh();
            };
        }]);

    </script>
<?php $this->endBlock(); ?>