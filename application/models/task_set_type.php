<?php

use Application\Interfaces\DataMapperExtensionsInterface;

/**
 * Task_set_type model.
 *
 * @property int    $id
 * @property string $updated date time format YYYY-MM-DD HH:MM:SS
 * @property string $created date time format YYYY-MM-DD HH:MM:SS
 * @property string $name
 * @property string $identifier
 * @property Task_set $task_set
 * @property Course $course
 *
 * @method DataMapper where_related_task_set(mixed $related, string $field = null, string $value = null)
 * @method DataMapper where_related_course(mixed $related, string $field = null, string $value = null)
 *
 * @package LIST_DM_Models
 * @author  Andrej Jursa
 */
class Task_set_type extends DataMapper implements DataMapperExtensionsInterface
{
    
    public $has_many = [
        'task_set',
        'course' => [
            'join_table' => 'course_task_set_type_rel',
        ],
    ];
}