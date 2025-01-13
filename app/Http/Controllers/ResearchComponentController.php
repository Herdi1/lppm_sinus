<?php

namespace App\Http\Controllers;

use App\Models\Scope;
use App\Models\Scheme;
use App\Models\Category;
use App\Models\Substance;
use Illuminate\Http\Request;
use App\Models\ResearchFocus;
use App\Models\ResearchTheme;
use App\Models\ResearchTopic;
use App\Models\ScienceCluster1;
use App\Models\ScienceCluster2;
use App\Models\ScienceCluster3;
use App\Models\ResearchPriority;

class ResearchComponentController extends Controller
{
    public function getResearchScheme($target)
    {
        $data = Scheme::where('tkt_target', '<=', $target)->get();
        return response()->json($data);
    }
    public function getScope()
    {
        $data = Scope::all();
        return response()->json($data);
    }
    public function getResearchCategory()
    {
        $data = Category::all();
        return response()->json($data);
    }
    public function getResearchFocus()
    {
        $data = ResearchFocus::all();
        return response()->json($data);
    }
    public function getResearchTheme($focus_id)
    {
        $data = ResearchTheme::where('focus_id', $focus_id)->get();
        return response()->json($data);
    }
    public function getResearchTopic($theme_id)
    {
        $data = ResearchTopic::where('theme_id', $theme_id)->get();
        return response()->json($data);
    }
    public function getCluster1()
    {
        $data = ScienceCluster1::all();
        return response()->json($data);
    }
    public function getCluster2($cluster1)
    {
        $data = ScienceCluster2::where('cluster1s_id', $cluster1)->get();
        return response()->json($data);
    }
    public function getCluster3($cluster2)
    {
        $data = ScienceCluster3::where('cluster2s_id', $cluster2)->get();
        return response()->json($data);
    }
    public function getResearchPriority()
    {
        $data = ResearchPriority::all();
        return response()->json($data);
    }
    public function getSubstance()
    {
        $data = Substance::all();
        return response()->json($data);
    }
}
