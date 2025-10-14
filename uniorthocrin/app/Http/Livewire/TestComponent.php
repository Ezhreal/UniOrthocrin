<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public $message = 'Componente carregado com sucesso!';
    
    public function test()
    {
        $this->message = 'Botão clicado! Livewire está funcionando!';
    }
    
    public function render()
    {
        return view('livewire.test-component');
    }
}
