<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "品牌列表";
?>
    <div ng-controller="walkthroughCtrl">
        <table at-table at-list="people" class="table table-striped" at-config="config" at-paginated>
            <thead></thead>
            <tbody>
            <tr>
                <td at-implicit at-attribute="name" at-sortable></td>
                <td at-implicit at-attribute="age" at-sortable></td>
                <td at-attribute="birthdate" at-sortable at-initial-sorting="desc">
                    {{item.birthdate.substring(0, 10)}}
                </td>
            </tr>
            </tbody>
        </table>
        <at-pagination at-config="config" at-list="people"></at-pagination>
    </div>
<?php
AppAsset::addJs($this,'http://samu.github.io/angular-table/js/angular-table.min.js');
AppAsset::addJs($this,'http://samu.github.io/angular-table/js/angular-tabs.min.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        var app = angular.module("mall", ["angular-table", "angular-tabs"]);
        app.controller("walkthroughCtrl", ["$scope", function($scope) {
            $scope.people = [{
                "id": 0,
                "age": 24,
                "name": "Mathis Hurst",
                "birthdate": "2004-11-17T00:04:56 -01:00"
            }, {
                "id": 1,
                "age": 38,
                "name": "Gallegos Ryan",
                "birthdate": "2001-08-06T11:04:54 -02:00"
            }, {
                "id": 2,
                "age": 27,
                "name": "Jodi Valencia",
                "birthdate": "2012-10-16T12:15:19 -02:00"
            }, {
                "id": 3,
                "age": 28,
                "name": "Jenna Anderson",
                "birthdate": "1990-05-06T01:57:40 -02:00"
            }, {
                "id": 4,
                "age": 28,
                "name": "Horne Clark",
                "birthdate": "1991-11-19T19:23:53 -01:00"
            }, {
                "id": 5,
                "age": 21,
                "name": "Briggs Walters",
                "birthdate": "1990-01-12T08:16:45 -01:00"
            },{
                "id": 14,
                "age": 32,
                "name": "Claudine Nunez",
                "birthdate": "2010-04-07T08:08:06 -02:00"
            }, {
                "id": 15,
                "age": 35,
                "name": "Sylvia Lindsay",
                "birthdate": "1992-07-28T21:54:32 -02:00"
            }, {
                "id": 16,
                "age": 36,
                "name": "Rosalie Wilkins",
                "birthdate": "1994-05-07T06:35:55 -02:00"
            }, {
                "id": 17,
                "age": 31,
                "name": "Dina Carpenter",
                "birthdate": "2013-12-05T21:29:41 -01:00"
            }, {
                "id": 18,
                "age": 38,
                "name": "Roxanne Cardenas",
                "birthdate": "2007-05-04T03:52:48 -02:00"
            }, {
                "id": 19,
                "age": 29,
                "name": "Sasha Everett",
                "birthdate": "2006-08-03T20:29:32 -02:00"
            }, {
                "id": 20,
                "age": 27,
                "name": "Chandler Crawford",
                "birthdate": "2009-02-24T18:25:31 -01:00"
            }, {
                "id": 21,
                "age": 32,
                "name": "Flora Boyle",
                "birthdate": "1995-09-03T23:04:36 -02:00"
            }, {
                "id": 22,
                "age": 37,
                "name": "Terrie Moran",
                "birthdate": "1989-09-25T05:07:00 -02:00"
            }, {
                "id": 23,
                "age": 30,
                "name": "Mueller Hewitt",
                "birthdate": "2007-07-15T22:25:24 -02:00"
            }, {
                "id": 24,
                "age": 37,
                "name": "Neva Mcfadden",
                "birthdate": "1997-04-22T17:07:56 -02:00"
            }, {
                "id": 25,
                "age": 20,
                "name": "Oconnor Lang",
                "birthdate": "1999-10-18T02:26:35 -02:00"
            }, {
                "id": 26,
                "age": 32,
                "name": "Lucille Mcguire",
                "birthdate": "2012-04-19T09:10:43 -02:00"
            }, {
                "id": 27,
                "age": 38,
                "name": "Nadia Beach",
                "birthdate": "2007-08-02T15:59:16 -02:00"
            }, {
                "id": 28,
                "age": 24,
                "name": "George Mercer",
                "birthdate": "2005-07-21T03:44:46 -02:00"
            }, {
                "id": 29,
                "age": 28,
                "name": "Reid Spears",
                "birthdate": "1996-10-07T19:29:49 -02:00"
            }, {
                "id": 30,
                "age": 25,
                "name": "Allen Woods",
                "birthdate": "2002-03-21T12:42:21 -01:00"
            }, {
                "id": 31,
                "age": 28,
                "name": "Jeannette Alford",
                "birthdate": "1993-01-11T21:15:10 -01:00"
            }, {
                "id": 32,
                "age": 35,
                "name": "Mia Pittman",
                "birthdate": "1990-08-05T16:59:03 -02:00"
            }, {
                "id": 33,
                "age": 39,
                "name": "Amanda Holder",
                "birthdate": "1989-11-02T04:42:42 -01:00"
            }, {
                "id": 34,
                "age": 25,
                "name": "Nelson Jimenez",
                "birthdate": "1994-10-18T17:33:06 -02:00"
            }, {
                "id": 35,
                "age": 35,
                "name": "Griffith Soto",
                "birthdate": "2000-02-10T22:29:47 -01:00"
            }, {
                "id": 36,
                "age": 39,
                "name": "Forbes Fernandez",
                "birthdate": "2003-09-17T06:09:03 -02:00"
            }, {
                "id": 37,
                "age": 24,
                "name": "Deana Ross",
                "birthdate": "1996-06-15T20:53:02 -02:00"
            }, {
                "id": 38,
                "age": 27,
                "name": "Lawrence Kane",
                "birthdate": "2005-09-21T20:14:37 -02:00"
            }, {
                "id": 39,
                "age": 31,
                "name": "Lillie Velez",
                "birthdate": "2006-03-19T07:29:01 -01:00"
            }
            ];

            $scope.config = {};
        }]);
    </script>
<?php $this->endBlock(); ?>