<?php

namespace App\Livewire;

use Livewire\Component;

class ThreeDSecureModal extends Component
{
    public $acsUrl = '';
    public $paReq = '';
    public $termUrl = '';
    public $md = '';

    protected $listeners = ['open3DSecureModal'];

    public function open3DSecureModal($data)
    {
        $this->acsUrl = $data['acsUrl'];
        $this->paReq = $data['paReq'];
        $this->termUrl = $data['termUrl'];
        $this->md = $data['md'];
    }

    public function render()
    {
        return view('livewire.three-d-secure-modal');
    }
}
