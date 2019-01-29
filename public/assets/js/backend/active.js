define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'active/index',
                    add_url: 'active/add',
                    edit_url: 'active/edit',
                    del_url: 'active/del',
                    multi_url: 'active/multi',
                    table: 'active',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                search: false, //是否启用快速搜索
                showExport: false,
                showToggle: false,
                showColumns: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'username', title: __('User_id')},
                        {field: 'coupon_name', title: __('Coupon_name')},
                        {field: 'active_count', title: __('领取数')},
                        {
                            field: 'buttons',
                            title: __('查看'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('查看'),
                                    title: __('领取列表'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-gratipay',
                                    extend: 'data-area=\'["1000px","80%"]\'',
                                    url: function(row){
                                        return 'user_active/index?active_id='+row.id;
                                    },
                                    callback: function (data) {
                                        console.log(data);
                                        console.log(111111111111111111111111111);
                                    }
                                }
                            ],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'min_amount', title: __('Min_amount'), operate:'BETWEEN'},
                        {field: 'prefer_acount', title: __('Prefer_acount'), operate:'BETWEEN'},
                        {field: 'start_time', title: __('Start_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"2":__('Status 2'),"1":__('Status 1'),"0":__('Status 0')}, formatter: Table.api.formatter.status},
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