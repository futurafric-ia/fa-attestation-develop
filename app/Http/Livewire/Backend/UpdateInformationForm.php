<?php

namespace App\Http\Livewire\Backend;

use Domain\Backend\Actions\UpdateLogo;
use Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

class UpdateInformationForm extends Component
{
    use WithFileUploads;


    /**
     * The new logo for the broker.
     *
     * @var mixed
     */
    public $logo;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = ['logo'];


    public function updateBroker(UpdateLogo $action)
    {
        $this->resetErrorBag();
        $this->validate(['logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',]);

        $fileName = "logo_saham." . $this->logo->extension();
        $this->logo->storeAs('', $fileName, 'public');

        $img = Image::make(Storage::disk('public')->path($fileName));
        $img->resize(null, 200);
        $img->save();

        settings()->set('logo_path', $fileName)->save();

        $this->emit('saved');

        session()->flash('success', "Paramètrages effectués avec succès!");

        return redirect()->route('backend.settings.index');
    }

    public function render()
    {
        return view('livewire.backend.update-information-form');
    }
}
