<?php

namespace App\Http\Controllers;

use App\Models\FocusRIRN;
use App\Models\FocusThematic;
use App\Models\MediaOutputCategory;
use App\Models\MediaOutputType;
use App\Models\PartnerGroup;
use App\Models\PartnerOutputCategory;
use App\Models\PartnerOutputType;
use App\Models\PartnerType;
use App\Models\PublicationOutputCategory;
use App\Models\PublicationOutputType;
use App\Models\ScienceCluster1;
use App\Models\ScienceCluster2;
use App\Models\ScienceCluster3;
use App\Models\ServiceCategory;
use App\Models\ServiceScheme;
use App\Models\ServiceScope;
use App\Models\SupportingFileType;
use App\Models\VideoOutputCategory;
use App\Models\VideoOutputType;
use Illuminate\Http\Request;

class ComunityServiceComponentController extends Controller
{
    public function getServiceCategory()
    {
        $data = ServiceCategory::all();
        return response()->json($data);
    }

    public function getScheme()
    {
        $data = ServiceScheme::all();
        return response()->json($data);
    }
    
    public function getScope()
    {
        $data = ServiceScope::all();
        return response()->json($data);
    }

    public function getFocusTemathic()
    {
        $data = FocusThematic::all();
        return response()->json($data);
    }

    public function getFocusRirn()
    {
        $data = FocusRIRN::all();
        return response()->json($data);
    }

    public function getCluster1()
    {
        $data = ScienceCluster1::all();
        return response()->json($data);
    }

    public function getCluster2($cluster1s_id)
    {
        $data = ScienceCluster2::where('cluster1s_id', $cluster1s_id)->get();
        return response()->json($data);
    }

    public function getCluster3($cluster2s_id)
    {
        $data = ScienceCluster3::where('cluster2s_id', $cluster2s_id)->get();
        return response()->json($data);
    }

    public function getPartnerOutputCategories()
    {
        $data = PartnerOutputCategory::all();
        return response()->json($data);
    }

    public function getPartnerOutputType()
    {
        $data = PartnerOutputType::all();
        return response()->json($data);
    }

    public function getPublicationOutputCategories()
    {
        $data = PublicationOutputCategory::all();
        return response()->json($data);
    }

    public function getPublicationOutputType($category_id)
    {
        $data = PublicationOutputType::where('category_id', $category_id)->get();
        return response()->json($data);
    }

    public function getMediaOutputCategories()
    {
        $data = MediaOutputCategory::all();
        return response()->json($data);
    }

    public function getMediaOutputType($category_id)
    {
        $data = MediaOutputType::where('category_id', $category_id)->get();
        return response()->json($data);
    }

    public function getVideoOutputCategories()
    {
        $data = VideoOutputCategory::all();
        return response()->json($data);
    }

    public function getVideoOutputType()
    {
        $data = VideoOutputType::all();
        return response()->json($data);
    }

    public function getPartnerGroup()
    {
        $data = PartnerGroup::all();
        return response()->json($data);
    }

    public function getPartnerType()
    {
        $data = PartnerType::all();
        return response()->json($data);
    }

    public function getSupportingFileType()
    {
        $data = SupportingFileType::all();
        return response()->json($data);
    }
}
