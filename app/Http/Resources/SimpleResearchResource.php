<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleResearchResource extends JsonResource
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
            'title' => $this->title,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'nidn' => $this->user->nidn,
                'study_program' => $this->user->study_program,
                'education_level' => $this->user->education_level,
            ],
            'scheme' => [
                'id' => $this->scheme->id,
                'name' => $this->scheme->name,
            ],
            'focus' => [
                'id' => $this->researchFocus->id,
                'name' => $this->researchFocus->name,
            ],
            'year' => $this->year,
            'duration' => $this->duration,
            'status' => $this->status,
            'progressReport' => $this->progressReport ?? [],
            'finalReport' => $this->finalReport ?? [],
        ];
    }
}
