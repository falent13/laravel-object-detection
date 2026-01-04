<div class="p-6 max-w-5xl mx-auto">
  <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
    <div class="mb-8">
      <h1 class="text-3xl font-extrabold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
        Object Detection
      </h1>
      <p class="text-gray-500 mt-2">Masukkan URL gambar untuk mendeteksi objek menggunakan Hugging Face AI.
      </p>
    </div>

    <form wire:submit="detect" class="space-y-4">
      <div>
        <label for="imageURL" class="block text-sm font-semibold text-gray-700 mb-2">Image URL</label>
        <div class="flex gap-3">
          <input type="url" id="imageURL" wire:model="imageURL" placeholder="https://example.com/image.jpg"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">
          <button type="submit" wire:loading.attr="disabled"
            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition disabled:opacity-50">

            <span wire:loading.remove wire:target="detect">Deteksi</span>
            <span wire:loading wire:target="detect" class="flex items-center">
              Deteksi...
            </span>
          </button>
        </div>
        @error('imageURL')
          <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span>
        @enderror
      </div>
    </form>

    <div class="mt-10">
      <div wire:loading wire:target="detect"
        class="w-full h-64 bg-gray-50 animate-pulse rounded-xl flex items-center justify-center border-2 border-dashed border-gray-200">
        <span class="text-gray-400 font-medium text-lg"> Sedang menganalisis gambar...</span>
      </div>

      <div wire:loading.remove wire:target="detect">
        @if ($imageURL)
          <div class="relative inline-block border-4 border-white shadow-2xl rounded-lg overflow-hidden bg-gray-100"
            x-data="{
                naturalWidth: 0,
                naturalHeight: 0,
                getScale() {
                    return {
                        x: $el.clientWidth / this.naturalWidth,
                        y: $el.clientHeight / this.naturalHeight
                    }
                }
            }">

            <img src="{{ $imageURL }}" alt="Detection Target" class="max-w-full h-auto block"
              style="max-height: 70vh;" x-on:load="naturalWidth = $el.naturalWidth; naturalHeight = $el.naturalHeight">

            @foreach ($objects as $object)
              @php
                $box = $object['box'];
                $score = round($object['score'] * 100);
              @endphp

              <div class="absolute border-2 border-green-400 bg-green-400/10 pointer-events-none group"
                style="
                                    left: calc(({{ $box['xmin'] }} / naturalWidth) * 100%);
                                    top: calc(({{ $box['ymin'] }} / naturalHeight) * 100%);
                                    width: calc(({{ $box['xmax'] }} - {{ $box['xmin'] }}) / naturalWidth * 100%);
                                    height: calc(({{ $box['ymax'] }} - {{ $box['ymin'] }}) / naturalHeight * 100%);
                                 ">
                <span
                  class="absolute -top-6 left-0 bg-green-500 text-white text-[10px] px-2 py-0.5 rounded-t-md font-bold whitespace-nowrap shadow-sm">
                  {{ strtoupper($object['label']) }} {{ $score }}%
                </span>
              </div>
            @endforeach
          </div>

          <div class="mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Object Name</th>
                  <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Confidence Score</th>
                  <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($objects as $object)
                  <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 uppercase">
                      {{ $object['label'] }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {{ round($object['score'] * 100, 2) }}%
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                      <span
                        class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Detected</span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-20 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
              </path>
            </svg>
            <p class="mt-4 text-gray-500 font-medium">No image analyzed yet. Enter a URL above to start.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
