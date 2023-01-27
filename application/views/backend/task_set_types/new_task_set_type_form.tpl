{include file='partials/backend_general/flash_messages.tpl' inline}
<div class="field">
    <label for="task_set_type_name_id" class="required">{translate line='admin_task_set_types_form_label_name'}:</label>
    <p class="input"><input type="text" name="task_set_type[name]" value="{$smarty.post.task_set_type.name|escape:'html'}" id="task_set_type_name_id" /></p>
    {form_error field='task_set_type[name]' left_delimiter='<p class="error"><span class="message">' right_delimiter='</span></p>'}
</div>
<div class="field">
    <label for="task_set_type_identifier_id">{translate line='admin_task_set_types_form_label_identifier'}:</label>
    <p class="input"><input type="text" name="task_set_type[identifier]" value="{$smarty.post.task_set_type.identifier|escape:'html'}" id="task_set_type_identifier_id" /></p>
    {form_error field='task_set_type[identifier]' left_delimiter='<p class="error"><span class="message">' right_delimiter='</span></p>'}
</div>
<div class="buttons">
    <input type="submit" value="{translate line='admin_task_set_types_form_button_submit'}" name="submit_button" class="button" />
</div>