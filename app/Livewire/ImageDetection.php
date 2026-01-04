<?php

namespace App\Livewire;

use App\Service\HuggingFaceService;
use Livewire\Component;

class ImageDetection extends Component
{
  public $imageUrl;
  public $objects = [];

  public function detect(HuggingFaceService $hf)
  {
    $this->validate([
      'imageUrl' => 'required|url',
    ]);

    $this->objects = $hf->detectObjectImage('/models/facebook/detr-resnet-50', [
      'inputs' => $this->imageUrl
    ]);
  }

  public function render()
  {
    return view('livewire.image-detection');
  }
}
