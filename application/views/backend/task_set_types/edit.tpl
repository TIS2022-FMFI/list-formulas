{extends file='layouts/backend.tpl'}
{block title}{translate line='admin_task_set_types_page_header'}{/block}
{block main_content}
    <h2>{translate line='admin_task_set_types_page_header'}</h2>
    {include file='partials/backend_general/flash_messages.tpl' inline}
    {if $task_set_type->exists() or $smarty.post.task_set_type}
    <fieldset>
        <form action="{internal_url url='admin_task_set_types/update'}" method="post" id="new_task_set_type_form_id">
            <div class="field">
                <label for="task_set_type_name_id" class="required">{translate line='admin_task_set_types_form_label_name'}:</label>
                <p class="input"><input type="text" name="task_set_type[name]" value="{$smarty.post.task_set_type.name|default:$task_set_type->name|escape:'html'}" id="task_set_type_name_id" /></p>
                {form_error field='task_set_type[name]' left_delimiter='<p class="error"><span class="message">' right_delimiter='</span></p>'}
            </div>
            <div class="field">
                <label for="task_set_type_identifier_id">{translate line='admin_task_set_types_form_label_identifier'}:</label>
                <p class="input"><input type="text" name="task_set_type[identifier]" value="{$smarty.post.task_set_type.identifier|default:$task_set_type->identifier|escape:'html'}" id="task_set_type_identifier_id" /></p>
                {form_error field='task_set_type[identifier]' left_delimiter='<p class="error"><span class="message">' right_delimiter='</span></p>'}
            </div>
            <div class="buttons">
                <input type="submit" value="{translate line='admin_task_set_types_form_button_submit'}" name="submit_button" class="button" /> <a href="{internal_url url='admin_task_set_types'}" class="button special">{translate line='common_button_back'}</a>
                <input type="hidden" name="task_set_type_id" value="{$smarty.post.task_set_type_id|default:$task_set_type->id|intval}" />
            </div>
        </form>
    </fieldset>
    {else}
        {include file='partials/backend_general/error_box.tpl' message='lang:admin_task_set_types_error_task_set_type_not_found' inline}
    {/if}
{/block}