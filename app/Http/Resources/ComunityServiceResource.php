<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComunityServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'title' => $this->title,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'focus_thematic' => [
                'id' => $this->focusThematic->id,
                'name' => $this->focusThematic->name,
            ],
            'focus_r_i_r_n_s' => [
                'id' => $this->focusRirn->id,
                'name' => $this->focusRirn->name,
            ],
            'scheme' => [
                'id' => $this->scheme->id,
                'name' => $this->scheme->name,
            ],
            'scope' => [
                'id' => $this->scope->id,
                'name' => $this->scope->name,
            ],
            'year' => $this->year,
            'duration' => $this->duration,
            'cluster_lv1' =>[
                'id' => $this->clusterLv1->id,
                'name' => $this->clusterLv1->name,
            ],
            'cluster_lv2' => [
                'id' => $this->clusterLv2->id,
                'name' => $this->clusterLv2->name,
            ],
            'cluster_lv3' => [
                'id' => $this->clusterLv3->id,
                'name' => $this->clusterLv3->name,
            ],
            'leader_name' => $this->leader_name,
            'leader_task' => $this->leader_task,
            'members' => $this->members,
            'students' => $this->students,
            // 'substance_document' => $this->substance_document,
            // // 'letter_of_intent' => $this->letter_of_intent,
            'outputPartner' => $this->outputPartner,
            'outputPublication' => $this->outputPublication,
            'outputMedia' => $this->outputMedia,
            'outputVideo' => $this->outputVideo,
            'budgetPlanService' => $this->budgetPlanService,
            'partner' => $this->partner,
            'supportingFile' => $this->supportingFile,
            'status' => $this->status,
        ];
    }
}
