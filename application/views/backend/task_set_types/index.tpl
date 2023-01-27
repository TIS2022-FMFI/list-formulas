{extends file='layouts/backend.tpl'}
{block title}{translate line='admin_task_set_types_page_header'}{/block}
{block main_content}
    <h2>{translate line='admin_task_set_types_page_header'}</h2>
    {include file='partials/backend_general/flash_messages.tpl' inline}
    <fieldset>
        <legend>{translate line='admin_task_set_types_fieldset_legend_new_task_set_type'}</legend>
        <form action="{internal_url url='admin_task_set_types/create'}" method="post" id="new_task_set_type_form_id">
            {include file='backend/task_set_types/new_task_set_type_form.tpl' inline}
        </form>
    </fieldset>
    <fieldset>
        <legend>{translate line='admin_task_set_types_fieldset_legend_all_task_set_types'}</legend>
        <table class="task_set_types_table">
            <thead>
                <th>ID</th>
                <th>{translate line='admin_task_set_types_table_header_name'}</th>
                <th>{translate line='admin_task_set_types_table_header_identifier'}</th>
                <th>{translate line='admin_task_set_types_table_header_task_sets'}</th>
                <th>{translate line='admin_task_set_types_table_header_courses'}</th>
                <th class="controlls" colspan="2">{translate line='admin_task_set_types_table_header_controlls'}</th>
            </thead>
            <tbody id="task_set_types_table_content_id">
            </tbody>
        </table>
    </fieldset>
{/block}
{block custom_head}<script type="text/javascript">
    var messages = {
        delete_question: '{translate|addslashes line="admin_task_set_types_javascript_message_delete_question"}',
        after_delete: '{translate|addslashes line="admin_task_set_types_javascript_message_after_delete"}',
    }; 
</script>{/block}