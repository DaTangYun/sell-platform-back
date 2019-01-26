define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'help_me/index',
                    add_url: 'help_me/add',
                    edit_url: 'help_me/edit',
                    del_url: 'help_me/del',
                    multi_url: 'help_me/multi',
                    table: 'help_me',
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
                        {field: 'username', title: __('用户名'),operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'demands', title: __('需求类型'),operate:false},
                        {field: 'desc', title: __('Desc'),operate:false},
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image,operate:false},
                        {field: 'province', title: __('Province'),operate:false},
                        {field: 'city', title: __('City'),operate:false},
                        {field: 'area', title: __('Area'),operate:false},
                        {field: 'mobile', title: __('Mobile')},
                        {field: 'contact', title: __('Contact')},
                        {field: 'commission', title: __('Commission'),operate:false},
                        {field: 'weigh', title: __('Weigh'),operate:false},
                        {field: 'status', title: __('Status'), searchList: {"2":__('Status 2'),"1":__('Status 1'),"0":__('Status 0')}, formatter: Table.api.formatter.status},
                        {field: 'start_time', title: __('Start_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime,operate:false},
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime,operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,operate:false}
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