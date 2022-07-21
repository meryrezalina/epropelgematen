<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    
    public $active; 
    
    public function __construct($active)
    {
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {

        return view('components.menu', ['active' => $this->active]);
    }

    public function list(){
        return [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon'  => 'fas fa-tachometer-alt',
            ],
            [
                'label' => 'E-Propel',
                'route' => 'dashboard.propel',
                'icon'  => 'fas fa-edit',
            ],
            [
                'label' => 'E-Proposal',
                'route' => 'dashboard.proposals',
                'icon'  => 'fas fa-edit',
            ],
            [
                'label' => 'E-LPJ',
                'route' => 'dashboard.lpjs',
                'icon'  => 'fas fa-edit',
                'icon2'  => 'fas fa-chevron-left'
            ],
            [
                'label' => 'Users',
                'route' => 'dashboard.users',
                'icon'  => 'fas fa-users',
            ],
            // [
            //     'label' => 'Profil',
            //     'route' => 'dashboard.profils',
            //     'icon'  => 'fas fa-user', 
            // ]
        ];

    }


    public function isActive($label){
        return $label === $this->active;
    }
}
