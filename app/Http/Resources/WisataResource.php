<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WisataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'lat' => (float) $this->lat,
            'lng' => (float) $this->lng,
            'kategori' => $this->kategori,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
