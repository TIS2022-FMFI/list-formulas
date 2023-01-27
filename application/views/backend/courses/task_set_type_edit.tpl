{extends file='layouts/backend.tpl'}
{block title}{translate|sprintf:{translate_text|default:{translate line='admin_courses_error_course_not_found'} text=$course->name} line='admin_course_task_set_types_editor_page_header'}{/block}
{block main_content}
    <h3>{translate|sprintf:{translate_text|default:{translate line='admin_courses_error_course_not_found'} text=$course->name} line='admin_course_task_set_types_editor_page_header'}</h3>
    {include file='partials/backend_general/flash_messages.tpl' inline}
    {if $course->exists()}
    <fieldset>
        <legend>{translate line='admin_courses_fieldset_legend_edit_task_set_type'}</legend>
        <form action="{internal_url url="admin_courses/save_task_set_type/course_id/{$course->id}/task_set_type_id/{$task_set_type->id}"}" method="post" id="edit_task_set_type_form_id">
            {include file='backend/courses/task_set_type_form.tpl' inline}
        </form>
    </fieldset>
    {else}
        {include file='partials/backend_general/error_box.tpl' message='lang:admin_courses_error_course_not_found' inline}
    {/if}
{/block}
{block custom_head}<script type="text/javascript">
    var current_course = {$course->id|default:0|intval};
    var identifiers = [];
    {foreach $identifiers as $identifier}
        identifiers.push('{$identifier|escape:'html'}');
    {/foreach}
</script>{/block}