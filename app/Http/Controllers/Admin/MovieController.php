<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_movies')->only(['index']);
        $this->middleware('permission:create_movies')->only(['create', 'store']);
        $this->middleware('permission:update_movies')->only(['edit', 'update']);
        $this->middleware('permission:delete_movies')->only(['delete', 'bulk_delete']);

    }// end of __construct

    public function index()
    {
        return view('admin.movies.index');

    }// end of index


    public function data()
    {
        $Movies = Movie::with(['genres']);

        return DataTables::of($Movies)
            ->addColumn('record_select', 'admin.movies.data_table.record_select')
            ->addColumn('genres', function (Movie $movie){
                return view('admin.movies.data_table.genres',compact('movie'));
            })
            ->editColumn('created_at', function (Movie $Movies) {
                return $Movies->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.movies.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data



    public function destroy(Movie $Movies)
    {
        $this->delete($Movies);
        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $Movies = Movie::FindOrFail($recordId);
            $this->delete($Movies);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));

    }// end of bulkDelete

    private function delete(Movie $Movies)
    {
        $Movies->delete();

    }// end of delete
}
