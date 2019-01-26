define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ability/index',
                    edit_url: 'ability/edit',
                    del_url: 'ability/del',
                    table: 'ability',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'username', title: __('User_id'),operate:false},
                        {field: 'cate_title', title: __('Ability_id'),operate:false},
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image},
                        {field: 'price', title: __('Price')},
                        {field: 'mobile', title: __('Mobile')},
                        {field: 'status', title: __('Status'), searchList: {"2":__('Status 2'),"1":__('Status 1'),"0":__('Status 0')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime,operate:false},
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, 
                            buttons: [
                                {
                                    name: 'detail',
                                    text:'查看评论',
                                    title: __('评论列表'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-gratipay',
                                    extend: 'data-area=\'["1000px","80%"]\'',
                                    url: function(row){
                                        return 'ability_comment/index?ability_id='+row.id;
                                    }
                                }
                            ],
                            formatter: Table.api.formatter.operate}
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