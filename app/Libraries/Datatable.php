<?php

namespace App\Libraries;

class Datatable
{
    static function output($columns, $data)
    {
        $out = [];
        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = [];

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                if (isset($column['formatter'])) {
                    if (empty($column['select'])) {
                        $row[$column['dt']] = $column['formatter']($data[$i]);
                    } else {
                        $row[$column['dt']] = $column['formatter']($data[$i][$column['select']], $data[$i]);
                    }
                } else {
                    if (!empty($column['select'])) {
                        $row[$column['dt']] = $data[$i][$columns[$j]['select']];
                    } else {
                        $row[$column['dt']] = "";
                    }
                }
            }
            $out[] = $row;
        }
        return $out;
    }
    static function pluck($columns, $prop)
    {
        $out = [];
        foreach ($columns as $k => $v) {
            if (empty($columns[$k][$prop])) {
                continue;
            }
            $out[$k] = $columns[$k][$prop];
        }
        return $out;
    }
    static function order($req, $columns)
    {
        $order_by = [];
        if (isset($req['order']) && count($req['order'])) {
            $dt_columns = self::pluck($columns, 'dt');
            for ($i = 0, $ien = count($req['order']); $i < $ien; $i++) {
                $column_order_index = intval($req['order'][$i]['column']);
                $column_request = $req['columns'][$column_order_index];
                $column_index = array_search($column_request['data'], $dt_columns);
                $column = $columns[$column_index];
                if ($column_request['orderable'] == 'true') {
                    $dir = $req['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $order_by[] = '' . $column['cond'] . ' ' . $dir;
                }
            }
        }
        return count($order_by) > 0 ? implode(', ', $order_by) : NULL;
    }
    function globalSearch($req, $dt_columns, $columns)
    {
        $str = $req['search']['value'];
        $global_search = [];
        foreach ($req['columns'] as $k => $v) {
            $request_column = $req['columns'][$k];
            $column_index = array_search($request_column['data'], $dt_columns);
            if (!empty($column_index)) {
                $column = $columns[$column_index];
                if ($request_column['searchable'] == 'true') {
                    if (!empty($column['cond'])) {
                        $global_search[$column['cond']] = $str;
                    }
                }
            }
        }
        return $global_search;
    }
    function columnSearch($req, $dt_columns, $columns)
    {
        $column_search = [];
        for ($i = 0, $ien = count($req['columns']); $i < $ien; $i++) {
            $request_column = $req['columns'][$i];
            $column_index = array_search($request_column['data'], $dt_columns);
            if (!empty($column_index)) {
                $column = $columns[$column_index];
                $str = $request_column['search']['value'];
                if ($request_column['searchable'] == 'true' && $str != '') {
                    if (!empty($column['cond'])) {
                        $column_search[$column['cond']] = $str;
                    }
                }
            }
        }
        return $column_search;
    }
    function run($model1,$model2, $req, $columns)
    {
        
        $dt_columns = self::pluck($columns, 'dt');
        $query = clone $model1;
        $filter = clone $model2;
        if (isset($req['start']) && $req['length'] != -1) {
            $query->limit($req['length'], $req['start']);
            $filter->limit($req['length'], $req['start']);
        }

        //order start
        if (isset($req['order']) && count($req['order'])) {
            $order_by = self::order($req, $columns);
            $order_by != NULL ? $query->orderBy($order_by) : '';
            $order_by != NULL ? $filter->orderBy($order_by) : '';
        }
        //order end
        
        //start global search
        $global_search = [];
        if (isset($req['search']) && $req['search']['value'] != '') {
            $global_search = self::globalSearch($req, $dt_columns, $columns);
            if (count($global_search) > 0) {
                $query->groupStart()->orLike($global_search)->groupEnd();
                $filter->groupStart()->orLike($global_search)->groupEnd();
            }
        }
        //end global search
        
        //start column search
        $column_search = [];
        if (isset($req['columns'])) {
            $column_search = self::columnSearch($req, $dt_columns, $columns);
            if (count($column_search) > 0) {
                $query->like($column_search);
                $filter->like($column_search);
            }
        }

        $data = $query->get()->getResultArray();
        $data_filter_total = $filter->countAllResults();
        $data_total = $model1->countAllResults();

        return [
            // "see" => $filter->builder()->getCompiledSelect(),
            "draw" => isset($req['draw']) ? intval($req['draw']) : 0,
            "recordsTotal"    => intval($data_total),
            "recordsFiltered" => intval($data_filter_total),
            "data"            => self::output($columns, $data)
        ];
    }
}
