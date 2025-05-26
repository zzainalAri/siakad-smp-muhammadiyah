<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HasFile
{
    public function upload_file(Request $request, $column, $folder)
    {
        return $request->hasFile($column) ? $request->file($column)->store($folder) : null;
    }

    public function update_file(Request $request, Model $model, $column, $folder)
    {
        if ($request->hasFile($column)) {

            $this->delete_file($model, $column);

            $thumbnail = $request->file($column)->store($folder);
        } else {
            $thumbnail = $model->$column;
        }

        return $thumbnail;
    }

    public function delete_file(Model $model, $column)
    {
        if ($model->$column) {
            Storage::delete($model->$column);
        }
    }
}
