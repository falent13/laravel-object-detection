<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class HuggingFaceService
{

  protected string $token;
  protected string $baseUrl;

  /**
   * Create a new class instance.
   */
  public function __construct()
  {
    $this->token = config('services.huggingface.key');
    $this->baseUrl = config('services.huggingface.url');
  }

  public function detectObjectImage($model, $payload = [])
  {
    $response = Http::withToken($this->token)
      ->timeout(30)
      ->post($this->baseUrl . $model, $payload);

    return $response->json();
  }
}
