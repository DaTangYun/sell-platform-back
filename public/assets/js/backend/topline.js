define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'topline/index',
                    add_url: 'topline/add',
                    edit_url: 'topline/edit',
                    del_url: 'topline/del',
                    multi_url: 'topline/multi',
                    table: 'topline',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                showExport: false,
                showToggle: false,
                showColumns: false,
                searchFormVisible: true, //是否始终显示搜索表单
                search: false, //是否启用快速搜索
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'username', title: __('上传用户'),operate:false},
                        {field: 'cate_name', title: __('Topline_cate_id'),operate:false},
                        {field: 'cover', title: __('Cover'),formatter: Table.api.formatter.image,operate:false},
                        {field: 'reading_count', title: __('Reading_count'),operate:false},
                        {field: 'province', title: __('Province'),operate:false},
                        {field: 'city', title: __('City'),operate:false},
                        {field: 'area', title: __('Area'),operate:false},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'weigh', title: __('Weigh'),operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime,operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});