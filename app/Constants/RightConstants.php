<?php

namespace App\Constants;

class RightConstants extends Constants
{
    protected $defaultscope = 'P';

    protected $constants = [
        'recipe' => ['G' => 'Manage all recipes'],
        'translate' => ['G' => 'Manage translations'],
        /*'project' => ['G' => 'Create projects', 'P' => 'Project settings'],
        'patient' => ['G' => 'Manage patients / Patient history', 'P' => 'Show and edit information for patients'],
        'organization' => ['G' => 'Manage organizations'],
        'employee' => ['G' => 'Manage employees', 'P' => 'Manage employees in project'],
        'user' => ['P' => 'Manage project users'],
        'team' => ['P' => 'Manage teams in project'],
        'treatment' => ['P' => 'Create and edit treatments'],
        'treatment_extended' => ['P' => 'Reopen and delete treatments'],
        'treatment_project' => ['P' => 'Edit treatments for all stations'],
        'material_order' => ['P' => 'Can request material'],
        'material' => ['P' => 'Material management'],
        'dispatcher' => ['P' => 'Use Mission Control Center'],
        'fleet' => ['P' => 'Manage cars and mobile teams in MCC'],
        'statistic' => ['P' => 'Create and show statistics'],*/
    ];
}
