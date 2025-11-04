<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $recentFiles = File::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_files' => File::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->count(),
            'new_files_today' => File::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->whereDate('created_at', today())
                ->count(),
            'documents_size' => File::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->whereIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'])
                ->sum('size'),
            'images_size' => File::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->where('mime_type', 'like', 'image/%')
                ->sum('size'),
            'videos_size' => File::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->where('mime_type', 'like', 'video/%')
                ->sum('size'),
            'others_size' => File::where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->whereNotIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'])
                ->where('mime_type', 'not like', 'image/%')
                ->where('mime_type', 'not like', 'video/%')
                ->sum('size'),
        ];
        
        $catalogs = Catalog::query()
            ->orderBy('name')
            ->withCount('files')
            ->limit(8)
            ->get();
        
        return view('dashboard.index', compact('recentFiles', 'stats', 'catalogs'));
    }
}
