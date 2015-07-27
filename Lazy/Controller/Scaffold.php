<?php
namespace Lazy\Controller;

class Scaffold extends Base
{
    function do_table_select($db, $table, $filter, $page_info, $return = TRUE)
    {
        list($where, $bindings) = $filter ? array($filter['where'], $filter['bindings']) : array('', array());
        $sort = ($filter && !empty($filter['sort'])) ? 'ORDER BY ' . $filter['sort'] : '';
        $page = !empty($page_info['page']) ? $page_info['page'] : 1;
        $per_page = !empty($page_info['per']) ? $page_info['per'] : 20;
        $records = $db->query("SELECT * FROM $table $where $sort LIMIT " . ($page - 1) * $per_page . ",$per_page", $bindings);
        list($total) = $db->query("SELECT COUNT(*) total FROM $table $where", $bindings);
        $page_structure = $this->paginator($page, ceil($total['total']/$per_page), 5);

        if($return)
            return array('records' => $records, 'page_structure' => $page_structure);

        /* below for lazy guy only */
        $page_html = $this->default_paginator_html($page_structure, $page);
        $pks = $this->_get_table_pk($db, $table);
        $this->set_main('scaffold/table_select.tmpl');
        foreach ($records as $i => $record)
        {
            $id_param = array();
            foreach ($pks as $pk)
            {
                $id_param[$pk] = $record[$pk];
            }
            $records[$i]['_id'] = http_build_query($id_param);
        }

        $this->output(
            array(
                'records' => $records,
                'table' => $table,
                'page_html' => $page_html,
            )
        );
    }

    function _get_table_pk($db, $table)
    {
        $pks_verbose = $db->query("SHOW INDEX FROM $table WHERE Key_name='PRIMARY'");
        $pks = array();
        foreach ($pks_verbose as $pk_record)
        {
            $pks[] = $pk_record['Column_name'];
        }
        return $pks;
    }

    function _get_table_columns($db, $table)
    {
        $pks_verbose = $db->query("SHOW COLUMNS FROM $table");
        $columns = array();
        foreach ($pks_verbose as $pk_record)
        {
            $columns[] = $pk_record['Field'];
        }
        return $columns;

    }

    /* get row for pk */
    function do_table_select_row($db, $table, $params, $pks = null)
    {
        if(!$pks)
            $pks = $this->_get_table_pk($db, $table);
        $bindings = array();
        $where = 'WHERE ' . $this->prepare_select_binding_string($pks, $params, $bindings);
        $sql = "SELECT * FROM $table $where";
        list($record) = $db->query($sql, $bindings);
        return $record;
    }

    /* lazy only */
    function _do_table_before_delete($db, $table, $params)
    {
        $record = $this->do_table_select_row($db, $table, $params);
        $this->set_main('scaffold/table_delete.tmpl');
        $this->output(
            array(
                'record' => $record,
                'table' => $table,
            )
        );
    }

    function do_table_delete_row($db, $table, $params, $pks = null, $return = TRUE)
    {
        if(!$pks)
            $pks = $this->_get_table_pk($db, $table);
        $bindings = array();
        $where = 'WHERE ' . $this->prepare_update_binding_string($pks, $params, $bindings);
        $sql = "DELETE FROM $table $where LIMIT 1";
        $result = $db->execute($sql, $bindings);

        if($return)
            return $result;

        if($result)
            $this->redirect($this->generate_uri(array('random' => rand()), $_SERVER['HTTP_REFERER']));
        else
        {
            $msg = "Operation Failed!";
            $this->set_main('scaffold/table_delete.tmpl');
            $this->output(
                array(
                    'record' => $params,
                    'message' => $msg,
                    'table' => $table,
                )
            );
        }
    }

    /* caller should bake correctly */
    function do_table_delete($db, $table, $baked)
    {
        $where = $baked['where'];
        $bindings = $baked['bindings'];
        $sql = "DELETE FROM $table $where";
        return $db->execute($sql, $bindings);
    }

    /* lazy only */
    function _do_table_before_update($db, $table, $params)
    {
        $record = $this->do_table_select_row($db, $table, $params);
        $pks = $this->_get_table_pk($db, $table);
        $this->set_main('scaffold/table_update.tmpl');
        $this->output(
            array(
                'record' => $record,
                'pks' => $pks,
                'table' => $table,
            )
        );
    }

    function do_table_update_row($db, $table, $params, $columns, $pks = null, $include = TRUE, $return = TRUE)
    {
        if(!$pks)
            $pks = $this->_get_table_pk($db, $table);

        $bindings = array();
        if($include)
        {
            $binding_string = $this->prepare_update_binding_string($columns, $params, $bindings);
        }
        else
        {
            $all_columns = $this->_get_table_columns($db, $table);
            foreach ($all_columns as $i => $column)
            {
                if(in_array($column, $pks) || in_array($column, $columns))
                {
                    unset($all_columns[$i]);
                }
            }
            $binding_string = $this->prepare_update_binding_string($all_columns, $params, $bindings);
        }

        $where = 'WHERE ' . $this->prepare_update_binding_string($pks, $params, $bindings);
        $sql = "UPDATE $table SET $binding_string $where LIMIT 1";
        $result = $db->execute($sql, $bindings);

        if($return)
            return $result;

        if($result)
            $this->redirect($this->generate_uri(array('random' => rand()), $_SERVER['HTTP_REFERER']));
        else
        {
            $msg = "Operation Failed!";
            $this->set_main('scaffold/table_update.tmpl');
            $this->output(
                array(
                    'record' => $params,
                    'pks' => $pks,
                    'message' => $msg,
                    'table' => $table,
                )
            );
        }
    }

    function do_table_update($db, $table, $filter)
    {
        $where = $filter['where'];
        $bindings = $filter['bindings'];
        $sql = "DELETE FROM $table $where";
        return $db->execute($sql, $bindings);
    }

    function _do_table_before_insert($db, $table, $params)
    {
        $all_columns = $this->_get_table_columns($db, $table);
        $pks = $this->_get_table_pk($db, $table);
        $this->set_main('scaffold/table_insert.tmpl');
        foreach ($all_columns as $column)
        {
            if(!isset($params[$column]))
                $params[$column] = '';
        }
        $this->output(
            array(
                'record' => $params,
                'pks' => $pks,
                'table' => $table,
            )
        );
    }

    function do_table_insert_row($db, $table, $params, $columns, $include = TRUE, $return = TRUE)
    {
        $bindings = array();
        if($include)
        {
            $binding_string = $this->prepare_insert_binding_string($columns, $params, $bindings);
        }
        else
        {
            $all_columns = $this->_get_table_columns($db, $table);
            foreach ($all_columns as $i => $column)
            {
                if(in_array($column, $columns))
                {
                    unset($all_columns[$i]);
                }
            }
            $binding_string = $this->prepare_insert_binding_string($all_columns, $params, $bindings);
        }

        $sql = "INSERT INTO $table $binding_string";
        $result = $db->execute($sql, $bindings);

        if($return)
            return $result;

        if($result)
            $this->redirect($this->generate_uri(array('random' => rand()), $_SERVER['HTTP_REFERER']));
        else
        {
            $msg = "Operation Failed!";
            $this->set_main('scaffold/table_insert.tmpl');
            $this->output(
                array(
                    'record' => $params,
                    'pks' => $pks,
                    'message' => $msg,
                    'table' => $table,
                )
            );
        }
    }

    function do_table_insert($db, $table, $baked)
    {
        $binding_string = $baked['binding_string'];
        $bindings = $baked['bindings'];
        $sql = "INSERT INTO $table VALUES $binding_string";
        return $db->execute($sql, $bindings);
    }
}


