<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\PageTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageTableController extends Controller
{
    public function cloneData(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $tables = PageTable::whereIn('id', $request->tables)->orderBy('order', 'asc')->get();
                foreach ($tables as $table) {
                    Helper::resolveOrder(
                        PageTable::class,
                        [
                            "page_id" => $request->page_current_id,
                            "group_id" => $table->group_id
                        ],
                        $table->order,
                    );
                    $newTable = new PageTable();
                    $newTable->page_id =  $request->page_current_id;
                    $newTable->group_id =  $table->group_id;
                    $newTable->order = $table->order;
                    $newTable->name =  $table->name . '_' . md5(rand());
                    $newTable->save();
                }
                $getGroupByTable = PageTable::where('page_id', $request->page_current_id)->groupBy('group_id')->pluck('group_id')->toArray();
                foreach ($getGroupByTable as $value) {
                    $getTables  = PageTable::where('page_id', $request->page_current_id)->where('group_id', $value)->orderBy('order', 'asc')->get();
                    foreach ($getTables as $key => $tbl) {
                        PageTable::where('id', $tbl->id)->update(['order' => $key + 1]);
                    }
                }

                return true;
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(Request $request)
    {
        PageTable::create($request->all());
        return true;
    }
}
