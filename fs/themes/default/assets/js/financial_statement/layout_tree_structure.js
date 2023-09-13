/* Set global variables so that can get grid item when edit Reference ID */
var grid_function = '';
var grid_col    = '';
var grid_target = '';
var grid_target = '';
/* END OF Set global variable so that can get grid item when edit Reference ID */

function numberWithCommas(x) 
{
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/* Drag Bar */
var handler = document.querySelector('.handler');
var wrapper = handler.closest('.wrapper');
var boxA = wrapper.querySelector('.box');
var isHandlerDragging = false;

// load_sub_account_list();

function load_sub_account_list()
{
    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    $.ajax({ //Upload common input
        url: "fs_account_category/partial_sub_account_list",
        type: "POST",
        data: {current_categorized_tree: CategoriedTree},
        dataType: 'html',
        success: function (response,data) {
            $('#sub_account_list .modal-body').html(response);
        }
    });
}

function load_sub_account_list_without_input_desc_name(this_node)
{
    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    var selected_acc_code = this_node['data']['account_code'];

    $.ajax({ //Upload common input
        url: "fs_account_category/partial_edit_account_code_list",
        type: "POST",
        // data: {current_categorized_tree: CategoriedTree, selected_acc_code: selected_acc_code},
        dataType: 'html',
        data: {selected_acc_code: selected_acc_code, current_categorized_tree: CategoriedTree},
        success: function (response,data) 
        {
            // console.log(response);
            // console.log(data);

            $('#edit_account_code_list .modal-body').html(response);
        }
    });
}

// function load_main_account_list()
// {
//     var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
//     var CategoriedTree = JSON.parse(JSON.stringify(v));

//     $.ajax({ //Upload common input
//         url: "fs_account_category/partial_main_account_list",
//         type: "POST",
//         data: {current_categorized_tree: CategoriedTree},
//         dataType: 'html',
//         success: function (response,data) {
//             $('#main_account_list .modal-body').html(response);
//         }
//     });
// }

document.addEventListener('mousedown', function (e) {
    // If mousedown event is fired from .handler, toggle flag to true
    if (e.target === handler) {
        isHandlerDragging = true;
    }
});

document.addEventListener('mousemove', function (e) {
    // Don't do anything if dragging flag is false
    if (!isHandlerDragging) {
        return false;
    }

    // Get offset
    var containerOffsetLeft = wrapper.offsetLeft;

    // Get x-coordinate of pointer relative to container
    var pointerRelativeXpos = e.clientX - containerOffsetLeft;

    // Resize box A
    // * 8px is the left/right spacing between .handler and its inner pseudo-element
    // * Set flex-grow to 0 to prevent it from growing
    boxA.style.width = (pointerRelativeXpos - 8) + 'px';
    boxA.style.flexGrow = 0;
});

document.addEventListener('mouseup', function (e) {
    // Turn off dragging flag when user mouse is up
    isHandlerDragging = false;
});
/* End of Drag Bar */

// /* Change div height if tree expand */
// function changeHeight() {
//     console.log("change height");
//     var height = $('.jstree-grid-wrapper').height();
//     $(".classificationBox").css({ "height": "" + height + "" });
// }
// window.addEventListener('resize', changeHeight);
// changeHeight();
// /* End of change div height if tree expand */

$("input#categorized_account_search").keyup(function (e) {
    var tree = $("#Categoried_Treeview").jstree();
    tree.search($(this).val());
});

$("#Categoried_Treeview")
.jstree({
    "core": {
        'check_callback': function (operation, node, node_parent, node_position, more) {
            if (operation === "move_node") {
                if (node_parent.parents.length >= 4 && node.data.Type === "Branch") {
                    return false;
                }
            }
            return true;  //allow all other operations
        },
        "expand_selected_onload": true,
        "animation": 0,
        "themes": { "icons": false },
        'data': {
            'url': "fs_account_category/categoriedDefaultData/" + $('#fs_company_info_id').val(),
            'dataType': 'json'
            // 'data': function (node) {
            //     console.log(node);
            //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
            // }
        }
    },
    "grid": {
        columns: [{
            'minWidth': 250,
            'header': 'Category & Description'
        },
        {
            'cellClass': "acenter",
            'minWidth': 100,
            'header': 'Reference ID',
            'value': 'account_code'
        },
        {
            'minWidth': 100,
            'header': 'Current Year Value',
            'value': 'value'
        },
        {
            'minWidth': 100,
            'header': 'Last Year Value',
            'value': 'company_end_prev_ye_value'
        }],
        resizable:true,
        // draggable:true,
        contextmenu:true,
        gridcontextmenu: function (grid,tree,node,val,col,t,target)
        {
            // console.log(grid);
            // console.log(node);

            var this_obj = t.get_node(node);

            // exclude column with name of "Account Code", else others can have change value menu to edit value. 
            if(col['header'] != "Reference ID")
            {
                return {
                    "edit": {
                        label: "Change value",
                        icon: "glyphicon glyphicon-pencil",
                        "action": function (data) {

                            var obj = t.get_node(node);

                            grid._edit(obj,col,target);
                        }
                    }
                }
            }
            else if(col['header'] == "Reference ID" && this_obj.type != "Leaf" && this_obj.parent != "#")
            {
                return {
                    "edit": {
                        label: "Change Reference ID",
                        icon: "glyphicon glyphicon-pencil",
                        "action": function (data) {

                            var obj = t.get_node(node);

                            // grid._edit(obj,col,target);
                            grid_function = grid;
                            grid_obj    = obj;      // set global value
                            grid_col    = col;      // set global value
                            grid_target = target;   // set global value

                            // console.log(grid);
                            // console.log(target);
                            // console.log(obj);

                            load_sub_account_list_without_input_desc_name(obj);
                            $("#edit_account_code_list").modal("show"); 
                        }
                    }
                }
            }
            else
            {
                return {};
            }
        },
    },
    "dnd" : {
        "is_draggable" : function(node) 
        {
            if(main_account_code_list.includes(node[0]['data']['account_code']))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    },
    "types": {
        "#": {
            "valid_children": ["Branch", "Leaf", "UncategoriedLeaf"],
            "max_depth": 5
        },
        "Branch": {
            "valid_children": ["Branch", "Leaf", "UncategoriedLeaf"],
            "a_attr": { "style": "color:green" }
        },
        "Leaf": {
            "valid_children": [],
            "hover_node": true
        },
        "UncategoriedLeaf": {
            "valid_children": []
        },
        "default": {
            "valid_children": ["Leaf", "UncategoriedLeaf"]
        }
    },
    "contextmenu": {
        items: function customMenu(node) {
            var ref = $('#Categoried_Treeview').jstree(true);
            // The default set of all items
            var items = {
                createItem: { // The "create" menu item
                    label: "Create",
                    action: function () {
                        // console.log(ref.get_selected());
                        // console.log($("#Categoried_Treeview").jstree(true).get_selected());
                        load_sub_account_list();
                        $("#sub_account_list").modal("show"); 

                        // var sel = ref.get_selected();
                        // if (!sel.length) { return false; }
                        // sel = sel[0];
                        // sel = ref.create_node(sel, { "text": "New Category", "type": "Branch", 'data': { 'Type': 'Branch' } });

                        // if (sel) {
                        //     ref.edit(sel);
                        // }
                    }
                },
                renameItem: { // The "rename" menu item
                    label: "Rename",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);
                        inst.edit(obj);
                    }
                },
                deleteItem: { // The "delete" menu item
                    label: "Delete",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        var children_id = $("#Categoried_Treeview").jstree(true).get_node(obj.id).children;

                        // create node to uncategorized 
                        for(i = 0; i < children_id.length; i++)
                        {
                            $('#Uncategoried_Treeview').jstree().create_node('#', $("#Categoried_Treeview").jstree(true).get_node(children_id[i]), "first", function(){});
                        }

                        // delete node from categorized tree
                        if (inst.is_selected(obj)) {
                            inst.delete_node(inst.get_selected());
                        }
                        else {
                            inst.delete_node(obj);
                        }
                    }
                }
            };

            // remove context menu selection
            if (node.type == "Leaf" || node.type == "Uncategoried") {
                delete items.createItem;
                // delete items.renameItem;
                delete items.deleteItem;
            }

            // remove create selection from menu from level 4
            if (ref.get_selected(node)[0].parents.length > 3) {
                delete items.createItem;
            }

            // remove delete selection from menu only
            if(node.parent == "#" || node.data.account_code == "C101" || node.data.account_code == "C102")
            {
                delete items.deleteItem;
            }

            return items;
        }
    },
    "plugins": [
        "contextmenu", "grid", "dnd", "state", "types", "themes","json","search"
    ]
})
.on('copy_node.jstree', function (e, data) {
    $('#Categoried_Treeview').jstree(true).set_id(data.node, data.original.id);
})
.bind("loaded.jstree", function (event, data) {
    $(this).jstree("open_all");
})
.on('update_cell.jstree-grid',function (e,data) {   // replace (-) sign and insert neagtive brackets when value is changed.

    // console.log(data);
    // for this year value
    var value_this_year = data.node.data.value;

    if (value_this_year.includes("-") || value_this_year.includes("(") || value_this_year.includes(")"))
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = value_this_year.replace(/-/g, "");      // replace (-) sign
        value_this_year = value_this_year.replace(/[()]/g, "");   // replace "()" brackets
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator 
        value_this_year = '(' + value_this_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator
        value_this_year = value_this_year.toString();             // add negative brackets
    }

    data.node.data.value = value_this_year;   // update this year value

    // for this year value
    var value_last_year = data.node.data.company_end_prev_ye_value;

    if (value_last_year.includes("-") || value_last_year.includes("(") || value_last_year.includes(")"))
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = value_last_year.replace(/-/g, "");
        value_last_year = value_last_year.replace(/[()]/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator 
        value_last_year = '(' + value_last_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator
        value_last_year = value_last_year.toString();             // add negative brackets
    }

    data.node.data.company_end_prev_ye_value = value_last_year;   // update last year value
});

// bold wording in row if keyword matched.
$("input#uncategorized_account_search").keyup(function (e) {

    var tree = $("#Uncategoried_Treeview").jstree();
    tree.search($(this).val());
});

$('#Uncategoried_Treeview').jstree({
    "core": {
        "animation": 0,
        'check_callback': true,
        "themes": { "icons": false },
        'data': {
            //'url': "/AccountCategory/UncategoriedData?AccountId=" + AccountId,
            'url': "fs_account_category/uncategoriedData/" + $('#fs_company_info_id').val(),
            'dataType': 'json'
            // 'data': function (node) {
            //     console.log(node);
            //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
            // }
        }
    },
    "grid": {
        "columns": [{
            'minWidth': 200,
            'header': 'Description'
        },
        {
            'minWidth': 130,
            'contentAlignment': 'right',
            'header': 'Current Year Value',
            'value': 'value'
        },
        {
            'minWidth': 130,
            'contentAlignment': 'right',
            'header': 'Last Year Value',
            'value': 'company_end_prev_ye_value'
        }],
        resizable:true,
        // draggable:true,
        contextmenu:true,
        gridcontextmenu: function (grid,tree,node,val,col,t,target)
        {
            return {
                "edit": {
                    label: "Change value",
                    icon: "glyphicon glyphicon-pencil",
                    "action": function (data) {
                        // console.log(data);
                        var obj = t.get_node(node);
                        grid._edit(obj,col,target);
                    }
                }
            }
            
        }
    },
    "types": {
        "#": {
            "valid_children": ["Leaf", "UncategoriedLeaf"]
        },
        "Leaf": {
            "valid_children": [],
            "hover_node": true
        },
        "UncategoriedLeaf": {
            "valid_children": []
        },
        "default": {
            "valid_children": ["UncategoriedLeaf", "Leaf"]
        }
    },
    "contextmenu": {
        items: function customMenu(node) {
            var ref = $('#Categoried_Treeview').jstree(true);
            // The default set of all items
            var items = {
                renameItem: { // The "rename" menu item
                    label: "Rename",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);
                        inst.edit(obj);
                    }
                },
                deleteItem: { // The "delete" menu item
                    label: "Delete",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        var children_id = $("#Categoried_Treeview").jstree(true).get_node(obj.id).children;

                        // create node to uncategorized 
                        // for(i = 0; i < children_id.length; i++)
                        // {
                        //     $('#Uncategoried_Treeview').jstree().create_node('#', $("#Categoried_Treeview").jstree(true).get_node(children_id[i]), "first", function(){});
                        // }

                        // delete node from uncategorized tree
                        if (inst.is_selected(obj)) {
                            inst.delete_node(inst.get_selected());
                        }
                        else {
                            inst.delete_node(obj);
                        }
                    }
                }
            };

            return items;
        }
    },
    "plugins": [
        "contextmenu", "grid", "dnd", "state", "types", "themes","json","search", "wholerow"
    ]
})
.on('copy_node.jstree', function (e, data) {
    $('#Uncategoried_Treeview').jstree(true).set_id(data.node, data.original.id);
})
.on('update_cell.jstree-grid',function (e,data) {   // replace (-) sign and insert neagtive brackets when value is changed.

    // for this year value
    var value_this_year = data.node.data.value;

    if (value_this_year.includes("-") || value_this_year.includes("(") || value_this_year.includes(")"))
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = value_this_year.replace(/-/g, "");      // replace (-) sign
        value_this_year = value_this_year.replace(/[()]/g, "");   // replace "()" brackets
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator
        value_this_year = '(' + value_this_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator
        value_this_year = value_this_year.toString();             // add negative brackets
    }

    data.node.data.value = value_this_year;   // update this year value

    // for this year value
    var value_last_year = data.node.data.company_end_prev_ye_value;

    if (value_last_year.includes("-") || value_last_year.includes("(") || value_last_year.includes(")"))
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = value_last_year.replace(/-/g, "");
        value_last_year = value_last_year.replace(/[()]/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator 
        value_last_year = '(' + value_last_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator 
        value_last_year = value_last_year.toString();             // add negative brackets
    }

    data.node.data.company_end_prev_ye_value = value_last_year;   // update last year value
});

$('#CreateAccount').click(function () {
    $("#create_account_form").modal("show"); 
    $("#create_account_form #uncategorised_new_account").val("");

    // $('#Categoried_Treeview').jstree("create_node", null, {
    //     "text": "New Category", "type": "Branch", "data": { "Type": "#" }
    // }, "first", function (node) {
    //     this.edit(node);
    // });
});

// $('#CreateMainCategory').click(function () {
//     load_main_account_list();
//     $("#main_account_list").modal("show"); 

//     // $('#Categoried_Treeview').jstree("create_node", null, {
//     //     "text": "New Category", "type": "Branch", "data": { "Type": "#" }
//     // }, "first", function (node) {
//     //     this.edit(node);
//     // });
// });

// $('#Categoried_Treeview').on('rename_node.jstree', function (e, data) {
//     if(data.node.parent == "#")
//     {
//         // check if the id is existed.
//         var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
//         var categoriedTree = JSON.parse(JSON.stringify(v));
//         var isAccount_existed_in_tree = false;

//         categoriedTree.forEach(function(element) {
//             // console.log(element);

//             if(element.id == data.text)
//             {
//                 isAccount_existed_in_tree = true;

//                 alert("Account is existed in tree. Therefore, it will not be created.");
//                 $("#Categoried_Treeview").jstree().delete_node(data.node);
//             }
//         });

//         if(!isAccount_existed_in_tree)
//         {
//             $.ajax({
//                 type: 'post',
//                 url: "fs_account_category/get_default_account_list",
//                 // dataType: 'json',
//                 data: { account_code: data.text},
//                 success: function (response) {
//                     // console.log(response);
//                     if(response == '')
//                     {
//                         alert("Account code is not found. Therefore, it will not be created.");

//                         $("#Categoried_Treeview").jstree().delete_node(data.node);
//                     }
//                     else
//                     {
//                         var result = JSON.parse(response);

//                         $('#Categoried_Treeview').jstree(true).set_id(data.node, data.text);

//                         var selected_node = $('#Categoried_Treeview').jstree(true).get_node(data.text);
//                         $("#Categoried_Treeview").jstree('set_text', selected_node, result.description);
//                     }
//                 }
//             });
//         }
//     }
//     else // if account is a sub
//     {
//         console.log(data);
//     }
// });
/* End of treeview with JsTree plugin */

/* Save action */
// $('#SaveAllAccountDetail').click(function () {
//     var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
//     var CategoriedTree = JSON.parse(JSON.stringify(v));

//     var u = $('#Uncategoried_Treeview').jstree(true).get_json('#', { flat: true });
//     var UncategoriedTree = JSON.parse(JSON.stringify(u));

//     $.ajax({
//         type: 'post',
//         url: "fs_account_category/save_categorized_uncategorized_account",
//         dataType: 'json',
//         data: { CategoriedTree: CategoriedTree, UncategoriedTree: UncategoriedTree, fs_company_info_id: $('#fs_company_info_id').val() },
//         success: function (response) {
//             // console.log(response);

//             alert(response.message);

//             // if(response['result'])
//             // {
//             //     alert("Successfully saved all trees!");
//             // }
//             // else
//             // {
//             //     alert("Opps! Something went wrong! Please try again later.");
//             // }
//         }
//     });

//     // $.ajax({
//     //     type: 'post',
//     //     url: "fs_account_category/write_main_excel",
//     //     dataType: 'json',
//     //     // data: { CategoriedTree: CategoriedTree, UncategoriedTree: UncategoriedTree, fs_company_info_id: $('#fs_company_info_id').val() },
//     //     success: function (response) {
//     //         // console.log(response);

//     //         if(response['result'])
//     //         {
//     //             alert("Excel file is created!");
//     //         }
//     //         else
//     //         {
//     //             alert("Opps! Something went wrong! Please try again later.");
//     //         }
//     //     }
//     // });

// });

/* End of save action */

/* For main account list */
function select_main(main_account_code, description)
{
    var fs_default_acc_category_id = $("#tbl_main_account_list tr.selected .fs_default_acc_category_id").val();
    var ref = $("#Categoried_Treeview").jstree(true);
    
    $('#Categoried_Treeview').jstree("create_node", null, {
        "id": main_account_code, "text": description, "type": "Branch", 'data': { 'account_code': main_account_code, 'Type': '#', 'fs_default_acc_category_id': fs_default_acc_category_id }
    });

    ref.deselect_all();
    ref.select_node(main_account_code);

    $("#main_account_list").modal("hide");
}
/* END OF For main account list */

/* For sub account list */
function select_sub(sub_account_code, description)
{
    description  = $("#partial_sub_account_list #input_new_description").val();
    description  = description.trimLeft();  // remove whitespace in the beginning

    var fs_default_acc_category_id = '';

    if(description)
    {
        var link_with_ref_id = $("#link_with_ref_id_val").val();
        var node_id = 'NS' + categoried_treeview_dynamic_temp_id;

        if(link_with_ref_id !== '0')
        {
            node_id = $("#tbl_sub_account_list tr.selected td:first").html();
            sub_account_code = $("#tbl_sub_account_list tr.selected td:first").html();
            fs_default_acc_category_id = $("#tbl_sub_account_list tr.selected .fs_default_acc_category_id").val();
        }
        else
        {
            sub_account_code = '';
            categoried_treeview_dynamic_temp_id++;
        }

        var ref = $("#Categoried_Treeview").jstree(true);
        var sel = $("#Categoried_Treeview").jstree(true).get_selected();

        if (!sel.length) { return false; }

        sel = sel[0];
        sel = ref.create_node(sel, { "id": node_id, "text": description, "type": "Branch", 'data': { 'id': '', 'account_code': sub_account_code, 'Type': 'Branch', 'fs_default_acc_category_id': fs_default_acc_category_id } });

        ref.deselect_all();
        ref.select_node(sub_account_code);

        $("#sub_account_list").modal("hide");
    }
    else
    {
        alert("Description cannot be empty!");
    }
}
/* END OF For sub account list */

function edit_account_code(account_code, fs_default_acc_category_id)
{
    if(account_code == undefined)
    {
        grid_obj.data.account_code = '';
        grid_obj.data.fs_default_acc_category_id = '';
        $(grid_target).text('');
    }
    else
    {
        grid_obj.data.account_code = account_code;
        grid_obj.data.fs_default_acc_category_id = fs_default_acc_category_id;
        $(grid_target).text(account_code);
    }

    $("#edit_account_code_list").modal("hide");
}

// $('#btn_select_main').on('click', function(e)
// {
//     var main_account_code = $("#tbl_main_account_list tr.selected td:first").html();
//     var description  = $("#tbl_main_account_list tr.selected td:last").html();
//     var fs_default_acc_category_id = $("#tbl_main_account_list tr.selected .fs_default_acc_category_id").val();

//     var ref = $("#Categoried_Treeview").jstree(true);

//     if(main_account_code) 
//     {
//         $('#Categoried_Treeview').jstree("create_node", null, {
//             "id": main_account_code, "text": description, "type": "Branch", 'data': { 'account_code': main_account_code, 'Type': '#', 'fs_default_acc_category_id': fs_default_acc_category_id }
//         });

//         ref.deselect_all();
//         ref.select_node(main_account_code);
//     }

//     $("#main_account_list").modal("hide");
// });

var categoried_treeview_dynamic_temp_id = 1;

$('#sub_account_list #btn_insert_sub').on('click', function(e)
{
    var description  = $("#partial_sub_account_list #input_new_description").val();

    if(description)
    {
        var link_with_ref_id = $("#link_with_ref_id_val").val();
        var sub_account_code = '';
        var node_id = 'NS' + categoried_treeview_dynamic_temp_id;
        var fs_default_acc_category_id = '';

        if(link_with_ref_id !== '0')
        {
            node_id = $("#tbl_sub_account_list tr.selected td:first").html();
            sub_account_code = $("#tbl_sub_account_list tr.selected td:first").html();
            fs_default_acc_category_id = $("#tbl_sub_account_list tr.selected .fs_default_acc_category_id").val();
        }
        else
        {
            categoried_treeview_dynamic_temp_id++;
        }

        // console.log(node_id, description, sub_account_code, categoried_treeview_dynamic_temp_id);

        var ref = $("#Categoried_Treeview").jstree(true);
        var sel = $("#Categoried_Treeview").jstree(true).get_selected();

        if (!sel.length) { return false; }

        // if(sub_account_code) 
        // {
            sel = sel[0];
            // sel = ref.create_node(sel, { "id": sub_account_code, "text": description, "type": "Branch", 'data': { 'account_code': sub_account_code, 'Type': 'Branch' } });
            sel = ref.create_node(sel, { "id": node_id, "text": description, "type": "Branch", 'data': { 'id': '', 'account_code': sub_account_code, 'Type': 'Branch', 'fs_default_acc_category_id': fs_default_acc_category_id } });

            ref.deselect_all();
            ref.select_node(sub_account_code);
        // }

        $("#sub_account_list").modal("hide");
    }
    else
    {
        alert("Description cannot be empty!");
    }
});

$('#edit_account_code_list #btn_edit_sub').on('click', function(e)
{
    var edit_s_account_code = $("#tbl_edit_sub_account_list tr.selected .account_code").text();
    var edit_s_fs_default_acc_category_id = $("#tbl_edit_sub_account_list tr.selected .fs_default_acc_category_id").val();

    edit_account_code(edit_s_account_code,edit_s_fs_default_acc_category_id);
});

// function createNewCategory()
// {
//     var new_description = $('#input_new_description').val();

//     $.ajax({
//         type: 'post',
//         url: "fs_account_category/create_new_category",
//         dataType: 'html',
//         data: { new_description: new_description},
//         success: function (response) {
//             response = JSON.parse(response);
//             // console.log(response);
//             // console.log(response['result']);
//             if(response['result'] == true)
//             {
//                 $('.result_msg').html('Successfully created!');
//                 $('.result_msg').css('color', 'green');
            
//                 load_sub_account_list();

//                 alert("Sucessfully created!");
//             }
//             else
//             {
//                 // console.log('Opps! Something went wrong.');
//                 $('.result_msg').html('Opps! Something went wrong.');
//                 $('.result_msg').css('color', 'red');

//                 alert('Opps! Something went wrong.');
//             }
//             // console.log(response);
            
//         }
//     });
// }

function create_uncategorized_account()
{
    // var main_account_code = $("#tbl_main_account_list tr.selected td:first").html();
    // var description  = $("#tbl_main_account_list tr.selected td:last").html();

    var ref = $("#Uncategoried_Treeview").jstree(true);

    var new_account = $('#uncategorised_new_account').val();

    // console.log(new_account);
    // if(main_account_code) 
    // {
        $('#Uncategoried_Treeview').jstree("create_node", null, {
            "id": '', "parent": '#', "text": new_account, "type": "Leaf", "data": { "value": '0.00', "company_end_prev_ye_value": '0.00' }
        });

        // ref.deselect_all();
        // ref.select_node(main_account_code);
    // }

    $("#create_account_form").modal("hide");
};

// function change_result_msg()
// {
//     $('.result_msg').html('Input account name and hit "Create" button to create new account.');
//     $('.result_msg').css('color', 'black');
// }

// Keyboard trigger functions 
$('#uncategorised_new_account').keypress(function (e) 
{
    var key = e.which;

    if(key == 13)  // the enter key code
    {
        create_uncategorized_account();
    }
});
