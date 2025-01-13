<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchResource extends JsonResource
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
            'tkt_current' => $this->tkt_current,
            'tkt_final' => $this->tkt_final,
            'scheme' => [
                'id' => $this->scheme->id,
                'name' => $this->scheme->name,
            ],
            'scope' => [
                'id' => $this->scope->id,
                'name' => $this->scope->name,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'focus' =>  [
                'id' => $this->researchFocus->id,
                'name' => $this->researchFocus->name,
            ],
            'theme' =>  [
                'id' => $this->researchTheme->id,
                'name' => $this->researchTheme->name,
            ],
            'topic' =>  [
                'id' => $this->researchTopic->id,
                'name' => $this->researchTopic->name,
            ],
            'cluster_lv1' =>  [
                'id' => $this->scienceCluster1->id,
                'name' => $this->scienceCluster1->name,
            ],
            'cluster_lv2' => [
                'id' => $this->scienceCluster2->id,
                'name' => $this->scienceCluster2->name,
            ],
            'cluster_lv3' => [
                'id' => $this->scienceCluster3->id,
                'name' => $this->scienceCluster3->name,
            ],
            'priority' => $this->researchPriority,
            'year' => $this->year,
            'duration' => $this->duration,
            'leader' => $this->leader_name,
            'leader_task' => $this->leader_task,
            'members' => $this->members,
            'students' => $this->students,
            'substance' => [
                'id' => $this->substances->id,
                'name' => $this->substances->name
            ],
            'output' => $this->output,
            'budgetPlan' => $this->budgetPlan,
            'supportingDocument' => $this->supportingDocument,
            'status' => $this->status,
            'reviewers' => $this->reviewers,
            'user' => $this->user,
            'roles' => $this->role ? $this->role : null,
        ];
    }
}
