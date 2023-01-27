{assign 'no_yes_options' [
    {translate line='admin_courses_form_select_option_no'},
    {translate line='admin_courses_form_select_option_yes'}
]}

{foreach $task_set_types as $task_set_type}
<tr class="task_set_types_table_row">
    <td>{$task_set_type->id|intval}</td>
    <td>{translate_text|escape:'html' text=$task_set_type->name}</td>
    <td>
        {if $task_set_type->identifier != null}
            {$task_set_type->identifier|escape:'html'}
        {else}-{/if}
    </td>
    <td>
        {$no_yes_options[$task_set_type->join_upload_solution|intval]}
        <input type="hidden" value="{$task_set_type->id|intval}" name="task_set_type_id">
        <input type="hidden" value="{$course->id|intval}" name="course_id"></td>
    <td>
        {if $task_set_type->join_min_points != null}
            {$task_set_type->join_min_points}{if $task_set_type->join_min_points_in_percentage}%{/if}
        {else}-{/if}
    </td>
    <td>
        {$no_yes_options[$task_set_type->join_include_in_total|intval]}
    </td>
    <td>
        {$no_yes_options[$task_set_type->join_virtual|intval]}
    </td>
    <td>
        {if $task_set_type->join_virtual|intval == 1}{$task_set_type->join_formula}
        {else}-{/if}
    </td>
    <td class="controlls">
        <a href="{internal_url url="admin_courses/edit_task_set_type/course_id/{$course->id}/task_set_type_id/{$task_set_type->id}"}" class="button edit" title="{translate line='admin_courses_table_controlls_edit'}">
            <span class="list-icon list-icon-edit"></span>
        </a>
    </td>
    <td class="controlls">
        <a href="{internal_url url="admin_courses/delete_task_set_type/course_id/{$course->id}/task_set_type_id/{$task_set_type->id}"}" class="button delete" title="{translate line='admin_courses_table_controlls_delete'}">
            <span class="list-icon list-icon-delete"></span>
        </a>
    </td>
</tr>
{/foreach}