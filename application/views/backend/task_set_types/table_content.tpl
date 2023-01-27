{foreach $task_set_types as $task_set_type}
<tr>
    <td>{$task_set_type->id|intval}</td>
    <td>{translate_text|escape:'html' text=$task_set_type->name}</td>
    <td>
        {if $task_set_type->identifier != null}
            {$task_set_type->identifier|escape:'html'}
        {else}-{/if}
    </td>
    <td>{$task_set_type->task_set_count}</td>
    <td>{$task_set_type->course_count}</td>
    <td class="controlls"><a href="{internal_url url="admin_task_set_types/edit/task_set_type_id/{$task_set_type->id}"}" class="button" title="{translate line='admin_task_set_types_table_buttons_edit'}"><span class="list-icon list-icon-edit"></span></a></td>
    <td class="controlls"><a href="{internal_url url="admin_task_set_types/delete/task_set_type_id/{$task_set_type->id}"}" class="button delete" title="{translate line='admin_task_set_types_table_buttons_delete'}"><span class="list-icon list-icon-delete"></span></a></td>
</tr>
{/foreach}