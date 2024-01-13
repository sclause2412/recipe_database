<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    private function _norights()
    {
        return response()->json(['message' => __('You have no rights to use this search')], 403);
    }

    public function search(Request $request, string $area, string $model)
    {
        $select = false;
        if (!$request->exists('search')) {
            if ($request->exists('selected')) {
                $select = true;
            } else {
                return response()->json(['message' => __('This search is not valid')], 400);
            }
        }
        $search = $request->get('search');
        $selected = $request->get('selected', []);
        $maxresults = intval($request->get('maxresults', 10));
        $new = false;

        switch ($area) {
            case 'recipe':
                switch ($model) {
                    case 'category':
                        $idcol = 'id';
                        $display = fn($r) => $r->name;
                        $fields = ['name'];
                        $new = true;
                        break;
                    case 'ingredient':
                        $idcol = 'id';
                        $display = fn($r) => $r->name;
                        $fields = ['name'];
                        $new = true;
                        break;
                    case 'unit':
                        $idcol = 'id';
                        $display = fn($r) => $r->name;
                        $fields = ['name', 'unit'];
                        $new = false;
                        break;
                    default:
                        return $this->_norights();
                }
                break;
            default:
                return response()->json(['message' => __('This search is not valid')], 400);
        }

        $class = '\\App\\Models\\' . ucfirst($model);
        if (class_exists($class)) {
            $model = new $class();

            if ($select) {
                $query = $model->whereIn($idcol, $selected);
            } else {
                $query = $model->search($fields, $search);
                if ($maxresults > 0) {
                    $query = $query->take($maxresults);
                }
            }

            $query = $query->get();
            $result = [];

            foreach ($query as $e) {
                array_push($result, ['id' => $e->$idcol, 'name' => $display($e)]);
                $new = false;
            }

            if ($new && $search) {
                array_push($result, ['id' => $search, 'name' => __('New: ') . $search]);
            }

            return response()->json($result);
        } else {
            return response()->json(['message' => __('Model :model not found', ['model' => $model])], 400);
        }
    }
}
